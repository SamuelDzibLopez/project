<?php 
require_once './../permisos.php'; // Incluir archivo de conexión

require_once './../conexion.php';

header('Content-Type: application/json');

// Campos
$usuario        = $_POST['usuario'] ?? '';
$contraseña     = $_POST['contraseña'] ?? '';
$nombreCompleto = $_POST['nombre'] ?? '';
$apellidoPaterno   = $_POST['apellido_paterno'] ?? null;
$apellidoMaterno   = $_POST['apellido_materno'] ?? null;
$fechaNacimiento   = $_POST['fecha_nacimiento'] ?? null;
$telefono          = $_POST['telefono'] ?? null;
$correoElectronico = $_POST['correo'] ?? null;
$numeroTarjeta     = $_POST['tarjeta'] ?? null;
$rol               = $_POST['rol'] ?? null;
$puesto            = $_POST['puesto'] ?? null;
$departamento      = $_POST['departamento'] ?? null;
$fechaVigencia     = $_POST['fecha_vigencia'] ?? null;

// Insertar sin archivos primero
$sql = "INSERT INTO usuarios (
    nombreCompleto, apellidoPaterno, apellidoMaterno, fechaNacimiento, telefono,
    correoElectronico, numeroTarjeta, rol, puesto, departamento, fechaVigencia,
    usuario, contraseña, estado
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";

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
        $usuario,
        password_hash($contraseña, PASSWORD_BCRYPT)
    ]);

    $idUsuario = $pdo->lastInsertId(); // Obtener el ID insertado

    // Guardar archivos con el ID como nombre
    function guardarArchivo($archivo, $directorio, $id, $sufijo) {
        if (isset($archivo) && $archivo['error'] === 0) {
            $ext = pathinfo($archivo['name'], PATHINFO_EXTENSION);
            $nombreArchivo = "{$id}." . strtolower($ext);
            $rutaCompleta = $directorio . $nombreArchivo;

            if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
                return $nombreArchivo;
            }
        }
        return null;
    }

    $perfil           = guardarArchivo($_FILES['perfil'] ?? null, "../../perfiles/", $idUsuario, "perfil");
    $vigencia         = guardarArchivo($_FILES['vigencia'] ?? null, "../../vigencias/", $idUsuario, "vigencia");
    $firmaElectronica = guardarArchivo($_FILES['firma'] ?? null, "../../firmas/", $idUsuario, "firma");

    // Actualizar usuario con nombres de archivo
    $updateSQL = "UPDATE usuarios SET perfil = ?, vigencia = ?, firmaElectronica = ? WHERE idUsuario = ?";
    $updateStmt = $pdo->prepare($updateSQL);
    $updateStmt->execute([$perfil, $vigencia, $firmaElectronica, $idUsuario]);

    http_response_code(201);
    echo json_encode([
        "status" => "success",
        "ok" => true,
        "statusCode" => 201,
        "message" => "Usuario insertado correctamente.",
        "data" => [
            "idUsuario" => $idUsuario,
            "usuario" => $usuario,
            "nombreCompleto" => $nombreCompleto,
            "archivos" => [
                "perfil" => $perfil,
                "vigencia" => $vigencia,
                "firma" => $firmaElectronica
            ]
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error al insertar usuario: " . $e->getMessage(),
        "data" => null
    ]);
}
?>
