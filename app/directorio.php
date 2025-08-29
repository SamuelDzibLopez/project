<?php
require_once './../server/php/verificacion.php'; // Incluir archivo de conexión

require_once './../server/php/permisos.php'; // Incluir archivo de conexión
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Directorio</title>
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
                                <img src="./../sources/icons/icon-usuarios.svg" alt="">
                                <h2 class="font-title">Directorio</h2>
                            </div>

                            <!--Apartado de Nuevo usuario-->
                            <div class="div-gray">
                                <div class="div-subtitle">
                                    <img src="./../sources/icons/icon-usuarios.svg" alt="">
                                    <h2 class="font-subtitle">Nuevo contacto</h2>
                                </div>
                                <hr class="hr-blue">
                                <div class="div-mi-perfil">
                                    <div class="div-perfil">
                                    </div>
                                        <button class="btn-apartado-center escalado" id="btn-nuevo-contacto">Nuevo contacto</button>

                                </div>
                            </div>

                            <!--Apartado de Usuarios-->
                            <div class="div-gray">
                                <div class="div-subtitle">
                                    <img src="./../sources/icons/icon-usuarios.svg" alt="">
                                    <h2 class="font-subtitle">Contactos existentes</h2>
                                </div>
                                <hr class="hr-blue">
                                    <form action="" class="div-search-users">
                                        <div class="search-users">
                                            <input type="text" placeholder="Buscar">
                                            <button type="reset">
                                                <img src="./../sources/icons/icon-cancel.svg" alt="">
                                            </button>
                                        </div>
                                    <button>
                                        <img src="./../sources/icons/icon-search.svg" alt="">
                                    </button>
                                </form>
                                <hr class="hr-blue">
                                <div class="div-usuarios">
                                    <div class="div-usuarios" id="div_contacts">
                                    <!-- <a href="/residencia/app/modules/usuario.php?id_user=0${e.idUsuario}" class="div-usuario escalado">
                                        <div class="user-div-main">
                                            <img src="./../sources/imgs/user-Icon.png" alt="${e.perfil ? 'Imagen de usuario' : 'Sin imagen disponible'}">
                                            <div class="user-name">
                                                <h2 class="user-name-name">USUARIO</h2>
                                                <p class="user-rol">Rol: <span class="user-rol-bold">Administrador.</span></p>
                                            </div>
                                        </div>
                                        <div class="user-data">
                                            <p class="user-rol">Fecha de creación:</p>
                                            <p class="user-rol"><span class="user-rol-bold">${e.fechaCreacion}.</span></p>
                                        </div>
                                    </a> -->
                                    </div>
                                    <button class="btn-apartado-center escalado" id="btn_contacts">Ver más contactos</button>
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
    <script type="module" src="./../js/directorio.js"></script>
</body>
</html>