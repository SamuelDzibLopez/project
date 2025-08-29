<?php
header("Content-Type: application/json");
require_once './../conexion.php';

$idProceso = $_GET['idProceso'] ?? null;
if (!$idProceso) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "Falta el parámetro idProceso",
        "data" => null
    ]);
    exit;
}

try {
    $pdo->beginTransaction();

    // --- 1. Proceso ---
    $stmt = $pdo->prepare("SELECT * FROM procesos WHERE idProceso = ?");
    $stmt->execute([$idProceso]);
    $proceso = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$proceso) throw new Exception("Proceso no encontrado");

    // --- 2. Auditoría ---
    $stmt = $pdo->prepare("SELECT * FROM auditorias WHERE idProceso = ?");
    $stmt->execute([$idProceso]);
    $auditoria = $stmt->fetch(PDO::FETCH_ASSOC);
    $idAuditoria = $auditoria['idAuditoria'] ?? null;

    // --- 3. Usuarios del proceso (toda la info) ---
    $stmt = $pdo->prepare("SELECT u.* FROM procesos_usuarios pu
                           JOIN usuarios u ON pu.idUsuario = u.idUsuario
                           WHERE pu.idProceso = ?");
    $stmt->execute([$idProceso]);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // --- 4. Actividades ---
    $stmt = $pdo->prepare("SELECT * FROM actividades WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($actividades as &$act) {
        $idActividad = $act['idActividad'];

        // Participantes (toda la info)
        $stmt = $pdo->prepare("SELECT u.* FROM participantes p
                               JOIN usuarios u ON p.idUsuario = u.idUsuario
                               WHERE p.idActividad = ?");
        $stmt->execute([$idActividad]);
        $act['participantes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Contactados (toda la info)
        $stmt = $pdo->prepare("SELECT c.* FROM contactados ct
                               JOIN contactos c ON ct.idContacto = c.idContacto
                               WHERE ct.idActividad = ?");
        $stmt->execute([$idActividad]);
        $act['contactados'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- 5. Institutos ---
    $stmt = $pdo->prepare("SELECT i.* FROM auditorias_institutos ai
                           JOIN institutos i ON ai.idInstituto = i.idInstituto
                           WHERE ai.idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $institutos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // --- 6. Personal Contactado (toda la info) ---
    $stmt = $pdo->prepare("SELECT c.* FROM personalContactado pc
                           JOIN contactos c ON pc.idContacto = c.idContacto
                           WHERE pc.idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $personalContactado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // --- 7. Auditores (toda la info) ---
    $stmt = $pdo->prepare("SELECT u.* FROM auditores a
                           JOIN usuarios u ON a.idUsuario = u.idUsuario
                           WHERE a.idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $auditores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // --- 8. Auditores líderes (toda la info) ---
    $stmt = $pdo->prepare("SELECT u.* FROM auditoresLideres al
                           JOIN usuarios u ON al.idUsuario = u.idUsuario
                           WHERE al.idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $auditoresLideres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // --- 9. Oportunidades ---
    $stmt = $pdo->prepare("SELECT * FROM oportunidades WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $oportunidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // --- 10. Comentarios ---
    $stmt = $pdo->prepare("SELECT * FROM comentarios WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // --- 11. Conclusiones ---
    $stmt = $pdo->prepare("SELECT * FROM conclusiones WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $conclusiones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // --- 12. No conformidades ---
    $stmt = $pdo->prepare("SELECT * FROM noConformidades WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $noConformidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "ok" => true,
        "statusCode" => 200,
        "message" => "Datos del proceso y auditoría obtenidos correctamente",
        "data" => [
            "proceso" => $proceso,
            "auditoria" => $auditoria,
            "usuarios" => $usuarios,
            "actividades" => $actividades,
            "institutos" => $institutos,
            "personalContactado" => $personalContactado,
            "auditores" => $auditores,
            "auditoresLideres" => $auditoresLideres,
            "oportunidades" => $oportunidades,
            "comentarios" => $comentarios,
            "conclusiones" => $conclusiones,
            "noConformidades" => $noConformidades
        ]
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => $e->getMessage(),
        "data" => null
    ]);
}
