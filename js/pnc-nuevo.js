import {showModal} from "./functions/functions.js";

const $btnAgregarUsuario = document.getElementById("pnc-btn-usuarios");
const $btnAgregarPNC = document.getElementById("pnc-btn-agregar");

$btnAgregarUsuario.addEventListener("click", () => {
    const $usuarios = document.getElementById("pnc-usuarios");
    const $divUsarios = document.getElementById("pnc-div-usuarios");

    agregarTarjeta($usuarios, $divUsarios);
});

$btnAgregarPNC.addEventListener("click", (e) => {

    const PNC = {
        folio: document.getElementById("pnc-folio").value,
        fecha: document.getElementById("pnc-fecha").value,
        especificacion: document.getElementById("pnc-especificacion").value,
        accion: document.getElementById("pnc-accion").value,
        numero: document.getElementById("pnc-rac").value,
        eliminarPNC: document.querySelector('input[name="check"]:checked')?.value === "Si",
        idUsuarioVerifica: document.getElementById("pnc-verifica").value,
        idUsuarioLibera: document.getElementById("pnc-libera").value,
    };
    console.log(PNC);

    agregarPNC(PNC, document.getElementById("pnc-tabla"));

    document.getElementById("pnc-folio").value = null;
    document.getElementById("pnc-fecha").value = null;
    document.getElementById("pnc-especificacion").value = null;
    document.getElementById("pnc-accion").value = null;
    document.getElementById("pnc-rac").value = null;
    document.getElementById("pnc-verifica").value = "";
    document.getElementById("pnc-libera").value = "";
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

function agregarPNC(datos, $table) {
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
    datos.folio;

  const $tdProceso = document.createElement("td");
  $tdProceso.textContent = formatearFecha(datos.fecha);

  const $tdActividad = document.createElement("td");
  $tdActividad.textContent = datos.especificacion;

  const $tdRequisito = document.createElement("td");
  $tdRequisito.textContent = datos.accion;

  const $tdOpciones = document.createElement("td");

  // Botón Modificar
  const $btnModificar = document.createElement("button");
  $btnModificar.type = "button";
  $btnModificar.textContent = "Modificar";
  $btnModificar.classList.add(
    "btn",
    "btn-modificar",
    "escalado",
    "btn-modificar-pnc"
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

let filaModificada = null; // ← Guardará la fila a modificar

//Abrir modal de modificar
document.addEventListener("click", function (e) {
  if (e.target.classList.contains("btn-modificar-pnc")) {
    const fondo = document.getElementById("modalModificarPNCFondo");
    fondo.style.display = "flex";

    const fila = e.target.parentElement.parentElement;
    filaModificada = fila; // ← Guardar la fila correspondiente

    const data = JSON.parse(fila.getAttribute("data-info"));

    console.log(data);

      document.getElementById("modificar-pnc-folio").value = data.folio;
      document.getElementById("modificar-pnc-fecha").value = data.fecha;
      document.getElementById("modificar-pnc-especificacion").value = data.especificacion;
      document.getElementById("modificar-pnc-accion").value = data.accion;
      document.getElementById("modificar-pnc-rac").value = data.numero;
      if (data.eliminarPNC) {
        document.getElementById("CheckYesModificar").checked = true;
        document.getElementById("CheckNoModificar").checked = false;
      } else {
        document.getElementById("CheckNoModificar").checked = true;
        document.getElementById("CheckYesModificar").checked = false;
      }
      document.getElementById("modificar-pnc-verifica").value = data.idUsuarioVerifica;
      document.getElementById("modificar-pnc-libera").value = data.idUsuarioLibera;
  }
});

const $btnGuardarModificarPNC = document.getElementById("btnGuardarModificarPNC");

$btnGuardarModificarPNC.addEventListener("click", (e) => {
  const dataModificado = {
    folio: document.getElementById("modificar-pnc-folio").value,
    fecha: document.getElementById("modificar-pnc-fecha").value,
    especificacion: document.getElementById("modificar-pnc-especificacion")
      .value,
    accion: document.getElementById("modificar-pnc-accion").value,
    numero: document.getElementById("modificar-pnc-rac").value,
    eliminarPNC:
      document.querySelector('input[name="checkModificar"]:checked')?.value ===
      "Si",
    idUsuarioVerifica: document.getElementById("modificar-pnc-verifica").value,
    idUsuarioLibera: document.getElementById("modificar-pnc-libera").value,
  };

  if (!filaModificada) return;

  filaModificada.setAttribute("data-info", JSON.stringify(dataModificado));

  const tds = filaModificada.querySelectorAll("td");

  // Actualizar los td deseados
  tds[0].textContent = dataModificado.folio
  tds[1].textContent = formatearFecha(dataModificado.fecha);
  tds[2].textContent = dataModificado.especificacion;
  tds[3].textContent = dataModificado.accion;

  filaModificada = null; // Limpiar referencia

  document.getElementById("modalModificarPNCFondo").style.display = "none";
} );

const $PNCform = document.getElementById("pnc-form");

$PNCform.addEventListener("submit", (e) => {
  e.preventDefault();

  console.log("Registrando PNC");

  // Recolectar datos
  const infoPNC = {
    idUsuarioElabora: document.getElementById("pnc-elabora").value,
    idUsuarioValida: document.getElementById("pnc-valida").value,
    idCoordinador: document.getElementById("pnc-coordinador").value,
    PNCs: obtenerDatosDeTabla("pnc-tabla"), // debe devolver array de objetos
    usuarios: obtenerInfoTarjetas(document.getElementById("pnc-div-usuarios")), // debe devolver array de ids
  };

  // Crear FormData
  const formData = new FormData();
  formData.append("idUsuarioElabora", infoPNC.idUsuarioElabora);
  formData.append("idUsuarioValida", infoPNC.idUsuarioValida);
  formData.append("idCoordinador", infoPNC.idCoordinador);
  formData.append("PNCs", JSON.stringify(infoPNC.PNCs));
  formData.append("usuarios", JSON.stringify(infoPNC.usuarios));

  // Enviar con fetch
  fetch("http://localhost/residencia/server/php/procesos/create-pnc.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      console.log("Respuesta del servidor:", data);
      if (data.status === "success") {
        showModal(true, "Registro exitoso");
        // Aquí puedes limpiar el formulario o redirigir
      } else {
        showModal(false, "Error: " + data.message);
      }
    })
    .catch((err) => {
      console.error("Error en el fetch:", err);
      showModal(false, "Error en la conexión");
    });
});

//Cancelar modificación
document
  .getElementById("btnCancelarModificarPNC")
  .addEventListener("click", function () {
    document.getElementById("modalModificarPNCFondo").style.display =
      "none";
  });

  function formatearFecha(fechaStr) {
    const fecha = new Date(fechaStr);
    const dia = String(fecha.getDate()+ 1).padStart(2, "0");
    const mes = String(fecha.getMonth() + 1).padStart(2, "0"); // Los meses van de 0 a 11
    const anio = fecha.getFullYear();
    return `${dia}/${mes}/${anio}`;
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