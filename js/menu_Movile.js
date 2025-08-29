//1. Importacion de modulos

//2. Funciones de pagina

//A. Abrir menu movile
export function open_Menu_Movile ($menuMovile) {
    $menuMovile.style.setProperty("display", "flex");
}

//B. Cerrar menu movile
export function close_Menu_Movile ($menuMovile) {
    $menuMovile.style.setProperty("display", "none");
}

//3. Captura de elementos 

let $btnMenuMovile = document.getElementById("btn-menu-movile");
let $menuMovile = document.getElementById("menu-movile");
let $btnCloseMenuMovile = document.getElementById("btn-close-menu-movile");

//4. Asignación de eventos a botones de apartados

//A. Abrir menu movile
$btnMenuMovile.addEventListener("click", () => {
    open_Menu_Movile($menuMovile);    
});

//B. Cerrar menu movile
$btnCloseMenuMovile.addEventListener("click", () => {
    close_Menu_Movile($menuMovile);    
});


