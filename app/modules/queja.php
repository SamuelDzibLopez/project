<?php
require_once './../../server/php/verificacion.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queja o sugerencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./../../styles/">
    <link rel="stylesheet" href="./../../styles/style.css">
    <link rel="stylesheet" href="./../../styles/dashboard.css">
    <link rel="stylesheet" href="./../../styles/header.css">
    <link rel="stylesheet" href="./../../styles/menu.css">
    <link rel="stylesheet" href="./../../styles/fonts.css">
    <link rel="stylesheet" href="./../../styles/apartado-mis-datos.css">
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
                                <h2 class="font-title">Información de queja o sugerencia</h2>
                            </div>

                            <!-- Quejas y sugerencias -->
                            <div class="div-gray" id="Sujerencia">
                            <form class="div-main-blur" id="form-quejas">
                                <div class="div-subtitle">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Información</h2>
                                </div>
                                <hr class="hr-blue">
                                <div class="div-mis-datos">
                                    <div class="div-datos-mis-datos">
                                    <div class="mb-3">
                                        <label for="fecha" class="form-label">Fecha:</label>
                                        <input type="date" class="form-control" id="fecha">
                                    </div>
                                    <div class="mb-3">
                                        <label for="folio" class="form-label">Folio:</label>
                                        <input type="text" class="form-control" id="folio">
                                    </div>
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre:</label>
                                        <input type="text" class="form-control" id="nombre">
                                    </div>
                                    <div class="mb-3">
                                        <label for="correo" class="form-label">Correo electrónico:</label>
                                        <input type="email" class="form-control" id="correo" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="telefono" class="form-label">Teléfono:</label>
                                        <input type="tel" class="form-control" id="telefono">
                                    </div>
                                    <div class="mb-3">
                                        <label for="matricula" class="form-label">No. de Control:</label>
                                        <input type="text" class="form-control" id="matricula">
                                    </div>
                                    <div class="mb-3">
                                        <label for="carrera" class="form-label">Carrera:</label>
                                        <select class="form-select" id="carrera">
                                        <option value="">Seleccione una carrera</option>
                                        <option value="ISC">Ing. en Sistemas Computacionales</option>
                                        <option value="IGE">Ing. en Gestión Empresarial</option>
                                        <option value="II">Ing. Industrial</option>
                                        <option value="IE">Ing. Eléctrica</option>
                                        <option value="IME">Ing. Mecánica</option>
                                        <option value="IQ">Ing. Química</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="semestre" class="form-label">Semestre:</label>
                                        <select class="form-select" id="semestre">
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
                                    <div class="mb-3">
                                        <label for="grupo" class="form-label">Grupo:</label>
                                        <input type="text" class="form-control" id="grupo">
                                    </div>
                                    <div class="mb-3">
                                        <label for="turno" class="form-label">Turno:</label>
                                        <select class="form-select" id="turno">
                                        <option value="">Seleccione un turno</option>
                                        <option value="Matutino">Matutino</option>
                                        <option value="Vespertino">Vespertino</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="aula" class="form-label">Aula:</label>
                                        <input type="text" class="form-control" id="aula">
                                    </div>
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" placeholder="Queja" id="queja" style="height: 100px"></textarea>
                                        <label for="queja">Queja / Sugerencia</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" placeholder="Respuesta" id="respuesta" style="height: 100px"></textarea>
                                        <label for="respuesta">Respuesta</label>
                                    </div>
                                    <div class="mb-3">
                                    <label for="subdirector" class="form-label">Subdirector:</label>
                                        <select class="form-select" id="subdirector" required>
                                        <option value="">Seleccione el Subdirector</option>
                                        </select>
                                    </div>
                                    </div>
                                </div>
                                <div class="div_buttons">
                                    <button type="submit" class="btn-apartado-center escalado">Modificar datos</button>
                                    <button type="button" class="btn-apartado-center btn_green escalado" id="btn-validar">Cerrar/abrir proceso</button>
                                    <button type="button" class="btn-apartado-center btn_red escalado" id="btn-eliminar">Eliminar queja o sugerencia</button>
                                    <button type="button" class="btn-apartado-center btn_orange escalado" id="btn-pdf">Generar Documento PDF</button>
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
    <script type="module" src="./../../js/queja.js"></script>
</body>
</html>