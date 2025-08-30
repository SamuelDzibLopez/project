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

    // 11. NO CONFORMIDADES
    foreach ($noConformidades as $nc) {
        $pdo->prepare("INSERT INTO noConformidades (idAuditoria, descripcion, requisito, folio, fecha, accion, numRAC, estado, idVerifica, idLibera) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")
            ->execute([
                $idAuditoria,
                $nc["descripcion"],
                $nc["requisito"],
                $nc["folio"],
                $nc["fecha"],
                $nc["accion"],
                $nc["numRAC"],
                $nc["estado"],
                $nc["idVerifica"],
                $nc["idLibera"]
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


