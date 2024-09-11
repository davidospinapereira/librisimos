<?php
    if(isset($_GET['book-id']))
    {
        // Si está establecido el id del libro
        $book_id = $_GET['book-id'];
    }
    else
    {
        // Si no hay id del libro entonces tenemos que salirnos de aquí
        header('Location: index.php?page=main');
        exit();
    }
?>
        <section class="contenido" id="inicio">
            <!-- Comienza la sección de datos principales y funciones  -->
            <div class="fila" id="book-info">
                <!-- Comienza la columna con la imagen de libro -->
                <div class="col w20" id="image" style="background-image: url('./view/uploads/books/generic-book-cover.jpg');">
                    <!-- Jalado de profile-edit -->
                    <input class="btn" type="file" name="file-selector" id="file-selector" accept="image/png, image/jpeg" style="display: none;">
                    <button onclick="$('#file-selector').click();" id="selector-archivo">Seleccionar foto</button>
                </div>
                <!-- Termina la columna con la imagen de libro -->
                <!-- Comienza la columna con los datos principales -->
                <div class="col w60" id="main-data">
                    <!-- Un input para el nombre del libro -->
                    <input type="text" id="book-name">
                    <h4 id="id-book">ID de libro: 1</h4>
                    <!-- Un listado de géneros con un X al final de cada uno, al dar clic a la X se quita del listado -->
                    <div id="genres">
                        <div id="genre-list">
                            <span style="background-color: #45f2a2;">Aquí <i class="bx bx-x icon-close"></i></span>
                            <span style="background-color: #45f2a2;">van <i class="bx bx-x icon-close"></i></span>
                            <span style="background-color: #45f2a2;">los <i class="bx bx-x icon-close"></i></span>
                            <span style="background-color: #45f2a2;">géneros <i class="bx bx-x icon-close"></i></span>
                        </div>
                        <!-- Un select con un botón de añadir género -->
                        <div class="genre-select">
                            <select id="available-genres">
                                <option value="X">Seleccione un género</option>
                            </select>
                            <button class="btn" id="add-genre">Añadir género</button>
                        </div>
                    </div>
                    <!-- Un listado de autores con un X al final de cada uno, al dar clic a la X se quita del listado -->                    
                    <!-- Un listado de autores con una X al final -->
                    <div id="authors">
                        <div id="author-list">
                            <span style="background-color: grey;">Aquí <i class="bx bx-x icon-close"></i></span>
                            <span style="background-color: grey;">van <i class="bx bx-x icon-close"></i></span>
                            <span style="background-color: grey;">los <i class="bx bx-x icon-close"></i></span>
                            <span style="background-color: grey;">autores <i class="bx bx-x icon-close"></i></span>
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
                    <button class="btn" id="update-book">Actualizar libro</button>
                    <button class="btn" id="cancel-edit">Cancelar edición</button>
                    <button class="btn" id="delete-book">Borrar libro</button>
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
            <!-- Comienza una sección donde se jalen las secciones existentes en componentes de acordeón con TinyMCE, y un botón más para nueva sección -->
            <div class="fila" id="book-description">
                <div class="col w100">
                    <div style="display: inline-flex; justify-content: space-between; margin-bottom: 15px;">
                        <h4>Secciones</h4>
                        <button id="add-section">Añadir sección/capítulo</button>
                    </div>
                    <button class="accordion-button" onclick="toggle('section-1');">Sección 1: El título de la sección</button>
                    <div class="accordion-section" id="section-1">
                        <div class="section-title-functions">
                            <input type="text" value="El título de la sección">
                            <button class="remove-section">Eliminar sección</button>
                        </div>
                        <textarea class="section-content">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Reiciendis tempora aperiam rem iure dolorem, eos eveniet hic doloribus facere impedit quae tempore molestias molestiae cumque numquam perferendis accusamus natus ab veritatis iusto. Maiores ea expedita architecto iure aspernatur illo sint, eum perferendis officiis repudiandae, voluptate deleniti quod consectetur itaque. Voluptates?</textarea>
                    </div>
                    <button class="accordion-button" onclick="toggle('section-2');">Sección 2: El título de la sección</button>
                    <div class="accordion-section" id="section-2">
                        <div class="section-title-functions">
                            <input type="text" value="El título de la sección">
                            <button class="remove-section">Eliminar sección</button>
                        </div>
                        <textarea class="section-content">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Reiciendis tempora aperiam rem iure dolorem, eos eveniet hic doloribus facere impedit quae tempore molestias molestiae cumque numquam perferendis accusamus natus ab veritatis iusto. Maiores ea expedita architecto iure aspernatur illo sint, eum perferendis officiis repudiandae, voluptate deleniti quod consectetur itaque. Voluptates?</textarea>
                    </div>
                </div>
            </div>
            <!-- Termina una sección donde se jalen las secciones existentes en componentes de acordeón con TinyMCE, y un botón más para nueva sección -->
            <!-- Comienzan Inputs ocultos, para poder pasarle variables al JS -->
            <input type="hidden" value="<?php echo $book_id;?>" id="book-id">
            <input type="hidden" value="<?php echo $user_id;?>" id="user-id">
            <!-- Terminan Inputs ocultos -->
        </section>