<?php
// Ruta relativa a la carpeta PHPMailer
require '../app/PHPMailer/src/PHPMailer.php';
require '../app/PHPMailer/src/SMTP.php';
require '../app/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP (Ejemplo con Gmail)
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'tu_correo@gmail.com'; // Cambia esto
    $mail->Password   = 'tu_contraseña_o_clave_app'; // Cambia esto
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Remitente y destinatario
    $mail->setFrom('tu_correo@gmail.com', 'Tu nombre');
    $mail->addAddress($_POST['email'], 'Destinatario');

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = $_POST['asunto'];
    $mail->Body    = $_POST['mensaje'];

    $mail->send();
    echo 'Correo enviado correctamente.';
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
?>
