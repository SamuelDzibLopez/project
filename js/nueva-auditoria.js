import {showModal} from "./functions/functions.js";
import { obtenerInfoTarjetas } from "./submit-auditoria.js";

  // Esperar a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", function () {
    // Seleccionar todos los botones con la clase .btn-close-white
    const botonesCerrar = document.querySelectorAll(".btn-closed");

    // Agregar evento a cada botón
    botonesCerrar.forEach(function (boton) {
        boton.addEventListener("click", function () {
            const tarjeta = this.parentElement; // Captura el div padre de la tarjeta

            tarjeta.remove();
    });
});

});

//Renderizar options de usuarios
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
            const select = document.getElementById("participantesAuditoria");
            const selectTwo = document.getElementById("participantesActividad");
            const selectThree = document.getElementById("participantesAcceso");
            const selectFour = document.getElementById("participantesActividad2");
            const selectFive = document.getElementById("auditorLider");


            usuarios.forEach(usuario => {
                const texto = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
                
                // Crear y agregar opción al primer select
                const option1 = document.createElement("option");
                option1.value = usuario.idUsuario;
                option1.textContent = texto;
                select.appendChild(option1);

                // Crear y agregar opción al segundo select
                const option2 = document.createElement("option");
                option2.value = usuario.idUsuario;
                option2.textContent = texto;
                selectTwo.appendChild(option2);

                const option3 = document.createElement("option");
                option3.value = usuario.idUsuario;
                option3.textContent = texto;
                selectThree.appendChild(option3);

                const option4 = document.createElement("option");
                option4.value = usuario.idUsuario;
                option4.textContent = texto;
                selectFour.appendChild(option4);

                const option5 = document.createElement("option");
                option5.value = usuario.idUsuario;
                option5.textContent = texto;
                selectFive.appendChild(option5);
            });
        } else {
            showModal(false, `Error en la respuesta: ${data.message}`);
        }
    })
    .catch(error => {
        showModal(false, "Error en la solicitud: " + error.message);
    });

    //Renderizar options de contacto
fetch('http://localhost/residencia/server/php/contactos/obtener-id-contactos.php')
.then(response => {
    if (!response.ok) {
        throw new Error('Error al obtener los usuarios');
    }
    return response.json();
})
.then(data => {
    if (data.status === "success") {
        const contactos = data.data;

        const select = document.getElementById("contactosActividad");
        const selectTwo = document.getElementById("contactosActividad2");

        contactos.forEach(contacto => {
            const texto = `${contacto.nombreCompleto} ${contacto.apellidoPaterno} ${contacto.apellidoMaterno}`;
            
            // Crear y agregar opción al primer select
            const option1 = document.createElement("option");
            option1.value = contacto.idContacto;
            option1.textContent = texto;
            select.appendChild(option1);

            // Crear y agregar opción al primer select
            const option2 = document.createElement("option");
            option2.value = contacto.idContacto;
            option2.textContent = texto;
            selectTwo.appendChild(option2);
        });
    } else {
        showModal(false, `Error en la respuesta: ${data.message}`);
    }
})
.catch(error => {
    showModal(false, "Error en la solicitud: " + error.message);
});

//Participante de auditoria
const $btnParticipantesAuditoria = document.getElementById("btnParticipantesAuditoria");
//Participante de actividad
const $btnParticipantesActividad = document.getElementById("btnParticipantesActividad");
//Contactos de actividad
const $btnContactoActividad = document.getElementById("btnContactosActividad");
//Usuarios de acceso
const $btnUsuarios = document.getElementById("btnUsuarios");
//Boton de agregar actividad
const $btnAgregarActividad = document.getElementById("btnAgregarActividad");
//Tabla de actividades
const $tablaActividades = document.getElementById("tabla-actividades");

//Participante de actividad
const $btnParticipantesActividad2 = document.getElementById("btnParticipantesActividad2");
//Contactos de actividad
const $btnContactoActividad2 = document.getElementById("btnContactosActividad2");

$btnParticipantesAuditoria.addEventListener("click", () => {

  const $participantesAuditoria = document.getElementById("participantesAuditoria");

  const $divParticipantesAuditoria = document.getElementById("divParticipantesAuditoria");

  agregarTarjeta($participantesAuditoria, $divParticipantesAuditoria);
});

$btnParticipantesActividad.addEventListener("click", () => {
  const $participantesActividad = document.getElementById("participantesActividad");

  const $divParticipantesActividad = document.getElementById("divParticipantesActividad");

  agregarTarjeta($participantesActividad, $divParticipantesActividad);
});

$btnContactoActividad.addEventListener("click", () => {
  const $contactosActividad = document.getElementById("contactosActividad");

  const $divContactosActividad = document.getElementById("divContactosActividad");

  agregarTarjeta($contactosActividad, $divContactosActividad);
});

$btnUsuarios.addEventListener("click", () => {
  const $participantesAcceso = document.getElementById("participantesAcceso");

  const $divParticipantesAcceso = document.getElementById("divParticipantesAcceso");

  agregarTarjeta($participantesAcceso, $divParticipantesAcceso);
});

$btnContactoActividad2.addEventListener("click", () => {
  const $contactosActividad2 = document.getElementById("contactosActividad2");

  const $divContactosActividad2 = document.getElementById("divContactosActividad2");

  agregarTarjeta($contactosActividad2, $divContactosActividad2);
});

$btnParticipantesActividad2.addEventListener("click", () => {
  const $participantesActividad2 = document.getElementById("participantesActividad2");

  const $divParticipantesActividad2 = document.getElementById("divParticipantesActividad2");

  agregarTarjeta($participantesActividad2, $divParticipantesActividad2);
});

//Agregar tarjeta en respectivo div
function agregarTarjeta($Select, $DivTarjet) {
  // Obtener el valor
  const valorSeleccionado = $Select.value;

  // Validar que se haya seleccionado un valor
  if (!valorSeleccionado) {
    showModal(false, "Por favor selecciona un nombre válido.");
    return;
  }

  // Obtener el texto visible
  const textoSeleccionado = $Select.options[$Select.selectedIndex].text;

  // Verificar si ya existe una tarjeta con ese valor
  if ($DivTarjet.querySelector(`.tarjet[data-value="${valorSeleccionado}"]`)) {
    showModal(false, "Este nombre ya fue agregado.");
    return;
  }

  // Crear el elemento tarjeta
  const $Tarjeta = document.createElement("div");
  $Tarjeta.classList.add("tarjet");
  $Tarjeta.setAttribute("data-value", valorSeleccionado);

  // Crear el <p> con el texto
  const $P = document.createElement("p");
  $P.textContent = textoSeleccionado;

  // Crear el botón
  const $Boton = document.createElement("button");
  $Boton.type = "button";
  $Boton.textContent = "X";
  $Boton.classList.add("btn-closed");

  // Evento para eliminar la tarjeta al hacer clic en el botón
  $Boton.addEventListener("click", function () {
    $Tarjeta.remove();
  });

  // Ensamblar la tarjeta
  $Tarjeta.appendChild($P);
  $Tarjeta.appendChild($Boton);

  // Agregar la tarjeta al contenedor
  $DivTarjet.appendChild($Tarjeta);
}

$btnAgregarActividad.addEventListener("click", (e) => {

  const $UsuariosActividad = document.getElementById("divParticipantesActividad");
  const $ContactosActividad = document.getElementById("divContactosActividad");

  const actividad = {
    inicio: document.getElementById("inicioActividad").value,
    fin: document.getElementById("finActividad").value,
    tipoProceso: document.getElementById("tipoProceso").value,
    texto: document.getElementById("actividadTexto").value,
    requisitoCriterio: document.getElementById("requisitoCriterio").value,
    participantes: obtenerInfoTarjetas($UsuariosActividad),
    contactos: obtenerInfoTarjetas($ContactosActividad),
    areaSitio: document.getElementById("areaSitioActividad").value,
    oportunidadesMejora: [],
    comentarios: [],
    noConformidades: [],
    conclusiones: [],
  };

  // Mostrar en la tabla
  agregarActividad(actividad, $tablaActividades);

  // Limpiar inputs
  document.getElementById("inicioActividad").value = "";
  document.getElementById("finActividad").value = "";
  document.getElementById("tipoProceso").value = "";
  document.getElementById("actividadTexto").value = "";
  document.getElementById("requisitoCriterio").value = "";
  document.getElementById("areaSitioActividad").value = "";

  // Limpiar participantes y contactos si aplica
  $UsuariosActividad.innerHTML = "";
  $ContactosActividad.innerHTML = "";
});


function agregarActividad(datos, $table) {
  const $tbody = $table.querySelector("tbody");

  // Verificar si las fechas no están vacías antes de formatear
  let inicioFormatted = "";
  let finFormatted = "";

  if (datos.inicio) {
    inicioFormatted = new Date(datos.inicio).toLocaleString('es-MX', {
      dateStyle: 'short',
      timeStyle: 'short'
    });
  }

  if (datos.fin) {
    finFormatted = new Date(datos.fin).toLocaleString('es-MX', {
      dateStyle: 'short',
      timeStyle: 'short'
    });
  }

  // Crear fila
  const $fila = document.createElement("tr");

  // Guardar el objeto datos como string en un atributo data-info
  $fila.dataset.info = JSON.stringify(datos);

  // Crear y agregar celdas
  const $tdHorario = document.createElement("td");
  $tdHorario.textContent = inicioFormatted && finFormatted
    ? `${inicioFormatted} a ${finFormatted}`
    : "";

  const $tdProceso = document.createElement("td");
  $tdProceso.textContent = datos.tipoProceso;

  const $tdActividad = document.createElement("td");
  $tdActividad.textContent = datos.texto;

  const $tdRequisito = document.createElement("td");
  $tdRequisito.textContent = datos.requisitoCriterio;

  const $tdOpciones = document.createElement("td");

  // Botón Modificar
  const $btnModificar = document.createElement("button");
  $btnModificar.type = "button";
  $btnModificar.textContent = "Modificar";
  $btnModificar.classList.add("btn", "btn-modificar", "escalado");

  // Botón Eliminar
  const $btnEliminar = document.createElement("button");
  $btnEliminar.type = "button";
  $btnEliminar.textContent = "Eliminar";
  $btnEliminar.classList.add("btn", "btn-eliminar", "escalado");

  // Agregar botones
  $tdOpciones.appendChild($btnModificar);
  $tdOpciones.appendChild($btnEliminar);

  // Agregar celdas a la fila
  $fila.appendChild($tdHorario);
  $fila.appendChild($tdProceso);
  $fila.appendChild($tdActividad);
  $fila.appendChild($tdRequisito);
  $fila.appendChild($tdOpciones);

  // Agregar la fila al tbody
  $tbody.appendChild($fila);
}

document.addEventListener("click", function (e) {
  if (e.target.classList.contains("btn-eliminar")) {
    const fila = e.target.closest("tr");
    if (fila) fila.remove();
  }
});









