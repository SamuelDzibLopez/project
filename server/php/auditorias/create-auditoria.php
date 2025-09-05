<?php
header("Content-Type: application/json");
require_once './../permisos.php'; 
require_once './../conexion.php';

try {
    // Recibir JSON del body
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input) {
        throw new Exception("No se recibieron datos válidos en el body");
    }

    // Variables
    $tipoProceso      = $input["tipoProceso"];
    $folioProceso     = $input["folioProceso"];
    $estadoProceso    = $input["estadoProceso"];
    $auditoriaData    = $input["auditoriaData"];
    $usuariosProceso  = $input["usuariosProceso"];
    $actividades      = $input["actividades"];
    $institutos       = $input["institutos"];
    $personalContactado = $input["personalContactado"];
    $auditores        = $input["auditores"];
    $auditoresLideres = $input["auditoresLideres"];
    $oportunidades    = $input["oportunidades"];
    $comentarios      = $input["comentarios"];
    $conclusiones     = $input["conclusiones"];
    $noConformidades  = $input["noConformidades"];

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
        $pdo->prepare("INSERT INTO oportunidades (idAuditoria, oportunidad) VALUES (?, ?)")->execute([$idAuditoria, $op["oportunidad"]]);
    }

    // 9. COMENTARIOS
    foreach ($comentarios as $com) {
        $pdo->prepare("INSERT INTO comentarios (idAuditoria, comentario) VALUES (?, ?)")->execute([$idAuditoria, $com["comentario"]]);
    }

    // 10. CONCLUSIONES
    foreach ($conclusiones as $con) {
        $pdo->prepare("INSERT INTO conclusiones (idAuditoria, conclusion) VALUES (?, ?)")->execute([$idAuditoria, $con["conclusion"]]);
    }

    // 11. NO CONFORMIDADES + crear PROCESO (Acción Correctiva) y accionesCorrectivas
    foreach ($noConformidades as $nc) {

        // 11a) Insertar no conformidad y obtener su id
        $stmtNoC = $pdo->prepare("INSERT INTO noConformidades (idAuditoria, descripcion, requisito, folio, fecha, accion, numRAC, estado, idVerifica, idLibera) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtNoC->execute([
            $idAuditoria,
            $nc["descripcion"],
            $nc["requisito"],
            $nc["folio"],
            $nc["fecha"],
            $nc["accion"],
            // conservar numRAC si viene (estructura original)
            array_key_exists('numRAC', $nc) ? $nc["numRAC"] : null,
            $nc["estado"],
            $nc["idVerifica"],
            $nc["idLibera"]
        ]);
        $idNoConformidad = $pdo->lastInsertId();

        // 11b) Determinar folio para el proceso de la Acción Correctiva:
        // usar numAC si viene, si no usar numRAC (compatibilidad)
        $folioAC = null;
        if (array_key_exists('numAC', $nc) && $nc['numAC'] !== '') {
            $folioAC = $nc['numAC'];
        } elseif (array_key_exists('numRAC', $nc) && $nc['numRAC'] !== '') {
            $folioAC = $nc['numRAC'];
        }

        // 11c) Crear PROCESO tipo "Acción Correctiva"
        $stmtProcAC = $pdo->prepare("INSERT INTO procesos (tipoProceso, folio, estado) VALUES (?, ?, ?)");
        $stmtProcAC->execute([
            "Acción Correctiva",
            $folioAC,
            // estado inicial, puedes cambiar si tu lógica requiere otro estado
            "1"
        ]);
        $idProcesoAC = $pdo->lastInsertId();

        // 11d) Insertar registro en accionesCorrectivas ligado al idNoConformidad y al nuevo proceso
        $stmtACC = $pdo->prepare("INSERT INTO accionesCorrectivas (
            idProceso, idNoConformidad, areaProceso, idResponsable, fecha, origenRequisito,
            fuenteNC, requiereAC, requiereCorreccion, tecnicaUtilizada, causaRaiz, aCRealizar,
            seguimiento, idDefinir, idVerificar, fechaCierre, idCoordinador
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmtACC->execute([
            $idProcesoAC,
            $idNoConformidad,
            $nc["areaProceso"]        ?? null,
            $nc["idResponsable"]      ?? null,
            // si el campo fecha en la NC representa la fecha de la AC se reusa, si no dejar null
            $nc["fecha"]              ?? null,
            $nc["origenRequisito"]    ?? null,
            $nc["fuenteNC"]           ?? null,
            // asegurar que se inserte 0/1
            isset($nc["requiereAC"]) ? (int)$nc["requiereAC"] : 0,
            isset($nc["requiereCorreccion"]) ? (int)$nc["requiereCorreccion"] : 0,
            $nc["tecnicaUtilizada"]   ?? null,
            $nc["causaRaiz"]          ?? null,
            $nc["aCRealizar"]         ?? null,
            $nc["seguimiento"]        ?? null,
            $nc["idDefinir"]          ?? null,
            $nc["idVerificar"]        ?? null,
            $nc["fechaCierre"]        ?? null,
            $nc["idCoordinador"]      ?? null
        ]);
    }

    // Confirmar transacción
    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "ok" => true,
        "statusCode" => 201,
        "message" => "Auditoría creada con éxito",
        "data" => [
            "idProceso" => $idProceso,
            "idAuditoria" => $idAuditoria
        ]
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode([
        "status" => "error",
        "ok" => false,
        "statusCode" => 500,
        "message" => $e->getMessage(),
        "data" => null
    ]);
}
