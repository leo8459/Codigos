<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 8px; text-align: center; }
        th { background-color: #003366; color: white; }
        .center { text-align: center; }
        .footer { margin-top: 30px; font-size: 12px; text-align: center; color: #555; }
    </style>
</head>
<body>
    <h2 class="center">Agencia Boliviana de Correos</h2>
    <h4 class="center">Reporte de Códigos Generados</h4>
    <p class="center">
        <strong>Rango de fechas:</strong> {{ $rango['desde'] }} al {{ $rango['hasta'] }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Código IATA</th>
                <th>Total Generados</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reporte as $fila)
                <tr>
                    <td>{{ $fila->iata }}</td>
                    <td>{{ $fila->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Reporte generado automáticamente por el sistema de control de códigos – {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
