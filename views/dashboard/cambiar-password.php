<?php include_once __DIR__."/header-dashboard.php";?>


<div class="contenedor-sm">
    <?php include_once __DIR__.'/../auth/templates/alertas.php';?>

    
    <a class="enlace" href="/perfil">Volver a tu perfil</a>

    <form class="formulario" method="POST"  action="">
        <div class="campo">
            <label for="nombre">Contraseña Actual</label>
            <input type="password"  name="password_actual" placeholder="Tu Password actual ">
        </div>

        <div class="campo">
            <label for="email">Nueva Contraseña</label>
            <input type="password" name="password_nuevo" placeholder="Tu Password nuevo">
        </div>

        <input type="submit" value="Guardar cambios">
    </form>
</div>



<?php include_once __DIR__  . '/footer-dashboard.php'; ?>