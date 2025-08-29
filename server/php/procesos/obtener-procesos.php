<?php

session_start();

require_once './../conexion.php'; // Conexión a la base de datos

header('Content-Type: application/json');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idUsuario']) || !isset($_SESSION['rol'])) {
    http_response_code(401);
    echo json_encode([
        "status" => "unauthorized",
        "ok" => false,
        "statusCode" => 401,
        "message" => "Acceso no autorizado. Sesión no iniciada o incompleta.",
        "data" => null
    ]);
    exit;
}

$idUsuario = $_SESSION['idUsuario'];
$rol = $_SESSION['rol'];

try {
    // Parámetros de paginación
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $registrosPorPagina = 3;
    $offset = ($pagina - 1) * $registrosPorPagina;

    // Variables para consulta
    $procesos = [];
    $totalRegistros = 0;
    $totalPaginas = 0;

    if ($rol === "Administrador") {
        // Consulta para obtener total de registros
        $countStmt = $pdo->query("SELECT COUNT(*) FROM procesos");
        $totalRegistros = (int)$countStmt->fetchColumn();

        // Consulta paginada
        $stmt = $pdo->prepare("
            SELECT idProceso, tipoProceso, folio, fechaCreacion
            FROM procesos
            ORDER BY fechaCreacion DESC
            LIMIT ?, ?
        ");
        $stmt->bindParam(1, $offset, PDO::PARAM_INT);
        $stmt->bindParam(2, $registrosPorPagina, PDO::PARAM_INT);
    } else {
        // Consulta para obtener total de registros del usuario
        $countStmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM procesos p
            INNER JOIN procesos_usuarios pu ON p.idProceso = pu.idProceso
            WHERE pu.idUsuario = ?
        ");
        $countStmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
        $countStmt->execute();
        $totalRegistros = (int)$countStmt->fetchColumn();

        // Consulta paginada para el usuario
        $stmt = $pdo->prepare("
            SELECT p.idProceso, p.tipoProceso, p.folio, p.fechaCreacion
            FROM procesos p
            INNER JOIN procesos_usuarios pu ON p.idProceso = pu.idProceso
            WHERE pu.idUsuario = ?
            ORDER BY p.fechaCreacion DESC
            LIMIT ?, ?
        ");
        $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(2, $offset, PDO::PARAM_INT);
        $stmt->bindParam(3, $registrosPorPagina, PDO::PARAM_INT);
    }

    $stmt->execute();
    $procesos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calcular total de páginas
    $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

    if ($procesos) {
        echo json_encode([
            "status" => "success",
            "ok" => true,
            "statusCode" => 200,
            "message" => "Procesos obtenidos correctamente.",
            "data" => $procesos,
            "pagination" => [
                "paginaActual" => $pagina,
                "totalPaginas" => $totalPaginas,
                "totalRegistros" => $totalRegistros
            ]
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 404,
            "message" => "No se encontraron procesos.",
            "data" => [],
            "pagination" => [
                "paginaActual" => $pagina,
                "totalPaginas" => $totalPaginas,
                "totalRegistros" => $totalRegistros
            ]
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error en la base de datos: " . $e->getMessage(),
        "data" => null
    ]);
}
