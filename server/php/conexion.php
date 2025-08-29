<?php
// Establecer cabecera para que las respuestas sean JSON
header('Content-Type: application/json');

// Configuración de conexión a la base de datos
$host = 'localhost'; // Cambia esto si tu base de datos está en otro servidor
$dbname = 'project'; // Cambia con el nombre de tu base de datos
$username = 'root'; // Cambia con tu usuario
$password = ''; // Cambia con tu contraseña

try {
    // Crear una instancia de PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Establecer el modo de error de PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si hay un error, devolver el mensaje de error con formato JSON
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => "Error de conexión: " . $e->getMessage(),
        "data" => null
    ]);
    exit;
}
?>
