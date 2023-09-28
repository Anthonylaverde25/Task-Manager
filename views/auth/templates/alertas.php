

<?php if(!empty($alertas)):?>
    <?php foreach($alertas as $key => $alerta): ?>
        <?php if(is_array($alerta)):?>
            <?php foreach($alerta as $mensaje):?>
                <div class="alerta <?php echo $key;?>">
                    <?= $mensaje;?>
                </div>
            <?php endforeach;?>
        <?php endif;?>
    <?php endforeach;?>
<?php endif;?>
