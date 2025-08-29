<?php

require_once './../conexion.php';

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Función para convertir cadenas vacías a null
function valorONull($valor) {
    return (isset($valor) && $valor !== '') ? $valor : null;
}

// 1. Obtener datos simples por POST
$idUsuarioElabora     = valorONull($_POST['idUsuarioElabora'] ?? null);
$idUsuarioValida      = valorONull($_POST['idUsuarioValida'] ?? null);
$idUsuarioCoordinador = valorONull($_POST['idCoordinador'] ?? null);

// 2. Obtener arrays en JSON y convertirlos a array PHP
$usuarios = json_decode($_POST['usuarios'] ?? '[]', true);
$PNCs     = json_decode($_POST['PNCs'] ?? '[]', true);

try {
    $pdo->beginTransaction();

    // 1. Insertar el proceso
    $stmtProceso = $pdo->prepare("INSERT INTO procesos (tipoProceso) VALUES (?)");
    $stmtProceso->execute(["Producto No Conforme"]);
    $idProceso = $pdo->lastInsertId();

    // 2. Insertar producto no conforme (cabecera)
    $stmtPNC = $pdo->prepare("INSERT INTO productosNoConformes (
        idProceso, idUsuarioElabora, idUsuarioValida, idUsuarioCoordinador
    ) VALUES (?, ?, ?, ?)");
    $stmtPNC->execute([
        $idProceso,
        $idUsuarioElabora,
        $idUsuarioValida,
        $idUsuarioCoordinador
    ]);
    $idProductoNoConforme = $pdo->lastInsertId();

    // 3. Insertar productos no conformes individuales (solo si hay)
    if (!empty($PNCs)) {
        $stmtPNCIndividual = $pdo->prepare("INSERT INTO ListaProductosNoConformes (
            idProductoNoConforme, folio, fecha, especificacion, accion, numero, eliminar, idUsuarioVerifica, idUsuarioLibera
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($PNCs as $item) {
            $stmtPNCIndividual->execute([
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

    // 4. Insertar usuarios relacionados con el proceso
    if (!empty($usuarios)) {
        $stmtUsuarios = $pdo->prepare("INSERT INTO procesos_usuarios (idProceso, idUsuario) VALUES (?, ?)");
        foreach ($usuarios as $idUsuario) {
            $stmtUsuarios->execute([$idProceso, $idUsuario]);
        }
    }

    $pdo->commit();

    http_response_code(201);
    echo json_encode([
        "status" => "success",
        "message" => "Producto no conforme registrado exitosamente.",
        "data" => [
            "idProceso" => $idProceso,
            "idProductoNoConforme" => $idProductoNoConforme
        ]
    ]);
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al registrar producto no conforme: " . $e->getMessage(),
        "data" => null
    ]);
}
