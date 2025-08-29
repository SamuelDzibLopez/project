//1. Importacion de modulos
import {url_usuarios_crear_usuario, usuarios} from "./urls/urls.js"

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

//D. Submit del formulario
$form.addEventListener('submit', async function (event) {
    event.preventDefault();

    const formData = new FormData(this);

    const contraseña = formData.get('contraseña');
    const confirmarContraseña = formData.get('confirmar_contraseña');

    if (contraseña !== confirmarContraseña) {
        alert("Las contraseñas no coinciden.");
        return;
    }

    try {
        const response = await fetch(url_usuarios_crear_usuario, {
            method: "POST",
            body: formData,
        });

        const text = await response.text();
        // console.log("Respuesta cruda del servidor:", text);

        let data;
        try {
            data = JSON.parse(text);
        } catch (jsonError) {
            throw new Error("La respuesta no es un JSON válido. Respuesta recibida: " + text);
        }

        if (data.ok) {
            alert('Usuario creado correctamente.');
            $form.reset();
            window.location.href = usuarios;
            // Si deseas también resetear imágenes previas, puedes hacerlo aquí.
        } else {
            alert('Hubo un error: ' + data.message);
        }
    } catch (error) {
        console.error("Error durante la petición:", error);
        alert('Error de conexión: ' + error.message);
    }
});




