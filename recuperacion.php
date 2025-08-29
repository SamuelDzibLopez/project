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
        <form class="flexbox-one" id="recuperacion">
            <div class="div-logos">
                <img src="./sources/imgs/logo-GDM.png" alt="">
                <img src="./sources/imgs/logo-TECNM.png" alt="">
                <img src="./sources/imgs/logo-ITM.png" alt="">
            </div>
            <h1 class="title-one text-center">Recuperacion de Clave de acceso</h1>
            <p class="title-two text-center">Recuperación de contraseña:</p>
            <input type="email" class="input-one" placeholder="Correo electronico vinculado" id="email" required>
            <button type="submit" class="button-one">Enviar correo</button>
            <a href="./index.php" class="text-one text-center">Tienes una cuenta?, ¡Haz click aqui!.</a>
        </form>
    </div>

    <?php
    //Inclusión de footer
    include "components/footer.php"
    ?>
    <script type="module" src="./recuperacion.js"></script>
</body>
</html>