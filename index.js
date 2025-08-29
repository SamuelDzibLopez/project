//A. Funcion para Redirigir
import { showModal } from "./js/functions/functions";
import { url_usuarios_auth } from "./js/urls/urls";

document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("login");

    loginForm.addEventListener("submit", async function (event) {
        event.preventDefault(); // Prevenir la acción por defecto del formulario

        // Obtener los valores de usuario y contraseña
        const usuario = document.getElementById("user").value.trim();
        const contrasena = document.getElementById("password").value.trim();

        // Validar que ambos campos no estén vacíos
        if (!usuario || !contrasena) {
            alert("Por favor, ingresa ambos campos: usuario y contraseña.");
            return;
        }

        // Crear el objeto con los datos para enviar
        const loginData = {
            usuario: usuario,
            contrasena: contrasena
        };

        try {
            // Enviar la solicitud de inicio de sesión al servidor
            const response = await fetch(url_usuarios_auth, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(loginData)
            });

            const data = await response.json(); // Procesar la respuesta como JSON

            // Manejo de la respuesta
            if (data.status === "success" && data.ok) {
                // Si el login es exitoso, redirigir a la página principal o dashboard
                // showModal(true, data.message)
                // Aquí puedes redirigir al usuario a otra página
                window.location.href = "./app/dashboard.php"; // Cambia esto a la página correspondiente
            } else {
                // Si hubo un error, mostrar el mensaje
                showModal(false, data.message)
            }

        } catch (error) {
            // Manejo de errores si la solicitud falla
            alert("Hubo un error al procesar tu solicitud. Intenta nuevamente.");
            console.error(error);
        }
    });
});
