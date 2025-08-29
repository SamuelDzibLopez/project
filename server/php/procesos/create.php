<?php
require_once './../conexion.php'; // Incluir archivo de conexión

// Cabecera para indicar que la respuesta es JSON
header('Content-Type: application/json');

// Datos a insertar (pueden venir de $_POST si lo deseas)
$tipoProceso = 'Proceso de prueba'; // Ejemplo, puedes cambiarlo o tomarlo de $_POST

// Consulta preparada
$sql = "INSERT INTO procesos (tipoProceso) VALUES (?)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$tipoProceso]);

    http_response_code(200); // Código HTTP para OK

    $respuesta = [
        "status" => "success",
        "ok" => true,
        "statusCode" => 200,
        "message" => "Proceso insertado correctamente.",
        "data" => [
            "idProceso" => $pdo->lastInsertId(),
            "tipoProceso" => $tipoProceso
        ]
    ];
    echo json_encode($respuesta);
} catch (PDOException $e) {
    http_response_code(500); // Código HTTP para error del servidor

    $respuesta = [
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error al insertar proceso: " . $e->getMessage(),
        "data" => null
    ];
    echo json_encode($respuesta);
}
?>
