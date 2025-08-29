<?php

// http://localhost/residencia/server/php/procesos/obtener-procesos.php?pagina=1

require_once './../permisos.php'; // Incluir archivo de permisos (si aplica)
require_once './../conexion.php'; // Incluir archivo de conexión

// Cabecera para respuesta JSON
header('Content-Type: application/json');

// Obtener el parámetro de la página, por defecto será 1 (primeros 3 registros)
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

// Calcular el límite de registros y el desplazamiento (offset)
$registrosPorPagina = 3;
$offset = ($pagina - 1) * $registrosPorPagina;

try {
    // Consulta para obtener los registros correspondientes al número de página
    $stmt = $pdo->prepare("SELECT idProceso, tipoProceso, fechaCreacion FROM procesos LIMIT ?, ?");
    $stmt->bindParam(1, $offset, PDO::PARAM_INT);
    $stmt->bindParam(2, $registrosPorPagina, PDO::PARAM_INT);
    $stmt->execute();

    // Obtener los resultados
    $procesos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si se encontraron procesos
    if ($procesos) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "ok" => true,
            "statusCode" => 200,
            "message" => "Procesos obtenidos correctamente.",
            "data" => $procesos
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "ok" => false,
            "statusCode" => 404,
            "message" => "No se encontraron procesos.",
            "data" => null
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
?>
