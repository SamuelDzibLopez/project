// Importaciones

import { get_Id_Proceso } from "./functions/functions.js";
import { url_auditorias_obtener_info_auditoria } from "./urls/urls.js";

// Funcionamiento de modulo

document.addEventListener("DOMContentLoaded", async () => {
  // A. Obtener los parámetros de la URL
  let urlParams = new URLSearchParams(window.location.search);

  // Ejecutar función que retorna ID de proceso
  let id_proceso = get_Id_Proceso(urlParams);

  console.log("ID proceso:", id_proceso);

  try {
    const response = await fetch(
      `${url_auditorias_obtener_info_auditoria}${id_proceso}`,
      {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
      }
    );

    if (!response.ok) {
      throw new Error("Error en la petición: " + response.status);
    }

    const data = await response.json();
    console.log(data);

    if (data.ok) {
      await ingresarDatosAuditoria(data.data);
    } else {
      alert(data.message);
    }
  } catch (error) {
    console.error("Error al obtener datos:", error);
    alert("Ocurrió un error al consultar la auditoría.");
  }
});

// Funciones

// Esperar a que exista un elemento en el DOM
async function waitForSelector(
  selector,
  { timeout = 10000, predicate = (el) => !!el } = {}
) {
  return new Promise((resolve, reject) => {
    const start = Date.now();
    const tick = () => {
      const el = document.querySelector(selector);
      if (el && predicate(el)) return resolve(el);
      if (Date.now() - start >= timeout)
        return reject(new Error(`Timeout esperando ${selector}`));
      requestAnimationFrame(tick);
    };
    tick();
  });
}

// Esperar a que un <select> tenga la opción con el value indicado
async function waitForOption(selectEl, value, timeout = 10000) {
  const start = Date.now();
  return new Promise((resolve, reject) => {
    const tick = () => {
      const exists = Array.from(selectEl.options).some(
        (o) => String(o.value) === String(value)
      );
      if (exists) return resolve(true);
      if (Date.now() - start >= timeout)
        return reject(
          new Error(`Timeout esperando opción "${value}" en #${selectEl.id}`)
        );
      requestAnimationFrame(tick);
    };
    tick();
  });
}

// Helper para asignar valor a un <select>
async function setSelectFromData(selector, value) {
  if (value === "" || value === null || value === undefined) return;
  const sel = await waitForSelector(selector, { timeout: 10000 });
  await waitForOption(sel, value, 10000);
  sel.value = String(value);
  sel.dispatchEvent(new Event("change", { bubbles: true }));
}

async function ingresarDatosAuditoria(data) {
  console.log(data);

  // --- Folio (input text)
  try {
    const numeroInput = await waitForSelector("#numero", { timeout: 5000 });
    const folio = data?.proceso?.folio ?? data?.auditoria?.folio ?? "";
    if (folio !== "") numeroInput.value = folio;
  } catch (e) {
    console.warn(e.message);
  }

  // --- Selects de usuarios
  try {

    //Institutos
    data.institutos.forEach((inst) => {
      if (inst.idInstituto === "1") {
        document.getElementById("institutoNorte").checked = true;
      } else if (inst.idInstituto === "2") {
        document.getElementById("institutoPoniente").checked = true;
      }
    });

    // Información general
    document.getElementById("proceso").value = data.auditoria.proceso;
    document.getElementById("fecha").value = data.auditoria.fecha;
    document.getElementById("documentos").value =
      data.auditoria.documentosReferencia;
    document.getElementById("objetivo").value = data.auditoria.objetivo;
    document.getElementById("alcance").value = data.auditoria.alcance;
    document.getElementById("fechaEmision").value = data.auditoria.fechaEmision;

    const idElabora =
      data?.auditoria?.idElabora ?? data?.proceso?.idElabora ?? "";
    await setSelectFromData("#idElabora", idElabora);

    const idValida = data?.auditoria?.idValida ?? data?.proceso?.idValida ?? "";
    await setSelectFromData("#idValida", idValida);

    const idCoordinador =
      data?.auditoria?.idCoordinador ?? data?.proceso?.idCoordinador ?? "";
    await setSelectFromData("#idCoordinador", idCoordinador);

    const idRecibe = data?.auditoria?.idRecibe ?? data?.proceso?.idRecibe ?? "";
    await setSelectFromData("#idRecibe", idRecibe);

    //Reunion de apertura
    document.getElementById("cuidadApertura").value =
      data.auditoria.ciudadInicioApertura;
    document.getElementById("inicioApertura").value =
      data.auditoria.fechaInicioApertura;
    document.getElementById("finApertura").value =
      data.auditoria.fechaFinalApertura;
    document.getElementById("areaApertura").value =
      data.auditoria.lugarInicioApertura;

    //Reunion de cierre
    document.getElementById("cuidadCierre").value =
      data.auditoria.ciudadInicioCierre;
    document.getElementById("inicioCierre").value =
      data.auditoria.fechaInicioCierre;
    document.getElementById("finCierre").value =
      data.auditoria.fechaFinalCierre;
    document.getElementById("areaCierre").value =
      data.auditoria.lugarInicioCierre;

    //Evidencia
    document.getElementById("entregaEvidencia").value =
      data.auditoria.fechaEntregaEvidencia;

    //Renderizado de elementos en tabla
    renderizarArrayATabla(
      data.conclusiones,
      "tabla-conclusiones",
      "conclusion",
      "idConclusion"
    );
    renderizarArrayATabla(
      data.comentarios,
      "tabla-comentarios",
      "comentario",
      "idComentario"
    );
    renderizarArrayATabla(
      data.oportunidades,
      "tabla-mejoras",
      "oportunidad",
      "idOportunidad"
    );
    renderizarArrayNoConformidadesATabla(
      data.noConformidades,
      "tabla-noconformidades"
    );
    renderizarArrayActividadesATabla(data.actividades, "tabla-actividades");

    //Renderizado de tarjetas
    renderizarTarjetasDesdeArrayUsuarios(
      data.auditores,
      document.getElementById("divAuditores")
    );
    renderizarTarjetasDesdeArrayUsuarios(
      data.auditoresLideres,
      document.getElementById("divAuditoresLideres")
    );
    renderizarTarjetasDesdeArrayUsuarios(
      data.personalContactado,
      document.getElementById("divParticipantes")
    );
    renderizarTarjetasDesdeArrayUsuarios(
      data.usuarios,
      document.getElementById("divUsuarios")
    );

    renderizarTarjetasDesdeArrayContactos(
      data.personalContactado,
      document.getElementById("divParticipantes")
    );
  } catch (e) {
    console.warn(e.message);
  }
}

// Funciones

//Funcion para renderizar en tablas
function renderizarArrayATabla(data, idTable, atributo, idAtributo) {
  // Obtener la tabla y su tbody
  const tabla = document.getElementById(idTable);
  const tbody = tabla.querySelector("tbody");

  // Limpiar la tabla antes de renderizar
  tbody.innerHTML = "";

  // Recorrer el array y crear las filas
  data.forEach((item) => {
    const nuevaFila = document.createElement("tr");

    // Guardar todo el objeto en data-info
    nuevaFila.setAttribute("data-info", JSON.stringify(item));

    // Crear primera celda con el texto
    const tdTexto = document.createElement("td");
    tdTexto.textContent = item[atributo];

    // Crear segunda celda con los botones
    const tdOpciones = document.createElement("td");

    const btnModificar = document.createElement("button");
    btnModificar.type = "button";
    btnModificar.className = `btn btn-modificar btn-modificar-${atributo} btn-tables escalado`;
    btnModificar.textContent = "Modificar";

    const btnEliminar = document.createElement("button");
    btnEliminar.type = "button";
    btnEliminar.className = "btn btn-eliminar escalado";
    btnEliminar.textContent = "Eliminar";

    // Evento para eliminar la fila
    btnEliminar.addEventListener("click", () => {
      nuevaFila.remove();
    });

    // Agregar botones
    tdOpciones.appendChild(btnModificar);
    tdOpciones.appendChild(btnEliminar);

    // Agregar las celdas a la fila
    nuevaFila.appendChild(tdTexto);
    nuevaFila.appendChild(tdOpciones);

    // Agregar la fila al tbody
    tbody.appendChild(nuevaFila);
  });
}

//Funcion para renderizar en tabla de noConformidades
function renderizarArrayNoConformidadesATabla ( data, idTable = "tabla-noconformidades") {
  // Obtener la tabla y su tbody
  const tabla = document.getElementById(idTable);
  const tbody = tabla.querySelector("tbody");

  // Limpiar la tabla antes de renderizar
  tbody.innerHTML = "";

  // Recorrer cada no conformidad
  data.forEach((nc) => {
    const nuevaFila = document.createElement("tr");
    nuevaFila.setAttribute("data-info", JSON.stringify(nc));

    // Crear celdas con la info más importante
    const tdDescripcion = document.createElement("td");
    tdDescripcion.textContent = nc.descripcion;

    const tdRequisito = document.createElement("td");
    tdRequisito.textContent = nc.requisito;

    // Celda de opciones
    const tdOpciones = document.createElement("td");

    const btnModificar = document.createElement("button");
    btnModificar.type = "button";
    btnModificar.className = "btn btn-modificar btn-modificar-nc escalado";
    btnModificar.textContent = "Modificar";

    const btnEliminar = document.createElement("button");
    btnEliminar.type = "button";
    btnEliminar.className = "btn btn-eliminar escalado";
    btnEliminar.textContent = "Eliminar";

    btnEliminar.addEventListener("click", () => {
      nuevaFila.remove();
    });

    tdOpciones.appendChild(btnModificar);
    tdOpciones.appendChild(btnEliminar);

    // Agregar las celdas a la fila
    nuevaFila.appendChild(tdDescripcion);
    nuevaFila.appendChild(tdRequisito);
    nuevaFila.appendChild(tdOpciones);

    // Insertar la fila
    tbody.appendChild(nuevaFila);
  });
}

//Funcion para renderizar en tabla de actividades
function renderizarArrayActividadesATabla(actividades, idTabla = "tabla-actividades") {
  const tabla = document.getElementById(idTabla);
  const tbody = tabla.querySelector("tbody");

  // Limpiar la tabla antes de renderizar
  tbody.innerHTML = "";

  actividades.forEach((actividad) => {
    const {
      idActividad,
      idAuditoria,   // 👈 ahora también se guarda
      idProceso,
      horarioInicial,
      horarioFinal,
      proceso,
      actividad: nombreActividad,
      requisito,
      area,
      participantes,
      contactados
    } = actividad;

    // Extraer solo los IDs de participantes y contactados
    const participantesIds = (participantes || []).map((p) => p.idUsuario || p);
    const contactadosIds = (contactados || []).map((c) => c.idContacto || c);

    // Objeto limpio para guardar en la fila (incluyendo idAuditoria)
    const dataActividad = {
      idActividad,
      idAuditoria,    // 👈 guardado en data-info
      idProceso,
      horarioInicial,
      horarioFinal,
      proceso,
      actividad: nombreActividad,
      requisito,
      area,
      participantes: participantesIds,
      contactados: contactadosIds
    };

    // Crear nueva fila
    const tr = document.createElement("tr");

    // Guardar el objeto limpio en data-info
    tr.setAttribute("data-info", JSON.stringify(dataActividad));
    tr.setAttribute("value", idActividad);

    // Crear celdas visibles
    const tdHorario = document.createElement("td");
    tdHorario.textContent = `${horarioInicial} - ${horarioFinal}`;

    const tdProceso = document.createElement("td");
    tdProceso.textContent = proceso;

    const tdActividad = document.createElement("td");
    tdActividad.textContent = nombreActividad;

    const tdRequisito = document.createElement("td");
    tdRequisito.textContent = requisito;

    // Opciones (botones)
    const tdOpciones = document.createElement("td");

    const btnModificar = document.createElement("button");
    btnModificar.type = "button";
    btnModificar.className = "btn btn-modificar btn-modificar-actividad btn-tables escalado";
    btnModificar.textContent = "Modificar";

    const btnEliminar = document.createElement("button");
    btnEliminar.type = "button";
    btnEliminar.className = "btn btn-eliminar escalado";
    btnEliminar.textContent = "Eliminar";

    btnEliminar.addEventListener("click", () => tr.remove());

    tdOpciones.appendChild(btnModificar);
    tdOpciones.appendChild(btnEliminar);

    // Insertar celdas en la fila
    tr.appendChild(tdHorario);
    tr.appendChild(tdProceso);
    tr.appendChild(tdActividad);
    tr.appendChild(tdRequisito);
    tr.appendChild(tdOpciones);

    // Agregar la fila al tbody
    tbody.appendChild(tr);
  });
}

function renderizarTarjetasDesdeArrayUsuarios (array, divTarjetas) {
  // Limpiar el contenedor antes de renderizar
  divTarjetas.innerHTML = "";

  array.forEach((usuario) => {
    const { idUsuario, nombreCompleto, apellidoPaterno, apellidoMaterno } = usuario;

    // Verificar si ya existe una tarjeta con ese id
    if (divTarjetas.querySelector(`.tarjet[data-value="${idUsuario}"]`)) {
      return; // saltar si ya existe
    }

    // Crear tarjeta
    const tarjeta = document.createElement("div");
    tarjeta.classList.add("tarjet");
    tarjeta.setAttribute("data-value", idUsuario);

    // Crear párrafo con el nombre
    const p = document.createElement("p");
    p.textContent = `${nombreCompleto} ${apellidoPaterno} ${apellidoMaterno}`;

    // Crear botón para eliminar
    const boton = document.createElement("button");
    boton.type = "button";
    boton.textContent = "X";
    boton.classList.add("btn-closed");

    // Evento para eliminar la tarjeta
    boton.addEventListener("click", () => {
      tarjeta.remove();
    });

    // Ensamblar tarjeta
    tarjeta.appendChild(p);
    tarjeta.appendChild(boton);

    // Agregar al contenedor
    divTarjetas.appendChild(tarjeta);
  });
}

function renderizarTarjetasDesdeArrayContactos (array, divTarjetas) {
  // Limpiar el contenedor antes de renderizar
  divTarjetas.innerHTML = "";

  array.forEach((usuario) => {
    const { idContacto, nombreCompleto, apellidoPaterno, apellidoMaterno } =
      usuario;

    // Verificar si ya existe una tarjeta con ese id
    if (divTarjetas.querySelector(`.tarjet[data-value="${idContacto}"]`)) {
      return; // saltar si ya existe
    }

    // Crear tarjeta
    const tarjeta = document.createElement("div");
    tarjeta.classList.add("tarjet");
    tarjeta.setAttribute("data-value", idContacto);

    // Crear párrafo con el nombre
    const p = document.createElement("p");
    p.textContent = `${nombreCompleto} ${apellidoPaterno} ${apellidoMaterno}`;

    // Crear botón para eliminar
    const boton = document.createElement("button");
    boton.type = "button";
    boton.textContent = "X";
    boton.classList.add("btn-closed");

    // Evento para eliminar la tarjeta
    boton.addEventListener("click", () => {
      tarjeta.remove();
    });

    // Ensamblar tarjeta
    tarjeta.appendChild(p);
    tarjeta.appendChild(boton);

    // Agregar al contenedor
    divTarjetas.appendChild(tarjeta);
  });
}

