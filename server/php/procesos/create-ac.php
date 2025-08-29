<?php

require_once './../conexion.php';

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function valorONull($valor) {
    return (isset($valor) && $valor !== '') ? $valor : null;
}

try {
    $pdo->beginTransaction();

    // 1. Insertar en la tabla procesos
    $stmtProceso = $pdo->prepare("INSERT INTO procesos (tipoProceso) VALUES (?)");
    $stmtProceso->execute(["Accion Correctiva"]);
    $idProceso = $pdo->lastInsertId();

    // 2. Obtener datos simples
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

    // 3. Insertar en accionesCorrectivas (excluyendo idAccionCorrectiva autoincrement)
    $stmtAC = $pdo->prepare("INSERT INTO accionesCorrectivas (
        idProceso, folio, areaProceso, fecha, origenRequisito, fuenteNC, descripcion,
        idDefine, idVerifica, idCoordinador,
        requiereAC, requiereCorreccion,
        tecnicaUtilizada, causaRaizIdentificada, ACRealizar,
        Similares, ACSimilares,
        potenciales, ACPotenciales,
        seguimiento, actualizar, ACActualizar,
        cambios, ACCambios
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmtAC->execute([
        $idProceso, $folio, $areaProceso, $fecha, $origenRequisito, $fuenteNC, $descripcion,
        $idDefine, $idVerifica, $idCoordinador,
        $requiereAC, $requiereCorreccion,
        $tecnicaUtilizada, $causaRaizIdentificada, $ACRealizar,
        $Similares, $ACSimilares,
        $potenciales, $ACPotenciales,
        $seguimiento, $actualizar, $ACActualizar,
        $cambios, $ACCambios
    ]);

    $idAccionCorrectiva = $pdo->lastInsertId();

    // 4. Insertar correcciones
    $correcciones = json_decode($_POST['correcciones'] ?? '[]', true);
    if (!empty($correcciones)) {
        $stmtCorreccion = $pdo->prepare("INSERT INTO correcciones (
            idAccionCorrectiva, correccion, idResponsable, fecha
        ) VALUES (?, ?, ?, ?)");

        foreach ($correcciones as $corr) {
            $stmtCorreccion->execute([
                $idAccionCorrectiva,
                valorONull($corr['texto'] ?? null),
                valorONull($corr['idResponsable'] ?? null),
                valorONull($corr['fecha'] ?? null)
            ]);
        }
    }

    // 5. Insertar acciones
    $acciones = json_decode($_POST['acciones'] ?? '[]', true);
    if (!empty($acciones)) {
        $stmtAccion = $pdo->prepare("INSERT INTO acciones (
            idAccionCorrectiva, accion, idResponsable, fecha
        ) VALUES (?, ?, ?, ?)");

        foreach ($acciones as $act) {
            $stmtAccion->execute([
                $idAccionCorrectiva,
                valorONull($act['texto'] ?? null),
                valorONull($act['idResponsable'] ?? null),
                valorONull($act['fecha'] ?? null)
            ]);
        }
    }

    // 6. Insertar usuarios relacionados
    $usuarios = json_decode($_POST['usuarios'] ?? '[]', true);
    if (!empty($usuarios)) {
        $stmtUsuario = $pdo->prepare("INSERT INTO procesos_usuarios (idProceso, idUsuario) VALUES (?, ?)");
        foreach ($usuarios as $idUsuario) {
            $stmtUsuario->execute([$idProceso, $idUsuario]);
        }
    }

    $pdo->commit();

    http_response_code(201);
    echo json_encode([
        "status" => "success",
        "message" => "Acción Correctiva registrada exitosamente.",
        "data" => [
            "idProceso" => $idProceso,
            "idAccionCorrectiva" => $idAccionCorrectiva
        ]
    ]);
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al registrar Acción Correctiva: " . $e->getMessage(),
        "data" => null
    ]);
}
