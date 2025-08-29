<?php

//http://localhost/residencia/server/php/procesos/obtener-info-queja.php?idProceso=5
require_once './../verificacion.php';
require_once './../conexion.php';

header('Content-Type: application/json');

// Validar parámetro GET
$idProceso = $_GET['idProceso'] ?? null;

if (!$idProceso || !is_numeric($idProceso)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Parámetro 'idProceso' inválido o no proporcionado.",
        "data" => null
    ]);
    exit;
}

// Función para obtener usuario por ID, incluyendo firmaElectronica
function obtenerUsuarioPorId($pdo, $idUsuario) {
    if (!$idUsuario) return null;
    $stmt = $pdo->prepare("
        SELECT 
            idUsuario, 
            nombreCompleto, 
            apellidoPaterno, 
            apellidoMaterno, 
            correoElectronico, 
            telefono, 
            rol, 
            puesto, 
            departamento,
            firmaElectronica
        FROM usuarios 
        WHERE idUsuario = ?
    ");
    $stmt->execute([$idUsuario]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

try {
    // 1. Obtener tipo de proceso
    $stmtProceso = $pdo->prepare("SELECT * FROM procesos WHERE idProceso = ?");
    $stmtProceso->execute([$idProceso]);
    $proceso = $stmtProceso->fetch(PDO::FETCH_ASSOC);

    if (!$proceso) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "No se encontró el proceso con ID $idProceso.",
            "data" => null
        ]);
        exit;
    }

    $tipo = strtolower($proceso['tipoProceso']);

    // 2. Consultar la tabla correspondiente
    $dataDetalle = null;
    if ($tipo === "queja o sugerencia") {
        $stmtDetalle = $pdo->prepare("SELECT * FROM quejas WHERE idProceso = ?");
        $stmtDetalle->execute([$idProceso]);
        $dataDetalle = $stmtDetalle->fetch(PDO::FETCH_ASSOC);
    } elseif ($tipo === "auditoria") {
        $stmtDetalle = $pdo->prepare("SELECT * FROM auditorias WHERE idProceso = ?");
        $stmtDetalle->execute([$idProceso]);
        $dataDetalle = $stmtDetalle->fetch(PDO::FETCH_ASSOC);
    } else {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Tipo de proceso no reconocido: {$proceso['tipoProceso']}",
            "data" => null
        ]);
        exit;
    }

    if (!$dataDetalle) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "No se encontraron datos adicionales del proceso.",
            "data" => null
        ]);
        exit;
    }

    // 3. Traer datos de usuario subdirector y receptor si existen
    $subdirector = null;
    if (!empty($dataDetalle['idSubdirector'])) {
        $subdirector = obtenerUsuarioPorId($pdo, $dataDetalle['idSubdirector']);
    }

    $receptor = null;
    if (!empty($dataDetalle['idReceptor'])) {
        $receptor = obtenerUsuarioPorId($pdo, $dataDetalle['idReceptor']);
    }

    // 4. Éxito con datos completos
    echo json_encode([
        "status" => "success",
        "message" => "Datos del proceso obtenidos correctamente.",
        "data" => [
            "proceso" => $proceso,
            "detalle" => $dataDetalle,
            "subdirector" => $subdirector,
            "receptor" => $receptor
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al obtener los datos: " . $e->getMessage(),
        "data" => null
    ]);
}
?>
