<?php
// Ejemplo de uso:
// http://localhost/residencia/server/php/contactos/obtener-info-contacto.php?idContacto=3

require_once './../permisos.php'; // Incluir archivo de conexión

require_once './../conexion.php'; // Incluir archivo de conexión

header('Content-Type: application/json'); // Cabecera para respuesta JSON

// Obtener el idContacto desde la URL con método GET
$idContacto = isset($_GET['idContacto']) ? intval($_GET['idContacto']) : null;

// Verificar que el idContacto fue proporcionado
if (!$idContacto) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "Parámetro 'idContacto' no proporcionado o no válido.",
        "data" => null
    ]);
    exit;
}

try {
    // Consulta para obtener los detalles del contacto por idContacto
    $stmt = $pdo->prepare("
        SELECT 
            idContacto, nombreCompleto, apellidoPaterno, apellidoMaterno,
            fechaNacimiento, telefono, correoElectronico, numeroTarjeta,
            puesto, departamento, perfil, fechaCreacion
        FROM contactos
        WHERE idContacto = ?
    ");
    $stmt->bindParam(1, $idContacto, PDO::PARAM_INT);
    $stmt->execute();

    $contacto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($contacto) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "ok" => true,
            "statusCode" => 200,
            "message" => "Contacto obtenido correctamente.",
            "data" => $contacto
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 404,
            "message" => "No se encontró el contacto.",
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
