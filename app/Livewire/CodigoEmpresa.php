<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CodigoEmpresas;
use App\Models\Empresas;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Milon\Barcode\DNS1D;




class CodigoEmpresa extends Component
{
    public $empresa_id;
    public $cantidad = 1;
    public $codigos;

    public function mount()
    {
        $this->codigos = CodigoEmpresas::with('empresa')->get();
    }

   public function generar()
{
    $this->validate([
        'empresa_id' => 'required|exists:empresa,id',
        'cantidad'   => 'required|integer|min:1|max:100000',
    ]);

    $empresa   = Empresas::findOrFail($this->empresa_id);
    $ultimo    = CodigoEmpresas::where('empresa_id', $empresa->id)->count();
    $generator = new \Milon\Barcode\DNS1D();

    $nuevosCodigos = [];

    for ($i = 1; $i <= $this->cantidad; $i++) {
        $numero  = str_pad($ultimo + $i, 5, '0', STR_PAD_LEFT);
        $codigo  = 'C' . $empresa->codigo_cliente . 'A' . $numero . 'BO';
        $archivo = $codigo . '.png';                               // →  C0003A00031BO.png
$ruta = storage_path('app/public/barcodes/' . $archivo);

if (!file_exists(dirname($ruta))) {
    mkdir(dirname($ruta), 0755, true);
}

if (!file_exists($ruta)) {
    $png = $generator->getBarcodePNG($codigo, 'C128', 2, 40);
    file_put_contents($ruta, base64_decode($png)); // decodificamos el base64 antes de guardar
}


        $nuevosCodigos[] = [
            'codigo'     => $codigo,
            'barcode'    => $archivo,     // sólo guardamos el nombre del archivo
            'empresa_id' => $empresa->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    DB::table('codigoempresa')->insert($nuevosCodigos);

    // …
    $codigosInsertados = collect($nuevosCodigos)->pluck('codigo');
    $idsGenerados      = CodigoEmpresas::whereIn('codigo', $codigosInsertados)
                        ->pluck('id')->join(',');

    return redirect()->route('codigos.pdf', ['ids' => $idsGenerados]);
}


    public function render()
    {
        return view('livewire.codigo-empresa', [
            'codigos' => $this->codigos,
            'empresas' => Empresas::all(),
        ]);
    }

      
}

