<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Eventos;
use App\Models\User;

class Eventosregistro extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $searchUserId = '';

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $query = Eventos::query()->with('user');

        if ($this->searchTerm) {
            $query->where('codigo', 'like', '%' . $this->searchTerm . '%');
        }

        if ($this->searchUserId) {
            $query->where('user_id', $this->searchUserId);
        }

        $admisiones = $query->latest()->paginate(10);
        $usuarios = User::orderBy('name')->get();

        return view('livewire.eventosregistro', compact('admisiones', 'usuarios'));
    }
}
