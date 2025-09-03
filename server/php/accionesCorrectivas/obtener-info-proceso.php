<?php
header("Content-Type: application/json");
require_once './../conexion.php';

$idAC = isset($_GET['idAC']) ? $_GET['idAC'] : null;
$idProceso = isset($_GET['idProceso']) ? $_GET['idProceso'] : null;

if (!$idAC && !$idProceso) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "Falta el parámetro idAC o idProceso",
        "data" => null
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $pdo->beginTransaction();

    // Columnas seguras para usuarios
    $USER_COLUMNS = "
        u.idUsuario,
        u.nombreCompleto,
        u.apellidoPaterno,
        u.apellidoMaterno,
        u.fechaNacimiento,
        u.telefono,
        u.correoElectronico,
        u.numeroTarjeta,
        u.rol,
        u.puesto,
        u.departamento,
        u.perfil,
        u.estado,
        u.fechaCreacion,
        u.usuario,
        u.fechaVigencia,
        u.vigencia,
        COALESCE(u.firmaElectronica, '') AS firmaElectronica
    ";

    // Plantilla de usuario vacío
    $EMPTY_USER = [
        "idUsuario" => "",
        "nombreCompleto" => "",
        "apellidoPaterno" => "",
        "apellidoMaterno" => "",
        "fechaNacimiento" => "",
        "telefono" => "",
        "correoElectronico" => "",
        "numeroTarjeta" => "",
        "rol" => "",
        "puesto" => "",
        "departamento" => "",
        "perfil" => "",
        "estado" => "",
        "fechaCreacion" => "",
        "usuario" => "",
        "fechaVigencia" => "",
        "vigencia" => "",
        "firmaElectronica" => ""
    ];

    $getUserByIdStmt = $pdo->prepare("SELECT $USER_COLUMNS FROM usuarios u WHERE u.idUsuario = ?");

    $getUser = function($id) use ($getUserByIdStmt, $EMPTY_USER) {
        if (empty($id)) return $EMPTY_USER;
        $getUserByIdStmt->execute([$id]);
        return $getUserByIdStmt->fetch(PDO::FETCH_ASSOC) ?: $EMPTY_USER;
    };

    // Si enviaron idAC -> una sola acción correctiva
    if ($idAC) {
        $stmt = $pdo->prepare("SELECT * FROM accionesCorrectivas WHERE idAC = ?");
        $stmt->execute([$idAC]);
        $ac = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$ac) throw new Exception("Acción correctiva no encontrada");

        $stmt = $pdo->prepare("SELECT * FROM procesos WHERE idProceso = ?");
        $stmt->execute([$ac['idProceso']]);
        $proceso = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$proceso) throw new Exception("Proceso asociado no encontrado");

        $stmt = $pdo->prepare("SELECT * FROM noConformidades WHERE idNoConformidad = ?");
        $stmt->execute([$ac['idNoConformidad']]);
        $nc = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        // Resolver usuarios en la acción correctiva
        $ac['idResponsable_usuario'] = $getUser($ac['idResponsable']);
        $ac['idDefinir_usuario']     = $getUser($ac['idDefinir']);
        $ac['idVerificar_usuario']   = $getUser($ac['idVerificar']);
        $ac['idCoordinador_usuario'] = $getUser($ac['idCoordinador']);

        // Resolver usuarios en la noConformidad
        if ($nc) {
            $nc['idVerifica_usuario'] = $getUser($nc['idVerifica']);
            $nc['idLibera_usuario']   = $getUser($nc['idLibera']);
        }

        $stmt = $pdo->prepare("
            SELECT $USER_COLUMNS
            FROM procesos_usuarios pu
            JOIN usuarios u ON pu.idUsuario = u.idUsuario
            WHERE pu.idProceso = ?
        ");
        $stmt->execute([$proceso['idProceso']]);
        $usuariosProceso = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdo->commit();

        echo json_encode([
            "status" => "success",
            "ok" => true,
            "statusCode" => 200,
            "message" => "Acción correctiva obtenida correctamente",
            "data" => [
                "proceso" => $proceso,
                "accionCorrectiva" => $ac,
                "noConformidad" => $nc,
                "usuariosProceso" => $usuariosProceso
            ]
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Si enviaron idProceso -> todas las ACs
    $stmt = $pdo->prepare("SELECT * FROM procesos WHERE idProceso = ?");
    $stmt->execute([$idProceso]);
    $proceso = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$proceso) throw new Exception("Proceso no encontrado");

    $stmt = $pdo->prepare("SELECT * FROM accionesCorrectivas WHERE idProceso = ?");
    $stmt->execute([$idProceso]);
    $acs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmtNC = $pdo->prepare("SELECT * FROM noConformidades WHERE idNoConformidad = ?");
    foreach ($acs as &$acItem) {
        $acItem['noConformidad'] = null;
        if (!empty($acItem['idNoConformidad'])) {
            $stmtNC->execute([$acItem['idNoConformidad']]);
            $nc = $stmtNC->fetch(PDO::FETCH_ASSOC) ?: null;
            if ($nc) {
                $nc['idVerifica_usuario'] = $getUser($nc['idVerifica']);
                $nc['idLibera_usuario']   = $getUser($nc['idLibera']);
            }
            $acItem['noConformidad'] = $nc;
        }

        $acItem['idResponsable_usuario'] = $getUser($acItem['idResponsable']);
        $acItem['idDefinir_usuario']     = $getUser($acItem['idDefinir']);
        $acItem['idVerificar_usuario']   = $getUser($acItem['idVerificar']);
        $acItem['idCoordinador_usuario'] = $getUser($acItem['idCoordinador']);
    }
    unset($acItem);

    $stmt = $pdo->prepare("
        SELECT $USER_COLUMNS
        FROM procesos_usuarios pu
        JOIN usuarios u ON pu.idUsuario = u.idUsuario
        WHERE pu.idProceso = ?
    ");
    $stmt->execute([$idProceso]);
    $usuariosProceso = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "ok" => true,
        "statusCode" => 200,
        "message" => "Acciones correctivas obtenidas correctamente",
        "data" => [
            "proceso" => $proceso,
            "accionesCorrectivas" => $acs,
            "usuariosProceso" => $usuariosProceso
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => $e->getMessage(),
        "data" => null
    ], JSON_UNESCAPED_UNICODE);
}
