<?php
require_once './../conexion.php'; // Incluir archivo de conexión

// Cabecera para indicar que la respuesta es JSON
header('Content-Type: application/json');

// Datos a insertar (pueden venir de $_POST si lo deseas)
$nombreCompleto     = 'Samuel';
$apellidoPaterno    = 'Dzib';
$apellidoMaterno    = 'López';
$fechaNacimiento    = '2002-11-21';
$telefono           = '9994249233';
$correoElectronico  = 'Patricio@gmail.com';
$numeroTarjeta      = '123456789';
$puesto             = 'Director';
$departamento       = 'General';
$perfil             = null; // Este campo es opcional y puede ir como null

// Consulta preparada para tabla contactos
$sql = "INSERT INTO contactos (
    nombreCompleto, apellidoPaterno, apellidoMaterno, fechaNacimiento, telefono,
    correoElectronico, numeroTarjeta, puesto, departamento, perfil
) VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $nombreCompleto, $apellidoPaterno, $apellidoMaterno, $fechaNacimiento, $telefono,
        $correoElectronico, $numeroTarjeta, $puesto, $departamento, $perfil
    ]);

    http_response_code(200); // OK

    $respuesta = [
        "status" => "success",
        "ok" => true,
        "statusCode" => 200,
        "message" => "Contacto insertado correctamente.",
        "data" => [
            "nombreCompleto" => $nombreCompleto,
            "correoElectronico" => $correoElectronico,
            "puesto" => $puesto
        ]
    ];
    echo json_encode($respuesta);
} catch (PDOException $e) {
    http_response_code(500); // Error interno del servidor

    $respuesta = [
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error al insertar contacto: " . $e->getMessage(),
        "data" => null
    ];
    echo json_encode($respuesta);
}
?>
