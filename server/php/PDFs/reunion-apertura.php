<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Mpdf\Mpdf;

$horaInicio  = $_POST['horaInicio'] ?? '________';
$horaFinal   = $_POST['horaFinal'] ?? '________';
$dia         = $_POST['dia'] ?? '________';
$mes         = $_POST['mes'] ?? '________';
$año         = $_POST['anio'] ?? '________'; // se recomienda evitar la 'ñ' en nombres de variables
$area        = $_POST['area'] ?? '________';
$tecnologico = $_POST['tecnologico'] ?? 'Instituto Tecnológico de Mérida';

$participantesPost = isset($_POST['participantes']) ? json_decode($_POST['participantes']) : [];

$participantes = [];

function crearParticipante(string $nombre, string $cargo, ?string $imgNombre): stdClass {
    $obj = new stdClass();
    $obj->nombre = $nombre;
    $obj->cargo = $cargo;
    $obj->img = $imgNombre;

    if (!empty($imgNombre) && is_string($imgNombre)) {
        $ruta = __DIR__ . './../../firmas/' . $imgNombre;
        $obj->imgPath = $ruta;

        if (file_exists($ruta)) {
            $obj->imgBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($ruta));
        } else {
            $obj->imgBase64 = '';
        }
    } else {
        $obj->imgPath = '';
        $obj->imgBase64 = '';
    }

    return $obj;
}

foreach ($participantesPost as $p) {
    $participantes[] = crearParticipante($p->nombre, $p->cargo, $p->img);
}

// Crear las filas de la tabla dinámicamente a partir del arreglo $actividades
$filasPersonalHtml = '';
$contador = 1;

foreach ($participantes as $actividad) {
    $filasPersonalHtml .= '
        <tr>
            <td colspan="1" class="celdas center"><span>' . $contador . '</span></td>
            <td colspan="2" class="celdas center"><span>' . htmlspecialchars($actividad->nombre) . '</span></td>
            <td colspan="1" class="celdas center"><span>' . htmlspecialchars($actividad->cargo) . '</span></td>
            <td colspan="1" class="celdas center">
                <img class="firma-div" src="' . htmlspecialchars($actividad->imgBase64) . '" alt="Firma de ' . htmlspecialchars($actividad->nombre) . '">
            </td>
        </tr>';

    $contador++;
}

// Cargar imágenes en base64
$imgPath = __DIR__ . '/../../../sources/imgs/image.png'; 
$imgBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath));

$imgPath4 = __DIR__ . '/../../../sources/imgs/logo-ITM.png'; 
$imgBase644 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath4));

// Crear instancia de mPDF
$mpdf = new Mpdf([
    'format' => 'letter',
    'orientation' => 'p',
    'margin_top' => 40,
    'margin_bottom' => 30,
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
            <td>ISO 9001:2015, 9.2 REV. 04</td>
            <td rowspan="4" style="text-align: center;">
            <img src="' . $imgBase644 . '" style="width: 60px;" alt="Logo ITM"></td>
            <td style="text-align: center;">ITMER-CA-PG-003-03</td>
        </tr>
        <tr><td>ISO 14001:2015, 9.2</td><td></td></tr>
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
    .documentos {
        font-size: 10px;
        border-spacing: 0 5px;
    }
    .celdas {
        font-size: 11px;
        width: 16%;
        text-align: justify;
        word-wrap: break-word;
        hyphens: auto;
    }
    .subrayado {
        text-decoration: underline;
    }
    .border { 
        border: 1px solid black;
        padding: 15px 10px;
    }
    .negritas {
        font-weight: bold;
    }
    .firma {
        width: 150px;
        margin: 40px 10px 0px 10px;
    }
    .firma-div {
        width: 90px;
    }
    .overrayado {
        text-decoration: overline;
    }
    .logo-itm {
        width: 60px;
    }
    .borders {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
    }
    .borders th {
        background-color:rgb(214, 204, 204); /* Gris muy claro */
    }
    .borders th, .borders td {
        border: 1px solid black;
        padding: 5px;
    }
    </style>
</head>
<body>
    <h1 class="center">REUNIÓN DE APERTURA</h1>
    <table class="datos">
        <tr>
            <td colspan="5" class="celdas">
                En la Ciudad de <span class="negritas">Mérida, Yucatan</span> siendo las <span class="negritas">'. $horaInicio .'</span> hrs. del día <span class="negritas">'. $dia .'</span> del mes de <span class="negritas">'. $mes .'</span> del año <span class="negritas">'. $año .'</span> reunidos en <span class="negritas">'. $area .'</span> del <span class="negritas">'. $tecnologico .'</span> se lleva a cabo la REUNIÓN DE APERTURA para establecer los objetivos, alcance y participantes en la auditoría de interna al Sistema de Gestión de Integral <span class="negritas">(Sistema de Gestión integral SGI. (SGC, SGA, SGEn, EN IGUALDAD LABORAL Y NO DISCRIMINACIÓN, PROGRAMA 100% LIBRE DE PLÁSTICO DE UN SOLO USO)</span> practicada en esta institución, así como confirmar el plan, criterios y establecer los canales de comunicación con ésta.
            </td>
        </tr>
    </table>

    <table class="borders">
        <tr>
            <th colspan="1" class="celdas center">
                <span class="negritas">No.</span>
            </th>
            <th colspan="2" class="celdas center">
                <span class="negritas">Nombre</span>
            </th>
            <th colspan="1" class="celdas center">
                <span class="negritas">Cargo</span>
            </th>
            <th colspan="1" class="celdas center">
                <span class="negritas">firma:</span>
            </th>
        </tr>
        '. $filasPersonalHtml .'
    </table>

    <table>
      <tr class="">
        <td class=""></td>
      </tr>
    </table>

    <table class="datos">
        <tr>
            <td colspan="5" class="celdas">
                Siendo las <span class="negritas">'. $horaFinal .'</span> horas del día <span class="negritas">'. $dia .'</span> del mes de <span class="negritas">'. $mes .'</span> del año <span class="negritas">'. $año .'</span> se da por concluida la presente reunión recabando las firmas de los involucrados quienes dan fe de la misma.
            </td>
        </tr>
    </table>

    <h2 class="center">INSTRUCTIVO DE LLENADO</h2>

    <table class="borders">
        <tr>
            <th colspan="2" class="celdas center">
                <span class="negritas">Número:</span>
            </th>
            <th colspan="4" class="celdas center">
                <span class="negritas">Descripción:</span>
            </th>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">
                1
            </td>
            <td colspan="4" class="celdas">
              Anotar el nombre de la Ciudad en donde se realiza la reunión de apertura            
          </td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">
                2
            </td>
            <td colspan="4" class="celdas">
                Anotar la hora en que da inicio la reunión de apertura 
            </td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">
                3
            </td>
            <td colspan="4" class="celdas">
                Anotar el día en que se realiza la reunión de apertura
            </td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">
                4
            </td>
            <td colspan="4" class="celdas">
                Anotar el mes en el que se realiza la reunión de apertura
            </td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">
                5
            </td>
            <td colspan="4" class="celdas">
                Anotar el  nombre  del espacio físico en donde se realiza la reunión (ej.: Sala de juntas)
            </td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">
                6
            </td>
            <td colspan="4" class="celdas">
                Anotar el nombre del Instituto Tecnológico de Mérida
            </td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">
                7
            </td>
            <td colspan="4" class="celdas">
                Anotar la hora en la que concluye la reunión de apertura.
            </td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">
                8
            </td>
            <td colspan="4" class="celdas">
                Anotar el día en el que se concluye la reunión de apertura. 
            </td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">
                9
            </td>
            <td colspan="4" class="celdas">
                Anotar el mes en el que se llevó al cabo la reunión de apertura 
            </td>
        </tr>
    </table>
</body>
</html>
';

try {
    $mpdf->WriteHTML($html);
    $mpdf->Output('reunion-apertura.pdf', 'I');
} catch (\Mpdf\MpdfException $e) {
    echo $e->getMessage();
}
