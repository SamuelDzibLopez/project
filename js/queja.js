// Importaciones

import { get_Id_Proceso } from "./functions/functions.js";
import { url_quejas_obtener_info_queja, url_usuarios_obtener_id_usuarios } from "./urls/urls.js";

//Varaibles
const idSelectsUsuarios = ["recibe-queja", "coordinador-queja", "usuarios-queja"];

// Funcionamiento de modulo


document.addEventListener("DOMContentLoaded", async () => {
  // A. Obtener los parámetros de la URL
  let urlParams = new URLSearchParams(window.location.search);

  // Ejecutar función que retorna ID de proceso
  let id_proceso = get_Id_Proceso(urlParams);

  console.log("ID proceso:", id_proceso);

  await cargarUsuariosEnSelects(idSelectsUsuarios, url_usuarios_obtener_id_usuarios);


  try {
    const response = await fetch(
      `${url_quejas_obtener_info_queja}${id_proceso}`,
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

  try {

    document.getElementById("fecha-queja").value = data.queja.info.fecha;
    document.getElementById("folio-queja").value = data.proceso.folio;
    document.getElementById("nombre-queja").value = data.queja.info.nombre;
    document.getElementById("correo-queja").value = data.queja.info.correo;
    document.getElementById("telefono-queja").value = data.queja.info.telefono;
    document.getElementById("matricula-queja").value = data.queja.info.matricula;
    document.getElementById("carrera-queja").value = data.queja.info.carrera;

    document.getElementById("semestre-queja").value = data.queja.info.semestre;
    document.getElementById("grupo-queja").value = data.queja.info.grupo;
    document.getElementById("turno-queja").value = data.queja.info.turno;
    document.getElementById("aula-queja").value = data.queja.info.aula;
    document.getElementById("queja-queja").value = data.queja.info.queja;
    document.getElementById("respuesta-queja").value = data.queja.info.respuesta;

    const idCoordinador = data?.queja?.coordinador?.idUsuario ?? data?.queja?.coordinador?.idUsuario ?? "";
    await setSelectFromData("#coordinador-queja", idCoordinador);

    const idRecibe = data?.queja?.recibe?.idUsuario ?? data?.queja?.recibe?.idUsuario ?? "";
    await setSelectFromData("#recibe-queja", idRecibe);

    renderizarTarjetasDesdeArrayUsuarios(data.usuariosProceso, document.getElementById("divUsuarios-queja"));

  } catch (e) {
    console.warn(e.message);
  }
}

// Funciones

//Funcion para renderizar tarjetas de usuarios
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
        select.innerHTML += ``;

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

