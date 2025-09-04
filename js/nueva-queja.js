//A. Importaciones

import { url_quejas_create_queja, procesos } from "./urls/urls.js";

//B. Declaracion de identificadores y elementos

// 1. Formulario de quejas
const $formQueja = document.getElementById("form-quejas");

// 2. Boton de agregar usuario a proceso
const $btnUsuariosQueja = document.getElementById("btnUsuarios-queja");

//Funcionamiento de pagina

$formQueja.addEventListener("submit", async (e) => {
  e.preventDefault(); // Evitar recarga de página

  // Objeto estático a enviar
  const quejaData = {
    tipoProceso: "Queja o Sugerencia",
    folioProceso: document.getElementById("folio-queja").value,
    estadoProceso: "1",
    fechaQueja: document.getElementById("fecha-queja").value,
    folioQueja: document.getElementById("folio-queja").value,
    nombre: document.getElementById("nombre-queja").value,
    correo: document.getElementById("correo-queja").value,
    telefono: document.getElementById("telefono-queja").value,
    matricula: document.getElementById("matricula-queja").value,
    carrera: document.getElementById("carrera-queja").value,
    semestre: document.getElementById("semestre-queja").value,
    grupo: document.getElementById("grupo-queja").value,
    turno: document.getElementById("turno-queja").value,
    aula: document.getElementById("aula-queja").value,
    textoQueja: document.getElementById("queja-queja").value,
    respuesta: document.getElementById("respuesta-queja").value,
    idCoordinador: document.getElementById("coordinador-queja").value,
    idRecibe: document.getElementById("recibe-queja").value,
    usuariosProceso: obtenerInfoTarjetas(
      document.getElementById("divUsuarios-queja")
    ),
  };

  console.log(quejaData);

  if (quejaData.folioProceso == "" || quejaData.folioQueja == "" || quejaData.fechaQueja == "" || quejaData.textoQueja == "") {
    alert("Datos necesarios faltantes: folio, fecha o queja o sugerencia.");
    return;
  }
    try {
      const response = await fetch(url_quejas_create_queja, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(quejaData),
      });

      const data = await response.json();
      console.log("Respuesta del servidor:", data);

      if (data.ok) {
        alert(data.message);
        window.location.href = procesos;
      } else {
        alert(data.message);
      }
    } catch (error) {
      console.error("Error en fetch:", error);
      alert("Ocurrió un error al enviar la queja.");
    }
}
);

// Funciones

//8. Funcionamiento de agregado de usuarios
$btnUsuariosQueja.addEventListener("click", () => {
  const $usuarios = document.getElementById("usuarios-queja");
  const $divUsuarios = document.getElementById("divUsuarios-queja");

  agregarTarjeta($usuarios, $divUsuarios);
});

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