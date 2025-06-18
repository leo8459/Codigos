<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventosController extends Controller
{
     public function eventos ()
    {
        return view('eventos.eventosregistro');
    }
}
