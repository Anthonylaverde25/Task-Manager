

<div class="contenedor login">
    
    <?php include_once __DIR__."/templates/nombre-sitio.php";?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">
            Iniciar Sesion
        </p>
        <?php include_once __DIR__."/templates/alertas.php";?>
        <form class="formulario" method="POST" action="/" novalidate>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Email">
            </div>

            <div class="campo">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Password">
            </div>

            <input type="submit" class="boton" value="Iniciar Sesion">
        </form>
        <div class="acciones">
            <a href="/crear">Aun no tienes una cuenta? Crea una</a>
            <a href="/olvide">Olvidaste tu Password? </a>
        </div>
    </div>
</div>