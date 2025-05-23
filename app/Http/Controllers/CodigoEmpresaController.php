<?php

namespace App\Http\Controllers;

use App\Models\CodigoEmpresas;
use Barryvdh\DomPDF\Facade\Pdf;
use Milon\Barcode\DNS1D;
use Illuminate\Http\Request;

class CodigoEmpresaController extends Controller
{
    public function crearcodigo()
    {
        return view('codigos.codigo-empresa'); // Nueva vista para crear
    }



    public function generarPDF(string $ids)
    {
        // Aumenta el tiempo de ejecución permitido a 5 minutos
        ini_set('memory_limit', '4096M'); // 2 GB, ajusta según tus recursos
        set_time_limit(6000000);              // 10 minutos si fuera necesario


        $idsArray = explode(',', $ids);
        $codigos  = CodigoEmpresas::whereIn('id', $idsArray)->get();

        foreach ($codigos as $c) {
            $rutaPng = storage_path('app/public/barcodes/' . $c->barcode);
            $c->barcode_base64 = base64_encode(file_get_contents($rutaPng));
        }

        $pdf = Pdf::loadView('pdf.codigo-etiquetas', compact('codigos'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('codigos_generados.pdf');
    }
}
