<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;
use Barryvdh\DomPDF\Facade\Pdf;

class CodigoController extends Controller
{
    public function exportarPDF()
{
    $ids = session('codigos_generados');

    if (!$ids || empty($ids)) {
        return redirect()->back()->with('error', 'No se encontraron códigos generados recientemente.');
    }

    $codigos = \App\Models\Code::whereIn('id', $ids)->get();

    $pdf = Pdf::loadView('codigos.reporte', compact('codigos'))
              ->setPaper('legal', 'portrait'); // ← esto define tamaño oficio vertical

    session()->forget('codigos_generados');

    return $pdf->download('codigos-generados.pdf');
}



    public function generarcodigos ()
    {
        return view('codigos.codigo');
    }
}
