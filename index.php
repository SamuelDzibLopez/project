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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Calidad</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <div class="div-1200px">
        <form class="flexbox-one" id="login">
            <div class="div-logos">
                <img src="./sources/imgs/logo-GDM.png" alt="">
                <img src="./sources/imgs/logo-TECNM.png" alt="">
                <img src="./sources/imgs/logo-ITM.png" alt="">
            </div>
            <h1 class="title-one text-center">Inicio de Sesión</h1>
            <p class="title-two text-center">Acceso a la plataforma:</p>
            <input type="text" class="input-one" placeholder="Nombre de Usuario" id="user">
            <input type="password" class="input-one" placeholder="Clave de Acceso" id="password">
            <button type="submit" class="button-one">Iniciar sesión</button>
            <a href="./recuperacion.php" class="text-one text-center">¿Olvidaste tu contraseña?, ¡Haz click aqui!.</a>
        </form>
    </div>

    <?php
    //Inclusión de footer
    include "components/footer.php"
    ?>
    <script type="module" src="index.js"></script>
</body>
</html>