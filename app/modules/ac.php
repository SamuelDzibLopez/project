<?php
require_once './../../server/php/verificacion.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acción Correctiva</title>
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
                            <h2 class="font-title">Información de Acción Correctiva</h2>
                        </div>

                        <!--Acciones correctiva-->
                        <div class="div-gray" id="accionCorrectiva">
                            <form class="div-main-blur" id="ac-form-modificar">

                                <!--Información general-->
                                <div class="div-subtitle">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Información General</h2>
                                </div>
                                <hr class="hr-blue">
                                <div class="div-mis-datos">
                                    <div class="div-datos-mis-datos">
                                        <div class="mb-3 inputs-responsive">
                                            <label for="ac-folio" class="form-label inputs-responsive">Folio:</label>
                                            <input type="text" class="form-control inputs-responsive" id="ac-folio">
                                        </div>
                                        <div class="mb-3 inputs-responsive">
                                            <label for="ac-area-proceso" class="form-label inputs-responsive">Área del proceso:</label>
                                            <select class="form-select" id="ac-area-proceso">
                                                <option value="" selected>Seleccione el área del proceso</option>
                                                <option value="Calidad">Calidad</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 inputs-responsive">
                                            <label for="ac-fecha" class="form-label inputs-responsive">Fecha de registro</label>
                                            <input type="date" class="form-control inputs-responsive" id="ac-fecha">
                                        </div>
                                        <div class="mb-3 inputs-responsive">
                                            <label for="ac-origen-requisito" class="form-label inputs-responsive">Origen del requisito:</label>
                                            <select class="form-select" id="ac-origen-requisito">
                                                <option value="" selected>Seleccione el origen del requisito</option>
                                                <option value="Calidad">Calidad</option>
                                                <option value="Ambiental">Ambiental</option>
                                                <option value="Energia">Energia</option>
                                                <option value="R-025">R-025</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 inputs-responsive">
                                            <label for="ac-fuente-nc" class="form-label inputs-responsive">Fuente de la No Conformidad:</label>
                                            <select class="form-select" id="ac-fuente-nc">
                                                <option value="" selected>Seleccione la fuente de la No Conformidad</option>
                                                <option value="1">Quejas de parte interesadas</option>
                                                <option value="2">Riesgos</option>
                                                <option value="3">Auditoría Externa</option>
                                                <option value="4">Oportunidades</option>
                                                <option value="5">Auditoría Interna</option>
                                                <option value="6">Incumplimiento a objetivo/Indicador</option>
                                                <option value="7">Resultado de Análisis de datos</option>
                                                <option value="8">Aspecto Ambiental</option>
                                                <option value="9">Revisión por la Dirección</option>
                                                <option value="10">Accidente incidente</option>
                                                <option value="11">Desviaciones del proceso</option>
                                                <option value="12">Atención y respuesta a Emergencia</option>
                                                <option value="13">Producto No conforme</option>
                                                <option value="14">Otra</option>
                                            </select>
                                        </div>
                                                <div class="form-floating inputs-responsive">
                                                    <textarea class="form-control inputs-responsive" placeholder="Descripción"
                                                        id="ac-descripcion" style="height: 100px"></textarea>
                                                    <label for="ac-descripcion">Descripción</label>
                                                </div>
                                        <div class="mb-3">
                                            <label for="ac-personal-define" class="form-label">Personal que define:</label>
                                            <select class="form-select" id="ac-personal-define">
                                                <option value="" selected>Seleccione al personal que define</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="ac-persona-verifica" class="form-label">Persona que verifica:</label>
                                            <select class="form-select" id="ac-persona-verifica">
                                                <option value="" selected>Seleccione al personal que verifica</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="ac-coordinador-general" class="form-label">Coordinador General de Calidad:</label>
                                            <select class="form-select" id="ac-coordinador-general">
                                                <option value="" selected>Seleccione al Coordinador General de Calidad</option>
                                            </select>
                                        </div>
                                        <label class="form-label inputs-responsive">Requiere Acción Correcativa:</label>
                                        <div class="div-mis-datos">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="ac-accion-si" name="ac-requiere-accion" value="Si">
                                                <label class="form-check-label" for="ac-accion-si">Si</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="ac-accion-no" name="ac-requiere-accion" value="No" checked>
                                                <label class="form-check-label" for="ac-accion-no">No</label>
                                            </div>
                                        </div>
                                        <label class="form-label inputs-responsive">Requiere Corrección:</label>
                                        <div class="div-mis-datos">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="ac-correccion-si" name="ac-requiere-correccion" value="Si">
                                                <label class="form-check-label" for="ac-correccion-si">Si</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="ac-correccion-no" name="ac-requiere-correccion" value="No" checked>
                                                <label class="form-check-label" for="ac-correccion-no">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!--Correciones-->
                                <div class="div-subtitle">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Correciones</h2>
                                </div>
                                <hr class="hr-blue">
                                <div class="div-datos-mis-datos inputs-responsive">
                                    <!--Corrección-->
                                    <div class="form-floating inputs-responsive">
                                        <textarea class="form-control inputs-responsive" placeholder="Corrección"
                                            id="ac-correccion-textarea" style="height: 100px"></textarea>
                                        <label for="ac-correccion-textarea">Corrección</label>
                                    </div>
                                    <!--Responsable-->
                                    <div class="mb-3">
                                        <label for="ac-responsable-select" class="form-label">Responsable:</label>
                                        <select class="form-select" id="ac-responsable-select">
                                            <option value="" selected>Seleccione usuario responsable
                                            </option>
                                        </select>
                                    </div>
                                    <!--Fecha de registro-->
                                    <div class="mb-3 inputs-responsive">
                                        <label for="ac-fecha-registro" class="form-label inputs-responsive">Fecha de registro</label>
                                        <input type="date" class="form-control inputs-responsive" id="ac-fecha-registro">
                                    </div>
                                </div>

                                <!--Boton de agregar PNC-->
                                <div class="div_buttons inputs-responsive">
                                    <button type="button"
                                        class="inputs-responsive btn-apartado-center escalado btn_green"
                                        id="ac-btn-agregar-correccion">Agregar Corrección</button>
                                </div>

                                <!--Tabla de Correciones-->
                                <div class="div-mis-datos inputs-responsive">
                                    <div class="tabla-container">
                                        <div class="tabla-scroll">
                                            <table id="ac-tabla-correcciones">
                                                <thead>
                                                    <tr>
                                                        <th>Correción</th>
                                                        <th>Responsable</th>
                                                        <th>Fecha</th>
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

                                <div class="div-mis-datos">
                                    <div class="div-datos-mis-datos">
                                        <div class="form-floating inputs-responsive">
                                            <textarea class="form-control inputs-responsive" placeholder="Técnica estadistica utilizada"
                                                id="ac-tecnica-estadistica" style="height: 100px"></textarea>
                                            <label for="ac-tecnica-estadistica">Técnica estadistica utilizada</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="div-mis-datos">
                                    <div class="div-datos-mis-datos">
                                        <div class="form-floating inputs-responsive">
                                            <textarea class="form-control inputs-responsive" placeholder="Causa raiz identificada"
                                                id="ac-causa-raiz" style="height: 100px"></textarea>
                                            <label for="ac-causa-raiz">Causa raiz identificada</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="div-mis-datos">
                                    <div class="div-datos-mis-datos">
                                        <div class="form-floating inputs-responsive">
                                            <textarea class="form-control inputs-responsive" placeholder="Acción Correctiva para realizar"
                                                id="ac-accion-correctiva" style="height: 100px"></textarea>
                                            <label for="ac-accion-correctiva">Acción Correctiva para realizar</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="div-mis-datos">
                                    <div class="div-datos-mis-datos">
                                        <!-- No conformidades similares -->
                                        <label for="ac-nc-similares-si" class="form-label inputs-responsive">¿Existen No conformidades similares?</label>
                                        <div class="div-mis-datos">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="ac-nc-similares-si" name="ac-nc-similares" value="Si">
                                                <label class="form-check-label" for="ac-nc-similares-si">Si</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="ac-nc-similares-no" name="ac-nc-similares" value="No" checked>
                                                <label class="form-check-label" for="ac-nc-similares-no">No</label>
                                            </div>
                                        </div>

                                        <div class="div-datos-mis-datos">
                                            <div class="form-floating inputs-responsive">
                                                <textarea class="form-control inputs-responsive" placeholder="¿Cual? / Acciones"
                                                        id="ac-nc-similares-acciones" style="height: 100px"></textarea>
                                                <label for="ac-nc-similares-acciones">¿Cual? / Acciones</label>
                                            </div>
                                        </div>

                                        <!-- No conformidades potenciales -->
                                        <label for="ac-nc-potenciales-si" class="form-label inputs-responsive">¿Existen No conformidades Potenciales?</label>
                                        <div class="div-mis-datos">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="ac-nc-potenciales-si" name="ac-nc-potenciales" value="Si">
                                                <label class="form-check-label" for="ac-nc-potenciales-si">Si</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="ac-nc-potenciales-no" name="ac-nc-potenciales" value="No" checked>
                                                <label class="form-check-label" for="ac-nc-potenciales-no">No</label>
                                            </div>
                                        </div>

                                        <div class="div-datos-mis-datos">
                                            <div class="form-floating inputs-responsive">
                                                <textarea class="form-control inputs-responsive" placeholder="¿Cual? / Acciones"
                                                        id="ac-nc-potenciales-acciones" style="height: 100px"></textarea>
                                                <label for="ac-nc-potenciales-acciones">¿Cual? / Acciones</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--Acciones-->
                                <div class="div-subtitle">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Acciones</h2>
                                </div>
                                <hr class="hr-blue">

                                <div class="div-datos-mis-datos inputs-responsive">
                                    <!--Acción-->
                                    <div class="form-floating inputs-responsive">
                                        <textarea class="form-control inputs-responsive" placeholder="Acción"
                                            id="ac-textarea-accion" style="height: 100px"></textarea>
                                        <label for="ac-textarea-accion">Acción</label>
                                    </div>
                                    <!--Responsable-->
                                    <div class="mb-3">
                                        <label for="ac-select-responsable-accion" class="form-label">Responsable:</label>
                                        <select class="form-select" id="ac-select-responsable-accion">
                                            <option value="" selected>Seleccione usuario responsable</option>
                                        </select>
                                    </div>
                                    <!--Fecha de registro-->
                                    <div class="mb-3 inputs-responsive">
                                        <label for="ac-fecha-registro-accion" class="form-label inputs-responsive">Fecha programada</label>
                                        <input type="date" class="form-control inputs-responsive" id="ac-fecha-registro-accion">
                                    </div>
                                </div>

                                <!--Botón de agregar Acción-->
                                <div class="div_buttons inputs-responsive">
                                    <button type="button"
                                        class="inputs-responsive btn-apartado-center escalado btn_green"
                                        id="ac-btn-agregar-accion">Agregar Acción</button>
                                </div>

                                <!--Tabla de Acciones-->
                                <div class="div-mis-datos inputs-responsive">
                                    <div class="tabla-container">
                                        <div class="tabla-scroll">
                                            <table id="ac-tabla-acciones">
                                                <thead>
                                                    <tr>
                                                        <th>Acción</th>
                                                        <th>Responsable</th>
                                                        <th>Fecha</th>
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

                                <div class="div-datos-mis-datos">
                                    <div class="form-floating inputs-responsive">
                                        <textarea class="form-control inputs-responsive" placeholder="Causa raiz identificada" id="ac-seguimiento-evidencias" style="height: 100px"></textarea>
                                        <label for="ac-seguimiento-evidencias">Seguimiento y evidencias de acciones realizadas:</label>
                                    </div>
                                </div>

                                <div class="div-mis-datos">
                                    <div class="div-datos-mis-datos">
                                        <!-- ¿Es necesario actualizar Riesgos / oportunidades? -->
                                        <label for="ac-riesgos-si" class="form-label inputs-responsive">¿Es necesario actualizar Riesgos / oportunidades?</label>
                                        <div class="div-mis-datos">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="ac-riesgos-si" name="ac-riesgos" value="Si">
                                                <label class="form-check-label" for="ac-riesgos-si">Si</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="ac-riesgos-no" name="ac-riesgos" value="No" checked>
                                                <label class="form-check-label" for="ac-riesgos-no">No</label>
                                            </div>
                                        </div>

                                        <div class="div-datos-mis-datos">
                                            <div class="form-floating inputs-responsive">
                                                <textarea class="form-control inputs-responsive" placeholder="¿Cual? / Acciones"
                                                        id="ac-riesgos-acciones" style="height: 100px"></textarea>
                                                <label for="ac-riesgos-acciones">¿Cual? / Acciones</label>
                                            </div>
                                        </div>

                                        <!-- ¿Es necesario hacer cambios en el Sistema de Gestión? -->
                                        <label for="ac-cambios-sg-si" class="form-label inputs-responsive">¿Es necesario hacer cambios en el Sistema de Gestión?</label>
                                        <div class="div-mis-datos">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="ac-cambios-sg-si" name="ac-cambios-sg" value="Si">
                                                <label class="form-check-label" for="ac-cambios-sg-si">Si</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="ac-cambios-sg-no" name="ac-cambios-sg" value="No" checked>
                                                <label class="form-check-label" for="ac-cambios-sg-no">No</label>
                                            </div>
                                        </div>

                                        <div class="div-datos-mis-datos">
                                            <div class="form-floating inputs-responsive">
                                                <textarea class="form-control inputs-responsive" placeholder="¿Cual? / Acciones"
                                                        id="ac-cambios-sg-acciones" style="height: 100px"></textarea>
                                                <label for="ac-cambios-sg-acciones">¿Cual? / Acciones</label>
                                            </div>
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
                                    <label for="ac-usuarios" class="form-label">Agrega usuarios:</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control" list="usuarios" id="ac-usuarios"
                                            placeholder="Buscar usuario...">
                                            <option value="">Agregue usuarios</option>
                                        </select>
                                        <button class="btn btn-primary" type="button"
                                            id="ac-btn-agregar-usuarios">Agregar</button>
                                    </div>
                                    <!--Div de usuarios-->
                                    <div class="div-participantes inputs-responsive" id="ac-div-usuarios"></div>
                                </div>

                                <!--botones-->
                                <div class="div_buttons">
                                    <button type="submit" class="btn-apartado-center escalado">Modificar Acción correctiva</button>
                                    <button type="button" class="btn-apartado-center btn_green escalado" id="btn-validar">Cerrar/abrir proceso</button>
                                    <button type="button" class="btn-apartado-center btn_red escalado" id="btn-eliminar">Eliminar Acción Correctiva</button>
                                    <button type="button" class="btn-apartado-center btn_orange escalado" id="btn-pdf">Generar documento de AC</button>
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

    <!-- Modal Modificar Corrección -->
    <div id="modalModificarCorreccionFondo" class="modalModificarActividadFondo"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 9999;">
        <div id="ac-modificar-modal-correccion"
            style="background-color: #fff; padding: 20px; border-radius: 10px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; box-shadow: 0 0 15px rgba(0,0,0,0.3);">
            <h3 style="margin-top: 0;">Modificar Corrección</h3>
            <div class="div-datos-mis-datos inputs-responsive">
                <!-- Corrección -->
                <div class="form-floating inputs-responsive">
                    <textarea class="form-control inputs-responsive" placeholder="Corrección"
                        id="ac-modificar-correccion-textarea" style="height: 100px"></textarea>
                    <label for="ac-modificar-correccion-textarea">Corrección</label>
                </div>
                <!-- Responsable -->
                <div class="mb-3">
                    <label for="ac-modificar-responsable-select" class="form-label">Responsable:</label>
                    <select class="form-select" id="ac-modificar-responsable-select">
                        <option value="" selected>Seleccione usuario responsable</option>
                    </select>
                </div>
                <!-- Fecha de registro -->
                <div class="mb-3 inputs-responsive">
                    <label for="ac-modificar-fecha-registro" class="form-label inputs-responsive">Fecha de registro</label>
                    <input type="date" class="form-control inputs-responsive" id="ac-modificar-fecha-registro">
                </div>
            </div>

            <div style="text-align: right; margin-top: 15px;">
                <button type="button" id="ac-modificar-btn-cancelar-correccion" class="btn btn-secondary">Cancelar</button>
                <button type="button" id="ac-modificar-btn-guardar-correccion" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>

    <!-- Modal Modificar Acción -->
    <div id="modalModificarAccionFondo" class="modalModificarActividadFondo"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 9999;">
        <div id="ac-modificar-modal-accion"
            style="background-color: #fff; padding: 20px; border-radius: 10px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; box-shadow: 0 0 15px rgba(0,0,0,0.3);">
            <h3 style="margin-top: 0;">Modificar Acción</h3>
            <div class="div-datos-mis-datos inputs-responsive">
                <!-- Acción -->
                <div class="form-floating inputs-responsive">
                    <textarea class="form-control inputs-responsive" placeholder="Acción"
                        id="ac-modificar-accion-textarea" style="height: 100px"></textarea>
                    <label for="ac-modificar-accion-textarea">Acción</label>
                </div>
                <!-- Responsable -->
                <div class="mb-3">
                    <label for="ac-modificar-responsable-accion" class="form-label">Responsable:</label>
                    <select class="form-select" id="ac-modificar-responsable-accion">
                        <option value="" selected>Seleccione usuario responsable</option>
                    </select>
                </div>
                <!-- Fecha de registro -->
                <div class="mb-3 inputs-responsive">
                    <label for="ac-modificar-fecha-accion" class="form-label inputs-responsive">Fecha de registro</label>
                    <input type="date" class="form-control inputs-responsive" id="ac-modificar-fecha-accion">
                </div>
            </div>

            <div style="text-align: right; margin-top: 15px;">
                <button type="button" id="ac-modificar-btn-cancelar-accion" class="btn btn-secondary">Cancelar</button>
                <button type="button" id="ac-modificar-btn-guardar-accion" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>

    <script type="module" src="./../../js/ac.js"></script>
    <script type="module" src="./../../js/nueva-ac.js"></script>
</body>

</html>