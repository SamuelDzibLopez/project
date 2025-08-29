<?php
require_once './../permisos.php';
require_once './../conexion.php';

header('Content-Type: application/json');

// Obtener idProceso de la query string (GET)
$idProceso = $_GET['idProceso'] ?? null;

if (!$idProceso || !is_numeric($idProceso)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "ID del proceso inválido o no proporcionado.",
        "data" => null
    ]);
    exit;
}

try {
    // Obtener estado actual
    $stmt = $pdo->prepare("SELECT estado FROM procesos WHERE idProceso = ?");
    $stmt->execute([$idProceso]);
    $proceso = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$proceso) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 404,
            "message" => "Proceso no encontrado.",
            "data" => null
        ]);
        exit;
    }

    // Alternar estado
    $nuevoEstado = ($proceso['estado'] == 1) ? 0 : 1;

    // Actualizar estado
    $stmtUpdate = $pdo->prepare("UPDATE procesos SET estado = ? WHERE idProceso = ?");
    $stmtUpdate->execute([$nuevoEstado, $idProceso]);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "ok" => true,
        "statusCode" => 200,
        "message" => "Estado modificado correctamente.",
        "data" => [
            "idProceso" => $idProceso,
            "nuevoEstado" => $nuevoEstado
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error al modificar estado: " . $e->getMessage(),
        "data" => null
    ]);
}
