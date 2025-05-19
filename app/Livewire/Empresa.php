<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Empresas;

class Empresa extends Component
{
    // Propiedades públicas (se reflejan en la vista)
    public $empresas;
    public $empresa_id = null;
    public $nombre = '';
    public $sigla  = '';
    public $codigo_cliente = '';

    // Configuración de la tabla
    public $titulo   = 'Empresas';
    public $rutaCrear = 'empresas.create';
    public $columnas = ['Nombre', 'Sigla', 'Código Cliente'];
    public $campos   = ['nombre', 'sigla', 'codigo_cliente'];
    public $eliminar = true;

    /* --------------------------------------------------------------------- */
    public function mount()
    {
        // Para tener la colección completa (no es imprescindible)
        $this->empresas = Empresas::all();
    }

    /* --------------------------------------------------------------------- */
    public function editar($id)
    {
        $empresa = Empresas::findOrFail($id);

        // Rellena las propiedades que la vista usa
        $this->fill([
            'empresa_id'     => $empresa->id,
            'nombre'         => $empresa->nombre,
            'sigla'          => $empresa->sigla,
            'codigo_cliente' => $empresa->codigo_cliente,
        ]);

        // Dispara el browser-event (escuchado por JS)
        $this->dispatch('abrir-modal-edicion');
    }

    /* --------------------------------------------------------------------- */
    public function actualizar()
    {
        $this->validate([
            'nombre'         => 'required',
            'sigla'          => 'required',
            'codigo_cliente' => 'required',
        ]);

        if (!$this->empresa_id) {
            session()->flash('error', 'No se encontró la empresa a actualizar.');
            return;
        }

        $empresa = Empresas::findOrFail($this->empresa_id);

        $empresa->update([
            'nombre'         => $this->nombre,
            'sigla'          => $this->sigla,
            'codigo_cliente' => $this->codigo_cliente,
        ]);

        session()->flash('message', 'Empresa actualizada exitosamente.');

        // Limpia y cierra el modal
        $this->reset(['empresa_id', 'nombre', 'sigla', 'codigo_cliente']);
        $this->dispatch('cerrar-modal-edicion');
    }

    /* --------------------------------------------------------------------- */
    public function eliminar($id)
    {
        Empresas::destroy($id);
        session()->flash('message', 'Empresa eliminada.');
    }

    /* --------------------------------------------------------------------- */
    public function render()
    {
        // Paginación (10 por página)
        $registros = Empresas::latest()->paginate(10);

        return view('livewire.empresa', [
            'registros' => $registros,
        ]);
    }
}
