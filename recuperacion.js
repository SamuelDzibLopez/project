//A. Funcion para Redirigir
import { showModal } from "./js/functions/functions";
import { url_usuarios_validar_correo, url_usuarios_validar_codigo, url_usuarios_cambiar_contraseña } from "./js/urls/urls";

const $mail = document.getElementById("email");
const $form = document.getElementById("recuperacion");

$form.addEventListener("submit", (e) => {

    e.preventDefault();

    let correo = $mail.value;

    fetch(`${url_usuarios_validar_correo}?correoElectronico=${encodeURIComponent(correo)}`)
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if (data.ok) {
            validarCodigo();
        } else {
            showModal(false, "Usuario no encontrado, ingrese un correo valido");
        }
    })
    .catch(error => {
        showModal(true, error);
    });
});

function validarCodigo() {
    let dialog = document.createElement('dialog');

    dialog.innerHTML = `
        <h3>Validar código</h3>
        <p><b>Ingresa el código enviado a tu correo registrado</b></p>
        <form class="validar">
            <div>
                <input type="number" min="0" max="9" required />
                <input type="number" min="0" max="9" required />
                <input type="number" min="0" max="9" required />
                <input type="number" min="0" max="9" required />
            </div>
            <button type="submit">Validar</button>
            <button type="button" class="cancelarBtn">Cancelar</button>
        </form>
    `;

    document.body.appendChild(dialog);
    dialog.showModal();

    let $formCode = dialog.querySelector('form');
    let cancelarBtn = dialog.querySelector('.cancelarBtn');

    $formCode.addEventListener('submit', function (e) {
        e.preventDefault();
    
        const inputs = $formCode.querySelectorAll('input[type="number"]');
    
        const [codigoUno, codigoDos, codigoTres, codigoCuatro] = Array.from(inputs).map(input => input.value);
    
        fetch(url_usuarios_validar_codigo, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                codigoUno,
                codigoDos,
                codigoTres,
                codigoCuatro
            })
        })
        .then(res => res.json())
        .then(data => {
    
            if (data.ok) {
                dialog.remove(); // Elimina completamente el dialog del DOM
                cambiarContraseña();
            } else {
                showModal(false, data.message);
                dialog.remove(); // Elimina completamente el dialog del DOM
            }
        })
        .catch(error => {
            console.error("Error en la verificación:", error);
            dialog.remove(); // Elimina completamente el dialog del DOM
            showModal(false, "Error al verificar el código.");
        });
    });
    

    cancelarBtn.addEventListener('click', () => {
        dialog.remove(); // También lo elimina al cancelar
    });
}

function cambiarContraseña () {
    let dialog = document.createElement('dialog');

    dialog.innerHTML = `
        <h3>Cambio de clave de acceso</h3>
        <p><b>Ingresa su nueva clave de acceso:</b></p>
        <form class="passwords">
            <div>
                <input type="password" required placeholder="Nueva clave" />
                <p><b>Verifique su nueva clave de acceso:</b></p>
                <input type="password" required placeholder="Confirmar clave" />
            </div>
            <p class="error-message" style="color:red; display:none;"></p>
            <button type="submit">Cambiar</button>
            <button type="button" class="cancelarBtn">Cancelar</button>
        </form>
    `;

    document.body.appendChild(dialog);
    dialog.showModal();

    let $formPassword = dialog.querySelector('form');
    let cancelarBtn = dialog.querySelector('.cancelarBtn');
    let errorMsg = dialog.querySelector('.error-message');

    $formPassword.addEventListener('submit', function (e) {
        e.preventDefault();
    
        const inputs = $formPassword.querySelectorAll('input[type="password"]');
        const pass1 = inputs[0].value.trim();
        const pass2 = inputs[1].value.trim();
    
        const errorMsg = dialog.querySelector('.error-msg') || (() => {
            const p = document.createElement('p');
            p.className = 'error-msg';
            p.style.color = 'red';
            p.style.display = 'none';
            dialog.querySelector('form').appendChild(p);
            return p;
        })();
    
        if (pass1 !== pass2) {
            errorMsg.textContent = "Las contraseñas no coinciden.";
            errorMsg.style.display = "block";
            return;
        }
    
        errorMsg.style.display = "none";
    
        fetch(url_usuarios_cambiar_contraseña, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `nuevaContraseña=${encodeURIComponent(pass1)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                dialog.remove();
                showModal(true, "Contraseña actualizada correctamente.");
            } else {
                errorMsg.textContent = data.message || "Error al actualizar la contraseña.";
                errorMsg.style.display = "block";
            }
        })
        .catch(err => {
            console.error("Error en la petición:", err);
            errorMsg.textContent = "Error al conectar con el servidor.";
            errorMsg.style.display = "block";
        });
    });
    

    cancelarBtn.addEventListener('click', () => {
        dialog.remove(); // También lo elimina al cancelar
    });
}


