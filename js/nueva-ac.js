import { showModal } from "./functions/functions.js";

const $btnAgregarUsuario = document.getElementById("ac-btn-agregar-usuarios");
const $tablaCorrecciones = document.getElementById("ac-tabla-correcciones");
const $tablaAcciones = document.getElementById("ac-tabla-acciones");
const $btnAgregarCorreccion = document.getElementById("ac-btn-agregar-correccion");
const $btnAgregarAccion = document.getElementById("ac-btn-agregar-accion");
const $acForm = document.getElementById("ac-form");

//Agregar usuarios
$btnAgregarUsuario.addEventListener("click", () => {
    const $usuarios = document.getElementById("ac-usuarios");
    const $divUsarios = document.getElementById("ac-div-usuarios");

    agregarTarjeta($usuarios, $divUsarios);
});

//Agregar correcciones
$btnAgregarCorreccion.addEventListener("click", (e) => {
  const correccion = {
    texto: document.getElementById("ac-correccion-textarea").value,
    responsable:
      document.getElementById("ac-responsable-select").value === ""
        ? ""
        : document.getElementById("ac-responsable-select").options[
            document.getElementById("ac-responsable-select").selectedIndex
          ].text,
    idResponsable: document.getElementById("ac-responsable-select").value,
    fecha: document.getElementById("ac-fecha-registro").value,
  };
  agregarATabla(correccion, $tablaCorrecciones, "correccion");
});

//Agregar acciones
$btnAgregarAccion.addEventListener("click", (e) => {
    const accion = {
        texto: document.getElementById("ac-textarea-accion").value,
        responsable: document.getElementById("ac-select-responsable-accion").value === "" ? "" : document.getElementById("ac-select-responsable-accion").options[document.getElementById("ac-select-responsable-accion").selectedIndex].text,
        idResponsable: document.getElementById("ac-select-responsable-accion").value,
        fecha: document.getElementById("ac-fecha-registro-accion").value,
    };
    agregarATabla(accion, $tablaAcciones, "accion");
});

let filaModificada = null; // ← Guardará la fila a modificar


//Modificar correccion
document.addEventListener("click", function (e) {
    if (e.target.classList.contains("btn-modificar-correccion")) {
        const fondo = document.getElementById("modalModificarCorreccionFondo");
        fondo.style.display = "flex";

        const fila = e.target.parentElement.parentElement;
        filaModificada = fila; // ← Guardar la fila correspondiente

        const data = JSON.parse(fila.getAttribute("data-info"));

        console.log(data);

        document.getElementById("ac-modificar-correccion-textarea").value = data.texto;
        document.getElementById("ac-modificar-responsable-select").value = data.idResponsable;
        document.getElementById("ac-modificar-fecha-registro").value = data.fecha;
    }
});

//Modificar accion
document.addEventListener("click", function (e) {
    if (e.target.classList.contains("btn-modificar-accion")) {
        const fondo = document.getElementById("modalModificarAccionFondo");
        fondo.style.display = "flex";

        const fila = e.target.parentElement.parentElement;
        filaModificada = fila; // ← Guardar la fila correspondiente

        const data = JSON.parse(fila.getAttribute("data-info"));

        console.log(data);

        document.getElementById("ac-modificar-accion-textarea").value = data.texto;
        document.getElementById("ac-modificar-responsable-accion").value = data.idResponsable;
        document.getElementById("ac-modificar-fecha-accion").value = data.fecha;


    }
});

//Cancelar modal de correccion
document.getElementById("ac-modificar-btn-cancelar-correccion").addEventListener("click", function () {
    document.getElementById("modalModificarCorreccionFondo").style.display = "none";
});

//Cancelar modal de accion
document.getElementById("ac-modificar-btn-cancelar-accion").addEventListener("click", function () {
    document.getElementById("modalModificarAccionFondo").style.display = "none";
});

//Guardar modificacion de correccion
const $btnGuardarModificarCorreccion = document.getElementById("ac-modificar-btn-guardar-correccion");

$btnGuardarModificarCorreccion.addEventListener("click", (e) => {
    const correccion = {
      texto: document.getElementById("ac-modificar-correccion-textarea").value,
      responsable:
        document.getElementById("ac-modificar-responsable-select").value === ""
          ? ""
          : document.getElementById("ac-modificar-responsable-select").options[
              document.getElementById("ac-modificar-responsable-select")
                .selectedIndex
            ].text,
      idResponsable: document.getElementById("ac-modificar-responsable-select")
        .value,
      fecha: document.getElementById("ac-modificar-fecha-registro").value,
    };

  if (!filaModificada) return;

  filaModificada.setAttribute("data-info", JSON.stringify(correccion));

  const tds = filaModificada.querySelectorAll("td");

  // Actualizar los td deseados
  tds[0].textContent = correccion.texto;
  tds[1].textContent = correccion.responsable;
  tds[2].textContent = formatearFecha(correccion.fecha);

  filaModificada = null; // Limpiar referencia

  document.getElementById("modalModificarCorreccionFondo").style.display = "none";
});

//Guardar modificacion de accion
const $btnGuardarModificarAccion = document.getElementById("ac-modificar-btn-guardar-accion");

$btnGuardarModificarAccion.addEventListener("click", (e) => {
    const accion = {
      texto: document.getElementById("ac-modificar-accion-textarea").value,
      responsable:
        document.getElementById("ac-modificar-responsable-accion").value === ""
          ? ""
          : document.getElementById("ac-modificar-responsable-accion").options[
              document.getElementById("ac-modificar-responsable-accion")
                .selectedIndex
            ].text,
      idResponsable: document.getElementById("ac-modificar-responsable-accion")
        .value,
      fecha: document.getElementById("ac-modificar-fecha-accion").value,
    };

  if (!filaModificada) return;

  filaModificada.setAttribute("data-info", JSON.stringify(accion));

  const tds = filaModificada.querySelectorAll("td");

  // Actualizar los td deseados
  tds[0].textContent = accion.texto;
  tds[1].textContent = accion.responsable;
  tds[2].textContent = formatearFecha(accion.fecha);

  filaModificada = null; // Limpiar referencia

  document.getElementById("modalModificarAccionFondo").style.display = "none";
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

//Agregar fila a tabla
function agregarATabla(datos, $table, tipo) {
  const $tbody = $table.querySelector("tbody");

  // Formatear fecha si existe
  let fechaFormatted = "";
  if (datos.fecha) {
    fechaFormatted = new Date(datos.fecha + "T00:00:00").toLocaleDateString(
      "es-MX",
      {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
      }
    );
  }

  // Crear fila
  const $fila = document.createElement("tr");
  $fila.dataset.info = JSON.stringify(datos);

  const $tdAccion = document.createElement("td");
  $tdAccion.textContent = datos.texto || "";

  const $tdResponsable = document.createElement("td");
  $tdResponsable.textContent = datos.responsable || "";

  const $tdFecha = document.createElement("td");
  $tdFecha.textContent = fechaFormatted;

  const $tdOpciones = document.createElement("td");

  const $btnModificar = document.createElement("button");
  $btnModificar.type = "button";
  $btnModificar.textContent = "Modificar";
  if (tipo == "correccion") {
    $btnModificar.classList.add(
      "btn",
      "btn-modificar",
      "escalado",
      "btn-modificar-correccion"
    );
  } else if (tipo == "accion") {
    $btnModificar.classList.add(
      "btn",
      "btn-modificar",
      "escalado",
      "btn-modificar-accion"
    );
  } else {
    $btnModificar.classList.add(
      "btn",
      "btn-modificar",
      "escalado",
      "btn-modificar-correccion"
    );
  }

  const $btnEliminar = document.createElement("button");
  $btnEliminar.type = "button";
  $btnEliminar.textContent = "Eliminar";
  $btnEliminar.classList.add("btn", "btn-eliminar", "escalado");

  $tdOpciones.appendChild($btnModificar);
  $tdOpciones.appendChild($btnEliminar);

  $fila.appendChild($tdAccion);
  $fila.appendChild($tdResponsable);
  $fila.appendChild($tdFecha);
  $fila.appendChild($tdOpciones);

  $tbody.appendChild($fila);
}

//Formatear la fecha
function formatearFecha(fechaStr) {
    const fecha = new Date(fechaStr + "T00:00:00"); // fuerza hora local
    const dia = String(fecha.getDate()).padStart(2, "0");
    const mes = String(fecha.getMonth() + 1).padStart(2, "0");
    const anio = fecha.getFullYear();
    return `${dia}/${mes}/${anio}`;
}

$acForm.addEventListener("submit", (e) => {
  e.preventDefault();

  console.log("Registrando AC");

  const accionCorrectiva = {
    folio: document.getElementById("ac-folio").value,
    areaProceso: document.getElementById("ac-area-proceso").value,
    fecha: document.getElementById("ac-fecha").value,
    origenRequisito: document.getElementById("ac-origen-requisito").value,
    fuenteNC: document.getElementById("ac-fuente-nc").value,
    descripcion: document.getElementById("ac-descripcion").value,
    idUsuarioDefine: document.getElementById("ac-personal-define").value,
    idUsuarioVerifica: document.getElementById("ac-persona-verifica").value,
    idUsuarioCoordinador: document.getElementById("ac-coordinador-general")
      .value,
    requiereAC:
      document.querySelector('input[name="ac-requiere-accion"]:checked')
        .value === "Si",
    requiereCorreccion:
      document.querySelector('input[name="ac-requiere-correccion"]:checked')
        .value === "Si",
    correcciones: obtenerDatosDeTabla("ac-tabla-correcciones"),
    tecnicaUtilizada: document.getElementById("ac-tecnica-estadistica").value,
    causaRaizIdentificada: document.getElementById("ac-causa-raiz").value,
    ACRealizar: document.getElementById("ac-accion-correctiva").value,
    Similares:
      document.querySelector('input[name="ac-nc-similares"]:checked').value ===
      "Si",
    ACSimilares: document.getElementById("ac-nc-similares-acciones").value,
    potenciales:
      document.querySelector('input[name="ac-nc-potenciales"]:checked')
        .value === "Si",
    ACPotenciales: document.getElementById("ac-nc-potenciales-acciones").value,
    acciones: obtenerDatosDeTabla("ac-tabla-acciones"),
    seguimiento: document.getElementById("ac-seguimiento-evidencias").value,
    actualizar:
      document.querySelector('input[name="ac-riesgos"]:checked').value === "Si",
    ACActualizar: document.getElementById("ac-riesgos-acciones").value,
    cambios:
      document.querySelector('input[name="ac-cambios-sg"]:checked').value ===
      "Si",
    ACCambios: document.getElementById("ac-cambios-sg-acciones").value,
    usuarios: obtenerInfoTarjetas(document.getElementById("ac-div-usuarios")),
  };
  // console.log("Objeto a enviar:", accionCorrectiva);

  const formData = new FormData();

  for (const key in accionCorrectiva) {
    if (key === "correcciones" || key === "acciones" || key === "usuarios") {
      formData.append(key, JSON.stringify(accionCorrectiva[key]));
    } else {
      formData.append(key, accionCorrectiva[key]);
    }
  }

  fetch("http://localhost/residencia/server/php/procesos/create-ac.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      // console.log("Respuesta del servidor:", data);
      if (data.status === "success") {
        showModal(true, "Acción Correctiva registrada con éxito");
        // Puedes resetear el formulario si lo deseas:
        // $acForm.reset();
      } else {
        showModal(false, "Error al registrar Acción Correctiva: " + data.message);
      }
    })
    .catch((err) => {
      // console.error("Error en el fetch:", err);
      showModal(false, "Error al conectar con el servidor.");
    });
});

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