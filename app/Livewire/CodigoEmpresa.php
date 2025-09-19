<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CodigoEmpresas;
use App\Models\Empresas;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Milon\Barcode\DNS1D;
use Livewire\WithPagination;
use App\Models\Eventos; // Asegúrate de importar el modelo Eventos
use Illuminate\Support\Facades\Auth;




class CodigoEmpresa extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // usa Bootstrap para el estilo de paginación

    public $empresa_id;
   public $cantidad   = 1;
    public $inicio     = null;   // 👈 nuevo
    public $reset      = false;  // 👈 nuevo (checkbox “Resetear”)    // public $codigos;
 protected $rules = [
        'empresa_id' => 'required|exists:empresa,id',
        'cantidad'   => 'required|integer|min:1|max:100000',
        'inicio'     => 'nullable|integer|min:1|max:99999',
        'reset'      => 'boolean',
    ];
    // public function mount()
    // {
    //     $this->codigos = CodigoEmpresas::with('empresa')->get();
    // }

   public function generar()
{
    $this->validate();

    $empresa = Empresas::findOrFail($this->empresa_id);

    /* 1️⃣  Detectar “reset” */
    if ($this->reset) {
        // Avanzar letra: A→B→C…  (si llega a Z vuelve a A)
        $empresa->ciclo = chr(((ord($empresa->ciclo) - 65 + 1) % 26) + 65); // 65 = 'A'
        $empresa->secuencia = 0;  // reiniciar folio
        $empresa->save();
    }

    /* 2️⃣  Determinar punto de partida */
    $start = $this->inicio ?: $empresa->secuencia + 1;
    $end   = $start + $this->cantidad - 1;

    /* 3️⃣  Verificar colisión: misma letra de ciclo */
    $duplicados = CodigoEmpresas::where('empresa_id', $empresa->id)
        ->whereBetween(
            DB::raw("CAST(substr(codigo, 2 + length('$empresa->codigo_cliente') + 1, 5) AS INTEGER)"), // +1 salta la letra
            [$start, $end]
        )
        ->whereRaw("substr(codigo, 2 + length('$empresa->codigo_cliente'), 1) = ?", [$empresa->ciclo])
        ->exists();

    if ($duplicados) {
        session()->flash('message', 'Rango ocupado para el ciclo '.$empresa->ciclo.
            '. Elige otro inicio o realiza un nuevo reset.');
        return;
    }

    /* 4️⃣  Generar códigos */
    $generator     = new \Milon\Barcode\DNS1D();
    $lote          = [];

    for ($n = $start; $n <= $end; $n++) {
        $numero = str_pad($n, 5, '0', STR_PAD_LEFT);
        $codigo = 'C'.$empresa->codigo_cliente.$empresa->ciclo.$numero.'BO';

        $png     = $generator->getBarcodePNG($codigo, 'C128', 2, 40);
        $archivo = $codigo.'.png';
        $ruta    = storage_path("app/public/barcodes/$archivo");
        if (!is_dir(dirname($ruta))) mkdir(dirname($ruta), 0755, true);
        file_put_contents($ruta, base64_decode($png));

        $lote[] = [
            'codigo'     => $codigo,
            'barcode'    => $archivo,
            'empresa_id' => $empresa->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        Eventos::create([
            'accion'      => 'Generación de código de contrato',
            'descripcion' => "Se generó el código $codigo",
            'codigo'      => $codigo,
            'user_id'     => Auth::id(),
        ]);
    }

    DB::table('codigoempresa')->insert($lote);

    /* 5️⃣  Actualizar contador y redirigir al PDF */
    $empresa->secuencia = $end;
    $empresa->save();

    $ids = CodigoEmpresas::whereIn('codigo', collect($lote)->pluck('codigo'))
        ->pluck('id')->join(',');

    return redirect()->route('codigos.pdf', ['ids' => $ids]);
}





    public function render()
    {
        return view('livewire.codigo-empresa', [
            'codigos' => CodigoEmpresas::with('empresa')
                ->orderBy('id', 'desc')
                ->paginate(10),
            'empresas' => Empresas::orderBy('codigo_cliente')->get(),
        ]);
    }
}
