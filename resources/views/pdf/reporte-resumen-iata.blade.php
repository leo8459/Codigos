<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen por Código IATA</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            margin: 40px;
        }

        h1, h3 {
            text-align: center;
            margin-bottom: 5px;
        }

        p {
            margin: 5px 0;
            text-align: center;
        }

        .table-container {
            margin-top: 30px;
            width: 100%;
        }

        table {
            width: 60%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        th {
            background-color: #004080;
            color: white;
            font-weight: bold;
        }

        th, td {
            border: 1px solid #999;
            padding: 8px 12px;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>

    <h1>Agencia Boliviana de Correos</h1>
    <h3>Reporte de Códigos Generados</h3>

    <p><strong>Rango de fechas:</strong>
        {{ $fechaInicio ?? '---' }} &nbsp; al &nbsp; {{ $fechaFin ?? '---' }}</p>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Código IATA</th>
                    <th>Total Generados</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resumen as $iata => $total)
                    <tr>
                        <td>{{ $iata }}</td>
                        <td>{{ $total }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No se encontraron datos para los filtros seleccionados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <footer>
        Reporte generado automáticamente por el sistema de control de códigos – {{ now()->format('d/m/Y H:i') }}
    </footer>
</body>
</html>
