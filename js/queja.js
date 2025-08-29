import { get_Id_Proceso, showModal} from "./functions/functions.js";

    // A. Obtener los parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    
    //Ejecutar función que retorna ID de usuario
    const id_proceso = get_Id_Proceso(urlParams);

// Primero se obtienen los usuarios
fetch('http://localhost/residencia/server/php/usuarios/obtener-id-usuarios.php')
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al obtener los usuarios');
        }
        return response.json();
    })
    .then(data => {
        if (data.status === "success") {
            const usuarios = data.data;
            const select = document.getElementById("subdirector");

            usuarios.forEach(usuario => {
                const option = document.createElement("option");
                option.value = usuario.idUsuario;
                option.textContent = `${usuario.nombreCompleto} ${usuario.apellidoPaterno} ${usuario.apellidoMaterno}`;
                select.appendChild(option);
            });

            // ✅ Segundo fetch: se ejecuta solo después de que las opciones están listas
            return fetch(`http://localhost/residencia/server/php/procesos/obtener-info-queja.php?idProceso=${id_proceso}`);
        } else {
            showModal(false, `Error en la respuesta: ${data.message}`);
            throw new Error(data.message); // Detener la cadena si hubo error
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al obtener la información de la queja');
        }
        return response.json();
    })
    .then(data => {
        if (data.status === "success") {
            const detalle = data.data.detalle;

            document.getElementById('fecha').value = detalle.fecha;
            document.getElementById('folio').value = detalle.folio;
            document.getElementById('nombre').value = detalle.nombre;
            document.getElementById('correo').value = detalle.correoElectronico;
            document.getElementById('telefono').value = detalle.telefono;
            document.getElementById('matricula').value = detalle.matricula;
            document.getElementById('carrera').value = detalle.carrera;
            document.getElementById('semestre').value = detalle.semestre;
            document.getElementById('grupo').value = detalle.grupo;
            document.getElementById('turno').value = detalle.turno;
            document.getElementById('aula').value = detalle.aula;
            document.getElementById('queja').value = detalle.queja;
            document.getElementById('respuesta').value = detalle.respuesta;

            // Asegura que el valor exista antes de asignarlo
            const select = document.getElementById('subdirector');
            const optionExists = Array.from(select.options).some(opt => opt.value == detalle.idSubdirector);
            if (optionExists) {
                select.value = detalle.idSubdirector;
            }
        } else {
            console.error("Error en respuesta:", data.message);
        }
    })
    .catch(error => {
        console.error("Error en la solicitud:", error);
    });


const $formQuejas = document.getElementById("form-quejas");

$formQuejas.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = {
        idProceso : id_proceso,
        fecha: document.getElementById('fecha').value,
        folio: document.getElementById('folio').value,
        nombre: document.getElementById('nombre').value,
        correo: document.getElementById('correo').value,
        telefono: document.getElementById('telefono').value,
        matricula: document.getElementById('matricula').value,
        carrera: document.getElementById('carrera').value,
        semestre: document.getElementById('semestre').value,
        grupo: document.getElementById('grupo').value,
        turno: document.getElementById('turno').value,
        aula: document.getElementById('aula').value,
        queja: document.getElementById('queja').value,
        respuesta: document.getElementById('respuesta').value,
        id_subdirector: document.getElementById('subdirector').value,
    };

    console.log(formData.idProceso);

    try {
        const res = await fetch('http://localhost/residencia/server/php/procesos/modificar-queja.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });
    
        const result = await res.json();
        console.log(result);
    
        if (res.ok && result.ok) {
            showModal(true, "Queja modificada correctamente.");
            // Puedes redirigir o resetear el formulario si lo deseas:
            // window.location.href = "/ruta/deseada";
            // $formQuejas.reset();
        } else {
            showModal(false, `Error: ${result.message}`);
        }
    } catch (error) {
        showModal(false, "Error de red o del servidor. Inténtalo más tarde.");
    }
    
});

const $btnValidar = document.getElementById("btn-validar");
const $btnEliminar = document.getElementById("btn-eliminar");
const $btnPDF = document.getElementById("btn-pdf");

$btnValidar.addEventListener("click", (e) => {
    const idProceso = id_proceso;

    fetch(`http://localhost/residencia/server/php/procesos/activar-proceso.php?idProceso=${idProceso}`)
    .then(res => res.json())
    .then(data => {
        if (data.ok) {
            showModal(true, `El proceso ha sido ${data.data.nuevoEstado == 1 ? 'activado' : 'desactivado'} con éxito.`);
        } else {
            showModal(false, `Error: ${data.message}`);
        }
    })
    .catch(() => showModal(false, "Error de red o servidor."));

})

$btnEliminar.addEventListener("click", (e) => {
    const idProceso = id_proceso;

    fetch(`http://localhost/residencia/server/php/procesos/eliminar-proceso.php?idProceso=${idProceso}`)
    .then(res => res.json())
    .then(data => {
        if (data.ok) {
            showModal(true, "El proceso ha sido eliminado con éxito.");
            // Aquí podrías agregar alguna acción extra, como recargar la lista o redirigir.
        } else {
            showModal(false, `Error: ${data.message}`);
        }
    })
    .catch(() => showModal(false, "Error de red o servidor."));
});


$btnPDF.addEventListener("click", function () {
  fetch(`http://localhost/residencia/server/php/procesos/obtener-info-queja.php?idProceso=${id_proceso}`)
    .then(response => response.json())
    .then(data => {

        // console.log(data);
      if (data.status === "success") {
        const detalle = data.data.detalle;
        const subdirector = data.data.subdirector;
        const receptor = data.data.receptor;

        // Crear formulario oculto
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "http://localhost/residencia/server/php/PDFs/quejas-pdf.php";
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
        addInput("fecha", detalle.fecha);
        addInput("folio", detalle.folio);
        addInput("nombre", detalle.nombre);
        addInput("correo", detalle.correoElectronico);
        addInput("telefono", detalle.telefono);
        addInput("nocontrol", detalle.matricula);
        addInput("carrera", detalle.carrera);
        addInput("semestre", detalle.semestre);
        addInput("grupo", detalle.grupo);
        addInput("turno", detalle.turno);
        addInput("aula", detalle.aula);
        addInput("mensaje", detalle.queja);
        addInput("respuesta", detalle.respuesta);
        addInput("firmaSubdirector", subdirector.firmaElectronica);
        addInput("nombreSubdirector", `${subdirector.nombreCompleto} ${subdirector.apellidoPaterno} ${subdirector.apellidoMaterno}`);
        addInput("firmaEntregado", receptor.firmaElectronica);
        addInput("nombreReceptor", `${receptor.nombreCompleto} ${receptor.apellidoPaterno} ${receptor.apellidoMaterno}`);

        document.body.appendChild(form);
        form.submit();

      } else {
        console.error("Error en respuesta:", data.message);
      }
    })
    .catch(error => {
      console.error("Error de red:", error);
    });
});
