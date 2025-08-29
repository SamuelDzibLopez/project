<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Mpdf\Mpdf;

$objetivo = $_POST['objetivo'] ?? '';
$alcance = $_POST['alcance'] ?? '';
$numero = $_POST['numero'] ?? '';
$proceso = $_POST['proceso'] ?? '';
$fechaHoy = $_POST['fechaHoy'] ?? '';
$fechaInicio = $_POST['fechaInicio'] ?? '';
$fechaFinal = $_POST['fechaFinal'] ?? '';
$NombreauditorLider = $_POST['NombreauditorLider'] ?? '';
$firmaAuditorLider = $_POST['firmaAuditorLider'] ?? 'null.png';
$NombreauditorLider2 = $_POST['NombreauditorLider2'] ?? '';
$firmaAuditorLider2 = $_POST['firmaAuditorLider2'] ?? 'null.png';
$NombreauditorLider3 = $_POST['NombreauditorLider3'] ?? '';
$firmaAuditorLider3 = $_POST['firmaAuditorLider3'] ?? 'null.png';
$firmaRecibe = $_POST['firmaRecibe'] ?? 'null.png';
$mejoras = isset($_POST['mejoras']) ? json_decode($_POST['mejoras']) : [];
$comentarios = isset($_POST['comentarios']) ? json_decode($_POST['comentarios']) : [];
$conclusiones = isset($_POST['conclusiones']) ? json_decode($_POST['conclusiones']) : [];
$noConformidades = isset($_POST['noConformidades']) ? json_decode($_POST['noConformidades']) : [];
$participantes = isset($_POST['participantes']) ? json_decode($_POST['participantes']) : [];
$personalContactado = isset($_POST['personalContactado']) ? json_decode($_POST['personalContactado']) : [];

$imgPath2 = __DIR__ . './../../firmas/' . $firmaAuditorLider;

if (file_exists($imgPath2)) {
    $imgBase642 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath2));
} else {
    $imgBase642 = '';
}

$imgPathFirma2 = __DIR__ . './../../firmas/' . $firmaAuditorLider2;

if (file_exists($imgPathFirma2)) {
    $imgBase64Firma2 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPathFirma2));
} else {
    $imgBase64Firma2 = '';
}

$imgPathFirma3 = __DIR__ . './../../firmas/' . $firmaAuditorLider3;

if (file_exists($imgPathFirma3)) {
    $imgBase64Firma3 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPathFirma3));
} else {
    $imgBase64Firma3 = '';
}

$imgPath3 = __DIR__ . './../../firmas/' . $firmaRecibe;

if (file_exists($imgPath3)) {
    $imgBase643 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath3));
} else {
    $imgBase643 = '';
}

$htmlMejoras = '';
$contadorMejoras = 1;

foreach ($mejoras as $mejora) {
    $htmlMejoras .= '
        <tr>
            <td colspan="6" class="celdas center"><span class="">' . $contadorMejoras . '. ' . htmlspecialchars($mejora->texto) . '</span></td>
        </tr>
    ';
    $contadorMejoras++;
}

$htmlComentarios = '';

foreach ($comentarios as $comentario) {
    $htmlComentarios .= '
        <tr>
            <td colspan="6" class="celdas center"><span class="">' . htmlspecialchars($comentario->texto) . '</span></td>
        </tr>
    ';
}

$htmlConclusiones = '';

foreach ($conclusiones as $conclusion) {
    $htmlConclusiones .= '
        <tr>
            <td colspan="6" class="celdas center"><span class="">' . htmlspecialchars($conclusion->texto) . '</span></td>
        </tr>
    ';
}

$htmlNoConformidades = '';
$contadorNoConformidades = 0;

foreach ($noConformidades as $noConformidad) {
    $contadorNoConformidades++;
    $htmlNoConformidades .= '
        <tr>
            <td colspan="1" class="celdas center"><span class="">' . $contadorNoConformidades . '.</span></td>
            <td colspan="8" class="celdas center"><span class="">' . htmlspecialchars($noConformidad->texto) . '</span></td>
            <td colspan="1" class="celdas center"><span class="">' . htmlspecialchars($noConformidad->requisito) . '</span></td>

        </tr>
    ';
}

$htmlParticipantes = '';

$listaNombres = [];

foreach ($participantes as $participante) {
    $nombreCompleto = htmlspecialchars($participante->nombreCompleto) . ' ' .
        htmlspecialchars($participante->apellidoPaterno) . ' ' .
        htmlspecialchars($participante->apellidoMaterno);
    $listaNombres[] = $nombreCompleto;
}

$htmlParticipantes = implode(', ', $listaNombres) . '.';

$htmlPersonalContactado = '';

foreach ($personalContactado as $personal) {
    $htmlPersonalContactado .= '<tr>
            <td colspan="5" class="celdas center"><span class="">'. htmlspecialchars($personal->nombreCompleto) .' '. htmlspecialchars($personal->apellidoPaterno) . ' '. htmlspecialchars($personal->apellidoMaterno) .'</span></td>
            <td colspan="5" class="celdas center"><span class="">'. htmlspecialchars($personal->puesto) .' de '. htmlspecialchars($personal->departamento) .'</span></td>
        </tr>';
}

// Cargar imágenes en base64
$imgPath = __DIR__ . '/../../../sources/imgs/image.png'; 
$imgBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath));

$imgPath4 = __DIR__ . '/../../../sources/imgs/logo-ITM.png'; 
$imgBase644 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath4));

//Nombres
$nombres = array_filter([$NombreauditorLider, $NombreauditorLider2, $NombreauditorLider3], function($val) {
    return trim($val) !== '';
});
$texto = implode(', ', $nombres);

$spanFirmas = (
    (trim($imgBase642) !== '' || trim($imgBase64Firma2) !== '' || trim($imgBase64Firma3) !== '')
    ? '<span class="firmas">'
        . (trim($imgBase642) !== '' ? '<img class="firma" src="' . $imgBase642 . '" alt="Firma 1" style="max-width: 200px; max-height: auto;">' : '')
        . (trim($imgBase64Firma2) !== '' ? '<img class="firma" src="' . $imgBase64Firma2 . '" alt="Firma 2" style="max-width: 200px; max-height: auto;">' : '')
        . (trim($imgBase64Firma3) !== '' ? '<img class="firma" src="' . $imgBase64Firma3 . '" alt="Firma 3" style="max-width: 200px; max-height: auto;">' : '')
      . '</span>'
    : ''
);


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
            <td style="text-align: center;">ITMER-CA-PG-003-04</td>
        </tr>
        <tr><td>ISO 14001:2015, 9.2</td><td></td></tr>
        <tr><td>ISO 5001:2018</td><td></td></tr>
        <tr><td>NMX-R-025-SCFI:2015</td><td></td></tr>
    </table>
');

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
        min-width: 10%;
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
        table-layout: fixed;
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
    <h1 class="center">Informe de auditoria</h1>

    <table class="borders">
        <colgroup><col span="10"></colgroup>
        <tr>
            <td colspan="4" class="celdas "><span class="negritas">INSTITUTO TECNOLÓGICO:</span> Instituto Tecnológico de Mérida</td>
            <td colspan="2" class="celdas"><span class="negritas">NO. DE AUDITORIA:</span> '. $numero .'</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas "><span class="negritas">PROCESO:</span></td>
            <td colspan="2" class="celdas"><span class="">'. $proceso .'</span></td>
            <td colspan="2" class="celdas"><span class="negritas">FECHA: </span>'. $fechaInicio .'</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas "><span class="negritas">AUDITOR LIDER:</span></td>
            <td colspan="4" class="celdas"><span class="">'. $texto .'.</span></td>
        </tr>
        <tr>
            <td colspan="2" class="celdas "><span class="negritas">GRUPO AUDITOR:</span></td>
            <td colspan="4" class="celdas"><span class="">'. $htmlParticipantes .'</span></td>
        </tr>
    </table>

    <br>

    <table class="borders">
        <colgroup><col span="10"></colgroup>
        <tr>
            <th colspan="2" class="celdas "><span class="">DOCUMENTO DE REFERENCIA:</span></th>
            <td colspan="3" class="celdas"><span class="">ISO-9001-2015, MX-CC-9001-IMNC-2015, ISO-14001:2015, NMX-SAA-14001-IMNC-2015, ISO-50001:2018, NMX-J-SAA-50001-ANCE-IMNC-2019      
NMX-R-025-SCFI-2015
</span></td>
        </tr>
    </table>

    <br>

    <table class="borders">
        <colgroup><col span="10"></colgroup>
        <tr>
            <th colspan="5" class="celdas center"><span class="">OBJETIVO</span></th>
        </tr>
        <tr>
            <td colspan="5" class="celdas center"><span class="">'. $objetivo .'</span></td>
        </tr>
    </table>

    <br>

    <table class="borders">
        <colgroup><col span="10"></colgroup>
        <tr>
            <th colspan="5" class="celdas center"><span class="">ALCANCE</span></th>
        </tr>
        <tr>
            <td colspan="5" class="celdas center"><span class="">'. $alcance .'</span></td>
        </tr>
    </table>

    <br>

    <table class="borders">
        <colgroup><col span="10"></colgroup>
        <tr>
            <th colspan="10" class="celdas center"><span class="">PERSONAL CONTACTADO</span></th>
        </tr>
        <tr>
            <th colspan="5" class="celdas center"><span class="">NOMBRE</span></th>
            <th colspan="5" class="celdas center"><span class="">PUESTO</span></th>
        </tr>
        '. $htmlPersonalContactado .'
    </table>

    <br>

    <table class="datos">
        <tr>
            <td colspan="5" class="celdas">
                Nota: El personal contactado solo es representativo de los cargos más relevantes en cada proceso.
            </td>
        </tr>
    </table>

    <br>

    <table class="borders">
        <colgroup><col span="10"></colgroup>
        <tr>
            <th colspan="6" class="celdas center"><span class="">NO CONFORMIDADES</span></th>
        </tr>
        <tr>
            <td colspan="6" class="celdas center"><span class="">En la revisión al Sistema de Gestión de Integral se encontraron un total de '. $contadorNoConformidades .' No Conformidades</span></td>
        </tr>
    </table>

    <br>

    <table class="borders">
        <colgroup><col span="10"></colgroup>
        <tr>
            <th colspan="6" class="celdas center"><span class="">OPORTUNIDADES DE MEJORA</span></th>
        </tr>
        '. $htmlMejoras .'
    </table>

    <br>

    <table class="borders">
        <colgroup><col span="10"></colgroup>
        <tr>
            <th colspan="6" class="celdas center"><span class="">COMENTARIOS</span></th>
        </tr>
        '. $htmlComentarios .'
    </table>

    <br>

    <table class="borders">
        <colgroup><col span="10"></colgroup>
        <tr>
            <th colspan="10" class="celdas center"><span class="">NO CONFORMIDADES</span></th>
        </tr>
        <tr>
            <th colspan="1" class="celdas center"><span class="">No.</span></th>
            <th colspan="8" class="celdas center"><span class="">Descripción del Hallazgo</span></th>
            <th colspan="1" class="celdas center"><span class="">Requisito.</span></th>
            </tr>
        '. $htmlNoConformidades .'
    </table>

    <br>

    <table class="borders">
        <tr>
            <th colspan="6" class="celdas center"><span class="">CONCLUSIONES DE AUDITORIA</span></th>
        </tr>
        '. $htmlConclusiones .'
    </table>

    <br>

    <table class="borders">
        <tr>
            <th colspan="3" class="celdas center"><span class="">AUDITOR LIDER</span></th>
            <th colspan="3" class="celdas center"><span class="">RECIBI DE CONFORMIDAD</span></th>
            <th colspan="3" class="celdas center"><span class="">FECHA DE AUDITORIA</span></th>
        </tr>
        <tr>
            <td colspan="3" class="celdas center">
                <span class="">
                    '. $spanFirmas .'
                </span>
            </td>
            <td colspan="3" class="celdas center">
                <span class="">
                    <img class="firma" src="' . $imgBase643 . '" alt="Firma 1">
                </span>
            </td>
            <td colspan="3" class="celdas center">
                <span class="">
                '. $fechaInicio .' a '. $fechaFinal .'
                </span>
            </td>
        </tr>
    </table>

    <br>

    <table class="borders">
        <tr>
            <th colspan="1" class="celdas center"><span class="">FECHA DE EMISIÓN DEL INFORME</span></th>
            <td colspan="4" class="celdas center"><span class="">'. $fechaFinal .'</span></td>
        </tr>
    </table>

    <h2 class="center">INSTRUCTIVO DE LLENADO</h2>

    <table class="borders">
        <colgroup><col span="10"></colgroup>
        <tr>
            <th colspan="2" class="celdas center"><span class="negritas">Número:</span></th>
            <th colspan="4" class="celdas center"><span class="negritas">Descripción:</span></th>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">1</td>
            <td colspan="4" class="celdas">Anotar el nombre del Instituto Tecnológico.</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">2</td>
            <td colspan="4" class="celdas">Anotar el número consecutivo de la auditoria de acuerdo al historial de las mismas.</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">3</td>
            <td colspan="4" class="celdas">Anotar el proceso a auditar considerado en él, el alcance de la misma.</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">4</td>
            <td colspan="4" class="celdas">Anotar la fecha de elaboración del Informe de la auditoria.</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">5</td>
            <td colspan="4" class="celdas">Anotar el nombre del líder del equipo auditor.</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">6</td>
            <td colspan="4" class="celdas">Anotar los nombres de todos los integrantes que conforman el equipo auditor.</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">7</td>
            <td colspan="4" class="celdas">Anotar el Objetivo de realizar la auditoria.</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">8</td>
            <td colspan="4" class="celdas">Anotar a que partes del proceso se auditara ej. A todo el Proceso educativo, al proceso estratégico de vinculación del Proceso Educativo.</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">9</td>
            <td colspan="4" class="celdas">Anotar al personal contactado responsable del proceso de acuerdo a la estructura orgánica del Instituto Tecnológico, ejemplo: Director, Subdirector y Jefe de Departamento.</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">10</td>
            <td colspan="4" class="celdas">Anotar por cada punto de norma A=aplica, NA=No aplica, EP=Exclusión permitida, de acuerdo al Plan de Auditoria y declarado en el SGI, conforme a la revisión hecha si anotar si es AD= Adecuado, NC=No conforme, NR=No revisado, EP=Exclusión permitida, NA=No aplica.</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">11</td>
            <td colspan="4" class="celdas">Anotar las oportunidades de mejora detectadas durante la auditoria.</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">12</td>
            <td colspan="4" class="celdas">Anotar los comentarios sobre la apertura y disposición de las personas responsables de los procesos durante la auditoria.</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">13</td>
            <td colspan="4" class="celdas">Anotar las conclusiones a las que se llegó en la auditoria punto 5 de la descripción del procedimiento.</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">14</td>
            <td colspan="4" class="celdas">Anotar las conclusiones de la auditoria conforme al resultado obtenido declarando el nivel de madurez del SGI.</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">15</td>
            <td colspan="4" class="celdas">Anotar nombre y firma del Auditor Líder.</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">16</td>
            <td colspan="4" class="celdas">Anotar nombre y firma del Director del Instituto Tecnológico o de la persona designada para recibir el Informe de la auditoria.</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">17</td>
            <td colspan="4" class="celdas">Anotar las fechas en que se desarrolló la auditoria.</td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">18</td>
            <td colspan="4" class="celdas">Anotar la fecha de cierre de la auditoria.</td>
        </tr>
    </table>
</body>
</html>
';


try {
    $mpdf->WriteHTML($html);
    $mpdf->Output('reunion-cierre.pdf', 'I');
} catch (\Mpdf\MpdfException $e) {
    echo $e->getMessage();
}
