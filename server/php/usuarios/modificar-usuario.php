<?php

require_once './../permisos.php'; // Incluir archivo de conexión

require_once './../conexion.php'; // Asegúrate que aquí se establece $pdo como instancia de PDO

header('Content-Type: application/json');

// Obtener el ID del usuario desde la URL
$id_usuario = isset($_GET['idUsuario']) ? intval($_GET['idUsuario']) : null;

if (!$id_usuario) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "No se proporcionó un ID de usuario válido.",
        "data" => null
    ]);
    exit;
}

// Obtener campos del formulario
$usuario           = $_POST['user'] ?? '';
$nombreCompleto    = $_POST['nombre_completo'] ?? '';
$apellidoPaterno   = $_POST['apellido_paterno'] ?? '';
$apellidoMaterno   = $_POST['apellido_materno'] ?? '';
$fechaNacimiento   = $_POST['fecha_nacimiento'] ?? '';
$telefono          = $_POST['telefono'] ?? '';
$correoElectronico = $_POST['correo_electronico'] ?? '';
$numeroTarjeta     = $_POST['numero_tarjeta'] ?? '';
$rol               = $_POST['rol'] ?? '';
$puesto            = $_POST['puesto'] ?? '';
$departamento      = $_POST['departamento'] ?? '';
$fechaVigencia     = $_POST['fecha_vigencia'] ?? '';

// Variables para archivos
$perfil           = null;
$vigencia         = null;
$firmaElectronica = null;

// Procesar archivos si se envían
if (isset($_FILES['perfil']) && $_FILES['perfil']['error'] === 0) {
    $ruta = "../../perfiles/";
    $extension = pathinfo($_FILES['perfil']['name'], PATHINFO_EXTENSION);
    $nombreArchivo = $id_usuario . "." . $extension;
    move_uploaded_file($_FILES['perfil']['tmp_name'], $ruta . $nombreArchivo);
    $perfil = $nombreArchivo;
}

if (isset($_FILES['vigencia']) && $_FILES['vigencia']['error'] === 0) {
    $ruta = "../../vigencias/";
    $extension = pathinfo($_FILES['vigencia']['name'], PATHINFO_EXTENSION);
    $nombreArchivo = $id_usuario . "." . $extension;
    move_uploaded_file($_FILES['vigencia']['tmp_name'], $ruta . $nombreArchivo);
    $vigencia = $nombreArchivo;
}

if (isset($_FILES['firma']) && $_FILES['firma']['error'] === 0) {
    $ruta = "../../firmas/";
    $extension = pathinfo($_FILES['firma']['name'], PATHINFO_EXTENSION);
    $nombreArchivo = $id_usuario . "." . $extension;
    move_uploaded_file($_FILES['firma']['tmp_name'], $ruta . $nombreArchivo);
    $firmaElectronica = $nombreArchivo;
}

// Validación simple
$campos = [
    $usuario, $nombreCompleto, $apellidoPaterno, $apellidoMaterno, $fechaNacimiento,
    $telefono, $correoElectronico, $numeroTarjeta, $rol, $puesto, $departamento, $fechaVigencia
];

$hayDatos = false;
foreach ($campos as $campo) {
    if (!empty($campo)) {
        $hayDatos = true;
        break;
    }
}

if (!$hayDatos) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "No se proporcionaron datos para actualizar.",
        "data" => null
    ]);
    exit;
}

// Consulta actualizada con todos los campos
$sql = "UPDATE usuarios SET 
    nombreCompleto = ?, 
    apellidoPaterno = ?, 
    apellidoMaterno = ?, 
    fechaNacimiento = ?, 
    telefono = ?, 
    correoElectronico = ?, 
    numeroTarjeta = ?, 
    rol = ?, 
    puesto = ?, 
    departamento = ?, 
    fechaVigencia = ?, 
    perfil = COALESCE(?, perfil), 
    vigencia = COALESCE(?, vigencia), 
    firmaElectronica = COALESCE(?, firmaElectronica),
    usuario = ?
    WHERE idUsuario = ?";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $nombreCompleto,
        $apellidoPaterno,
        $apellidoMaterno,
        $fechaNacimiento,
        $telefono,
        $correoElectronico,
        $numeroTarjeta,
        $rol,
        $puesto,
        $departamento,
        $fechaVigencia,
        $perfil,
        $vigencia,
        $firmaElectronica,
        $usuario,
        $id_usuario
    ]);

    // Obtener datos actualizados del usuario
    $sqlSelect = "SELECT idUsuario, nombreCompleto, apellidoPaterno, perfil, usuario, rol FROM usuarios WHERE idUsuario = ?";
    $stmtSelect = $pdo->prepare($sqlSelect);
    $stmtSelect->execute([$id_usuario]);
    $usuarioActualizado = $stmtSelect->fetch(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "ok" => true,
        "statusCode" => 200,
        "message" => "Datos del usuario actualizados correctamente.",
        "data" => $usuarioActualizado
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error al actualizar usuario: " . $e->getMessage(),
        "data" => null
    ]);
}
?>
