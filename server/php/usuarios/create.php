<?php
require_once './../conexion.php'; // Incluir archivo de conexión

// Cabecera para indicar que la respuesta es JSON
header('Content-Type: application/json');

// Datos a insertar (pueden venir de $_POST si lo deseas)
$nombreCompleto     = 'Sara del C.';
$apellidoPaterno    = 'Pastrana';
$apellidoMaterno    = 'Contreras';
$fechaNacimiento    = '2002-11-21';
$telefono           = '';
$correoElectronico  = 'ssamuel211102@gmail.com';
$numeroTarjeta      = '';
$rol                = 'Administrador';
$puesto             = 'Director';
$departamento       = 'General';
$perfil             = null;
$estado             = 1;
$usuario            = 'Administrador';
$contrasena         = password_hash('c4l1d4d@2o25', PASSWORD_DEFAULT); // Contraseña segura
$fechaVigencia      = '2026-05-08';
$vigencia           = null;
$firmaElectronica   = null;

// Consulta preparada
$sql = "INSERT INTO usuarios (
    nombreCompleto, apellidoPaterno, apellidoMaterno, fechaNacimiento, telefono,
    correoElectronico, numeroTarjeta, rol, puesto, departamento,
    perfil, estado, usuario, contraseña,
    fechaVigencia, vigencia, firmaElectronica
) VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $nombreCompleto, $apellidoPaterno, $apellidoMaterno, $fechaNacimiento, $telefono,
        $correoElectronico, $numeroTarjeta, $rol, $puesto, $departamento,
        $perfil, $estado, $usuario, $contrasena,
        $fechaVigencia, $vigencia, $firmaElectronica
    ]);

    http_response_code(200); // Código HTTP para OK

    $respuesta = [
        "status" => "success",
        "ok" => true,
        "statusCode" => 200,
        "message" => "Usuario insertado correctamente.",
        "data" => [
            "nombreCompleto" => $nombreCompleto,
            "correoElectronico" => $correoElectronico,
            "rol" => $rol
        ]
    ];
    echo json_encode($respuesta);
} catch (PDOException $e) {
    http_response_code(500); // Código HTTP para error del servidor

    $respuesta = [
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error al insertar usuario: " . $e->getMessage(),
        "data" => null
    ];
    echo json_encode($respuesta);
}
?>
