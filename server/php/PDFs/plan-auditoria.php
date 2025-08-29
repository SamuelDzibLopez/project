<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Mpdf\Mpdf;

// Variables de contenido dinámico
$tecnologicos = isset($_POST['tecnologicos']) ? json_decode($_POST['tecnologicos'], true) : [];
$docReferencia = ["ISO 9001:2015", "NMX-CC-9001-IMNC-2015 O ISO 14001:2015", "NMX-MMX-SAA-14001-IMMC-2015"]; //Se queda asi
$domicilio = "Av. Tecnológico S/N Km. 4.5 C.P. 97118, Mérida Yuc.";
$NACE = "Español";
$objetivo = $_POST['objetivo'] ?? '';
$alcance = $_POST['alcance'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$firma = $_POST['firma'] ?? 'null.jpg'; //4.jpg
$firma2 = $_POST['firma2'] ?? 'null.jpg'; //4.jpg
$firma3 = $_POST['firma3'] ?? 'null.jpg'; //4.jpg
$nombre = $_POST['nombre'] ?? '';
$nombre2 = $_POST['nombre2'] ?? '';
$nombre3 = $_POST['nombre3'] ?? '';

$actividades = isset($_POST['actividades']) ? json_decode($_POST['actividades']) : [];

$filasActividadesHtml = '';
foreach ($actividades as $actividad) {
    // Convertimos los arrays de participantes y contactos en texto separado por comas
    $participantesText = implode(", ", $actividad->participantes);
    $contactosText = implode(", ", $actividad->contactos);

    $filasActividadesHtml .= '
        <tr>
            <td colspan="1" class="celdas center"><span>' . htmlspecialchars($actividad->horario) . '</span></td>
            <td colspan="2" class="celdas center"><span>' . htmlspecialchars($actividad->proceso) . '</span></td>
            <td colspan="1" class="celdas center"><span>' . htmlspecialchars($participantesText) . '</span></td>
            <td colspan="1" class="celdas center"><span>' . htmlspecialchars($contactosText) . '</span></td>
            <td colspan="1" class="celdas center"><span>' . htmlspecialchars($actividad->lugar) . '</span></td>
        </tr>';
}


$docReferenciaHtml = '';
foreach ($docReferencia as $doc) {
    $docReferenciaHtml .= $doc . ', ';
}
$docReferenciaHtml = rtrim($docReferenciaHtml, ', ');

$tecnologicosHtml = '';
foreach ($tecnologicos as $tec) {
    $tecnologicosHtml .= $tec . ', ';
}
$tecnologicosHtml = rtrim($tecnologicosHtml, ', ');


// Cargar imágenes en base64
$imgPath = __DIR__ . '/../../../sources/imgs/image.png'; 
$imgBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath));

$imgPath2 = __DIR__ . './../../firmas/' . $firma; //funciona con $imgPath2 = __DIR__ . './../../firmas/3.png.';

$imgPath3 = __DIR__ . './../../firmas/' . $firma2; //funciona con $imgPath2 = __DIR__ . './../../firmas/3.png.';

$imgPath5 = __DIR__ . './../../firmas/' . $firma3; //funciona con $imgPath2 = __DIR__ . './../../firmas/3.png.';

if (file_exists($imgPath2)) {
    $imgBase642 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath2));
} else {
    $imgBase642 = '';
}

if (file_exists($imgPath3)) {
    $imgBase643 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath3));
} else {
    $imgBase643 = '';
}

if (file_exists($imgPath5)) {
    $imgBase645 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath5));
} else {
    $imgBase645 = '';
}

$imgPath4 = __DIR__ . '/../../../sources/imgs/logo-ITM.png'; 
$imgBase644 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath4));

//Nombres
$nombres = array_filter([$nombre, $nombre2, $nombre3], function($val) {
    return trim($val) !== '';
});
$texto = implode(', ', $nombres);

$tdFirmas = (
    (trim($imgBase642) !== '' || trim($imgBase643) !== '' || trim($imgBase645) !== '')
    ? '<td colspan="5" class="celdas center">'
        . (trim($imgBase642) !== '' ? '<img class="firma" src="' . $imgBase642 . '" alt="Firma 1">' : '')
        . (trim($imgBase643) !== '' ? '<img class="firma" src="' . $imgBase643 . '" alt="Firma 2">' : '')
        . (trim($imgBase645) !== '' ? '<img class="firma" src="' . $imgBase645 . '" alt="Firma 3">' : '')
      . '</td>'
    : ''
);


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
            <td>ISO 9001:2015, 9.2 REV. 04</td>
            <td rowspan="4" style="text-align: center;">
            <img src="' . $imgBase644 . '" style="width: 60px;" alt="Logo ITM"></td>
            <td style="text-align: center;">ITMER-CA-PG-003-02</td>
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
    <h1 class="center">Plan de Auditoria</h1>
    <table class="datos">
        <tr>
            <td colspan="5" class="celdas">
                <span class="negritas">Instituto Tecnológico de Mérida:</span> 
                <span class="subrayado">' . $tecnologicosHtml . '.</span>
            </td>
        </tr>
        <tr>
            <td colspan="5" class="celdas">
                <span class="negritas">Documento de Referencia:</span> 
                <span class="subrayado">' . $docReferenciaHtml . '</span>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="celdas">
                <span class="negritas">Domicilio:</span> 
                <span class="subrayado">' . $domicilio . '</span>
            </td>
            <td colspan="1" class="celdas">
                <span class="negritas">NACE:</span> 
                <span class="subrayado">' . $NACE . '</span>
            </td>
        </tr>
        <tr>
            <td colspan="5" class="celdas">
                <span class="negritas">Objetivo:</span> 
                <span class="subrayado">' . $objetivo . '</span>
            </td>
        </tr>
        <tr>
            <td colspan="5" class="celdas">
                <span class="negritas">Alcance:</span> 
                <span class="subrayado">' . $alcance . '</span>
            </td>
        </tr>
    </table>

    <table class="datos">
        <tr>
            <td colspan="5" class="celdas">
                <span class="negritas">Fecha:</span> 
                <span class="subrayado">' . $fecha . '</span>
            </td>
        </tr>
    </table>

    <table class="borders">
        <tr>
            <th colspan="1" class="celdas center">
                <span class="negritas">HORARIO:</span>
            </th>
            <th colspan="2" class="celdas center">
                <span class="negritas">PROCESO / ACTIVIDAD REQUISITO / CRITERIO:</span>
            </th>
            <th colspan="1" class="celdas center">
                <span class="negritas">PARTICIPANTES:</span>
            </th>
            <th colspan="1" class="celdas center">
                <span class="negritas">CONTACTO:</span>
            </th>
            <th colspan="1" class="celdas center">
                <span class="negritas">ÁREA / SITIO:</span>
            </th>
        </tr>
        ' . $filasActividadesHtml . '
    </table>

    <table class="datos">
        <tr>
            '. $tdFirmas .'
        </tr>
        <tr>
            <td colspan="5" class="celdas center">' . $texto . ' / Auditor lider</td>
        </tr>
    </table>

    <table class="datos">
        <tr>
            <td colspan="5" class="celdas">
                * Alcance propuesto por el equipo auditor
            </td>
        </tr>
        <tr>
            <td colspan="5" class="celdas">
                ** Se revisarán todos requisitos de la norma de referencia
            </td>
        </tr>
        <tr>
            <td colspan="5" class="celdas">
                <ul>
                    <li>
                        El grupo auditor tiene el mandato de examinar la estructura, políticas y procedimientos del auditado, de confirmar que estos cumplan todos los requisitos pertinentes al alcance del SGI, que los procedimientos estén implantados y sean tales que den confianza en los procesos o servicios del auditado.
                    </li>
                </ul>
            </td>
        </tr>
        <tr class="">
            <td colspan="5" class="celdas">
                <ul>
                    <li>
                        <span class="negritas">Distribución del plan:</span> original para Tecnológico.
                    </li>
                </ul>
            </td>
        </tr>
        <tr class="">
            <td colspan="5" class="celdas">
                <ul>
                    <li>
                        <span class="negritas">Requisitos de confidencialidad:</span> Toda la información / documentación revisada, proporcionada o generada será tratada en forma confidencial.
                    </li>
                </ul>
            </td>
        </tr>
        <tr class="">
            <td colspan="5" class="celdas">
                <ul>
                    <li>
                        Con la finalidad de alcanzar los objetivos de la auditoría y conforme a los avances en la ejecución del plan, se puede ajustar las actividades y horarios establecidos.
                    </li>
                </ul>
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
            <td colspan="4" class="celdas center">
                Anotar el Nombre del Instituto Tecnológico.
            </td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">
                2
            </td>
            <td colspan="4" class="celdas">
                Anotar el domicilio oficial del Instituto Tecnológico. 
            </td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">
                3
            </td>
            <td colspan="4" class="celdas">
                Anotar el objetivo de la auditoría a realizar ejemplo: Determinar el grado de conformidad con la norma ISO 9001:2015
            </td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">
                4
            </td>
            <td colspan="4" class="celdas">
                Anotar el alcance de la auditoría
            </td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">
                5
            </td>
            <td colspan="4" class="celdas">
                Anotar la fecha en la que se realizara la auditoría; ejemplo (22/marzo/2013)
            </td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">
                6
            </td>
            <td colspan="4" class="celdas">
                Anotar el periodo de tiempo que se realizara la auditoría por procesos y requisitos de norma declarados. Ejemplo: 09:00-09:30 reunión de apertura
            </td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">
                7
            </td>
            <td colspan="4" class="celdas">
                Anotar el nombre completo del responsable de la actividad (a partir de los procesos auditados se pone el nombre del(os) auditor(es))
            </td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">
                8
            </td>
            <td colspan="4" class="celdas">
                Anotar el nombre de la persona con quien se tendrá contacto durante la auditoría ejemplo: Lic. Alejandro Leyva Vega de Flores 
            </td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">
                9
            </td>
            <td colspan="4" class="celdas">
                Anotar el área a la que pertenece el contacto: ejemplo: jefe de ingeniería en sistemas computacionales 
            </td>
        </tr>
        <tr>
            <td colspan="2" class="celdas center">
                10
            </td>
            <td colspan="4" class="celdas">
                Nombre y firma del auditor líder
            </td>
        </tr>
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
