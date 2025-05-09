<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 1cm;
        }

        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }

        .pagina {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        table {
            border-collapse: collapse;
        }

        td {
            width: 130px;
            /* Aproximadamente 6 cm */
            height: 70px;
            text-align: center;
            vertical-align: middle;
            padding-top: 5px;
            padding-bottom: 5px;
            padding-left: 12px;
            /* Aumenta el espacio a la izquierda */
            padding-right: 12px;
            /* Aumenta el espacio a la derecha */
            border-right: 1px dashed #999;
            border-bottom: 1px dashed #999;
        }

        tr:last-child td {
            border-bottom: none;
        }

        td:last-child {
            border-right: none;
        }

        img {
            width: 170px;
            height: 28px;
            display: block;
            margin: 0 auto;
        }

        .codigo-texto {
            margin-top: 5px;
            font-weight: bold;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="pagina">
        <table>
            <tbody>
                @foreach ($codigos as $codigo)
                    <tr>
                        @for ($i = 0; $i < 4; $i++)
                            <td>
                                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $codigo->barcode))) }}" alt="Barras">
                                <div class="codigo-texto">{{ $codigo->codigo }}</div>
                            </td>
                        @endfor
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
