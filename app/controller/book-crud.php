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
    /* Termina invocación AJAX */

    /* Comienza la función que genera los datos de la tabla de continuar leyendo */
    function generar_tabla($user_id, $json_file)
    {
        // Debe devolver booleano
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql = 
        "SELECT vs.`id_usuario`, vs.`id_seccion`, vs.`fecha_lectura_ver_seccion`, s.`numero_seccion`, s.`titulo_seccion`, cs.`id_libro`, l.`nombre_libro`, al.`id_autor`, GROUP_CONCAT(DISTINCT(a.`nombre_autor`) SEPARATOR ', ') AS `nombre_autor`, gl.`id_genero`, GROUP_CONCAT(DISTINCT(g.`nombre_genero`) SEPARATOR ', ') AS `nombre_genero` FROM `ver_seccion` AS vs INNER JOIN `seccion` AS s ON (vs.`id_seccion` = s.`id_seccion`) INNER JOIN `componer_seccion` AS cs ON (cs.`id_seccion` = s.`id_seccion`) INNER JOIN `libro` AS l ON (l.`id_libro` = cs.`id_libro`) INNER JOIN `autores_libro` AS al ON (al.`id_libro` = l.`id_libro`) INNER JOIN `autor` AS a ON (a.`id_autor` = al.`id_autor`) INNER JOIN `generos_libro` AS gl ON (gl.`id_libro` = l.`id_libro`) INNER JOIN `genero` AS g ON (g.`id_genero` = gl.`id_genero`) INNER JOIN (SELECT cs.`id_libro`, MAX(vs.`fecha_lectura_ver_seccion`) AS max_fecha FROM `ver_seccion` AS vs INNER JOIN `componer_seccion` AS cs ON (vs.`id_seccion` = cs.`id_seccion`) WHERE vs.`id_usuario` = $user_id GROUP BY cs.`id_libro` ) AS subquery_max ON (cs.`id_libro` = subquery_max.`id_libro` AND vs.`fecha_lectura_ver_seccion` = subquery_max.`max_fecha`) WHERE vs.`id_usuario` = $user_id GROUP BY l.`id_libro` ORDER BY vs.`fecha_lectura_ver_seccion` DESC";
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
                            <span class='button continue' onclick='activarHerramienta(" . $row['id_seccion'] . ")'><div class='tooltip'>Continuar leyendo</div><i class='bx bx-book-reader'></i></span>
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
?>