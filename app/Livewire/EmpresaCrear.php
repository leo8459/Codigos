<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Empresas;

class EmpresaCrear extends Component
{
    public $nombre, $sigla, $codigo_cliente;

    public function guardar()
    {
        $this->validate([
            'nombre' => 'required',
            'sigla' => 'required',
            'codigo_cliente' => 'required',
        ]);

        Empresas::create([
            'nombre' => $this->nombre,
            'sigla' => $this->sigla,
            'codigo_cliente' => $this->codigo_cliente,
        ]);

        session()->flash('message', 'Empresa creada exitosamente.');
        return redirect()->route('empresas.index'); // ruta del listado
    }

    public function render()
    {
        return view('livewire.empresa-crear');
    }
}

