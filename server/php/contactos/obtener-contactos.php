<?php

// http://localhost/residencia/server/php/contactos/obtener-contactos.php?pagina=1

require_once './../permisos.php'; // Incluir archivo de conexión

require_once './../conexion.php'; // Incluir archivo de conexión

// Cabecera para respuesta JSON
header('Content-Type: application/json');

// Obtener el parámetro de la página, por defecto será 1
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

// Configurar paginación
$registrosPorPagina = 3;
$offset = ($pagina - 1) * $registrosPorPagina;

try {
    // Preparar consulta para la tabla "contactos"
    $stmt = $pdo->prepare("SELECT idContacto, nombreCompleto, apellidoPaterno, apellidoMaterno, fechaNacimiento, telefono, correoElectronico, numeroTarjeta, puesto, departamento, perfil, fechaCreacion FROM contactos LIMIT ?, ?");
    $stmt->bindParam(1, $offset, PDO::PARAM_INT);
    $stmt->bindParam(2, $registrosPorPagina, PDO::PARAM_INT);
    $stmt->execute();

    $contactos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($contactos) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "ok" => true,
            "statusCode" => 200,
            "message" => "Contactos obtenidos correctamente.",
            "data" => $contactos
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 404,
            "message" => "No se encontraron contactos.",
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
