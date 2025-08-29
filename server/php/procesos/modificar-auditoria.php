<?php
require_once './../conexion.php';

session_start(); // asegúrate de iniciar sesión para usar $_SESSION

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Recibir datos POST
$idProceso = $_POST['idProceso'] ?? null;
$objetivo = $_POST['objetivo'] ?? null;
$alcance = $_POST['alcance'] ?? null;
$inicioApertura = $_POST['inicioApertura'] ?? null;
$finApertura = $_POST['finApertura'] ?? null;
$areaApertura = $_POST['areaApertura'] ?? null;
$inicioCierre = $_POST['inicioCierre'] ?? null;
$finCierre = $_POST['finCierre'] ?? null;
$areaCierre = $_POST['areaCierre'] ?? null;
$entregaEvidencia = $_POST['entregaEvidencia'] ?? null;
$idAuditorLider = $_POST['idAuditorLider'] ?? null;
$idAuditorLider2 = ($_POST['idAuditorLider2'] ?? null) !== "" ? $_POST['idAuditorLider2'] : null;
$idAuditorLider3 = ($_POST['idAuditorLider3'] ?? null) !== "" ? $_POST['idAuditorLider3'] : null;
$numero = $_POST['numero'] ?? null;
$tipoProcesoAuditoria = $_POST['proceso'] ?? null;
$idRecibe = $_POST['idUsuarioRecibe'] ?? null;

$usuarios = json_decode($_POST['usuarios'] ?? '[]', true);
$participantes = json_decode($_POST['participantes'] ?? '[]', true);
$personalContactado = json_decode($_POST['personalContactado'] ?? '[]', true);

$institutoNorte = filter_var($_POST['institutoNorte'] ?? false, FILTER_VALIDATE_BOOLEAN);
$institutoPoniente = filter_var($_POST['institutoPoniente'] ?? false, FILTER_VALIDATE_BOOLEAN);

$mejoras = json_decode($_POST['mejoras'] ?? '[]', true);
$comentarios = json_decode($_POST['comentarios'] ?? '[]', true);
$noConformidades = json_decode($_POST['noConformidades'] ?? '[]', true);
$conclusiones = json_decode($_POST['conclusiones'] ?? '[]', true);

// Validaciones básicas
if (!$idProceso) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Falta el identificador de proceso.",
        "data" => null
    ]);
    exit;
}
if (!$objetivo || !$alcance || !$idAuditorLider) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Faltan campos obligatorios como objetivo, alcance o auditor líder.",
        "data" => null
    ]);
    exit;
}

try {
    // Validar estado del proceso
    $stmtEstado = $pdo->prepare("SELECT estado FROM procesos WHERE idProceso = ?");
    $stmtEstado->execute([$idProceso]);
    $proceso = $stmtEstado->fetch(PDO::FETCH_ASSOC);

    if (!$proceso) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "No existe el proceso especificado.",
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
            "message" => "No se puede modificar la auditoría porque el proceso está cerrado o inactivo.",
            "data" => null
        ]);
        exit;
    }

    // Verificar si el usuario tiene permiso
    $rolUsuario = $_SESSION['rol'] ?? '';
    $idUsuarioActual = $_SESSION['idUsuario'] ?? 0;

    $usuarioAutorizado = false;

    if ($rolUsuario === 'Administrador') {
        $usuarioAutorizado = true;
    } else {
        $stmtVerificarUsuario = $pdo->prepare("SELECT 1 FROM procesos_usuarios WHERE idProceso = ? AND idUsuario = ?");
        $stmtVerificarUsuario->execute([$idProceso, $idUsuarioActual]);
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
            "message" => "No tienes permiso para modificar esta auditoría.",
            "data" => null
        ]);
        exit;
    }

    $pdo->beginTransaction();

    // Obtener idAuditoria de este idProceso
    $stmtFindAuditoria = $pdo->prepare("SELECT idAuditoria FROM auditorias WHERE idProceso = ?");
    $stmtFindAuditoria->execute([$idProceso]);
    $row = $stmtFindAuditoria->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        throw new Exception("No existe auditoría asociada a este proceso.");
    }
    $idAuditoria = $row['idAuditoria'];

    // Actualizar tabla procesos
    $stmtUpdateProceso = $pdo->prepare("UPDATE procesos SET tipoProceso = ? WHERE idProceso = ?");
    if (!$stmtUpdateProceso->execute(['Auditoría', $idProceso])) {
        $errorInfo = $stmtUpdateProceso->errorInfo();
        throw new Exception("Error actualizando proceso: " . implode(", ", $errorInfo));
    }

    // Actualizar tabla auditorias
    $stmtUpdateAuditoria = $pdo->prepare("UPDATE auditorias SET 
        fechaInicioApertura = ?, 
        fechaFinalApertura = ?, 
        areaApertura = ?, 
        tipoProceso = ?, 
        objetivo = ?, 
        alcance = ?, 
        idAuditorLider = ?, 
        idAuditorLider2 = ?, 
        idAuditorLider3 = ?, 
        idRecibe = ?, 
        fechaInicioCierre = ?, 
        fechaFinalCierre = ?, 
        areaCierre = ?, 
        fechaEntregaEvidencia = ?, 
        numeroAuditoria = ?
        WHERE idAuditoria = ?");
    $paramsAuditoria = [
        $inicioApertura,
        $finApertura,
        $areaApertura,
        $tipoProcesoAuditoria,
        $objetivo,
        $alcance,
        $idAuditorLider,
        $idAuditorLider2,
        $idAuditorLider3,
        $idRecibe,
        $inicioCierre,
        $finCierre,
        $areaCierre,
        $entregaEvidencia,
        $numero,
        $idAuditoria
    ];
    if (!$stmtUpdateAuditoria->execute($paramsAuditoria)) {
        $errorInfo = $stmtUpdateAuditoria->errorInfo();
        throw new Exception("Error actualizando auditoría: " . implode(", ", $errorInfo));
    }

    // Borrar e insertar relaciones

    // procesos_usuarios
    $pdo->prepare("DELETE FROM procesos_usuarios WHERE idProceso = ?")->execute([$idProceso]);
    $stmtPU = $pdo->prepare("INSERT INTO procesos_usuarios (idProceso, idUsuario) VALUES (?, ?)");
    foreach ($usuarios as $idUsuario) {
        $stmtPU->execute([$idProceso, $idUsuario]);
    }

    // usuarios_auditorias
    $pdo->prepare("DELETE FROM usuarios_auditorias WHERE idAuditoria = ?")->execute([$idAuditoria]);
    $stmtUA = $pdo->prepare("INSERT INTO usuarios_auditorias (idUsuario, idAuditoria) VALUES (?, ?)");
    foreach ($participantes as $idParticipante) {
        $stmtUA->execute([$idParticipante, $idAuditoria]);
    }

    // auditorias_institutos
    $pdo->prepare("DELETE FROM auditorias_institutos WHERE idAuditoria = ?")->execute([$idAuditoria]);
    $stmtAI = $pdo->prepare("INSERT INTO auditorias_institutos (idAuditoria, idInstituto) VALUES (?, ?)");
    if ($institutoNorte) $stmtAI->execute([$idAuditoria, 1]);
    if ($institutoPoniente) $stmtAI->execute([$idAuditoria, 2]);

    // mejoras
    $pdo->prepare("DELETE FROM mejoras WHERE idAuditoria = ?")->execute([$idAuditoria]);
    $stmtMejora = $pdo->prepare("INSERT INTO mejoras (idAuditoria, mejora) VALUES (?, ?)");
    foreach ($mejoras as $item) {
        $texto = $item['texto'] ?? null;
        if ($texto) $stmtMejora->execute([$idAuditoria, $texto]);
    }

    // comentarios
    $pdo->prepare("DELETE FROM comentarios WHERE idAuditoria = ?")->execute([$idAuditoria]);
    $stmtComentario = $pdo->prepare("INSERT INTO comentarios (idAuditoria, comentario) VALUES (?, ?)");
    foreach ($comentarios as $item) {
        $texto = $item['texto'] ?? null;
        if ($texto) $stmtComentario->execute([$idAuditoria, $texto]);
    }

    // noConformidades
    $pdo->prepare("DELETE FROM noConformidades WHERE idAuditoria = ?")->execute([$idAuditoria]);
    $stmtNC = $pdo->prepare("INSERT INTO noConformidades (idAuditoria, noConformidad, requisito) VALUES (?, ?, ?)");
    foreach ($noConformidades as $item) {
        $texto = $item['texto'] ?? null;
        $requisito = $item['requisito'];
        if ($texto) $stmtNC->execute([$idAuditoria, $texto, $requisito]);
    }

    // conclusiones
    $pdo->prepare("DELETE FROM conclusiones WHERE idAuditoria = ?")->execute([$idAuditoria]);
    $stmtConclusion = $pdo->prepare("INSERT INTO conclusiones (idAuditoria, conclusion) VALUES (?, ?)");
    foreach ($conclusiones as $item) {
        $texto = $item['texto'] ?? null;
        if ($texto) $stmtConclusion->execute([$idAuditoria, $texto]);
    }

    // personalContactado
    $pdo->prepare("DELETE FROM personalContactado WHERE idAuditoria = ?")->execute([$idAuditoria]);
    $stmtContacto = $pdo->prepare("INSERT INTO personalContactado (idContacto, idAuditoria) VALUES (?, ?)");
    foreach ($personalContactado as $idContacto) {
        if ($idContacto) $stmtContacto->execute([$idContacto, $idAuditoria]);
    }

    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Auditoría modificada exitosamente.",
        "data" => [
            "idProceso" => $idProceso,
            "idAuditoria" => $idAuditoria
        ]
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al modificar auditoría: " . $e->getMessage(),
        "data" => null
    ]);
}