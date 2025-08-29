//1. Asignación de eventos a botones de apartados

//Importación de modulos
import { redirigirAMmodulo, fetchAndRenderUsers } from "./functions/functions.js";
import { url_usuarios_obtener_usuarios, nuevoUsuario } from "./urls/urls.js";
//2. Funciones de pagina

//3. Captura de elementos 
let $btnNuevoUsuario = document.getElementById("btn-nuevo-usuario");
let $divUsers = document.getElementById("div_users");
let $btnUsers = document.getElementById("btn_users");

var stateUsuarios = 1;

//4. Asignación de eventos a botones de apartados

//A. Boton para boton de Nuevo Usuario
$btnNuevoUsuario.addEventListener("click", () => {
    redirigirAMmodulo(nuevoUsuario);
});

//B. Boton para traer mas usuarios.
$btnUsers.addEventListener("click", async (e) => {

    //Sumar al state
    stateUsuarios = stateUsuarios + 1;

    //Llamado a funcion fetchAndRenderUsers
    fetchAndRenderUsers(stateUsuarios, $btnUsers, $divUsers, url_usuarios_obtener_usuarios);
});

//D. Llamado a funcion para llamar usuarios iniciales
fetchAndRenderUsers(stateUsuarios, $btnUsers, $divUsers, url_usuarios_obtener_usuarios);




