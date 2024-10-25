<?php
    require '../model/database_handler.php';
    $json_file = '../model/connection_data.json';
    /* Comienza invocación AJAX */
    if(isset($_POST['get_list']))
    {
        echo generar_tabla($_POST['user_id'], $json_file);
    }
    
    if(isset($_POST['delete_read']))
    {
        echo dejar_de_leer($_POST['book_id'], $_POST['user_id'], $json_file);
    }
    
    if(isset($_POST['book_grid']))
    {
        echo book_grid_general($_POST['genero'], $_POST['input'], $json_file);
    }
    
    if(isset($_POST['my_books_grid']))
    {
        echo my_books_grid($_POST['user_id'], $_POST['genero'], $_POST['input'], $json_file);
    }
    
    if(isset($_POST['get_book_data']))
    {
        echo json_encode(get_book_data($_POST['book_id'], $json_file));
    }
    
    if(isset($_POST['get_author_data']))
    {
        echo get_author_data($_POST['book_id'], $json_file);
    }
    
    if(isset($_POST['get_book_list']))
    {
        echo get_book_list($_POST['user_id'], $_POST['book_id'], $json_file);
    }
    
    if(isset($_POST['get_similar_books']))
    {
        echo get_similar_books($_POST['book_id'], $json_file);
    }

    if (isset($_POST['is_user_reading']))
    {
        echo is_user_reading($_POST['user_id'], $_POST['book_id'], $json_file);
    }

    if (isset($_POST['quit_reading']))
    {
        echo quit_reading($_POST['user_id'], $_POST['book_id'], $json_file);
    }

    if (isset($_POST['get_book_edit_data']))
    {
        echo json_encode(get_book_edit_data($_POST['book_id'], $json_file));
    }

    if (isset($_POST['get_book_status']))
    {
        echo get_book_status($_POST['book_id'], $json_file);
    }

    if (isset($_POST['update_book']))
    {
        echo update_book($_POST['id_libro'], $_POST['nombre_libro'], json_decode($_POST['generos_libro']), json_decode($_POST['autores_libro']), $_POST['sinopsis_libro'], $_POST['url_imagen_save'], $json_file);
    }

    if (isset($_POST['publish_book']))
    {
        echo publish_book($_POST['id_libro'], $_POST['nombre_libro'], json_decode($_POST['generos_libro']), json_decode($_POST['autores_libro']), $_POST['sinopsis_libro'], $_POST['url_imagen_save'], $json_file);
    }

    if (isset($_POST['borrar_libro']))
    {
        echo borrar_libro($_POST['id_libro'], $json_file);
    }

    if (isset($_POST['nuevo_libro']))
    {
        echo nuevo_libro($_POST['nombre_libro'], json_decode($_POST['generos_libro']), json_decode($_POST['autores_libro']), $_POST['sinopsis_libro'], $_POST['url_imagen_libro'], $json_file);
    }
    
    if(isset($_POST['get_author_books']))
    {
        echo get_author_books($_POST['author_id'], $json_file);
    }
    /* Termina invocación AJAX */

    /* Comienza la función que genera los datos de la tabla de continuar leyendo */
    function generar_tabla($user_id, $json_file)
    {
        // Debe devolver String
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql = 
        "SELECT l.`id_libro`, l.`nombre_libro`, GROUP_CONCAT(DISTINCT a.`nombre_autor` SEPARATOR ', ') AS `nombre_autor`, GROUP_CONCAT(DISTINCT g.`nombre_genero` SEPARATOR ', ') AS `nombre_genero`, s.`id_seccion`, s.`titulo_seccion`, s.`numero_seccion` FROM `ver_seccion` vs JOIN `seccion` s ON vs.`id_seccion` = s.`id_seccion` JOIN componer_seccion cs ON s.`id_seccion` = cs.`id_seccion` JOIN libro l ON cs.`id_libro` = l.`id_libro` JOIN `autores_libro` al ON l.`id_libro` = al.`id_libro` JOIN `autor` a ON al.`id_autor` = a.`id_autor` JOIN `generos_libro` gl ON l.`id_libro` = gl.`id_libro` JOIN `genero` g ON gl.`id_genero` = g.`id_genero` WHERE vs.`id_usuario` = $user_id AND vs.`id_seccion` = (SELECT MAX(vs_inner.`id_seccion`) FROM `ver_seccion` vs_inner JOIN seccion s_inner ON vs_inner.`id_seccion` = s_inner.`id_seccion` JOIN `componer_seccion` cs_inner ON s_inner.`id_seccion` = cs_inner.`id_seccion` WHERE vs_inner.`id_usuario` = $user_id AND cs_inner.`id_libro` = l.`id_libro` AND vs_inner.`fecha_lectura_ver_seccion` = vs.`fecha_lectura_ver_seccion`) GROUP BY l.`id_libro`, s.`id_seccion` ORDER BY vs.`fecha_lectura_ver_seccion` DESC";
        /* // Ejecutamos la sentencia
        $sentencia = mysqli_query($conexion, $sql); */
        if ($sentencia = mysqli_query($conexion, $sql))
        {
            $respuesta = 
            "
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
            ";
            /* obtener array asociativo */
            while ($row = mysqli_fetch_assoc($sentencia)) 
            {
                $respuesta .= 
                "
                    <tr>
                        <td data-cell='titulo'>".$row['nombre_libro']."</td>
                        <td data-cell='autor'>".$row['nombre_autor']."</td>
                        <td data-cell='genero'>".$row['nombre_genero']."</td>
                        <td data-cell='seccion'>".$row['numero_seccion']." - " . $row['titulo_seccion']. "</td>
                        <td data-cell='acciones' class='acciones'>
                            <span class='button continue' onclick='activarHerramienta(" . $row['id_seccion'] . ", ". $user_id . ")'><div class='tooltip'>Continuar leyendo</div><i class='bx bx-book-reader'></i></span>
                            <span class='button quit' onclick='dejarDeLeer(" . $row['id_libro'] . ", " . $user_id . ")'><div class='tooltip'>Dejar de leer</div><i class='bx bx-x' ></i></span>
                        </td>
                    </tr>
                ";
            }
        }
        // Finalmente, cerramos la conexión
        cerrar_conexion($conexion);
        return $respuesta;
    }
    /* Termina la función que genera los datos de la tabla de continuar leyendo */

    /* Comienza la función que borra las lecturas del libro por parte de un usuario */
    function dejar_de_leer($book_id, $user_id, $json_file)
    {
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos la consulta
        $sql = "DELETE vs FROM `ver_seccion` vs JOIN `seccion` s ON (vs.`id_seccion` = s.`id_seccion`) JOIN componer_seccion cs ON (s.`id_seccion` = cs.`id_seccion`) WHERE cs.`id_libro` = $book_id AND vs.`id_usuario` = $user_id";
        $respuesta = '';
        try
        {
            // Ejecutamos la sentencia
            $sentencia = mysqli_query($conexion, $sql);
            // Si la sentencia funciona que devuelva un código de mensaje
            $respuesta = "SUCCESS";
        }
        catch(Exception $e)
        {
            // Si en algo falla, que devuelva el error
            $respuesta = $e;
        }
        finally
        {
            // Finalmente, cerramos la conexión
            cerrar_conexion($conexion);
            // Y retornamos la respuesta
            return $respuesta;
        }
    }
    /* Termina la función que borra las lecturas del libro por parte de un usuario */

    /* Comienza la función que genera las tarjetas en la página de biblioteca */
    function book_grid_general($id_genero, $termino_busqueda, $json_file)
    {
        // Debe devolver booleano
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Ahora, hagamos un valor adicional
        $sql_genero = ($id_genero == 0) ? "" : "g.id_genero = '$id_genero' AND ";
        // Luego preparamos un statement
        $sql = 
        "SELECT l.`id_libro`, l.`url_caratula_libro`, l.`nombre_libro`, a.`nombre_autor`, g.`nombre_genero`, g.`color_genero`, COUNT(DISTINCT(cs.`id_seccion`)) AS cantidad_secciones, l.`sinopsis_libro` FROM `libro` l INNER JOIN `componer_seccion` cs ON (cs.`id_libro` = l.`id_libro`) INNER JOIN `autores_libro` al ON (al.`id_libro` = l.`id_libro`) INNER JOIN `autor` a ON (a.`id_autor` = al.`id_autor`) INNER JOIN `generos_libro` gl ON (gl.`id_libro` = l.`id_libro`) INNER JOIN `genero` g ON (g.`id_genero` = gl.`id_genero`) WHERE " . $sql_genero . "(l.nombre_libro LIKE '%$termino_busqueda%' OR a.nombre_autor LIKE '%$termino_busqueda%' OR g.nombre_genero LIKE '%$termino_busqueda%' OR l.sinopsis_libro LIKE '%$termino_busqueda%') GROUP BY l.id_libro";
        // Ejecutamos la sentencia
        try 
        {
            // Si hay respuesta, que la genere
            if ($sentencia = mysqli_query($conexion, $sql))
            {
                $respuesta .= "<div class='col w100' id='cards-grid'>";
                if (mysqli_num_rows($sentencia) > 0) 
                {
                    while ($row = mysqli_fetch_assoc($sentencia)) 
                    {
                        $id_libro = $row['id_libro'];
                        if ($row['url_caratula_libro'] != '' || $row['url_caratula_libro'] != NULL)
                        {
                            $url_caratula_libro = $row['url_caratula_libro'];
                        }
                        else
                        {
                            $url_caratula_libro = './view/uploads/books/generic-book-cover.jpg';
                        }
                        $nombre_libro = $row['nombre_libro'];
                        $nombre_autor = $row['nombre_autor'];
                        $nombre_genero = $row['nombre_genero'];
                        $color_genero = $row['color_genero'];
                        $cantidad_secciones = $row['cantidad_secciones'];
                        $sinopsis_libro = substr($row['sinopsis_libro'], 0, 45) . "...";
                        $respuesta .= 
                        "
                        <div class='card'>
                            <a href='index.php?page=book-page&book-id=$id_libro'>
                                <div class='poster'>
                                    <img src='./$url_caratula_libro'>
                                </div>
                                <div class='details'>
                                    <h3>$nombre_autor</h3>
                                    <h2>$nombre_libro</h2>
                                    <div class='genres'>
                                        <span style='background-color: #$color_genero'>$nombre_genero</span>
                                    </div>
                                    <div class='sections'>
                                        <span>$cantidad_secciones secciones/capítulos</span>
                                    </div>
                                    <div class='sinopsis'>
                                        <p>$sinopsis_libro</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        ";
                    }
                    $respuesta .= "</div>";
                }
                // Si no, que mande la sección de nada encontrado
                else
                {
                    $respuesta = '<div class="col w100" id="nothing-found"><h2>¡OOPS! Su búsqueda no arrojó ningún resultado</h2><h4>Por favor cambie los parámetros de búsqueda.</h4></div>';
                }
            }
        } 
        catch (Exception $e) 
        {
            $respuesta = "<div class='col w100' id='nothing-found'><h2>¡ERROR! Hay un error en el programa</h2><h4>$error</h4><h4>Por favor consulte al administrador.</h4></div>";
        }
        finally
        {
            cerrar_conexion($conexion);
            return $respuesta;
        }
    }
    /* Termina la función que genera las tarjetas en la página de biblioteca */

    /* Comienza la función que jala los datos para la ficha inicial en la página de libro */
    function get_book_data($book_id, $json_file)
    {
        // Debe devolver array
        $respuesta = array();
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Primero, Generamos el SQL del libro
        $sql_libro = 
        "SELECT `nombre_libro`, `url_caratula_libro`, `sinopsis_libro` FROM `libro` WHERE `id_libro` = $book_id";
        // Segundo, generamos el SQL de los géneros
        $sql_genero = 
        "SELECT g.`nombre_genero`, g.`color_genero` FROM `genero` g INNER JOIN `generos_libro` gl ON (gl.`id_genero` = g.`id_genero`) WHERE gl.`id_libro` = $book_id";
        // Aplicamos los SQL dentro de un try
        try 
        {
            $nombre_libro = '';
            $url_caratula_libro = '';
            $sinopsis_libro = '';
            $generos_html = '';
            // Primero, el SQL del libro
            if ($sentencia_libro = mysqli_query($conexion, $sql_libro))
            {
                // Si se generó la sentencia
                if (mysqli_num_rows($sentencia_libro) > 0) 
                {
                    // Si la respuesta trajo datos, sólo me traerá una fila, por lo que puedo pasar todos los datos
                    $resultado = mysqli_fetch_assoc($sentencia_libro);
                    $nombre_libro = $resultado['nombre_libro'];
                    $url_caratula_libro = $resultado['url_caratula_libro'];
                    $sinopsis_libro = $resultado['sinopsis_libro'];
                    // Segundo, el SQL de géneros
                    if ($sentencia_generos = mysqli_query($conexion, $sql_genero))
                    {
                        //<span class="genre" style="background-color: blue;">Aventura</span>
                        while ($row = mysqli_fetch_assoc($sentencia_generos))
                        {
                            $nombre_genero = $row['nombre_genero'];
                            $color_genero = $row['color_genero'];
                            $generos_html .= "<span class='genre' style='background-color: #$color_genero;'>$nombre_genero</span> ";
                        }
                    }
                    // Confiemos, no hagamos un else
                    // Luego, preparamos el array
                    $respuesta = 
                    [
                        'codigo' => 'SUCCESS',
                        'nombre_libro' => $nombre_libro,
                        'url_caratula_libro' => $url_caratula_libro,
                        'sinopsis_libro' => $sinopsis_libro,
                        'generos_html' => $generos_html
                    ];
                }
                else
                {
                    // Si la respuesta no generó datos
                    $respuesta = 
                    [
                        'codigo' => 'ERROR',
                        'error' => 'NO_DATA_GENERATED'
                    ];
                }
            }
            else
            {
                // Si no se generó la sentencia
                $respuesta = 
                [
                    'codigo' => 'ERROR',
                    'error' => 'NULL_QUERY'
                ];
            }
        } 
        catch (Exception $e) 
        {
            // Si hay algún error pare todo y devuelva la respuesta
            $respuesta = 
            [
                'codigo' => 'ERROR',
                'error' => $e
            ];
        }
        // Finalmente, cerramos la conexión y devolvemos la respuesta
        cerrar_conexion($conexion);
        return $respuesta;
    }
    /* Termina la función que jala los datos para la ficha inicial en la página de libro */

    /* Comienza la función que jala los autores para la ficha inicial en la página de libro */
    function get_author_data($book_id, $json_file)
    {
        // Debe devolver string
        $respuesta = '';
        // Necesitamos un array secundario
        $autores = array();
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Primero, Generamos el SQL del libro
        $sql = 
        "SELECT a.`id_autor`, a.`nombre_autor` FROM `autor` a INNER JOIN `autores_libro` al ON (al.`id_autor` = a.`id_autor`) WHERE al.`id_libro` = $book_id";
        // Ejecutamos la sentencia
        try 
        {
            // Si hay respuesta, que la genere
            if ($sentencia = mysqli_query($conexion, $sql))
            {
                if (mysqli_num_rows($sentencia) > 0) 
                {
                    while ($row = mysqli_fetch_assoc($sentencia)) 
                    {
                        // Añadimos el autor y el ID que nos llega al array
                        $id_autor = $row['id_autor'];
                        $nombre_autor = $row['nombre_autor'];
                        $autores[] = "<a href='index.php?page=author-page&author-id=$id_autor'>$nombre_autor</a>";
                    }
                    // Cuando terminamos de jalar todos los autores, convertimos el array en un string con el separador
                    $respuesta = implode(' - ', $autores);
                }
                // Si es vacío, que muestre error
                else
                {
                    $respuesta .= "Error: Resultado vacío";
                }
            }
            // Si no se generó el query, que lo muestre
            else
            {
                $respuesta .= "Error: Query nulo";
            }
        } 
        catch (Exception $e) 
        {
            $respuesta .= "Error: " . $e;
        }
        cerrar_conexion($conexion);
        return $respuesta;
    }
    /* Termina la función que jala los autores para la ficha inicial en la página de libro */

    /* Comienza función que genera la tabla de secciones en la página de libro */
    function get_book_list($user_id, $book_id, $json_file)
    {
        // Debe devolver String
        $respuesta = '';
        // Debo tener un array secundario para las secciones leídas por el usuario
        $leidas = array();
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql_secciones = 
        "SELECT s.`id_seccion`, s.`numero_seccion`, s.`titulo_seccion` FROM `seccion` s INNER JOIN `componer_seccion` cs ON (cs.`id_seccion` = s.`id_seccion`) WHERE cs.`id_libro` = $book_id ORDER BY s.`numero_seccion` ASC";
        $sql_leidos = 
        "SELECT cs.`id_seccion` FROM `componer_seccion` cs INNER JOIN `ver_seccion` vs ON (vs.`id_seccion` = cs.`id_seccion`) WHERE vs.`id_usuario` = $user_id AND cs.`id_libro` = $book_id";
        // Ejecutamos la sentencia
        if ($sentencia_secciones = mysqli_query($conexion, $sql_secciones))
        {
            $respuesta .= 
            "
                <div class='col w100'>
                    <table class='section-table'>
                        <!-- Nombre de la tabla -->
                        <caption>Secciones</caption>
                        <!-- Encabezado de la tabla -->
                        <tr>
                            <th class='seccion'>Sección</th>
                            <th class='acciones'>Acciones</th>
                        </tr>

                        <!-- Contenido de la tabla -->
            ";
            // Antes de hacer un loop, llenamos el array de leídas
            if($sentencia_leidos = mysqli_query($conexion, $sql_leidos))
            {
                // Sacamos sólo los ids
                while ($row = mysqli_fetch_assoc($sentencia_leidos))
                {
                    $leidas[] = $row['id_seccion'];
                }
            }
            /* obtener array asociativo */
            while ($row = mysqli_fetch_assoc($sentencia_secciones)) 
            {
                $id_seccion = $row['id_seccion'];
                $numero_seccion = $row['numero_seccion'];
                $titulo_seccion = $row['titulo_seccion'];
                // Aquí debo meter la lógica para que, en vez de decir "Continuar leyendo", diga "Leer de nuevo" cuando la sección ya está leída
                $boton_leer = in_array($id_seccion, $leidas) ? "<span class='button again' onclick='activarHerramienta($id_seccion, $user_id)'><div class='tooltip'>Leer de nuevo</div><i class='bx bx-book-reader'></i></span>" : "<span class='button continue' onclick='activarHerramienta($id_seccion, $user_id)'><div class='tooltip'>Leer</div><i class='bx bx-book-reader'></i></span>";
                $respuesta .= 
                "
                    <tr>
                        <td data-cell='seccion' class='seccion'>$numero_seccion - $titulo_seccion</td>
                        <td data-cell='acciones' class='acciones'>
                            $boton_leer
                        </td>
                    </tr>
                ";
            }
            $respuesta .= 
            "</table>
                </div>";
        }
        // Finalmente, cerramos la conexión
        cerrar_conexion($conexion);
        return $respuesta;
    }
    /* Termina función que genera la tabla de secciones en la página de libro */

    /* Comienza función que genera el grid de libros relacionados */
    function get_similar_books($book_id, $json_file)
    {
        // Debe devolver String
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql = 
        "SELECT l.`id_libro`, l.`nombre_libro`, l.`sinopsis_libro`, a.`nombre_autor`, l.`url_caratula_libro`, g.`nombre_genero`, g.`color_genero`, COUNT(DISTINCT(cs.`id_seccion`)) AS cantidad_secciones FROM libro l JOIN generos_libro gl ON l.`id_libro` = gl.`id_libro` JOIN genero g ON gl.`id_genero` = g.`id_genero` JOIN autores_libro al ON l.`id_libro` = al.`id_libro` JOIN autor a ON al.`id_autor` = a.`id_autor` JOIN componer_seccion cs ON l.`id_libro` = cs.`id_libro` WHERE (g.`id_genero` IN (SELECT `id_genero` FROM `generos_libro` WHERE `id_libro` = $book_id) OR l.`id_libro` IN ( SELECT `id_libro` FROM `autores_libro` WHERE `id_autor` IN (SELECT `id_autor` FROM `autores_libro` WHERE `id_libro` = $book_id))) AND l.`id_libro` != $book_id GROUP BY l.`id_libro` ORDER BY l.`lecturas_libro` DESC LIMIT 4";
        // Ejecutamos la sentencia
        if ($sentencia = mysqli_query($conexion, $sql))
        {
            $respuesta .= 
            "
                <!-- Comienza grid de tarjetas -->
            ";
            /* obtener array asociativo */
            while ($row = mysqli_fetch_assoc($sentencia)) 
            {
                $id_libro = $row['id_libro'];
                $nombre_libro = $row['nombre_libro'];
                $sinopsis_libro = substr($row['sinopsis_libro'], 0, 45) . "...";
                $nombre_autor = $row['nombre_autor'];
                $nombre_genero = $row['nombre_genero'];
                $color_genero = $row['color_genero'];
                $url_caratula_libro = $row['url_caratula_libro'];
                $cantidad_secciones = $row['cantidad_secciones'];
                // Aquí debo meter la lógica para que, en vez de decir "Continuar leyendo", diga "Leer", y ya me compliqué la vida
                $respuesta .= 
                "
                    <div class='card'>
                        <a href='index.php?page=book-page&book-id=$id_libro'>
                            <div class='poster'>
                                <img src='./$url_caratula_libro'>
                            </div>
                            <div class='details'>
                                <h3>$nombre_autor</h3>
                                <h2>$nombre_libro</h2>
                                <div class='genres'>
                                    <span style='background-color: #$color_genero'>$nombre_genero</span>
                                </div>
                                <div class='sections'>
                                    <span>$cantidad_secciones secciones/capítulos</span>
                                </div>
                                <div class='sinopsis'>
                                    $sinopsis_libro
                                </div>
                            </div>
                        </a>
                    </div>
                ";
            }
            $respuesta .= 
            "<!-- Termina grid de tarjetas -->";
        }
        // Finalmente, cerramos la conexión
        cerrar_conexion($conexion);
        return $respuesta;
    }
    /* Termina función que genera el grid de libros relacionados */

    /* Comienza función que muestra el botón de dejar de leer sólo si el usuario está leyendo un libro */
    function is_user_reading($user_id, $book_id, $json_file)
    {
        // Debe devolver String
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql_is_reading = 
        "SELECT vs.`id_seccion` FROM `ver_seccion` vs INNER JOIN `componer_seccion` cs ON (cs.`id_seccion` = vs.`id_seccion`) WHERE vs.`id_usuario` = $user_id AND cs.`id_libro` = $book_id";
        $sql_user_type = 
        "SELECT tu.`id_tipo_usuario` FROM `tipo_usuario` tu INNER JOIN `usuario` u ON (u.`id_tipo_usuario` = tu.`id_tipo_usuario`) WHERE u.`id_usuario` = $user_id";
        try 
        {
            if ($sentencia_leyendo = mysqli_query($conexion, $sql_is_reading))
            {
                $respuesta .= "<div class='col w100'>";
                // Si la respuesta tiene campos, es porque el libro está leído, y aplica el botón
                if(mysqli_num_rows($sentencia_leyendo) > 0)
                {
                    $respuesta .= "<button class='control' id='stop-reading' onclick='dejarDeLeer($user_id, $book_id);'>Dejar de leer</button>";
                }
                // Si la respuesta no tiene campos, es porque no se ha leído el libro, y entonces no aplica nada
                // FUNCIÓN PARA ADMINISTRADORES = Si el tipo de usuario es menor a 3, es porque es administrador y debe poder borrar el libro
                if ($sentencia_tipo = mysqli_query($conexion, $sql_user_type))
                {
                    // Sólo debe haber un valor encontrado
                    $row = mysqli_fetch_assoc($sentencia_tipo);
                    if ($row['id_tipo_usuario'] < 3)
                    {
                        $respuesta .= " <button class='control' onclick='editarLibro($book_id)';'>Editar libro</button>";
                        /* window.location.href='index.php?page=edit-page&book-id=$book_id'; */
                    }
                }
                $respuesta .= "</div>";
            }
        }
        catch (Exception $e) 
        {
            // Si hay un error, que me lo muestre
            $respuesta .= "<div class='col w100'>Error en el programa:<br/> $e->getMessage()</div>";
        }
        finally
        {
            cerrar_conexion($conexion);
            return $respuesta;
        }
    }
    /* Termina función que muestra el botón de dejar de leer sólo si el usuario está leyendo un libro */

    /* Comienza función para dejar de leer un libro */
    function quit_reading($user_id, $book_id, $json_file)
    {
        // Debe devolver String
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Debemos preparar un statement
        $sql = 
        "DELETE vs FROM `ver_seccion` vs INNER JOIN `componer_seccion` cs ON cs.`id_seccion` = vs.`id_seccion` WHERE vs.`id_usuario` = $user_id AND cs.`id_libro` = $book_id;";
        // Abrimos try-catch
        try 
        {
            $sentencia = mysqli_query($conexion, $sql);
            $respuesta = 'SUCCESS';
        } 
        catch (Exception $e) 
        {
            $respuesta = $e;
        }
        finally
        {
            cerrar_conexion($conexion);
            return $respuesta;
        }
    }
    /* Termina función para dejar de leer un libro */

    /* Comienza la función que genera las tarjetas en la página de biblioteca */
    function my_books_grid($id_usuario, $id_genero, $termino_busqueda, $json_file)
    {
        // Debe devolver booleano
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Ahora, hagamos un valor adicional
        $sql_genero = ($id_genero == 0) ? "" : "g.id_genero = '$id_genero' AND ";
        // Luego preparamos un statement
        $sql = 
        "SELECT l.`id_libro`, l.`url_caratula_libro`, l.`nombre_libro`, a.`nombre_autor`, g.`nombre_genero`, g.`color_genero`, COUNT(DISTINCT(cs.`id_seccion`)) AS cantidad_secciones, l.`sinopsis_libro` FROM `libro` l INNER JOIN `componer_seccion` cs ON (cs.`id_libro` = l.`id_libro`) INNER JOIN `autores_libro` al ON (al.`id_libro` = l.`id_libro`) INNER JOIN `autor` a ON (a.`id_autor` = al.`id_autor`) INNER JOIN `generos_libro` gl ON (gl.`id_libro` = l.`id_libro`) INNER JOIN `genero` g ON (g.`id_genero` = gl.`id_genero`) INNER JOIN `ver_seccion` vs ON (vs.`id_seccion` = cs.`id_seccion`) WHERE " . $sql_genero . "vs.`id_usuario` = $id_usuario AND (l.nombre_libro LIKE '%$termino_busqueda%' OR a.nombre_autor LIKE '%$termino_busqueda%' OR g.nombre_genero LIKE '%$termino_busqueda%' OR l.sinopsis_libro LIKE '%$termino_busqueda%') GROUP BY l.id_libro";
        // Ejecutamos la sentencia
        try 
        {
            // Si hay respuesta, que la genere
            if ($sentencia = mysqli_query($conexion, $sql))
            {
                $respuesta .= "<div class='col w100' id='cards-grid'>";
                if (mysqli_num_rows($sentencia) > 0) 
                {
                    while ($row = mysqli_fetch_assoc($sentencia)) 
                    {
                        $id_libro = $row['id_libro'];
                        $url_caratula_libro = $row['url_caratula_libro'];
                        $nombre_libro = $row['nombre_libro'];
                        $nombre_autor = $row['nombre_autor'];
                        $nombre_genero = $row['nombre_genero'];
                        $color_genero = $row['color_genero'];
                        $cantidad_secciones = $row['cantidad_secciones'];
                        $sinopsis_libro = substr($row['sinopsis_libro'], 0, 45) . "...";
                        $respuesta .= 
                        "
                        <div class='card'>
                            <a href='index.php?page=book-page&book-id=$id_libro'>
                                <div class='poster'>
                                    <img src='./$url_caratula_libro'>
                                </div>
                                <div class='details'>
                                    <h3>$nombre_autor</h3>
                                    <h2>$nombre_libro</h2>
                                    <div class='genres'>
                                        <span style='background-color: #$color_genero'>$nombre_genero</span>
                                    </div>
                                    <div class='sections'>
                                        <span>$cantidad_secciones secciones/capítulos</span>
                                    </div>
                                    <div class='sinopsis'>
                                        <p>$sinopsis_libro</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        ";
                    }
                    $respuesta .= "</div>";
                }
                // Si no, que mande la sección de nada encontrado
                else
                {
                    $respuesta = '<div class="col w100" id="nothing-found"><h2>¡OOPS! Su búsqueda no arrojó ningún resultado</h2><h4>Por favor cambie los parámetros de búsqueda.</h4></div>';
                }
            }
        } 
        catch (Exception $e) 
        {
            $respuesta = "<div class='col w100' id='nothing-found'><h2>¡ERROR! Hay un error en el programa</h2><h4>$e</h4><h4>Por favor consulte al administrador.</h4></div>";
        }
        finally
        {
            cerrar_conexion($conexion);
            return $respuesta;
        }
    }
    /* Termina la función que genera las tarjetas en la página de biblioteca */

    /* Comienza función que recupera los datos del libro para la página de edición del libro */
    function get_book_edit_data($book_id, $json_file)
    {
        // Debe devolver array, y debe funcionar muy similar a get_book_data
        $respuesta = array();
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Primero, Generamos el SQL del libro
        $sql_libro = 
        "SELECT `nombre_libro`, `url_caratula_libro`, `sinopsis_libro` FROM `libro` WHERE `id_libro` = $book_id";
        // Segundo, generamos el SQL de los géneros
        $sql_generos = 
        "SELECT g.`id_genero`, g.`nombre_genero`, g.`color_genero` FROM `genero` g INNER JOIN `generos_libro` gl ON (gl.`id_genero` = g.`id_genero`) WHERE gl.`id_libro` = $book_id";
        // Tercero, generamos el SQL de los autores
        $sql_autores = 
        "SELECT a.`id_autor`, a.`nombre_autor` FROM `autor` a INNER JOIN `autores_libro` al ON (al.`id_autor` = a.`id_autor`) WHERE al.`id_libro` = $book_id";
        // Aplicamos los SQL dentro de un try
        try 
        {
            $nombre_libro = '';
            $url_caratula_libro = '';
            $sinopsis_libro = '';
            $generos_html = '';
            $autores_html = '';
            // Primero, el SQL del libro
            if ($sentencia_libro = mysqli_query($conexion, $sql_libro))
            {
                // Si se generó la sentencia
                if (mysqli_num_rows($sentencia_libro) > 0) 
                {
                    // Si la respuesta trajo datos, sólo me traerá una fila, por lo que puedo pasar todos los datos
                    $resultado = mysqli_fetch_assoc($sentencia_libro);
                    $nombre_libro = $resultado['nombre_libro'];
                    $url_caratula_libro = $resultado['url_caratula_libro'];
                    $sinopsis_libro = $resultado['sinopsis_libro'];
                    // Segundo, el SQL de géneros
                    if ($sentencia_generos = mysqli_query($conexion, $sql_generos))
                    {
                        //<span style="background-color: #45f2a2;" id = "genero-1">Aquí <i class="bx bx-x icon-close" onclick="quitarBaldosa('#genero-1')"></i></span>
                        while ($row = mysqli_fetch_assoc($sentencia_generos))
                        {
                            $id_genero = $row['id_genero'];
                            $nombre_genero = $row['nombre_genero'];
                            $color_genero = $row['color_genero'];
                            $generos_html .= "<span style='background-color: #$color_genero;' id = 'genero-$id_genero'>$nombre_genero <i class='bx bx-x icon-close' onclick='quitarBaldosa(1, $id_genero)'></i></span> ";
                        }
                    }
                    if ($sentencia_autores = mysqli_query($conexion, $sql_autores))
                    {
                        //<span style="background-color: #45f2a2;" id = "genero-1">Aquí <i class="bx bx-x icon-close" onclick="quitarBaldosa('#genero-1')"></i></span>
                        while ($row = mysqli_fetch_assoc($sentencia_autores))
                        {
                            $id_autor = $row['id_autor'];
                            $nombre_autor = $row['nombre_autor'];
                            $autores_html .= "<span id = 'autor-$id_autor'>$nombre_autor <i class='bx bx-x icon-close' onclick='quitarBaldosa(2, $id_autor)'></i></span> ";
                        }
                    }
                    // Confiemos, no hagamos un else
                    // Luego, preparamos el array
                    $respuesta = 
                    [
                        'codigo' => 'SUCCESS',
                        'nombre_libro' => $nombre_libro,
                        'url_caratula_libro' => $url_caratula_libro,
                        'sinopsis_libro' => $sinopsis_libro,
                        'generos_html' => $generos_html,
                        'autores_html' => $autores_html
                    ];
                }
                else
                {
                    // Si la respuesta no generó datos
                    $respuesta = 
                    [
                        'codigo' => 'ERROR',
                        'error' => 'NO_DATA_GENERATED'
                    ];
                }
            }
            else
            {
                // Si no se generó la sentencia
                $respuesta = 
                [
                    'codigo' => 'ERROR',
                    'error' => 'NULL_QUERY'
                ];
            }
        } 
        catch (Exception $e) 
        {
            // Si hay algún error pare todo y devuelva la respuesta
            $respuesta = 
            [
                'codigo' => 'ERROR',
                'error' => $e
            ];
        }
        // Finalmente, cerramos la conexión y devolvemos la respuesta
        finally
        {
            cerrar_conexion($conexion);
            return $respuesta;
        }
    }
    /* Termina función que recupera los datos del libro para la página de edición del libro */

    /* Comienza función que recupera sólo el id de status del libro */
    function get_book_status($book_id, $json_file)
    {
        // Debe devolver número, ya sea como texto o como entero
        $respuesta = 0;
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Primero, Generamos el SQL del libro
        $sql_status = 
        "SELECT `clave_estado_libro` FROM `libro` WHERE `id_libro` = $book_id";
        // Sigue un try-catch
        try 
        {
            if ($sentencia_status = mysqli_query($conexion, $sql_status))
            {
                // Si la respuesta trajo datos, sólo me traerá una fila, por lo que puedo pasar todos los datos
                $resultado = mysqli_fetch_assoc($sentencia_status);
                $respuesta = $resultado['clave_estado_libro'];
            }
        } 
        catch (Exception $e) 
        {
            var_dump($e);
        }
        finally
        {
            cerrar_conexion($conexion);
            return $respuesta;
        }
    }
    /* Termina función que recupera sólo el id de status del libro */

    /* Comienza función que actualiza los datos base de un libro */
    function update_book($id_libro, $nombre_libro, $generos_libro, $autores_libro, $sinopsis_libro, $url_imagen_save, $json_file)
    {
        $respuesta = '';
        // Primero, abrimos la conexión
        $conexion = abrir_conexion($json_file);
        // Primero borramos los géneros
        $sql_borrar_generos_libro = "DELETE FROM `generos_libro` WHERE `id_libro` = '$id_libro'";
        // Después borramos los autores
        $sql_borrar_autores_libro = "DELETE FROM `autores_libro` WHERE `id_libro` = '$id_libro'";
        // SQL para los datos base del libro
        $sql_datos_libro = "UPDATE `libro` SET `nombre_libro`='$nombre_libro',`url_caratula_libro`='$url_imagen_save',`sinopsis_libro`='$sinopsis_libro' WHERE `id_libro` = '$id_libro'";
        try 
        {
            // Actualizamos el SQL base del libro
            $sentencia = mysqli_query($conexion, $sql_datos_libro);
            // Borramos géneros
            $sentencia = mysqli_query($conexion, $sql_borrar_generos_libro);
            // Borramos autores
            $sentencia = mysqli_query($conexion, $sql_borrar_autores_libro);
            // Géneros y autores vienen decodificados desde la invocación, no hace falta decodificar aquí
            // Trabajamos con géneros
            foreach ($generos_libro as $key => $id_genero) 
            {
                $sql_genero_libro = "INSERT INTO `generos_libro` (`id_libro`, `id_genero`) VALUES ('$id_libro', '$id_genero')";
                $sentencia = mysqli_query($conexion, $sql_genero_libro);
            }
            foreach ($autores_libro as $key => $id_autor) 
            {
                $sql_autor_libro = "INSERT INTO `autores_libro` (`id_libro`, `id_autor`) VALUES ('$id_libro', '$id_autor')";
                $sentencia = mysqli_query($conexion, $sql_autor_libro);
            }
            $respuesta = 'SUCCESS';
        } 
        catch (Exception $e) 
        {
            $respuesta = $e->getMessage();
        }
        finally
        {
            cerrar_conexion($conexion);
            return $respuesta;
        }
    }
    /* Termina función que actualiza los datos base de un libro */

    /* Comienza función para publicar un libro */
    function publish_book($id_libro, $nombre_libro, $generos_libro, $autores_libro, $sinopsis_libro, $url_imagen_save, $json_file)
    {
        // Generos y autores ya fueron decodificados en la invocación, no hace falta re-decodificar
        // Esta función es muy similar a la de actualizar
        $respuesta = '';
        // Primero, abrimos la conexión
        $conexion = abrir_conexion($json_file);
        // Primero borramos los géneros
        $sql_borrar_generos_libro = "DELETE FROM `generos_libro` WHERE `id_libro` = '$id_libro'";
        // Después borramos los autores
        $sql_borrar_autores_libro = "DELETE FROM `autores_libro` WHERE `id_libro` = '$id_libro'";
        // SQL para los datos base del libro
        $sql_datos_libro = "UPDATE `libro` SET `nombre_libro` = '$nombre_libro', `url_caratula_libro` = '$url_imagen_save', `sinopsis_libro`='$sinopsis_libro', `clave_estado_libro` = 1 WHERE `id_libro` = '$id_libro'";
        try 
        {
            // Actualizamos el SQL base del libro
            $sentencia = mysqli_query($conexion, $sql_datos_libro);
            // Borramos géneros
            $sentencia = mysqli_query($conexion, $sql_borrar_generos_libro);
            // Borramos autores
            $sentencia = mysqli_query($conexion, $sql_borrar_autores_libro);
            // Géneros y autores vienen decodificados desde la invocación, no hace falta decodificar aquí
            // Trabajamos con géneros
            foreach ($generos_libro as $key => $id_genero) 
            {
                $sql_genero_libro = "INSERT INTO `generos_libro` (`id_libro`, `id_genero`) VALUES ('$id_libro', '$id_genero')";
                $sentencia = mysqli_query($conexion, $sql_genero_libro);
            }
            foreach ($autores_libro as $key => $id_autor) 
            {
                $sql_autor_libro = "INSERT INTO `autores_libro` (`id_libro`, `id_autor`) VALUES ('$id_libro', '$id_autor')";
                $sentencia = mysqli_query($conexion, $sql_autor_libro);
            }
            $respuesta = 'SUCCESS';
        } 
        catch (Exception $e) 
        {
            $respuesta = $e->getMessage();
        }
        finally
        {
            cerrar_conexion($conexion);
            return $respuesta;
        }
    }
    /* Termina función para publicar un libro */

    /* Comienza función para borrar un libro completamente */
    function borrar_libro($id_libro, $json_file)
    {
        // Generos y autores ya fueron decodificados en la invocación, no hace falta re-decodificar
        // Esta función es muy similar a la de actualizar
        $respuesta = '';
        // Abrimos la conexión
        $conexion = abrir_conexion($json_file);
        try 
        {
            // Primero borramos las relaciones en componer_seccion (esto evita el error de la clave foránea)
            $sql_borrar_componer_seccion_libro = "DELETE FROM `componer_seccion` WHERE `id_libro` = '$id_libro'";
            $sentencia = mysqli_query($conexion, $sql_borrar_componer_seccion_libro);
            // Luego borramos las visualizaciones
            $sql_borrar_visualizaciones_libro = "DELETE vs FROM `ver_seccion` vs JOIN `seccion` s ON vs.`id_seccion` = s.`id_seccion` JOIN `componer_seccion` cs ON s.`id_seccion` = cs.`id_seccion` WHERE cs.`id_libro` = '$id_libro'";
            // Luego borramos las secciones
            $sql_borrar_secciones_libro = "DELETE s FROM `seccion` s JOIN `componer_seccion` cs ON s.`id_seccion` = cs.`id_seccion` WHERE cs.`id_libro` = '$id_libro'";
            $sentencia = mysqli_query($conexion, $sql_borrar_secciones_libro);
            // Borramos los géneros relacionados con el libro
            $sql_borrar_generos_libro = "DELETE FROM `generos_libro` WHERE `id_libro` = '$id_libro'";
            $sentencia = mysqli_query($conexion, $sql_borrar_generos_libro);
            // Borramos las relaciones con los autores
            $sql_borrar_autores_libro = "DELETE FROM `autores_libro` WHERE `id_libro` = '$id_libro'";
            $sentencia = mysqli_query($conexion, $sql_borrar_autores_libro);
            // Finalmente, borramos el libro
            $sql_borrar_libro = "DELETE FROM `libro` WHERE `id_libro` = '$id_libro'";
            $sentencia = mysqli_query($conexion, $sql_borrar_libro);
            $respuesta = 'SUCCESS';
        } 
        catch (Exception $e) 
        {
            $respuesta = $e->getMessage();
        }
        finally
        {
            cerrar_conexion($conexion);
            return $respuesta;
        }
    }
    /* Termina función para borrar un libro completamente */

    /* Comienza función para guardar un libro nuevo */
    function nuevo_libro($nombre_libro, $generos_libro, $autores_libro, $sinopsis_libro, $url_imagen_libro, $json_file)
    {
        // Esta función es muy similar a la de actualizar libro
        $respuesta = array();
        // Primero, abrimos la conexión
        $conexion = abrir_conexion($json_file);
        // Invocamos la función auxiliar para guardar el libro y obtener su ID
        $id_libro = guardar_libro($nombre_libro, $sinopsis_libro, $url_imagen_libro, $json_file);
        try 
        {
            if (is_numeric($id_libro)) 
            {
                // Operamos con el ID del libro y los géneros y autores si se insertó el libro correctamente
                // Trabajamos con géneros
                foreach ($generos_libro as $key => $id_genero) 
                {
                    $sql_genero_libro = "INSERT INTO `generos_libro` (`id_libro`, `id_genero`) VALUES ('$id_libro', '$id_genero')";
                    $sentencia = mysqli_query($conexion, $sql_genero_libro);
                }
                // Trabajamos con autores
                foreach ($autores_libro as $key => $id_autor) 
                {
                    $sql_autor_libro = "INSERT INTO `autores_libro` (`id_libro`, `id_autor`) VALUES ('$id_libro', '$id_autor')";
                    $sentencia = mysqli_query($conexion, $sql_autor_libro);
                }
                $respuesta = [
                    "mensaje" => "SUCCESS",
                    "book_id" => $id_libro
                ];
            } 
            else 
            {
                // Mostrar mensaje de error si no se insertó el libro correctamente
                $respuesta = [
                    "mensaje" => $id_libro
                ];
            }
        } 
        catch (Exception $e) 
        {
            $respuesta = [
                "mensaje" => $e->getMessage()
            ];
        }
        finally
        {
            cerrar_conexion($conexion);
            return json_encode($respuesta);
        }
    }
    /* Termina función para guardar un libro nuevo */

    /* Comienza función auxiliar para guardar un libro sin géneros ni autores y retornar el ID del libro guardado */
    function guardar_libro($nombre_libro, $sinopsis_libro, $url_imagen, $json_file)
    {
        $conexion = abrir_conexion($json_file);
        $respuesta = '';
        try 
        {
            // Primero, insertamos los datos del libro en la tabla 'libro', se guarda con 0 visualizaciones y como borrador
            $sql_datos_libro = "INSERT INTO `libro` (`nombre_libro`, `url_caratula_libro`, `sinopsis_libro`, `lecturas_libro`, `clave_estado_libro`) VALUES ('$nombre_libro', '$url_imagen', '$sinopsis_libro', 0, 2)";
            // Ejecutamos la consulta de inserción
            if (mysqli_query($conexion, $sql_datos_libro)) 
            {
                // Obtener el ID del libro recién insertado
                $id_libro = mysqli_insert_id($conexion);
                $respuesta = $id_libro; // Retorna el ID del libro para ser utilizado posteriormente
            } 
            else 
            {
                // Si hay error en la inserción
                $respuesta = 'ERROR: ' . mysqli_error($conexion);
            }

        } 
        catch (Exception $e) 
        {
            $respuesta = $e->getMessage();
        } 
        finally 
        {
            cerrar_conexion($conexion);
            return $respuesta; // Retorna el ID del libro o un mensaje de error
        }
    }
    /* Termina función auxiliar para guardar un libro sin géneros ni autores y retornar el ID del libro guardado */

    /* Comienza función para obtener libros de un autor específico */
    function get_author_books($author_id, $json_file)
    {
        // Debe devolver String
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql_libros_autor = 
        "SELECT l.`id_libro`, l.`nombre_libro`, l.`sinopsis_libro`, l.`url_caratula_libro`, a.`nombre_autor`, g.`nombre_genero`, g.`color_genero`, COUNT(DISTINCT(cs.`id_seccion`)) AS cantidad_secciones FROM libro l JOIN autores_libro al ON l.`id_libro` = al.`id_libro` JOIN autor a ON al.`id_autor` = a.`id_autor` JOIN generos_libro gl ON l.`id_libro` = gl.`id_libro` JOIN genero g ON gl.`id_genero` = g.`id_genero` JOIN componer_seccion cs ON l.`id_libro` = cs.`id_libro` WHERE a.`id_autor` = $author_id GROUP BY l.`id_libro`, l.`nombre_libro`, l.`sinopsis_libro`, a.`nombre_autor`, g.`nombre_genero`, g.`color_genero` ORDER BY l.`nombre_libro` DESC";
        // Ejecutamos la sentencia
        if ($sentencia = mysqli_query($conexion, $sql_libros_autor))
        {
            $respuesta .= 
            "
                <!-- Comienza grid de tarjetas -->
            ";
            /* obtener array asociativo */
            while ($row = mysqli_fetch_assoc($sentencia)) 
            {
                $id_libro = $row['id_libro'];
                $nombre_libro = $row['nombre_libro'];
                $sinopsis_libro = substr($row['sinopsis_libro'], 0, 45) . "...";
                $nombre_autor = $row['nombre_autor'];
                $nombre_genero = $row['nombre_genero'];
                $color_genero = $row['color_genero'];
                $url_caratula_libro = $row['url_caratula_libro'];
                $cantidad_secciones = $row['cantidad_secciones'];
                // Aquí debo meter la lógica para que, en vez de decir "Continuar leyendo", diga "Leer", y ya me compliqué la vida
                $respuesta .= 
                "
                    <div class='card'>
                        <a href='index.php?page=book-page&book-id=$id_libro'>
                            <div class='poster'>
                                <img src='./$url_caratula_libro'>
                            </div>
                            <div class='details'>
                                <h3>$nombre_autor</h3>
                                <h2>$nombre_libro</h2>
                                <div class='genres'>
                                    <span style='background-color: #$color_genero'>$nombre_genero</span>
                                </div>
                                <div class='sections'>
                                    <span>$cantidad_secciones secciones/capítulos</span>
                                </div>
                                <div class='sinopsis'>
                                    $sinopsis_libro
                                </div>
                            </div>
                        </a>
                    </div>
                ";
            }
            $respuesta .= 
            "<!-- Termina grid de tarjetas -->";
        }
        // Finalmente, cerramos la conexión
        cerrar_conexion($conexion);
        return $respuesta;
    }
    /* Termina función para obtener libros de un autor específico */
?>