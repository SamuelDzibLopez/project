<?php

require_once './../permisos.php'; // Incluir archivo de conexión

require_once './../conexion.php';

header('Content-Type: application/json');

$id_usuario = isset($_GET['idUsuario']) ? intval($_GET['idUsuario']) : null;

if (!$id_usuario) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "No se proporcionó un ID de usuario válido.",
        "data" => null
    ]);
    exit;
}

try {
    // Eliminar el usuario de la base de datos
    $sql = "DELETE FROM usuarios WHERE idUsuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario]);

    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "ok" => true,
            "statusCode" => 200,
            "message" => "Usuario eliminado correctamente.",
            "data" => [ "idUsuario" => $id_usuario ]
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 404,
            "message" => "Usuario no encontrado o ya fue eliminado.",
            "data" => null
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error al eliminar el usuario: " . $e->getMessage(),
        "data" => null
    ]);
}
?>
