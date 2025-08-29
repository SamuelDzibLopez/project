<?php
require_once './../conexion.php';

session_start();

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Recibir datos POST
$objetivo = $_POST['objetivo'] ?? null;
$alcance = $_POST['alcance'] ?? null;
$inicioApertura = $_POST['inicioApertura'] ?? null;
$finApertura = $_POST['finApertura'] ?? null;
$areaApertura = $_POST['areaApertura'] ?? null;
$inicioCierre = $_POST['inicioCierre'] ?? null;
$finCierre = $_POST['finCierre'] ?? null;
$areaCierre = $_POST['areaCierre'] ?? null;
$entregaEvidencia = $_POST['entregaEvidencia'] ?? null;

$idAuditorLider  = $_POST['idAuditorLider'] ?? null;
$idAuditorLider2 = $_POST['idAuditorLider2'] ?? null;
$idAuditorLider3 = $_POST['idAuditorLider3'] ?? null;

// Convertir "" a null para evitar errores de integridad referencial
$idAuditorLider  = ($idAuditorLider === "")  ? null : $idAuditorLider;
$idAuditorLider2 = ($idAuditorLider2 === "") ? null : $idAuditorLider2;
$idAuditorLider3 = ($idAuditorLider3 === "") ? null : $idAuditorLider3;

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

try {
    $pdo->beginTransaction();

    $stmtProceso = $pdo->prepare("INSERT INTO procesos (tipoProceso) VALUES (?)");
    $stmtProceso->execute(['Auditoría']);
    $idProceso = $pdo->lastInsertId();

    $stmtAuditoria = $pdo->prepare("INSERT INTO auditorias (
        idProceso, fechaInicioApertura, fechaFinalApertura, areaApertura, tipoProceso,
        objetivo, alcance, idAuditorLider, idAuditorLider2, idAuditorLider3,
        idRecibe, fechaInicioCierre, fechaFinalCierre, areaCierre, fechaEntregaEvidencia,
        numeroAuditoria
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmtAuditoria->execute([
        $idProceso, $inicioApertura, $finApertura, $areaApertura, $tipoProcesoAuditoria,
        $objetivo, $alcance, $idAuditorLider, $idAuditorLider2, $idAuditorLider3,
        $idRecibe, $inicioCierre, $finCierre, $areaCierre, $entregaEvidencia, $numero
    ]);

    $idAuditoria = $pdo->lastInsertId();

    $stmtPU = $pdo->prepare("INSERT INTO procesos_usuarios (idProceso, idUsuario) VALUES (?, ?)");
    foreach ($usuarios as $idUsuario) {
        $stmtPU->execute([$idProceso, $idUsuario]);
    }

    $stmtUA = $pdo->prepare("INSERT INTO usuarios_auditorias (idUsuario, idAuditoria) VALUES (?, ?)");
    foreach ($participantes as $idParticipante) {
        $stmtUA->execute([$idParticipante, $idAuditoria]);
    }

    $stmtAI = $pdo->prepare("INSERT INTO auditorias_institutos (idAuditoria, idInstituto) VALUES (?, ?)");
    if ($institutoNorte) $stmtAI->execute([$idAuditoria, 1]);
    if ($institutoPoniente) $stmtAI->execute([$idAuditoria, 2]);

    $stmtMejora = $pdo->prepare("INSERT INTO mejoras (idAuditoria, mejora) VALUES (?, ?)");
    foreach ($mejoras as $item) {
        $texto = $item['texto'] ?? null;
        if ($texto) $stmtMejora->execute([$idAuditoria, $texto]);
    }

    $stmtComentario = $pdo->prepare("INSERT INTO comentarios (idAuditoria, comentario) VALUES (?, ?)");
    foreach ($comentarios as $item) {
        $texto = $item['texto'] ?? null;
        if ($texto) $stmtComentario->execute([$idAuditoria, $texto]);
    }

    $stmtNC = $pdo->prepare("INSERT INTO noConformidades (idAuditoria, noConformidad, requisito) VALUES (?, ?, ?)");
    foreach ($noConformidades as $item) {
        $texto = $item['texto'] ?? null;
        $requiito = $item['requisito'];
        if ($texto) $stmtNC->execute([$idAuditoria, $texto, $requiito]);
    }

    $stmtConclusion = $pdo->prepare("INSERT INTO conclusiones (idAuditoria, conclusion) VALUES (?, ?)");
    foreach ($conclusiones as $item) {
        $texto = $item['texto'] ?? null;
        if ($texto) $stmtConclusion->execute([$idAuditoria, $texto]);
    }

    $stmtContacto = $pdo->prepare("INSERT INTO personalContactado (idContacto, idAuditoria) VALUES (?, ?)");
    foreach ($personalContactado as $idContacto) {
        if ($idContacto) $stmtContacto->execute([$idContacto, $idAuditoria]);
    }

    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Auditoría registrada exitosamente.",
        "data" => ["idProceso" => $idProceso, "idAuditoria" => $idAuditoria]
    ]);
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al guardar auditoría: " . $e->getMessage(),
        "data" => null
    ]);
}
