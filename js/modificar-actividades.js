import { obtenerInfoTarjetas } from "./submit-auditoria.js";
import { showModal } from "./functions/functions";

//Boton para agregar mejora
const $agregarMejora = document.getElementById("agregarMejora");
//Tabla de mejoras
const $tablaMejoras = document.getElementById("mejoras");

//Boton para agrefar comentario
const $agregarComentario = document.getElementById("agregarComentario");
//Tabla de comentarios
const $tablaComentarios = document.getElementById("comentarios");

//Boton para agrefar noConformidades
const $agregarNoConformidades = document.getElementById("agregarNoConformidad");
//Tabla de noConformidades
const $tablaNoConformidades = document.getElementById("noConformidades");

//Boton para agrefar conclusiones
const $agregarConclusiones = document.getElementById("agregarConclusiones");
//Tabla de noConformidades
const $tablaConclusiones = document.getElementById("conclusiones");

// Si se da click en modificar una actividad
document.addEventListener("click", function (e) {
  if (e.target.classList.contains("btn-modificar")) {
    // Buscar el contenedor que tiene el atributo data-info
    const padre = e.target.closest("[data-info]");
    const dataInfo = padre.getAttribute("data-info");

    try {
      const objeto = JSON.parse(dataInfo);
    //   console.log(objeto); // Ya es un objeto JS

      // Mostrar el modal
      document.getElementById("modalActividad").style.display = "block";

      // Llenar campos del modal
      document.getElementById("modalInicioActividad").value = objeto.inicio;
      document.getElementById("modalFinActividad").value = objeto.fin;
      document.getElementById("modalTipoProceso").value = objeto.tipoProceso;
      document.getElementById("modalActividadTexto").value = objeto.texto;
      document.getElementById("modalRequisitoCriterio").value =
        objeto.requisitoCriterio;
      document.getElementById("modalAreaSitioActividad").value =
        objeto.areaSitio;

        //Agregar oportunidades de mejora si contiene
        obtenerSubtablas(objeto.oportunidadesMejora, $tablaMejoras, 1);
        obtenerSubtablas(objeto.comentarios, $tablaComentarios, 2);
        obtenerSubtablas(objeto.noConformidades, $tablaNoConformidades, 3);
        obtenerSubtablas(objeto.conclusiones, $tablaConclusiones, 4);

      // Renderizar tarjetas de participantes
      fetch(
        "http://localhost/residencia/server/php/usuarios/obtener-id-usuarios.php"
      )
        .then((response) => {
          if (!response.ok) throw new Error("Error al obtener los usuarios");
          return response.json();
        })
        .then((data) => {
          const contenedor = document.getElementById(
            "divParticipantesActividad2"
          );
          contenedor.innerHTML = "";

          objeto.participantes.forEach((idParticipante) => {
            const usuario = data.data.find(
              (u) => u.idUsuario === idParticipante
            );
            if (usuario) {
              const $Tarjeta = document.createElement("div");
              $Tarjeta.classList.add("tarjet");
              $Tarjeta.setAttribute("data-value", usuario.idUsuario);

              const $P = document.createElement("p");
              $P.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;

              const $Boton = document.createElement("button");
              $Boton.type = "button";
              $Boton.textContent = "X";
              $Boton.classList.add("btn-closed");
              $Boton.onclick = () => $Tarjeta.remove();

              $Tarjeta.appendChild($P);
              $Tarjeta.appendChild($Boton);
              contenedor.appendChild($Tarjeta);
            }
          });
        })
        .catch((error) =>
          showModal(false, "Error en la solicitud: " + error.message)
        );

      // Renderizar tarjetas de contactos
      fetch(
        "http://localhost/residencia/server/php/contactos/obtener-id-contactos.php"
      )
        .then((response) => {
          if (!response.ok) throw new Error("Error al obtener los contactos");
          return response.json();
        })
        .then((data) => {
          const contenedor = document.getElementById("divContactosActividad2");
          contenedor.innerHTML = "";

          objeto.contactos.forEach((idContacto) => {
            const contacto = data.data.find((c) => c.idContacto === idContacto);
            if (contacto) {
              const $Tarjeta = document.createElement("div");
              $Tarjeta.classList.add("tarjet");
              $Tarjeta.setAttribute("data-value", contacto.idContacto);

              const $P = document.createElement("p");
              $P.textContent = `${contacto.nombreCompleto} ${contacto.apellidoPaterno} ${contacto.apellidoMaterno}`;

              const $Boton = document.createElement("button");
              $Boton.type = "button";
              $Boton.textContent = "X";
              $Boton.classList.add("btn-closed");
              $Boton.onclick = () => $Tarjeta.remove();

              $Tarjeta.appendChild($P);
              $Tarjeta.appendChild($Boton);
              contenedor.appendChild($Tarjeta);
            }
          });
        })
        .catch((error) =>
          showModal(false, "Error en la solicitud: " + error.message)
        );

      // Guardar cambios al dar clic
      const btnGuardar = document.getElementById("modifcar-actividad");
      btnGuardar.onclick = function () {
        // Actualiza los valores del objeto
        objeto.inicio = document.getElementById("modalInicioActividad").value;
        objeto.fin = document.getElementById("modalFinActividad").value;
        objeto.tipoProceso = document.getElementById("modalTipoProceso").value;
        objeto.texto = document.getElementById("modalActividadTexto").value;
        objeto.requisitoCriterio = document.getElementById(
          "modalRequisitoCriterio"
        ).value;
        objeto.areaSitio = document.getElementById(
          "modalAreaSitioActividad"
        ).value;
        objeto.participantes = obtenerInfoTarjetas(divParticipantesActividad2);
        objeto.contactos = obtenerInfoTarjetas(divContactosActividad2);

        objeto.oportunidadesMejora = obtenerObjetosDeTabla($tablaMejoras);
        objeto.comentarios = obtenerObjetosDeTabla($tablaComentarios);
        objeto.noConformidades = obtenerObjetosDeTabla($tablaNoConformidades);
        objeto.conclusiones = obtenerObjetosDeTabla($tablaConclusiones);

        // Actualizar el atributo data-info del elemento padre
        padre.setAttribute("data-info", JSON.stringify(objeto));

        const celdas = padre.children;
        celdas[0].textContent = `${formatearFechaHora(
          objeto.inicio
        )} a ${formatearFechaHora(objeto.fin)}`;
        celdas[1].textContent = objeto.tipoProceso;
        celdas[2].textContent = objeto.texto;
        celdas[3].textContent = objeto.requisitoCriterio;

        // console.log("Nuevo objeto actualizado:", objeto);
        // console.log("Nuevo data-info:", padre.getAttribute("data-info"));

        // Cerrar el modal
        document.getElementById("modalActividad").style.display = "none";
      };
    } catch (error) {
      console.error("Error al parsear data-info:", error);
    }
  }
});

//formatear fecha
function formatearFechaHora(fechaStr) {
  const fecha = new Date(fechaStr);
  return fecha.toLocaleString("es-MX", {
    day: "2-digit",
    month: "2-digit",
    year: "2-digit",
    hour: "numeric",
    minute: "2-digit",
    hour12: true,
  });
}

//Agregar mejora
$agregarMejora.addEventListener("click", (e) => {
    let text = document.getElementById("modalOportunidadMejora").value;
    agregarEnSubtabla(text, $tablaMejoras, 1);

    document.getElementById("modalOportunidadMejora").value = "";
});

//Agregar comentario
$agregarComentario.addEventListener("click", (e) => {
    let text = document.getElementById("modalComentarios").value;
    agregarEnSubtabla(text, $tablaComentarios, 2);

    document.getElementById("modalComentarios").value = "";
});

//Agregar no conformidad
$agregarNoConformidades.addEventListener("click", (e) => {
  let text = document.getElementById("modalNoConformidades").value;
  agregarEnSubtabla(text, $tablaNoConformidades, 3);

  document.getElementById("modalNoConformidades").value = "";
});

//Agregar no conclusiones
$agregarConclusiones.addEventListener("click", (e) => {
    let text = document.getElementById("modalConclusiones").value;
    agregarEnSubtabla(text, $tablaConclusiones, 4);

    document.getElementById("modalConclusiones").value = "";
});

//Agregar filas de tablas
function agregarEnSubtabla(text, $table, tipotabla) {
    // Obtener el tbody de la tabla
    const tbody = $table.querySelector("tbody");
  
    // Crear la fila
    const tr = document.createElement("tr");
  
    // Definir el objeto con atributos según el tipo
    let data = { texto: text };
  
    if (tipotabla === 3) {
      // Si es no conformidad, agrega más campos vacíos
      data = {
        texto: text,
        causa: "",
        evidencia: "",
        tipoNoConformidad: "",
        accion: ""
      };
    }
  
    // Guardar el objeto en data-info como JSON
    tr.setAttribute("data-info", JSON.stringify(data));
  
    // Crear y agregar la celda con el texto
    const tdTexto = document.createElement("td");
    tdTexto.textContent = text;
    tr.appendChild(tdTexto);
  
    // Crear y agregar la celda con los botones
    const tdOpciones = document.createElement("td");
  
    const btnModificar = document.createElement("button");
    btnModificar.type = "button";
    btnModificar.textContent = "Modificar";
    btnModificar.classList.add("btn-modificar-actividad", "escalado");
  
    // Agregar clase extra según el tipo
    switch (tipotabla) {
      case 1:
        btnModificar.classList.add("btn-modificar-mejora");
        break;
      case 2:
        btnModificar.classList.add("btn-modificar-comentario");
        break;
      case 3:
        btnModificar.classList.add("btn-modificar-noConformidad");
        break;
      case 4:
        btnModificar.classList.add("btn-modificar-conclusion");
        break;
    }
  
    const btnEliminar = document.createElement("button");
    btnEliminar.type = "button";
    btnEliminar.textContent = "Eliminar";
    btnEliminar.classList.add("btn", "btn-eliminar", "escalado");
  
    // Agregar los botones al td
    tdOpciones.appendChild(btnModificar);
    tdOpciones.appendChild(btnEliminar);
  
    // Agregar td de botones a la fila
    tr.appendChild(tdOpciones);
  
    // Agregar la fila al tbody
    tbody.appendChild(tr);
}

//Crear modal de mejora, conclusion, no conformidad y comentario
function crearModal(titulo, texto, tr) {
  // Crear elementos del modal
  const modal = document.createElement("div");
  modal.classList.add("modal-backdrop");

  const contenido = document.createElement("div");
  contenido.classList.add("modal-content");

  const encabezado = document.createElement("h2");
  encabezado.textContent = titulo;
  encabezado.classList.add("modal-title");

  const textarea = document.createElement("textarea");
  textarea.classList.add("modal-textarea");
  textarea.value = texto || "";

  const botonesWrapper = document.createElement("div");
  botonesWrapper.classList.add("modal-buttons");

  const btnCerrar = document.createElement("button");
  btnCerrar.textContent = "Cerrar";
  btnCerrar.classList.add("btn", "btn-cerrar");

  const btnGuardar = document.createElement("button");
  btnGuardar.textContent = "Guardar";
  btnGuardar.classList.add("btn", "btn-guardar");

  // Eventos botones
  btnCerrar.addEventListener("click", () => {
    document.body.removeChild(modal);
  });

  btnGuardar.addEventListener("click", () => {
    // Actualizar el objeto data-info en el tr
    try {
      const objeto = JSON.parse(tr.getAttribute("data-info"));
      objeto.texto = textarea.value;
      tr.setAttribute("data-info", JSON.stringify(objeto));

      // También actualizamos el texto visible en la celda
      const tdTexto = tr.querySelector("td:first-child");
      if (tdTexto) tdTexto.textContent = textarea.value;

      console.log("Texto guardado y actualizado:", textarea.value);
    } catch (error) {
      console.error("Error al actualizar data-info:", error);
    }

    document.body.removeChild(modal);
  });

  // Construir modal
  botonesWrapper.appendChild(btnCerrar);
  botonesWrapper.appendChild(btnGuardar);

  contenido.appendChild(encabezado);
  contenido.appendChild(textarea);
  contenido.appendChild(botonesWrapper);

  modal.appendChild(contenido);
  document.body.appendChild(modal);
}

//Modificar mejora, conclusion, no conformidad y comentario
document.addEventListener("click", function (e) {
  if (e.target.classList.contains("btn-modificar-mejora")) {
    const tr = e.target.closest("tr");
    const dataInfo = tr.getAttribute("data-info");
    try {
      const objeto = JSON.parse(dataInfo);
      crearModal("Modificar oportunidad de mejora", objeto.texto, tr);
    } catch (error) {
      console.error("Error en MEJORA:", error);
    }
  }

  if (e.target.classList.contains("btn-modificar-comentario")) {
    const tr = e.target.closest("tr");
    const dataInfo = tr.getAttribute("data-info");
    try {
      const objeto = JSON.parse(dataInfo);
      crearModal("Modificar comentario", objeto.texto, tr);
    } catch (error) {
      console.error("Error en COMENTARIO:", error);
    }
  }

  if (e.target.classList.contains("btn-modificar-noConformidad")) {
    const tr = e.target.closest("tr");
    const dataInfo = tr.getAttribute("data-info");
    try {
      const objeto = JSON.parse(dataInfo);
      crearModal("Modificar NO CONFORMIDAD", objeto.texto, tr);
    } catch (error) {
      console.error("Error en NO CONFORMIDAD:", error);
    }
  }

  if (e.target.classList.contains("btn-modificar-conclusion")) {
    const tr = e.target.closest("tr");
    const dataInfo = tr.getAttribute("data-info");
    try {
      const objeto = JSON.parse(dataInfo);
      crearModal("Modificar CONCLUSIÓN", objeto.texto, tr);
    } catch (error) {
      console.error("Error en CONCLUSIÓN:", error);
    }
  }
});

//Obtener los objetos data-info
export function obtenerObjetosDeTabla(tabla) {
  const resultados = [];

  // Seleccionamos todas las filas <tr> dentro de la tabla (thead + tbody)
  const filas = tabla.querySelectorAll("tr[data-info]");

  filas.forEach((tr) => {
    const dataInfo = tr.getAttribute("data-info");
    try {
      // Parseamos el JSON y lo agregamos al array
      const objeto = JSON.parse(dataInfo);
      resultados.push(objeto);
    } catch (error) {
      console.error("Error al parsear data-info en una fila:", error, dataInfo);
    }
  });

  return resultados;
}

function obtenerSubtablas(arrayDatos, $tabla, tipoTabla) {
  if (!$tabla) {
    console.warn(`Tabla no encontrada.`);
    return;
  }

  // Obtener el tbody y limpiar su contenido
  const tbody = $tabla.querySelector("tbody");
  if (tbody) {
    tbody.innerHTML = ""; // Elimina todas las filas
  }

  // Si el array está vacío, no hacemos nada más
  if (!arrayDatos || arrayDatos.length === 0) return;

  // Insertar los datos si existen
  arrayDatos.forEach(({ texto }) => {
    agregarEnSubtabla(texto, $tabla, tipoTabla);
  });
}

