<?php
session_start();
require_once './../conexion.php';

header('Content-Type: application/json');

$idProceso = $_GET['idProceso'] ?? null;

if (!$idProceso) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "Falta el parámetro idProceso",
        "data" => null
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // ======================================
    // 1. OBTENER PROCESO
    // ======================================
    $sqlProceso = "SELECT idProceso, tipoProceso, folio, estado 
                   FROM procesos 
                   WHERE idProceso = ?";
    $stmt = $pdo->prepare($sqlProceso);
    $stmt->execute([$idProceso]);
    $proceso = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$proceso) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 404,
            "message" => "Proceso no encontrado",
            "data" => null
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ======================================
    // 2. OBTENER QUEJA Y DATOS COMPLETOS DE USUARIOS
    // ======================================
    $sqlQueja = "SELECT 
                    q.idQueja, q.fecha, q.folio, q.nombre, q.correo, q.telefono,
                    q.matricula, q.carrera, q.semestre, q.grupo, q.turno, q.aula,
                    q.queja, q.respuesta,
                    q.idCoordinador, q.idRecibe
                 FROM quejas q
                 WHERE q.idProceso = ?";
    $stmt = $pdo->prepare($sqlQueja);
    $stmt->execute([$idProceso]);
    $quejaData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quejaData) {
        $quejaData = [
            "idQueja" => null,
            "fecha" => "",
            "folio" => "",
            "nombre" => "",
            "correo" => "",
            "telefono" => "",
            "matricula" => "",
            "carrera" => "",
            "semestre" => "",
            "grupo" => "",
            "turno" => "",
            "aula" => "",
            "queja" => "",
            "respuesta" => "",
            "idCoordinador" => null,
            "idRecibe" => null
        ];
    }

    // Obtener datos de coordinador
    $coordinador = null;
    if ($quejaData['idCoordinador']) {
        $stmt = $pdo->prepare("SELECT idUsuario, nombreCompleto, apellidoPaterno, apellidoMaterno, fechaNacimiento, telefono, correoElectronico, numeroTarjeta, rol, puesto, departamento, perfil, estado, fechaCreacion, usuario, fechaVigencia, vigencia, firmaElectronica
                               FROM usuarios WHERE idUsuario = ?");
        $stmt->execute([$quejaData['idCoordinador']]);
        $coordinador = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener datos de quien recibe
    $recibe = null;
    if ($quejaData['idRecibe']) {
        $stmt = $pdo->prepare("SELECT idUsuario, nombreCompleto, apellidoPaterno, apellidoMaterno, fechaNacimiento, telefono, correoElectronico, numeroTarjeta, rol, puesto, departamento, perfil, estado, fechaCreacion, usuario, fechaVigencia, vigencia, firmaElectronica
                               FROM usuarios WHERE idUsuario = ?");
        $stmt->execute([$quejaData['idRecibe']]);
        $recibe = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ======================================
    // 3. OBTENER USUARIOS DEL PROCESO
    // ======================================
    $sqlUsuarios = "SELECT u.idUsuario, u.nombreCompleto, u.apellidoPaterno, u.apellidoMaterno,
                           u.fechaNacimiento, u.telefono, u.correoElectronico, u.numeroTarjeta,
                           u.rol, u.puesto, u.departamento, u.perfil, u.estado, u.fechaCreacion, u.usuario, u.fechaVigencia, u.vigencia, u.firmaElectronica
                    FROM procesos_usuarios pu
                    INNER JOIN usuarios u ON pu.idUsuario = u.idUsuario
                    WHERE pu.idProceso = ?";
    $stmt = $pdo->prepare($sqlUsuarios);
    $stmt->execute([$idProceso]);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ======================================
    // RESPUESTA JSON
    // ======================================
    echo json_encode([
        "status" => "success",
        "ok" => true,
        "statusCode" => 200,
        "message" => "Datos de queja obtenidos con éxito",
        "data" => [
            "proceso" => $proceso,
            "queja" => [
                "info" => $quejaData,
                "coordinador" => $coordinador,
                "recibe" => $recibe
            ],
            "usuariosProceso" => $usuarios
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => $e->getMessage(),
        "data" => null
    ], JSON_UNESCAPED_UNICODE);
}
