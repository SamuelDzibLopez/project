<?php 

require_once './../permisos.php'; // Incluir archivo de conexión

require_once './../conexion.php'; // Asegúrate que aquí se establece $pdo como instancia de PDO

header('Content-Type: application/json');

// Obtener el ID del contacto desde la URL
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

// Obtener campos del formulario
$nombreCompleto    = $_POST['nombre_completo'] ?? '';
$apellidoPaterno   = $_POST['apellido_paterno'] ?? '';
$apellidoMaterno   = $_POST['apellido_materno'] ?? '';
$fechaNacimiento   = $_POST['fecha_nacimiento'] ?? '';
$telefono          = $_POST['telefono'] ?? '';
$correoElectronico = $_POST['correo_electronico'] ?? '';
$numeroTarjeta     = $_POST['numero_tarjeta'] ?? '';
$puesto            = $_POST['puesto'] ?? '';
$departamento      = $_POST['departamento'] ?? '';

// Procesar imagen si se envía
$perfil = null;
if (isset($_FILES['perfil']) && $_FILES['perfil']['error'] === 0) {
    $ruta = "../../perfiles-contactos/";
    $extension = pathinfo($_FILES['perfil']['name'], PATHINFO_EXTENSION);
    $nombreArchivo = $id_contacto . "." . $extension;
    move_uploaded_file($_FILES['perfil']['tmp_name'], $ruta . $nombreArchivo);
    $perfil = $nombreArchivo;
}

// Validación básica
$campos = [
    $nombreCompleto, $apellidoPaterno, $apellidoMaterno, $fechaNacimiento,
    $telefono, $correoElectronico, $numeroTarjeta, $puesto, $departamento
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

// Actualizar contacto
$sql = "UPDATE contactos SET 
    nombreCompleto = ?, 
    apellidoPaterno = ?, 
    apellidoMaterno = ?, 
    fechaNacimiento = ?, 
    telefono = ?, 
    correoElectronico = ?, 
    numeroTarjeta = ?, 
    puesto = ?, 
    departamento = ?, 
    perfil = COALESCE(?, perfil)
    WHERE idContacto = ?";

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
        $puesto,
        $departamento,
        $perfil,
        $id_contacto
    ]);

    // Obtener datos actualizados
    $sqlSelect = "SELECT * FROM contactos WHERE idContacto = ?";
    $stmtSelect = $pdo->prepare($sqlSelect);
    $stmtSelect->execute([$id_contacto]);
    $contactoActualizado = $stmtSelect->fetch(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "ok" => true,
        "statusCode" => 200,
        "message" => "Datos del contacto actualizados correctamente.",
        "data" => $contactoActualizado
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error al actualizar contacto: " . $e->getMessage(),
        "data" => null
    ]);
}
?>
