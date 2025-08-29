import { get_Id_Proceso, showModal } from "./functions/functions.js";

const urlParams = new URLSearchParams(window.location.search);
const id_proceso = get_Id_Proceso(urlParams);

// Declaramos variables globales
let usuarios = [];

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
      "ac-personal-define",
      "ac-persona-verifica",
      "ac-coordinador-general",
      "ac-responsable-select",
      "ac-select-responsable-accion",
      "ac-usuarios",
      "ac-modificar-responsable-select",
      "ac-modificar-responsable-accion",
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
      `http://localhost/residencia/server/php/procesos/obtener-info-ac.php?idProceso=${id_proceso}`
    );
  })
  .then((response) => {
    if (!response.ok) throw new Error("Error al obtener auditoría");
    return response.json();
  })
  .then((data) => {
    if (data.status !== "success") throw new Error(data.message);

    console.log(data);

    document.getElementById("ac-folio").value = data.data.accionCorrectiva.folio;
    document.getElementById("ac-area-proceso").value = data.data.accionCorrectiva.areaProceso;
    document.getElementById("ac-fecha").value = data.data.accionCorrectiva.fecha;
    document.getElementById("ac-origen-requisito").value = data.data.accionCorrectiva.origenRequisito;
    document.getElementById("ac-fuente-nc").value = data.data.accionCorrectiva.fuenteNC;
    document.getElementById("ac-descripcion").value = data.data.accionCorrectiva.descripcion;
    document.getElementById("ac-personal-define").value = data.data.accionCorrectiva.idDefine;
    document.getElementById("ac-persona-verifica").value = data.data.accionCorrectiva.idVerifica;
    document.getElementById("ac-coordinador-general").value = data.data.accionCorrectiva.idCoordinador;

    data.data.accionCorrectiva.requiereAC 
        ? document.getElementById("ac-accion-si").checked = true 
        : document.getElementById("ac-accion-no").checked = true;
    data.data.accionCorrectiva.requiereCorreccion 
        ? document.getElementById("ac-correccion-si").checked = true 
        : document.getElementById("ac-correccion-no").checked = true;

    document.getElementById("ac-tecnica-estadistica").value = data.data.accionCorrectiva.tecnicaUtilizada;
    document.getElementById("ac-causa-raiz").value = data.data.accionCorrectiva.causaRaizIdentificada;
    document.getElementById("ac-accion-correctiva").value = data.data.accionCorrectiva.ACRealizar;

    data.data.accionCorrectiva.Similares
      ? (document.getElementById("ac-nc-similares-si").checked = true)
      : (document.getElementById("ac-nc-similares-no").checked = true);

    document.getElementById("ac-nc-similares-acciones").value = data.data.accionCorrectiva.ACSimilares;

    data.data.accionCorrectiva.potenciales
      ? (document.getElementById("ac-nc-potenciales-si").checked = true)
      : (document.getElementById("ac-nc-potenciales-no").checked = true);

    document.getElementById("ac-nc-potenciales-acciones").value = data.data.accionCorrectiva.ACPotenciales;
    document.getElementById("ac-seguimiento-evidencias").value = data.data.accionCorrectiva.seguimiento;

    data.data.accionCorrectiva.actualizar
      ? (document.getElementById("ac-riesgos-si").checked = true)
      : (document.getElementById("ac-riesgos-no").checked = true);

    document.getElementById("ac-riesgos-acciones").value = data.data.accionCorrectiva.ACActualizar;

    data.data.accionCorrectiva.cambios
      ? (document.getElementById("ac-cambios-sg-si").checked = true)
      : (document.getElementById("ac-cambios-sg-no").checked = true);

    document.getElementById("ac-cambios-sg-acciones").value = data.data.accionCorrectiva.ACCambios;

    //LLenado de tablas
    data.data.correcciones.forEach((correccion) => {
        const obj = {
          texto: correccion.correccion,
          responsable: `${correccion.idResponsable_info.nombreCompleto} ${correccion.idResponsable_info.apellidoPaterno} ${correccion.idResponsable_info.apellidoMaterno}`,
          idResponsable: correccion.idResponsable,
          fecha: correccion.fecha,
        };

        agregarATabla(obj, document.getElementById("ac-tabla-correcciones"), "correccion");
    });

    data.data.acciones.forEach((accion) => {
      const obj = {
        texto: accion.accion,
        responsable: `${accion.idResponsable_info.nombreCompleto} ${accion.idResponsable_info.apellidoPaterno} ${accion.idResponsable_info.apellidoMaterno}`,
        idResponsable: accion.idResponsable,
        fecha: accion.fecha,
      };

      agregarATabla(
        obj,
        document.getElementById("ac-tabla-acciones"),
        "correccion"
      );
    });

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

    renderTarjetas(data.data.usuarios, "ac-div-usuarios");
})
  .catch((error) => {
    console.error("Error en proceso:", error);
    showModal(false, error.message);
});

//Boton de eliminar de tabla
document.addEventListener("click", function (e) {
    if (e.target.classList.contains("btn-eliminar")) {
      const fila = e.target.closest("tr");
      if (fila) fila.remove();
    }
  });

const $btnValidar = document.getElementById("btn-validar");

//Validar proceso
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

//Eliminar proceso
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

const $acFormModificar = document.getElementById("ac-form-modificar");

//Modificar ac
$acFormModificar.addEventListener("submit", (e) => {
  e.preventDefault();

  const accionCorrectiva = {
    idProceso: id_proceso,
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

  console.log("Objeto a enviar:", accionCorrectiva);

  const formData = new FormData();

  for (const key in accionCorrectiva) {
    if (key === "correcciones" || key === "acciones" || key === "usuarios") {
      formData.append(key, JSON.stringify(accionCorrectiva[key]));
    } else {
      formData.append(key, accionCorrectiva[key]);
    }
  }

  fetch("http://localhost/residencia/server/php/procesos/modificar-ac.php", {
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

//Abrir pdf
const $btnPDF = document.getElementById("btn-pdf");

//documento
$btnPDF.addEventListener("click", function () {
  fetch(
    `http://localhost/residencia/server/php/procesos/obtener-info-ac.php?idProceso=${id_proceso}`
  )
    .then((response) => response.json())
    .then((data) => {
      // console.log(data);
      if (data.status === "success") {
        console.log(JSON.stringify(data));

        // Crear formulario oculto
        const form = document.createElement("form");
        form.method = "POST";
        form.action =
          "http://localhost/residencia/server/php/PDFs/accion-correctiva.php";
        form.target = "_blank"; // Abrir en nueva pestaña

        // Función para crear inputs rápidamente
        function addInput(name, value) {
          const input = document.createElement("input");
          input.type = "hidden";
          input.name = name;
          input.value = value;
          form.appendChild(input);
        }

        // Añadir los campos necesarios

        // Datos básicos del proceso
        addInput("folio", data.data.accionCorrectiva.folio);
        addInput("areaProceso", data.data.accionCorrectiva.areaProceso);
        addInput("fecha", formatearFecha(data.data.accionCorrectiva.fecha));
        addInput("origenRequisito", data.data.accionCorrectiva.origenRequisito);
        addInput("fuenteNC", data.data.accionCorrectiva.fuenteNC);
        addInput("descripcion", data.data.accionCorrectiva.descripcion);

        // IDs de responsables
        addInput("responsableDefinir", data.data.accionCorrectiva.idDefine);
        addInput("responsableVerificar", data.data.accionCorrectiva.idVerifica);

        // Booleans
        addInput("requiereAC", data.data.accionCorrectiva.requiereAC);
        addInput(
          "requiereCorreccion",
          data.data.accionCorrectiva.requiereCorreccion
        );

        // Textos adicionales
        addInput("tecnica", data.data.accionCorrectiva.tecnicaUtilizada);
        addInput("raiz", data.data.accionCorrectiva.causaRaizIdentificada);
        addInput("ACARealizar", data.data.accionCorrectiva.ACRealizar);

        // NC Similares y Potenciales
        addInput("NCSimilares", data.data.accionCorrectiva.Similares);
        addInput("ACSimilares", data.data.accionCorrectiva.ACSimilares);
        addInput("NCPotenciales", data.data.accionCorrectiva.potenciales);
        addInput("ACPotenciales", data.data.accionCorrectiva.ACPotenciales);

        // Plan de acción
        addInput("seguimiento", data.data.accionCorrectiva.seguimiento);
        addInput("actualizar", data.data.accionCorrectiva.actualizar);
        addInput("ACActualizar", data.data.accionCorrectiva.ACActualizar);
        addInput("cambios", data.data.accionCorrectiva.cambios);
        addInput("ACCambios", data.data.accionCorrectiva.ACCambios);

        // Firmas
        addInput(
          "firmaDefine",
          data.data.accionCorrectiva.idDefine_info.firmaElectronica
        );
        addInput(
          "firmaVerifica",
          data.data.accionCorrectiva.idVerifica_info.firmaElectronica
        );
        addInput(
          "firmaCoordinador",
          data.data.accionCorrectiva.idCoordinador_info.firmaElectronica
        );

        // Nombres completos
        addInput(
          "nombreUsuarioDefine",
          `${data.data.accionCorrectiva.idDefine_info.nombreCompleto} ${data.data.accionCorrectiva.idDefine_info.apellidoPaterno} ${data.data.accionCorrectiva.idDefine_info.apellidoMaterno}`
        );
        addInput(
          "nombreUsuarioVerifica",
          `${data.data.accionCorrectiva.idVerifica_info.nombreCompleto} ${data.data.accionCorrectiva.idVerifica_info.apellidoPaterno} ${data.data.accionCorrectiva.idVerifica_info.apellidoMaterno}`
        );
        addInput(
          "nombreUsuarioCoordinador",
          `${data.data.accionCorrectiva.idCoordinador_info.nombreCompleto} ${data.data.accionCorrectiva.idCoordinador_info.apellidoPaterno} ${data.data.accionCorrectiva.idCoordinador_info.apellidoMaterno}`
        );

        //Envio de arrays JSOM
        addInput("correcciones", JSON.stringify(data.data.correcciones));
        addInput("acciones", JSON.stringify(data.data.acciones));

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

//Formatear la fecha
function formatearFecha(fechaStr) {
    const fecha = new Date(fechaStr + "T00:00:00"); // fuerza hora local
    const dia = String(fecha.getDate()).padStart(2, "0");
    const mes = String(fecha.getMonth() + 1).padStart(2, "0");
    const anio = fecha.getFullYear();
    return `${dia}/${mes}/${anio}`;
}