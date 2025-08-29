//1. Asignación de eventos a botones de apartados

//Importación de modulos
import { redirigirAMmodulo, fetchAndRenderUsers } from "./functions/functions.js";
import { url_procesos_obtener_procesos, nuevoProceso} from "./urls/urls.js";

//2. Funciones de pagina

//3. Captura de elementos 
let $btnNuevoProceso = document.getElementById("btn-nuevo-proceso");
let $divProcesos = document.getElementById("div_procesos");
let $btnProcesos = document.getElementById("btn_procesos");

var stateProcesos = 1;
let subsection = "procesos"
//4. Asignación de eventos a botones de apartados

//A. Boton para boton de Nuevo Usuario
if ($btnNuevoProceso) {
    $btnNuevoProceso.addEventListener("click", () => {
        redirigirAMmodulo(nuevoProceso);
    });
}

//B. Boton para traer mas usuarios.
$btnProcesos.addEventListener("click", async (e) => {

    //Sumar al state
    stateProcesos= stateProcesos + 1;

    //Llamado a funcion fetchAndRenderUsers
    fetchAndRenderUsers(stateProcesos, $btnProcesos, $divProcesos, url_procesos_obtener_procesos, subsection);
});

//D. Llamado a funcion para llamar usuarios iniciales
fetchAndRenderUsers(stateProcesos, $btnProcesos, $divProcesos, url_procesos_obtener_procesos, subsection);




