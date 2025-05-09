<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Code;
use Illuminate\Support\Facades\Storage;
use DNS1D;
use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;

class Codigo extends Component
{
    use WithPagination;

    public $cantidad;
    public $sufijo = 'LPB'; // Valor por defecto
    protected $paginationTheme = 'bootstrap';
    public $fechaInicio;
    public $fechaFin;
    public $filtroSufijo;

    



    public function generar()
    {
        $this->validate([
            'cantidad' => 'required|integer|min:1',
        ]);
    
        $ultimo = Code::where('codigo', 'LIKE', '%'.$this->sufijo)
        ->orderBy('id', 'desc')
        ->first();
        if ($ultimo) {
            // Extrae el número entre EN y el sufijo
            preg_match('/EN(\d+)' . $this->sufijo . '/', $ultimo->codigo, $match);
            $numeroInicio = isset($match[1]) ? (int) $match[1] : 0;
        } else {
            $numeroInicio = 0;
        }
            
        $idsGenerados = [];
    
        for ($i = 1; $i <= $this->cantidad; $i++) {
            $numero = str_pad($numeroInicio + $i, 6, '0', STR_PAD_LEFT);
            $codigo = "EN{$numero}{$this->sufijo}";
    
            $barcode = DNS1D::getBarcodePNG($codigo, 'C128');
            $filename = "barcodes/{$codigo}.png";
            Storage::disk('public')->put($filename, base64_decode($barcode));
    
            $nuevo = Code::create([
                'codigo' => $codigo,
                'barcode' => $filename,
            ]);
    
            $idsGenerados[] = $nuevo->id;
        }
    
        // Guarda los IDs generados en sesión
        session()->put('codigos_generados', $idsGenerados);
    
        // Redirige al PDF
        return redirect()->route('generar.pdf');
    }

   public function render()
{
    $query = Code::query();

    if ($this->fechaInicio && $this->fechaFin) {
        $fechaInicio = \Carbon\Carbon::parse($this->fechaInicio)->startOfDay(); // 00:00:00
        $fechaFin = \Carbon\Carbon::parse($this->fechaFin)->endOfDay();         // 23:59:59
    
        $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
    }
    

    if ($this->filtroSufijo) {
        $query->where('codigo', 'LIKE', '%'.$this->filtroSufijo);
    }

    $codigos = $query->latest()->paginate(20);

    return view('livewire.codigo', compact('codigos'));
}

public function exportarPDF()
{
    $query = Code::query();

    if ($this->fechaInicio && $this->fechaFin) {
        $fechaInicio = \Carbon\Carbon::parse($this->fechaInicio)->startOfDay(); // 00:00:00
        $fechaFin = \Carbon\Carbon::parse($this->fechaFin)->endOfDay();         // 23:59:59
    
        $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
    }
    

    if ($this->filtroSufijo) {
        $query->where('codigo', 'LIKE', '%'.$this->filtroSufijo);
    }

    $codigos = $query->get();

    // Agrupar por sufijo (IATA)
    $resumen = $codigos->groupBy(function ($item) {
        return substr($item->codigo, -3); // Últimos 3 caracteres
    })->map(function ($group) {
        return $group->count();
    });

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.reporte-resumen-iata', [
        'resumen' => $resumen,
        'fechaInicio' => $this->fechaInicio,
        'fechaFin' => $this->fechaFin,
    ])->setPaper('letter', 'portrait');

    return response()->streamDownload(function () use ($pdf) {
        echo $pdf->stream();
    }, 'reporte_resumen_iata.pdf');
}

}
