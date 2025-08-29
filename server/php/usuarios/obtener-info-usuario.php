<?php
// http://localhost/residencia/server/php/usuarios/obtener-info-usuario.php?idUsuario=3

require_once './../permisos.php'; // Incluir archivo de conexión

// Incluir archivo de conexión
require_once './../conexion.php'; 

// Cabecera para respuesta JSON
header('Content-Type: application/json');

// Obtener el idUsuario desde la URL con método GET
$idUsuario = isset($_GET['idUsuario']) ? intval($_GET['idUsuario']) : null;

// Verificar que el idUsuario fue proporcionado
if (!$idUsuario) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "Parámetro 'idUsuario' no proporcionado o no válido.",
        "data" => null
    ]);
    exit;
}

try {
    // Consulta para obtener los detalles del usuario por idUsuario
    $stmt = $pdo->prepare("SELECT idUsuario, nombreCompleto, apellidoPaterno, apellidoMaterno, fechaNacimiento, telefono, correoElectronico, numeroTarjeta, rol, puesto, departamento, perfil, estado, fechaCreacion, fechaVigencia, vigencia, firmaElectronica, usuario FROM usuarios WHERE idUsuario = ?");
    $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
    $stmt->execute();

    // Obtener los resultados
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "ok" => true,
            "statusCode" => 200,
            "message" => "Usuario obtenido correctamente.",
            "data" => $usuario
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 404,
            "message" => "No se encontró el usuario.",
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
