<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Mpdf\Mpdf;

// Variables de contenido dinámico
// $firma = $_POST['firma'] ?? 'null.jpg'; //4.jpg
$firma = $_POST['firma'] ?? 'null.png'; //4.jpg
$firmaValida = $_POST['firmaValida'] ?? 'null.png'; //4.jpg
$firmaCoordinador = $_POST['firmaCoordinador'] ?? 'null.png'; //4.jpg
$nombre = $_POST['nombre'] ?? 'Nombre y Firma';
$nombreValida = $_POST['nombreValida'] ?? 'Nombre y Firma';
$nombreCoordinador = $_POST['nombreCoordinador'] ?? 'Nombre y Firma';
$pncPost = isset($_POST['pnc']) ? json_decode($_POST['pnc']) : [];

// Cargar imágenes en base64
$imgPath = __DIR__ . '/../../../sources/imgs/image.png'; 
$imgBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath));

$imgPath2 = __DIR__ . './../../firmas/' . $firma; //funciona con $imgPath2 = __DIR__ . './../../firmas/3.png.';

if (file_exists($imgPath2)) {
    $imgBase642 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath2));
} else {
    $imgBase642 = '';
}

$imgPath3 = __DIR__ . './../../firmas/' . $firmaValida; //funciona con $imgPath2 = __DIR__ . './../../firmas/3.png.';

if (file_exists($imgPath3)) {
    $imgBase643 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath3));
} else {
    $imgBase643 = '';
}

$imgPath5 = __DIR__ . './../../firmas/' . $firmaCoordinador; //funciona con $imgPath2 = __DIR__ . './../../firmas/3.png.';

if (file_exists($imgPath5)) {
    $imgBase645 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath5));
} else {
    $imgBase645 = '';
}

//PNC
$pnc = [];

// Crear objeto para cada fila de PNC
function crearPNC(string $folio, string $fecha, string $especificacion, string $accion, string $numero, bool $eliminar, ?string $imgVerifica, ?string $imgLibera): stdClass {
    $obj = new stdClass();
    $obj->folio = $folio;
    $obj->fecha = $fecha;
    $obj->especificacion = $especificacion;
    $obj->accion = $accion;
    $obj->numero = $numero;
    $obj->eliminar = $eliminar;

    // Firma verifica
    if (!empty($imgVerifica)) {
        $rutaVerifica = __DIR__ . './../../firmas/' . $imgVerifica;
        $obj->firmaVerifica = file_exists($rutaVerifica)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($rutaVerifica))
            : '';
    } else {
        $obj->firmaVerifica = '';
    }

    // Firma libera
    if (!empty($imgLibera)) {
        $rutaLibera = __DIR__ . './../../firmas/' . $imgLibera;
        $obj->firmaLibera = file_exists($rutaLibera)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($rutaLibera))
            : '';
    } else {
        $obj->firmaLibera = '';
    }

    return $obj;
}

// Armar arreglo de PNCs desde $pncPost
foreach ($pncPost as $p) {
    $pnc[] = crearPNC(
        $p->folio ?? '',
        $p->fecha ?? '',
        $p->especificacion ?? '',
        $p->accion ?? '',
        $p->numero ?? '',
        $p->eliminar ?? false,
        $p->imgVerifica ?? null,
        $p->imgLibera ?? null
    );
}

// Crear las filas de la tabla PNC
$filasPNCHtml = '';
$contador = 1;

foreach ($pnc as $actividad) {
    $filasPNCHtml .= '
        <tr>
            <td class="center">' . $contador . '</td>
            <td class="center">' . htmlspecialchars($actividad->folio) . '</td>
            <td class="center">' . htmlspecialchars($actividad->fecha) . '</td>
            <td class="center">' . htmlspecialchars($actividad->especificacion) . '</td>
            <td class="center">' . htmlspecialchars($actividad->accion) . '</td>
            <td class="center">' . htmlspecialchars($actividad->numero) . '</td>
            <td class="center">' . ($actividad->eliminar ? 'X' : '') . '</td>
            <td class="center">' . (!$actividad->eliminar ? 'X' : '') . '</td>
            <td class="center">
                <img src="' . htmlspecialchars($actividad->firmaVerifica) . '" alt="Firma Verifica" style="width:80px; height:auto;">
            </td>
            <td class="center">
                <img src="' . htmlspecialchars($actividad->firmaLibera) . '" alt="Firma Libera" style="width:80px; height:auto;">
            </td>
        </tr>';
    $contador++;
}

//Logos
$imgPath4 = __DIR__ . '/../../../sources/imgs/logo-ITM.png'; 
$imgBase644 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath4));

// Crear instancia de mPDF
$mpdf = new Mpdf([
    'format' => 'letter',
    'orientation' => 'L',
    'margin_top' => 50,
    'margin_bottom' => 40,
    'margin_left' => 20,
    'margin_right' => 20
]);

// HEADER
$mpdf->SetHTMLHeader('
    <div style="text-align: center;">
        <img src="' . $imgBase64 . '" style="width: 100%;" alt="Encabezado">
    </div>
');

// FOOTER
$mpdf->SetHTMLFooter('
    <table class="documentos" width="100%" style="font-size: 10px;">
        <tr>
            <td>ISO 9001:2015 8.7 REV. 03</td>
            <td rowspan="4" style="text-align: center;">
            <img src="' . $imgBase644 . '" style="width: 60px;" alt="Logo ITM"></td>
            <td style="text-align: center;">ITMER-CA-PG-004-01</td>
        </tr>
        <tr><td>ISO 14001:2015</td><td></td></tr>
        <tr><td>ISO 5001:2018</td><td></td></tr>
        <tr><td>NMX-R-025-SCFI:2015</td><td></td></tr>
    </table>
');

// Contenido HTML
$html = '
<html>
<head>
    <style>
    body { 
        font-family: Arial, sans-serif; 
        background-color: transparent; 
    }
    h1 { 
        color: black; 
        font-size: 11px; 
    }
    h2 {
        font-size: 20px;
        margin: 20px; 
    }
    .center {
        text-align: center; 
    }
    img {
        width: 100%;
    }
    .datos {
        width: 100%;
        font-size: 10px;
        border-collapse: separate;
        border-spacing: 0 10px;
    }
    .celdas {
        font-size: 10px;
        text-align: justify;
        word-wrap: break-word;
        hyphens: auto;
    }
    .firma {
        width: 150px;
        margin: 40px 10px 0px 10px;
    }
    .negritas {
        font-weight: bold;
    }
    .borders {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
    }
    .borders th {
        background-color:rgb(214, 204, 204);
    }
    .borders th, .borders td {
        border: 1px solid black;
        padding: 5px;
    }
    </style>
</head>
<body>
    <h1 class="center">Identificación de Producto No Conforme</h1>

    <br>

    <table class="borders">
        <thead>
            <tr>
                <th class="center" rowspan="2">No.</th>
                <th class="center" rowspan="2">Folio</th>
                <th class="center" rowspan="2">Fecha</th>
                <th class="center" rowspan="2">Especificación incumplida</th>
                <th class="center" rowspan="2">Acción implantada</th>
                <th class="center" rowspan="2">No. de RAC</th>
                <th class="center" colspan="2">Elimina PNC</th>
                <th class="center" rowspan="2">Verifica</th>
                <th class="center" rowspan="2">Libera</th>
            </tr>
            <tr>
                <td class="center negritas">Sí</td>
                <td class="center negritas">No</td>
            </tr>
        </thead>
        <tbody>
            ' . $filasPNCHtml . '
        </tbody>
    </table>


    <table class="datos">
        <tr>
            <td colspan="4" class="center">
                <img class="firma" src="' . $imgBase642 . '" alt="Firma">
            </td>
            <td colspan="4" class="center">
                <img class="firma" src="' . $imgBase643 . '" alt="Firma">
            </td>
            <td colspan="4" class="center">
                <img class="firma" src="' . $imgBase645 . '" alt="Firma">
            </td>
        </tr>
        <tr>
            <td colspan="4" class="center">
                ' . $nombre . '
            </td>
            <td colspan="4" class="center">
                ' . $nombreValida . '
            </td>
            <td colspan="4" class="center">
                ' . $nombreCoordinador . '
            </td>
        </tr>
    </table>

    <h2 class="center">INSTRUCTIVO DE LLENADO</h2>

    <table class="borders">
        <thead>
            <tr>
                <th colspan="2" class="center"><span class="negritas">Número:</span></th>
                <th colspan="4" class="center"><span class="negritas">Descripción:</span></th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="2" span="2" class="center">1</td><td colspan="4">Anotar número consecutivo de 3 dígitos.</td></tr>
            <tr><td colspan="2" class="center">2</td><td colspan="4">Anotar el número de folio que le asigna cada responsable del punto de control al PNC identificado.</td></tr>
            <tr><td colspan="2" class="center">3</td><td colspan="4">Anotar la fecha en que se registra el PNC.</td></tr>
            <tr><td colspan="2" class="center">4</td><td colspan="4">Anotar la especificación incumplida que da origen al PNC (especificación no cumplida de los planes de calidad o cláusula no cumplida del contrato con el alumno).</td></tr>
            <tr><td colspan="2" class="center">5</td><td colspan="4">Anotar la Acción implantada para la eliminación del Producto No Conforme.</td></tr>
            <tr><td colspan="2" class="center">6</td><td colspan="4">Anotar el número de RAC correspondiente( en caso de que se requiera el RAC para el Producto No Conforme).</td></tr>
            <tr><td colspan="2" class="center">7</td><td colspan="4">Determinar si se Elimina el Producto No Conforme, colocando una “X” según sea el caso en la columna SI o NO.</td></tr>
            <tr><td colspan="2" class="center">8</td><td colspan="4">Cuando la columna 6 si elimine el Producto No Conforme, hacer la verificación de la eliminación del Producto no Conforme.</td></tr>
            <tr><td colspan="2" class="center">9</td><td colspan="4">Para Liberar el Producto No Conforme, anotar la fecha de liberación y la firma del Coordinador General de Calidad.</td></tr>
            <tr><td colspan="2" class="center">10</td><td colspan="4">Anotar el Nombre y firma del Jefe de Área quien registra el PNC.</td></tr>
            <tr><td colspan="2" class="center">11</td><td colspan="4">Anotar el nombre y firma del Subdirector de Área quien valida el registro del PNC.</td></tr>
            <tr><td colspan="2" class="center">12</td><td colspan="4">Anotar el nombre y firma del Coordinador General de Calidad. quien da el Visto Bueno al registro y control del PNC.</td></tr>
        </tbody>
    </table>
</body>
</html>
';


try {
    $mpdf->WriteHTML($html);
    $mpdf->Output('plan-auditoria.pdf', 'I');
} catch (\Mpdf\MpdfException $e) {
    echo $e->getMessage();
}
