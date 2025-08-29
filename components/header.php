<header>
    <button class="btn-header movile" id="btn-menu-movile">
        <img src="/project/sources/icons/icon-burguer.svg" alt="" class="escalado">
    </button>
    <form class="form-search">
        <div class="div-search">
            <input type="text" placeholder="Buscar">
            <button type="reset" class="btn-header">
                <img src="/project/sources/icons/icon-cancel.svg" alt="" class="escalado">
            </button>
        </div>
        <button type="submit" class="btn-header">
            <img src="/project/sources/icons/icon-buscar.svg" alt="" class="escalado">
        </button>
    </form>
        
    <button class="btn-header movile-2">
        <img src="/project/sources/icons/icon-buscar.svg" alt="" class="escalado">
    </button>
    <button class="btn-header">
        <img src="/project/sources/icons/icon-notificacion.svg" alt="" class="escalado">
    </button>
    <div class="div-header-user">
        <img src="<?php echo !empty($_SESSION['perfil']) ? "/project/server/perfiles/" . $_SESSION['perfil'] : '/project/sources/imgs/user-Icon.png'; ?>" alt="" width="60px" class="escalado">
        <p><?php echo $_SESSION['nombreCompleto']?></p>
    </div>
    <button class="btn-header">
        <img src="/project/sources/icons/icon-options.svg" alt="" class="escalado">
    </button>
</header>