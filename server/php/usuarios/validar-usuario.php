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
    // Alternar el valor de 'estado' (0 a 1 o 1 a 0)
    $sql = "UPDATE usuarios SET estado = NOT estado WHERE idUsuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario]);

    if ($stmt->rowCount() > 0) {
        // Obtener el nuevo estado
        $sqlSelect = "SELECT idUsuario, estado FROM usuarios WHERE idUsuario = ?";
        $stmtSelect = $pdo->prepare($sqlSelect);
        $stmtSelect->execute([$id_usuario]);
        $usuario = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "ok" => true,
            "statusCode" => 200,
            "message" => "Estado del usuario actualizado correctamente.",
            "data" => $usuario,
            "nuevo_estado" => $usuario['estado'] // Aquí se incluye si es 1 o 0
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 404,
            "message" => "Usuario no encontrado o estado ya era el mismo.",
            "data" => null
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error al actualizar el estado: " . $e->getMessage(),
        "data" => null
    ]);
}
?>
