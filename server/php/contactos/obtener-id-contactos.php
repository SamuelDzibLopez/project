<?php
// obtener-contactos.php

require_once './../conexion.php';

header('Content-Type: application/json');

try {
    // Consulta de los contactos
    $stmt = $pdo->query("SELECT idContacto, nombreCompleto, apellidoPaterno, apellidoMaterno FROM contactos");
    $contactos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "message" => "Contactos obtenidos correctamente.",
        "data" => $contactos
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al obtener los contactos: " . $e->getMessage(),
        "data" => null
    ]);
}
?>
