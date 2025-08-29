<?php 

require_once './../permisos.php'; // Incluir archivo de conexión

require_once './../conexion.php';

header('Content-Type: application/json');

// Obtener datos del formulario
$nombreCompleto    = $_POST['nombre'] ?? '';
$apellidoPaterno   = $_POST['apellido_paterno'] ?? '';
$apellidoMaterno   = $_POST['apellido_materno'] ?? '';
$fechaNacimiento   = $_POST['fecha_nacimiento'] ?? null;
$telefono          = $_POST['telefono'] ?? null;
$correoElectronico = $_POST['correo'] ?? '';
$numeroTarjeta     = $_POST['tarjeta'] ?? '';
$puesto            = $_POST['puesto'] ?? '';
$departamento      = $_POST['departamento'] ?? '';

// Validar campos obligatorios
if (
    empty($nombreCompleto) || empty($apellidoPaterno) || empty($apellidoMaterno) ||
    empty($correoElectronico) || empty($numeroTarjeta) || empty($puesto) || empty($departamento)
) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "Faltan campos obligatorios.",
        "data" => null
    ]);
    exit;
}

try {
    // Insertar en la tabla contactos
    $sql = "INSERT INTO contactos (
        nombreCompleto, apellidoPaterno, apellidoMaterno, fechaNacimiento,
        telefono, correoElectronico, numeroTarjeta, puesto, departamento
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $nombreCompleto,
        $apellidoPaterno,
        $apellidoMaterno,
        $fechaNacimiento,
        $telefono,
        $correoElectronico,
        $numeroTarjeta,
        $puesto,
        $departamento
    ]);

    $idContacto = $pdo->lastInsertId();

    // Función para guardar archivo de perfil
    function guardarArchivo($archivo, $directorio, $id, $sufijo) {
        if (isset($archivo) && $archivo['error'] === 0) {
            $ext = pathinfo($archivo['name'], PATHINFO_EXTENSION);
            $nombreArchivo = "{$id}_{$sufijo}." . strtolower($ext);
            $rutaCompleta = $directorio . $nombreArchivo;

            if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
                return $nombreArchivo;
            }
        }
        return null;
    }

    // Guardar archivo de perfil
    $perfil = guardarArchivo($_FILES['perfil'] ?? null, "../../perfiles-contactos/", $idContacto, "perfil");

    // Si se subió, actualizar el campo perfil
    if ($perfil) {
        $updateSQL = "UPDATE contactos SET perfil = ? WHERE idContacto = ?";
        $updateStmt = $pdo->prepare($updateSQL);
        $updateStmt->execute([$perfil, $idContacto]);
    }

    http_response_code(201);
    echo json_encode([
        "status" => "success",
        "ok" => true,
        "statusCode" => 201,
        "message" => "Contacto insertado correctamente.",
        "data" => [
            "idContacto" => $idContacto,
            "nombreCompleto" => $nombreCompleto,
            "perfil" => $perfil
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error al insertar contacto: " . $e->getMessage(),
        "data" => null
    ]);
}
?>
