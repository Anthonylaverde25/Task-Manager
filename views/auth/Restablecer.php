



<div class="contenedor restablecer">
    
    <?php include_once __DIR__."/templates/nombre-sitio.php";?>


    <div class="contenedor-sm">
        <p class="descripcion-pagina">
            Establece una nueva contraseña
        </p>
        <?php include_once __DIR__."/templates/alertas.php";?>
        <?php if($mostrar):?>
        <form class="formulario" method="POST">
           
           <div class="campo">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Restablece tu contraseña">
            </div>

            <input type="submit" class="boton" value="Obtener nueva contraseña">
        </form>
        <?php endif ?>

        <div class="acciones">
            <a href="/">Ya tienes una cuenta? Inicia sesion</a>
            <a href="/crear">no tienes una cuenta?</a>
        </div>
    </div>
</div>