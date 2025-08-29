<?php
session_start(); // Iniciar la sesión

require_once './../conexion.php'; // Conexión a la base de datos

require './../PHPMailer/src/Exception.php';
require './../PHPMailer/src/PHPMailer.php';
require './../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

// Obtener el correo desde GET (puedes cambiar a POST si lo prefieres)
$correo = isset($_GET['correoElectronico']) ? trim($_GET['correoElectronico']) : null;

// Validar parámetro
if (!$correo || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "Parámetro 'correoElectronico' no proporcionado o no válido.",
        "data" => null
    ]);
    exit;
}

try {
    // Consulta SQL para obtener solo el idUsuario por correo
    $stmt = $pdo->prepare("SELECT idUsuario FROM usuarios WHERE correoElectronico = ?");
    $stmt->bindParam(1, $correo, PDO::PARAM_STR);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Crear las variables de sesión
        $_SESSION['idUsuario'] = $usuario['idUsuario'];
        $_SESSION['codigoUno'] = rand(0, 9);
        $_SESSION['codigoDos'] = rand(0, 9);
        $_SESSION['codigoTres'] = rand(0, 9);
        $_SESSION['codigoCuatro'] = rand(0, 9);

        // Armado del código
        $codigoCompleto = 
            $_SESSION['codigoUno'] . 
            $_SESSION['codigoDos'] . 
            $_SESSION['codigoTres'] . 
            $_SESSION['codigoCuatro'];

        $_SESSION['codigoCompleto'] = $codigoCompleto;

        // Envío del código por correo
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ssamuel211102@gmail.com'; // Tu correo Gmail
            $mail->Password = 'rwrbmxseuaenweez'; // Contraseña de aplicación de Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Configuración del mensaje
            $mail->setFrom('Calidad@gmail.com', 'Sistema de Calidad');
            $mail->addAddress($correo);
            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Subject = 'Código de verificación de acceso';

            $mail->Body = '
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        padding: 20px;
                    }
                    .container {
                        background-color: #ffffff;
                        border-radius: 8px;
                        padding: 20px;
                        max-width: 500px;
                        margin: auto;
                        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                    }
                    h2 {
                        color: #2c3e50;
                    }
                    .codigo {
                        font-size: 24px;
                        font-weight: bold;
                        color: #27ae60;
                        background-color: #ecf0f1;
                        padding: 10px 20px;
                        display: inline-block;
                        border-radius: 6px;
                        margin-top: 10px;
                    }
                    p {
                        color: #34495e;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h2>Hola,</h2>
                    <p>Tu código de verificación es el siguiente:</p>
                    <div class="codigo">' . htmlspecialchars($codigoCompleto) . '</div>
                    <p>Por favor, usa este código para completar tu verificación.</p>
                    <p><em>Este código es confidencial y expira en unos minutos.</em></p>
                    <p>Gracias por usar nuestro sistema de calidad.</p>
                </div>
            </body>
            </html>
            ';

            $mail->AltBody = "Tu código de verificación es: $codigoCompleto";

            $mail->send();
        } catch (Exception $e) {
            error_log("No se pudo enviar el correo: {$mail->ErrorInfo}");
        }

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "ok" => true,
            "statusCode" => 200,
            "message" => "Usuario encontrado y sesión iniciada correctamente.",
            "data" => [
                "correoElectronico" => $correo,
                "codigo" => $codigoCompleto
            ]
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 404,
            "message" => "No se encontró un usuario con ese correo.",
            "data" => null
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error en la base de datos: " . $e->getMessage(),
        "data" => null
    ]);
}
?>
