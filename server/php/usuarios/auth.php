<?php
session_start(); // Iniciar sesión al comienzo del archivo

require_once './../conexion.php'; // Incluir conexión a base de datos

// Cabecera para respuesta JSON
header('Content-Type: application/json');

// Leer datos de entrada
$input = json_decode(file_get_contents('php://input'), true);

$usuario    = $input['usuario'];
$contrasena = $input['contrasena'];

// Validar que se recibieron ambos campos
if (!$usuario || !$contrasena) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "Faltan datos: usuario o contraseña.",
        "data" => null
    ]);
    exit;
}

try {
    // Buscar al usuario por nombre de usuario (sin filtrar aún por estado)
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $usuarioEncontrado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuarioEncontrado) {
        if (!password_verify($contrasena, $usuarioEncontrado['contraseña'])) {
            http_response_code(401);
            echo json_encode([
                "status" => "error",
                "ok" => false,
                "statusCode" => 401,
                "message" => "Contraseña incorrecta.",
                "data" => null
            ]);
            exit;
        }

        if ($usuarioEncontrado['estado'] != 1) {
            http_response_code(403);
            echo json_encode([
                "status" => "error",
                "ok" => false,
                "statusCode" => 403,
                "message" => "Tu cuenta está inactiva. Contacta al administrador.",
                "data" => null
            ]);
            exit;
        }

        // Login exitoso: almacenar variables de sesión
        $_SESSION['idUsuario'] = $usuarioEncontrado['idUsuario'];
        $_SESSION['nombreCompleto'] = $usuarioEncontrado['nombreCompleto'];
        $_SESSION['apellidoPaterno'] = $usuarioEncontrado['apellidoPaterno'];
        $_SESSION['perfil'] = $usuarioEncontrado['perfil'];
        $_SESSION['usuario'] = $usuarioEncontrado['usuario'];
        $_SESSION['rol'] = $usuarioEncontrado['rol'];

        // Respuesta exitosa
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "ok" => true,
            "statusCode" => 200,
            "message" => "Inicio de sesión exitoso.",
            "data" => [
                "nombreCompleto" => $usuarioEncontrado['nombreCompleto'],
                "rol" => $usuarioEncontrado['rol'],
                "correoElectronico" => $usuarioEncontrado['correoElectronico']
            ]
        ]);
    } else {
        // Usuario no encontrado
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 404,
            "message" => "Usuario no encontrado.",
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
