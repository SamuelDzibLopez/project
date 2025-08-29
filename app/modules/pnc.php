<?php
require_once './../../server/php/verificacion.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto No Conforme</title>
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
                            <h2 class="font-title">Información de producto No Conforme</h2>
                        </div>

                        <!--Producto no conforme-->
                        <div class="div-gray" id="pnc">
                            <form class="div-main-blur" id="pnc-form">

                                <!--Información general-->
                                <div class="div-subtitle">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Información General</h2>
                                </div>
                                <hr class="hr-blue">
                                <div class="div-mis-datos">
                                    <div class="div-datos-mis-datos">
                                        <div class="mb-3">
                                            <label for="pnc-elabora" class="form-label">Personal que elabora:</label>
                                            <select class="form-select" id="pnc-elabora">
                                                <option value="" selected>Seleccione al personal que elabora</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pnc-valida" class="form-label">Persona que valida:</label>
                                            <select class="form-select" id="pnc-valida">
                                                <option value="" selected >Seleccione al personal que valida
                                                </option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pnc-coordinador" class="form-label">Coordinador General de
                                                Calidad:</label>
                                            <select class="form-select" id="pnc-coordinador">
                                                <option value="" selected>Seleccione al Coordinador General de
                                                    Calidad</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!--PNC-->
                                <div class="div-subtitle">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Productos No Conformes</h2>
                                </div>
                                <hr class="hr-blue">

                                <div class="div-datos-mis-datos inputs-responsive">
                                    <!--Folio-->
                                    <div class="mb-3 inputs-responsive">
                                        <label for="pnc-folio" class="form-label inputs-responsive">Folio:</label>
                                        <input type="text" class="form-control inputs-responsive" id="pnc-folio">
                                    </div>
                                    <!--Fecha de registro-->
                                    <div class="mb-3 inputs-responsive">
                                        <label for="pnc-fecha" class="form-label inputs-responsive">Fecha de
                                            registro</label>
                                        <input type="date" class="form-control inputs-responsive" id="pnc-fecha">
                                    </div>
                                    <!--Especificación-->
                                    <div class="form-floating inputs-responsive">
                                        <textarea class="form-control inputs-responsive"
                                            placeholder="Especificación inclumplida" id="pnc-especificacion"
                                            style="height: 100px"></textarea>
                                        <label for="pnc-especificacion">Especificación inclumplida</label>
                                    </div>
                                    <!--Acción-->
                                    <div class="form-floating inputs-responsive">
                                        <textarea class="form-control inputs-responsive" placeholder="Acción implantada"
                                            id="pnc-accion" style="height: 100px"></textarea>
                                        <label for="pnc-accion">Acción implantada</label>
                                    </div>
                                    <!--Numero de RAC-->
                                    <div class="mb-3 inputs-responsive">
                                        <label for="pnc-rac" class="form-label inputs-responsive">Número de RAC:</label>
                                        <input type="text" class="form-control inputs-responsive" id="pnc-rac">
                                    </div>

                                    <!--Checks-->
                                    <label for="" class="form-label inputs-responsive">Eliminar PNC:</label>
                                    <div class="div-mis-datos">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="CheckYes" name="check"
                                                value="Si">
                                            <label class="form-check-label" for="CheckYes">
                                                Si
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="CheckNo" name="check"
                                                value="No" checked>
                                            <label class="form-check-label" for="CheckNo">
                                                No
                                            </label>
                                        </div>
                                    </div>

                                    <!--Personal que verifica-->
                                    <div class="mb-3">
                                        <label for="pnc-verifica" class="form-label">Personal que verifica:</label>
                                        <select class="form-select" id="pnc-verifica">
                                            <option value="" selected>Seleccione al personal que verifica
                                            </option>
                                        </select>
                                    </div>
                                    <!--Personal que libera-->
                                    <div class="mb-3">
                                        <label for="pnc-libera" class="form-label">Personal que libera:</label>
                                        <select class="form-select" id="pnc-libera">
                                            <option value="" selected>Seleccione al personal que libera
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <!--Boton de agregar PNC-->
                                <div class="div_buttons inputs-responsive">
                                    <button type="button"
                                        class="inputs-responsive btn-apartado-center escalado btn_green"
                                        id="pnc-btn-agregar">Agregar Producto No Conforme</button>
                                </div>

                                <!--Tabla de PNC-->
                                <div class="div-mis-datos inputs-responsive">
                                    <div class="tabla-container">
                                        <div class="tabla-scroll">
                                            <table id="pnc-tabla">
                                                <thead>
                                                    <tr>
                                                        <th>Folio</th>
                                                        <th>Fecha</th>
                                                        <th>Especificación implantada</th>
                                                        <th>Acción implantada</th>
                                                        <th>Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Datos dinámicos -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!--Usuarios-->
                                <div class="div-subtitle">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Acceso a usuarios</h2>
                                </div>
                                <hr class="hr-blue">

                                <div class="div-mis-datos">
                                    <label for="pnc-usuarios" class="form-label">Agrega usuarios:</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control" list="usuarios" id="pnc-usuarios"
                                            placeholder="Buscar usuario...">
                                            <option value="">Agregue usuarios</option>
                                        </select>
                                        <button class="btn btn-primary" type="button"
                                            id="pnc-btn-usuarios">Agregar</button>
                                    </div>
                                    <!--Div de usuarios-->
                                    <div class="div-participantes inputs-responsive" id="pnc-div-usuarios"></div>
                                </div>

                                <!--botones-->
                                <div class="div_buttons">
                                    <button type="submit" class="btn-apartado-center escalado">Modificar lista de Productos No Conformes</button>
                                    <button type="button" class="btn-apartado-center btn_green escalado" id="btn-validar">Cerrar/abrir proceso</button>
                                    <button type="button" class="btn-apartado-center btn_red escalado" id="btn-eliminar">Eliminar lista de Productos No Conformes</button>
                                    <button type="button" class="btn-apartado-center btn_orange escalado" id="btn-pdf">Generar documento de PNC</button>
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

    <div id="modalModificarPNCFondo" class="modalModificarActividadFondo"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); display: none; align-items: center; justify-content: center; z-index: 9999;">
        <div id="modalModificarActividad"
            style="background-color: #fff; padding: 20px; border-radius: 10px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; box-shadow: 0 0 15px rgba(0,0,0,0.3);">
            <h3 style="margin-top: 0;">Modificar Producto No Conforme</h3>
            <div class="div-datos-mis-datos inputs-responsive">
                <div class="div-datos-mis-datos inputs-responsive">
                    <!--Folio-->
                    <div class="mb-3 inputs-responsive">
                        <label for="modificar-pnc-folio" class="form-label inputs-responsive">Folio:</label>
                        <input type="text" class="form-control inputs-responsive" id="modificar-pnc-folio">
                    </div>
                    <!--Fecha de registro-->
                    <div class="mb-3 inputs-responsive">
                        <label for="modificar-pnc-fecha" class="form-label inputs-responsive">Fecha de
                            registro</label>
                        <input type="date" class="form-control inputs-responsive" id="modificar-pnc-fecha">
                    </div>
                    <!--Especificación-->
                    <div class="form-floating inputs-responsive">
                        <textarea class="form-control inputs-responsive" placeholder="Especificación inclumplida"
                            id="modificar-pnc-especificacion" style="height: 100px"></textarea>
                        <label for="modificar-pnc-especificacion">Especificación inclumplida</label>
                    </div>
                    <!--Acción-->
                    <div class="form-floating inputs-responsive">
                        <textarea class="form-control inputs-responsive" placeholder="Acción implantada"
                            id="modificar-pnc-accion" style="height: 100px"></textarea>
                        <label for="modificar-pnc-accion">Acción implantada</label>
                    </div>
                    <!--Numero de RAC-->
                    <div class="mb-3 inputs-responsive">
                        <label for="modificar-pnc-rac" class="form-label inputs-responsive">Número de RAC:</label>
                        <input type="text" class="form-control inputs-responsive" id="modificar-pnc-rac">
                    </div>

                    <!--Checks-->
                    <label for="" class="form-label inputs-responsive">Eliminar PNC:</label>
                    <div class="div-mis-datos">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="CheckYesModificar" name="checkModificar"
                                value="Si">
                            <label class="form-check-label" for="CheckYesModificar">
                                Si
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="CheckNoModificar" name="checkModificar"
                                value="No" checked>
                            <label class="form-check-label" for="CheckNoModificar">
                                No
                            </label>
                        </div>
                    </div>

                    <!--Personal que verifica-->
                    <div class="mb-3">
                        <label for="modificar-pnc-verifica" class="form-label">Personal que verifica:</label>
                        <select class="form-select" id="modificar-pnc-verifica" required>
                            <option value="" selected>Seleccione al personal que verifica
                            </option>
                        </select>
                    </div>
                    <!--Personal que libera-->
                    <div class="mb-3">
                        <label for="modificar-pnc-libera" class="form-label">Personal que libera:</label>
                        <select class="form-select" id="modificar-pnc-libera" required>
                            <option value="" selected>Seleccione al personal que libera
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div style="text-align: right; margin-top: 15px;">
                <button type="button" id="btnCancelarModificarPNC" class="btn btn-secondary">Cancelar</button>
                <button type="button" id="btnGuardarModificarPNC" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>

    <script type="module" src="./../../js/pnc.js"></script>
</body>

</html>