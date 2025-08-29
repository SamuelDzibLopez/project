<?php
require_once './../conexion.php';

header('Content-Type: application/json');

// Leer datos
$idAuditoria = $_POST['idAuditoria'] ?? null;
$actividades = isset($_POST['actividades']) ? json_decode($_POST['actividades'], true) : [];

if (empty($idAuditoria) || empty($actividades)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "message" => "Faltan datos obligatorios (idAuditoria o actividades).",
    ]);
    exit;
}

try {
    $pdo->beginTransaction();

    // Obtener ids de actividades actuales
    $stmtGet = $pdo->prepare("SELECT idActividad FROM actividades WHERE idAuditoria = ?");
    $stmtGet->execute([$idAuditoria]);
    $ids = $stmtGet->fetchAll(PDO::FETCH_COLUMN);

    if ($ids) {
        $in = str_repeat('?,', count($ids) - 1) . '?';

        $pdo->prepare("DELETE FROM usuarios_actividades WHERE idActividad IN ($in)")->execute($ids);
        $pdo->prepare("DELETE FROM contactos_actividades WHERE idActividad IN ($in)")->execute($ids);
        $pdo->prepare("DELETE FROM actividades WHERE idActividad IN ($in)")->execute($ids);
    }

    // Insertar nuevas actividades
    $stmtAct = $pdo->prepare("INSERT INTO actividades (idAuditoria, fechaInicio, fechaFinal, tipoProceso, actividad, requisito, area) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmtUA = $pdo->prepare("INSERT INTO usuarios_actividades (idUsuario, idActividad) VALUES (?, ?)");
    $stmtCA = $pdo->prepare("INSERT INTO contactos_actividades (idContacto, idActividad) VALUES (?, ?)");

    foreach ($actividades as $a) {
        $stmtAct->execute([
            $idAuditoria,
            $a['inicio'] ?? null,
            $a['fin'] ?? null,
            $a['tipoProceso'] ?? '',
            $a['texto'] ?? '',
            $a['requisitoCriterio'] ?? '',
            $a['areaSitio'] ?? ''
        ]);
        $idActividad = $pdo->lastInsertId();

        foreach ($a['participantes'] ?? [] as $idUsuario) {
            $stmtUA->execute([$idUsuario, $idActividad]);
        }

        foreach ($a['contactos'] ?? [] as $idContacto) {
            $stmtCA->execute([$idContacto, $idActividad]);
        }
    }

    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "ok" => true,
        "message" => "Actividades actualizadas correctamente.",
    ]);
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "message" => "Error en transacción: " . $e->getMessage(),
    ]);
}
