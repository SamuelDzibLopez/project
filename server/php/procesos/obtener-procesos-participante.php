<?php

session_start();

require_once './../conexion.php'; // Conexión a la base de datos

// Cabecera para respuesta JSON
header('Content-Type: application/json');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idUsuario'])) {
    http_response_code(401);
    echo json_encode([
        "status" => "unauthorized",
        "ok" => false,
        "statusCode" => 401,
        "message" => "Acceso no autorizado. Usuario no autenticado.",
        "data" => null
    ]);
    exit;
}

$idUsuario = $_SESSION['idUsuario'];

try {
    // Consulta con INNER JOIN para obtener los procesos relacionados con el usuario
    $sql = "
        SELECT p.idProceso, p.tipoProceso, p.fechaCreacion
        FROM procesos p
        INNER JOIN procesos_usuarios pu ON p.idProceso = pu.idProceso
        WHERE pu.idUsuario = ?
        ORDER BY p.fechaCreacion DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idUsuario]);

    $procesos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($procesos) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "ok" => true,
            "statusCode" => 200,
            "message" => "Procesos obtenidos correctamente.",
            "data" => $procesos
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 404,
            "message" => "No se encontraron procesos relacionados con este usuario.",
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
