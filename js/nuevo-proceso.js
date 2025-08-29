import { showModal } from "./functions/functions.js";

const $select = document.getElementById('opciones');
const $auditoriaDiv = document.getElementById('auditoria');
const $sugerenciaDiv = document.getElementById('Sujerencia');
const $PNCDiv = document.getElementById("pnc");
const $accionCorrectivaDiv = document.getElementById("accionCorrectiva");

const $formQuejas = document.getElementById("form-quejas");

function toggleDivs() {
    const value = $select.value;

    if (value === '1') { // Auditoria
        $auditoriaDiv.style.display = 'block';
        $sugerenciaDiv.style.display = 'none';
        $PNCDiv.style.display = "none";
        $accionCorrectivaDiv.style.display = "none";

    } else if (value === '2') { // Quejas y Sugerencias
        $sugerenciaDiv.style.display = 'block';
        $auditoriaDiv.style.display = 'none';
        $PNCDiv.style.display = "none";
        $accionCorrectivaDiv.style.display = "none";
    } else if (value === '3') { // Quejas y Sugerencias
        $sugerenciaDiv.style.display = 'none';
        $auditoriaDiv.style.display = 'none';
        $PNCDiv.style.display = "block";
        $accionCorrectivaDiv.style.display = "none";
    } else if (value === '4') { // Quejas y Sugerencias
        $sugerenciaDiv.style.display = 'none';
        $auditoriaDiv.style.display = 'none';
        $PNCDiv.style.display = "none";
        $accionCorrectivaDiv.style.display = "block";
    } else {
        $auditoriaDiv.style.display = 'none';
        $sugerenciaDiv.style.display = 'none';
        $PNCDiv.style.display = "none";
        $accionCorrectivaDiv.style.display = "none";
    }
}

// Ejecutar una vez al cargar la página
toggleDivs();

    // Ejecutar cada vez que se cambia la opción
$select.addEventListener('change', toggleDivs);

$formQuejas.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = {
        tipo_proceso: 'Queja', // o puedes crear un input oculto si es dinámico
        fecha: document.getElementById('fecha').value,
        folio: document.getElementById('folio').value,
        nombre: document.getElementById('nombre').value,
        correo: document.getElementById('correo').value,
        telefono: document.getElementById('telefono').value,
        matricula: document.getElementById('matricula').value,
        carrera: document.getElementById('carrera').value,
        semestre: document.getElementById('semestre').value,
        grupo: document.getElementById('grupo').value,
        turno: document.getElementById('turno').value,
        aula: document.getElementById('aula').value,
        queja: document.getElementById('queja').value,
        respuesta: document.getElementById('respuesta').value,
        id_subdirector: document.getElementById('subdirector').value,
    };

    try {
        const response = await fetch('http://localhost/residencia/server/php/procesos/create-queja.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(formData)
        });

        const result = await response.json();

        if (result.ok || result.status === 'success') {
            showModal(true, 'Queja registrada con éxito.');
        document.getElementById('form-quejas').reset();
        } else {
            showModal(false, `Error al registrar: ${result.message}`);
        }
    } catch (error) {
        console.error(error);
            showModal(false, 'Error al enviar los datos.');
    }
});

fetch('http://localhost/residencia/server/php/usuarios/obtener-id-usuarios.php')
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al obtener los usuarios');
        }
        return response.json();
    })
    .then(data => {
        if (data.status === "success") {
            const usuarios = data.data;
            const select = document.getElementById("subdirector");
            const auditor = document.getElementById("auditor");
            const auditor2 = document.getElementById("lider2");
            const auditor3 = document.getElementById("lider3");

            const participantesActividad = document.getElementById("participantesActividad");
            const lider = document.getElementById("lider");
            const recibe = document.getElementById("recibe");
            const modificarParticipante = document.getElementById("modificarParticipantesActividad");
            const divusuarios = document.getElementById("usuarios");
            const pncElabora = document.getElementById("pnc-elabora");
            const pncValida = document.getElementById("pnc-valida");
            const pncCoordinador = document.getElementById("pnc-coordinador");
            const pncVerifica = document.getElementById("pnc-verifica");
            const pncLibera = document.getElementById("pnc-libera");
            const pncUsuarios = document.getElementById("pnc-usuarios");
            const modificarPncVerifica = document.getElementById("modificar-pnc-verifica");
            const modificarPncLibera = document.getElementById("modificar-pnc-libera");

            const aCPersonalDefine = document.getElementById("ac-personal-define");
            const aCPersonaVerifica = document.getElementById("ac-persona-verifica");
            const aCCoordinadorGeneral = document.getElementById("ac-coordinador-general");
            const aCResponsableSelect = document.getElementById("ac-responsable-select");
            const aCSelectResponsableAccion = document.getElementById("ac-select-responsable-accion");
            const aCUsuarios = document.getElementById("ac-usuarios");
            const modificarCorreccion = document.getElementById("ac-modificar-responsable-select");
            const modificarAccion = document.getElementById("ac-modificar-responsable-accion");

        usuarios.forEach((usuario) => {
            const option = document.createElement("option");
            option.value = usuario.idUsuario;
            option.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            select.appendChild(option);

            const option2 = document.createElement("option");
            option2.value = usuario.idUsuario;
            option2.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            auditor.appendChild(option2);

            const option3 = document.createElement("option");
            option3.value = usuario.idUsuario;
            option3.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            participantesActividad.appendChild(option3);

            const option5 = document.createElement("option");
            option5.value = usuario.idUsuario;
            option5.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            lider.appendChild(option5);

            const option6 = document.createElement("option");
            option6.value = usuario.idUsuario;
            option6.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            recibe.appendChild(option6);

            const option7 = document.createElement("option");
            option7.value = usuario.idUsuario;
            option7.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            modificarParticipante.appendChild(option7);

            const option8 = document.createElement("option");
            option8.value = usuario.idUsuario;
            option8.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            divusuarios.appendChild(option8);

            const option9 = document.createElement("option");
            option9.value = usuario.idUsuario;
            option9.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            pncElabora.appendChild(option9);

            const option10 = document.createElement("option");
            option10.value = usuario.idUsuario;
            option10.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            pncValida.appendChild(option10);

            const option11 = document.createElement("option");
            option11.value = usuario.idUsuario;
            option11.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            pncCoordinador.appendChild(option11);

            const option12 = document.createElement("option");
            option12.value = usuario.idUsuario;
            option12.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            pncVerifica.appendChild(option12);

            const option13 = document.createElement("option");
            option13.value = usuario.idUsuario;
            option13.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            pncLibera.appendChild(option13);

            const option14 = document.createElement("option");
            option14.value = usuario.idUsuario;
            option14.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            pncUsuarios.appendChild(option14);

            const option15 = document.createElement("option");
            option15.value = usuario.idUsuario;
            option15.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            modificarPncVerifica.appendChild(option15);

            const option16 = document.createElement("option");
            option16.value = usuario.idUsuario;
            option16.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            modificarPncLibera.appendChild(option16);

            const option17 = document.createElement("option");
            option17.value = usuario.idUsuario;
            option17.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            aCPersonalDefine.appendChild(option17);

            const option18 = document.createElement("option");
            option18.value = usuario.idUsuario;
            option18.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            aCPersonaVerifica.appendChild(option18);

            const option19 = document.createElement("option");
            option19.value = usuario.idUsuario;
            option19.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            aCCoordinadorGeneral.appendChild(option19);
            
            const option20 = document.createElement("option");
            option20.value = usuario.idUsuario;
            option20.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            aCResponsableSelect.appendChild(option20);

            const option21 = document.createElement("option");
            option21.value = usuario.idUsuario;
            option21.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            aCSelectResponsableAccion.appendChild(option21);

            const option22 = document.createElement("option");
            option22.value = usuario.idUsuario;
            option22.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            aCUsuarios.appendChild(option22);

            const option23 = document.createElement("option");
            option23.value = usuario.idUsuario;
            option23.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            modificarCorreccion.appendChild(option23);

            const option24 = document.createElement("option");
            option24.value = usuario.idUsuario;
            option24.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            modificarAccion.appendChild(option24);

            const option25 = document.createElement("option");
            option25.value = usuario.idUsuario;
            option25.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            auditor2.appendChild(option25);
            
            const option26 = document.createElement("option");
            option26.value = usuario.idUsuario;
            option26.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
            auditor3.appendChild(option26);
        });
        } else {
            showModal(false, `Error en la respuesta: ${data.message}`);
        }
    })
    .catch(error => {
        showModal(false, "Error en la solicitud:");
    });

fetch("http://localhost/residencia/server/php/contactos/obtener-id-contactos.php")
    .then((response) => {
        if (!response.ok) {
            throw new Error("Error al obtener los usuarios");
        }
        return response.json();
    })
    .then((data) => {
        if (data.status === "success") {
            const usuarios = data.data;
            const select = document.getElementById("contactosActividad");
            const modificarContactosActividad = document.getElementById("modificarContactosActividad");
            const participantes = document.getElementById("participantes");

            usuarios.forEach((usuario) => {
                const option = document.createElement("option");
                option.value = usuario.idContacto;
                option.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
                select.appendChild(option);

                const option2 = document.createElement("option");
                option2.value = usuario.idContacto;
                option2.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
                modificarContactosActividad.appendChild(option2);

                const option3 = document.createElement("option");
                option3.value = usuario.idContacto;
                option3.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
                participantes.appendChild(option2);
            });
        } else {
            showModal(false, `Error en la respuesta: ${data.message}`);
        }
    })
    .catch((error) => {
        showModal(false, "Error en la solicitud:");
    });