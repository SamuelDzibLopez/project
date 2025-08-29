//1. Asignación de eventos a botones de apartados

//Importación de modulos
import { redirigirAMmodulo, fetchAndRenderUsers } from "./functions/functions.js";
import {url_contactos_obtener_contactos, nuevoContacto} from "./urls/urls.js";

//2. Funciones de pagina

//3. Captura de elementos 
let $btnNuevoContacto = document.getElementById("btn-nuevo-contacto");
let $divContacts = document.getElementById("div_contacts");
let $btnContacts = document.getElementById("btn_contacts");

var stateContacts = 1;
let subsection="contactos";

//4. Asignación de eventos a botones de apartados

//A. Boton para boton de Nuevo Usuario
$btnNuevoContacto.addEventListener("click", () => {
    redirigirAMmodulo(nuevoContacto);
});

//B. Boton para traer mas usuarios.
$btnContacts.addEventListener("click", async (e) => {

    //Sumar al state
    stateContacts = stateContacts + 1;

    //Llamado a funcion fetchAndRenderUsers
    fetchAndRenderUsers(stateContacts, $btnContacts, $divContacts, url_contactos_obtener_contactos, subsection);
});

//D. Llamado a funcion para llamar usuarios iniciales
fetchAndRenderUsers(stateContacts, $btnContacts, $divContacts, url_contactos_obtener_contactos, subsection);