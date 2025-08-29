<?php
header("Content-Type: application/json");

require_once './../permisos.php'; 
require_once './../conexion.php';

try {
    // ==========================
    // VARIABLES DE DATOS INICIALES
    // ==========================

    // Proceso
    $tipoProceso = "Auditoría Interna";
    $folioProceso = "2025-001";
    $estadoProceso = 1;

    // Auditoría
    $auditoriaData = [
        "numAuditoria"        => 1001,
        "proceso"             => "Proceso de Auditoría 2025",
        "fecha"               => "2025-07-01",
        "documentosReferencia"=> "Documento X, Documento Y",
        "objetivo"            => "Verificar cumplimiento",
        "alcance"             => "Área administrativa",
        "fechaEmision"        => "2025-07-02",
        "ciudadInicioApertura"=> "Mérida",
        "fechaInicioApertura" => "2025-07-01 09:00:00",
        "lugarInicioApertura" => "Sala 101",
        "fechaFinalApertura"  => "2025-07-01 12:00:00",
        "ciudadInicioCierre"  => "Mérida",
        "fechaInicioCierre"   => "2025-07-02 09:00:00",
        "lugarInicioCierre"   => "Sala 102",
        "fechaFinalCierre"    => "2025-07-02 12:00:00",
        "fechaEntregaEvidencia"=> "2025-07-03",
        "idElabora"           => 1,
        "idValida"            => 2,
        "idCoordinador"       => 3,
        "idRecibe"            => 4
    ];

    // Usuarios vinculados al proceso
    $usuariosProceso = [1, 2, 3];

    // Actividades
    $actividades = [
        [
            "horarioInicial" => "2025-07-01 09:00:00",
            "horarioFinal"   => "2025-07-01 12:00:00",
            "proceso"        => "Academico",
            "actividad"      => "Actividad 1",
            "requisito"      => "1.1",
            "area"           => "Sala 1",
            "participantes"  => [1,2],
            "contactados"    => [1,2]
        ],
        [
            "horarioInicial" => "2025-07-01 09:00:00",
            "horarioFinal"   => "2025-07-01 12:00:00",
            "proceso"        => "Administracion",
            "actividad"      => "Actividad 2",
            "requisito"      => "2.1, 2.2, 2.3",
            "area"           => "Sala 2",
            "participantes"  => [1,2],
            "contactados"    => [1,2,3,4]
        ],
        [
            "horarioInicial" => "2025-07-01 09:00:00",
            "horarioFinal"   => "2025-07-01 12:00:00",
            "proceso"        => "Calidad",
            "actividad"      => "Actividad 3",
            "requisito"      => "3.1, 3.2",
            "area"           => "Sala 3",
            "participantes"  => [1,2,3],
            "contactados"    => [1,2,3]
        ]
    ];

    // Institutos relacionados
    $institutos = [1,2];

    // Personal contactado
    $personalContactado = [1,2,3,4];

    // Auditores
    $auditores = [1,2,3,4];

    // Auditores líderes
    $auditoresLideres = [1,2];

    // Oportunidades
    $oportunidades = [
        "Mejorar proceso de documentación 1",
        "Mejorar proceso de documentación 2",
        "Mejorar proceso de documentación 3"
    ];

    // Comentarios
    $comentarios = [
        "Comentario inicial sobre auditoría 1",
        "Comentario inicial sobre auditoría 2",
        "Comentario inicial sobre auditoría 3"
    ];

    // Conclusiones
    $conclusiones = [
        "Conclusión de auditoría satisfactoria 1",
        "Conclusión de auditoría satisfactoria 2",
        "Conclusión de auditoría satisfactoria 3"
    ];

    // No conformidades
    $noConformidades = [
        ["Falta de control en documentación 1", "ISO 9001:2015", "2025-001/01", "2025-07-02", "", "RAC-001/01", 1, 2, 3],
        ["Falta de control en documentación 2", "ISO 9001:2015", "2025-001/02", "2025-07-02", "", "RAC-001/02", 1, 2, 3],
        ["Falta de control en documentación 3", "ISO 9001:2015", "2025-001/03", "2025-07-02", "", "RAC-001/03", 1, 2, 3]
    ];

    // ==========================
    // EJECUCIÓN DEL PROCESO
    // ==========================

    $pdo->beginTransaction();

    // 1. CREAR PROCESO
    $sqlProceso = "INSERT INTO procesos (tipoProceso, folio, estado) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sqlProceso);
    $stmt->execute([$tipoProceso, $folioProceso, $estadoProceso]);
    $idProceso = $pdo->lastInsertId();

    // 2. CREAR AUDITORÍA
    $sqlAuditoria = "INSERT INTO auditorias (
        idProceso, numAuditoria, proceso, fecha, documentosReferencia, objetivo, alcance, fechaEmision,
        ciudadInicioApertura, fechaInicioApertura, lugarInicioApertura, fechaFinalApertura,
        ciudadInicioCierre, fechaInicioCierre, lugarInicioCierre, fechaFinalCierre, fechaEntregaEvidencia,
        idElabora, idValida, idCoordinador, idRecibe
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sqlAuditoria);
    $stmt->execute([
        $idProceso,
        $auditoriaData["numAuditoria"],
        $auditoriaData["proceso"],
        $auditoriaData["fecha"],
        $auditoriaData["documentosReferencia"],
        $auditoriaData["objetivo"],
        $auditoriaData["alcance"],
        $auditoriaData["fechaEmision"],
        $auditoriaData["ciudadInicioApertura"],
        $auditoriaData["fechaInicioApertura"],
        $auditoriaData["lugarInicioApertura"],
        $auditoriaData["fechaFinalApertura"],
        $auditoriaData["ciudadInicioCierre"],
        $auditoriaData["fechaInicioCierre"],
        $auditoriaData["lugarInicioCierre"],
        $auditoriaData["fechaFinalCierre"],
        $auditoriaData["fechaEntregaEvidencia"],
        $auditoriaData["idElabora"],
        $auditoriaData["idValida"],
        $auditoriaData["idCoordinador"],
        $auditoriaData["idRecibe"]
    ]);
    $idAuditoria = $pdo->lastInsertId();

    // 3. VINCULAR USUARIOS
    foreach ($usuariosProceso as $u) {
        $pdo->prepare("INSERT INTO procesos_usuarios (idProceso, idUsuario) VALUES (?, ?)")->execute([$idProceso, $u]);
    }

    // 4. CREAR ACTIVIDADES Y RELACIONES
    foreach ($actividades as $act) {
        $stmt = $pdo->prepare("INSERT INTO actividades (idAuditoria, horarioInicial, horarioFinal, proceso, actividad, requisito, area) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$idAuditoria, $act["horarioInicial"], $act["horarioFinal"], $act["proceso"], $act["actividad"], $act["requisito"], $act["area"]]);
        $idActividad = $pdo->lastInsertId();

        foreach ($act["participantes"] as $p) {
            $pdo->prepare("INSERT INTO participantes (idActividad, idUsuario) VALUES (?, ?)")->execute([$idActividad, $p]);
        }
        foreach ($act["contactados"] as $c) {
            $pdo->prepare("INSERT INTO contactados (idActividad, idContacto) VALUES (?, ?)")->execute([$idActividad, $c]);
        }
    }

    // 5. INSTITUTOS
    foreach ($institutos as $inst) {
        $pdo->prepare("INSERT INTO auditorias_institutos (idAuditoria, idInstituto) VALUES (?, ?)")->execute([$idAuditoria, $inst]);
    }

    // 6. PERSONAL CONTACTADO
    foreach ($personalContactado as $pc) {
        $pdo->prepare("INSERT INTO personalContactado (idAuditoria, idContacto) VALUES (?, ?)")->execute([$idAuditoria, $pc]);
    }

    // 7. AUDITORES
    foreach ($auditores as $au) {
        $pdo->prepare("INSERT INTO auditores (idAuditoria, idUsuario) VALUES (?, ?)")->execute([$idAuditoria, $au]);
    }

    // 7b. AUDITORES LÍDERES
    foreach ($auditoresLideres as $al) {
        $pdo->prepare("INSERT INTO auditoresLideres (idAuditoria, idUsuario) VALUES (?, ?)")->execute([$idAuditoria, $al]);
    }

    // 8. OPORTUNIDADES
    foreach ($oportunidades as $op) {
        $pdo->prepare("INSERT INTO oportunidades (idAuditoria, oportunidad) VALUES (?, ?)")->execute([$idAuditoria, $op]);
    }

    // 9. COMENTARIOS
    foreach ($comentarios as $com) {
        $pdo->prepare("INSERT INTO comentarios (idAuditoria, comentario) VALUES (?, ?)")->execute([$idAuditoria, $com]);
    }

    // 10. CONCLUSIONES
    foreach ($conclusiones as $con) {
        $pdo->prepare("INSERT INTO conclusiones (idAuditoria, conclusion) VALUES (?, ?)")->execute([$idAuditoria, $con]);
    }

    // 11. NO CONFORMIDADES
    foreach ($noConformidades as $nc) {
        $pdo->prepare("INSERT INTO noConformidades (idAuditoria, descripcion, requisito, folio, fecha, accion, numRAC, estado, idVerifica, idLibera) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")->execute(array_merge([$idAuditoria], $nc));
    }

    // Confirmar transacción
    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "ok" => true,
        "statusCode" => 201,
        "message" => "Proceso y auditoría creados con éxito",
        "data" => [
            "idProceso" => $idProceso,
            "idAuditoria" => $idAuditoria
        ]
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => $e->getMessage(),
        "data" => null
    ]);
}
