<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="menu-movile" id="menu-movile">
    <button id="btn-close-menu-movile" class="menu-movile-exit">
        <img src="/project/sources/icons/icon-close.svg" alt="btn-close" class="escalado">
    </button>
    <div class="div-menu-movile">
        <a class="div-option-menu div-option-menu-active" href="/project/app/dashboard.php">
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

        <a class="div-option-menu escalado no_underline"  href="/project/server/php/destroy-session.php">
            <button class="btn-close-session escalado" id="btn-exit-desktop">Cerrar Sesión</button>
        </a>
    </div>
</div>

    <script type="module" src="/project/JS/menu_Movile.js"></script>