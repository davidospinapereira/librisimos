    <!-- Cargaremos la herramienta de lectura manualmente en cada página que lo necesite -->
    <div class="read-overlay">
            <!-- Comienza popup con la herramienta -->
            <div class="read-space">
                <!-- Botón de cierre -->
                <div class="close-icon" id="close-read-tool"><i class='bx bx-x'></i></div>
                <!-- Comienza contenedor de datos -->
                <div class="data-container">
                    <!-- Comienza información del libro -->
                    <div class="book-container">
                        <h2 class="book-title">Cosmos</h2>
                        <div class="book-meta">
                            <div class="book-author"><h4>Carl Sagan</h4></div>
                            <div class="book-genres"><span style="background-color: blue; border-color: blue;">Divulgación científica</span><span style="background-color: blueviolet; border-color: blueviolet;">Fantasía</span></div>
                        </div>
                    </div>
                    <!-- Termina información del libro -->
                     <!-- Comienza contenedor de sección -->
                    <div class="section-container">
                        <div class="section-meta">
                            <div class="section-number">
                                <h4>Parte/Capítulo: <b>13</b></h4>
                            </div>
                            <div class="section-title">
                                <h4>¿Quién hablará en nombre de la Tierra?</h4>
                            </div>
                        </div>
                        <div class="section-content">
                            <!-- Un recuadro con un margen, con overflow interno y con estilos cuadrados para lectura. -->
                             <div class="content">
                                <!-- Comienza contenido de la sección -->
                                <p style="text-align: right;"><i><b>¿Por qué motivo tendría que ocuparme en buscar los<br/>
                                secretos de las estrellas si tengo continuamente, ante<br/>
                                mis ojos a la muerte y a la esclavitud?</b></i></p>
                                <p style="text-align: right; font-size: 13px;">Pregunta planteada a Pitágoras por Anaxímenes<br/>(hacia 600 a. de C.), según MONTAIGNE</p>
                                <br/>
                                <p>El cosmos no fue descubierto hasta ayer. Durante un millón de años era evidente para todos que aparte de la Tierra no había ningún otro lugar. Luego, en la última décima parte de un uno por ciento de la vida de nuestra especie, en el instante entre Aristarco y nosotros, nos dimos cuenta de mala gana de que no éramos el centro ni el objetivo del universo, sino que vivíamos sobre un mundo diminuto y frágil perdido en la inmensidad y en la eternidad, a la deriva por un gran océano cósmico punteado aquí y allí por centenares de miles de millones de galaxias y por mil millones de billones de estrellas. Sondeamos valientemente en las aguas y descubrimos que el océano nos gustaba, que resonaba con nuestra naturaleza. Algo en nosotros reconoce el Cosmos como su hogar. Estamos hechos de ceniza de estrellas. Nuestro origen y evolución estuvieron ligados a distantes acontecimientos cósmicos. La exploración del Cosmos es un viaje para autodescubrirnos.</p>
                                <p>Como ya sabían los antiguos creadores de mitos, somos hijos tanto del cielo como de la Tierra. En nuestra existencia sobre este planeta hemos acumulado un peligroso equipaje evolutivo, propensiones hereditarias a la agresión y al ritual, sumisión a los líderes y hostilidad hacia los forasteros, un equipaje que plantea algunas dudas sobre nuestra supervivencia. Pero también hemos adquirido compasión para con los demás, amor hacia nuestros hijos y hacia los hijos de nuestros hijos, el deseo de aprender de la historia, y una inteligencia apasionada y de altos vuelos: herramientas evidentes para que continuemos sobreviviendo y prosperando. No sabemos qué aspectos de nuestra naturaleza predominarán, especialmente cuando nuestra visión y nuestra comprensión de las perspectivas están limitadas exclusivamente a la Tierra, o lo que es peor a una pequeña parte de ella. Pero allí arriba, en la inmensidad del Cosmos, nos espera una perspectiva inescapable. Por ahora no hay signos obvios de inteligencias extraterrestres, y esto nos hace preguntamos si las civilizaciones como la nuestra se precipitan siempre de modo implacable y directo hacia la autodestrucción. Las fronteras nacionales no se distinguen cuando miramos la Tierra desde el espacio. Los chauvinismos étnicos o religiosos o nacionales son algo difíciles de mantener cuando vemos nuestro planeta como un creciente azul y frágil que se desvanece hasta convertirse en un punto de luz sobre el bastión y la ciudadela de las estrellas. Viajar ensancha nuestras perspectivas.</p>
                                <!-- Termina contenido de la sección -->
                             </div>
                        </div>
                        <div class="section-controls">
                            <div class="prev-next">
                                <button class="control" id="previous">Anterior</button>
                                <button class="control" id="next">Siguiente</button>
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
                </div>
                <!-- Comienzan estadísticas. Esto cambia en la página principal para usuarios, debo adaptarlo -->
                <div class="col w30" id="statistics">
                    <h2>Estadísticas</h2>
                    <!-- Spinner, cuando este se activa las estadísticas se desactivan -->
                    <div class="spinner">
                        <span class="loader"></span>
                    </div>
                    <!-- Termina Spinner -->
                    <!-- Comienzan datos a mostrar, cuando estas se activan el Spinner se desactiva -->
                    <div class="data active">
                        <h3>Usuarios registrados: <b>25</b></h3>
                        <h3>Libros publicados: <b>22</b></h3>
                        <h3>Libros sin publicar: <b>5</b></h3>
                        <h3>Libros leídos: <b>12</b></h3>
                    </div>
                    <!-- Terminan datos a mostrar -->
                </div>
                <!-- Terminan estadísticas -->
            </div>
            <div class="fila">
                <!-- Comienzan funciones rápidas, cambian dependiendo del tipo de usuario -->
                <div class="col w100" id="functions">
                    <h3>Funciones rápidas</h3>
                    <div id="listado-funciones">
                        <a href="#" class="btn">Nuevo libro</a>
                        <a href="#" class="btn">Nuevo usuario</a>
                        <a href="#" class="btn">Editar libros</a>
                        <a href="#" class="btn">Editar usuarios</a>
                    </div>
                </div>
                <!-- Terminan funciones rápidas -->
            </div>
            <!-- Comienza la sección de continuar leyendo -->
            <div class="fila">
                <div class="col w100" id="continue-reading">
                    <!--<h3>Continúa leyendo...</h3>-->
                    <table class="continue-table">
                        <caption>Continúa leyendo</caption>
                            <!-- Encabezado de la tabla -->
                            <tr>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>Género</th>
                                <th>Capítulo/Sección actual</th>
                                <th>Acciones</th>
                            </tr>
    
                            <!-- Contenido de la tabla -->
                            <tr>
                                <td data-cell="titulo">Cosmos</td>
                                <td data-cell="autor">Carl Sagan</td>
                                <td data-cell="genero">Divulgación Científica</td>
                                <td data-cell="seccion">13 - ¿Quién hablará en nombre de la Tierra?</td>
                                <td data-cell="acciones" class="acciones">
                                    <span class="button continue" onclick="activarHerramienta()"><div class="tooltip">Continuar leyendo</div><i class='bx bx-book-reader'></i></span>
                                    <span class="button quit"><div class="tooltip">Dejar de leer</div><i class='bx bx-x' ></i></span>
                                </td>
                            </tr>
                            <tr>
                                <td data-cell="titulo">Ojos de Serpiente</td>
                                <td data-cell="autor">David Ospina</td>
                                <td data-cell="genero">Suspenso</td>
                                <td data-cell="seccion">Antídoto - Parte 1</td>
                                <td data-cell="acciones" class="acciones">
                                    <span class="button continue" onclick="activarHerramienta()"><div class="tooltip">Continuar leyendo</div><i class='bx bx-book-reader'></i></span>
                                    <span class="button quit"><div class="tooltip">Dejar de leer</div><i class='bx bx-x' ></i></span>
                                </td>
                            </tr>
                            <tr>
                                <td data-cell="titulo">20 poemas de amor y una canción desesperada</td>
                                <td data-cell="autor">Pablo Neruda</td>
                                <td data-cell="genero">Poemas</td>
                                <td data-cell="seccion">4 - Me gustas cuando callas</td>
                                <td data-cell="acciones" class="acciones">
                                    <span class="button continue" onclick="activarHerramienta()"><div class="tooltip">Continuar leyendo</div><i class='bx bx-book-reader'></i></span>
                                    <span class="button quit"><div class="tooltip">Dejar de leer</div><i class='bx bx-x' ></i></span>
                                </td>
                            </tr>
                    </table>
                </div>
            </div>
            <!-- Comienza la sección de continuar leyendo -->
        </section>
        <!-- Termina contenido -->