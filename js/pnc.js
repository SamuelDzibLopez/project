import { get_Id_Proceso, showModal } from "./functions/functions.js";

// Declaramos variables globales
let usuarios = [];

const urlParams = new URLSearchParams(window.location.search);
const id_proceso = get_Id_Proceso(urlParams);

const $btnAgregarPNC = document.getElementById("pnc-btn-agregar");

Promise.all([
  fetch(
    "http://localhost/residencia/server/php/usuarios/obtener-id-usuarios.php"
  ).then((response) => {
    if (!response.ok) throw new Error("Error al obtener los usuarios");
    return response.json();
  }),
])
  .then(([usuariosData]) => {
    // CORREGIDO: destructuración del array
    if (usuariosData.status !== "success")
      throw new Error(usuariosData.message);

    usuarios = usuariosData.data;

    const selectsUsuarios = [
      "pnc-elabora",
      "pnc-valida",
      "pnc-coordinador",
      "pnc-verifica",
      "pnc-libera",
      "pnc-usuarios",
      "modificar-pnc-verifica",
      "modificar-pnc-libera",
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

    return fetch(
      `http://localhost/residencia/server/php/procesos/obtener-info-pnc.php?idProceso=${id_proceso}`
    );
  })
  .then((response) => {
    if (!response.ok) throw new Error("Error al obtener auditoría");
    return response.json();
  })
  .then((data) => {
    if (data.status !== "success") throw new Error(data.message);

    console.log(data);

    document.getElementById("pnc-elabora").value = data.data.productoNoConforme.idUsuarioElabora;
    document.getElementById("pnc-valida").value = data.data.productoNoConforme.idUsuarioValida;
    document.getElementById("pnc-coordinador").value = data.data.productoNoConforme.idUsuarioCoordinador;

    const renderTarjetas = (listaUsuarios, contenedorId) => {
      const contenedor = document.getElementById(contenedorId);
      contenedor.innerHTML = "";

      listaUsuarios.forEach((usuario) => {
        const tarjeta = document.createElement("div");
        tarjeta.classList.add("tarjet");
        tarjeta.setAttribute("data-value", usuario.idUsuario);
        tarjeta.innerHTML = `
            <p>${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}</p>
            <button type='button' class='btn-closed'>X</button>
          `;
        tarjeta.querySelector(".btn-closed").onclick = () => tarjeta.remove();
        contenedor.appendChild(tarjeta);
      });
    };      

    renderTarjetas(data.data.usuarios, "pnc-div-usuarios");

    data.data.productosNoConformesIndividuales.forEach((pnc) => {
        let objData = {
          folio: pnc.folio,
          fecha: pnc.fecha,
          especificacion: pnc.especificacion,
          accion: pnc.accion,
          numero: pnc.numero,
          eliminarPNC: pnc.eliminar === "1", // conversión explícita
          idUsuarioVerifica: pnc.idUsuarioVerifica,
          idUsuarioLibera: pnc.idUsuarioLibera,
        };        
        
        agregarPNC(objData, document.getElementById("pnc-tabla"));
    });

})
  .catch((error) => {
    console.error("Error en proceso:", error);
    showModal(false, error.message);
});

const $pncBtnUsuarios = document.getElementById("pnc-btn-usuarios");

$pncBtnUsuarios.addEventListener("click", (e) => {
    agregarTarjeta(document.getElementById("pnc-usuarios"), document.getElementById("pnc-div-usuarios"));
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

function formatearFecha(fechaStr) {
    const fecha = new Date(fechaStr);
    const dia = String(fecha.getDate() + 1).padStart(2, "0");
    const mes = String(fecha.getMonth() + 1).padStart(2, "0"); // Los meses van de 0 a 11
    const anio = fecha.getFullYear();
    return `${dia}/${mes}/${anio}`;
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
    document.getElementById("modificar-pnc-especificacion").value =
      data.especificacion;
    document.getElementById("modificar-pnc-accion").value = data.accion;
    document.getElementById("modificar-pnc-rac").value = data.numero;
    if (data.eliminarPNC) {
      document.getElementById("CheckYesModificar").checked = true;
      document.getElementById("CheckNoModificar").checked = false;
    } else {
      document.getElementById("CheckNoModificar").checked = true;
      document.getElementById("CheckYesModificar").checked = false;
    }
    document.getElementById("modificar-pnc-verifica").value =
      data.idUsuarioVerifica;
    document.getElementById("modificar-pnc-libera").value =
      data.idUsuarioLibera;
  }
});

const $btnGuardarModificarPNC = document.getElementById(
  "btnGuardarModificarPNC"
);

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
  tds[0].textContent = dataModificado.folio;
  tds[1].textContent = formatearFecha(dataModificado.fecha);
  tds[2].textContent = dataModificado.especificacion;
  tds[3].textContent = dataModificado.accion;

  filaModificada = null; // Limpiar referencia

  document.getElementById("modalModificarPNCFondo").style.display = "none";
});

//Cancelar modificación
document
  .getElementById("btnCancelarModificarPNC")
  .addEventListener("click", function () {
    document.getElementById("modalModificarPNCFondo").style.display =
      "none";
  });

$btnAgregarPNC.addEventListener("click", (e) => {
    const PNC = {
      folio: document.getElementById("pnc-folio").value,
      fecha: document.getElementById("pnc-fecha").value,
      especificacion: document.getElementById("pnc-especificacion").value,
      accion: document.getElementById("pnc-accion").value,
      numero: document.getElementById("pnc-rac").value,
      eliminarPNC:
        document.querySelector('input[name="check"]:checked')?.value === "Si",
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

//Boton de eliminar
document.addEventListener("click", function (e) {
  if (e.target.classList.contains("btn-eliminar")) {
    const fila = e.target.closest("tr");
    if (fila) fila.remove();
  }
});

const $pncForm = document.getElementById("pnc-form");

$pncForm.addEventListener("submit", (e) => {
  e.preventDefault();

  // console.log("Modificando pnc");

  const infoPNC = {
    idProceso: id_proceso,
    idUsuarioElabora: document.getElementById("pnc-elabora").value,
    idUsuarioValida: document.getElementById("pnc-valida").value,
    idCoordinador: document.getElementById("pnc-coordinador").value,
    PNCs: obtenerDatosDeTabla("pnc-tabla"), // array de objetos
    usuarios: obtenerInfoTarjetas(document.getElementById("pnc-div-usuarios")), // array de ids
  };

  // Mostrar en consola
  // console.log(JSON.stringify(infoPNC));

  // Crear FormData y anexar los datos como strings
  const formData = new FormData();
  formData.append("idProceso", infoPNC.idProceso);
  formData.append("idUsuarioElabora", infoPNC.idUsuarioElabora);
  formData.append("idUsuarioValida", infoPNC.idUsuarioValida);
  formData.append("idCoordinador", infoPNC.idCoordinador);
  formData.append("PNCs", JSON.stringify(infoPNC.PNCs));
  formData.append("usuarios", JSON.stringify(infoPNC.usuarios));

  fetch("http://localhost/residencia/server/php/procesos/modificar-pnc.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        showModal(true, "Producto no conforme modificado correctamente.");
        // console.log("Respuesta del servidor:", data);
      } else {
        showModal(false, "Error: " + data.message);
        // console.error("Error:", data);
      }
    })
    .catch((error) => {
      showModal(false, "Error de red o servidor.");
      // console.error("Error en fetch:", error);
    });
});

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

const $btnPDF = document.getElementById("btn-pdf");

//documento
$btnPDF.addEventListener("click", function () {
  fetch(
    `http://localhost/residencia/server/php/procesos/obtener-info-pnc.php?idProceso=${id_proceso}`
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
          "http://localhost/residencia/server/php/PDFs/producto-no-conforme.php";
        form.target = "_blank"; // Abrir en nueva pestaña

        // Función para crear inputs rápidamente
        function addInput(name, value) {
          const input = document.createElement("input");
          input.type = "hidden";
          input.name = name;
          input.value = value;
          form.appendChild(input);
        }

        const pncEjemplo = [];

        data.data.productosNoConformesIndividuales.forEach((producto) => {
          pncEjemplo.push({
            folio: producto.folio,
            fecha: producto.fecha,
            especificacion: producto.especificacion,
            accion: producto.accion,
            numero: producto.numero,
            eliminar: producto.eliminar === "1",
            imgVerifica:
              producto.idUsuarioVerifica_info?.firmaElectronica || "null.png",
            imgLibera:
              producto.idUsuarioLibera_info?.firmaElectronica || "null.png",
          });
        });

        console.log(pncEjemplo);


        // Añadir los campos necesarios
        addInput("nombre", `${data.data.productoNoConforme.idUsuarioElabora_info.nombreCompleto} ${data.data.productoNoConforme.idUsuarioElabora_info.apellidoPaterno} ${data.data.productoNoConforme.idUsuarioElabora_info.apellidoMaterno}`);
        addInput("nombreValida", `${data.data.productoNoConforme.idUsuarioValida_info.nombreCompleto} ${data.data.productoNoConforme.idUsuarioValida_info.apellidoPaterno} ${data.data.productoNoConforme.idUsuarioValida_info.apellidoMaterno}`);
        addInput("nombreCoordinador", `${data.data.productoNoConforme.idUsuarioCoordinador_info.nombreCompleto} ${data.data.productoNoConforme.idUsuarioCoordinador_info.apellidoPaterno} ${data.data.productoNoConforme.idUsuarioCoordinador_info.apellidoMaterno}`);
        addInput("firma", data.data.productoNoConforme.idUsuarioElabora_info.firmaElectronica);
        addInput("firmaValida", data.data.productoNoConforme.idUsuarioValida_info.firmaElectronica);
        addInput("firmaCoordinador", data.data.productoNoConforme.idUsuarioCoordinador_info.firmaElectronica);
        addInput("pnc", JSON.stringify(pncEjemplo));

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
