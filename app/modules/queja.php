<?php
require_once './../../server/php/verificacion.php';
require_once './../../server/php/permisos.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Proceso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./../../styles/">
    <link rel="stylesheet" href="./../../styles/style.css">
    <link rel="stylesheet" href="./../../styles/dashboard.css">
    <link rel="stylesheet" href="./../../styles/header.css">
    <link rel="stylesheet" href="./../../styles/menu.css">
    <link rel="stylesheet" href="./../../styles/fonts.css">
    <link rel="stylesheet" href="./../../styles/apartado-mis-datos.css">
    <link rel="stylesheet" href="./../../styles/nuevo-proceso.css">
</head>

<body>
    <?php include "./../../components/menu-movile.php"; ?>

    <div class="div-1200px app">
        <?php include "./../../components/menu.php"; ?>
        <div class="dashboard">
            <?php include "./../../components/header.php"; ?>

            <div class="div-main-blue">
                <div class="div-main-white">
                    <div class="div-main-ITM">
                        <div class="div-title">
                            <img src="./../../sources/icons/icon-proyectos.svg" alt="">
                            <h2 class="font-title">Nuevo Proceso</h2>
                        </div>

                        <!-- Quejas y sugerencias -->
                        <div class="div-gray" id="Sujerencia">
                            <form class="div-main-blur" id="form-quejas">

                                <div class="div-subtitle">
                                    <img src="/project/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Información</h2>
                                </div>

                                <hr class="hr-blue">

                                <div class="div-mis-datos">
                                    <div class="div-datos-mis-datos">

                                        <!--Fecha-->
                                        <div class="mb-3">
                                            <label for="fecha-queja" class="form-label">Fecha:</label>
                                            <input type="date" class="form-control" id="fecha-queja">
                                        </div>

                                        <!--Folio-->
                                        <div class="mb-3">
                                            <label for="folio-queja" class="form-label">Folio:</label>
                                            <input type="text" class="form-control" id="folio-queja">
                                        </div>

                                        <!--Nombre-->
                                        <div class="mb-3">
                                            <label for="nombre-queja" class="form-label">Nombre:</label>
                                            <input type="text" class="form-control" id="nombre-queja">
                                        </div>

                                        <!--Correo-->
                                        <div class="mb-3">
                                            <label for="correo-queja" class="form-label">Correo electrónico:</label>
                                            <input type="email" class="form-control" id="correo-queja" required>
                                        </div>

                                        <!--Telefono-->
                                        <div class="mb-3">
                                            <label for="telefono-queja" class="form-label">Teléfono:</label>
                                            <input type="tel" class="form-control" id="telefono-queja">
                                        </div>

                                        <!--Matricula-->
                                        <div class="mb-3">
                                            <label for="matricula-queja" class="form-label">No. de Control:</label>
                                            <input type="text" class="form-control" id="matricula-queja">
                                        </div>

                                        <!--Carrera-->
                                        <div class="mb-3">
                                            <label for="carrera-queja" class="form-label">Carrera:</label>
                                            <select class="form-select" id="carrera-queja">
                                                <option value="" selected>Seleccione una carrera</option>
                                                <option value="ISC">Ing. en Sistemas Computacionales</option>
                                                <option value="IGE">Ing. en Gestión Empresarial</option>
                                                <option value="II">Ing. Industrial</option>
                                                <option value="IE">Ing. Eléctrica</option>
                                                <option value="IME">Ing. Mecánica</option>
                                                <option value="IQ">Ing. Química</option>
                                            </select>
                                        </div>

                                        <!--Semestre-->
                                        <div class="mb-3">
                                            <label for="semestre-queja" class="form-label">Semestre:</label>
                                            <select class="form-select" id="semestre-queja">
                                                <option value="" selected>Seleccione un semestre</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                            </select>
                                        </div>

                                        <!--Grupo-->
                                        <div class="mb-3">
                                            <label for="grupo-queja" class="form-label">Grupo:</label>
                                            <input type="text" class="form-control" id="grupo-queja">
                                        </div>

                                        <!--Turno-->
                                        <div class="mb-3">
                                            <label for="turno-queja" class="form-label">Turno:</label>
                                            <select class="form-select" id="turno-queja">
                                                <option value="" selected>Seleccione un turno</option>
                                                <option value="Matutino">Matutino</option>
                                                <option value="Vespertino">Vespertino</option>
                                            </select>
                                        </div>

                                        <!--Aula-->
                                        <div class="mb-3">
                                            <label for="aula-queja" class="form-label">Aula:</label>
                                            <input type="text" class="form-control" id="aula-queja">
                                        </div>

                                        <!--Queja-->
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" placeholder="Queja" id="queja-queja"
                                                style="height: 100px"></textarea>
                                            <label for="queja-queja">Queja / Sugerencia</label>
                                        </div>

                                        <!--Respuesta-->
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" placeholder="Respuesta" id="respuesta-queja"
                                                style="height: 100px"></textarea>
                                            <label for="respuesta-queja">Respuesta</label>
                                        </div>

                                        <!--Coordinador-->
                                        <div class="mb-3">
                                            <label for="coordinador-queja" class="form-label">Coordinador:</label>
                                            <select class="form-select" id="coordinador-queja">
                                                <option value="" selected disabled>Seleccione el Coordinador</option>
                                            </select>
                                        </div>

                                        <!--Recibe-->
                                        <div class="mb-3">
                                            <label for="subdirector" class="form-label">Persona que recibe:</label>
                                            <select class="form-select" id="recibe-queja">
                                                <option value="" selected disabled>Seleccione quien recibe</option>
                                            </select>
                                        </div>

                                        <!--Acceso a usuarios-->
                                        <div class="div-mis-datos">
                                            <label for="usuarios-queja" class="form-label">Agrega
                                                usuarios:</label>
                                            <div class="input-group mb-3">
                                                <select class="form-control" list="usuarios" id="usuarios-queja"
                                                    placeholder="Buscar usuario...">
                                                    <option value="" disebled> Agrege usuarios</option>
                                                </select>
                                                <button class="btn btn-primary" type="button" id="btnUsuarios-queja">Agregar</button>
                                            </div>
                                            <!--Div de usuarios-->
                                            <div class="div-participantes inputs-responsive" id="divUsuarios-queja">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="div_buttons">
                                    <button type="submit" class="btn-apartado-center escalado" id="btn-queja">Crear
                                        queja/sugerencia</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <?php
            include "./../../components/footer.php";
            ?>

        </div>
    </div>


    <script type="module" src="./../../js/nueva-queja.js"></script>

    <!-- <script type="module" src="./../../js/nuevo-proceso.js"></script>
    <script type="module" src="./../../js/auditoria-nueva.js"></script>
    <script type="module" src="./../../js/pnc-nuevo.js"></script>
    <script type="module" src="./../../js/nueva-ac.js"></script> -->
</body>

</html>