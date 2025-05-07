<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;
use Barryvdh\DomPDF\Facade\Pdf;

class CodigoController extends Controller
{
    public function exportarPDF()
{
    $codigos = Code::all();
    $pdf = Pdf::loadView('codigos.reporte', compact('codigos'));
    return $pdf->download('codigos-generados.pdf');
}
    public function generarcodigos ()
    {
        return view('codigos.codigo');
    }
}
