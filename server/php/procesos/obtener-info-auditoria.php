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

function reemplazarFirmaElectronica(&$usuario) {
    if (!isset($usuario['firmaElectronica']) || $usuario['firmaElectronica'] === null || trim($usuario['firmaElectronica']) === '') {
        $usuario['firmaElectronica'] = 'null.js';
    }
}

function obtenerUsuarioSeguro($usuario) {
    if (!$usuario) {
        return [
            'nombreCompleto' => '',
            'apellidoPaterno' => '',
            'apellidoMaterno' => '',
            'firmaElectronica' => 'null.png'
        ];
    }
    if (!isset($usuario['firmaElectronica']) || $usuario['firmaElectronica'] === null || trim($usuario['firmaElectronica']) === '') {
        $usuario['firmaElectronica'] = 'null.js';
    }
    return $usuario;
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
    $idRecibe = $auditoria['idRecibe'];
    $idAuditorLider = $auditoria['idAuditorLider'];
    $idAuditorLider2 = $auditoria['idAuditorLider2'];
    $idAuditorLider3 = $auditoria['idAuditorLider3'];

    // 2. Obtener datos del usuario que recibe
    $stmt = $pdo->prepare("SELECT nombreCompleto, apellidoPaterno, apellidoMaterno, firmaElectronica FROM usuarios WHERE idUsuario = ?");
    $stmt->execute([$idRecibe]);
    $usuarioRecibe = $stmt->fetch(PDO::FETCH_ASSOC);
    reemplazarFirmaElectronica($usuarioRecibe);

// 2. Obtener datos del usuario que recibe
$stmt = $pdo->prepare("SELECT nombreCompleto, apellidoPaterno, apellidoMaterno, firmaElectronica FROM usuarios WHERE idUsuario = ?");
$stmt->execute([$idRecibe]);
$usuarioRecibe = obtenerUsuarioSeguro($stmt->fetch(PDO::FETCH_ASSOC));

    // 3. Obtener datos del auditor líder
    $stmt = $pdo->prepare("SELECT nombreCompleto, apellidoPaterno, apellidoMaterno, firmaElectronica FROM usuarios WHERE idUsuario = ?");
    $stmt->execute([$idAuditorLider]);
    $auditorLider = obtenerUsuarioSeguro($stmt->fetch(PDO::FETCH_ASSOC));

    // 3. Obtener datos del auditor líder2
    $stmt = $pdo->prepare("SELECT nombreCompleto, apellidoPaterno, apellidoMaterno, firmaElectronica FROM usuarios WHERE idUsuario = ?");
    $stmt->execute([$idAuditorLider2]);
    $auditorLider2 = obtenerUsuarioSeguro($stmt->fetch(PDO::FETCH_ASSOC));

    // 3. Obtener datos del auditor líder3
    $stmt = $pdo->prepare("SELECT nombreCompleto, apellidoPaterno, apellidoMaterno, firmaElectronica FROM usuarios WHERE idUsuario = ?");
    $stmt->execute([$idAuditorLider3]);
    $auditorLider3 = obtenerUsuarioSeguro($stmt->fetch(PDO::FETCH_ASSOC));

    // 4. Usuarios con acceso
    $stmt = $pdo->prepare("SELECT idUsuario FROM procesos_usuarios WHERE idProceso = ?");
    $stmt->execute([$idProceso]);
    $usuarios = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // 5. Participantes (solo IDs)
    $stmt = $pdo->prepare("SELECT idUsuario FROM usuarios_auditorias WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $participantesIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // 5a. Obtener datos completos de participantes
    $participantes = [];
    if (count($participantesIds) > 0) {
        $placeholders = implode(',', array_fill(0, count($participantesIds), '?'));
        $stmt = $pdo->prepare("SELECT idUsuario, nombreCompleto, apellidoPaterno, apellidoMaterno, puesto, departamento, firmaElectronica FROM usuarios WHERE idUsuario IN ($placeholders)");
        $stmt->execute($participantesIds);
        $participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($participantes as &$participante) {
            reemplazarFirmaElectronica($participante);
        }
    }

    // 6. Personal contactado
    $stmt = $pdo->prepare("SELECT idContacto FROM personalContactado WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $personalContactadoIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $personalContactado = [];
    if (count($personalContactadoIds) > 0) {
        $placeholders = implode(',', array_fill(0, count($personalContactadoIds), '?'));
        $stmt = $pdo->prepare("SELECT idContacto, nombreCompleto, apellidoPaterno, apellidoMaterno, puesto, departamento FROM contactos WHERE idContacto IN ($placeholders)");
        $stmt->execute($personalContactadoIds);
        $personalContactado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 7. Institutos
    $stmt = $pdo->prepare("SELECT idInstituto FROM auditorias_institutos WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $institutos = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $institutoNorte = in_array(1, $institutos);
    $institutoPoniente = in_array(2, $institutos);

    // 8. Mejoras
    $stmt = $pdo->prepare("SELECT idMejora, mejora AS texto FROM mejoras WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $mejoras = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 9. Comentarios
    $stmt = $pdo->prepare("SELECT idComentario, comentario AS texto FROM comentarios WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 10. No Conformidades
    $stmt = $pdo->prepare("SELECT idNoConformidad, requisito, noConformidad AS texto FROM noConformidades WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $noConformidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 11. Conclusiones
    $stmt = $pdo->prepare("SELECT idConclusion, conclusion AS texto FROM conclusiones WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $conclusiones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 12. Actividades
    $stmt = $pdo->prepare("SELECT * FROM actividades WHERE idAuditoria = ?");
    $stmt->execute([$idAuditoria]);
    $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($actividades as &$actividad) {
        $idActividad = $actividad['idActividad'];

        // Participantes de la actividad
        $stmtPart = $pdo->prepare("SELECT idUsuario FROM usuarios_actividades WHERE idActividad = ?");
        $stmtPart->execute([$idActividad]);
        $participantesIdsActividad = $stmtPart->fetchAll(PDO::FETCH_COLUMN);

        $actividad['participantes'] = [];
        if (count($participantesIdsActividad) > 0) {
            $placeholders = implode(',', array_fill(0, count($participantesIdsActividad), '?'));
            $stmtPartFull = $pdo->prepare("SELECT idUsuario, nombreCompleto, apellidoPaterno, apellidoMaterno, firmaElectronica FROM usuarios WHERE idUsuario IN ($placeholders)");
            $stmtPartFull->execute($participantesIdsActividad);
            $actividad['participantes'] = $stmtPartFull->fetchAll(PDO::FETCH_ASSOC);

            foreach ($actividad['participantes'] as &$participanteAct) {
                reemplazarFirmaElectronica($participanteAct);
            }
        }

        // Contactos de la actividad
        $stmtCont = $pdo->prepare("SELECT idContacto FROM contactos_actividades WHERE idActividad = ?");
        $stmtCont->execute([$idActividad]);
        $contactosIds = $stmtCont->fetchAll(PDO::FETCH_COLUMN);

        $actividad['contactos'] = [];
        if (count($contactosIds) > 0) {
            $placeholders = implode(',', array_fill(0, count($contactosIds), '?'));
            $stmtContFull = $pdo->prepare("SELECT idContacto, nombreCompleto, apellidoPaterno, apellidoMaterno FROM contactos WHERE idContacto IN ($placeholders)");
            $stmtContFull->execute($contactosIds);
            $actividad['contactos'] = $stmtContFull->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    // 13. Respuesta
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
            "idAuditorLider" => $idAuditorLider,
            "idAuditorLider2" => $idAuditorLider2,
            "idAuditorLider3" => $idAuditorLider3,
            "auditorLider" => $auditorLider,
            "auditorLider2" => $auditorLider2,
            "auditorLider3" => $auditorLider3,
            "idUsuarioRecibe" => $idRecibe,
            "usuarioRecibe" => $usuarioRecibe,
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
            "conclusiones" => $conclusiones,
            "actividades" => $actividades
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

