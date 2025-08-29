<?php
require_once './../server/php/verificacion.php'; // Incluir archivo de conexión
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesos</title>
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
                                <img src="./../sources/icons/icon-proyectos.svg" alt="">
                                <h2 class="font-title">Procesos</h2>
                            </div>

                            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador'): ?>
                            <!--Apartado de Nuevo proceso-->
                            <div class="div-gray">
                                <div class="div-subtitle">
                                    <img src="./../sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Nuevo Proceso</h2>
                                </div>
                                <hr class="hr-blue">
                                <div class="div-mi-perfil">
                                    <div class="div-perfil">
                                    </div>
                                        <button class="btn-apartado-center escalado" id="btn-nuevo-proceso">Nuevo proceso</button>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!--Apartado de procesos-->
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
                                    <button class="btn-apartado escalado" id="btn_procesos">Ver Procesos</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            include "./../components/footer.php"
            ?>
        </div>
    </div>
    <script type="module" src="./../js/procesos.js"></script>
</body>
</html>