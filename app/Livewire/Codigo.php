<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Code;
use Illuminate\Http\Request;
use DNS1D;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image; // requiere intervention/image
class Codigo extends Component
{
    public $cantidad;

    public function generar()
    {
        $this->validate([
            'cantidad' => 'required|integer|min:1',
        ]);
    
        $ultimo = Code::orderBy('id', 'desc')->first();
        $numeroInicio = $ultimo ? (int) substr($ultimo->codigo, 2, 6) : 0;
    
        for ($i = 1; $i <= $this->cantidad; $i++) {
            $numero = str_pad($numeroInicio + $i, 6, '0', STR_PAD_LEFT);
            $codigo = "EN{$numero}LP";
    
            $barcode = DNS1D::getBarcodePNG($codigo, 'C128');
    
            $filename = "barcodes/{$codigo}.png";
            Storage::disk('public')->put($filename, base64_decode($barcode));
    
            Code::create([
                'codigo' => $codigo,
                'barcode' => $filename,
            ]);
        }
    
        session()->flash('message', "{$this->cantidad} códigos generados correctamente.");
    }
    public function render()
    {
        return view('livewire.codigo');
    }
}
