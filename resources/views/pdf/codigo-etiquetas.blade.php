<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        /* Tamaño de página: Oficio (Legal), orientación vertical */
        @page {
            size: legal portrait; /* 8.5 x 14 pulgadas */
            margin: 1cm; /* Margen de 1 cm en todos los bordes */
        }

        /* Estilo base del cuerpo del PDF */
        body {
            font-family: sans-serif; /* Usa fuente limpia como Arial */
            margin: 0;
            padding: 0;
        }

        /* Contenedor principal para centrar el contenido horizontalmente */
        .pagina {
            width: 100%; /* Usa el 100% del ancho disponible */
            display: flex; /* Flexbox para centrar */
            justify-content: center; /* Centra la tabla horizontalmente */
        }

        /* Tabla que organiza los códigos en filas y columnas */
        table {
            border-collapse: collapse; /* Quita los espacios entre celdas */
            margin: 0 auto; /* Centra la tabla en la página */
        }

        /* Cada celda representa una etiqueta de código */
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

        /* No mostrar borde inferior en la última fila */
        tr:last-child td {
            border-bottom: none;
        }

        /* No mostrar borde derecho en la última celda de cada fila */
        td:last-child {
            border-right: none;
        }

        /* Imagen del código de barras */
        img {
            width: 170px; /* Ancho del código de barras */
            height: 28px; /* Alto fijo */
            display: block; /* Elimina espacio extra alrededor */
            margin: 0 auto; /* Centra horizontalmente */
        }

        /* Texto del código debajo de la imagen */
        .codigo-texto {
            margin-top: 5px; /* Espacio entre imagen y texto */
            font-weight: bold; /* Texto en negrita */
            font-size: 12px; /* Tamaño de fuente pequeño */
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
                    <img src="data:image/png;base64,{{ $codigo->barcode_base64 }}" alt="{{ $codigo->codigo }}">
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
