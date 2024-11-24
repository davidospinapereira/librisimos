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
    <div class="fila" id="intro">
        <h4 class="intro-title">Libro nuevo</h4>
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
            <input type="text" id="book-name" placeholder="Escribe un título">
            <div class="book-name-status">
                <div id="book-id-text"><h4 id="id-book"></h4></div>
                <div id="book-status"><span class="status-tile">NUEVO</span></div>
            </div>
            <!-- Un listado de géneros con un X al final de cada uno, al dar clic a la X se quita del listado -->
            <div id="genres">
                <div id="genre-list">
                    
                </div>
                <!-- Un select con un botón de añadir género -->
                <div class="genre-select">
                    <select id="available-genres">
                        <option value="X" data-color="54ff12">Seleccione un género</option>
                    </select>
                    <button class="btn" id="add-genre">Añadir género</button>
                </div>
            </div>
            <!-- Un listado de autores con un X al final de cada uno, al dar clic a la X se quita del listado -->
            <!-- Un listado de autores con una X al final -->
            <div id="authors">
                <div id="author-list">
                    
                </div>
                <!-- Un select con un botón de añadir autor -->
                <div class="author-select">
                    <select id="available-authors">
                        <option value="X">Seleccione un autor</option>
                    </select>
                    <button class="btn" id="add-author">Añadir autor</button>
                </div>
            </div>
        </div>
        <!-- Termina la columna con los datos principales -->
        <!-- Comienza la columna con los botones de función -->
        <div class="col w20" id="functions">
            <button class="btn" id="save-book">Guardar Libro</button>
            <button class="btn" id="cancel-edit">Cancelar edición</button>
        </div>
        <!-- Termina la columna con los botones de función -->
    </div>
    <!-- Termina la sección de datos principales y funciones -->
    <!-- Comienza la sección de descripción del libro -->
    <div class="fila" id="book-description">
        <div class="col w100">
            <textarea id="description" placeholder="Comienza a escribir..."></textarea>
        </div>
    </div>
    <!-- Termina la sección de descripción del libro -->
    </div>
</section>