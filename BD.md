# Tablas ya realizadas:

## BD calidad

CREATE DATABASE calidad
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_unicode_ci;

## 1. Tabla usuarios

CREATE TABLE usuarios (
    idUsuario INT(11) NOT NULL AUTO_INCREMENT,
    nombreCompleto VARCHAR(40) NOT NULL,
    apellidoPaterno VARCHAR(40) NOT NULL,
    apellidoMaterno VARCHAR(40) NOT NULL,
    fechaNacimiento DATE DEFAULT NULL,
    telefono VARCHAR(40) DEFAULT NULL,
    correoElectronico VARCHAR(40) NOT NULL UNIQUE,
    numeroTarjeta VARCHAR(40) NOT NULL,
    rol VARCHAR(40) NOT NULL,
    puesto VARCHAR(40) NOT NULL,
    departamento VARCHAR(40) NOT NULL,
    perfil VARCHAR(40) DEFAULT NULL,
    estado BOOLEAN NOT NULL,
    fechaCreacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    usuario VARCHAR(40) NOT NULL UNIQUE,
    contraseña VARCHAR(255) NOT NULL,
    fechaVigencia DATE NOT NULL,
    vigencia VARCHAR(255) DEFAULT NULL,
    firmaElectronica VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (idUsuario)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 2. Tabla contactos

CREATE TABLE contactos (
    idContacto INT(11) NOT NULL AUTO_INCREMENT,
    nombreCompleto VARCHAR(40) NOT NULL,
    apellidoPaterno VARCHAR(40) NOT NULL,
    apellidoMaterno VARCHAR(40) NOT NULL,
    fechaNacimiento DATE DEFAULT NULL,
    telefono VARCHAR(40) DEFAULT NULL,
    correoElectronico VARCHAR(40) NOT NULL UNIQUE,
    numeroTarjeta VARCHAR(40) NOT NULL,
    puesto VARCHAR(40) NOT NULL,
    departamento VARCHAR(40) NOT NULL,
    perfil VARCHAR(40) DEFAULT NULL,
    fechaCreacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (idContacto)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## Modificar
## 3. Tabla Procesos

CREATE TABLE procesos (
    idProceso INT(11) NOT NULL AUTO_INCREMENT,
    tipoProceso VARCHAR(40) NOT NULL,
    fechaCreacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado BOOLEAN NOT NULL DEFAULT TRUE, -- TRUE indica activo, FALSE desactivado
    PRIMARY KEY (idProceso)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 4. Tabla documentosReferencia

CREATE TABLE documentosReferencia (
    idDocumentoReferencia INT(11) NOT NULL AUTO_INCREMENT,
    nombreDocumentoReferencia VARCHAR(255) NOT NULL,
    documentoReferencia VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (idDocumentoReferencia)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 5. Tabla institutos

CREATE TABLE institutos (
    idInstituto INT(11) NOT NULL AUTO_INCREMENT,
    nombreInstituto VARCHAR(255) NOT NULL,
    PRIMARY KEY (idInstituto)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 6. Tabla procesos_usuarios

CREATE TABLE procesos_usuarios (
    idProceso INT(11) NOT NULL,
    idUsuario INT(11) NOT NULL,
    PRIMARY KEY (idProceso, idUsuario),
    FOREIGN KEY (idProceso) REFERENCES procesos(idProceso) ON DELETE CASCADE,
    FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 7. Tabla auditorias

CREATE TABLE auditorias (
    idProceso INT(11) NOT NULL,
    idAuditoria INT(11) NOT NULL AUTO_INCREMENT,
    fechaInicioApertura DATETIME DEFAULT NULL,
    fechaFinalApertura DATETIME DEFAULT NULL,
    areaApertura VARCHAR(40) DEFAULT NULL,
    tipoProceso VARCHAR(40) DEFAULT NULL,
    objetivo TEXT DEFAULT NULL,
    alcance TEXT DEFAULT NULL,
    idAuditorLider INT(11) DEFAULT NULL,
    idRecibe INT(11) DEFAULT NULL,
    fechaInicioCierre DATETIME DEFAULT NULL,
    fechaFinalCierre DATETIME DEFAULT NULL,
    areaCierre VARCHAR(40) DEFAULT NULL,
    fechaEntregaEvidencia DATE DEFAULT NULL,
    numeroAuditoria VARCHAR(40) DEFAULT NULL,
    PRIMARY KEY (idAuditoria),
    FOREIGN KEY (idProceso) REFERENCES procesos(idProceso) ON DELETE CASCADE,
    FOREIGN KEY (idAuditorLider) REFERENCES usuarios(idUsuario) ON DELETE SET NULL,
    FOREIGN KEY (idRecibe) REFERENCES usuarios(idUsuario) ON DELETE SET NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 8. Tabla quejas

CREATE TABLE quejas (
    idQueja INT AUTO_INCREMENT PRIMARY KEY,
    idProceso INT NOT NULL,
    fecha DATE DEFAULT NULL,
    folio VARCHAR(40) DEFAULT NULL,
    nombre VARCHAR(40) DEFAULT NULL,
    correoElectronico VARCHAR(40) NOT NULL,
    telefono VARCHAR(40) DEFAULT NULL,
    matricula VARCHAR(40) DEFAULT NULL,
    carrera VARCHAR(40) DEFAULT NULL,
    semestre INT DEFAULT NULL,
    grupo VARCHAR(40) DEFAULT NULL,
    turno VARCHAR(40) DEFAULT NULL,
    aula VARCHAR(40) DEFAULT NULL,
    queja TEXT DEFAULT NULL,
    respuesta TEXT DEFAULT NULL,
    idSubdirector INT DEFAULT NULL,
    idReceptor INT DEFAULT NULL,

    FOREIGN KEY (idProceso) REFERENCES procesos(idProceso) ON DELETE CASCADE,
    FOREIGN KEY (idSubdirector) REFERENCES usuarios(idUsuario) ON DELETE SET NULL,
    FOREIGN KEY (idReceptor) REFERENCES usuarios(idUsuario) ON DELETE SET NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 9. Tabla usuarios_auditorias

CREATE TABLE usuarios_auditorias (
    idUsuario INT(11) NOT NULL,
    idAuditoria INT(11) NOT NULL,
    PRIMARY KEY (idUsuario, idAuditoria),
    FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario) ON DELETE CASCADE,
    FOREIGN KEY (idAuditoria) REFERENCES auditorias(idAuditoria) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 10. Tabla auditorias_institutos

CREATE TABLE auditorias_institutos (
    idAuditoria INT(11) NOT NULL,
    idInstituto INT(11) NOT NULL,
    PRIMARY KEY (idAuditoria, idInstituto),
    FOREIGN KEY (idAuditoria) REFERENCES auditorias(idAuditoria) ON DELETE CASCADE,
    FOREIGN KEY (idInstituto) REFERENCES institutos(idInstituto) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 11. Tabla actividades

CREATE TABLE actividades (
    idActividad INT(11) NOT NULL AUTO_INCREMENT,
    idAuditoria INT(11) NOT NULL,
    fechaInicio DATETIME DEFAULT NULL,
    fechaFinal DATETIME DEFAULT NULL,
    tipoProceso VARCHAR(40) DEFAULT NULL,
    actividad VARCHAR(100) DEFAULT NULL,
    requisito VARCHAR(100) DEFAULT NULL,
    area VARCHAR(100) DEFAULT NULL,
    
    PRIMARY KEY (idActividad),
    FOREIGN KEY (idAuditoria) REFERENCES auditorias(idAuditoria) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 12. Tabla comentarios

CREATE TABLE comentarios (
    idComentario INT(11) NOT NULL AUTO_INCREMENT,
    idAuditoria INT(11) NOT NULL,
    comentario TEXT DEFAULT NULL,
    
    PRIMARY KEY (idComentario),
    FOREIGN KEY (idAuditoria) REFERENCES auditorias(idAuditoria) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 13. Tabla mejoras

CREATE TABLE mejoras (
    idMejora INT(11) NOT NULL AUTO_INCREMENT,
    idAuditoria INT(11) NOT NULL,
    mejora TEXT DEFAULT NULL,
    
    PRIMARY KEY (idMejora),
    FOREIGN KEY (idAuditoria) REFERENCES auditorias(idAuditoria) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 14. Tabla noConformidades

CREATE TABLE noConformidades (
    idNoConformidad INT(11) NOT NULL AUTO_INCREMENT,
    idAuditoria INT(11) NOT NULL,
    noConformidad TEXT DEFAULT NULL,
    
    PRIMARY KEY (idNoConformidad),
    FOREIGN KEY (idAuditoria) REFERENCES auditorias(idAuditoria) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 15. Tabla conclusiones

CREATE TABLE conclusiones (
    idConclusion INT(11) NOT NULL AUTO_INCREMENT,
    idAuditoria INT(11) NOT NULL,
    conclusion TEXT DEFAULT NULL,
    
    PRIMARY KEY (idConclusion),
    FOREIGN KEY (idAuditoria) REFERENCES auditorias(idAuditoria) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 16. Tabla personalContactado

CREATE TABLE personalContactado (
    idContacto INT(11) NOT NULL,
    idAuditoria INT(11) NOT NULL,
    PRIMARY KEY (idContacto, idAuditoria),
    FOREIGN KEY (idContacto) REFERENCES contactos(idContacto) ON DELETE CASCADE,
    FOREIGN KEY (idAuditoria) REFERENCES auditorias(idAuditoria) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 17. Tabla usuarios_actividades

CREATE TABLE usuarios_actividades (
    idUsuario INT(11) NOT NULL,
    idActividad INT(11) NOT NULL,
    PRIMARY KEY (idUsuario, idActividad),
    FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario) ON DELETE CASCADE,
    FOREIGN KEY (idActividad) REFERENCES actividades(idActividad) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 18. Tabla contactos_actividades

CREATE TABLE contactos_actividades (
    idContacto INT(11) NOT NULL,
    idActividad INT(11) NOT NULL,
    PRIMARY KEY (idContacto, idActividad),
    FOREIGN KEY (idContacto) REFERENCES contactos(idContacto) ON DELETE CASCADE,
    FOREIGN KEY (idActividad) REFERENCES actividades(idActividad) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 19. Tabla productosNoConformes

CREATE TABLE productosNoConformes (
    idProceso INT(11) NOT NULL,
    idProductoNoConforme INT(11) NOT NULL AUTO_INCREMENT,
    idUsuarioElabora INT(11) DEFAULT NULL,
    idUsuarioValida INT(11) DEFAULT NULL,
    idUsuarioCoordinador INT(11) DEFAULT NULL,
    PRIMARY KEY (idProductoNoConforme),
    FOREIGN KEY (idProceso) REFERENCES procesos(idProceso) ON DELETE CASCADE,
    FOREIGN KEY (idUsuarioElabora) REFERENCES usuarios(idUsuario) ON DELETE SET NULL,
    FOREIGN KEY (idUsuarioValida) REFERENCES usuarios(idUsuario) ON DELETE SET NULL,
    FOREIGN KEY (idUsuarioCoordinador) REFERENCES usuarios(idUsuario) ON DELETE SET NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 20. Tabla ListaProductosNoConformes

CREATE TABLE ListaProductosNoConformes (
    idProductoNoConforme INT(11) NOT NULL,
    idProductoNoConformeIndividual INT(11) NOT NULL AUTO_INCREMENT,
    folio VARCHAR(40) DEFAULT NULL,
    fecha DATE DEFAULT NULL,
    especificacion TEXT DEFAULT NULL,
    accion TEXT DEFAULT NULL,
    numero VARCHAR(40) DEFAULT NULL,
    eliminar BOOLEAN NOT NULL DEFAULT TRUE,
    idUsuarioVerifica INT(11) DEFAULT NULL,
    idUsuarioLibera INT(11) DEFAULT NULL,
    PRIMARY KEY (idProductoNoConformeIndividual),
    FOREIGN KEY (idProductoNoConforme) REFERENCES productosNoConformes(idProductoNoConforme) ON DELETE CASCADE,
    FOREIGN KEY (idUsuarioVerifica) REFERENCES usuarios(idUsuario) ON DELETE SET NULL,
    FOREIGN KEY (idUsuarioLibera) REFERENCES usuarios(idUsuario) ON DELETE SET NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 21. Tabla accionesCorrectivas

CREATE TABLE accionesCorrectivas (
    idProceso INT(11) NOT NULL,
    idAccionCorrectiva INT(11) NOT NULL AUTO_INCREMENT,
    folio VARCHAR(40) DEFAULT NULL,
    areaProceso VARCHAR(40) DEFAULT NULL,
    fecha DATE DEFAULT NULL,
    origenRequisito VARCHAR(40) DEFAULT NULL,
    fuenteNC VARCHAR(40) DEFAULT NULL,
    descripcion TEXT DEFAULT NULL,
    idDefine INT(11) DEFAULT NULL,
    idVerifica INT(11) DEFAULT NULL,
    idCoordinador INT(11) DEFAULT NULL,
    requiereAC BOOLEAN NOT NULL DEFAULT TRUE,
    requiereCorreccion BOOLEAN NOT NULL DEFAULT TRUE,
    tecnicaUtilizada TEXT DEFAULT NULL,
    causaRaizIdentificada TEXT DEFAULT NULL,
    ACRealizar TEXT DEFAULT NULL,
    Similares BOOLEAN NOT NULL DEFAULT TRUE,
    ACSimilares TEXT DEFAULT NULL,
    potenciales BOOLEAN NOT NULL DEFAULT TRUE,
    ACPotenciales TEXT DEFAULT NULL,
    seguimiento TEXT DEFAULT NULL,
    actualizar BOOLEAN NOT NULL DEFAULT TRUE,
    ACActualizar TEXT DEFAULT NULL,
    cambios BOOLEAN NOT NULL DEFAULT TRUE,
    ACCambios TEXT DEFAULT NULL,
    PRIMARY KEY (idAccionCorrectiva),
    FOREIGN KEY (idProceso) REFERENCES procesos(idProceso) ON DELETE CASCADE,
    FOREIGN KEY (idDefine) REFERENCES usuarios(idUsuario) ON DELETE SET NULL,
    FOREIGN KEY (idVerifica) REFERENCES usuarios(idUsuario) ON DELETE SET NULL,
    FOREIGN KEY (idCoordinador) REFERENCES usuarios(idUsuario) ON DELETE SET NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 22. Tabla correcciones

CREATE TABLE correcciones (
    idAccionCorrectiva INT(11) NOT NULL,
    idCorreccion INT(11) NOT NULL AUTO_INCREMENT,
    correccion TEXT DEFAULT NULL,
    idResponsable INT(11) DEFAULT NULL,
    fecha DATE DEFAULT NULL,
    PRIMARY KEY (idCorreccion),
    FOREIGN KEY (idAccionCorrectiva) REFERENCES accionesCorrectivas(idAccionCorrectiva) ON DELETE CASCADE,
    FOREIGN KEY (idResponsable) REFERENCES usuarios(idUsuario) ON DELETE SET NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

## 22. Tabla acciones

CREATE TABLE acciones (
    idAccionCorrectiva INT(11) NOT NULL,
    idAccion INT(11) NOT NULL AUTO_INCREMENT,
    accion TEXT DEFAULT NULL,
    idResponsable INT(11) DEFAULT NULL,
    fecha DATE DEFAULT NULL,
    PRIMARY KEY (idAccion),
    FOREIGN KEY (idAccionCorrectiva) REFERENCES accionesCorrectivas(idAccionCorrectiva) ON DELETE CASCADE,
    FOREIGN KEY (idResponsable) REFERENCES usuarios(idUsuario) ON DELETE SET NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;