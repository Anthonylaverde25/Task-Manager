<aside class="sidebar">

    <div class="contenedor-sidebar">
     <h2>Uptask</h2>
        <div class="cerrar-menu">
            <img src="build/img/cerrar.svg" alt="cerrar menu" id="cerrar-menu">
        </div>

    </div>

    <nav class="sidebar-nav">
        <a class="<?php echo ($titulo === "proyectos") ? "activo": "";?>" href="/dashboard">Proyectos</a>
        <a class="<?php echo ($titulo === "Crear proyecto") ? "activo" : "";?>" href="/crear-proyecto">Crear proyectos</a>
        <a class="<?php echo ($titulo === "Perfil") ? "activo" : ""; ?>" href="/perfil">Perfil</a>
    </nav>

    <div class="cerrar-sesion-mobile">
        <a href="/logout" class="cerrar-sesion">Cerrar sesion</a>
    </div>
</aside>