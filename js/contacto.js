//Importación de modulos
import { get_Id_Contact, fetchAndGetContact, showModal, funcionFetch} from "./functions/functions.js";
import { url_contactos_modificar_contacto, url_contactos_obtener_info_contacto, url_contactos_eliminar_contacto, directorio } from "./urls/urls.js";
//Captura de elementos

// A. Obtener los parámetros de la URL
let urlParams = new URLSearchParams(window.location.search);

//Ejecutar función que retorna ID de contacto
let id_contact = get_Id_Contact(urlParams);

// console.log(id_contact);

let $perfil = document.getElementById("input-perfil");
let $imgPerfil = document.getElementById("img-perfil");

let $form = document.getElementById("form_datos");

let $btnEliminar = document.getElementById("btn-eliminar");


//A. Change de foto de perfil
$perfil.addEventListener("change", (event) => {
    const file = event.target.files[0]; // Obtener el archivo seleccionado

    const reader = new FileReader();

    // Cargar el archivo y mostrar la imagen
    reader.onload = function (e) {
        $imgPerfil.src = e.target.result; // Establecer la imagen cargada
    }

    reader.readAsDataURL(file); // Leer el archivo como una URL

});

//D. Submit de formulario
document.getElementById("form_datos").addEventListener("submit", async (e) => {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);

    // Archivos personalizados
    const perfil = document.getElementById("input-perfil").files[0];

    if (perfil) formData.append("perfil", perfil);

    try {
        const res = await fetch(`${url_contactos_modificar_contacto}${id_contact}`, {
            method: "POST",
            body: formData
        });
        const data = await res.json();

        console.log(data);

        alert(data.message);
        

    } catch (error) {
        alert(`Error en la petición: ${error}`);
    }
});

//E. Obtener datos de perfil
fetchAndGetContact($form, `${url_contactos_obtener_info_contacto}${id_contact}`);

// F. Eliminar contacto
$btnEliminar.addEventListener("click", async () => {
    const confirmar = confirm("¿Estás seguro de que deseas eliminar este contacto?");

    if (!confirmar) {
        return; // Si el usuario presiona "Cancelar", no hace nada
    }

    try {
        const URL = `${url_contactos_eliminar_contacto}${id_contact}`;
        const data = await funcionFetch(URL);

        console.log(data);

        if (data.ok) {
            // Mostrar mensaje de éxito al eliminar el usuario
            alert("Contacto eliminado correctamente.");
            window.location.href = directorio;
        } else {
            // Mostrar mensaje de error si no se pudo eliminar
            alert(data.message || "Error al eliminar el contacto.");
        }

    } catch (error) {
        // Mostrar mensaje de error general
        alert("Ocurrió un error, inténtelo más tarde.");
        console.error("Error:", error);
    }
});

