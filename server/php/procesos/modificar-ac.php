<?php

require_once './../conexion.php';
session_start();

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function valorONull($valor) {
    return (isset($valor) && $valor !== '') ? $valor : null;
}

// 1. Obtener datos por POST
$idProceso = valorONull($_POST['idProceso'] ?? null);

if (!$idProceso) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Falta el identificador del proceso.", "data" => null]);
    exit;
}

try {
    // Verificar estado del proceso
    $stmtEstado = $pdo->prepare("SELECT estado FROM procesos WHERE idProceso = ?");
    $stmtEstado->execute([$idProceso]);
    $proceso = $stmtEstado->fetch(PDO::FETCH_ASSOC);

    if (!$proceso) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Proceso no encontrado.", "data" => null]);
        exit;
    }

    if ($proceso['estado'] != 1) {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "El proceso está cerrado o inactivo.", "data" => null]);
        exit;
    }

    // Verificar permisos
    $rolUsuario = $_SESSION['rol'] ?? '';
    $idUsuarioActual = $_SESSION['idUsuario'] ?? 0;
    $usuarioAutorizado = false;

    if ($rolUsuario === 'Administrador') {
        $usuarioAutorizado = true;
    } else {
        $stmtVerificaUsuario = $pdo->prepare("SELECT 1 FROM procesos_usuarios WHERE idProceso = ? AND idUsuario = ?");
        $stmtVerificaUsuario->execute([$idProceso, $idUsuarioActual]);
        $usuarioAutorizado = $stmtVerificaUsuario->fetch() ? true : false;
    }

    if (!$usuarioAutorizado) {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "No tienes permiso para modificar este proceso.", "data" => null]);
        exit;
    }

    $pdo->beginTransaction();

    // Obtener ID de la acción correctiva asociada
    $stmtBuscarAC = $pdo->prepare("SELECT idAccionCorrectiva FROM accionesCorrectivas WHERE idProceso = ?");
    $stmtBuscarAC->execute([$idProceso]);
    $idAccionCorrectiva = $stmtBuscarAC->fetchColumn();

    if (!$idAccionCorrectiva) {
        throw new Exception("No se encontró acción correctiva asociada al proceso.");
    }

    // 2. Obtener campos simples
    $folio               = valorONull($_POST['folio'] ?? null);
    $areaProceso         = valorONull($_POST['areaProceso'] ?? null);
    $fecha               = valorONull($_POST['fecha'] ?? null);
    $origenRequisito     = valorONull($_POST['origenRequisito'] ?? null);
    $fuenteNC            = valorONull($_POST['fuenteNC'] ?? null);
    $descripcion         = valorONull($_POST['descripcion'] ?? null);
    $idDefine            = valorONull($_POST['idUsuarioDefine'] ?? null);
    $idVerifica          = valorONull($_POST['idUsuarioVerifica'] ?? null);
    $idCoordinador       = valorONull($_POST['idUsuarioCoordinador'] ?? null);
    $requiereAC          = isset($_POST['requiereAC']) && $_POST['requiereAC'] === "false" ? 0 : 1;
    $requiereCorreccion  = isset($_POST['requiereCorreccion']) && $_POST['requiereCorreccion'] === "false" ? 0 : 1;
    $tecnicaUtilizada    = valorONull($_POST['tecnicaUtilizada'] ?? null);
    $causaRaizIdentificada = valorONull($_POST['causaRaizIdentificada'] ?? null);
    $ACRealizar          = valorONull($_POST['ACRealizar'] ?? null);
    $Similares           = isset($_POST['Similares']) && $_POST['Similares'] === "false" ? 0 : 1;
    $ACSimilares         = valorONull($_POST['ACSimilares'] ?? null);
    $potenciales         = isset($_POST['potenciales']) && $_POST['potenciales'] === "false" ? 0 : 1;
    $ACPotenciales       = valorONull($_POST['ACPotenciales'] ?? null);
    $seguimiento         = valorONull($_POST['seguimiento'] ?? null);
    $actualizar          = isset($_POST['actualizar']) && $_POST['actualizar'] === "false" ? 0 : 1;
    $ACActualizar        = valorONull($_POST['ACActualizar'] ?? null);
    $cambios             = isset($_POST['cambios']) && $_POST['cambios'] === "false" ? 0 : 1;
    $ACCambios           = valorONull($_POST['ACCambios'] ?? null);

    // 3. Actualizar tabla accionesCorrectivas
    $stmtActualizar = $pdo->prepare("UPDATE accionesCorrectivas SET
        folio = ?, areaProceso = ?, fecha = ?, origenRequisito = ?, fuenteNC = ?, descripcion = ?,
        idDefine = ?, idVerifica = ?, idCoordinador = ?,
        requiereAC = ?, requiereCorreccion = ?,
        tecnicaUtilizada = ?, causaRaizIdentificada = ?, ACRealizar = ?,
        Similares = ?, ACSimilares = ?,
        potenciales = ?, ACPotenciales = ?,
        seguimiento = ?, actualizar = ?, ACActualizar = ?,
        cambios = ?, ACCambios = ?
        WHERE idAccionCorrectiva = ?");

    $stmtActualizar->execute([
        $folio, $areaProceso, $fecha, $origenRequisito, $fuenteNC, $descripcion,
        $idDefine, $idVerifica, $idCoordinador,
        $requiereAC, $requiereCorreccion,
        $tecnicaUtilizada, $causaRaizIdentificada, $ACRealizar,
        $Similares, $ACSimilares,
        $potenciales, $ACPotenciales,
        $seguimiento, $actualizar, $ACActualizar,
        $cambios, $ACCambios,
        $idAccionCorrectiva
    ]);

    // 4. Borrar registros existentes relacionados
    $pdo->prepare("DELETE FROM correcciones WHERE idAccionCorrectiva = ?")->execute([$idAccionCorrectiva]);
    $pdo->prepare("DELETE FROM acciones WHERE idAccionCorrectiva = ?")->execute([$idAccionCorrectiva]);
    $pdo->prepare("DELETE FROM procesos_usuarios WHERE idProceso = ?")->execute([$idProceso]);

    // 5. Insertar correcciones
    $correcciones = json_decode($_POST['correcciones'] ?? '[]', true);
    if (!empty($correcciones)) {
        $stmtCorreccion = $pdo->prepare("INSERT INTO correcciones (
            idAccionCorrectiva, correccion, idResponsable, fecha
        ) VALUES (?, ?, ?, ?)");
        foreach ($correcciones as $c) {
            $stmtCorreccion->execute([
                $idAccionCorrectiva,
                valorONull($c['texto'] ?? null),
                valorONull($c['idResponsable'] ?? null),
                valorONull($c['fecha'] ?? null)
            ]);
        }
    }

    // 6. Insertar acciones
    $acciones = json_decode($_POST['acciones'] ?? '[]', true);
    if (!empty($acciones)) {
        $stmtAccion = $pdo->prepare("INSERT INTO acciones (
            idAccionCorrectiva, accion, idResponsable, fecha
        ) VALUES (?, ?, ?, ?)");
        foreach ($acciones as $a) {
            $stmtAccion->execute([
                $idAccionCorrectiva,
                valorONull($a['texto'] ?? null),
                valorONull($a['idResponsable'] ?? null),
                valorONull($a['fecha'] ?? null)
            ]);
        }
    }

    // 7. Insertar usuarios relacionados
    $usuarios = json_decode($_POST['usuarios'] ?? '[]', true);
    if (!empty($usuarios)) {
        $stmtUsuario = $pdo->prepare("INSERT INTO procesos_usuarios (idProceso, idUsuario) VALUES (?, ?)");
        foreach ($usuarios as $idUsuario) {
            $stmtUsuario->execute([$idProceso, $idUsuario]);
        }
    }

    $pdo->commit();

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Acción Correctiva modificada exitosamente.",
        "data" => [
            "idProceso" => $idProceso,
            "idAccionCorrectiva" => $idAccionCorrectiva
        ]
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al modificar la Acción Correctiva: " . $e->getMessage(),
        "data" => null
    ]);
}
