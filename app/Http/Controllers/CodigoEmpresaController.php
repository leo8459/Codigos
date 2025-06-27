<?php

namespace App\Http\Controllers;

use App\Models\CodigoEmpresas;
use Barryvdh\DomPDF\Facade\Pdf;
use Milon\Barcode\DNS1D;
use Illuminate\Http\Request;
use App\Models\Empresas;

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
    public function reimprimir(Request $request)
{
    $request->validate([
        'codigos' => 'required|string',
    ]);

    $codigoStrings = explode(',', $request->codigos);
    $codigoLimpios = array_map('trim', $codigoStrings);

    $codigos = CodigoEmpresas::whereIn('codigo', $codigoLimpios)->get();

    if ($codigos->isEmpty()) {
        return back()->with('error', 'No se encontraron códigos válidos.');
    }

    foreach ($codigos as $c) {
        $rutaPng = storage_path('app/public/barcodes/' . $c->barcode);
        $c->barcode_base64 = file_exists($rutaPng)
            ? base64_encode(file_get_contents($rutaPng))
            : null;
    }

    $pdf = Pdf::loadView('pdf.codigo-etiquetas', compact('codigos'))
        ->setPaper('a4', 'portrait');

    return $pdf->download('reimpresion_codigos.pdf');
}
public function formularioReporte()
{
    $empresas = Empresas::orderBy('sigla')->get();
    return view('codigos.reporte-formulario', compact('empresas'));
}

public function generarReporte(Request $request)
{
    $request->validate([
        'desde' => 'required|date',
        'hasta' => 'required|date',
        'empresa_id' => 'nullable|exists:empresa,id',
    ]);

    $desde = $request->input('desde') . ' 00:00:00';
    $hasta = $request->input('hasta') . ' 23:59:59';

    $query = CodigoEmpresas::selectRaw('empresas.sigla as iata, empresas.codigo_cliente, count(*) as total')
        ->join('empresa as empresas', 'codigoempresa.empresa_id', '=', 'empresas.id')
        ->whereBetween('codigoempresa.created_at', [$desde, $hasta])
        ->groupBy('empresas.sigla', 'empresas.codigo_cliente')
        ->orderBy('empresas.codigo_cliente', 'asc');

    if ($request->empresa_id) {
        $query->where('empresas.id', $request->empresa_id);
    }

    $reporte = $query->get();
    $totalGeneral = $reporte->sum('total');

    $rango = [
        'desde' => $request->input('desde'),
        'hasta' => $request->input('hasta'),
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.reporte-codigos-generados', compact('reporte', 'rango'))
        ->setPaper('A4', 'portrait');

    return $pdf->download('reporte_codigos_generados.pdf');
}

}
