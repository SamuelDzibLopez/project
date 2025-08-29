<?php
// http://localhost/residencia/server/php/correos/verificar-codigo.php

session_start();

header('Content-Type: application/json');

// Obtener los 4 números desde POST
$codigoUno    = isset($_POST['codigoUno']) ? intval($_POST['codigoUno']) : null;
$codigoDos    = isset($_POST['codigoDos']) ? intval($_POST['codigoDos']) : null;
$codigoTres   = isset($_POST['codigoTres']) ? intval($_POST['codigoTres']) : null;
$codigoCuatro = isset($_POST['codigoCuatro']) ? intval($_POST['codigoCuatro']) : null;

// Validar que todos estén presentes
if ($codigoUno === null || $codigoDos === null || $codigoTres === null || $codigoCuatro === null) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 400,
        "message" => "Faltan uno o más dígitos del código.",
        "data" => null
    ]);
    exit;
}

// Verificar que existan los códigos en la sesión
if (
    !isset($_SESSION['codigoUno'], $_SESSION['codigoDos'], $_SESSION['codigoTres'], $_SESSION['codigoCuatro'])
) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 401,
        "message" => "Sesión no válida o códigos no generados.",
        "data" => null
    ]);
    exit;
}

// Comparar los códigos ingresados con los de la sesión
if (
    $codigoUno === $_SESSION['codigoUno'] &&
    $codigoDos === $_SESSION['codigoDos'] &&
    $codigoTres === $_SESSION['codigoTres'] &&
    $codigoCuatro === $_SESSION['codigoCuatro']
) {
    $_SESSION['flag'] = true;
    
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "ok" => true,
        "statusCode" => 200,
        "message" => "Código verificado correctamente.",
        "data" => null
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 401,
        "message" => "Código incorrecto.",
        "data" => null
    ]);
}
?>
