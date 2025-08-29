import {showModal} from "./functions/functions.js";

const $formAuditoria = document.getElementById("form-auditoria");

const $btnParticipantesActividad = document.getElementById("btnParticipantesActividad");
const $btnAuditores = document.getElementById("btnAuditores");
const $btnContactosActividad = document.getElementById("btnContactosActividad");

const $btnAgregarActividad = document.getElementById("btnAgregarActividad");

const $tablaActividades = document.getElementById("tabla-actividades");

const $btnMejoras = document.getElementById("btnMejoras");
const $btnComentarios = document.getElementById("btnComentarios");
const $btnNoconformidades = document.getElementById("btnNoconformidades");
const $btnConclusiones = document.getElementById("btnConclusiones");

const $btnParticipantes = document.getElementById("btnParticipantes");

const $btnModificarParticipantesActividad = document.getElementById(
  "btnModificarParticipantesActividad"
);
const $btnModificarContactosActividad = document.getElementById(
  "btnModificarContactosActividad"
);

const $btnUsuarios = document.getElementById("btnUsuarios");

$formAuditoria.addEventListener("submit", (e) => {
  e.preventDefault();

  console.log("Enviando auditoría");

  const auditoria = {
    // Institutos
    institutoNorte: estaCheck("institutoNorte"),
    institutoPoniente: estaCheck("institutoPoniente"),

    // Información general
    objetivo: document.getElementById("objetivo").value,
    alcance: document.getElementById("alcance").value,
    numero: document.getElementById("numero").value,
    proceso: document.getElementById("carrera").value,
    lider: document.getElementById("lider").value,
    lider2: document.getElementById("lider2").value,
    lider3: document.getElementById("lider3").value,

    recibe: document.getElementById("recibe").value,

    // Grupo auditor
    grupoAuditor: obtenerInfoTarjetas(document.getElementById("divAuditores")),

    // Reunión de apertura
    apertura: {
      fechaInicio: document.getElementById("inicioApertura").value,
      fechaFin: document.getElementById("finApertura").value,
      area: document.getElementById("areaApertura").value,
    },

    // Reunión de cierre
    cierre: {
      fechaInicio: document.getElementById("inicioCierre").value,
      fechaFin: document.getElementById("finCierre").value,
      area: document.getElementById("areaCierre").value,
    },

    // Entrega de evidencias
    fechaEntregaEvidencia: document.getElementById("entregaEvidencia").value,

    // Actividades
    actividades: obtenerDatosDeTabla("tabla-actividades"),

    // Personal contactado
    personalContactado: obtenerInfoTarjetas(
      document.getElementById("divParticipantes")
    ),

    // Tablas adicionales
    mejoras: obtenerDatosDeTabla("tabla-mejoras"),
    comentarios: obtenerDatosDeTabla("tabla-comentarios"),
    noConformidades: obtenerDatosDeTabla("tabla-noconformidades"),
    conclusiones: obtenerDatosDeTabla("tabla-conclusiones"),

    // Acceso a usuarios
    accesoUsuarios: obtenerInfoTarjetas(document.getElementById("divUsuarios")),
  };

  console.log(auditoria);

  // Crear el FormData compatible con PHP
  const formData = new FormData();

  // Campos simples
  formData.append("proceso", auditoria.proceso);
  formData.append("objetivo", auditoria.objetivo);
  formData.append("alcance", auditoria.alcance);
  formData.append("numero", auditoria.numero);
  formData.append("inicioApertura", auditoria.apertura.fechaInicio);
  formData.append("finApertura", auditoria.apertura.fechaFin);
  formData.append("areaApertura", auditoria.apertura.area);
  formData.append("inicioCierre", auditoria.cierre.fechaInicio);
  formData.append("finCierre", auditoria.cierre.fechaFin);
  formData.append("areaCierre", auditoria.cierre.area);
  formData.append("entregaEvidencia", auditoria.fechaEntregaEvidencia);

  // El backend espera el id del líder
  formData.append("idAuditorLider", auditoria.lider);
  formData.append("idAuditorLider", auditoria.lider2);
  formData.append("idAuditorLider", auditoria.lider3);
  formData.append("idUsuarioRecibe", auditoria.recibe);

  // Booleanos
  formData.append("institutoNorte", auditoria.institutoNorte);
  formData.append("institutoPoniente", auditoria.institutoPoniente);

  // Arrays como JSON
  const usuarios = auditoria.accesoUsuarios; // Asegúrate de que `id` exista
  const participantes = auditoria.grupoAuditor;
  const mejoras = auditoria.mejoras;
  const comentarios = auditoria.comentarios;
  const noConformidades = auditoria.noConformidades;
  const conclusiones = auditoria.conclusiones;
  const personalContactado = auditoria.personalContactado;

  formData.append("usuarios", JSON.stringify(usuarios));
  formData.append("participantes", JSON.stringify(participantes));
  formData.append("personalContactado", JSON.stringify(personalContactado));
  formData.append("mejoras", JSON.stringify(mejoras));
  formData.append("comentarios", JSON.stringify(comentarios));
  formData.append("noConformidades", JSON.stringify(noConformidades));
  formData.append("conclusiones", JSON.stringify(conclusiones));

  fetch(
    "http://localhost/residencia/server/php/procesos/create-auditoria.php",
    {
      method: "POST",
      body: formData,
    }
  )
    .then((res) => res.json())
    .then((data) => {
      console.log(data);
      console.log(data.data.idProceso);
      console.log(data.data.idAuditoria);
      // Verifica que data.status sea 'success'
      if (data.status === "success") {
        showModal(true, "Auditoría registrada exitosamente");

        const idAuditoria = data.data.idAuditoria;

        // Iterar sobre cada actividad
        auditoria.actividades.forEach((actividad) => {
          const formDataActividad = new FormData();

          formDataActividad.append("idAuditoria", idAuditoria);
          formDataActividad.append("fechaInicio", actividad.inicio); // Ajusta según el nombre real
          formDataActividad.append("fechaFinal", actividad.fin);
          formDataActividad.append("tipoProceso", actividad.tipoProceso || "");
          formDataActividad.append("actividad", actividad.texto || "");
          formDataActividad.append(
            "requisito",
            actividad.requisitoCriterio || ""
          );
          formDataActividad.append("area", actividad.areaSitio || "");

          // Convertir los arrays a JSON si se usarán
          formDataActividad.append(
            "contactos",
            JSON.stringify(actividad.contactos || [])
          );
          formDataActividad.append(
            "participantes",
            JSON.stringify(actividad.participantes || [])
          );

          // Realizar el fetch por cada actividad
          fetch(
            "http://localhost/residencia/server/php/procesos/create-actividad.php",
            {
              method: "POST",
              body: formDataActividad,
            }
          )
            .then((res) => res.json())
            .then((respuesta) => {
              console.log("Respuesta de actividad:", respuesta);
              // Puedes mostrar un mensaje o manejar errores individuales aquí
            })
            .catch((error) => {
              console.error("Error al insertar actividad:", error);
            });
        });
      } else {
        alert("Error: " + data.message);
      }
    })
    .catch((err) => {
      console.error("Error en la solicitud:", err);
      alert("Ocurrió un error al enviar la auditoría.");
    });
});

function estaCheck(id) {
  const checkbox = document.getElementById(id);
  return checkbox ? checkbox.checked : false;
}

//Boton de eliminar
document.addEventListener("click", function (e) {
  if (e.target.classList.contains("btn-eliminar")) {
    const fila = e.target.closest("tr");
    if (fila) fila.remove();
  }
});

//Botones para agregar tarjetas
$btnParticipantesActividad.addEventListener("click", () => {
    const $participantesActividad = document.getElementById(
      "participantesActividad"
    );
    const $divParticipantesActividad = document.getElementById(
      "divParticipantesActividad"
    );

    agregarTarjeta($participantesActividad, $divParticipantesActividad);
});

$btnAuditores.addEventListener("click", () => {
    const $auditor = document.getElementById("auditor");
    const $divAuditores = document.getElementById("divAuditores");

    agregarTarjeta($auditor, $divAuditores);
});

$btnContactosActividad.addEventListener("click", () => {
    const $contactosActividad = document.getElementById("contactosActividad");
    const $divContactosActividad = document.getElementById("divContactosActividad");

    agregarTarjeta($contactosActividad, $divContactosActividad);
});

$btnModificarParticipantesActividad.addEventListener("click", () => {
  const $modificarParticipantesActividad = document.getElementById("modificarParticipantesActividad");
  const $divModificarParticipantesActividad = document.getElementById("divModificarParticipantesActividad");

  agregarTarjeta($modificarParticipantesActividad, $divModificarParticipantesActividad);
});

$btnModificarContactosActividad.addEventListener("click", () => {
  const $modificarContactosActividad = document.getElementById(
    "modificarContactosActividad"
  );
  const $divModificarContactosActividad = document.getElementById(
    "divModificarContactosActividad"
  );

  agregarTarjeta($modificarContactosActividad, $divModificarContactosActividad);
});

$btnUsuarios.addEventListener("click", () => {
  const $usuarios = document.getElementById("usuarios");
  const $divUsuarios = document.getElementById("divUsuarios");

  agregarTarjeta($usuarios, $divUsuarios);
});

$btnParticipantes.addEventListener("click", () => {
  const $participantes = document.getElementById("participantes");
  const $divParticipantes = document.getElementById("divParticipantes");

  agregarTarjeta($participantes, $divParticipantes);
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

//Agregar actividad 
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
    inicioFormatted = new Date(datos.inicio).toLocaleString("es-MX", {
      dateStyle: "short",
      timeStyle: "short",
    });
  }

  if (datos.fin) {
    finFormatted = new Date(datos.fin).toLocaleString("es-MX", {
      dateStyle: "short",
      timeStyle: "short",
    });
  }

  // Crear fila
  const $fila = document.createElement("tr");

  // Guardar el objeto datos como string en un atributo data-info
  $fila.dataset.info = JSON.stringify(datos);

  // Crear y agregar celdas
  const $tdHorario = document.createElement("td");
  $tdHorario.textContent =
    inicioFormatted && finFormatted
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
  $btnModificar.classList.add("btn", "btn-modificar", "escalado", "btn-modificar-actividad");

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

export function obtenerInfoTarjetas($divTarjetas) {
  // Obtener todos los elementos con clase "tarjet" dentro de $divTarjetas
  const tarjets = $divTarjetas.querySelectorAll(".tarjet");

  // Crear un array para almacenar los valores
  const valores = [];

  // Recorrer cada elemento .tarjet
  tarjets.forEach((tarjet) => {
    const valor = tarjet.getAttribute("data-value");
    valores.push(valor);
  });

  // Retornar el array de valores
  return valores;
}

//Obtener los objetos data-info
export function obtenerObjetosDeTabla(tabla) {
  const resultados = [];

  // Seleccionamos todas las filas <tr> dentro de la tabla (thead + tbody)
  const filas = tabla.querySelectorAll("tr[data-info]");

  filas.forEach((tr) => {
    const dataInfo = tr.getAttribute("data-info");
    try {
      // Parseamos el JSON y lo agregamos al array
      const objeto = JSON.parse(dataInfo);
      resultados.push(objeto);
    } catch (error) {
      console.error("Error al parsear data-info en una fila:", error, dataInfo);
    }
  });

  return resultados;
}

$btnMejoras.addEventListener("click", () => {
  agregaraTabla("mejoras", "tabla-mejoras");
});

$btnComentarios.addEventListener("click", () => {
  agregaraTabla("comentarios", "tabla-comentarios");
});

$btnNoconformidades.addEventListener("click", () => {
  agregaraTablaNC(
    "noConformidad",
    "noConformidadRequisitos",
    "tabla-noconformidades"
  );
});

$btnConclusiones.addEventListener("click", () => {
  agregaraTabla("conclusion", "tabla-conclusiones");
});

function agregaraTabla(idInput, idTable) {
  // Obtener el valor del input
  const input = document.getElementById(idInput);
  const valor = input.value.trim();

  // Validar que no esté vacío
  if (valor === "") {
    alert("Por favor, ingresa un valor válido.");
    return;
  }

  // Obtener la tabla y su tbody
  const tabla = document.getElementById(idTable);
  const tbody = tabla.querySelector("tbody");

  // Crear nueva fila
  const nuevaFila = document.createElement("tr");

  // Guardar el objeto en el atributo data-info
  const infoObjeto = { texto: valor };
  nuevaFila.setAttribute("data-info", JSON.stringify(infoObjeto));

  // Crear primera celda con el texto del input
  const tdTexto = document.createElement("td");
  tdTexto.textContent = valor;

  // Crear segunda celda con los botones
  const tdOpciones = document.createElement("td");

  const btnModificar = document.createElement("button");
  btnModificar.type = "button";
  btnModificar.className = "btn btn-modificar btn-tables escalado";
  btnModificar.textContent = "Modificar";

  const btnEliminar = document.createElement("button");
  btnEliminar.type = "button";
  btnEliminar.className = "btn btn-eliminar escalado";
  btnEliminar.textContent = "Eliminar";

  // Agregar botones a la segunda celda
  tdOpciones.appendChild(btnModificar);
  tdOpciones.appendChild(btnEliminar);

  // Agregar las celdas a la fila
  nuevaFila.appendChild(tdTexto);
  nuevaFila.appendChild(tdOpciones);

  // Agregar la fila al tbody
  tbody.appendChild(nuevaFila);

  // Limpiar el input
  input.value = "";
}

function agregaraTablaNC(idInput, idInput2, idTable) {
  // Obtener el valor del input
  const input = document.getElementById(idInput);
  const input2 = document.getElementById(idInput2);

  const valor = input.value.trim();
  const valor2 = input2.value.trim();

  // Validar que no esté vacío
  if (valor === "") {
    alert("Por favor, ingresa un valor válido.");
    return;
  }

  // Obtener la tabla y su tbody
  const tabla = document.getElementById(idTable);
  const tbody = tabla.querySelector("tbody");

  // Crear nueva fila
  const nuevaFila = document.createElement("tr");

  // Guardar el objeto en el atributo data-info
  const infoObjeto = { texto: valor, requisito: valor2 };
  nuevaFila.setAttribute("data-info", JSON.stringify(infoObjeto));

  // Crear primera celda con el texto del input
  const tdTexto = document.createElement("td");
  tdTexto.textContent = valor;

  // Crear segunda celda con el texto del segundo input
  const tdTexto2 = document.createElement("td");
  tdTexto2.textContent = valor2;

  // Crear tercera celda con los botones
  const tdOpciones = document.createElement("td");

  const btnModificar = document.createElement("button");
  btnModificar.type = "button";
  btnModificar.className = "btn btn-modificar escalado noConformidad";
  btnModificar.textContent = "Modificar";

  const btnEliminar = document.createElement("button");
  btnEliminar.type = "button";
  btnEliminar.className = "btn btn-eliminar escalado";
  btnEliminar.textContent = "Eliminar";

  // Agregar botones a la tercera celda
  tdOpciones.appendChild(btnModificar);
  tdOpciones.appendChild(btnEliminar);

  // Agregar las celdas a la fila (¡aquí está lo importante!)
  nuevaFila.appendChild(tdTexto);
  nuevaFila.appendChild(tdTexto2);
  nuevaFila.appendChild(tdOpciones);

  // Agregar la fila al tbody
  tbody.appendChild(nuevaFila);

  // Limpiar los inputs
  input.value = "";
  input2.value = "";
}

// Agrega estilos al <head> solo una vez
if (!document.getElementById("estilos-modal")) {
  const style = document.createElement("style");
  style.id = "estilos-modal";
  style.textContent = `
    .modal-fondo {
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }
    .modal-contenido {
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      width: 320px;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
      font-family: Arial, sans-serif;
      animation: fadeInModal 0.3s ease;
    }
    @keyframes fadeInModal {
      from {opacity: 0; transform: translateY(-20px);}
      to {opacity: 1; transform: translateY(0);}
    }
    .modal-contenido h3 {
      margin-top: 0;
      margin-bottom: 15px;
      font-size: 1.25rem;
      color: #333;
    }
    #modal-input {
      width: 100%;
      padding: 8px;
      font-size: 1rem;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }
        #modal-input1 {
      width: 100%;
      padding: 8px;
      font-size: 1rem;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }
    #modal-input2 {
      width: 100%;
      padding: 8px;
      font-size: 1rem;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }
    .modal-botones {
      text-align: right;
    }
    .modal-botones button {
      padding: 6px 14px;
      margin-left: 8px;
      border: none;
      border-radius: 5px;
      font-size: 0.9rem;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }
    #cancelar-modal {
      background-color: #ccc;
      color: #333;
    }
    #cancelar-modal:hover {
      background-color: #b3b3b3;
    }
    #guardar-modal {
      background-color: #007bff;
      color: white;
    }
    #guardar-modal:hover {
      background-color: #0056b3;
    }
  `;
  document.head.appendChild(style);
}

document.addEventListener("click", function (e) {
  if (e.target.classList.contains("btn-tables")) {
    const fila = e.target.closest("tr");
    if (!fila) return;

    const data = JSON.parse(fila.getAttribute("data-info"));
    const valorActual = data.texto || "";

    const modalFondo = document.createElement("div");
    modalFondo.className = "modal-fondo";

    const modal = document.createElement("div");
    modal.className = "modal-contenido";

    modal.innerHTML = `
      <h3>Modificar valor</h3>
      <input type="text" id="modal-input" value="${valorActual}" />
      <div class="modal-botones">
        <button id="cancelar-modal" type="button">Cancelar</button>
        <button id="guardar-modal" type="button">Guardar</button>
      </div>
    `;

    modalFondo.appendChild(modal);
    document.body.appendChild(modalFondo);

    document.getElementById("cancelar-modal").onclick = () => {
      document.body.removeChild(modalFondo);
    };

    document.getElementById("guardar-modal").onclick = () => {
      const nuevoValor = document.getElementById("modal-input").value.trim();
      if (nuevoValor === "") {
        alert("El valor no puede estar vacío.");
        return;
      }
      fila.querySelector("td").textContent = nuevoValor;
      fila.setAttribute("data-info", JSON.stringify({ texto: nuevoValor }));
      document.body.removeChild(modalFondo);
    };
  }
});

document.addEventListener("click", function (e) {
  if (e.target.classList.contains("noConformidad")) {
    const fila = e.target.closest("tr");
    if (!fila) return;

    const data = JSON.parse(fila.getAttribute("data-info"));
    const valorActual = data.texto || "";
    const valorActual2 = data.requisito || "";

    // Crear fondo del modal
    const modalFondo = document.createElement("div");
    modalFondo.className = "modal-fondo";

    // Crear contenido del modal
    const modal = document.createElement("div");
    modal.className = "modal-contenido";

    // Establecer el contenido del modal
    modal.innerHTML = `
      <h3>Modificar valor</h3>
      <input type="text" id="modal-input1" value="${valorActual}" />
      <input type="text" id="modal-input2" value="${valorActual2}" />
      <div class="modal-botones">
        <button id="cancelar-modal2" type="button">Cancelar</button>
        <button id="guardar-modal2" type="button">Guardar</button>
      </div>
    `;

    // Agregar el modal al documento
    modalFondo.appendChild(modal);
    document.body.appendChild(modalFondo);

    // Cancelar: quitar el modal
    document.getElementById("cancelar-modal2").onclick = () => {
      document.body.removeChild(modalFondo);
    };

    // Guardar: actualizar valores
    document.getElementById("guardar-modal2").onclick = () => {
      const nuevoValor = document.getElementById("modal-input1").value.trim();
      const nuevoValor2 = document.getElementById("modal-input2").value.trim();

      if (nuevoValor === "") {
        alert("El valor no puede estar vacío.");
        return;
      }

      const celdas = fila.querySelectorAll("td");
      if (celdas.length >= 2) {
        celdas[0].textContent = nuevoValor;
        celdas[1].textContent = nuevoValor2;
      }

      fila.setAttribute(
        "data-info",
        JSON.stringify({
          texto: nuevoValor,
          requisito: nuevoValor2,
        })
      );

      document.body.removeChild(modalFondo);
    };
  }
});


let filaModificada = null; // ← Guardará la fila a modificar

document.addEventListener("click", function (e) {
  if (e.target.classList.contains("btn-modificar-actividad")) {
    const fondo = document.getElementById("modalModificarActividadFondo");
    fondo.style.display = "flex";

    const fila = e.target.parentElement.parentElement;
    filaModificada = fila; // ← Guardar la fila correspondiente

    const data = JSON.parse(fila.getAttribute("data-info"));

    document.getElementById("modificarInicioActividad").value = data.inicio;
    document.getElementById("modificarFinActividad").value = data.fin;
    document.getElementById("modificarTipoProceso").value = data.tipoProceso;
    document.getElementById("modificarActividadTexto").value = data.texto;
    document.getElementById("modificarRequisitoCriterio").value =
      data.requisitoCriterio;
    document.getElementById("modificarAreaSitioActividad").value =
      data.areaSitio;

    let objeto = data;

    // Renderizar tarjetas de participantes
    fetch(
      "http://localhost/residencia/server/php/usuarios/obtener-id-usuarios.php"
    )
      .then((response) => {
        if (!response.ok) throw new Error("Error al obtener los usuarios");
        return response.json();
      })
      .then((data) => {
        const contenedor = document.getElementById(
          "divModificarParticipantesActividad"
        );
        contenedor.innerHTML = "";

        objeto.participantes.forEach((idParticipante) => {
          const usuario = data.data.find((u) => u.idUsuario === idParticipante);
          if (usuario) {
            const $Tarjeta = document.createElement("div");
            $Tarjeta.classList.add("tarjet");
            $Tarjeta.setAttribute("data-value", usuario.idUsuario);

            const $P = document.createElement("p");
            $P.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;

            const $Boton = document.createElement("button");
            $Boton.type = "button";
            $Boton.textContent = "X";
            $Boton.classList.add("btn-closed");
            $Boton.onclick = () => $Tarjeta.remove();

            $Tarjeta.appendChild($P);
            $Tarjeta.appendChild($Boton);
            contenedor.appendChild($Tarjeta);
          }
        });
      })
      .catch((error) =>
        showModal(false, "Error en la solicitud: " + error.message)
      );

    // Renderizar tarjetas de contactos
    fetch(
      "http://localhost/residencia/server/php/contactos/obtener-id-contactos.php"
    )
      .then((response) => {
        if (!response.ok) throw new Error("Error al obtener los contactos");
        return response.json();
      })
      .then((data) => {
        const contenedor = document.getElementById(
          "divModificarContactosActividad"
        );
        contenedor.innerHTML = "";

        objeto.contactos.forEach((idContacto) => {
          const contacto = data.data.find((c) => c.idContacto === idContacto);
          if (contacto) {
            const $Tarjeta = document.createElement("div");
            $Tarjeta.classList.add("tarjet");
            $Tarjeta.setAttribute("data-value", contacto.idContacto);

            const $P = document.createElement("p");
            $P.textContent = `${contacto.nombreCompleto} ${contacto.apellidoPaterno} ${contacto.apellidoMaterno}`;

            const $Boton = document.createElement("button");
            $Boton.type = "button";
            $Boton.textContent = "X";
            $Boton.classList.add("btn-closed");
            $Boton.onclick = () => $Tarjeta.remove();

            $Tarjeta.appendChild($P);
            $Tarjeta.appendChild($Boton);
            contenedor.appendChild($Tarjeta);
          }
        });
      })
      .catch((error) =>
        showModal(false, "Error en la solicitud: " + error.message)
      );
  }
});

// ✅ Listener para el botón de guardar modificación
document
  .getElementById("btnGuardarModificarActividad")
  .addEventListener("click", function () {
    const $divModificarParticipantesActividad = document.getElementById(
      "divModificarParticipantesActividad"
    );
    const $divModificarContactosActividad = document.getElementById(
      "divModificarContactosActividad"
    );

    const datos = {
      inicio: document.getElementById("modificarInicioActividad").value,
      fin: document.getElementById("modificarFinActividad").value,
      tipoProceso: document.getElementById("modificarTipoProceso").value,
      texto: document.getElementById("modificarActividadTexto").value,
      requisitoCriterio: document.getElementById("modificarRequisitoCriterio")
        .value,
      areaSitio: document.getElementById("modificarAreaSitioActividad").value,
      participantes: obtenerInfoTarjetas($divModificarParticipantesActividad),
      contactos: obtenerInfoTarjetas($divModificarContactosActividad),
    };

    document.getElementById("modalModificarActividadFondo").style.display =
      "none";

    if (!filaModificada) return;

    filaModificada.setAttribute("data-info", JSON.stringify(datos));

    const tds = filaModificada.querySelectorAll("td");

    let inicioFormatted = datos.inicio
      ? new Date(datos.inicio).toLocaleString("es-MX", {
          dateStyle: "short",
          timeStyle: "short",
        })
      : "";
    let finFormatted = datos.fin
      ? new Date(datos.fin).toLocaleString("es-MX", {
          dateStyle: "short",
          timeStyle: "short",
        })
      : "";

    // Actualizar los td deseados
    tds[0].textContent =
      inicioFormatted && finFormatted
        ? `${inicioFormatted} a ${finFormatted}`
        : "";
    tds[1].textContent = datos.tipoProceso;
    tds[2].textContent = datos.texto;
    tds[3].textContent = datos.requisitoCriterio;

    filaModificada = null; // Limpiar referencia
  });

document
  .getElementById("btnCancelarModificarActividad")
  .addEventListener("click", function () {
    document.getElementById("modalModificarActividadFondo").style.display =
      "none";
  });

  function obtenerDatosDeTabla(idTabla) {
    const tabla = document.getElementById(idTabla);
    if (!tabla) {
      console.warn(`No se encontró la tabla con id: ${idTabla}`);
      return [];
    }

    const filas = tabla.querySelectorAll("tr[data-info]");
    const datos = [];

    filas.forEach((fila) => {
      const info = fila.getAttribute("data-info");
      try {
        const objeto = JSON.parse(info);
        datos.push(objeto);
      } catch (error) {
        console.error("Error al parsear data-info en fila:", fila, error);
      }
    });

    return datos;
  }
  