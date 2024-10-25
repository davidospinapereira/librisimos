        <!-- Cargaremos la herramienta de lectura manualmente en cada página que lo necesite -->
        <div class="read-overlay">
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
        <!-- Termina herramienta de lectura -->
        <!-- Comienza contenido -->
        <section class="contenido" id="inicio">
            <div class="fila">
                <div class="col w70" id="main-data">
                    <h3 id="nombre-usuario">Bienvenido(a),<br/><span style="text-transform: uppercase;"><?php echo $perfil['nombres'] . ' ' . $perfil['apellidos']; ?></span></h3>
                    <h5 id="tipo-usuario">
                        <?php
                            // 1-SUPERADMIN, 2-ADMIN, 3-SUSCRIPTOR
                            switch ($perfil['id_tipo'])
                            {
                                case 1:
                                    echo "Súper administrador del sistema";
                                    break;
                                case 2:
                                    echo "Administrador";
                                    break;
                                case 3:
                                    echo "Suscriptor";
                                    break;
                                default:
                                    echo "Error en el tipo de usuario";
                                    break;
                            }
                        ?>
                    </h5>
                    <h6 id="id-usuario">ID: <span><?php echo $user_id; ?></span></h6>
                    <input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
                </div>
                <?php
                if ($perfil['id_tipo'] != 3)
                {
                    // Si el tipo de usuario es diferente a "Suscriptor", cargue estadísticas
                    ?>
                    <!-- Comienzan estadísticas. Esto cambia si el usuario es suscriptor, debo sacar los segmentos de los prototipos -->
                    <div class="col w30" id="statistics">
                        <h2>Estadísticas</h2>
                        <!-- Spinner, cuando este se activa las estadísticas se desactivan -->
                        <div class="spinner" id="stats-spinner">
                            <span class="loader"></span>
                        </div>
                        <!-- Termina Spinner -->
                        <!-- Comienzan datos a mostrar, cuando estas se activan el Spinner se desactiva -->
                        <div class="data active" id="stats-data">
                            
                        </div>
                        <!-- Terminan datos a mostrar -->
                    </div>
                    <!-- Terminan estadísticas -->
                    <?php
                }
                else
                {
                    // Si no, que cargue las tarjetas por AJAX
                    // Primero, debemos sacar las secciones leídas por el usuario
                    // Luego, la ID de a qué libro corresponden las secciones
                    // Con la ID del libro, sacamos los géneros
                    // Luego, sacamos un listado de libros que correspondan a esos géneros
                    ?>
                    <div class="col w30" id="side-cards">
                        <!-- Spinner, cuando este se activa las tarjetas se desactivan -->
                        <div class="spinner" id="cards-spinner">
                            <span class="loader"></span>
                        </div>
                        <!-- Termina Spinner -->
                        <!-- Comienzan tarjetas, cuando estas se activan el Spinner se desactiva -->
                        <div class="cards active" id="cards-data">

                        </div>
                        <!-- Terminan tarjetas -->
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="fila">
                <!-- Comienzan funciones disponibles, cambian dependiendo del tipo de usuario -->
                <div class="col w100" id="functions">
                    <h3>Funciones disponibles</h3>
                    <div id="listado-funciones">
                        <?php
                        // Esto solo sucede si el tipo de usuario es diferente a suscriptor
                        if ($perfil['id_tipo'] != 3)
                        {
                            ?>
                            <a href="index.php?page=new-book" class="btn">Nuevo libro</a>
                            <a href="index.php?page=new-author" class="btn">Nuevo autor</a>
                            <a href="index.php?page=genre-list" class="btn">Editar géneros</a>
                            <a href="index.php?page=new-user" class="btn">Nuevo usuario</a>
                            <a href="index.php?page=user-list" class="btn">Editar usuarios</a>
                            <?php
                        }
                        ?>
                        <!-- Y esto sucede para todos los usuarios -->
                        <a href="index.php?page=book-search" class="btn">Buscar libros</a>
                        <a href="index.php?page=author-search" class="btn">Buscar autores</a>
                    </div>
                </div>
                <!-- Terminan funciones rápidas -->
            </div>
            <!-- Comienza la sección de continuar leyendo -->
            <div class="fila">
                <div class="col w100" id="continue-reading">
                    <div id="table-overlay"><span class="loader"></span></div>
                    <table class="continue-table" id="continue-reading-table">                        
                    </table>
                </div>
            </div>
            <!-- Comienza la sección de continuar leyendo -->
        </section>
        <!-- Termina contenido -->