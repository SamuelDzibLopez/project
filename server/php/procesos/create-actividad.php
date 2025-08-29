<?php

require_once './../permisos.php';
require_once './../conexion.php';

header('Content-Type: application/json');

// Obtener datos del formulario
$idAuditoria   = $_POST['idAuditoria'] ?? null;
$fechaInicio   = $_POST['fechaInicio'] ?? null;
$fechaFinal    = $_POST['fechaFinal'] ?? null;
$tipoProceso   = $_POST['tipoProceso'] ?? '';
$actividad     = $_POST['actividad'] ?? '';
$requisito     = $_POST['requisito'] ?? '';
$area          = $_POST['area'] ?? '';

// Arrays (convertidos desde JSON)
$contactos     = isset($_POST['contactos']) ? json_decode($_POST['contactos'], true) : [];
$participantes = isset($_POST['participantes']) ? json_decode($_POST['participantes'], true) : [];

// Validar campos obligatorios
if (empty($idAuditoria) || empty($actividad)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "Faltan campos obligatorios: idAuditoria y actividad.",
        "data" => null
    ]);
    exit;
}

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    // Insertar actividad
    $sql = "INSERT INTO actividades (
        idAuditoria, fechaInicio, fechaFinal,
        tipoProceso, actividad, requisito, area
    ) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $idAuditoria,
        $fechaInicio,
        $fechaFinal,
        $tipoProceso,
        $actividad,
        $requisito,
        $area
    ]);

    $idActividad = $pdo->lastInsertId();

    // Insertar participantes en usuarios_actividades
    if (!empty($participantes)) {
        $sqlParticipante = "INSERT INTO usuarios_actividades (idUsuario, idActividad) VALUES (?, ?)";
        $stmtParticipante = $pdo->prepare($sqlParticipante);
        foreach ($participantes as $idUsuario) {
            if ($idUsuario) {
                $stmtParticipante->execute([$idUsuario, $idActividad]);
            }
        }
    }

    // Insertar contactos en contactos_actividades
    if (!empty($contactos)) {
        $sqlContacto = "INSERT INTO contactos_actividades (idContacto, idActividad) VALUES (?, ?)";
        $stmtContacto = $pdo->prepare($sqlContacto);
        foreach ($contactos as $idContacto) {
            if ($idContacto) {
                $stmtContacto->execute([$idContacto, $idActividad]);
            }
        }
    }

    // Confirmar transacción
    $pdo->commit();

    http_response_code(201);
    echo json_encode([
        "status" => "success",
        "ok" => true,
        "statusCode" => 201,
        "message" => "Actividad insertada correctamente.",
        "data" => [
            "idActividad" => $idActividad,
            "actividad" => $actividad,
            "contactos" => $contactos,
            "participantes" => $participantes
        ]
    ]);
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error al insertar la actividad: " . $e->getMessage(),
        "data" => null
    ]);
}
?>
