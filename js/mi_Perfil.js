//1. Importacion de modulos
import { showModal, fetchAndGetUser } from "./functions/functions.js";
import { url_usuarios_obtener_datos_mi_perfil, url_usuarios_modificar_datos_mi_perfil } from "./urls/urls.js";
//2. Funciones de pagina 

//3. Captura de elementos 

let $perfil = document.getElementById("input-perfil");
let $imgPerfil = document.getElementById("img-perfil");

let $vigencia = document.getElementById("doc_vigencia");
let $textVigencia = document.getElementById("text_vigencia");

let $efirma = document.getElementById("efirma");
let $imgEfirma = document.getElementById("img-efirma");

let $form = document.getElementById("form_datos");

//4. Asignación de eventos a botones de apartados

//X. Mostrar modal
// showModal(true, "Mensaje de prueba");

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
        const res = await fetch(url_usuarios_modificar_datos_mi_perfil, {
          method: "POST",
          body: formData,
        });
        const data = await res.json();

        // console.log(data);

        showModal(true ,data.message);
        

    } catch (error) {
        showModal(false , `Error en la petición: ${error}`);
    }
});


//E. Obtener datos de perfil
fetchAndGetUser($form, url_usuarios_obtener_datos_mi_perfil);