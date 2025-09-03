import { url_quejas_create_queja, procesos } from "./urls/urls.js";

const $formQueja = document.getElementById("form-quejas");

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
    carrera: document.getElementById("carrera-quja").value,
    semestre: document.getElementById("semestre-queja").value,
    grupo: document.getElementById("grupo-queja").value,
    turno: document.getElementById("turno-queja").value,
    aula: document.getElementById("aula-queja").value,
    textoQueja: document.getElementById("queja-queja").value,
    respuesta: document.getElementById("respuesta-queja").value,
    idCoordinador: document.getElementById("coordinador-queja").value,
    idRecibe: document.getElementById("recibe-queja").value,
    usuariosProceso: [2, 3, 5],
  };

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
});
