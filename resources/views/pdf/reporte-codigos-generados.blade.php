<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: 8.5in 13in;
            margin: 1in;
        }

        html, body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        h2, h4, p {
            text-align: center;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }

        th, td {
            border: 1px solid #444;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #003366;
            color: white;
        }

        tfoot tr th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            font-size: 10px;
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>
    <h2>Agencia Boliviana de Correos</h2>
    <h4>Reporte de Códigos Generados</h4>
    <p><strong>Rango de fechas:</strong> {{ $rango['desde'] }} al {{ $rango['hasta'] }}</p>

    <table>
        <thead>
            <tr>
                <th>Nombre Empresa</th>
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
        <tfoot>
            <tr>
                <th>Total General</th>
                <th>{{ $totalGeneral }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Reporte generado automáticamente por el sistema de control de códigos – {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
