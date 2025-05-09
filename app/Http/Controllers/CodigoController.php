<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;



class CodigoController extends Controller
{
    public function exportarPDF()
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 600);
    
        $ids = session('codigos_generados');
    
        if (!$ids || empty($ids)) {
            return redirect()->back()->with('error', 'No se encontraron códigos generados recientemente.');
        }
    
        $pdfPath = public_path('pdfs');
        File::ensureDirectoryExists($pdfPath); // crea /pdfs si no existe
        File::cleanDirectory($pdfPath); // limpia PDFs anteriores
    
        $chunkSize = 1000;
        $chunks = array_chunk($ids, $chunkSize);
    
        $zipPath = public_path("pdfs/codigos_lotes.zip");
        if (file_exists($zipPath)) {
            unlink($zipPath); // borra ZIP anterior si existía
        }
    
        $zip = new \ZipArchive;
        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($chunks as $index => $loteIds) {
                $codigos = \App\Models\Code::whereIn('id', $loteIds)->get();
    
                $pdf = Pdf::loadView('codigos.reporte', compact('codigos'))
                          ->setPaper('legal', 'portrait');
    
                $nombreArchivo = "lote_" . str_pad($index + 1, 2, '0', STR_PAD_LEFT) . ".pdf";
                $archivoPDF = $pdfPath . DIRECTORY_SEPARATOR . $nombreArchivo;
    
                $pdf->save($archivoPDF);
                $zip->addFile($archivoPDF, $nombreArchivo);
            }
    
            $zip->close();
            session()->forget('codigos_generados');
    
            return response()->download($zipPath)->deleteFileAfterSend(true);
        }
    
        return back()->with('error', 'No se pudo generar el archivo ZIP.');
    }



    public function generarcodigos ()
    {
        return view('codigos.codigo');
    }
}
