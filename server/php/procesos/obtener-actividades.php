<?php
require_once './../conexion.php';

header('Content-Type: application/json');

// Obtener idAuditoria desde GET
$idAuditoria = $_GET['idAuditoria'] ?? null;

if (empty($idAuditoria)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "Falta el parámetro obligatorio: idAuditoria.",
        "data" => null
    ]);
    exit;
}

try {
    // Obtener todas las actividades de la auditoría
    $sql = "SELECT * FROM actividades WHERE idAuditoria = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idAuditoria]);
    $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($actividades as &$actividad) {
        $idActividad = $actividad['idActividad'];

        // Obtener participantes (idUsuario)
        $sqlParticipantes = "SELECT idUsuario FROM usuarios_actividades WHERE idActividad = ?";
        $stmtPart = $pdo->prepare($sqlParticipantes);
        $stmtPart->execute([$idActividad]);
        $actividad['participantes'] = array_column($stmtPart->fetchAll(PDO::FETCH_ASSOC), 'idUsuario');

        // Obtener contactos (idContacto)
        $sqlContactos = "SELECT idContacto FROM contactos_actividades WHERE idActividad = ?";
        $stmtCont = $pdo->prepare($sqlContactos);
        $stmtCont->execute([$idActividad]);
        $actividad['contactos'] = array_column($stmtCont->fetchAll(PDO::FETCH_ASSOC), 'idContacto');
    }

    echo json_encode([
        "status" => "success",
        "ok" => true,
        "statusCode" => 200,
        "message" => "Actividades recuperadas correctamente.",
        "data" => $actividades
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error al recuperar actividades: " . $e->getMessage(),
        "data" => null
    ]);
}
