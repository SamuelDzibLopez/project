<?php
require_once './../../server/php/verificacion.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditoria</title>
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
                            <h2 class="font-title">Información de auditoria interna</h2>
                        </div>

                        <!-- Auditoria -->
                        <div class="div-gray" id="auditoria">
                            <form class="div-main-blur" id="form-auditoria">
                                <!--Institutos-->
                                <div class="div-subtitle">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Institutos</h2>
                                </div>
                                <hr class="hr-blue">
                                <div class="div-mis-datos">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="institutoNorte">
                                        <label class="form-check-label" for="institutoNorte">
                                            Instituto Tecnológico de Mérida Campus Norte
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="institutoPoniente">
                                        <label class="form-check-label" for="institutoPoniente">
                                            Instituto Tecnológico de Mérida Campus Poniente
                                        </label>
                                    </div>
                                </div>

                                <!--Información general-->
                                <div class="div-subtitle">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Información General</h2>
                                </div>
                                <hr class="hr-blue">
                                <div class="div-mis-datos">
                                    <div class="div-datos-mis-datos">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" placeholder="objetivo" id="objetivo"
                                                style="height: 100px"></textarea>
                                            <label for="queja">Objetivo</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" placeholder="alcance" id="alcance"
                                                style="height: 100px"></textarea>
                                            <label for="alcance">Alcance</label>
                                        </div>
                                        <div class="mb-3">
                                            <label for="numero" class="form-label">Número de auditoria:</label>
                                            <input type="text" class="form-control" id="numero">
                                        </div>
                                        <div class="mb-3">
                                            <label for="carrera" class="form-label">Proceso:</label>
                                            <select class="form-select" id="carrera" required>
                                                <option value="" selected disabled>Seleccione un proceso</option>
                                                <option value="Academico">Academico</option>
                                                <option value="Vinculacion">Vinculación</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="lider" class="form-label">Auditor lider:</label>
                                            <select class="form-select" id="lider">
                                                <option value="" selected>Seleccione el auditor lider</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <select class="form-select" id="lider2">
                                                <option value="" selected>Seleccione el auditor lider</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <select class="form-select" id="lider3">
                                                <option value="" selected>Seleccione el auditor lider</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="lider" class="form-label">Persona que recibe:</label>
                                            <select class="form-select" id="recibe" required>
                                                <option value="" selected disabled>Seleccione al personal que recibe</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!--Grupo auditor-->
                                <div class="div-subtitle">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Grupo auditor</h2>
                                </div>
                                <hr class="hr-blue">

                                <div class="div-mis-datos">
                                    <label for="auditor" class="form-label">Agrega
                                        auditor:</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control" list="auditor" id="auditor"
                                            placeholder="Buscar usuario...">
                                            <option value="" disebled> Agrege participantes</option>
                                        </select>
                                        <button class="btn btn-primary" type="button" id="btnAuditores">Agregar</button>
                                    </div>
                                    <!--Div de auditor-->
                                    <div class="div-participantes inputs-responsive" id="divAuditores">
                                    </div>
                                </div>

                                <!--Reunión de apertura-->
                                <div class="div-subtitle inputs-responsive">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Reunión de apertura</h2>
                                </div>

                                <hr class="hr-blue">

                                <div class="div-mis-datos">
                                    <div class="div-datos-mis-datos inputs-responsive">
                                        <div class="mb-3">
                                            <label for="inicioApertura" class="form-label ">Fecha y hora de
                                                inicio</label>
                                            <input type="datetime-local" class="form-control" id="inicioApertura">
                                        </div>
                                        <div class="mb-3">
                                            <label for="finApertura" class="form-label">Fecha y hora de
                                                final</label>
                                            <input type="datetime-local" class="form-control" id="finApertura">
                                        </div>
                                        <div class="mb-3">
                                            <label for="areaApertura" class="form-label">Areá/Sitio:</label>
                                            <input type="text" class="form-control" id="areaApertura">
                                        </div>
                                    </div>
                                </div>

                                <!--Reunión de cierre-->
                                <div class="div-subtitle inputs-responsive">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Reunión de cierre</h2>
                                </div>

                                <hr class="hr-blue">

                                <div class="div-mis-datos">
                                    <div class="div-datos-mis-datos">
                                        <div class="mb-3">
                                            <label for="inicioCierre" class="form-label">Fecha y hora de
                                                inicio</label>
                                            <input type="datetime-local" class="form-control" id="inicioCierre">
                                        </div>
                                        <div class="mb-3">
                                            <label for="finCierre" class="form-label">Fecha y hora de
                                                final</label>
                                            <input type="datetime-local" class="form-control" id="finCierre">
                                        </div>
                                        <div class="mb-3">
                                            <label for="areaCierre" class="form-label">Areá/Sitio:</label>
                                            <input type="text" class="form-control" id="areaCierre">
                                        </div>
                                    </div>
                                </div>

                                <!--Fecha de entrega de evidencia-->
                                <div class="div-subtitle inputs-responsive">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Fecha de entrega de evidencia</h2>
                                </div>

                                <hr class="hr-blue">

                                <div class="div-mis-datos">
                                    <div class="div-datos-mis-datos">
                                        <div class="mb-3">
                                            <label for="entregaEvidencia" class="form-label">Fecha de entrega de
                                                evidencia</label>
                                            <input type="datetime-local" class="form-control" id="entregaEvidencia">
                                        </div>
                                    </div>
                                </div>

                                <!--Actividades-->
                                <div class="div-subtitle">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Actividades</h2>
                                </div>
                                <hr class="hr-blue">

                                <div class="div-datos-mis-datos inputs-responsive">
                                    <!--Fecha y Hora de inicio-->
                                    <div class="mb-3 inputs-responsive">
                                        <label for="inicioActividad" class="form-label inputs-responsive">Fecha y hora
                                            de
                                            inicio</label>
                                        <input type="datetime-local" class="form-control inputs-responsive"
                                            id="inicioActividad">
                                    </div>
                                    <!--Fecha y Hora final-->
                                    <div class="mb-3 inputs-responsive">
                                        <label for="finActividad" class="form-label inputs-responsive">Fecha y hora de
                                            final</label>
                                        <input type="datetime-local" class="form-control inputs-responsive"
                                            id="finActividad">
                                    </div>
                                    <!--Tipo de proceso-->
                                    <label for="tipoProceso" class="form-label">Tipo de proceso:</label>

                                    <div class="input-group mb-3">
                                        <select class="form-control" list="opcionesUsuarios" id="tipoProceso"
                                            placeholder="Buscar usuario...">
                                            <option value="">Seleccione tipo de proceso</option>
                                            <option value="Academico">Academico</option>
                                            <option value="Calidad">Calidad</option>
                                            <option value="Ambiental">Ambiental</option>
                                        </select>
                                    </div>
                                    <!--Actividad-->
                                    <div class="form-floating inputs-responsive">
                                        <textarea class="form-control inputs-responsive" placeholder="Actividad"
                                            id="actividadTexto" style="height: 100px"></textarea>
                                        <label for="actividadTexto">Actividad</label>
                                    </div>
                                    <!--Requisito/Criterio-->
                                    <div class="mb-3 inputs-responsive">
                                        <label for="requisitoCriterio"
                                            class="form-label inputs-responsive">Requisito/Criterio:</label>
                                        <input type="text" class="form-control inputs-responsive"
                                            id="requisitoCriterio">
                                    </div>
                                    <!--Agregar Participantes-->
                                    <label for="participantesActividad" class="form-label">Agrega participantes:</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control" list="opcionesUsuarios" id="participantesActividad"
                                            placeholder="Buscar usuario...">
                                            <option value="" disebled> Agrege participantes</option>
                                        </select>
                                        <button class="btn btn-primary" type="button"
                                            id="btnParticipantesActividad">Agregar</button>
                                    </div>
                                    <!--Div de participantes-->
                                    <div class="div-participantes inputs-responsive" id="divParticipantesActividad">
                                    </div>

                                    <!--Agregar contactos-->
                                    <label for="contactosActividad" class="form-label">Agrega contactos:</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control" list="opcionesUsuarios" id="contactosActividad"
                                            placeholder="Buscar usuario...">
                                            <option value="" disebled> Agrege contactos</option>
                                        </select>
                                        <button class="btn btn-primary" type="button"
                                            id="btnContactosActividad">Agregar</button>
                                    </div>
                                    <!--Div de contactos-->
                                    <div class="div-participantes inputs-responsive" id="divContactosActividad">
                                    </div>
                                    <!--Area/Sitio-->
                                    <div class="mb-3 inputs-responsive">
                                        <label for="areaSitioActividad"
                                            class="form-label inputs-responsive">Areá/Sitio:</label>
                                        <input type="text" class="form-control inputs-responsive"
                                            id="areaSitioActividad">
                                    </div>
                                </div>

                                <!--Boton de agregar Actividad-->
                                <div class="div_buttons inputs-responsive">
                                    <button type="button"
                                        class="inputs-responsive btn-apartado-center escalado btn_green"
                                        id="btnAgregarActividad">Agregar Actividad</button>
                                </div>

                                <!--Tabla de actividades-->
                                <div class="div-mis-datos inputs-responsive">
                                    <div class="tabla-container">
                                        <div class="tabla-scroll">
                                            <table id="tabla-actividades">
                                                <thead>
                                                    <tr>
                                                        <th>Horario</th>
                                                        <th>Proceso</th>
                                                        <th>Actividad</th>
                                                        <th>Requisito/Criterio</th>
                                                        <th>Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- <tr value="1">
                                                        <td>08:00 - 09:00</td>
                                                        <td>Planeación</td>
                                                        <td>Revisión de documentos</td>
                                                        <td>ISO 9001:2015</td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-modificar escalado">Modificar</button>
                                                            <button type="button"
                                                                class="btn btn-eliminar escalado">Eliminar</button>
                                                        </td>
                                                    </tr> -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!--Participantes-->
                                <div class="div-subtitle">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Personal contactado</h2>
                                </div>
                                <hr class="hr-blue">

                                <div class="div-mis-datos">
                                    <label for="participantes" class="form-label">Agrega
                                        participantes:</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control" list="participantes" id="participantes"
                                            placeholder="Buscar usuario...">
                                            <option value="" disebled> Agrege participantes</option>
                                        </select>
                                        <button class="btn btn-primary" type="button" id="btnParticipantes">Agregar</button>
                                    </div>
                                    <!--Div de auditor-->
                                    <div class="div-participantes inputs-responsive" id="divParticipantes">
                                    </div>
                                </div>

                                <!--Mejoras-->
                                <div class="div-subtitle">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Oportunidades de mejoras</h2>
                                </div>
                                <hr class="hr-blue">

                                <div class="div-mis-datos">
                                    <label for="mejoras" class="form-label">Agrega
                                        Oportunidad de mejora:</label>
                                    <div class="input-group mb-3">
                                        <input class="form-control" list="mejoras" id="mejoras"
                                            placeholder="Agregar oportunidad de mejora">
                                        <button class="btn btn-primary" type="button" id="btnMejoras">Agregar</button>
                                    </div>
                                </div>

                                <!--Tabla de mejoras-->
                                <div class="div-mis-datos inputs-responsive">
                                    <div class="tabla-container">
                                        <div class="tabla-scroll">
                                            <table id="tabla-mejoras">
                                                <thead>
                                                    <tr>
                                                        <th>Oportunidades de mejora</th>
                                                        <th>Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- <tr value="1">
                                                        <td>ISO 9001:2015</td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-modificar escalado">Modificar</button>
                                                            <button type="button"
                                                                class="btn btn-eliminar escalado">Eliminar</button>
                                                        </td>
                                                    </tr> -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!--comentarios-->
                                <div class="div-subtitle">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Comentarios</h2>
                                </div>
                                <hr class="hr-blue">

                                <div class="div-mis-datos">
                                    <label for="comentarios" class="form-label">Agrega Comentario:</label>
                                    <div class="input-group mb-3">
                                        <input class="form-control" list="mejoras" id="comentarios"
                                            placeholder="Agregar comentario">
                                        <button class="btn btn-primary" type="button"
                                            id="btnComentarios">Agregar</button>
                                    </div>
                                </div>

                                <!--Tabla de comentarios-->
                                <div class="div-mis-datos inputs-responsive">
                                    <div class="tabla-container">
                                        <div class="tabla-scroll">
                                            <table id="tabla-comentarios">
                                                <thead>
                                                    <tr>
                                                        <th>Comentario</th>
                                                        <th>Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- <tr value="1">
                                                        <td>ISO 9001:2015</td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-modificar escalado">Modificar</button>
                                                            <button type="button"
                                                                class="btn btn-eliminar escalado">Eliminar</button>
                                                        </td>
                                                    </tr> -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!--No Conformidad-->
                                <div class="div-subtitle">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">No Conformidades</h2>
                                </div>
                                <hr class="hr-blue">

                                <div class="div-mis-datos">
                                    <label for="noConformidad" class="form-label">Agrega No Conformidad:</label>
                                    <div class="input-group mb-3">
                                        <input class="form-control" list="mejoras" id="noConformidad"
                                            placeholder="Agregar no conformidad">
                                    </div>
                                    <div class="input-group mb-3">
                                        <input class="form-control" list="mejoras" id="noConformidadRequisitos"
                                            placeholder="Requisito">
                                    </div>
                                </div>

                                <!--Boton de agregar NC-->
                                <div class="div_buttons inputs-responsive">
                                    <button type="button"
                                        class="inputs-responsive btn-apartado-center escalado btn_green"
                                        id="btnNoconformidades">Agregar No Conformidad</button>
                                </div>

                                <!--Tabla de no conformidades-->
                                <div class="div-mis-datos inputs-responsive">
                                    <div class="tabla-container">
                                        <div class="tabla-scroll">
                                            <table id="tabla-noconformidades">
                                                <thead>
                                                    <tr>
                                                        <th>No conformidades</th>
                                                        <th>Requisito</th>
                                                        <th>Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- <tr value="1">
                                                        <td>ISO 9001:2015</td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-modificar escalado">Modificar</button>
                                                            <button type="button"
                                                                class="btn btn-eliminar escalado">Eliminar</button>
                                                        </td>
                                                    </tr> -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!--Conclusiones-->
                                <div class="div-subtitle">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Conclusiones</h2>
                                </div>
                                <hr class="hr-blue">

                                <div class="div-mis-datos">
                                    <label for="conclusion" class="form-label">Agrega Conclusión:</label>
                                    <div class="input-group mb-3">
                                        <input class="form-control" list="conclusion" id="conclusion"
                                            placeholder="Agregar conclusión">
                                        <button class="btn btn-primary" type="button"
                                            id="btnConclusiones">Agregar</button>
                                    </div>
                                </div>

                                <!--Tabla de conclusiones-->
                                <div class="div-mis-datos inputs-responsive">
                                    <div class="tabla-container">
                                        <div class="tabla-scroll">
                                            <table id="tabla-conclusiones">
                                                <thead>
                                                    <tr>
                                                        <th>Conclusiones</th>
                                                        <th>Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- <tr value="1">
                                                        <td>ISO 9001:2015</td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-modificar escalado">Modificar</button>
                                                            <button type="button"
                                                                class="btn btn-eliminar escalado">Eliminar</button>
                                                        </td>
                                                    </tr> -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!--No Conformidad-->
                                <div class="div-subtitle">
                                    <img src="/residencia/sources/icons/icon-proyectos.svg" alt="">
                                    <h2 class="font-subtitle">Acceso a usuarios</h2>
                                </div>
                                <hr class="hr-blue">

                                <div class="div-mis-datos">
                                    <label for="usuarios" class="form-label">Agrega
                                        usuarios:</label>
                                    <div class="input-group mb-3">
                                        <select class="form-control" list="usuarios" id="usuarios"
                                            placeholder="Buscar usuario...">
                                            <option value="" disebled> Agrege usuarios</option>
                                        </select>
                                        <button class="btn btn-primary" type="button" id="btnUsuarios">Agregar</button>
                                    </div>
                                    <!--Div de usuarios-->
                                    <div class="div-participantes inputs-responsive" id="divUsuarios">
                                    </div>
                                </div>

                                <!--botones-->
                                <div class="div_buttons">
                                    <button type="submit" class="btn-apartado-center escalado">Modificar datos</button>
                                    <button type="button" class="btn-apartado-center btn_green escalado" id="btn-validar">Cerrar/abrir proceso</button>
                                    <button type="button" class="btn-apartado-center btn_red escalado" id="btn-eliminar">Eliminar auditoria</button>
                                    <button type="button" class="btn-apartado-center btn_orange escalado" id="btn-pdf-1">Generar Plan de Auditoria</button>
                                    <button type="button" class="btn-apartado-center btn_orange escalado" id="btn-pdf-2">Generar Reunión de Apertura</button>
                                    <button type="button" class="btn-apartado-center btn_orange escalado" id="btn-pdf-3">Generar Reunión de Cierre</button>
                                    <button type="button" class="btn-apartado-center btn_orange escalado" id="btn-pdf-4">Generar Informe de Auditoria</button>
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

    <div id="modalModificarActividadFondo" class="modalModificarActividadFondo" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); display: none; align-items: center; justify-content: center; z-index: 9999;">
        <div id="modalModificarActividad" style="background-color: #fff; padding: 20px; border-radius: 10px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; box-shadow: 0 0 15px rgba(0,0,0,0.3);">
            <h3 style="margin-top: 0;">Modificar Actividad</h3>
            <div class="div-datos-mis-datos inputs-responsive">
                <!-- Inicio -->
                <div class="mb-3 inputs-responsive">
                    <label for="modificarInicioActividad">Fecha y hora de inicio</label>
                    <input type="datetime-local" class="form-control inputs-responsive" id="modificarInicioActividad">
                </div>
                <!-- Fin -->
                <div class="mb-3 inputs-responsive">
                    <label for="modificarFinActividad">Fecha y hora de final</label>
                    <input type="datetime-local" class="form-control inputs-responsive" id="modificarFinActividad">
                </div>
                <!-- Tipo de proceso -->
                <label for="modificarTipoProceso">Tipo de proceso:</label>
                <div class="input-group mb-3">
                    <select class="form-control" id="modificarTipoProceso">
                        <option value="">Seleccione tipo de proceso</option>
                        <option value="Academico">Académico</option>
                        <option value="Calidad">Calidad</option>
                        <option value="Ambiental">Ambiental</option>
                    </select>
                </div>
                <!-- Actividad -->
                <div class="form-floating inputs-responsive">
                    <textarea class="form-control inputs-responsive" placeholder="Actividad"
                        id="modificarActividadTexto" style="height: 100px"></textarea>
                    <label for="modificarActividadTexto">Actividad</label>
                </div>
                <!-- Requisito/Criterio -->
                <div class="mb-3 inputs-responsive">
                    <label for="modificarRequisitoCriterio">Requisito/Criterio:</label>
                    <input type="text" class="form-control inputs-responsive" id="modificarRequisitoCriterio">
                </div>
                <!-- Participantes -->
                <label for="modificarParticipantesActividad">Agrega participantes:</label>
                <div class="input-group mb-3">
                    <select class="form-control" id="modificarParticipantesActividad">
                        <option value="">Agregue participantes</option>
                    </select>
                    <button class="btn btn-primary" type="button"
                        id="btnModificarParticipantesActividad">Agregar</button>
                </div>
                <div class="div-participantes inputs-responsive" id="divModificarParticipantesActividad"></div>
                <!-- Contactos -->
                <label for="modificarContactosActividad">Agrega contactos:</label>
                <div class="input-group mb-3">
                    <select class="form-control" id="modificarContactosActividad">
                        <option value="">Agregue contactos</option>
                    </select>
                    <button class="btn btn-primary" type="button" id="btnModificarContactosActividad">Agregar</button>
                </div>
                <div class="div-participantes inputs-responsive" id="divModificarContactosActividad"></div>
                <!-- Área/Sitio -->
                <div class="mb-3 inputs-responsive">
                    <label for="modificarAreaSitioActividad">Área/Sitio:</label>
                    <input type="text" class="form-control inputs-responsive" id="modificarAreaSitioActividad">
                </div>
            </div>

            <div style="text-align: right; margin-top: 15px;">
                <button type="button" id="btnCancelarModificarActividad" class="btn btn-secondary">Cancelar</button>
                <button type="button" id="btnGuardarModificarActividad" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
<script type="module" src="./../../js/auditoria.js"></script>
<script type="module" src="./../../js/modificar-auditoria.js"></script>
</body>

</html>

