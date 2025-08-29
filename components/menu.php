<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="menu-desktop">
    <a class="div-logo escalado" href="/project/">
        <img src="/project/sources/imgs/logo-TECNM.png" alt="">
    </a>
    <div class="div-options">
        <a class="div-option-menu div-option-menu-active escalado" href="/project/app/dashboard.php">
            <img src="/project/sources/icons/icon-dashboard.svg" alt="">
            <p>Dashoard</p>
        </a>

        <a class="div-option-menu escalado" href="/project/app/mi-perfil.php">
            <img src="/project/sources/icons/icon-perfil.svg" alt="">
            <p>Mi perfil</p>
        </a>

        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador'): ?>
        <a class="div-option-menu escalado" href="/project/app/usuarios.php">
            <img src="/project/sources/icons/icon-usuarios.svg" alt="">
            <p>Usuarios</p>
        </a>
        <?php endif; ?>

        <a class="div-option-menu escalado" href="/project/app/procesos.php">
            <img src="/project/sources/icons/icon-proyectos.svg" alt="">
            <p>Procesos</p>
        </a> 

        <a class="div-option-menu escalado" href="/project/app/documentos.php">
            <img src="/project/sources/icons/icon-documentos.svg" alt="">
            <p>Documentos</p>
        </a>

        <a class="div-option-menu escalado" href="/project/app/normoteca.php">
            <img src="/project/sources/icons/icon-normoteca.svg" alt="">
            <p>Normoteca</p>
        </a>

        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador'): ?>
        <a class="div-option-menu escalado" href="/project/app/directorio.php">
            <img src="/project/sources/icons/icon-personal.svg" alt="">
            <p>Directorio</p>
        </a>
        <?php endif; ?>

        <a class="div-option-menu escalado" href="/project/app/avisos.php">
            <img src="/project/sources/icons/icon-avisos.svg" alt="">
            <p>Avisos</p>
        </a>
        <a href="/project/server/php/destroy-session.php">
            <button class="btn-close-session escalado" id="btn-exit-desktop">Cerrar Sesión</button>
        </a>
    </div>
</div>
