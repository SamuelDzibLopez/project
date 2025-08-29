// 1. Importación de módulos
import { redirigirAMmodulo, fetchAndRenderUsers } from "./functions/functions.js";
import { url_usuarios_obtener_usuarios,  url_procesos_obtener_procesos, miPerfil, usuarios, procesos, documentos } from "./urls/urls.js";

// 2. Funciones de página

// 3. Captura de elementos
const $btnVerPerfil = document.getElementById("btn-ver-perfil");
const $btnVerUsuarios = document.getElementById("btn-ver-usuarios");
const $btnVerProyectos = document.getElementById("btn-ver-proyectos");
const $btnVerDocumentos = document.getElementById("btn-ver-documentos");

const $divUsers = document.getElementById("div_users");
const $divProcesos = document.getElementById("div_procesos");

const subsection = "procesos";

// 4. Asignación de eventos a botones de apartados

// A. Botón para ver perfil
if ($btnVerPerfil) {
    $btnVerPerfil.addEventListener("click", () => {
        redirigirAMmodulo(miPerfil);
    });
}

// B. Botón para ver usuarios
if ($btnVerUsuarios) {
    $btnVerUsuarios.addEventListener("click", () => {
        redirigirAMmodulo(usuarios);
    });
}

// C. Botón para ver proyectos
if ($btnVerProyectos) {
    $btnVerProyectos.addEventListener("click", () => {
        redirigirAMmodulo(procesos);
    });
}

// D. Botón para ver documentos
if ($btnVerDocumentos) {
    $btnVerDocumentos.addEventListener("click", () => {
        redirigirAMmodulo(documentos);
    });
}

// 5. Llamado a función para obtener usuarios iniciales
if ($divUsers) {
    fetchAndRenderUsers(1, NaN, $divUsers, url_usuarios_obtener_usuarios);
}

// 6. Llamado a función para obtener procesos iniciales
if ($divProcesos) {
    fetchAndRenderUsers(1, NaN, $divProcesos, url_procesos_obtener_procesos, subsection);
}
