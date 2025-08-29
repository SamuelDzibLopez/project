import { usuarios, directorio } from "../urls/urls";

//funcion para desplegar modal
export function showModal(isSuccess, message) {
    // Determinar icono y título según el booleano
    const iconSrc = isSuccess 
        ? "/project/sources/icons/icon-success.svg" 
        : "/project/sources/icons/icon-error.svg";

    const title = isSuccess ? "Operación Exitosa" : "Error Detectado";

    // Crear el elemento dialog
    const modal = document.createElement("dialog");
    modal.id = "modal";
    modal.open = true;

    // Insertar el contenido en el modal
    modal.innerHTML = `
        <img src="${iconSrc}" alt="" class="icon-back">
        <h3>${title}</h3>
        <p><b>${isSuccess ? "Éxito:" : "Error:"}</b> ${message}</p>
        <button id="close_modal">Aceptar</button>
    `;

    // Agregar al DOM (body)
    document.body.appendChild(modal);

    // Event Listener para cerrar y eliminar el modal
    modal.querySelector("#close_modal").addEventListener("click", () => {
        modal.remove();
    });
}

//Funcion para obtener id de usuario en URL get
export function get_Id_User (urlParams) {

    // Obtener el valor de 'id_user'
    const idUser = urlParams.get('id_user');

    //Retornar ID de usuario
    return idUser;
}

//Funcion para obtener id de usuario en URL get
export function get_Id_Contact (urlParams) {

    // Obtener el valor de 'id_contact'
    const idContact = urlParams.get('id_contact');

    //Retornar ID de usuario
    return idContact;
}

//Funcion para obtener id de proceso en URL get
export function get_Id_Proceso (urlParams) {

    // Obtener el valor de 'id_proceso'
    const idContact = urlParams.get('id_proceso');

    //Retornar ID de usuario
    return idContact;
}

//Funcion para redirigir a submodulo
export function redirigirAMmodulo (modulo) {
    window.location.href = modulo;
}

//Función para obtener datos de fetch
export async function funcionFetch(url) {

    const response = await fetch(url);
    if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

    return response.json();
}

// Función para crear tarjeta de usuarios usando templates literales en JavaScript
export function createTarjetsUsers(arrayData) {
    // Fragmento para mejorar el rendimiento
    const fragment = document.createDocumentFragment();

    arrayData.forEach((e) => {
        // Usar template literal para crear la estructura HTML
        let userCard = `
            <a href="/project/app/modules/usuario.php?id_user=0${e.idUsuario}" class="div-usuario escalado">
                <div class="user-div-main">
                    <img src="${e.perfil ? `./../server/perfiles/${e.perfil}` : './../sources/imgs/user-Icon.png'}" alt="${e.perfil ? 'Imagen de usuario' : 'Sin imagen disponible'}">
                    <div class="user-name">
                        <h2 class="user-name-name">${e.nombreCompleto} ${e.apellidoPaterno}.</h2>
                        <p class="user-rol">Rol: <span class="user-rol-bold">${e.rol}.</span></p>
                    </div>
                </div>
                <div class="user-data">
                    <p class="user-rol">Fecha de creación:</p>
                    <p class="user-rol"><span class="user-rol-bold">${e.fechaCreacion}.</span></p>
                </div>
            </a>
        `;

        // Convertir el string del template literal a un nodo DOM
        const userElement = new DOMParser().parseFromString(userCard, "text/html").body.firstChild;

        // Agregar el elemento generado al fragmento
        fragment.appendChild(userElement);
    });

    return fragment; // Devolver el fragmento listo para ser insertado en el DOM
}

// Función para crear tarjeta de contactos usando templates literales en JavaScript
export function createTarjetsContacts(arrayData) {
    // Fragmento para mejorar el rendimiento
    const fragment = document.createDocumentFragment();

    arrayData.forEach((e) => {
        // Usar template literal para crear la estructura HTML
        let userCard = `
            <a href="/project/app/modules/contacto.php?id_contact=0${e.idContacto}" class="div-usuario escalado">
                <div class="user-div-main">
                    <img src="${e.perfil ? `./../server/perfiles-contactos/${e.perfil}` : './../sources/imgs/user-Icon.png'}" alt="${e.perfil ? 'Imagen de usuario' : 'Sin imagen disponible'}">
                    <div class="user-name">
                        <h2 class="user-name-name">${e.nombreCompleto} ${e.apellidoPaterno}.</h2>
                        <p class="user-rol">Puesto: <span class="user-rol-bold">${e.puesto}.</span></p>
                    </div>
                </div>
                <div class="user-data">
                    <p class="user-rol">Fecha de creación:</p>
                    <p class="user-rol"><span class="user-rol-bold">${e.fechaCreacion}.</span></p>
                </div>
            </a>
        `;

        // Convertir el string del template literal a un nodo DOM
        const userElement = new DOMParser().parseFromString(userCard, "text/html").body.firstChild;

        // Agregar el elemento generado al fragmento
        fragment.appendChild(userElement);
    });

    return fragment; // Devolver el fragmento listo para ser insertado en el DOM
}

// Función para crear tarjeta de procesos usando templates literales en JavaScript
export function createTarjetsProcesos(arrayData) {
    // Fragmento para mejorar el rendimiento
    const fragment = document.createDocumentFragment();

    arrayData.forEach((e) => {
        // Usar template literal para crear la estructura HTML
        let ruta = '';

        if (e.tipoProceso.toLowerCase() === 'auditoría') {
            ruta = '/residencia/app/modules/auditoria.php';
        } else if (e.tipoProceso.toLowerCase() === "queja o sugerencia") {
            ruta = "/residencia/app/modules/queja.php";
        } else if (e.tipoProceso.toLowerCase() === "producto no conforme") {
            ruta = "/residencia/app/modules/pnc.php";
        } else if (e.tipoProceso.toLowerCase() === "accion correctiva") {
            ruta = "/residencia/app/modules/ac.php";
        } else {
          ruta = "/residencia/app/modules/proceso.php"; // Ruta por defecto
        }

        let userCard = `
            <a href="${ruta}?id_proceso=${e.idProceso}" class="div-usuario escalado">
                <div class="user-div-main">
                    <img src="./../sources/icons/icon-proyectos.svg" alt="">
                    <div class="user-name">
                        <h2 class="user-name-name">${e.folio}.</h2>
                        <p class="user-rol">Tipo: <span class="user-rol-bold">${e.tipoProceso}.</span></p>
                    </div>
                </div>
                <div class="user-data">
                    <p class="user-rol">Fecha de creación:</p>
                    <p class="user-rol"><span class="user-rol-bold">${e.fechaCreacion}.</span></p>
                </div>
            </a>
        `;


        // Convertir el string del template literal a un nodo DOM
        const userElement = new DOMParser().parseFromString(userCard, "text/html").body.firstChild;

        // Agregar el elemento generado al fragmento
        fragment.appendChild(userElement);
    });

    return fragment; // Devolver el fragmento listo para ser insertado en el DOM
}

//Funcion para desplegar usuarios
export async function fetchAndRenderUsers(stateUsuarios=1, $btnUsers, $divUsers, url, subsection="usuarios") {

    //Try-Catch
    try {
        //URL de fetch
        const URL = `${url}${stateUsuarios}`;
        const data = await funcionFetch(URL);

        console.log(data);

        //Si la peticion es exitosa y con contenido
        if (data.statusCode === 200) {
            // Limpiar antes de agregar nuevas tarjetas

            //Si es primera vez de fetch
            if (stateUsuarios == 1) {
                //Limpiar div princicpal
                $divUsers.innerHTML = "";
            }

            if (subsection=="usuarios") {
                $divUsers.appendChild(createTarjetsUsers(data.data));
            } else if (subsection=="contactos") {
                $divUsers.appendChild(createTarjetsContacts(data.data));
            } else if (subsection=="procesos") {
                $divUsers.appendChild(createTarjetsProcesos(data.data));
            } else {
                alert("Error al intentar mostrar tarjetas");
            }

        //Si no se encuentran mas usuarios
        } else if (data.statusCode === 404) {
            // showModal(false, data.message);
            $btnUsers.disabled = true;
        }
    } catch (error) {
        // console.error("Error al obtener usuarios:", error);
        alert("Ocurrio un error, intentelo mas tarde");
    } finally {
    }
}

//Funcion para ingresar datos default en form
export function addFormUser($form, data) {
    // console.log($form);
    // console.log(data);

    $form.user.value = data.usuario;

    $form.nombre_completo.value = data.nombreCompleto;
    $form.apellido_paterno.value = data.apellidoPaterno;
    $form.apellido_materno.value = data.apellidoMaterno;
    $form.fecha_nacimiento.value = data.fechaNacimiento;
    $form.telefono.value = data.telefono;
    $form.correo_electronico.value = data.correoElectronico;
    $form.numero_tarjeta.value = data.numeroTarjeta;
    $form.rol.value = data.rol;
    $form.puesto.value = data.puesto;
    $form.departamento.value = data.departamento;
    $form.fecha_vigencia.value = data.fechaVigencia;

    const $a_vigencia = document.getElementById("text_vigencia");
    const $p_firma = document.getElementById("text_firma");
    
    $a_vigencia.textContent = data.vigencia 
    ? data.vigencia 
    : 'Sin archivo de vigencia';

    $a_vigencia.href = data.vigencia 
    ? `/project/server/vigencias/${data.vigencia}` 
    : '#';

    $a_vigencia.target = data.vigencia 
    ? '_blank' 
    : '';

    $form.img_perfil.src = data.perfil 
    ? `/project/server/perfiles/${data.perfil}` 
    : '/project/sources/imgs/user-Icon.png';

    $form.archivo_firma_e.src = data.firmaElectronica 
    ? `/project/server/firmas/${data.firmaElectronica}` 
    : '';

    $p_firma.textContent = data.firmaElectronica 
    ? "" 
    : 'Sin firma electronica';
}

//Funcion para ingresar datos default en form
export function addFormContact($form, data) {

    $form.nombre_completo.value = data.nombreCompleto;
    $form.apellido_paterno.value = data.apellidoPaterno;
    $form.apellido_materno.value = data.apellidoMaterno;
    $form.fecha_nacimiento.value = data.fechaNacimiento;
    $form.telefono.value = data.telefono;
    $form.correo_electronico.value = data.correoElectronico;
    $form.numero_tarjeta.value = data.numeroTarjeta;
    $form.puesto.value = data.puesto;
    $form.departamento.value = data.departamento;

    $form.img_perfil.src = data.perfil 
    ? `/project/server/perfiles-contactos/${data.perfil}` 
    : '/project/sources/imgs/user-Icon.png';
}

//Funcion para desplegar usuarios
export async function fetchAndGetUser($form, url) {

    //Try-Catch
    try {
        
        //URL de fetch
        const data = await funcionFetch(url);

        //Si la peticion es exitosa y con contenido

        console.log(data);

        //Si la peticion es exitosa y con contenido
        if (data.statusCode === 200) {
            // Limpiar antes de agregar nuevas tarjetas
            addFormUser($form, data.data);
        //Si no se encuentran mas usuarios
        } else {
            // showModal(false, data.message);
        }

    } catch (error) {
        // console.error("Error al obtener usuarios:", error);
        alert("Ocurrio un error, intentelo mas tarde");
        window.location.href = usuarios;
    } finally {
    }
}

//Funcion para desplegar usuarios
export async function fetchAndGetContact($form, url) {

    //Try-Catch
    try {
        
        //URL de fetch
        const data = await funcionFetch(url);

        //Si la peticion es exitosa y con contenido

        // console.log(data);

        //Si la peticion es exitosa y con contenido
        if (data.statusCode === 200) {
            // Limpiar antes de agregar nuevas tarjetas
            addFormContact($form, data.data);
        //Si no se encuentran mas usuarios
        } else {
            // showModal(false, data.message);
        }

    } catch (error) {
        // console.error("Error al obtener usuarios:", error);
        alert("Ocurrio un error, intentelo mas tarde");
        window.location.href = directorio;
    } finally {
    }
}