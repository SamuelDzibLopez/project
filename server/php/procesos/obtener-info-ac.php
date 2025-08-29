<?php

require_once './../conexion.php';

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Reemplaza los valores null por cadenas vacías y "1"/"0" por booleanos
function transformarDatos($data) {
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $data[$key] = transformarDatos($value);
        } elseif (is_null($value)) {
            $data[$key] = "";
        } elseif ($value === "1" || $value === "0") {
            if (!preg_match('/^id[A-Z]/', $key)) {
                $data[$key] = $value === "1";
            }
        } elseif ($key === 'firmaElectronica' && trim($value) === '') {
            $data[$key] = 'null.png';
        }
    }
    return $data;
}

function firmaValida($firma) {
    return (!isset($firma) || trim($firma) === '') ? 'null.png' : $firma;
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

    $stmtAC = $pdo->prepare("SELECT * FROM accionesCorrectivas WHERE idProceso = ?");
    $stmtAC->execute([$idProceso]);
    $accionCorrectiva = $stmtAC->fetch(PDO::FETCH_ASSOC);

    if ($accionCorrectiva) {
        $camposUsuarios = ['idDefine', 'idVerifica', 'idCoordinador'];

        foreach ($camposUsuarios as $campo) {
            if (!empty($accionCorrectiva[$campo])) {
                $stmtUsuario = $pdo->prepare("SELECT idUsuario, nombreCompleto, apellidoPaterno, apellidoMaterno, firmaElectronica FROM usuarios WHERE idUsuario = ?");
                $stmtUsuario->execute([$accionCorrectiva[$campo]]);
                $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);
                if ($usuario) {
                    $usuario['firmaElectronica'] = firmaValida($usuario['firmaElectronica'] ?? '');
                }
                $accionCorrectiva[$campo . '_info'] = $usuario ?: usuarioVacio();
            } else {
                $accionCorrectiva[$campo . '_info'] = usuarioVacio();
            }
        }

        $idAccionCorrectiva = $accionCorrectiva['idAccionCorrectiva'];
    } else {
        $accionCorrectiva = [];
        $idAccionCorrectiva = null;
    }

    $correcciones = [];
    if ($idAccionCorrectiva) {
        $stmtCorr = $pdo->prepare("SELECT * FROM correcciones WHERE idAccionCorrectiva = ?");
        $stmtCorr->execute([$idAccionCorrectiva]);
        $correcciones = $stmtCorr->fetchAll(PDO::FETCH_ASSOC);

        foreach ($correcciones as &$corr) {
            if (!empty($corr['idResponsable'])) {
                $stmtResp = $pdo->prepare("SELECT idUsuario, nombreCompleto, apellidoPaterno, apellidoMaterno, firmaElectronica FROM usuarios WHERE idUsuario = ?");
                $stmtResp->execute([$corr['idResponsable']]);
                $corr['idResponsable_info'] = $stmtResp->fetch(PDO::FETCH_ASSOC) ?: usuarioVacio();
                $corr['idResponsable_info']['firmaElectronica'] = firmaValida($corr['idResponsable_info']['firmaElectronica'] ?? '');
            } else {
                $corr['idResponsable_info'] = usuarioVacio();
            }
        }
    }

    $acciones = [];
    if ($idAccionCorrectiva) {
        $stmtAcc = $pdo->prepare("SELECT * FROM acciones WHERE idAccionCorrectiva = ?");
        $stmtAcc->execute([$idAccionCorrectiva]);
        $acciones = $stmtAcc->fetchAll(PDO::FETCH_ASSOC);

        foreach ($acciones as &$acc) {
            if (!empty($acc['idResponsable'])) {
                $stmtResp = $pdo->prepare("SELECT idUsuario, nombreCompleto, apellidoPaterno, apellidoMaterno, firmaElectronica FROM usuarios WHERE idUsuario = ?");
                $stmtResp->execute([$acc['idResponsable']]);
                $acc['idResponsable_info'] = $stmtResp->fetch(PDO::FETCH_ASSOC) ?: usuarioVacio();
                $acc['idResponsable_info']['firmaElectronica'] = firmaValida($acc['idResponsable_info']['firmaElectronica'] ?? '');
            } else {
                $acc['idResponsable_info'] = usuarioVacio();
            }
        }
    }

    $stmtUsuarios = $pdo->prepare("SELECT u.idUsuario, u.nombreCompleto, u.apellidoPaterno, u.apellidoMaterno, u.firmaElectronica FROM procesos_usuarios pu INNER JOIN usuarios u ON pu.idUsuario = u.idUsuario WHERE pu.idProceso = ?");
    $stmtUsuarios->execute([$idProceso]);
    $usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

    foreach ($usuarios as &$usuario) {
        $usuario['firmaElectronica'] = firmaValida($usuario['firmaElectronica'] ?? '');
    }

    $respuesta = [
        "status" => "success",
        "message" => "Datos obtenidos correctamente.",
        "data" => transformarDatos([
            "proceso" => $proceso,
            "accionCorrectiva" => $accionCorrectiva,
            "correcciones" => $correcciones,
            "acciones" => $acciones,
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
