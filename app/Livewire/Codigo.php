<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Code;
use Illuminate\Support\Facades\Storage;
use DNS1D;
use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

class Codigo extends Component
{
    use WithPagination;

    public $cantidad;
    public $sufijo = 'LPB'; // Valor por defecto
    protected $paginationTheme = 'bootstrap';

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
        $codigos = Code::latest()->paginate(20);
        return view('livewire.codigo', compact('codigos'));
    }
}
