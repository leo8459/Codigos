<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index ()
    {
        return view('codigos.empresa');
    }
    public function create()
    {
        return view('codigos.empresa-crear'); // Nueva vista para crear
    }
}
