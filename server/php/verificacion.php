<?php
session_start(); // Iniciar sesión

// Verificar si las variables de sesión están definidas
if (!isset($_SESSION['usuario']) || !isset($_SESSION['nombreCompleto'])) {
    // Si las variables de sesión no están definidas, redirigir al login
    header("Location: /project/index.php"); // Redirige a la página de login o muestra un mensaje de error
    exit();
}

// Si las variables de sesión están definidas, mostrarlas
?>