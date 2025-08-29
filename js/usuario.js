//Importación de modulos
import { get_Id_User, fetchAndGetUser, showModal, funcionFetch} from "./functions/functions.js";
import {url_usuarios_modificar_usuario,url_usuarios_validar_usuario, url_usuarios_obtener_info_usuario, url_usurios_eliminar_usuario, usuarios} from "./urls/urls.js"

//Captura de elementos

// A. Obtener los parámetros de la URL
let urlParams = new URLSearchParams(window.location.search);

//Ejecutar función que retorna ID de usuario
let id_user = get_Id_User(urlParams);

// console.log(id_user);

let $perfil = document.getElementById("input-perfil");
let $imgPerfil = document.getElementById("img-perfil");

let $vigencia = document.getElementById("doc_vigencia");
let $textVigencia = document.getElementById("text_vigencia");

let $efirma = document.getElementById("efirma");
let $imgEfirma = document.getElementById("img-efirma");

let $form = document.getElementById("form_datos");

let $btnValidar = document.getElementById("btn-validar");
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

//B. change de documento de vigencia
$vigencia.addEventListener("change", (event) => {
    const file = event.target.files[0];
    if (file) {
        const fileURL = URL.createObjectURL(file); // Crear un objeto URL para el archivo

        $textVigencia.textContent = file.name; // Cambiar el texto del <a>
        $textVigencia.href = fileURL; // Establecer el enlace al archivo
        $textVigencia.target = "_blank"; // Abrir en una nueva pestaña

        //console.log(fileURL); // Verificar el objeto URL generado
    }
});

//C. Change de Efirma
$efirma.addEventListener("change", (event) => {
    const file = event.target.files[0]; // Obtener el archivo seleccionado

    const reader = new FileReader();

    // Cargar el archivo y mostrar la imagen
    reader.onload = function (e) {
        $imgEfirma.src = e.target.result; // Establecer la imagen cargada
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
    const vigencia = document.getElementById("doc_vigencia").files[0];
    const firma = document.getElementById("efirma").files[0];

    if (perfil) formData.append("perfil", perfil);
    if (vigencia) formData.append("vigencia", vigencia);
    if (firma) formData.append("firma", firma);

    try {
        const res = await fetch(`${url_usuarios_modificar_usuario}${id_user}`, {
          method: "POST",
          body: formData,
        });
        const data = await res.json();

        console.log(data);

        alert(data.message);
        

    } catch (error) {
        alert(`Error en la petición: ${error}`);
    }
});

//E. Obtener datos de perfil
fetchAndGetUser($form, `${url_usuarios_obtener_info_usuario}${id_user}`);

// F. Invalidar usuario
$btnValidar.addEventListener("click", async () => {
    try {
        const URL = `${url_usuarios_validar_usuario}${id_user}`;
        const data = await funcionFetch(URL);

        console.log(data);

        if (data.ok) {
            const mensaje = data.nuevo_estado == 1 
                ? "Usuario activado correctamente." 
                : "Usuario desactivado correctamente.";
            alert(mensaje);
        } else {
            alert(data.message || "Error al actualizar el usuario.");
        }

    } catch (error) {
        alert("Ocurrió un error, inténtelo más tarde");
        console.error("Error:", error);
    }
});

// G. Eliminar usuario
$btnEliminar.addEventListener("click", async () => {
    const confirmar = confirm("¿Estás seguro de que deseas eliminar este usuario?");

    if (!confirmar) {
        return; // Si el usuario presiona "Cancelar", no se elimina
    }

    try {
        const URL = `${url_usurios_eliminar_usuario}${id_user}`;
        const data = await funcionFetch(URL);

        console.log(data);

        if (data.ok) {
            // Mostrar mensaje de éxito al eliminar el usuario
            alert("Usuario eliminado correctamente.");
            window.location.href = usuarios;
        } else {
            // Mostrar mensaje de error si no se pudo eliminar
            alert(data.message || "Error al eliminar el usuario.");
        }

    } catch (error) {
        // Mostrar mensaje de error general
        alert("Ocurrió un error, inténtelo más tarde.");
        console.error("Error:", error);
    }
});

