

<div class="contenedor crear">


    <?php include_once __DIR__."/templates/nombre-sitio.php";?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">
            Crea tu cuenta en Up Task
        </p>


        <?php include_once __DIR__."/templates/alertas.php";?>
        <form class="formulario" method="POST" action="/crear">
           <div class="campo">
                <label for="name">User Name</label>
                <input type="text" name="nombre" id="name" placeholder="Ingrese un nombre de usuario" value="<?php echo $usuario->nombre; ?>">
                
            </div>

            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Email"  value="<?php echo $usuario->email; ?>">
            </div>

            <div class="campo">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Password">
            </div>


            <div class="campo">
                <label for="password2">Password</label>
                <input type="password" name="password2" id="password2" placeholder="Repite tu Password">
            </div>

            <input type="submit" class="boton" value="Crea una cuenta en up task">
        </form>
        <div class="acciones">
            <a href="/">Ya tienes una cuenta?</a>
            <a href="/olvide">Olvidaste tu Password? </a>
        </div>
    </div>
</div>