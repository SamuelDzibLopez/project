const $formAuditoria = document.getElementById("form-auditoria");

$formAuditoria.addEventListener("submit", async (e) => {
  e.preventDefault();

  // Institutos
  const institutoNorte = document.getElementById("institutoNorte").checked;
  const institutoPoniente =
    document.getElementById("institutoPoniente").checked;

  // Información general
  const objetivoGeneral = document.getElementById("objetivoGeneral").value;
  const alcanceGeneral = document.getElementById("alcanceGeneral").value;
  // const inicioGeneral = document.getElementById("inicioGeneral").value;
  // const finGeneral = document.getElementById("finGeneral").value;

  // Participantes
  const $divTarjetaParticipantes = document.getElementById(
    "divParticipantesAuditoria"
  );
  const participantes = obtenerInfoTarjetas($divTarjetaParticipantes);
  const auditorLider = document.getElementById("auditorLider").value;

  // Actividades
  const inicioApertura = document.getElementById("inicioApertura").value;
  const finApertura = document.getElementById("finApertura").value;
  const areaApertura = document.getElementById("areaApertura").value;

  const inicioCierre = document.getElementById("inicioCierre").value;
  const finCierre = document.getElementById("finCierre").value;
  const areaCierre = document.getElementById("areaCierre").value;

  const entregaEvidencia = document.getElementById("entregaEvidencia").value;

  // Acceso usuarios
  const $divTarjetaUsuarios = document.getElementById("divParticipantesAcceso");
  const usuarios = obtenerInfoTarjetas($divTarjetaUsuarios);

  const $TablaAct = document.getElementById("tabla-actividades");
  const actividades = obtenerObjetosDeTabla($TablaAct);

  // Crear FormData
  const formData = new FormData();
  formData.append("objetivo", objetivoGeneral);
  formData.append("alcance", alcanceGeneral);
  formData.append("inicioApertura", inicioApertura);
  formData.append("finApertura", finApertura);
  formData.append("areaApertura", areaApertura);
  formData.append("inicioCierre", inicioCierre);
  formData.append("finCierre", finCierre);
  formData.append("areaCierre", areaCierre);
  formData.append("entregaEvidencia", entregaEvidencia);
  formData.append("idAuditorLider", auditorLider);
  // formData.append("inicioGeneral", inicioGeneral);
  // formData.append("finGeneral", finGeneral);
  formData.append("institutoNorte", institutoNorte);
  formData.append("institutoPoniente", institutoPoniente);
  formData.append("usuarios", JSON.stringify(usuarios));
  formData.append("participantes", JSON.stringify(participantes));
  formData.append("actividades", JSON.stringify(actividades));

  // Imprimir todos los valores del formData en forma de objeto para debug
  const debugData = {};
  for (let [key, value] of formData.entries()) {
    try {
      debugData[key] = JSON.parse(value);
    } catch {
      debugData[key] = value;
    }
  }

  console.log(obtenerObjetosDeTabla($TablaAct));

  console.log("Datos del formulario a enviar:", debugData);

  // Enviar a servidor (descomenta si quieres probar)
  /*
    try {
      const response = await fetch("http://localhost/residencia/server/php/procesos/create-auditoria.php", {
        method: "POST",
        body: formData
      });
  
      const result = await response.json();
  
      if (result.status === "success") {
        alert("Auditoría guardada correctamente.");
        console.log(result.data);
      } else {
        alert("Error: " + result.message);
      }
    } catch (error) {
      console.error("Error al guardar la auditoría:", error);
      alert("Error al enviar los datos al servidor.");
    }
    */
});

export function obtenerInfoTarjetas($divTarjetas) {
    // Obtener todos los elementos con clase "tarjet" dentro de $divTarjetas
    const tarjets = $divTarjetas.querySelectorAll('.tarjet');

    // Crear un array para almacenar los valores
    const valores = [];

    // Recorrer cada elemento .tarjet
    tarjets.forEach(tarjet => {
        const valor = tarjet.getAttribute('data-value');
        valores.push(valor);
    });

    // Retornar el array de valores
    return valores;
}

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