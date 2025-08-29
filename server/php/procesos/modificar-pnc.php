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
$idProceso            = valorONull($_POST['idProceso'] ?? null);
$idUsuarioElabora     = valorONull($_POST['idUsuarioElabora'] ?? null);
$idUsuarioValida      = valorONull($_POST['idUsuarioValida'] ?? null);
$idUsuarioCoordinador = valorONull($_POST['idCoordinador'] ?? null);

$usuarios = json_decode($_POST['usuarios'] ?? '[]', true);
$PNCs     = json_decode($_POST['PNCs'] ?? '[]', true);

// Validaciones iniciales
if (!$idProceso) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Falta el identificador de proceso.", "data" => null]);
    exit;
}

try {
    // Verificar estado del proceso
    $stmtEstado = $pdo->prepare("SELECT estado FROM procesos WHERE idProceso = ?");
    $stmtEstado->execute([$idProceso]);
    $proceso = $stmtEstado->fetch(PDO::FETCH_ASSOC);

    if (!$proceso) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "No existe el proceso especificado.",
            "data" => null
        ]);
        exit;
    }

    if ($proceso['estado'] != 1) {
        http_response_code(403);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 403,
            "message" => "No se puede modificar el producto no conforme porque el proceso está cerrado o inactivo.",
            "data" => null
        ]);
        exit;
    }

    // Verificar permisos
    $rolUsuario = $_SESSION['rol'] ?? '';
    $idUsuarioActual = $_SESSION['idUsuario'] ?? 0;
    $usuarioAutorizado = false;

    if ($rolUsuario === 'Administrador') {
        $usuarioAutorizado = true;
    } else {
        $stmtVerificarUsuario = $pdo->prepare("SELECT 1 FROM procesos_usuarios WHERE idProceso = ? AND idUsuario = ?");
        $stmtVerificarUsuario->execute([$idProceso, $idUsuarioActual]);
        if ($stmtVerificarUsuario->fetch()) {
            $usuarioAutorizado = true;
        }
    }

    if (!$usuarioAutorizado) {
        http_response_code(403);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 403,
            "message" => "No tienes permiso para modificar este producto no conforme.",
            "data" => null
        ]);
        exit;
    }

    $pdo->beginTransaction();

    // Obtener ID de producto no conforme asociado
    $stmtBuscarPNC = $pdo->prepare("SELECT idProductoNoConforme FROM productosNoConformes WHERE idProceso = ?");
    $stmtBuscarPNC->execute([$idProceso]);
    $idProductoNoConforme = $stmtBuscarPNC->fetchColumn();

    if (!$idProductoNoConforme) {
        throw new Exception("No se encontró producto no conforme asociado al proceso.");
    }

    // Actualizar cabecera
    $stmtActualizarCabecera = $pdo->prepare("UPDATE productosNoConformes SET
        idUsuarioElabora = ?, idUsuarioValida = ?, idUsuarioCoordinador = ?
        WHERE idProductoNoConforme = ?");
    $stmtActualizarCabecera->execute([
        $idUsuarioElabora,
        $idUsuarioValida,
        $idUsuarioCoordinador,
        $idProductoNoConforme
    ]);

    // Borrar registros anteriores
    $pdo->prepare("DELETE FROM ListaProductosNoConformes WHERE idProductoNoConforme = ?")->execute([$idProductoNoConforme]);
    $pdo->prepare("DELETE FROM procesos_usuarios WHERE idProceso = ?")->execute([$idProceso]);

    // Insertar nuevos productos no conformes
    if (!empty($PNCs)) {
        $stmtInsertPNC = $pdo->prepare("INSERT INTO ListaProductosNoConformes (
            idProductoNoConforme, folio, fecha, especificacion, accion, numero, eliminar, idUsuarioVerifica, idUsuarioLibera
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($PNCs as $item) {
            $stmtInsertPNC->execute([
                $idProductoNoConforme,
                valorONull($item['folio'] ?? null),
                valorONull($item['fecha'] ?? null),
                valorONull($item['especificacion'] ?? null),
                valorONull($item['accion'] ?? null),
                valorONull($item['numero'] ?? null),
                isset($item['eliminarPNC']) && $item['eliminarPNC'] ? 1 : 0,
                valorONull($item['idUsuarioVerifica'] ?? null),
                valorONull($item['idUsuarioLibera'] ?? null)
            ]);
        }
    }

    // Insertar usuarios relacionados
    if (!empty($usuarios)) {
        $stmtUsuarios = $pdo->prepare("INSERT INTO procesos_usuarios (idProceso, idUsuario) VALUES (?, ?)");
        foreach ($usuarios as $idUsuario) {
            $stmtUsuarios->execute([$idProceso, $idUsuario]);
        }
    }

    $pdo->commit();

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Producto no conforme modificado exitosamente.",
        "data" => [
            "idProceso" => $idProceso,
            "idProductoNoConforme" => $idProductoNoConforme
        ]
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al modificar producto no conforme: " . $e->getMessage(),
        "data" => null
    ]);
}
