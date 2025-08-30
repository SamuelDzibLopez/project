//A. Importación de modulos
import { url_auditorias_create_auditoria, url_usuarios_obtener_id_usuarios, url_contactos_obtener_id_contactos } from "./urls/urls";

//B. Definicion de elementos

//1. Formulario de auditoria
const $formAuditoria = document.getElementById("auditoria");
//2. Array de selects de usuarios
const idSelectsUsuarios = ["idElabora", "idValida", "idCoordinador", "idRecibe", "auditoresLideres", "auditor", "participantesActividad", "noConformidadIdVerifica", "noConformidadIdLibera", "usuarios"];
//3. Array de selects de contactos
const idSelectsContactos = ["contactosActividad", "participantes"];
//4. Boton de auditores lideres
const $btnAuditoresLideres = document.getElementById("btnAuditoresLideres");
//5. Boton de auditores
const $btnAuditores = document.getElementById("btnAuditores");
//6. Boton de participantes de actividad
const $btnParticipantesActividad = document.getElementById("btnParticipantesActividad");
//7. Boton de participantes de actividad
const $btnContactosActividad = document.getElementById("btnContactosActividad");
//8. Boton de personal contactado
const $btnParticipantes = document.getElementById("btnParticipantes");
//9. Boton de usuarios
const $btnUsuarios = document.getElementById("btnUsuarios");

//10. Boton de oportunidad de mejora
const $btnMejoras = document.getElementById("btnMejoras");
//11. Boton de comentarios
const $btnComentarios = document.getElementById("btnComentarios");
//12.Boton de no conformidades
const $btnNoconformidades = document.getElementById("btnNoconformidades");
//13. Boton de conclusiones
const $btnConclusiones = document.getElementById("btnConclusiones");
//14. Boton de actividad
const $btnAgregarActividad = document.getElementById("btnAgregarActividad");

//C. Funcionamiento de pagina

//1. Llenar selects de usuarios
await cargarUsuariosEnSelects(idSelectsUsuarios, url_usuarios_obtener_id_usuarios);

//2. Llenar selects de contactos
await cargarContactosEnSelects(idSelectsContactos, url_contactos_obtener_id_contactos);

//3. Funcionamiento de agregado de auditores lideres
$btnAuditoresLideres.addEventListener("click", () => {
  const $auditoresLideres = document.getElementById("auditoresLideres");
  const $divAuditoresLideres = document.getElementById("divAuditoresLideres");

  agregarTarjeta($auditoresLideres, $divAuditoresLideres);
});

//4. Funcionamiento de agregado de auditores
$btnAuditores.addEventListener("click", () => {
  const $auditor = document.getElementById("auditor");
  const $divAuditores = document.getElementById("divAuditores");

  agregarTarjeta($auditor, $divAuditores);
});

//5. Funcionamiento de agregado de participantes de actividad
$btnParticipantesActividad.addEventListener("click", () => {
  const $participantesActividad = document.getElementById("participantesActividad");
  const $divParticipantesActividad = document.getElementById("divParticipantesActividad");

  agregarTarjeta($participantesActividad, $divParticipantesActividad);
});

//6. Funcionamiento de agregado de contactos de actividad
$btnContactosActividad.addEventListener("click", () => {
  const $contactosActividad = document.getElementById("contactosActividad");
  const $divContactosActividad = document.getElementById("divContactosActividad");

  agregarTarjeta($contactosActividad, $divContactosActividad);
});

//7. Funcionamiento de agregado de personal contactado
$btnParticipantes.addEventListener("click", () => {
  const $participantes = document.getElementById("participantes");
  const $divParticipantes = document.getElementById("divParticipantes");

  agregarTarjeta($participantes, $divParticipantes);
});

//8. Funcionamiento de agregado de usuarios
$btnUsuarios.addEventListener("click", () => {
  const $usuarios = document.getElementById("usuarios");
  const $divUsuarios = document.getElementById("divUsuarios");

  agregarTarjeta($usuarios, $divUsuarios);
});

//9. Funcionamiento de agregar oportunidad de mejora
$btnMejoras.addEventListener("click", () => {
  agregaraTabla("mejoras", "tabla-mejoras", "oportunidad", "idOportunidad");
});

//10. Funcionamiento de agregar comentarios
$btnComentarios.addEventListener("click", () => {
  agregaraTabla("comentarios", "tabla-comentarios", "comentario", "idComentario");
});

//11. Funcionamiento de agregar no conformidades
$btnNoconformidades.addEventListener("click", () => {

    // Obtener valores de inputs
    const descripcion = document.getElementById("noConformidad").value.trim();
    const requisito = document.getElementById("noConformidadRequisitos").value.trim();
    const folio = document.getElementById("noConformidadFolio").value.trim();
    const fecha = document.getElementById("noConformidadFecha").value.trim();
    const accion = document.getElementById("noConformidadAccion").value.trim();
    const numRAC = document.getElementById("noConformidadNumRAC").value.trim();

    // Radio seleccionado (Eliminar o No Eliminar)
    const estadoRadio = document.querySelector('input[name="estadoEliminar"]:checked');
    // Si eliges convertirlo en número (ej. 1 = Eliminar, 0 = No Eliminar):
    const estado = (estadoRadio && estadoRadio.value === "eliminar") ? 1 : 0;

    // Selects de verifica y libera
    const idVerifica = document.getElementById("noConformidadIdVerifica").value || null;
    const idLibera = document.getElementById("noConformidadIdLibera").value || null;

    // Armar objeto con los datos
    const noConformidad = {
        descripcion: descripcion,
        requisito: requisito,
        folio: folio,
        fecha: fecha,
        accion: accion,
        numRAC: numRAC,
        estado: estado,       // 1 = eliminar, 0 = no eliminar
        idVerifica: idVerifica ? parseInt(idVerifica) : null,
        idLibera: idLibera ? parseInt(idLibera) : null
    };

    console.log(noConformidad);

    agregarNoConformidadATabla(noConformidad);
});

//12. Funcionamiento de agregar conclusiones
$btnConclusiones.addEventListener("click", () => {
  agregaraTabla("conclusion", "tabla-conclusiones", "conclusion", "idConclusion");
});

//13. Funcionamiento de agregar actividad
$btnAgregarActividad.addEventListener("click", () => {

  // Obtener valores de los inputs
  const horarioInicial = document.getElementById("inicioActividad").value;
  const horarioFinal = document.getElementById("finActividad").value;
  const proceso = document.getElementById("tipoProceso").value;
  const actividad = document.getElementById("actividadTexto").value;
  const requisito = document.getElementById("requisitoCriterio").value;
  const area = document.getElementById("areaSitioActividad").value;

  // Participantes seleccionados
  const participantes = obtenerInfoTarjetas(
    document.getElementById("divParticipantesActividad")
  );

  // Contactos seleccionados
  const contactados = obtenerInfoTarjetas(
    document.getElementById("divContactosActividad")
  );

  // Crear objeto con todos los datos
  const dataActividad = {
    horarioInicial,
    horarioFinal,
    proceso,
    actividad,
    requisito,
    participantes,
    contactados,
    area,
  };

  console.log(dataActividad);

  agregarActividadATabla(dataActividad);
});


//Submit de creacion de auditoria
$formAuditoria.addEventListener("submit", async (e) => {
  console.log("Creando auditoría");

  e.preventDefault();

  const data = {
    tipoProceso: "Auditoría",
    folioProceso: "2025-001",
    estadoProceso: 1,
    auditoriaData: {
      numAuditoria: 1001,
      proceso: "Proceso de Auditoría 2025",
      fecha: "2025-07-01",
      documentosReferencia: "Documento X, Documento Y",
      objetivo: "Verificar cumplimiento",
      alcance: "Área administrativa",
      fechaEmision: "2025-07-02",
      ciudadInicioApertura: "Mérida",
      fechaInicioApertura: "2025-07-01 09:00:00",
      lugarInicioApertura: "Sala 101",
      fechaFinalApertura: "2025-07-01 12:00:00",
      ciudadInicioCierre: "Mérida",
      fechaInicioCierre: "2025-07-02 09:00:00",
      lugarInicioCierre: "Sala 102",
      fechaFinalCierre: "2025-07-02 12:00:00",
      fechaEntregaEvidencia: "2025-07-03",
      idElabora: 1,
      idValida: 2,
      idCoordinador: 3,
      idRecibe: 4,
    },
    usuariosProceso: [1, 2, 3],
    actividades: [
      {
        horarioInicial: "2025-07-01 09:00:00",
        horarioFinal: "2025-07-01 12:00:00",
        proceso: "Académico",
        actividad: "Actividad 1",
        requisito: "1.1",
        area: "Sala 1",
        participantes: [1, 2],
        contactados: [1, 2],
      },
    ],
    institutos: [1, 2],
    personalContactado: [1, 2, 3, 4],
    auditores: [1, 2, 3, 4],
    auditoresLideres: [1, 2],
    oportunidades: [{ oportunidad: "Mejorar proceso de documentación 1" }],
    comentarios: [{ comentario: "Comentario inicial sobre auditoría 1" }],
    conclusiones: [{ conclusion: "Conclusión de auditoría satisfactoria 1" }],
    noConformidades: [
      {
        descripcion: "Falta de control en documentación 1",
        requisito: "ISO 9001:2015",
        folio: "2025-001/01",
        fecha: "2025-07-02",
        accion: "",
        numRAC: "RAC-001/01",
        estado: 1,
        idVerifica: 2,
        idLibera: 3,
      },
    ],
  };

  try {
    const response = await fetch(url_auditorias_create_auditoria, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });

    if (!response.ok) {
      throw new Error(`Error HTTP: ${response.status}`);
    }

    const result = await response.json();
    console.log("Respuesta del servidor:", result);
    alert(result.message);
  } catch (error) {
    console.error("Error al crear la auditoría:", error);
    alert(result.message);
  }
});

//D. Funciones

//Función para poblar varios selects con usuarios
async function cargarUsuariosEnSelects(idsSelects, url) {
    try {
      const response = await fetch(url);
      const { status, data, message } = await response.json();

      if (status !== "success" || !Array.isArray(data)) {
        console.error("Error al obtener usuarios:", message);
        return;
      }

      idsSelects.forEach((id) => {
        const select = document.getElementById(id);
        if (!select) return;

        // Limpiar y agregar placeholder
        select.innerHTML = `<option value="" disabled selected>Seleccione un usuario</option>`;

        // Insertar usuarios
        data.forEach(
          ({ idUsuario, nombreCompleto, apellidoPaterno, apellidoMaterno }) => {
            const option = document.createElement("option");
            option.value = idUsuario;
            option.textContent = `${nombreCompleto} ${apellidoPaterno} ${apellidoMaterno}`;
            select.appendChild(option);
          }
        );
      });
    } catch (error) {
      console.error("Error al cargar usuarios:", error);
    }
};

//Función para poblar varios selects con contactos
async function cargarContactosEnSelects(idsSelects, url) {
    try {
      const response = await fetch(url);
      const { status, data, message } = await response.json();

      if (status !== "success" || !Array.isArray(data)) {
        console.error("Error al obtener contactos:", message);
        return;
      }

      idsSelects.forEach(id => {
        const select = document.getElementById(id);
        if (!select) return;

        // Limpiar y agregar placeholder
        select.innerHTML = `<option value="" disabled selected>Seleccione un contacto</option>`;

        // Insertar contactos
        data.forEach(({ idContacto, nombreCompleto, apellidoPaterno, apellidoMaterno }) => {
          const option = document.createElement("option");
          option.value = idContacto;
          option.textContent = `${nombreCompleto} ${apellidoPaterno} ${apellidoMaterno}`;
          select.appendChild(option);
        });
      });
    } catch (error) {
      console.error("Error al cargar contactos:", error);
    }
}

//Funcion para agregar tarjetas
function agregarTarjeta($Select, $DivTarjet) {
  // Obtener el valor
  const valorSeleccionado = $Select.value;

  // Validar que se haya seleccionado un valor
  if (!valorSeleccionado) {
    alert("Por favor selecciona un nombre válido.");
    return;
  }

  // Obtener el texto visible
  const textoSeleccionado = $Select.options[$Select.selectedIndex].text;

  // Verificar si ya existe una tarjeta con ese valor
  if ($DivTarjet.querySelector(`.tarjet[data-value="${valorSeleccionado}"]`)) {
    alert("Este nombre ya fue agregado.");
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

//Funcion para agregar a tabla de a 1 valor
function agregaraTabla(idInput, idTable, atributo, idAtributo) {
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

  // Guardar el objeto en el atributo data-info con el nombre dinámico
  const infoObjeto = {
    [idAtributo]: "",
    [atributo]: valor
  };
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

  // Evento para eliminar la fila
  btnEliminar.addEventListener("click", () => {
    nuevaFila.remove();
  });

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

// Función para agregar una actividad a la tabla
function agregarActividadATabla(dataActividad, idTable = "tabla-actividades") {
  const { horarioInicial, horarioFinal, proceso, actividad, requisito, participantes, contactados, area } = dataActividad;

  // Validación básica (puedes ajustarla a tu necesidad)
  if (!horarioInicial || !horarioFinal || !proceso || !actividad || !requisito) {
    alert("Por favor, llena todos los campos requeridos (horario, proceso, actividad, requisito).");
    return;
  }

  // Obtener la tabla y su tbody
  const tabla = document.getElementById(idTable);
  const tbody = tabla.querySelector("tbody");

  // Crear nueva fila
  const nuevaFila = document.createElement("tr");

  // Guardar el objeto completo en data-info (para luego modificar/eliminar)
  nuevaFila.setAttribute("data-info", JSON.stringify(dataActividad));

  // Crear celdas
  const tdHorario = document.createElement("td");
  tdHorario.textContent = `${horarioInicial} - ${horarioFinal}`;

  const tdProceso = document.createElement("td");
  tdProceso.textContent = proceso;

  const tdActividad = document.createElement("td");
  tdActividad.textContent = actividad;

  const tdRequisito = document.createElement("td");
  tdRequisito.textContent = requisito;

  // Celda de opciones
  const tdOpciones = document.createElement("td");

  const btnModificar = document.createElement("button");
  btnModificar.type = "button";
  btnModificar.className = "btn btn-modificar btn-tables escalado";
  btnModificar.textContent = "Modificar";

  const btnEliminar = document.createElement("button");
  btnEliminar.type = "button";
  btnEliminar.className = "btn btn-eliminar escalado";
  btnEliminar.textContent = "Eliminar";

  // Evento para eliminar
  btnEliminar.addEventListener("click", () => {
    nuevaFila.remove();
  });

  // (Opcional) Evento para modificar: puedes cargar los datos al formulario
  btnModificar.addEventListener("click", () => {
    const info = JSON.parse(nuevaFila.getAttribute("data-info"));
    console.log("Modificar actividad:", info);
    // Aquí puedes llenar tus inputs con info.*
  });

  tdOpciones.appendChild(btnModificar);
  tdOpciones.appendChild(btnEliminar);

  // Agregar celdas a la fila
  nuevaFila.appendChild(tdHorario);
  nuevaFila.appendChild(tdProceso);
  nuevaFila.appendChild(tdActividad);
  nuevaFila.appendChild(tdRequisito);
  nuevaFila.appendChild(tdOpciones);

  // Agregar la fila al tbody
  tbody.appendChild(nuevaFila);
}

// Función para agregar una no conformidad a la tabla
function agregarNoConformidadATabla(dataNoConformidad, idTable = "tabla-noconformidades") {
  const { descripcion, requisito, folio, fecha, accion, numRAC, estado, idVerifica, idLibera } = dataNoConformidad;

  // Validación básica (ajústala a lo que sea obligatorio)
  if (!descripcion || !requisito) {
    alert("Por favor, llena al menos la descripción y el requisito.");
    return;
  }

  // Obtener la tabla y su tbody
  const tabla = document.getElementById(idTable);
  const tbody = tabla.querySelector("tbody");

  // Crear nueva fila
  const nuevaFila = document.createElement("tr");

  // Guardar el objeto completo en data-info (para luego modificar/eliminar)
  nuevaFila.setAttribute("data-info", JSON.stringify(dataNoConformidad));

  // Crear celdas
  const tdDescripcion = document.createElement("td");
  tdDescripcion.textContent = descripcion;

  const tdRequisito = document.createElement("td");
  tdRequisito.textContent = requisito;

  // Celda de opciones
  const tdOpciones = document.createElement("td");

  const btnModificar = document.createElement("button");
  btnModificar.type = "button";
  btnModificar.className = "btn btn-modificar escalado";
  btnModificar.textContent = "Modificar";

  const btnEliminar = document.createElement("button");
  btnEliminar.type = "button";
  btnEliminar.className = "btn btn-eliminar escalado";
  btnEliminar.textContent = "Eliminar";

  // Evento para eliminar
  btnEliminar.addEventListener("click", () => {
    nuevaFila.remove();
  });

  // Evento para modificar: cargar los datos en el formulario
  btnModificar.addEventListener("click", () => {
    const info = JSON.parse(nuevaFila.getAttribute("data-info"));
    console.log("Modificar no conformidad:", info);
    // Aquí llenas los inputs con info.descripcion, info.requisito, etc.
  });

  tdOpciones.appendChild(btnModificar);
  tdOpciones.appendChild(btnEliminar);

  // Agregar celdas a la fila
  nuevaFila.appendChild(tdDescripcion);
  nuevaFila.appendChild(tdRequisito);
  nuevaFila.appendChild(tdOpciones);

  // Agregar la fila al tbody
  tbody.appendChild(nuevaFila);
}

//Funcion para obtener los ids (en data-value) de un div de tarjetas
function obtenerInfoTarjetas($divTarjetas) {
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



