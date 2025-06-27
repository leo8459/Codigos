<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: 8.5in 13in; /* Tamaño oficio */
            margin: 1in;       /* Márgenes seguros */
        }

        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
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
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #003366;
            color: white;
        }

        .footer {
            margin-top: auto;
            font-size: 10px;
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>
    <div>
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
        </table>
    </div>

    <div class="footer">
        Reporte generado automáticamente por el sistema de control de códigos – {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
