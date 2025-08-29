<?php
require_once './../../server/php/verificacion.php'; // Incluir archivo de conexión

require_once './../../server/php/permisos.php'; // Incluir archivo de conexión
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo contacto</title>
    <link rel="stylesheet" href="./../../styles/style.css">
    <link rel="stylesheet" href="./../../styles/dashboard.css">
    <link rel="stylesheet" href="./../../styles/header.css">
    <link rel="stylesheet" href="./../../styles/menu.css">
    <link rel="stylesheet" href="./../../styles/fonts.css">
    <link rel="stylesheet" href="./../../styles/apartado-mis-datos.css">


</head>
<body>
    <?php
        include "./../../components/menu-movile.php";
    ?>
    <div class="div-1200px app">
        <?php 
            include "./../../components/menu.php";
        ?>
        <div class="dashboard">
            <?php 
            include "./../../components/header.php";
            ?>
            <div class="div-main-blue">
                <div class="div-main-white">
                    <div class="div-main-ITM">
                    <form class="div-main-blur" id="form_datos" enctype="multipart/form-data">
                            <div class="div-title">
                                <img src="/project/sources/icons/icon-usuarios.svg" alt="">
                                <h2 class="font-title">Nuevo contacto</h2>
                            </div>

                            <!--Apartado de información-->
                            <div class="div-gray">
                                <div class="div-subtitle">
                                    <img src="/project/sources/icons/icon-usuarios.svg" alt="">
                                    <h2 class="font-subtitle">Información</h2>
                                </div>
                                <hr class="hr-blue">
                                <div class="div-mis-datos">
                                    <div class="div-foto-perfil">
                                        <label for="input-perfil" class="label-foto-perfil">
                                            <img name="img_perfil" src="./../../sources/imgs/user-Icon.png" alt="" class="img-foto-perfil" id="img-perfil">
                                        </label>
                                        <input id="input-perfil" type="file" accept="image/*" class="input-foto-perfil" name="perfil">
                                    </div>
                                </div>
                                <div class="div-mis-datos">
                                    <div class="div-datos-mis-datos">
                                        <input type="text" placeholder="NOMBRE COMPLETO" class="input-mis-datos" name="nombre">
                                        <input type="text" placeholder="APELLIDO PATERNO" class="input-mis-datos" name="apellido_paterno">
                                        <input type="text" placeholder="APELLIDO MATERNO" class="input-mis-datos" name="apellido_materno">
                                        <div class="input-mis-datos">
                                            <label for="date-one">FECHA DE NACIMIENTO</label>
                                            <input type="date" class="input-mis-datos none" id="date-one" name="fecha_nacimiento">
                                        </div>
                                        <input type="number" placeholder="TELEFONO" class="input-mis-datos" name="telefono">
                                        <input type="email" placeholder="CORREO ELECTRONICO INSTITUCIONAL" class="input-mis-datos" name="correo">
                                        <input type="text" placeholder="TARJETA" class="input-mis-datos" name="tarjeta">

                                        <select name="puesto" class="input-mis-datos">
                                            <option value="">Puesto</option>
                                            <option value="Director">Director</option>
                                            <option value="Subdirector">Subdirector</option>
                                            <option value="Jefe de Departamento">Jefe de Departamento</option>
                                            <option value="Coordinador">Coordinador</option>
                                            <option value="Depto.">Depto.</option>
                                            <option value="División">División</option>
                                        </select>

                                        <select name="departamento" class="input-mis-datos">
                                            <option value="">Departamento</option>
                                            <option value="General">General</option>
                                            <option value="Calidad">Calidad</option>
                                            <option value="Servicios Administrativos">Servicios Administrativos</option>
                                            <option value="TI">TI</option>
                                            <option value="Energía">Energía</option>
                                            <option value="Académicos">Académicos</option>
                                            <option value="Planeación y Vinculación">Planeación y Vinculación</option>
                                            <option value="Planeación y Programación Presupuestaria">Planeación y Programación Presupuestaria</option>
                                            <option value="Gestión Tecnológica y Vinculación">Gestión Tecnológica y Vinculación</option>
                                            <option value="Actividades Extraescolares">Actividades Extraescolares</option>
                                            <option value="Comunicación y Difusión">Comunicación y Difusión</option>
                                            <option value="Servicios Escolares">Servicios Escolares</option>
                                            <option value="Económico-Administrativas">Económico-Administrativas</option>
                                            <option value="Desarrollo Académico">Desarrollo Académico</option>
                                            <option value="Ingeniería Industrial">Ingeniería Industrial</option>
                                            <option value="Estudios Profesionales">Estudios Profesionales</option>
                                            <option value="Ciencias Básicas">Ciencias Básicas</option>
                                            <option value="Recursos Financieros">Recursos Financieros</option>
                                            <option value="Recursos Humanos">Recursos Humanos</option>
                                            <option value="Recursos Materiales y Servicios">Recursos Materiales y Servicios</option>
                                            <option value="Mantenimiento de Equipo">Mantenimiento de Equipo</option>
                                            <option value="Mantenimiento de Equipo">Centro de Cómputo</option>
                                            <option value="Ambiental">Ambiental</option>
                                            <option value="Igualdad Laboral y No Discriminación">Igualdad Laboral y No Discriminación</option>
                                        </select>
                                        <button type="submit" class="btn-apartado-center escalado">Crear contacto</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php
            include "./../../components/footer.php";
            ?>
        </div>
    </div>
    <script type="module" src="./../../js/nuevo-contacto.js"></script>
</body>
</html>