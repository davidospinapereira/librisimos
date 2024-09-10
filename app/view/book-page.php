<?php
    // Si no hay id de libro, debemos redirigir
    if (!isset($_GET['book-id']))
    {
        header('Location: index.php?page=main');
        exit();
    }
    // Si sí, podemos arrancar
?>
        <!-- Cargaremos la herramienta de lectura manualmente en cada página que lo necesite -->
        <div class="read-overlay" style="display:none">
            <!-- Comienza popup con la herramienta -->
            <div class="read-space">
                <!-- Botón de cierre -->
                <div class="close-icon" id="close-read-tool"><i class='bx bx-x'></i></div>
                <!-- Comienza contenedor de datos -->
                <div class="data-container">
                    <!-- Comienza información del libro -->
                    <div class="overlay" id="reader-overlay"><span class="reader-loader"></span></div>
                    <div class="book-container">
                        <h2 class="book-title" id="read-tool-book-title"></h2>
                        <div class="book-meta">
                            <div class="book-author" id="read-tool-book-author"></div>
                            <div class="book-genres" id="read-tool-book-genres"></div>
                        </div>
                    </div>
                    <!-- Termina información del libro -->
                     <!-- Comienza contenedor de sección -->
                    <div class="section-container">
                        <div class="section-meta">
                            <div class="section-number" id="read-tool-section-chapter"></div>
                            <div class="section-title" id="read-tool-section-title"></div>
                        </div>
                        <div class="section-content">
                            <!-- Un recuadro con un margen, con overflow interno y con estilos cuadrados para lectura. -->
                             <div class="content" id="read-tool-section-content"></div>
                        </div>
                        <div class="section-controls">
                            <div class="prev-next">
                                <button class="control" id="read-tool-previous">Anterior</button>
                                <button class="control" id="read-tool-next">Siguiente</button>
                            </div>
                            <!-- Comienza control de modo oscuro y claro -->
                            <div class="light-dark">
                                <span class="text" id="light-dark-text">Modo oscuro</span>
                                <div class="toggle" id="dark-toggle"><i></i></div>
                            </div>
                            <!-- Termina control de modo oscuro y claro -->
                        </div>
                    </div>
                    <!-- Termina contenedor de sección -->
                </div>
                <!-- Termina contenedor de datos -->
            </div>
            <!-- Termina popup con la herramienta -->
        </div>
        <section class="contenido" id="inicio">
            <!-- Comienza Información principal del libro -->
            <div class="fila" id="main-info">
                <div class="col w20" id="image">
                    
                </div>
                <div class="col w80" id="main-data">
                    <div class="info" id="info">
                        <h3 id="author"></h3>
                        <h2 id="title"></h2>
                        <div class="genres" id="genres"></div>
                    </div>
                    <div class="sinopsis" id="sinopsis"></div>
                </div>
            </div>
            <!-- Aquí debajo debe ir el botón de dejar de leer, en rojo Y SOLO CUANDO NO SE HAYA LEÍDO -->
            <div class="fila" id="funciones"></div>
            
            <!-- Termina Información principal del libro -->
            <!-- Comienza listado de secciones -->
            <div class="fila" id="listado-secciones">
                <div class="col w100" id="loading">
                    <div class="spinner" id="cards-spinner">
                        <span class="loader"></span>
                    </div>
                </div>
            </div>
            <!-- Termina listado de secciones -->
            <!-- Comienza grid de libros semejantes -->
            <div class="fila" id="titulo-relacionados">
                <div class="col w100">
                    <h3 class="section-title">Otros libros similares</h3>
                </div>
                <div class="col w100" id="cards-loading">
                    <div class="spinner" id="cards-spinner">
                        <span class="loader"></span>
                    </div>
                </div>
            </div>
            <div class="fila" id="cards-grid">
            </div>
            <!-- Termina grid de libros semejantes -->
            <!-- Comienzan Inputs ocultos, para poder pasarle variables al JS -->
            <input type="hidden" value="<?php echo $_GET['book-id'];?>" id="book-id">
            <input type="hidden" value="<?php echo $user_id;?>" id="user-id">
            <!-- Terminan Inputs ocultos -->
        </section>