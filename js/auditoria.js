import { get_Id_Proceso, showModal } from "./functions/functions.js";

// Declaramos variables globales
let contactos = [];
let usuarios = [];

const urlParams = new URLSearchParams(window.location.search);
const id_proceso = get_Id_Proceso(urlParams);

const $formAuditoria = document.getElementById("form-auditoria");

// Primero obtenemos los usuarios y contactos, luego los datos de auditoría
Promise.all([
  fetch(
    "http://localhost/residencia/server/php/usuarios/obtener-id-usuarios.php"
  ).then((response) => {
    if (!response.ok) throw new Error("Error al obtener los usuarios");
    return response.json();
  }),
  fetch(
    "http://localhost/residencia/server/php/contactos/obtener-id-contactos.php"
  ).then((response) => {
    if (!response.ok) throw new Error("Error al obtener los contactos");
    return response.json();
  }),
])
  .then(([usuariosData, contactosData]) => {
    if (usuariosData.status !== "success")
      throw new Error(usuariosData.message);
    if (contactosData.status !== "success")
      throw new Error(contactosData.message);

    usuarios = usuariosData.data;
    contactos = contactosData.data;

    const selectsUsuarios = [
      "auditor",
      "participantesActividad",
      "lider",
      "recibe",
      "modificarParticipantesActividad",
      "usuarios",
      "lider2",
      "lider3"
    ];
    selectsUsuarios.forEach((id) => {
      const select = document.getElementById(id);
      usuarios.forEach((usuario) => {
        const option = document.createElement("option");
        option.value = usuario.idUsuario;
        option.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
        select.appendChild(option);
      });
    });

    const selectsContactos = [
      "contactosActividad",
      "modificarContactosActividad",
      "participantes",
    ];
    selectsContactos.forEach((id) => {
      const select = document.getElementById(id);
      contactos.forEach((contacto) => {
        const option = document.createElement("option");
        option.value = contacto.idContacto;
        option.textContent = `${contacto.nombreCompleto} ${contacto.apellidoPaterno} ${contacto.apellidoMaterno}`;
        select.appendChild(option);
      });
    });

    return fetch(
      `http://localhost/residencia/server/php/procesos/obtener-auditoria.php?idProceso=${id_proceso}`
    );
  })
  .then((response) => {
    if (!response.ok) throw new Error("Error al obtener auditoría");
    return response.json();
  })
  .then((data) => {
    if (data.status !== "success") throw new Error(data.message);

    console.log(data.data);

    const a = data.data;

    document.getElementById("institutoNorte").checked = a.institutoNorte;
    document.getElementById("institutoPoniente").checked = a.institutoPoniente;
    document.getElementById("objetivo").value = a.objetivo;
    document.getElementById("alcance").value = a.alcance;
    document.getElementById("numero").value = a.numero;
    document.getElementById("carrera").value = a.tipoProceso;
    document.getElementById("lider").value = a.idAuditorLider;
    document.getElementById("lider2").value = a.idAuditorLider2;
    document.getElementById("lider3").value = a.idAuditorLider3;
    document.getElementById("recibe").value = a.idUsuarioRecibe;
    document.getElementById("inicioApertura").value = a.fechaInicioApertura;
    document.getElementById("finApertura").value = a.fechaFinalApertura;
    document.getElementById("areaApertura").value = a.areaApertura;
    document.getElementById("inicioCierre").value = a.fechaInicioCierre;
    document.getElementById("finCierre").value = a.fechaFinalCierre;
    document.getElementById("areaCierre").value = a.areaCierre;
    document.getElementById("entregaEvidencia").value =
      a.entregaEvidencia + "T00:00";

    a.comentarios.forEach((comentario) => {
      // console.log(comentario);
      agregaraTablaPrincipio(comentario, "tabla-comentarios");
    });

    a.mejoras.forEach((mejora) => {
      // console.log(mejora);
      agregaraTablaPrincipio(mejora, "tabla-mejoras");
    });

    a.noConformidades.forEach((noConformidad) => {
      // console.log(mejora);
      agregaraTablaPrincipioNC(noConformidad, "tabla-noconformidades");
    });

    a.conclusiones.forEach((conclusion) => {
      // console.log(mejora);
      agregaraTablaPrincipio(conclusion, "tabla-conclusiones");
    });

    const renderTarjetas = (listaIds, contenedorId) => {
      const contenedor = document.getElementById(contenedorId);
      contenedor.innerHTML = "";
      listaIds.forEach((id) => {
        const usuario = usuarios.find((u) => u.idUsuario === id);
        if (usuario) {
          const tarjeta = document.createElement("div");
          tarjeta.classList.add("tarjet");
          tarjeta.setAttribute("data-value", usuario.idUsuario);
          tarjeta.innerHTML = `<p>${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}</p><button type='button' class='btn-closed'>X</button>`;
          tarjeta.querySelector(".btn-closed").onclick = () => tarjeta.remove();
          contenedor.appendChild(tarjeta);
        }
      });
    };

    renderTarjetas(a.participantes, "divAuditores");
    renderTarjetas(a.usuarios, "divUsuarios");

    const contenedorContactos = document.getElementById("divParticipantes");
    contenedorContactos.innerHTML = "";
    a.personalContactado.forEach((idContacto) => {
      const contacto = contactos.find((c) => c.idContacto === idContacto);
      if (contacto) {
        const tarjeta = document.createElement("div");
        tarjeta.classList.add("tarjet");
        tarjeta.setAttribute("data-value", contacto.idContacto);
        tarjeta.innerHTML = `<p>${contacto.nombreCompleto} ${contacto.apellidoPaterno} ${contacto.apellidoMaterno}</p><button type='button' class='btn-closed'>X</button>`;
        tarjeta.querySelector(".btn-closed").onclick = () => tarjeta.remove();
        contenedorContactos.appendChild(tarjeta);
      }
    });

    // Aquí agregamos el fetch para obtener actividades usando el idAuditoria de 'a'
    const idAuditoria = a.idAuditoria;

    if (idAuditoria) {
      fetch(
        `http://localhost/residencia/server/php/procesos/obtener-actividades.php?idAuditoria=${encodeURIComponent(
          idAuditoria
        )}`
      )
        .then((response) => {
          if (!response.ok) throw new Error("Error al obtener actividades");
          return response.json();
        })
        .then((dataActividades) => {
          console.log(dataActividades);
          const tabla = document.getElementById("tabla-actividades");
          renderActividadesEnOrden(dataActividades.data, tabla);
        })
        .catch((error) => {
          console.error("Error al obtener actividades:", error);
        });
    } else {
      console.warn("No se encontró idAuditoria para obtener actividades.");
    }
  })
  .catch((error) => {
    console.error("Error en proceso:", error);
    showModal(false, error.message);
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
  $btnModificar.classList.add(
    "btn",
    "btn-modificar",
    "escalado",
    "btn-modificar-actividad"
  );

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

function crearFilaActividad(datos) {
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

  const $fila = document.createElement("tr");
  $fila.dataset.info = JSON.stringify(datos);

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

  const $btnModificar = document.createElement("button");
  $btnModificar.type = "button";
  $btnModificar.textContent = "Modificar";
  $btnModificar.classList.add(
    "btn",
    "btn-modificar",
    "escalado",
    "btn-modificar-actividad"
  );

  const $btnEliminar = document.createElement("button");
  $btnEliminar.type = "button";
  $btnEliminar.textContent = "Eliminar";
  $btnEliminar.classList.add("btn", "btn-eliminar", "escalado");

  $tdOpciones.appendChild($btnModificar);
  $tdOpciones.appendChild($btnEliminar);

  $fila.appendChild($tdHorario);
  $fila.appendChild($tdProceso);
  $fila.appendChild($tdActividad);
  $fila.appendChild($tdRequisito);
  $fila.appendChild($tdOpciones);

  return $fila;
}

function renderActividadesEnOrden(actividades, $tabla) {
  const $tbody = $tabla.querySelector("tbody");
  $tbody.innerHTML = ""; // Limpiar contenido actual

  // Ordenar por fecha de inicio
  actividades.sort((a, b) => new Date(a.fechaInicio) - new Date(b.fechaInicio));

  const fragment = document.createDocumentFragment();

  actividades.forEach((actividad) => {
    const fila = crearFilaActividad({
      idActividad: actividad.idActividad || "",
      inicio: actividad.fechaInicio || "",
      fin: actividad.fechaFinal || "",
      tipoProceso: actividad.tipoProceso || "",
      texto: actividad.actividad || "",
      requisitoCriterio: actividad.requisito || "",
      participantes: actividad.participantes || [],
      contactos: actividad.contactos || [],
      areaSitio: actividad.area || "",
    });
    fragment.appendChild(fila);
  });

  $tbody.appendChild(fragment);
}

function agregaraTablaPrincipio(objeto, idTable) {
  // Obtener la tabla y su tbody
  const tabla = document.getElementById(idTable);
  const tbody = tabla.querySelector("tbody");

  // Crear nueva fila
  const nuevaFila = document.createElement("tr");

  // Guardar el objeto en el atributo data-info
  nuevaFila.setAttribute("data-info", JSON.stringify(objeto));

  // Crear primera celda con el texto del input
  const tdTexto = document.createElement("td");
  tdTexto.textContent = objeto.texto;

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
}

function agregaraTablaPrincipioNC(objeto, idTable) {
  // Obtener la tabla y su tbody
  const tabla = document.getElementById(idTable);
  const tbody = tabla.querySelector("tbody");

  // Crear nueva fila
  const nuevaFila = document.createElement("tr");

  // Guardar el objeto en el atributo data-info
  nuevaFila.setAttribute("data-info", JSON.stringify(objeto));

  // Crear primera celda con el texto del input
  const tdTexto = document.createElement("td");
  tdTexto.textContent = objeto.texto;

  const tdTexto2 = document.createElement("td");
  tdTexto2.textContent = objeto.requisito;


  // Crear segunda celda con los botones
  const tdOpciones = document.createElement("td");

  const btnModificar = document.createElement("button");
  btnModificar.type = "button";
  btnModificar.className = "btn btn-modificar escalado noConformidad";
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
  nuevaFila.appendChild(tdTexto2);
  nuevaFila.appendChild(tdOpciones);

  // Agregar la fila al tbody
  tbody.appendChild(nuevaFila);
}

function estaCheck(id) {
  const checkbox = document.getElementById(id);
  return checkbox ? checkbox.checked : false;
}

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

//Modificar auditoria
$formAuditoria.addEventListener("submit", (e) => {
  e.preventDefault();

  console.log("Enviando auditoría");

  const auditoria = {
    // Institutos
    idProceso: id_proceso,
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
  formData.append("idProceso", auditoria.idProceso);
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
  formData.append("idAuditorLider2", auditoria.lider2);
  formData.append("idAuditorLider3", auditoria.lider3);

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
    "http://localhost/residencia/server/php/procesos/modificar-auditoria.php",
    {
      method: "POST",
      body: formData,
    }
  )
    .then((response) => response.json())
    .then((data) => {
      console.log("Respuesta del servidor:", data);
      if (data.status === "success") {
        showModal(true, "Auditoría modificada correctamente.");

        const idAuditoria = data.data.idAuditoria;
        //Registrar actividades
        const formData = new FormData();

        formData.append("idAuditoria", idAuditoria);
        formData.append("actividades", JSON.stringify(auditoria.actividades));

        // Enviar todas las actividades en un solo request
        fetch(
          "http://localhost/residencia/server/php/procesos/modificar-actividad.php",
          {
            method: "POST",
            body: formData,
          }
        )
          .then((res) => res.json())
          .then((respuesta) => {
            console.log("Respuesta:", respuesta);
          })
          .catch((error) => {
            console.error("Error al modificar actividades:", error);
          });
        //
      } else {
        showModal(false, "Error: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Error en la solicitud:", error);
      showModal(false, "Ocurrió un error al modificar la auditoría.");
    });
});

const $btnValidar = document.getElementById("btn-validar");

$btnValidar.addEventListener("click", (e) => {
  const idProceso = id_proceso;

  fetch(
    `http://localhost/residencia/server/php/procesos/activar-proceso.php?idProceso=${idProceso}`
  )
    .then((res) => res.json())
    .then((data) => {
      if (data.ok) {
        showModal(
          true,
          `El proceso ha sido ${
            data.data.nuevoEstado == 1 ? "activado" : "desactivado"
          } con éxito.`
        );
      } else {
        showModal(false, `Error: ${data.message}`);
      }
    })
    .catch(() => showModal(false, "Error de red o servidor."));
});

const $btnEliminar = document.getElementById("btn-eliminar");

$btnEliminar.addEventListener("click", (e) => {
  const idProceso = id_proceso;

  fetch(
    `http://localhost/residencia/server/php/procesos/eliminar-proceso.php?idProceso=${idProceso}`
  )
    .then((res) => res.json())
    .then((data) => {
      if (data.ok) {
        showModal(true, "El proceso ha sido eliminado con éxito.");
        // Aquí podrías agregar alguna acción extra, como recargar la lista o redirigir.
      } else {
        showModal(false, `Error: ${data.message}`);
      }
    })
    .catch(() => showModal(false, "Error de red o servidor."));
});

const $btnPDF1 = document.getElementById("btn-pdf-1");
const $btnPDF2 = document.getElementById("btn-pdf-2");
const $btnPDF3 = document.getElementById("btn-pdf-3");
const $btnPDF4 = document.getElementById("btn-pdf-4");

//Plan de auditoria
$btnPDF1.addEventListener("click", function () {
  fetch(
    `http://localhost/residencia/server/php/procesos/obtener-info-auditoria.php?idProceso=${id_proceso}`
  )
    .then((response) => response.json())
    .then((data) => {
      // console.log(data);
      if (data.status === "success") {
        console.log(data);

        // Crear formulario oculto
        const form = document.createElement("form");
        form.method = "POST";
        form.action =
          "http://localhost/residencia/server/php/PDFs/plan-auditoria.php";
        form.target = "_blank"; // Abrir en nueva pestaña

        // Función para crear inputs rápidamente
        function addInput(name, value) {
          const input = document.createElement("input");
          input.type = "hidden";
          input.name = name;
          input.value = value;
          form.appendChild(input);
        }

        const tecnologicos = [
          data.data.institutoNorte &&
            "Instituto Tecnologico de Mérida Campus Norte",
          data.data.institutoPoniente &&
            "Instituto Tecnologico de Mérida Campus Poniente",
        ].filter(Boolean);

        let actividades = [
          {
            horario: `${formatearFechaHora(
              data.data.fechaInicioApertura
            )} a ${formatearFechaHora(data.data.fechaFinalApertura)}`,
            proceso: "Reunión de apertura",
            participantes: ["Todos los participantes"],
            contactos: [],
            lugar: data.data.areaApertura,
          },
        ];

        data.data.actividades.sort(
          (a, b) => new Date(a.fechaInicio) - new Date(b.fechaInicio)
        );

        data.data.actividades.forEach(function (actividad) {
          // Extraer nombres de contactos
          const contactos = actividad.contactos.map(
            (c) =>
              `${c.nombreCompleto} ${c.apellidoPaterno} ${c.apellidoMaterno}`
          );

          // Extraer nombres de participantes
          const participantes = actividad.participantes.map(
            (p) =>
              `${p.nombreCompleto} ${p.apellidoPaterno} ${p.apellidoMaterno}`
          );

          let objetoActividad = {
            horario: `${formatearFechaHora(
              actividad.fechaInicio
            )} a ${formatearFechaHora(actividad.fechaFinal)}`,
            proceso: `${actividad.tipoProceso},   ${actividad.actividad},   ${actividad.requisito}`,
            participantes: participantes,
            contactos: contactos,
            lugar: actividad.area,
          };

          actividades.push(objetoActividad);
        });

        actividades.push({
          horario: `${formatearFechaHora(
            data.data.fechaInicioCierre
          )} a ${formatearFechaHora(data.data.fechaFinalCierre)}`,
          proceso: "Reunión de cierre",
          participantes: ["Todos los participantes"],
          contactos: [],
          lugar: data.data.areaCierre,
        });

        console.log(actividades);

        // Añadir los campos necesarios
        addInput("alcance", data.data.alcance);
        addInput("objetivo", data.data.objetivo);
        addInput("fecha", data.data.fechaInicioApertura);
        addInput("tecnologicos", JSON.stringify(tecnologicos));
        addInput("actividades", JSON.stringify(actividades));
        addInput("firma", data.data.auditorLider.firmaElectronica);
        addInput(
          "nombre",
          `${data.data.auditorLider.nombreCompleto} ${data.data.auditorLider.apellidoPaterno} ${data.data.auditorLider.apellidoMaterno}`
        );
        addInput("firma2", data.data.auditorLider2.firmaElectronica);
        addInput(
          "nombre2",
          `${data.data.auditorLider2.nombreCompleto} ${data.data.auditorLider2.apellidoPaterno} ${data.data.auditorLider2.apellidoMaterno}`
        );
        addInput("firma3", data.data.auditorLider3.firmaElectronica);
        addInput(
          "nombre3",
          `${data.data.auditorLider3.nombreCompleto} ${data.data.auditorLider3.apellidoPaterno} ${data.data.auditorLider3.apellidoMaterno}`
        );

        document.body.appendChild(form);
        form.submit();
      } else {
        console.error("Error en respuesta:", data.message);
      }
    })
    .catch((error) => {
      console.error("Error de red:", error);
    });
});

//Reunión de apertura
$btnPDF2.addEventListener("click", function () {
  fetch(
    `http://localhost/residencia/server/php/procesos/obtener-info-auditoria.php?idProceso=${id_proceso}`
  )
    .then((response) => response.json())
    .then((data) => {
      // console.log(data);
      if (data.status === "success") {
        console.log(data);

        // Crear formulario oculto
        const form = document.createElement("form");
        form.method = "POST";
        form.action =
          "http://localhost/residencia/server/php/PDFs/reunion-apertura.php";
        form.target = "_blank"; // Abrir en nueva pestaña

        // Función para crear inputs rápidamente
        function addInput(name, value) {
          const input = document.createElement("input");
          input.type = "hidden";
          input.name = name;
          input.value = value;
          form.appendChild(input);
        }

        const participantes = [];

        data.data.participantes.forEach(function (p) {
          participantes.push({
            nombre: `${p.nombreCompleto} ${p.apellidoPaterno} ${p.apellidoMaterno}`,
            cargo: `${p.puesto} de ${p.departamento}`,
            img: p.firmaElectronica, // Asegúrate que en el backend se envía el nombre del archivo de imagen como "img"
          });
        });


        // Añadir los campos necesarios
        addInput(
          "horaInicio",
          obtenerHoraMinuto(data.data.fechaInicioApertura)
        );
        addInput("horaFinal", obtenerHoraMinuto(data.data.fechaFinalApertura));
        addInput("dia", obtenerDia(data.data.fechaFinalApertura));
        addInput("mes", obtenerMes(data.data.fechaFinalApertura));
        addInput("anio", obtenerAnio(data.data.fechaFinalApertura));
        addInput("area", data.data.areaApertura);
        addInput("participantes", JSON.stringify(participantes));

        document.body.appendChild(form);
        form.submit();
      } else {
        console.error("Error en respuesta:", data.message);
      }
    })
    .catch((error) => {
      console.error("Error de red:", error);
    });
});

//Reunión de cierre
$btnPDF3.addEventListener("click", function () {
  fetch(
    `http://localhost/residencia/server/php/procesos/obtener-info-auditoria.php?idProceso=${id_proceso}`
  )
    .then((response) => response.json())
    .then((data) => {
      // console.log(data);
      if (data.status === "success") {
        console.log(data);

        // Crear formulario oculto
        const form = document.createElement("form");
        form.method = "POST";
        form.action =
          "http://localhost/residencia/server/php/PDFs/reunion-cierre.php";
        form.target = "_blank"; // Abrir en nueva pestaña

        // Función para crear inputs rápidamente
        function addInput(name, value) {
          const input = document.createElement("input");
          input.type = "hidden";
          input.name = name;
          input.value = value;
          form.appendChild(input);
        }

        const participantes = [];

        data.data.participantes.forEach(function (p) {
          participantes.push({
            nombre: `${p.nombreCompleto} ${p.apellidoPaterno} ${p.apellidoMaterno}`,
            cargo: `${p.puesto} de ${p.departamento}`,
            img: p.firmaElectronica, // Asegúrate que en el backend se envía el nombre del archivo de imagen como "img"
          });
        });


        // Añadir los campos necesarios
        addInput("horaInicio", obtenerHoraMinuto(data.data.fechaInicioCierre));
        addInput("horaFinal", obtenerHoraMinuto(data.data.fechaFinalCierre));
        addInput("dia", obtenerDia(data.data.fechaFinalCierre));
        addInput("mes", obtenerMes(data.data.fechaFinalCierre));
        addInput("anio", obtenerAnio(data.data.fechaFinalCierre));
        addInput("area", data.data.areaCierre);
        addInput("participantes", JSON.stringify(participantes));
        addInput("fechaEvidencias", formatearFecha(data.data.entregaEvidencia));

        document.body.appendChild(form);
        form.submit();
      } else {
        console.error("Error en respuesta:", data.message);
      }
    })
    .catch((error) => {
      console.error("Error de red:", error);
    });
});

//Informe de auditoria
$btnPDF4.addEventListener("click", function () {
  fetch(
    `http://localhost/residencia/server/php/procesos/obtener-info-auditoria.php?idProceso=${id_proceso}`
  )
    .then((response) => response.json())
    .then((data) => {
      // console.log(data);
      if (data.status === "success") {
        console.log(data);

        // Crear formulario oculto
        const form = document.createElement("form");
        form.method = "POST";
        form.action =
          "http://localhost/residencia/server/php/PDFs/informe-auditoria.php";
        form.target = "_blank"; // Abrir en nueva pestaña

        // Función para crear inputs rápidamente
        function addInput(name, value) {
          const input = document.createElement("input");
          input.type = "hidden";
          input.name = name;
          input.value = value;
          form.appendChild(input);
        }

        const participantes = [];

        data.data.participantes.forEach(function (p) {
          participantes.push({
            nombre: `${p.nombreCompleto} ${p.apellidoPaterno} ${p.apellidoMaterno}`,
            cargo: `${p.puesto} de ${p.departamento}`,
            img: p.firmaElectronica, // Asegúrate que en el backend se envía el nombre del archivo de imagen como "img"
          });
        });

        // Añadir los campos necesarios
        // addInput("horaInicio", obtenerHoraMinuto(data.data.fechaInicioCierre));
        addInput("objetivo", data.data.objetivo);
        addInput("alcance", data.data.alcance);
        addInput("numero", data.data.numero);
        addInput("proceso", data.data.tipoProceso);
        addInput("fechaInicio", formatearFecha(data.data.fechaInicioApertura));
        addInput("fechaFinal", formatearFecha(data.data.fechaFinalCierre));
        addInput(
          "fechaHoy",
          formatearFecha(new Date().toISOString().split("T")[0])
        );

        addInput(
          "NombreauditorLider",
          `${data.data.auditorLider.nombreCompleto} ${data.data.auditorLider.apellidoPaterno} ${data.data.auditorLider.apellidoMaterno}`
        );
        addInput("firmaAuditorLider", data.data.auditorLider.firmaElectronica);
        
        addInput(
          "NombreauditorLider2",
          `${data.data.auditorLider2.nombreCompleto} ${data.data.auditorLider2.apellidoPaterno} ${data.data.auditorLider2.apellidoMaterno}`
        );
        addInput("firmaAuditorLider2", data.data.auditorLider2.firmaElectronica);

        addInput(
          "NombreauditorLider3",
          `${data.data.auditorLider3.nombreCompleto} ${data.data.auditorLider3.apellidoPaterno} ${data.data.auditorLider3.apellidoMaterno}`
        );
        addInput("firmaAuditorLider3", data.data.auditorLider3.firmaElectronica);

        addInput("firmaRecibe", data.data.usuarioRecibe.firmaElectronica);
        addInput("mejoras", JSON.stringify(data.data.mejoras));
        addInput("comentarios", JSON.stringify(data.data.comentarios));
        addInput("conclusiones", JSON.stringify(data.data.conclusiones));
        addInput("noConformidades", JSON.stringify(data.data.noConformidades));
        addInput("participantes", JSON.stringify(data.data.participantes));
        addInput("personalContactado", JSON.stringify(data.data.personalContactado));

        // addInput("horaFinal", obtenerHoraMinuto(data.data.fechaFinalCierre));
        // addInput("dia", obtenerDia(data.data.fechaFinalCierre));
        // addInput("mes", obtenerMes(data.data.fechaFinalCierre));
        // addInput("anio", obtenerAnio(data.data.fechaFinalCierre));
        // addInput("area", data.data.areaCierre);
        // addInput("participantes", JSON.stringify(participantes));
        // addInput("fechaEvidencias", formatearFecha(data.data.entregaEvidencia));

        document.body.appendChild(form);
        form.submit();
      } else {
        console.error("Error en respuesta:", data.message);
      }
    })
    .catch((error) => {
      console.error("Error de red:", error);
    });
});

function formatearFechaHora(fechaStr) {
  const fecha = new Date(fechaStr);
  const opcionesFecha = { day: "2-digit", month: "2-digit", year: "2-digit" };
  const opcionesHora = { hour: "numeric", minute: "2-digit", hour12: true };

  const fechaFormateada = fecha.toLocaleDateString("es-MX", opcionesFecha);
  const horaFormateada = fecha
    .toLocaleTimeString("es-MX", opcionesHora)
    .toLowerCase();

  return `${fechaFormateada}, ${horaFormateada}`;
}

// Día (número del día del mes)
function obtenerDia(fechaStr) {
  const fecha = new Date(fechaStr);
  return fecha.getDate().toString().padStart(2, "0");
}

// Mes en español
function obtenerMes(fechaStr) {
  const meses = [
    "Enero",
    "Febrero",
    "Marzo",
    "Abril",
    "Mayo",
    "Junio",
    "Julio",
    "Agosto",
    "Septiembre",
    "Octubre",
    "Noviembre",
    "Diciembre",
  ];
  const fecha = new Date(fechaStr);
  return meses[fecha.getMonth()];
}

// Hora y minuto en formato 12h con a.m./p.m.
function obtenerHoraMinuto(fechaStr) {
  const fecha = new Date(fechaStr);
  let horas = fecha.getHours();
  const minutos = fecha.getMinutes().toString().padStart(2, "0");
  const ampm = horas >= 12 ? "p.m." : "a.m.";
  horas = horas % 12 || 12;
  return `${horas}:${minutos} ${ampm}`;
}

// Año
function obtenerAnio(fechaStr) {
  const fecha = new Date(fechaStr);
  return fecha.getFullYear().toString();
}

function formatearFecha(fechaStr) {
  const fecha = new Date(fechaStr);
  const dia = String(fecha.getDate()).padStart(2, "0");
  const mes = String(fecha.getMonth() + 1).padStart(2, "0"); // Los meses van de 0 a 11
  const anio = fecha.getFullYear();
  return `${dia}/${mes}/${anio}`;
}
