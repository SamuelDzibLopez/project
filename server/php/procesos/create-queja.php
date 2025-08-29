<?php
require_once './../permisos.php';
require_once './../conexion.php';

header('Content-Type: application/json');

// Capturar los datos del proceso
$tipoProceso =  "Queja o sugerencia";

// Capturar los datos de la queja
$fecha             = $_POST['fecha'] ?? null;
$folio             = $_POST['folio'] ?? null;
$nombre            = $_POST['nombre'] ?? null;
$correoElectronico = $_POST['correo'] ?? null;
$telefono          = $_POST['telefono'] ?? null;
$matricula         = $_POST['matricula'] ?? null;
$carrera           = $_POST['carrera'] ?? null;
$semestre          = $_POST['semestre'] ?? null;
$grupo             = $_POST['grupo'] ?? null;
$turno             = $_POST['turno'] ?? null;
$aula              = $_POST['aula'] ?? null;
$queja             = $_POST['queja'] ?? null;
$respuesta         = $_POST['respuesta'] ?? null;
$idSubdirector     = $_POST['id_subdirector'] ?? null;
$idReceptor        = $_SESSION['idUsuario'];

// Validar que tipoProceso y correo estén presentes
if (!$tipoProceso || !$correoElectronico) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Faltan campos obligatorios: tipo_proceso y correo son requeridos.",
        "data" => null
    ]);
    exit;
}

try {
    // 1. Insertar el proceso
    $sqlProceso = "INSERT INTO procesos (tipoProceso) VALUES (?)";
    $stmtProceso = $pdo->prepare($sqlProceso);
    $stmtProceso->execute([$tipoProceso]);

    $idProceso = $pdo->lastInsertId();

    // 2. Insertar la queja asociada al proceso
    $sqlQueja = "INSERT INTO quejas (
        idProceso, fecha, folio, nombre, correoElectronico, telefono,
        matricula, carrera, semestre, grupo, turno, aula, queja,
        respuesta, idSubdirector, idReceptor
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )";

    $stmtQueja = $pdo->prepare($sqlQueja);
    $stmtQueja->execute([
        $idProceso,
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
        $idReceptor
    ]);

    $idQueja = $pdo->lastInsertId();

    // 3. Insertar en procesos_usuarios
    if ($idSubdirector) {
        $sqlPU = "INSERT INTO procesos_usuarios (idProceso, idUsuario) VALUES (?, ?)";
        $stmtPU = $pdo->prepare($sqlPU);
        $stmtPU->execute([$idProceso, $idSubdirector]);
    }

    // Respuesta exitosa
    http_response_code(201);
    echo json_encode([
        "status" => "success",
        "message" => "Proceso, queja y asignación insertados correctamente.",
        "data" => [
            "idProceso" => $idProceso,
            "idQueja" => $idQueja
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error en la inserción: " . $e->getMessage(),
        "data" => null
    ]);
}
?>
