<?php
// obtener-usuarios.php

require_once './../conexion.php';

header('Content-Type: application/json');

try {
    // Consulta de los usuarios
    $stmt = $pdo->query("SELECT idUsuario, nombreCompleto, apellidoPaterno, apellidoMaterno FROM usuarios");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "message" => "Usuarios obtenidos correctamente.",
        "data" => $usuarios
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al obtener los usuarios: " . $e->getMessage(),
        "data" => null
    ]);
}
?>
