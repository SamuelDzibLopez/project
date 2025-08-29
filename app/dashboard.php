<?php
require_once './../server/php/verificacion.php'; // Incluir archivo de conexión
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./../styles/style.css">
    <link rel="stylesheet" href="./../styles/dashboard.css">
    <link rel="stylesheet" href="./../styles/header.css">
    <link rel="stylesheet" href="./../styles/menu.css">
    <link rel="stylesheet" href="./../styles/fonts.css">
    <link rel="stylesheet" href="./../styles/apartado-mi_perfil.css">
    <link rel="stylesheet" href="./../styles/apartado-usuarios.css">

</head>
<body>
    <?php
        include "./../components/menu-movile.php"
    ?>
    <div class="div-1200px app">
        <?php 
            include "./../components/menu.php";
        ?>
        <div class="dashboard">
            <?php 
            include "./../components/header.php";
            ?>
            <div class="div-main-blue">
                <div class="div-main-white">
                    <div class="div-main-ITM">
                        <div class="div-main-blur">
                            <div class="div-title">
                                <img src="./../sources/icons/icon-dashboard.svg" alt="">
                                <h2 class="font-title">Dashboard</h2>
                            </div>

                            <!--Apartado de Mi perfil-->
                            <div class="div-gray">
                                <div class="div-subtitle">
                                    <img src="./../sources/icons/icon-perfil.svg" alt="">
                                    <h2 class="font-subtitle">Mi Perfil</h2>
                                </div>
                                <hr class="hr-blue">
                                <div class="div-mi-perfil">
                                    <div class="div-perfil">
                                        <img src="<?php echo !empty($_SESSION['perfil']) ? "./../server/perfiles/" . $_SESSION['perfil'] : '/project/sources/imgs/user-Icon.png'; ?>" alt="">
                                        <a href="/project/app/mi-perfil.php" class="a-user-perfil">
                                            <h2 class="user-perfil">
                                                <?php echo $_SESSION['nombreCompleto']?> <?php echo $_SESSION['apellidoPaterno']?> (<?php echo $_SESSION['rol']?>).
                                            </h2>
                                        </a>
                                    </div>
                                        <button class="btn-apartado escalado" id="btn-ver-perfil">Ver Perfil</button>
                                </div>
                            </div>

                            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador'): ?>
                            <!--Apartado de Usuarios-->
                            <div class="div-gray">
                                <div class="div-subtitle">
                                    <img src="./../sources/icons/icon-usuarios.svg" alt="">
                                    <h2 class="font-subtitle">Usuarios</h2>
                                </div>
                                <hr class="hr-blue">
                                <div class="div-usuarios">
                                    <div class="div-usuarios" id="div_users">
                                    </div>
                                    <button class="btn-apartado escalado" id="btn-ver-usuarios">Ver Usuarios</button>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!--Apartados de Procesos-->
                            <div class="div-gray">
                                <div class="div-subtitle">
                                    <img src="./../sources/icons/icon-proyectos.svg" alt="">
                                        <h2 class="font-subtitle">
                                            <?php
                                                if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador') {
                                                    echo "Procesos existentes";
                                                } else {
                                                    echo "Tus procesos inscritos";
                                                }
                                            ?>
                                        </h2>
                                </div>
                                <hr class="hr-blue">
                                <div class="div-usuarios">
                                    <div class="div-usuarios" id="div_procesos">
                                    </div>
                                    <button class="btn-apartado escalado" id="btn-ver-proyectos">Ver Procesos</button>
                                </div>
                            </div>

                            <!--Apartados de Documentos-->
                        </div>
                    </div>
                </div>
            </div>

            <?php
            include "./../components/footer.php"
            ?>
        </div>
    </div>
    <script type="module" src="./../js/dashboard.js"></script>
</body>
</script>
</html>