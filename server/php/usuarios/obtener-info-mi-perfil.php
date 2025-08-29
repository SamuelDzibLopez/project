<?php

// Incluir archivo de conexión
require_once './../conexion.php'; 

// Iniciar la sesión para acceder a la variable $_SESSION
session_start();

// Verificar que el idUsuario esté presente en la sesión
if (!isset($_SESSION['idUsuario'])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "El idUsuario no está presente en la sesión.",
        "data" => null
    ]);
    exit;
}

// Obtener el idUsuario de la sesión
$idUsuario = $_SESSION['idUsuario'];

// Cabecera para respuesta JSON
header('Content-Type: application/json');

try {
    // Consulta para obtener los detalles del usuario por idUsuario
    $stmt = $pdo->prepare("SELECT idUsuario, nombreCompleto, apellidoPaterno, apellidoMaterno, fechaNacimiento, telefono, correoElectronico, numeroTarjeta, rol, puesto, departamento, perfil, estado, fechaCreacion, fechaVigencia, vigencia, firmaElectronica, usuario FROM usuarios WHERE idUsuario = ?");
    $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
    $stmt->execute();

    // Obtener los resultados
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró el usuario
    if ($usuario) {
        // Respuesta exitosa
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "ok" => true,
            "statusCode" => 200,
            "message" => "Usuario obtenido correctamente.",
            "data" => $usuario
        ]);
    } else {
        // No se encontró el usuario
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
    // Error en la consulta
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
