<?php
header("Content-Type: application/json");
require_once './../permisos.php';
require_once './../conexion.php';

try {
    // Solo permitir POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 405,
            "message" => "Método no permitido, use POST",
            "data" => null
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Obtener datos JSON enviados por POST
    $input = json_decode(file_get_contents("php://input"), true);
    if (!$input) throw new Exception("No se recibieron datos válidos en el body");

    $pdo->beginTransaction();

    // ================================
    // 🔹 DATOS DESDE POST
    // ================================
    $tipoProceso    = $input["tipoProceso"] ?? "Queja o Sugerencia";
    $folioProceso   = $input["folioProceso"] ?? "2025-QS/01";
    $estadoProceso  = $input["estadoProceso"] ?? "1";

    $fechaQueja     = $input["fechaQueja"] ?? date("Y-m-d");
    $folioQueja     = $input["folioQueja"] ?? $folioProceso;
    $nombre         = $input["nombre"] ?? "";
    $correo         = $input["correo"] ?? "";
    $telefono       = $input["telefono"] ?? "";
    $matricula      = $input["matricula"] ?? "";
    $carrera        = $input["carrera"] ?? "";
    $semestre       = $input["semestre"] ?? "";
    $grupo          = $input["grupo"] ?? "";
    $turno          = $input["turno"] ?? "";
    $aula           = $input["aula"] ?? "";
    $textoQueja     = $input["textoQueja"] ?? "";
    $respuesta      = $input["respuesta"] ?? "";
    $idCoordinador  = $input["idCoordinador"] ?? null;
    $idRecibe       = $input["idRecibe"] ?? null;

    $usuariosProceso = $input["usuariosProceso"] ?? [];

    // ================================
    // 🔹 1. CREAR PROCESO
    // ================================
    $sqlProceso = "INSERT INTO procesos (tipoProceso, folio, estado) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sqlProceso);
    $stmt->execute([$tipoProceso, $folioProceso, $estadoProceso]);
    $idProceso = $pdo->lastInsertId();

    // ================================
    // 🔹 2. CREAR QUEJA
    // ================================
    $sqlQueja = "INSERT INTO quejas (
        idProceso, fecha, folio, nombre, correo, telefono, matricula, carrera,
        semestre, grupo, turno, aula, queja, respuesta, idCoordinador, idRecibe
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sqlQueja);
    $stmt->execute([
        $idProceso,
        $fechaQueja,
        $folioQueja,
        $nombre,
        $correo,
        $telefono,
        $matricula,
        $carrera,
        $semestre,
        $grupo,
        $turno,
        $aula,
        $textoQueja,
        $respuesta,
        $idCoordinador,
        $idRecibe
    ]);
    $idQueja = $pdo->lastInsertId();

    // ================================
    // 🔹 3. VINCULAR USUARIOS
    // ================================
    foreach ($usuariosProceso as $u) {
        $pdo->prepare("INSERT INTO procesos_usuarios (idProceso, idUsuario) VALUES (?, ?)")
            ->execute([$idProceso, $u]);
    }

    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "ok" => true,
        "statusCode" => 201,
        "message" => "Queja o sugerencia creada con éxito",
        "data" => [
            "proceso" => [
                "idProceso"   => $idProceso,
                "tipoProceso" => $tipoProceso,
                "folio"       => $folioProceso,
                "estado"      => $estadoProceso
            ],
            "queja" => [
                "idQueja"      => $idQueja,
                "fecha"        => $fechaQueja,
                "folio"        => $folioQueja,
                "nombre"       => $nombre,
                "correo"       => $correo,
                "telefono"     => $telefono,
                "matricula"    => $matricula,
                "carrera"      => $carrera,
                "semestre"     => $semestre,
                "grupo"        => $grupo,
                "turno"        => $turno,
                "aula"         => $aula,
                "queja"        => $textoQueja,
                "respuesta"    => $respuesta,
                "idCoordinador"=> $idCoordinador,
                "idRecibe"     => $idRecibe
            ],
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
