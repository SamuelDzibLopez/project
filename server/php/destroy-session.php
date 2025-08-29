<?php
session_start(); // Iniciar sesión

// Destruir todas las variables de sesión
$_SESSION = array();

// Si deseas destruir la sesión completamente, también debes eliminar la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, 
        $params["path"], 
        $params["domain"], 
        $params["secure"], 
        $params["httponly"]
    );
}

// Finalmente, destruir la sesión
session_destroy();

header("Location: /project/index.php"); // Redirige a la página de login o muestra un mensaje de error

?>