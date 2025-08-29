<?php  
require_once './../conexion.php';
session_start(); // Asegúrate de iniciar la sesión

header('Content-Type: application/json');

// Leer el JSON desde el cuerpo de la solicitud
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validar que se recibió correctamente el JSON
if (!$data) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "No se pudo decodificar el JSON.",
        "data" => null
    ]);
    exit;
}

// Obtener los valores del JSON
$idProceso         = $data['idProceso'] ?? null;
$fecha             = $data['fecha'] ?? null;
$folio             = $data['folio'] ?? null;
$nombre            = $data['nombre'] ?? null;
$correoElectronico = $data['correo'] ?? null;
$telefono          = $data['telefono'] ?? null;
$matricula         = $data['matricula'] ?? null;
$carrera           = $data['carrera'] ?? null;
$semestre          = $data['semestre'] ?? null;
$grupo             = $data['grupo'] ?? null;
$turno             = $data['turno'] ?? null;
$aula              = $data['aula'] ?? null;
$queja             = $data['queja'] ?? null;
$respuesta         = $data['respuesta'] ?? null;
$idSubdirector     = $data['id_subdirector'] ?? null;

// ID y rol del usuario receptor (de la sesión)
$idReceptor = $_SESSION['idUsuario'] ?? null;
$rolUsuario = $_SESSION['rol'] ?? null;

if (!$idProceso || !is_numeric($idProceso) || !$idReceptor) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "ID del proceso o usuario inválido o no proporcionado.",
        "data" => null
    ]);
    exit;
}

try {
    // Verificar si el usuario tiene permiso
    $usuarioAutorizado = false;

    if ($rolUsuario === 'Administrador') {
        $usuarioAutorizado = true;
    } else {
        $stmtVerificarUsuario = $pdo->prepare("SELECT 1 FROM procesos_usuarios WHERE idProceso = ? AND idUsuario = ?");
        $stmtVerificarUsuario->execute([$idProceso, $idReceptor]);
        $usuarioVinculado = $stmtVerificarUsuario->fetch(PDO::FETCH_ASSOC);

        if ($usuarioVinculado) {
            $usuarioAutorizado = true;
        }
    }

    if (!$usuarioAutorizado) {
        http_response_code(403);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 403,
            "message" => "No tienes permiso para modificar esta queja.",
            "data" => null
        ]);
        exit;
    }

    // Verificar estado del proceso
    $stmtEstado = $pdo->prepare("SELECT estado FROM procesos WHERE idProceso = ?");
    $stmtEstado->execute([$idProceso]);
    $proceso = $stmtEstado->fetch(PDO::FETCH_ASSOC);

    if (!$proceso) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 404,
            "message" => "Proceso no encontrado.",
            "data" => null
        ]);
        exit;
    }

    if ($proceso['estado'] != 1) {
        http_response_code(403);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 403,
            "message" => "No se puede modificar la queja porque el proceso está cerrado o inactivo.",
            "data" => null
        ]);
        exit;
    }

    // Verificar existencia de la queja
    $stmtCheck = $pdo->prepare("SELECT idQueja FROM quejas WHERE idProceso = ?");
    $stmtCheck->execute([$idProceso]);
    $quejaExistente = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$quejaExistente) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 404,
            "message" => "No se encontró una queja asociada al proceso.",
            "data" => null
        ]);
        exit;
    }

    // Actualizar queja
    $sql = "UPDATE quejas SET
        fecha = ?, folio = ?, nombre = ?, correoElectronico = ?, telefono = ?, matricula = ?,
        carrera = ?, semestre = ?, grupo = ?, turno = ?, aula = ?, queja = ?, respuesta = ?,
        idSubdirector = ?
        WHERE idProceso = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $fecha,
        $folio,
        $nombre,
        $correoElectronico,
        $telefono,
        $matricula,
        $carrera,
        $semestre,
        $grupo,
        $turno,
        $aula,
        $queja,
        $respuesta,
        $idSubdirector,
        $idProceso
    ]);

    // Verificar si ya existe un registro en procesos_usuarios con ese idProceso
    $stmtCheckPU = $pdo->prepare("SELECT COUNT(*) as total FROM procesos_usuarios WHERE idProceso = ?");
    $stmtCheckPU->execute([$idProceso]);
    $existePU = $stmtCheckPU->fetch(PDO::FETCH_ASSOC)['total'] > 0;

    if ($existePU) {
        // Si existe, actualiza el idUsuario
        $stmtUpdatePU = $pdo->prepare("UPDATE procesos_usuarios SET idUsuario = ? WHERE idProceso = ?");
        $stmtUpdatePU->execute([$idSubdirector, $idProceso]);
    } else {
        // Si no existe, crea el registro
        $stmtInsertPU = $pdo->prepare("INSERT INTO procesos_usuarios (idProceso, idUsuario) VALUES (?, ?)");
        $stmtInsertPU->execute([$idProceso, $idSubdirector]);
    }

    // Éxito
    http_response_code(201);
    echo json_encode([
        "status" => "success",
        "ok" => true,
        "statusCode" => 201,
        "message" => "Queja y usuario actualizados correctamente.",
        "data" => [
            "idProceso" => $idProceso
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error al actualizar: " . $e->getMessage(),
        "data" => null
    ]);
}
