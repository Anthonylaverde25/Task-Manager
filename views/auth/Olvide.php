



<div class="contenedor olvide">
    
    <?php include_once __DIR__."/templates/nombre-sitio.php";?>


    <div class="contenedor-sm">
        <p class="descripcion-pagina">
            Recupera  tu cuenta en Up Task
        </p>

        <?php include_once __DIR__."/templates/alertas.php";?>


        <form class="formulario" method="POST" action="/olvide" novalidate>
           

            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Email">
            </div>


            <input type="submit" class="boton" value="Recuperar cuenta">
        </form>
        <div class="acciones">
            <a href="/">Ya tienes una cuenta? Inicia sesion</a>
            <a href="/crear">no tienes una cuenta?</a>
        </div>
    </div>
</div>