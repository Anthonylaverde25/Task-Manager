<?php include_once __DIR__."/header-dashboard.php";?>


<div class="contenedor-sm">
    <?php include_once __DIR__.'/../auth/templates/alertas.php';?>

    <a class="enlace" href="/cambiar-password">Cambiar Contraseña</a>

    <form class="formulario" method="POST"  action="">
        <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" value="<?= $usuario->nombre;?>" name="nombre" placeholder="Tu nombre">
        </div>

        <div class="campo">
            <label for="email">Email</label>
            <input type="email" value="<?= $usuario->email;?>" name="email" placeholder="Tu Email">
        </div>

        <input type="submit" value="Guardar cambios">
    </form>
</div>


<?php include_once __DIR__  . '/footer-dashboard.php'; ?>