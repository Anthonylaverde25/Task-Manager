<?php include_once __DIR__."/header-dashboard.php";?>

<div class="contenedor-sm">
<?php include_once __DIR__.'/../auth/templates/alertas.php';?>

<form class="formulario" action="/crear-proyecto" method="POST" >
    <?php include_once __DIR__."/../dashboard/formulario-proyecto.php";?>
    <input type="submit" value="Crear Proyecto">
</form>
</div>

<?php include_once __DIR__."/footer-dashboard.php";?>