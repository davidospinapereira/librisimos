<?php
    $id_tipo = $perfil['id_tipo'];
    // Si un usuario suscriptor trata de acceder a esta cuenta fraudulentamente, devuelva a main
    if ($id_tipo > 2)
    {
        header('Location: index.php?page=main');
        exit();
    }
?>

<section class="contenido" id="inicio">
    <div class="fila">
        <div class="col w70" id="intro">
            <h3>Este es nuestro <br/><span>SISTEMA DE GÉNEROS</span></h3>
            <h5>Por favor usa las opciones disponibles.</h5>
        </div>
    </div>
    <div class="fila" id="controles">
        <div class="col w50">
            <!-- Primero, el selector del nombre de género -->
            <input type="text" placeholder="Nombre del género..." id="nombre-genero">
        </div>
        <div class="col w30" id="color">
            <input type="color" id="color-picker" value="#ffffff">
            <input type="text" id="color-value" placeholder="Código HEX" value="ffffff" readonly>
        </div>
        <div class="col w20">
            <button class="btn" id="add-genre">Añadir género</button>
        </div>
    </div>
    <div class="fila">
        <div class="col w100" id="genre-table">
            <div id="table-overlay" class=""><span class="loader"></span></div>
        </div>
    </div>
</section>