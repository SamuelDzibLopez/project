<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Mpdf\Mpdf;

$folio = $_POST['folio'] ?? '';
$areaProceso = $_POST['areaProceso'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$origenRequisito = $_POST['origenRequisito'] ?? '';

$fuenteNC = $_POST['fuenteNC'] ?? '';

$descripcion = $_POST['descripcion'] ?? '';

$requiereAC = isset($_POST['requiereAC']) ? $_POST['requiereAC'] === "true" : false;
$requiereCorreccion = isset($_POST['requiereCorreccion']) ? $_POST['requiereCorreccion'] === "true" : false;

$tecnica = $_POST['tecnica'] ?? '';
$raiz = $_POST['raiz'] ?? '';
$ACARealizar = $_POST['ACARealizar'] ?? '';

$NCSimilares = isset($_POST['NCSimilares']) ? $_POST['NCSimilares'] === "true" : false;
$NCPotenciales = isset($_POST['NCPotenciales']) ? $_POST['NCPotenciales'] === "true" : false;

// Faltan cuales: campos tipo texto para detallar qué acciones aplicar en caso similares/potenciales
$ACSimilares = $_POST['ACSimilares'] ?? '';
$ACPotenciales = $_POST['ACPotenciales'] ?? '';

$seguimiento = $_POST['seguimiento'] ?? '';

$actualizar = isset($_POST['actualizar']) ? $_POST['actualizar'] === "true" : false;
$cambios = isset($_POST['cambios']) ? $_POST['cambios'] === "true" : false;

// Faltan cuales
$ACActualizar = $_POST['ACActualizar'] ?? '';
$ACCambios = $_POST['ACCambios'] ?? '';

$firmaDefine = $_POST['firmaDefine'] ?? '';
$firmaVerifica = $_POST['firmaVerifica'] ?? '';
$firmaCoordinador = $_POST['firmaCoordinador'] ?? '';

$nombreUsuarioDefine = $_POST['nombreUsuarioDefine'] ?? '';
$nombreUsuarioVerifica = $_POST['nombreUsuarioVerifica'] ?? '';
$nombreUsuarioCoordinador = $_POST['nombreUsuarioCoordinador'] ?? '';

//Arrays JSON
$correcciones = json_decode($_POST['correcciones'] ?? '[]', true);
$acciones = json_decode($_POST['acciones'] ?? '[]', true);

//Generar templates de correcciones

$correccionesHTML = '';
$correlativo = 1;

foreach ($correcciones as $corr) {
    $textoCorreccion = $corr['correccion'] ?? '';
    $responsable = isset($corr['idResponsable_info']) 
        ? trim($corr['idResponsable_info']['nombreCompleto'] . ' ' . $corr['idResponsable_info']['apellidoPaterno'] . ' ' . $corr['idResponsable_info']['apellidoMaterno'])
        : '';
    $fecha = $corr['fecha'] ?? '';

    $correccionesHTML .= '
        <tr>
            <td class="center" colspan="2">' . $correlativo . '</td>
            <td class="center" colspan="7">' . htmlspecialchars($textoCorreccion) . '</td>
            <td class="center" colspan="3">' . htmlspecialchars($responsable) . '</td>
            <td class="center" colspan="3">' . htmlspecialchars($fecha) . '</td>
        </tr>
    ';

    $correlativo++;
}

//Generar templates de acciones

$accionesHTML = '';
$correlativoAcciones = 1;

foreach ($acciones as $accion) {
    $textoAccion = $accion['accion'] ?? '';
    $responsable = isset($accion['idResponsable_info']) 
        ? trim($accion['idResponsable_info']['nombreCompleto'] . ' ' . $accion['idResponsable_info']['apellidoPaterno'] . ' ' . $accion['idResponsable_info']['apellidoMaterno'])
        : '';
    $fecha = $accion['fecha'] ?? '';

    $accionesHTML .= '
        <tr>
            <td class="" colspan="8">' . $correlativoAcciones . '.- ' . htmlspecialchars($textoAccion) . '</td>
            <td class="center" colspan="4">' . htmlspecialchars($responsable) . '</td>
            <td class="center" colspan="3">' . htmlspecialchars($fecha) . '</td>
        </tr>
    ';

    $correlativoAcciones++;
}

//Cargar firmas

//Firma de Define
$imgPath2 = __DIR__ . './../../firmas/' . $firmaDefine;

if (file_exists($imgPath2)) {
    $imgBase642 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath2));
} else {
    $imgBase642 = '';
}

//Firma de Verifica
$imgPath3 = __DIR__ . './../../firmas/' . $firmaVerifica;

if (file_exists($imgPath3)) {
    $imgBase643 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath3));
} else {
    $imgBase643 = '';
}

//Firma de Coordinador
$imgPath5 = __DIR__ . './../../firmas/' . $firmaCoordinador;

if (file_exists($imgPath5)) {
    $imgBase645 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath5));
} else {
    $imgBase645 = '';
}

// Cargar imágenes en base64
$imgPath = __DIR__ . '/../../../sources/imgs/image.png'; 
$imgBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath));

//Logos
$imgPath4 = __DIR__ . '/../../../sources/imgs/logo-ITM.png'; 
$imgBase644 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath4));

// Crear instancia de mPDF
$mpdf = new Mpdf([
    'format' => 'letter',
    'orientation' => 'p',
    'margin_top' => 40,
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
            <td>ISO 9001:2015, 10.2.1 REV. 04</td>
            <td rowspan="4" style="text-align: center;">
            <img src="' . $imgBase644 . '" style="width: 60px;" alt="Logo ITM"></td>
            <td style="text-align: center;">ITMER-CA-PG-005-01</td>
        </tr>
        <tr><td>ISO 14001:2015, 10.2</td><td></td></tr>
        <tr><td>ISO 5001:2015, 10.1</td><td></td></tr>
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
    .borders-gray {
        background-color:rgb(214, 204, 204);
    }
    .celda-blue {
        background-color: rgb(48, 83, 151);
        color: white;
    }
    .borders th, .borders td {
        border: 1px solid black;
        padding: 5px;
    }
    /* Aplicar correctamente no-border solo en celdas específicas */
    .borders td.no-border, .borders th.no-border {
        border: none !important;
    }
    </style>
</head>
<body>
    <h1 class="center">Identificación de Producto No Conforme</h1>

    <br>

    <table class="borders">
        <thead>
            <tr>
                <th class="center celda-blue" colspan="3">Folio</th>
                <td class="center no-border"colspan="3"></td>
                <th class="center celda-blue" colspan="1">Área Proceso</th>
                <td class="center" colspan="3">' . $areaProceso . '</td>
                <td class="center no-border"></td>
                <th class="center celda-blue" colspan="2">Calidad</th>
                <td class="center" colspan="2">' . (($origenRequisito === 'Calidad') ? 'X' : '') . '</td>
            </tr>
            <tr>
                <td class="center" colspan="3" rowspan="2">'. $folio .'</td>
                <td class="center no-border" colspan="3"></td>
                <th class="center celda-blue" colspan="1">Responsable</th>
                <td class="center" colspan="3">' . $nombreUsuarioDefine . '</td>
                <td class="center no-border"></td>
                <th class="center celda-blue" colspan="2">Ambiental</th>
                <td class="center" colspan="2">' . (($origenRequisito === 'Ambiental') ? 'X' : '') . '</td>
            </tr>
            <tr>
                <td class="center no-border" colspan="3"></td>
                <th class="center celda-blue" colspan="1">Fecha</th>
                <td class="center" colspan="3">' . $fecha . '</td>
                <td class="center no-border"></td>
                <th class="center celda-blue" colspan="2">Energía</th>
                <td class="center" colspan="2">' . (($origenRequisito === 'Energia') ? 'X' : '') . '</td>
            </tr>
            <tr>
                <td class="center no-border" colspan="11"></td>
                <th class="center celda-blue" colspan="2">R-025</th>
                <td class="center" colspan="2">' . (($origenRequisito === 'R-025') ? 'X' : '') . '</td>
            </tr>
            <tr>
                <td class="center no-border" colspan="11"></td>
                <th class="center celda-blue" colspan="4">Origen del requisito</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <br>

    <table class="borders">
        <thead>
            <tr>
                <th class="center celda-blue" colspan="15">FUENTE DE LA NO CONFORMIDAD</th>
            </tr>
            <tr>
                <td class="center" colspan="5">Quejas de parte interesadas</td>
                <td class="center" colspan="2">' . (($fuenteNC === "true") ? 'X' : '') . '</td>
                <td class="center no-border" colspan="1"></td>
                <td class="center" colspan="5">Riesgos</td>
                <td class="center" colspan="2">' . (($fuenteNC === "2") ? 'X' : '') . '</td>
            </tr>
            <tr>
                <td class="center" colspan="5">Auditoria Externa</td>
                <td class="center" colspan="2">' . (($fuenteNC === "3") ? 'X' : '') . '</td>
                <td class="center no-border" colspan="1"></td>
                <td class="center" colspan="5">Oportunidades</td>
                <td class="center" colspan="2">' . (($fuenteNC === "4") ? 'X' : '') . '</td>
            </tr>
            <tr>
                <td class="center" colspan="5">Auditoria Interna</td>
                <td class="center" colspan="2">' . (($fuenteNC === "5") ? 'X' : '') . '</td>
                <td class="center no-border" colspan="1"></td>
                <td class="center" colspan="5">Incumplimiento a objetivo/Indicador</td>
                <td class="center" colspan="2">' . (($fuenteNC === "6") ? 'X' : '') . '</td>
            </tr>
            <tr>
                <td class="center" colspan="5">Resultado de Análisis de datos</td>
                <td class="center" colspan="2">' . (($fuenteNC === "7") ? 'X' : '') . '</td>
                <td class="center no-border" colspan="1"></td>
                <td class="center" colspan="5">Aspecto Ambiental</td>
                <td class="center" colspan="2">' . (($fuenteNC === "8") ? 'X' : '') . '</td>
            </tr>
            <tr>
                <td class="center" colspan="5">Revisión por la Dirección</td>
                <td class="center" colspan="2">' . (($fuenteNC === "9") ? 'X' : '') . '</td>
                <td class="center no-border" colspan="1"></td>
                <td class="center" colspan="5">Accidente incidente</td>
                <td class="center" colspan="2">' . (($fuenteNC === "10") ? 'X' : '') . '</td>
            </tr>
            <tr>
                <td class="center" colspan="5">Desviaciones del proceso</td>
                <td class="center" colspan="2">' . (($fuenteNC === "11") ? 'X' : '') . '</td>
                <td class="center no-border" colspan="1"></td>
                <td class="center" colspan="5">Atención y respuesta a Emergencia</td>
                <td class="center" colspan="2">' . (($fuenteNC === "12") ? 'X' : '') . '</td>
            </tr>
            <tr>
                <td class="center" colspan="5">Producto No conforme</td>
                <td class="center" colspan="2">' . (($fuenteNC === "13") ? 'X' : '') . '</td>
                <td class="center no-border" colspan="1"></td>
                <td class="center" colspan="5">Otra</td>
                <td class="center" colspan="2">' . (($fuenteNC === "14") ? 'X' : '') . '</td>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <br>

    <table class="borders">
        <thead>
            <tr>
                <td class="no-border negritas" colspan="15">DESCRIPCIÓN:</td>
            </tr>
            <tr>
                <td class="" colspan="15">' . $descripcion . '</td>
            </tr>
            <tr>
                <td class="negritas" colspan="15">Responsable de definir la(s) acción(es) correctiva y/o correcciones para eliminar la No Conformidad y/o PNC identificado: ' . $nombreUsuarioDefine . '</td>
            </tr>
            <tr>
                <td class="negritas" colspan="15">Responsable de verificar el cumplimento de las acciones de mejora definidas en el plan: ' . $nombreUsuarioVerifica . '</td>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <br>

    <table class="borders">
        <thead>
            <tr>
                <td class="no-border negritas" colspan="15">REPORTE:</td>
            </tr>
            <tr>
                <th class="center no-border" colspan="2">REQUIERE ACCIÓN CORRECTIVA</th>
                <td class="center" colspan="1">SI</td>
                <td class="center" colspan="1">' . (($requiereAC == "1") ? 'X' : '') . '</td>
                <td class="center no-border" colspan="1"></td>
                <td class="center" colspan="1">NO</td>
                <td class="center" colspan="1">' . (($requiereAC == "") ? 'X' : '') . '</td>
                <td class="center no-border" colspan="1"></td>
                <th class="center no-border" colspan="2">REQUIERE CORRECCIÓN</th>
                <td class="center" colspan="1">SI</td>
                <td class="center" colspan="1">' . (($requiereCorreccion == "1") ? 'X' : '') . '</td>
                <td class="center no-border" colspan="1"></td>
                <td class="center" colspan="1">NO</td>
                <td class="center" colspan="1">' . (($requiereCorreccion == "") ? 'X' : '') . '</td>
                </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <br>

    <table class="borders">
        <thead>
            <tr>
                <td class="celda-blue center negritas" colspan="2">No.</td>
                <td class="celda-blue center negritas" colspan="7">Corrección</td>
                <td class="celda-blue center negritas" colspan="3">Responsable</td>
                <td class="celda-blue center negritas" colspan="3">Fecha</td>
            </tr>
            <tr>
                '. $correccionesHTML .'
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <br>

    <table class="borders">
        <thead>
            <tr>
                <td class="no-border negritas" colspan="15">ANALISIS:</td>
            </tr>
            <tr>
                <td class="negritas" colspan="15">Técnica estadística utilizada: '. $tecnica .'</td>
            </tr>
            <tr>
                <td class="negritas" colspan="15">Causa raíz identificada: '. $raiz .'</td>
            </tr>
            <tr>
                <td class="negritas" colspan="15">Acción Correctiva para realizar: '. $ACARealizar .'</td>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <br>

    <table class="borders">
        <thead>
            <tr>
                <td class="no-border" colspan="6"></td>
                <td class="celda-blue center negritas" colspan="2">si</td>
                <td class="celda-blue center negritas" colspan="2">no</td>
                <td class="celda-blue center negritas" colspan="5">¿Cuál? / Acciones</td>
            </tr>
            <tr>
                <td class="center" colspan="6">¿Existen No conformidades similares?</td>
                <td class="center" colspan="2">' . (($NCSimilares == "1") ? 'X' : '') . '</td>
                <td class="center" colspan="2">' . (($NCSimilares == "") ? 'X' : '') . '</td>
                <td class="center" colspan="5">'. $ACSimilares .'</td>
            </tr>
            <tr>
                <td class="center" colspan="6">¿Existen No conformidades Potenciales?</td>
                <td class="center" colspan="2">' . (($NCPotenciales == "1") ? 'X' : '') . '</td>
                <td class="center" colspan="2">' . (($NCPotenciales == "") ? 'X' : '') . '</td>
                <td class="center" colspan="5">'. $ACPotenciales .'</td>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <br>

    <table class="borders">
        <thead>
            <tr>
                <td class="no-border negritas" colspan="15">PLAN DE ACCIÓN:</td>
            </tr>
            <tr>
                <td class="celda-blue center negritas" colspan="8">Acciones</td>
                <td class="celda-blue center negritas" colspan="4">Responsable</td>
                <td class="celda-blue center negritas" colspan="3">Fecha programada</td>
            </tr>
            '. $accionesHTML .'
        </thead>
        <tbody>
        </tbody>
    </table>

    <br>

    <table class="borders">
        <thead>
            <tr>
                <td class="no-border negritas" colspan="15">SEGUIMIENTO Y EVIDENCIAS DE LAS ACIONES REALIZADAS:</td>
            </tr>
            <tr>
                <td class="center" colspan="15">'. $seguimiento .'</td>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <br>

    <table class="borders">
        <thead>
            <tr>
                <td class="no-border" colspan="6"></td>
                <td class="celda-blue center negritas" colspan="2">si</td>
                <td class="celda-blue center negritas" colspan="2">no</td>
                <td class="celda-blue center negritas" colspan="5">¿Cuál? / Acciones</td>
            </tr>
            <tr>
                <td class="center" colspan="6">¿Es necesario actualizar Riesgos / oportunidades?</td>
                <td class="center" colspan="2">' . (($actualizar == "1") ? 'X' : '') . '</td>
                <td class="center" colspan="2">' . (($actualizar == "") ? 'X' : '') . '</td>
                <td class="center" colspan="5">'. $ACActualizar .'</td>
            </tr>
            <tr>
                <td class="center" colspan="6">¿Es necesario hacer cambios en el Sistema de Gestión?</td>
                <td class="center" colspan="2">' . (($cambios == "1") ? 'X' : '') . '</td>
                <td class="center" colspan="2">' . (($cambios == "") ? 'X' : '') . '</td>
                <td class="center" colspan="5">'. $ACCambios .'</td>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    
    <br>

    <table class="borders">
        <thead>
            <tr>
                <td class="celda-blue center negritas" colspan="5">Definió la AC o Corrección:</td>
                <td class="celda-blue center negritas" colspan="5">Verifico AC o Corrección:</td>
                <td class="celda-blue center negritas" colspan="5">Fecha de cierre:</td>
            </tr>
            <tr>
                <td class="negritas center" colspan="5">
                    <img class="firma" src="' . $imgBase642 . '" alt="Firma" style="width:100px; height:auto;">
                </td>
                <td class="negritas center" colspan="5">
                    <img class="firma" src="' . $imgBase643 . '" alt="Firma" style="width:100px; height:auto;">
                </td>
                <td class="negritas center" colspan="5">
                    <img class="firma" src="' . $imgBase645 . '" alt="Firma" style="width:100px; height:auto;">
                </td>
            </tr>
            <tr>
                <td class="negritas center" colspan="5">'. $nombreUsuarioDefine .'</td>
                <td class="negritas center" colspan="5">'. $nombreUsuarioVerifica .'</td>
                <td class="negritas center" colspan="5">'. $nombreUsuarioCoordinador .'</td>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <h2 class="center">INSTRUCTIVO DE LLENADO</h2>

    <table class="borders">
        <thead>
            <tr class="borders-gray">
                <th colspan="2" class="center"><span class="negritas">Número:</span></th>
                <th colspan="4" class="center"><span class="negritas">Descripción:</span></th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="2" class="center">1</td><td colspan="4">Anotar el número consecutivo que se da a la solicitud de la acción correctiva poniendo el número consecutivo, año, iniciales del área.</td></tr>
            <tr><td colspan="2" class="center">2</td><td colspan="4">Área donde se detectó la acción a corregir.</td></tr>
            <tr><td colspan="2" class="center">3</td><td colspan="4">Responsable del área.</td></tr>
            <tr><td colspan="2" class="center">4</td><td colspan="4">Anotar la fecha en que se requisita (llena) el formato.</td></tr>
            <tr><td colspan="2" class="center">5</td><td colspan="4">Marcar con una X en el recuadro correspondiente, el origen de donde proviene la No Conformidad por lo que se solicita la Acción Correctiva o corrección.</td></tr>
            <tr><td colspan="2" class="center">6</td><td colspan="4">Marcar la fuente de la no conformidad.</td></tr>
            <tr><td colspan="2" class="center">7</td><td colspan="4">Anotar en este espacio la descripción detallada de la No Conformidad encontrada y/o el Producto No Conforme Identificado, También deberá Anotar en los espacios inferiores el nombre de la persona responsable de definir la acción correctiva o corrección que se implementará para eliminar la No Conformidad o PNC, y el nombre del responsable de verificar la eficacia de las acciones de mejora.</td></tr>
            <tr><td colspan="2" class="center">8</td><td colspan="4">Realizar el reporte marcando que se requiere acción correctiva y corrección, así como mencionarlo a detalle como solicita el cuadro.</td></tr>
            <tr><td colspan="2" class="center">9</td><td colspan="4">Anotará la técnica estadística que utilizó para realizar el análisis de la causa raíz, puede ser (los 5 porqués, lluvia de ideas, diagrama de Pareto, histograma, diagrama de pescado, etc.) anotar la causa raíz identificada y la acción correctiva a realizar.</td></tr>
            <tr><td colspan="2" class="center">10</td><td colspan="4">Marcar con una X en si o no de acuerdo con las preguntas que se indican.</td></tr>
            <tr><td colspan="2" class="center">11</td><td colspan="4">Anotar la(s) accione(s) específicas para eliminar la causa raíz o realizar la corrección.</td></tr>
            <tr><td colspan="2" class="center">12</td><td colspan="4">Dar seguimiento a las acciones, mencionando en el recuadro las evidencias que se entregaran de cada una de las acciones.</td></tr>
            <tr><td colspan="2" class="center">13</td><td colspan="4">Contestar si ó no a las preguntas que se indican.</td></tr><tr><td colspan="2" class="center">14</td><td colspan="4">Anotar el nombre de la(s) persona (s) que definió (eron) la acción correctiva.</td></tr>
            <tr><td colspan="2" class="center">15</td><td colspan="4">Anotar el nombre y firma del subdirector que verifico la (s) acción (es), a realizar.</td></tr>
            <tr><td colspan="2" class="center">16</td><td colspan="4">Anotar nombre y firma del Coordinador General de Calidad del y la fecha de cierre del RAC.</td></tr>
        </tbody>
    </table>
</body>
</html>
';



try {
    $mpdf->WriteHTML($html);
    $mpdf->Output('accion-correctiva.pdf', 'I');
} catch (\Mpdf\MpdfException $e) {
    echo $e->getMessage();
}
