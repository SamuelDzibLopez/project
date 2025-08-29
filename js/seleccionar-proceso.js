const $select = document.getElementById("opciones");
const $auditoriaDiv = document.getElementById("auditoria");
const $sugerenciaDiv = document.getElementById("Sujerencia");

const $formQuejas = document.getElementById("form-quejas");

function toggleDivs() {
  const value = $select.value;

  if (value === "1") {
    // Auditoria
    $auditoriaDiv.style.display = "block";
    $sugerenciaDiv.style.display = "none";
  } else if (value === "2") {
    // Quejas y Sugerencias
    $sugerenciaDiv.style.display = "block";
    $auditoriaDiv.style.display = "none";
  } else {
    $auditoriaDiv.style.display = "none";
    $sugerenciaDiv.style.display = "none";
  }
}

// Ejecutar una vez al cargar la página
toggleDivs();

// Ejecutar cada vez que se cambia la opción
$select.addEventListener("change", toggleDivs);

