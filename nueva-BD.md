CREATE DATABASE project
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_unicode_ci;

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

CREATE TABLE institutos (
	idInstituto INT NOT NULL AUTO_INCREMENT,
	instituto VARCHAR(100) NOT NULL,
	domicilio VARCHAR(255) DEFAULT NULL,
	PRIMARY KEY (idInstituto)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE procesos (
	idProceso INT NOT NULL AUTO_INCREMENT,
	tipoProceso VARCHAR(100) NOT NULL,
	folio VARCHAR(100) NOT NULL UNIQUE,
	fechaCreacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	estado BOOLEAN NOT NULL DEFAULT 1,
	PRIMARY KEY (idProceso)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE auditorias (
  idAuditoria INT NOT NULL AUTO_INCREMENT,
  idProceso INT NOT NULL,
  numAuditoria INT NOT NULL,
  proceso VARCHAR(100) DEFAULT NULL,
  fecha DATE DEFAULT NULL,
  documentosReferencia TEXT DEFAULT NULL,
  objetivo TEXT DEFAULT NULL,
  alcance TEXT DEFAULT NULL,
  fechaEmision DATE,

  ciudadInicioApertura VARCHAR(100) DEFAULT NULL,
  fechaInicioApertura DATETIME DEFAULT NULL,
  lugarInicioApertura VARCHAR(100) DEFAULT NULL,
  fechaFinalApertura DATETIME DEFAULT NULL,

  ciudadInicioCierre VARCHAR(100) DEFAULT NULL,
  fechaInicioCierre DATETIME DEFAULT NULL,
  lugarInicioCierre VARCHAR(100) DEFAULT NULL,
  fechaFinalCierre DATETIME DEFAULT NULL, 
  fechaEntregaEvidencia DATE DEFAULT NULL,

  idElabora INT,
  idValida INT,
  idCoordinador INT,
  idRecibe INT,

  PRIMARY KEY (idAuditoria),

  FOREIGN KEY (idProceso) REFERENCES procesos(idProceso) ON DELETE CASCADE ON UPDATE CASCADE,
  
  FOREIGN KEY (idElabora) REFERENCES usuarios(idUsuario) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (idValida) REFERENCES usuarios(idUsuario) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (idCoordinador) REFERENCES usuarios(idUsuario) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (idRecibe) REFERENCES usuarios(idUsuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE auditorias_institutos (
  idAuditoria INT NOT NULL,
  idInstituto INT NOT NULL,
  PRIMARY KEY (idAuditoria, idInstituto),
  FOREIGN KEY (idAuditoria) REFERENCES auditorias(idAuditoria) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (idInstituto) REFERENCES institutos(idInstituto) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE personalContactado (
  idAuditoria INT NOT NULL,
  idContacto INT NOT NULL,
  PRIMARY KEY (idAuditoria, idContacto),
  FOREIGN KEY (idAuditoria) REFERENCES auditorias(idAuditoria) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (idContacto) REFERENCES contactos(idContacto) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE auditores (
  idAuditoria INT NOT NULL,
  idUsuario INT NOT NULL,
  PRIMARY KEY (idAuditoria, idUsuario),
  FOREIGN KEY (idAuditoria) REFERENCES auditorias(idAuditoria) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE auditoresLideres (
  idAuditoria INT NOT NULL,
  idUsuario INT NOT NULL,
  PRIMARY KEY (idAuditoria, idUsuario),
  FOREIGN KEY (idAuditoria) REFERENCES auditorias(idAuditoria) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE oportunidades (
  idOportunidad INT NOT NULL AUTO_INCREMENT,
  idAuditoria INT NOT NULL,
  oportunidad VARCHAR(255) NOT NULL,
  PRIMARY KEY (idOportunidad),
  FOREIGN KEY (idAuditoria) REFERENCES auditorias(idAuditoria) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE comentarios (
  idComentario INT NOT NULL AUTO_INCREMENT,
  idAuditoria INT NOT NULL,
  comentario VARCHAR(500) NOT NULL,
  PRIMARY KEY (idComentario),
  FOREIGN KEY (idAuditoria) REFERENCES auditorias(idAuditoria) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE conclusiones (
  idConclusion INT NOT NULL AUTO_INCREMENT,
  idAuditoria INT NOT NULL,
  conclusion VARCHAR(500) NOT NULL,
  PRIMARY KEY (idConclusion),
  FOREIGN KEY (idAuditoria) REFERENCES auditorias(idAuditoria) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE actividades (
  idActividad INT NOT NULL AUTO_INCREMENT,
  idAuditoria INT NOT NULL,
  horarioInicial DATETIME NOT NULL,
  horarioFinal DATETIME NOT NULL,
  proceso VARCHAR(100) NOT NULL,
  actividad TEXT NOT NULL,
  requisito TEXT DEFAULT NULL,
  area VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (idActividad),
  FOREIGN KEY (idAuditoria) REFERENCES auditorias(idAuditoria) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE participantes (
  idActividad INT NOT NULL,
  idUsuario INT NOT NULL,
  PRIMARY KEY (idActividad, idUsuario),
  FOREIGN KEY (idActividad) REFERENCES actividades(idActividad) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE contactados (
  idActividad INT NOT NULL,
  idContacto INT NOT NULL,
  PRIMARY KEY (idActividad, idContacto),
  FOREIGN KEY (idActividad) REFERENCES actividades(idActividad) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (idContacto) REFERENCES contactos(idContacto) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE procesos_usuarios (
  idProceso INT NOT NULL,
  idUsuario INT NOT NULL,
  PRIMARY KEY (idProceso, idUsuario),
  FOREIGN KEY (idProceso) REFERENCES procesos(idProceso) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE noConformidades (
  idNoConformidad INT NOT NULL AUTO_INCREMENT,
  idAuditoria INT NOT NULL,
  descripcion TEXT NOT NULL,
  requisito TEXT DEFAULT NULL,
  folio VARCHAR(100) NOT NULL,
  fecha DATE NOT NULL,
  accion TEXT DEFAULT NULL,
  numRAC VARCHAR(100) DEFAULT NULL,
  estado BOOLEAN NOT NULL DEFAULT 1,
  idVerifica INT DEFAULT NULL,
  idLibera INT DEFAULT NULL,
  PRIMARY KEY (idNoConformidad),
  FOREIGN KEY (idAuditoria) REFERENCES auditorias(idAuditoria) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (idVerifica) REFERENCES usuarios(idUsuario) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (idLibera) REFERENCES usuarios(idUsuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE accionesCorrectivas (
  idAC INT NOT NULL AUTO_INCREMENT,
  idProceso INT NOT NULL,
  idNoConformidad INT NOT NULL,
  folio VARCHAR(100) NOT NULL,
  areaProceso VARCHAR(100) DEFAULT NULL,
  idResponsable INT DEFAULT NULL,
  fecha DATE DEFAULT NULL,
  origenRequisito INT DEFAULT NULL,
  fuenteNC INT DEFAULT NULL,
  descripcion TEXT NOT NULL,
  requiereAC BOOLEAN NOT NULL DEFAULT FALSE,
  requiereCorreccion BOOLEAN NOT NULL DEFAULT FALSE,
  tecnicaUtilizada TEXT DEFAULT NULL,
  causaRaiz TEXT DEFAULT NULL,
  aCRealizar TEXT DEFAULT NULL,
  seguimiento TEXT DEFAULT NULL,
  idDefinir INT DEFAULT NULL,
  idVerificar INT DEFAULT NULL,
  fechaCierre DATE DEFAULT NULL,
  idCoordinador INT DEFAULT NULL,
  PRIMARY KEY (idAC),
  FOREIGN KEY (idProceso) REFERENCES procesos(idProceso) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (idNoConformidad) REFERENCES noConformidades(idNoConformidad) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (idResponsable) REFERENCES usuarios(idUsuario) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (idDefinir) REFERENCES usuarios(idUsuario) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (idVerificar) REFERENCES usuarios(idUsuario) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (idCoordinador) REFERENCES usuarios(idUsuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE correcciones (
  idCorreccion INT NOT NULL AUTO_INCREMENT,
  idAC INT NOT NULL,
  correccion TEXT NOT NULL,
  idResponsable INT DEFAULT NULL,
  fecha DATE NOT NULL,
  PRIMARY KEY (idCorreccion),
  FOREIGN KEY (idAC) REFERENCES accionesCorrectivas(idAC) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (idResponsable) REFERENCES usuarios(idUsuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE nCSimilares (
  idNCSimilar INT NOT NULL AUTO_INCREMENT,
  idAC INT NOT NULL,
  noConformidad TEXT NOT NULL,
  PRIMARY KEY (idNCSimilar),
  FOREIGN KEY (idAC) REFERENCES accionesCorrectivas(idAC) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ncPotenciales (
  idNCPotencial INT NOT NULL AUTO_INCREMENT,
  idAC INT NOT NULL,
  noConformidad TEXT NOT NULL,
  PRIMARY KEY (idNCPotencial),
  FOREIGN KEY (idAC) REFERENCES accionesCorrectivas(idAC) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE acciones (
  idAccion INT NOT NULL AUTO_INCREMENT,
  idAC INT NOT NULL,
  accion TEXT NOT NULL,
  idResponsable INT DEFAULT NULL,
  fecha DATE NOT NULL,
  PRIMARY KEY (idAccion),
  FOREIGN KEY (idAC) REFERENCES accionesCorrectivas(idAC) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (idResponsable) REFERENCES usuarios(idUsuario) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE actualizables (
  idActualizables INT NOT NULL AUTO_INCREMENT,
  idAC INT NOT NULL,
  accion TEXT NOT NULL,
  PRIMARY KEY (idActualizables),
  FOREIGN KEY (idAC) REFERENCES accionesCorrectivas(idAC) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cambiables (
  idCambiables INT NOT NULL AUTO_INCREMENT,
  idAC INT NOT NULL,
  accion TEXT NOT NULL,
  PRIMARY KEY (idCambiables),
  FOREIGN KEY (idAC) REFERENCES accionesCorrectivas(idAC) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE quejas (
  idQueja INT NOT NULL AUTO_INCREMENT,
  idProceso INT NOT NULL,
  fecha DATE,
  folio VARCHAR(100) NOT NULL UNIQUE,
  nombre VARCHAR(150),
  correo VARCHAR(150),
  telefono VARCHAR(20),
  matricula VARCHAR(50),
  carrera VARCHAR(100),
  semestre VARCHAR(20),
  grupo VARCHAR(20),
  turno VARCHAR(20),
  aula VARCHAR(50),
  queja TEXT,
  respuesta TEXT,
  idCoordinador INT,
  idRecibe INT,

  PRIMARY KEY (idQueja),

  CONSTRAINT fk_queja_proceso 
    FOREIGN KEY (idProceso) REFERENCES procesos(idProceso) 
    ON DELETE CASCADE ON UPDATE CASCADE,

  CONSTRAINT fk_queja_coordinador 
    FOREIGN KEY (idCoordinador) REFERENCES usuarios(idUsuario) 
    ON DELETE SET NULL ON UPDATE CASCADE,

  CONSTRAINT fk_queja_recibe 
    FOREIGN KEY (idRecibe) REFERENCES usuarios(idUsuario) 
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB 
  DEFAULT CHARSET = utf8mb4 
  COLLATE = utf8mb4_unicode_ci;