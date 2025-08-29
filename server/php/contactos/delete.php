<?php 
require_once './../permisos.php'; // Incluir archivo de conexión

require_once './../conexion.php';

header('Content-Type: application/json');

// Obtener el idContacto desde la URL
$id_contacto = isset($_GET['idContacto']) ? intval($_GET['idContacto']) : null;

if (!$id_contacto) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "No se proporcionó un ID de contacto válido.",
        "data" => null
    ]);
    exit;
}

try {
    // Eliminar el contacto de la base de datos
    $sql = "DELETE FROM contactos WHERE idContacto = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_contacto]);

    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "ok" => true,
            "statusCode" => 200,
            "message" => "Contacto eliminado correctamente.",
            "data" => [ "idContacto" => $id_contacto ]
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 404,
            "message" => "Contacto no encontrado o ya fue eliminado.",
            "data" => null
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error al eliminar el contacto: " . $e->getMessage(),
        "data" => null
    ]);
}
?>
