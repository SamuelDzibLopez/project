<?php
session_start();
require_once './../conexion.php'; // Conexión a la base de datos

header('Content-Type: application/json');

// Verificar que la sesión esté activa y tenga flag en true
if (!isset($_SESSION['flag']) || $_SESSION['flag'] !== true || !isset($_SESSION['idUsuario'])) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 401,
        "message" => "Acceso no autorizado.",
        "data" => null
    ]);
    exit;
}

// Obtener la nueva contraseña desde POST
$nuevaContraseña = $_POST['nuevaContraseña'] ?? '';

if (!$nuevaContraseña || strlen(trim($nuevaContraseña)) < 6) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "La nueva contraseña es requerida y debe tener al menos 6 caracteres.",
        "data" => null
    ]);
    exit;
}

try {
    $idUsuario = $_SESSION['idUsuario'];

    // Actualizar la contraseña del usuario
    $stmt = $pdo->prepare("UPDATE usuarios SET contraseña = ? WHERE idUsuario = ?");
    $stmt->execute([
        password_hash($nuevaContraseña, PASSWORD_BCRYPT),
        $idUsuario
    ]);

    // Limpiar la bandera de sesión (opcional)
    unset($_SESSION['flag']);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "ok" => true,
        "statusCode" => 200,
        "message" => "Contraseña actualizada correctamente.",
        "data" => null
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error al actualizar contraseña: " . $e->getMessage(),
        "data" => null
    ]);
}
?>
