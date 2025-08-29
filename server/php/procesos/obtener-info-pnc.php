<?php

require_once './../conexion.php';

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function reemplazarFirmaElectronicaNull(&$usuario) {
    if (!isset($usuario['firmaElectronica']) || $usuario['firmaElectronica'] === null || trim($usuario['firmaElectronica']) === '') {
        $usuario['firmaElectronica'] = 'null.png';
    }
}

function reemplazarNullPorVacio($data) {
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $data[$key] = reemplazarNullPorVacio($value);
        } elseif (is_null($value)) {
            $data[$key] = "";
        }
    }
    return $data;
}

function usuarioVacio() {
    return [
        "idUsuario" => "",
        "nombreCompleto" => "",
        "apellidoPaterno" => "",
        "apellidoMaterno" => "",
        "firmaElectronica" => "null.png"
    ];
}

$idProceso = $_GET['idProceso'] ?? null;

if (!$idProceso || !is_numeric($idProceso)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Parámetro 'idProceso' inválido o faltante.",
        "data" => null
    ]);
    exit;
}

try {
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

    $stmtPNC = $pdo->prepare("SELECT * FROM productosNoConformes WHERE idProceso = ?");
    $stmtPNC->execute([$idProceso]);
    $productoNoConforme = $stmtPNC->fetch(PDO::FETCH_ASSOC);

    if ($productoNoConforme) {
        $camposUsuarios = ['idUsuarioElabora', 'idUsuarioValida', 'idUsuarioCoordinador'];

        foreach ($camposUsuarios as $campo) {
            if (!empty($productoNoConforme[$campo])) {
                $stmtUsuario = $pdo->prepare("SELECT idUsuario, nombreCompleto, apellidoPaterno, apellidoMaterno, firmaElectronica FROM usuarios WHERE idUsuario = ?");
                $stmtUsuario->execute([$productoNoConforme[$campo]]);
                $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);
                if ($usuario) {
                    reemplazarFirmaElectronicaNull($usuario);
                }
                $productoNoConforme[$campo . '_info'] = $usuario ?: usuarioVacio();
            } else {
                $productoNoConforme[$campo . '_info'] = usuarioVacio();
            }
        }

        $stmtPNCInd = $pdo->prepare("SELECT * FROM ListaProductosNoConformes WHERE idProductoNoConforme = ?");
        $stmtPNCInd->execute([$productoNoConforme['idProductoNoConforme']]);
        $PNCs = $stmtPNCInd->fetchAll(PDO::FETCH_ASSOC);

        foreach ($PNCs as &$pnc) {
            if (!empty($pnc['idUsuarioVerifica'])) {
                $stmtVerifica = $pdo->prepare("SELECT idUsuario, nombreCompleto, apellidoPaterno, apellidoMaterno, firmaElectronica FROM usuarios WHERE idUsuario = ?");
                $stmtVerifica->execute([$pnc['idUsuarioVerifica']]);
                $usuario = $stmtVerifica->fetch(PDO::FETCH_ASSOC);
                if ($usuario) {
                    reemplazarFirmaElectronicaNull($usuario);
                }
                $pnc['idUsuarioVerifica_info'] = $usuario ?: usuarioVacio();
            } else {
                $pnc['idUsuarioVerifica_info'] = usuarioVacio();
            }

            if (!empty($pnc['idUsuarioLibera'])) {
                $stmtLibera = $pdo->prepare("SELECT idUsuario, nombreCompleto, apellidoPaterno, apellidoMaterno, firmaElectronica FROM usuarios WHERE idUsuario = ?");
                $stmtLibera->execute([$pnc['idUsuarioLibera']]);
                $usuario = $stmtLibera->fetch(PDO::FETCH_ASSOC);
                if ($usuario) {
                    reemplazarFirmaElectronicaNull($usuario);
                }
                $pnc['idUsuarioLibera_info'] = $usuario ?: usuarioVacio();
            } else {
                $pnc['idUsuarioLibera_info'] = usuarioVacio();
            }
        }
    } else {
        $productoNoConforme = [];
        $PNCs = [];
    }

    $stmtUsuarios = $pdo->prepare("
        SELECT u.idUsuario, u.nombreCompleto, u.apellidoPaterno, u.apellidoMaterno, u.firmaElectronica
        FROM procesos_usuarios pu
        INNER JOIN usuarios u ON pu.idUsuario = u.idUsuario
        WHERE pu.idProceso = ?
    ");
    $stmtUsuarios->execute([$idProceso]);
    $usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

    foreach ($usuarios as &$u) {
        reemplazarFirmaElectronicaNull($u);
    }

    $respuesta = [
        "status" => "success",
        "message" => "Datos obtenidos correctamente.",
        "data" => reemplazarNullPorVacio([
            "proceso" => $proceso,
            "productoNoConforme" => $productoNoConforme,
            "productosNoConformesIndividuales" => $PNCs,
            "usuarios" => $usuarios
        ])
    ];

    http_response_code(200);
    echo json_encode($respuesta);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al obtener datos: " . $e->getMessage(),
        "data" => null
    ]);
}
