<?php

require_once './../conexion.php';

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$idProceso = $_GET['idProceso'] ?? null;

if (!$idProceso) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Falta el parámetro idProceso.",
        "data" => null
    ]);
    exit;
}

try {
    // 1. Obtener auditoría
    $sql = "SELECT * FROM auditorias WHERE idProceso = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idProceso]);
    $auditoria = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$auditoria) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "Auditoría no encontrada.",
            "data" => null
        ]);
        exit;
    }

    $idAuditoria = $auditoria['idAuditoria'];

    // 2. Usuarios con acceso
    $stmt = $pdo->prepare("SELECT idUsuario FROM procesos_usuarios WHERE idProceso = ?");
    $stmt->execute([$idProceso]);
    $usuarios = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // 3. Participantes
    $stmt = $pdo->prepare("SELECT idUsuario FROM usuarios_auditorias WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $participantes = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // 4. Personal contactado
    $stmt = $pdo->prepare("SELECT idContacto FROM personalContactado WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $personalContactado = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // 5. Institutos
    $stmt = $pdo->prepare("SELECT idInstituto FROM auditorias_institutos WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $institutos = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $institutoNorte = in_array(1, $institutos);
    $institutoPoniente = in_array(2, $institutos);

    // 6. Mejoras (con idMejora)
    $stmt = $pdo->prepare("SELECT idMejora, mejora AS texto FROM mejoras WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $mejoras = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 7. Comentarios (con idComentario)
    $stmt = $pdo->prepare("SELECT idComentario, comentario AS texto FROM comentarios WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 8. No Conformidades (con idNoConformidad)
    $stmt = $pdo->prepare("SELECT idNoConformidad, requisito, noConformidad AS texto FROM noConformidades WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $noConformidades = $stmt->fetchAll(PDO::FETCH_ASSOC);    

    // 9. Conclusiones (con idConclusion)
    $stmt = $pdo->prepare("SELECT idConclusion, conclusion AS texto FROM conclusiones WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $conclusiones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Respuesta JSON
    echo json_encode([
        "status" => "success",
        "message" => "Auditoría encontrada.",
        "data" => [
            "idProceso" => $idProceso,
            "idAuditoria" => $idAuditoria,
            "fechaInicioApertura" => $auditoria['fechaInicioApertura'],
            "fechaFinalApertura" => $auditoria['fechaFinalApertura'],
            "areaApertura" => $auditoria['areaApertura'],
            "tipoProceso" => $auditoria['tipoProceso'],
            "objetivo" => $auditoria['objetivo'],
            "alcance" => $auditoria['alcance'],
            "idAuditorLider" => $auditoria['idAuditorLider'],
            "idAuditorLider2" => $auditoria['idAuditorLider2'],
            "idAuditorLider3" => $auditoria['idAuditorLider3'],

            "idUsuarioRecibe" => $auditoria['idRecibe'],
            "fechaInicioCierre" => $auditoria['fechaInicioCierre'],
            "fechaFinalCierre" => $auditoria['fechaFinalCierre'],
            "areaCierre" => $auditoria['areaCierre'],
            "entregaEvidencia" => $auditoria['fechaEntregaEvidencia'],
            "numero" => $auditoria['numeroAuditoria'],
            "usuarios" => $usuarios,
            "participantes" => $participantes,
            "personalContactado" => $personalContactado,
            "institutoNorte" => $institutoNorte,
            "institutoPoniente" => $institutoPoniente,
            "mejoras" => $mejoras,
            "comentarios" => $comentarios,
            "noConformidades" => $noConformidades,
            "conclusiones" => $conclusiones
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al consultar auditoría: " . $e->getMessage(),
        "data" => null
    ]);
}
