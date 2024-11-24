<?php
    $id_tipo = $perfil['id_tipo'];
    // Si un usuario suscriptor trata de acceder a esta cuenta fraudulentamente, devuelva a main
    if ($id_tipo > 2)
    {
        header('Location: index.php?page=main');
        exit();
    }
?>
<!-- Esta página es una copia casi exacta de nuevo libro -->
<section class="contenido" id="inicio">
    <div class="fila" id="intro">
        <h4 class="intro-title">Nuevo Autor</h4>
        <h6 class="intro-description">Por favor coloque los datos en los espacios correspondientes.</h6>
    </div>
    <div class="fila" id="book-info">
        <!-- Comienza la columna con la imagen de libro -->
        <div class="col w20" id="image">
            <!-- Jalado de profile-edit -->
            <input class="btn" type="file" name="file-selector" id="file-selector" accept="image/png, image/jpeg" style="display: none;">
            <button class="btn btn-file" onclick="$('#file-selector').click();" id="selector-archivo">Seleccionar foto</button>
        </div>
        <!-- Termina la columna con la imagen de libro -->
        <!-- Comienza la columna con los datos principales -->
        <div class="col w60" id="main-data">
            <!-- Un input para el nombre del libro -->
            <input type="text" id="author-name" placeholder="Escribe un nombre">
            <div class="author-status">
                <div id="book-id-text"><h4 id="id-book"></h4></div>
                <div id="author-status"><span class="status-tile">NUEVO</span></div>
            </div>
        </div>
        <!-- Termina la columna con los datos principales -->
        <!-- Comienza la columna con los botones de función -->
        <div class="col w20" id="functions">
            <button class="btn" id="save-author">Guardar Autor</button>
            <button class="btn" id="cancel-edit">Cancelar edición</button>
        </div>
        <!-- Termina la columna con los botones de función -->
    </div>
    <!-- Termina la sección de datos principales y funciones -->
    <!-- Comienza la sección de descripción del libro -->
    <div class="fila" id="author-description">
        <div class="col w100">
            <textarea id="description" placeholder="Comienza a escribir..."></textarea>
        </div>
    </div>
    <!-- Termina la sección de descripción del libro -->
    </div>
</section>