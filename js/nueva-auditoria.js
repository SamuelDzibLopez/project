//A. Importación de modulos
import { url_auditorias_create_auditoria, url_usuarios_obtener_id_usuarios, url_contactos_obtener_id_contactos } from "./urls/urls";

//B. Definicion de elementos
const $formAuditoria = document.getElementById("auditoria");
let idSelectsUsuarios = ["idElabora", "idValida", "idCoordinador", "idRecibe", "auditoresLideres", "auditor", "participantesActividad", "noConformidadIdVerifica", "noConformidadIdLibera", "usuarios"];
let idSelectsContactos = ["contactosActividad", "participantes", ""];
  //C. Funcionamiento de pagina

  // Función para poblar varios selects con usuarios
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
        select.innerHTML = `<option value="" disabled selected>Seleccione un usuario</option>`;

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

  // Función para poblar varios selects con contactos
  async function cargarContactosEnSelects(idsSelects, url) {
    try {
      const response = await fetch(url);
      const { status, data, message } = await response.json();

      if (status !== "success" || !Array.isArray(data)) {
        console.error("Error al obtener contactos:", message);
        return;
      }

      idsSelects.forEach(id => {
        const select = document.getElementById(id);
        if (!select) return;

        // Limpiar y agregar placeholder
        select.innerHTML = `<option value="" disabled selected>Seleccione un contacto</option>`;

        // Insertar contactos
        data.forEach(({ idContacto, nombreCompleto, apellidoPaterno, apellidoMaterno }) => {
          const option = document.createElement("option");
          option.value = idContacto;
          option.textContent = `${nombreCompleto} ${apellidoPaterno} ${apellidoMaterno}`;
          select.appendChild(option);
        });
      });
    } catch (error) {
      console.error("Error al cargar contactos:", error);
    }
  }

await cargarUsuariosEnSelects(idSelectsUsuarios, url_usuarios_obtener_id_usuarios);

await cargarContactosEnSelects(idSelectsContactos, url_contactos_obtener_id_contactos);



//Submit de creacion de auditoria
$formAuditoria.addEventListener("submit", async (e) => {
  console.log("Creando auditoría");

  e.preventDefault();

  const data = {
    tipoProceso: "Auditoría Interna",
    folioProceso: "2025-001",
    estadoProceso: 1,
    auditoriaData: {
      numAuditoria: 1001,
      proceso: "Proceso de Auditoría 2025",
      fecha: "2025-07-01",
      documentosReferencia: "Documento X, Documento Y",
      objetivo: "Verificar cumplimiento",
      alcance: "Área administrativa",
      fechaEmision: "2025-07-02",
      ciudadInicioApertura: "Mérida",
      fechaInicioApertura: "2025-07-01 09:00:00",
      lugarInicioApertura: "Sala 101",
      fechaFinalApertura: "2025-07-01 12:00:00",
      ciudadInicioCierre: "Mérida",
      fechaInicioCierre: "2025-07-02 09:00:00",
      lugarInicioCierre: "Sala 102",
      fechaFinalCierre: "2025-07-02 12:00:00",
      fechaEntregaEvidencia: "2025-07-03",
      idElabora: 1,
      idValida: 2,
      idCoordinador: 3,
      idRecibe: 4,
    },
    usuariosProceso: [1, 2, 3],
    actividades: [
      {
        horarioInicial: "2025-07-01 09:00:00",
        horarioFinal: "2025-07-01 12:00:00",
        proceso: "Académico",
        actividad: "Actividad 1",
        requisito: "1.1",
        area: "Sala 1",
        participantes: [1, 2],
        contactados: [1, 2],
      },
    ],
    institutos: [1, 2],
    personalContactado: [1, 2, 3, 4],
    auditores: [1, 2, 3, 4],
    auditoresLideres: [1, 2],
    oportunidades: [{ oportunidad: "Mejorar proceso de documentación 1" }],
    comentarios: [{ comentario: "Comentario inicial sobre auditoría 1" }],
    conclusiones: [{ conclusion: "Conclusión de auditoría satisfactoria 1" }],
    noConformidades: [
      {
        descripcion: "Falta de control en documentación 1",
        requisito: "ISO 9001:2015",
        folio: "2025-001/01",
        fecha: "2025-07-02",
        accion: "",
        numRAC: "RAC-001/01",
        estado: 1,
        idVerifica: 2,
        idLibera: 3,
      },
    ],
  };

  try {
    const response = await fetch(url_auditorias_create_auditoria, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });

    if (!response.ok) {
      throw new Error(`Error HTTP: ${response.status}`);
    }

    const result = await response.json();
    console.log("Respuesta del servidor:", result);
    alert(result.message);
  } catch (error) {
    console.error("Error al crear la auditoría:", error);
    alert(result.message);
  }
});
