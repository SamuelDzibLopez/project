<?php
require_once './../server/php/verificacion.php'; // Incluir archivo de conexión
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Normoteca</title>
    <link rel="stylesheet" href="./../styles/style.css">
    <link rel="stylesheet" href="./../styles/dashboard.css">
    <link rel="stylesheet" href="./../styles/header.css">
    <link rel="stylesheet" href="./../styles/menu.css">
    <link rel="stylesheet" href="./../styles/fonts.css">
    <link rel="stylesheet" href="./../styles/apartado-mi_perfil.css">
    <link rel="stylesheet" href="./../styles/apartado-usuarios.css">

</head>
<body>
    <?php
        include "./../components/menu-movile.php"
    ?>
    <div class="div-1200px app">
        <?php 
            include "./../components/menu.php";
        ?>
        <div class="dashboard">
            <?php 
            include "./../components/header.php";
            ?>
            <div class="div-main-blue">
                <div class="div-main-white">
                    <div class="div-main-ITM">
                        <div class="div-main-blur">
                            <div class="div-title">
                                <img src="./../sources/icons/icon-normoteca.svg" alt="">
                                <h2 class="font-title">Normoteca</h2>
                            </div>

                            <!-- Apartado de Manual de Calidad -->
<div class="div-gray">
    <div class="div-subtitle">
        <img src="./../sources/icons/icon-normoteca.svg" alt="">
        <h2 class="font-subtitle">Manual de calidad</h2>
    </div>
    <hr class="hr-blue">
    <div class="div-mi-perfil">
        <div class="div-perfil">
            <br>

            <!-- Estilos de los botones en la carpeta apartado-mi_peril.css -->

            <!-- Enlace al documento PDF -->
            <a href="https://tecnm9-my.sharepoint.com/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/1.-%20Manual%20del%20SGC.pdf?CT=1738715825215&OR=ItemsView" target="_blank" class="btn-pdf">
                📄 Ver Manual de Calidad (PDF)
            </a>

            <a href="https://tecnm9-my.sharepoint.com/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/2.-%20CONTEXTO%20DE%20LA%20ORGANIZACION%202023.pdf?CT=1738770987564&OR=ItemsView" target="_blank" class="btn-pdf">
                📄 Ver Contexto de la Organización 2023 (PDF)
            </a>

            <a href="https://tecnm9-my.sharepoint.com/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/3.-%20IDENTIFICACI%C3%93N%20DE%20%20LAS%20PARTES%20INTERESADAS%202023%20.pdf?CT=1738771124186&OR=ItemsView" target="_blank" class="btn-pdf">
                📄 Ver Identificación de las partes interesadas 2023 (PDF)
            </a>

            <a href="https://tecnm9-my.sharepoint.com/:w:/r/personal/controlador_documentos_merida_tecnm_mx/_layouts/15/Doc.aspx?sourcedoc=%7B1E2A4BCC-5315-42F3-A8FC-D3C075D24AF7%7D&file=4.-%20Anexo%20Plan%20Rector%20de%20la%20Calidad%20(rev03)%20-%20Copia.docx&action=default&mobileredirect=true" target="_blank" class="btn-pdf">
                📄 Ver Anexo Plan Rector de la Calidad (rev03) (Doc)
            </a>

            <a href="https://tecnm9-my.sharepoint.com/:x:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/5A.-%20Matriz%20de%20Riesgos%20SGA%202023%20corregido.xls?d=w1cb124e5d97c4a7badff24582d38019c&csf=1&web=1&e=Z68NM6" target="_blank" class="btn-pdf">
                📄 Ver Matriz de Riesgos SGA 2023 Corregido (Excel)
            </a>

            <a href="https://tecnm9-my.sharepoint.com/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/5B.-%20TecNM-CA-PO-003-02%20Matriz%20%20de%20Gesti%C3%B3n%20de%20los%20riesgos%20y%20oportunidades%202024.pdf?CT=1738773628477&OR=ItemsView" target="_blank" class="btn-pdf">
                📄 Ver Matriz de Gestión de los Riesgos y Oportunidades 2024 (PDF)
            </a>

            <a href="https://tecnm9-my.sharepoint.com/:i:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/6.-%20POLITICA%20DE%20CALIDAD%20Y%20AMBIENTAL.jpg?csf=1&web=1&e=jcm7fg" target="_blank" class="btn-pdf">
                📄 Ver Política de Calidad y Ambiental (IMG)
            </a>

            <a href="https://tecnm9-my.sharepoint.com/:x:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/7.-%20ITMER-GA-PO-001-01%20MATRIZ%20ASPECTOS%20AMBIENTALES%202023.xlsx?d=wff70e144f5a741e69ab77e0ce56468e1&csf=1&web=1&e=MZICIG" target="_blank" class="btn-pdf">
                📄 Ver Matriz Aspectos Ambientales 2023 (Excel)
            </a>

            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/PDI%20v7.2%202019-2024.pdf?csf=1&web=1&e=CROpog " target="_blank" class="btn-pdf">
                📄 Ver Programa de Desarrollo Institucional (PDI) (PDF)
            </a>

            <a href="https://tecnm9-my.sharepoint.com/:w:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/Plan%20de%20mejora%20SGC%202020.docx?d=wf84600d0fe52426eac7d21c1214d4bd2&csf=1&web=1&e=8VGhLl" target="_blank" class="btn-pdf">
                📄 Ver Plan de Mejora SGC 2020 (Doc)
            </a>

            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/Sensibilizacion%20DEL%20SGIG_ITM11.pdf?csf=1&web=1&e=mTyhQH" target="_blank" class="btn-pdf">
                📄 Ver Sensibilización Del SGIG_ITM11 (PDF)
            </a>

            <a href="https://tecnm9-my.sharepoint.com/:w:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/V2%20Distribuci%C3%B3n%20de%20responsabilidades.docx?d=w81ac4f2f70b24ba882bd080299310afe&csf=1&web=1&e=HM258N" target="_blank" class="btn-pdf">
                📄 Ver V2 Distribución de desponsabilidades (Doc)
            </a>

        </div>
    </div>
</div>


                            <!--Apartado de Documentos Externos-->
                            <div class="div-gray">
                                <div class="div-subtitle">
                                    <img src="./../sources/icons/icon-normoteca.svg" alt="">
                                    <h2 class="font-subtitle">Documentos Externos</h2>
                                </div>
                                <hr class="hr-blue">
                                <div class="div-mi-perfil">
                                    <div class="div-perfil">
                                        <!--Aqui va estructura de Documentos Externos-->

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/04%20Norma_ISO_19011_2011.pdf?csf=1&web=1&e=J9HmUF" target="_blank" class="btn-pdf">
                                                📄 Ver Norma ISO 19011 2011 (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/24%20MANUAL%20DE%20PROCEDIMIENTOS%20DEL%20TECNOLOGICO%20NACIONAL%20DE%20MEXICO.pdf?csf=1&web=1&e=GJQsbM" target="_blank" class="btn-pdf">
                                                📄 Ver Ley de Transparencia y Acceso LGTAIP_130820 (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/06%20DOF%20-%20ADICIONES%202018%20Diario%20Oficial%20de%20la%20Federaci%C3%B3n.pdf?csf=1&web=1&e=OhJEYU" target="_blank" class="btn-pdf">
                                                📄 Ver DOF-Adiciones 2018 Diario Oficial de la federación (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/06%20Ley_Planeacion%20DOF.pdf?csf=1&web=1&e=vauRdz" target="_blank" class="btn-pdf">
                                                📄 Ver Ley_Planeación DOF (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/07%20LEY%20DE%20ADQUISICIONES%20Y%20ARRENDAMIENTOS%2011-08-2020.pdf?csf=1&web=1&e=qIu3SM" target="_blank" class="btn-pdf">
                                                📄 Ver Ley de Adquisiciones y Arrendamientos 11-08-2020 (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/08%20REGLAMENTO%20DE%20LA%20LEY%20FEDERAL%20DE%20PRESUPUESTO%20Y%20RESPONSABILIDAD%20HACENDARIA.pdf?csf=1&web=1&e=ce7y8n" target="_blank" class="btn-pdf">
                                                📄 Ver Reglamento de la ley federal de presupuesto y responsabilidad hacendaria (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/09%20CONSTITUCI%C3%93N%20POL%C3%8DTICA%20DE%20LOS%20ESTADOS%20UNIDOS%20MEXICANOS.pdf?csf=1&web=1&e=vVXsyC" target="_blank" class="btn-pdf">
                                                📄 Ver Constitución Política de los Estados Unidos Mexicanos (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/10%20LEY%20FEDERAL%20DE%20LOS%20TRABAJADORES%20DEL%20ESTADO.pdf?csf=1&web=1&e=g6hXf0" target="_blank" class="btn-pdf">
                                                📄 Ver Ley Federal de los Trabajadores del Estado (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/11%20CLASIFICADOR%20POR%20OBJETO%20DEL%20GASTO%20PARA%20LA%20ADMINISTRACI%C3%93N%20PUBLICA%20FEDERAL.pdf?csf=1&web=1&e=wYREyx" target="_blank" class="btn-pdf">
                                                📄 Ver Clasificador por objeto del gasto para la administración pública federal (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/12%20LEY%20FEDERALDE%20RESPONSABILIDADES%20DE%20LOS%20SERVIDORES%20P%C3%9ABLICOS.pdf?csf=1&web=1&e=xhe9P8" target="_blank" class="btn-pdf">
                                                📄 Ver Ley Federal de Responsabilidades de los servidores públicos (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/14%20INSTITUTO%20DE%20SEGURIDAD%20Y%20SERVICIOS%20SOCIALES%20DE%20LOS%20TRABAJADORES%20DEL%20ESTADO.pdf?csf=1&web=1&e=lU15La" target="_blank" class="btn-pdf">
                                                📄 Ver Instituto de Seguridad y Servicios Sociales de los Trabajadores del Estado (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/15%20REGLAMENTO%20DE%20PRESTACIONES%20ECONOMICAS%20Y%20VIVIENDA%20DEL%20ISSSTE.pdf?csf=1&web=1&e=NSY4zs" target="_blank" class="btn-pdf">
                                                📄 Ver Reglamento de Prestaciones Económicas y Vivienda del ISSSTE (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/16%20MANUAL%20DE%20LINEAMIENTOS%20ACAD%C3%89MICO%20ADMINISTRATIVOS.pdf?csf=1&web=1&e=39a5gv" target="_blank" class="btn-pdf">
                                                📄 Ver Manual de Lineamientos Académicos Administrativos (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/17%20FORMATO%20DE%20SEGUIMIENTO%20DEL%20PTA%202020%20TecNM.pdf?csf=1&web=1&e=vECbSY" target="_blank" class="btn-pdf">
                                                📄 Ver Formato de Seguimiento del PTA 2020 (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/22%20REGLAMENTO%20INTERIOR%20DE%20TRABAJO%20DEL%20PERSONAL%20DOCENTE%20DE%20LOS%20INSTITUTOS%20TECNOL%C3%93GICOS.pdf?csf=1&web=1&e=5N39m6" target="_blank" class="btn-pdf">
                                                📄 Ver Reglamento interior de trabajo del personal docente de los institutos tecnológicos (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/23%20CONVOCATORIA%20PROGRAMA%20LICENCIA%20POR%20BECA-COMISION.pdf?csf=1&web=1&e=dZAkdG" target="_blank" class="btn-pdf">
                                                📄 Ver Convocatoria programa licencia por BECA-COMICION (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/23%20MANUAL%20DE%20LINEAMIENTOS%20ACAD%C3%89MICO-ADMINISTRATIVO%20DEL%20TECNM.pdf?csf=1&web=1&e=H3eBy8" target="_blank" class="btn-pdf">
                                                📄 Ver Manual de lineamientos Academico-Administrativo del TECNM (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/23%20MODELO%20DE%20EDUCACI%C3%93N%20A%20DISTANCIA%20DEL%20TECNOL%C3%93GICO%20NACIONAL%20DE%20M%C3%89XICO.pdf?csf=1&web=1&e=5syWyp" target="_blank" class="btn-pdf">
                                                📄 Ver Modelo de educación a distancia del Tecnológico Nacional de México (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/23%20MODELO%20DE%20EDUCACI%C3%93N%20DUAL%20PARA%20NIVEL%20LICENCIATURA%20DEL%20TECNM.pdf?csf=1&web=1&e=ruDdfF" target="_blank" class="btn-pdf">
                                                📄 Ver Modelo de educación dual para nivel licenciatura del TECNM (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/24%20GUIA%20PARA%20LA%20ELABORACION%20Y%20ACTUALIACION%20DE%20MANUALES%20DE%20PROCEDIMIENTOS%202017.pdf?csf=1&web=1&e=0IUcYk" target="_blank" class="btn-pdf">
                                                📄 Ver Guia para la elaboración y actualización de manuales de procedimientos 2017 (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/24%20MANUAL%20DE%20ORGANIZACI%C3%93N%20GENERAL%20DEL%20TECNOL%C3%93GICO%20NACIONAL%20DE%20M%C3%89XICO.pdf?csf=1&web=1&e=cezDkh" target="_blank" class="btn-pdf">
                                                📄 Ver Manual de organización general del Tecnológico Nacional de México (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/24%20MANUAL%20DE%20PROCEDIMIENTOS%20DEL%20TECNOLOGICO%20NACIONAL%20DE%20MEXICO.pdf?csf=1&web=1&e=oUWX4q" target="_blank" class="btn-pdf">
                                                📄 Ver Manual de procedimientos del Tecnológico Nacional de México (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/26%20CIRCULAR_DF_0016_2018.pdf?csf=1&web=1&e=dUzLGJ" target="_blank" class="btn-pdf">
                                                📄 Ver Circular_DF_0016_2018 (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/26%20Circular_DF-0012018_CALENDARIO_DE_OBLIGACIONES_2018.pdf?csf=1&web=1&e=LD9c7m" target="_blank" class="btn-pdf">
                                                📄 Ver Circular_DF_0012018_Calendario de Obligaciones 2018  (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/26%20CIRCULAR_DF-0018-2018.PDF?csf=1&web=1&e=lhXSuC" target="_blank" class="btn-pdf">
                                                📄 Ver Circular_DF-0018-2018 (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/26%20CIRCULAR_No._DF00172018.pdf?csf=1&web=1&e=SKsx5c" target="_blank" class="btn-pdf">
                                                📄 Ver Circular No. DF00172018 (PDF)
                                            </a>

                                            <a href="https://tecnm9-my.sharepoint.com/:b:/r/personal/controlador_documentos_merida_tecnm_mx/Documents/CSG-acceso%20al%201%20de%20septiembre%20de%202023/1.-SISTEMAS%20DE%20GESTI%C3%93N/01.-%20SISTEMA%20DE%20GESTION%20DE%20CALIDAD%20Y%20AMBIENTAL%202022/0.-%20MANUAL%20DE%20CALIDAD/DOCUMENTOS%20EXTERNOS/27%20ACTA%20DE%20ENTREGA%20RECEPCION%20E%20INFORME%20DE%20ASUNTOS.pdf?csf=1&web=1&e=XGrFpz" target="_blank" class="btn-pdf">
                                                📄 Ver Acta de entrega recepcion e informe de asuntos (PDF)
                                            </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            include "./../components/footer.php"
            ?>
        </div>
    </div>
    </script>
</body>
</html>