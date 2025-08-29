<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Mpdf\Mpdf;

// // Variables de contenido dinámico
$fecha = $_POST['fecha'] ?? '';
$folio = $_POST['folio'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$nocontrol = $_POST['nocontrol'] ?? '';
$carrera = $_POST['carrera'] ?? '';
$semestre = $_POST['semestre'] ?? '';
$grupo = $_POST['grupo'] ?? '';
$turno = $_POST['turno'] ?? '';
$aula = $_POST['aula'] ?? '';
$mensaje = $_POST['mensaje'] ?? '';
$respuesta = $_POST['respuesta'] ?? '';
$firmaSubdirector = isset($_POST['firmaSubdirector']) && $_POST['firmaSubdirector'] !== '' ? basename($_POST['firmaSubdirector']): '0.png';
$firmaEntregado = isset($_POST['firmaEntregado']) && $_POST['firmaEntregado'] !== '' ? basename($_POST['firmaEntregado']) : '0.png';
$nombreSubdirector = $_POST['nombreSubdirector'] ?? '';
$nombreReceptor = $_POST['nombreReceptor'] ?? '';


// Cargar imágenes en base64
$imgPath = __DIR__ . '/../../../sources/imgs/image.png'; 
$imgBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath));

$imgPath2 = __DIR__ . './../../firmas/' . $firmaSubdirector; 

if (file_exists($imgPath2)) {
  $imgBase642 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath2));
} else {
  $imgBase642 = '';
}

$imgPath3 = __DIR__ . './../../firmas/' . $firmaEntregado; 

if (file_exists($imgPath3)) {
  $imgBase643 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath3));
} else {
  $imgBase643 = '';
}

$imgPath4 = __DIR__ . '/../../../sources/imgs/logo-ITM.png'; 
$imgBase644 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgPath4));

// Crear instancia de mPDF
$mpdf = new Mpdf([
  'format' => 'letter',
  'orientation' => 'P',
  'margin_top' => 40,
  'margin_bottom' => 30,
  'margin_left' => 15,
  'margin_right' => 15
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
      <td>ISO 9001:2015 5.1.2, 8.2.1   REV. 03</td>
      <td rowspan="4" style="text-align: center;">
        <img src="' . $imgBase644 . '" style="width: 60px;" alt="Logo ITM">
      </td>
      <td style="text-align: center;">ITMER-CA-PO-001-01</td>
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
    body { font-family: Arial, sans-serif; background-color: transparent; }
    h1 { color: black; font-size: 11px; }
    .center { text-align: center; }
    img { width: 100%; }
    table { width: 100%; font-size: 10px; border-collapse: separate; border-spacing: 0 10px; }
    .documentos { font-size: 10px; border-spacing: 0 5px; }
    td { font-size: 11px; width: 25%; text-align: justify; word-wrap: break-word; hyphens: auto; }
    .subrayado { text-decoration: underline; }
    .border { border: 1px solid black; padding: 15px 10px; }
    .firma { width: 25%; }
    .logo-itm { width: 60px; }
  </style>
</head>
<body>
  <h1 class="center">FORMATO PARA QUEJAS Y/O SUGERENCIAS</h1>
  <table>
    <tr><td colspan="2">Fecha: <span class="subrayado">'. $fecha .'</span></td><td colspan="2">FOLIO: <span class="subrayado">'. $folio .'</span></td></tr>
    <tr><td colspan="4">Para validar su queja y/o sugerencia deberá requisitar algún dato que nos permita localizarlo y darle respuesta, esta información es de carácter <strong>CONFIDENCIAL</strong>.</td></tr>
    <tr><td colspan="2">Nombre: <span class="subrayado">'. $nombre .'</span></td><td colspan="2">Correo Electrónico: <span class="subrayado">'. $correo .'</span></td></tr>
    <tr><td colspan="2">Tel: <span class="subrayado">'. $telefono .'</span></td><td colspan="2"></td></tr>
    <tr><td colspan="2">No. de Control: <span class="subrayado">'. $nocontrol .'</span></td><td colspan="2"></td></tr>
    <tr><td colspan="2">Carrera: <span class="subrayado">'. $carrera .'</span></td><td colspan="2"></td></tr>
    <tr>
      <td>Semestre: <span class="subrayado">'. $semestre .'</span></td>
      <td>Grupo: <span class="subrayado">'. $grupo .'</span></td>
      <td>Turno: <span class="subrayado">'. $turno .'</span></td>
      <td>Aula: <span class="subrayado">'. $aula .'</span></td>
    </tr>
  </table>
  <h1>✂----------------------------------------------------------------------------------------------------------------------------------------------------------------------✂</h1>
  <table>
    <tr><td colspan="3"><h1>Describa su:</h1></td><td>FOLIO: <span class="subrayado">'. $folio .'</span></td></tr>
    <tr><td colspan="4" class="center">QUEJA   /     SUGERENCIA</td></tr>
    <tr><td colspan="4" class="center border">'. $mensaje .'</td></tr>
  </table>
  <table>
    <tr><td colspan="4">Fecha: <span class="subrayado">'. $fecha .'</span></td></tr>
    <tr><td colspan="4"><h1>Esta sección será llenada por el Subdirector Correspondiente.</h1></td></tr>
    <tr><td colspan="4">Respuesta:</td></tr>
    <tr><td colspan="4" class="subrayado">'. $respuesta .'</td></tr>
    <tr><td colspan="2" class="center">ATENTAMENTE:</td><td colspan="2" class="center">RECIBIDO POR:</td></tr>
    <tr>
      <td colspan="2" class="center"><img class="firma" src="' . $imgBase642 . '" alt="Firma 1"></td>
      <td colspan="2" class="center"><img class="firma" src="' . $imgBase643 . '" alt="Firma 2"></td>
    </tr>
    <tr><td colspan="2" class="center">'. $nombreSubdirector .'</td><td colspan="2" class="center">'. $nombreReceptor .'</td></tr>
  </table>
</body>
</html>
';

try {
    $mpdf->WriteHTML($html);
    $mpdf->Output('ITMER-CA-PO-001-01 FORMATO QUEJAS'. $folio .'.pdf', 'I');
} catch (\Mpdf\MpdfException $e) {
    echo $e->getMessage();
}
