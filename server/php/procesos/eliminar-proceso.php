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
    // Verificar si el proceso existe
    $stmt = $pdo->prepare("SELECT * FROM procesos WHERE idProceso = ?");
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

    // Eliminar el proceso
    $stmtDelete = $pdo->prepare("DELETE FROM procesos WHERE idProceso = ?");
    $stmtDelete->execute([$idProceso]);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "ok" => true,
        "statusCode" => 200,
        "message" => "Proceso eliminado correctamente.",
        "data" => [
            "idProceso" => $idProceso
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error al eliminar proceso: " . $e->getMessage(),
        "data" => null
    ]);
}
