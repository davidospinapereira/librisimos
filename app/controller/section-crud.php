<?php
    /* Comienza invocación de código indispensable */
    require '../model/database_handler.php';
    $json_file = '../model/connection_data.json';
    /* Termina invocación de código indispensable */

    /* Comienza invocación AJAX */
    if (isset($_POST['read_section']))
    {
        echo generar_datos_lectura($_POST['section_id'], $_POST['user_id'], $json_file);
    }

    if (isset($_POST['other_section']))
    {
        echo leer_otra_seccion($_POST['section_number'], $_POST['book_id'], $_POST['user_id'], $json_file);
    }

    if (isset($_POST['guardar_seccion']))
    {
        echo guardar_seccion($_POST['titulo_seccion'], $_POST['contenido_seccion'], $_POST['id_libro'], $json_file);
    }
    
    if (isset($_POST['obtener_secciones_edit']))
    {
        echo obtener_secciones_edit($_POST['book_id'], $_POST['book_status'], $json_file);
    }
    
    if (isset($_POST['borrar_seccion_edit']))
    {
        echo borrar_seccion_edit($_POST['current_section_id'], $json_file);
    }
    
    if (isset($_POST['update_section']))
    {
        echo update_section($_POST['section_id'], $_POST['section_title'], $_POST['section_content'], $json_file);
    }
    /* Termina invocación AJAX */
    
    /* COMIENZA CÓDIGO C (CREATE) */
    /* Comienza función para guardar una sección de un libro específico */
    function guardar_seccion($titulo_seccion, $contenido_seccion, $id_libro, $json_file)
    {
        $respuesta = '';
        // Tenemos que invocar la conexión
        $conexion = abrir_conexion($json_file);
        try 
        {
            // Esto se hace en 2 sqls
            // Primero, viene el SQL 1, que inserta el contenido de la sección, con el número de sección controlado por MySQL y no por el usuario:
            $sql1 = "INSERT INTO `seccion` (`titulo_seccion`, `contenido_seccion`, `numero_seccion`) VALUES ('$titulo_seccion', '$contenido_seccion', (SELECT IFNULL(MAX(s.`numero_seccion`), 0) + 1 FROM `seccion` AS s INNER JOIN `componer_seccion` AS cs ON cs.`id_seccion` = s.`id_seccion` WHERE cs.`id_libro` = $id_libro))";
            $sentencia = mysqli_prepare($conexion, $sql1);
            mysqli_stmt_execute($sentencia);
            // Después, viene el SQL 2, que asocia la nueva sección con el libro en la tabla `componer_seccion`:
            $sql2 = "INSERT INTO `componer_seccion` (`id_libro`, `id_seccion`) VALUES ($id_libro, LAST_INSERT_ID())";
            $sentencia = mysqli_prepare($conexion, $sql2);
            mysqli_stmt_execute($sentencia);
            // Si todo está bien, confirma la transacción
            mysqli_commit($conexion);
            // Código de respuesta
            $respuesta = "SUCCESS";
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
    /* Termina función para guardar una sección de un libro específico */
    /* TERMINA CÓDIGO C (CREATE) */

    /* COMIENZA CÓDIGO R (READ) */
    /* Comienza función que devuelve datos para la herramienta de lectura */
    function generar_datos_lectura($section_id, $user_id, $json_file)
    {
        $respuesta = array();
        $leidos = array();
        // Inicializamos estas variables para no tener problemas
        $id_libro = '';
        $conexion = abrir_conexion($json_file);
        // Preparamos los SQL para cada cosa
        // 1. Los datos de la sección
        $sql_datos_seccion = 
        "SELECT cs.`id_libro`, l.`nombre_libro`, s.`numero_seccion`, s.`titulo_seccion`, s.`contenido_seccion` FROM `seccion` AS s INNER JOIN `componer_seccion` AS cs ON (cs.`id_seccion` = s.`id_seccion`) INNER JOIN `libro` AS l ON (l.`id_libro`= cs.`id_libro`) WHERE s.`id_seccion` = $section_id";
        // 2. Los autores del libro (Mejor la reinicializo dentro del try-catch porque si no puedo tener problemas)
        $sql_autores = "";
        // 3. El listado de secciones leídas por el usuario (Mejor la reinicializo dentro del try-catch porque si no puedo tener problemas)
        $sql_leidos = "";
        // Antes que nada, un try-catch
        try 
        {
            // Sacamos los datos de la sección
            $sentencia_datos_seccion = mysqli_query($conexion, $sql_datos_seccion);
            // Verificamos si esa sección ha sido leída por el usuario
            $consulta_datos_seccion = mysqli_fetch_array($sentencia_datos_seccion);
            /* var_dump($consulta); */
            // Separamos el resultado de la búsqueda en sus componentes, no hay que hacer ifs ni nada, porque sabemos que se va a encontrar
            $id_libro = $consulta_datos_seccion['id_libro'];
            $nombre_libro = $consulta_datos_seccion['nombre_libro'];
            $numero_seccion = $consulta_datos_seccion['numero_seccion'];
            $titulo_seccion = $consulta_datos_seccion['titulo_seccion'];
            $contenido_seccion = $consulta_datos_seccion['contenido_seccion'];
            // Ahora, tenemos que generar el loop para la sección leída
            $sql_leidos = 
            "SELECT cs.`id_seccion` FROM `componer_seccion` cs INNER JOIN `ver_seccion` vs ON (vs.`id_seccion` = cs.`id_seccion`) WHERE vs.`id_usuario` = $user_id AND cs.`id_libro` = $id_libro";
            if($sentencia_leidos = mysqli_query($conexion, $sql_leidos))
            {
                // Sacamos sólo los ids
                while ($row = mysqli_fetch_assoc($sentencia_leidos))
                {
                    $leidos[] = $row['id_seccion'];
                }
            }
            // Verificamos si la sección actual ya ha sido leída por el usuario
            if (!in_array($section_id, $leidos))
            {
                // Si no ha sido leída la sección por el usuario, que registre que ya la leyó
                $sql_registrar_lectura = 
                "INSERT INTO `ver_seccion`(`id_usuario`, `id_seccion`, `fecha_lectura_ver_seccion`) VALUES ($user_id,$section_id, CURDATE())";
                $stmt_insert = mysqli_prepare($conexion, $sql_registrar_lectura);
                mysqli_stmt_execute($stmt_insert);
            }
            // Si sí está, pues no haga nada
            // Inicializamos el SQL de los autores
            $sql_autores = 
            "SELECT GROUP_CONCAT(DISTINCT(a.`nombre_autor`) SEPARATOR ', ') AS `autores_libro` FROM `autores_libro` AS al INNER JOIN `autor` AS a ON (a.`id_autor` = al.`id_autor`) WHERE al.`id_libro` = $id_libro";
            // Luego, sacamos los autores
            $sentencia_autores = mysqli_query($conexion, $sql_autores);
            // Separamos el resultado de la búsqueda en sus componentes
            $consulta_autores = mysqli_fetch_array($sentencia_autores);
            $autor_libro = $consulta_autores['autores_libro'];
            // Ahora, siguen los géneros del libro, aquí no puedo usar concatenaciones pero sí un INNER JOIN
            $sql_generos = "SELECT g.`nombre_genero`, g.`color_genero` FROM `generos_libro` AS gl INNER JOIN `genero` AS g ON (g.`id_genero` = gl.`id_genero`) WHERE gl.`id_libro` = $id_libro";
            $generos = "";
            // Ejecutamos la sentencia
            if ($sentencia_generos = mysqli_query($conexion, $sql_generos))
            {
                // obtener array asociativo
                while ($row = mysqli_fetch_assoc($sentencia_generos)) 
                {
                    $generos .= "<span style='background-color: #" . $row['color_genero'] . "; border-color: #" . $row['color_genero'] . ";'>" . $row['nombre_genero'] . "</span>";
                }
            }
            // Ahora averiguamos en qué posición se encuentra el número de la sección entre todas las secciones registradas del libro
            $sql_numeros_secciones = "SELECT s.`numero_seccion` FROM `seccion` AS s INNER JOIN `componer_seccion` AS cs ON (cs.`id_seccion` = s.`id_seccion`) WHERE cs.`id_libro` = $id_libro";
            // Generamos la sentencia
            $sentencia_numeros_secciones = mysqli_query($conexion, $sql_numeros_secciones);
            // Convertir los resultados en un array asociativo
            $secciones = [];
            while ($row = mysqli_fetch_assoc($sentencia_numeros_secciones)) 
            {
                $secciones[] = $row['numero_seccion'];
            }
            // Verificar la posición del número en el array
            if (count($secciones) === 0) 
            {
                // Hasta aquí no se supone que llegue
                $posicion_seccion = "EMPTY_ARRAY";
            }
            else if (count($secciones) === 1)
            {
                // Caso especial: si solo hay un elemento
                if ($secciones[0] == $numero_seccion) 
                {
                    // Si el número está entonces la posición es ONLY
                    $posicion_seccion = "ONLY";
                } 
                else 
                {
                    // Si el número no está entonces... no está
                    $posicion_seccion = "NOT_IN";
                }
            }
            else
            {
                // Caso general: más de un elemento
                if ($secciones[0] == $numero_seccion) 
                {
                    // El número está en la primera posición
                    $posicion_seccion = "FIRST";
                } 
                else if ($secciones[count($secciones) - 1] == $numero_seccion) 
                {
                    // El número está en la última posición
                    $posicion_seccion = "LAST";
                } 
                elseif (in_array($numero_seccion, $secciones)) 
                {
                    // El número está en la lista, pero no en la primera ni en la última posición
                    $posicion_seccion = "MIDDLE";
                } 
                else 
                {
                    // Si el número no está entonces... no está
                    $posicion_seccion = "NOT_IN";
                }
            }
            // Ahora tenemos que armar la respuesta como array
            $respuesta = array
            (
                "codigo" => 'SUCCESS',
                "book_title" => $nombre_libro,
                "book_author" => $autor_libro,
                "genres" => $generos,
                "section_number" => $numero_seccion,
                "section_title" => $titulo_seccion,
                "section_content" => $contenido_seccion,
                "section_position" => $posicion_seccion,
                "book_id" => $id_libro
            );
        } 
        catch (Exception $e) 
        {
            $respuesta = array
            (
                "codigo" => 'ERROR',
                "error" => $e
            );
        }
        finally
        {
            // Cerramos la conexión
            cerrar_conexion($conexion);
            // Retornamos la respuesta codificada como JSON
            return json_encode($respuesta);
        }
    }
    /* Termina función que devuelve datos para la herramienta de lectura */

    /* Comienza función que devuelve datos para leer otra sección dentro de la herramienta de lectura */
    function leer_otra_seccion($numero_seccion, $book_id, $user_id, $json_file)
    {
        $respuesta = array();
        // Primero, tenemos que invocar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego, viene el SQL:
        // Debemos sacar el los datos de la sección, haciendo un inner join con componer_sección tal que la ID del libro sea la ID proporcionada y el número de sección sea el proporcionado
        $sql = "SELECT s.`id_seccion`, s.`titulo_seccion`, s.`contenido_seccion` FROM `seccion` AS s INNER JOIN `componer_seccion` AS cs ON (cs.`id_seccion` = s.`id_seccion`) WHERE cs.`id_libro` = $book_id AND s.`numero_seccion` = $numero_seccion";
        // Ejecutamos la sentencia
        $sentencia = mysqli_query($conexion, $sql);
        // convertimos el resultado en un array asociativo
        $consulta = mysqli_fetch_array($sentencia);
        // Luego, guardamos los datos generados
        $titulo_seccion = $consulta['titulo_seccion'];
        $contenido_seccion = $consulta['contenido_seccion'];
        $id_seccion = $consulta['id_seccion'];
        // Finalmente, cerramos la conexión
        cerrar_conexion($conexion); 
        // Ahora averiguamos en qué posición se encuentra el número de la sección entre todas las secciones registradas del libro
        $conexion = abrir_conexion($json_file);
        $sql = "SELECT s.`numero_seccion` FROM `seccion` AS s INNER JOIN `componer_seccion` AS cs ON (cs.`id_seccion` = s.`id_seccion`) WHERE cs.`id_libro` = $book_id";
        // Generamos la sentencia
        $sentencia = mysqli_query($conexion, $sql);
        // Convertir los resultados en un array asociativo
        $secciones = [];
        while ($row = mysqli_fetch_assoc($sentencia)) 
        {
            $secciones[] = $row['numero_seccion'];
        }
        // Verificar la posición del número en el array
        if (count($secciones) === 0) 
        {
            // Hasta aquí no se supone que llegue
            $posicion_seccion = "EMPTY_ARRAY";
        }
        else if (count($secciones) === 1)
        {
            // Caso especial: si solo hay un elemento
            if ($secciones[0] == $numero_seccion) 
            {
                // Si el número está entonces la posición es ONLY
                $posicion_seccion = "ONLY";
            } 
            else 
            {
                // Si el número no está entonces... no está
                $posicion_seccion = "NOT_IN";
            }
        }
        else
        {
            // Caso general: más de un elemento
            if ($secciones[0] == $numero_seccion) 
            {
                // El número está en la primera posición
                $posicion_seccion = "FIRST";
            } 
            else if ($secciones[count($secciones) - 1] == $numero_seccion) 
            {
                // El número está en la última posición
                $posicion_seccion = "LAST";
            } 
            elseif (in_array($numero_seccion, $secciones)) 
            {
                // El número está en la lista, pero no en la primera ni en la última posición
                $posicion_seccion = "MIDDLE";
            } 
            else 
            {
                // Si el número no está entonces... no está
                $posicion_seccion = "NOT_IN";
            }
        }
        // Cerramos la conexión
        cerrar_conexion($conexion);
        // Luego, verificamos si el usuario ya vio la sección que estamos abriendo, para eso hacemos otra conexión SQL
        $conexion = abrir_conexion($json_file);
        $sql = "SELECT * FROM `ver_seccion` WHERE `id_usuario` = $user_id AND `id_seccion` = $id_seccion";
        // Generamos la sentencia
        $sentencia = mysqli_prepare($conexion, $sql);
        mysqli_stmt_execute($sentencia);
        $result = mysqli_stmt_get_result($sentencia);
        // Verificar si el registro existe
        if (mysqli_num_rows($result) == 0)
        {
            // Si no la ha visto, registre que la vió
            $sql_insert = "INSERT INTO `ver_seccion`(`id_usuario`, `id_seccion`, `fecha_lectura_ver_seccion`) VALUES ($user_id, $id_seccion, CURDATE())";
            $stmt_insert = mysqli_prepare($conexion, $sql_insert);
            if (mysqli_stmt_execute($stmt_insert)) 
            {
                $view_registered = "SUCCESS";
            } 
            else 
            {
                $view_registered = "ERROR: " . mysqli_error($conexion);
            }
        }
        // Si sí, reporte algo más
        else
        {
            $view_registered = "ALREADY_VIEWED";
        }
        // Ahora tenemos que armar la respuesta como array
        $respuesta = array
        (
            "section_number" => $numero_seccion,
            "section_title" => $titulo_seccion,
            "section_content" => $contenido_seccion,
            "section_position" => $posicion_seccion,
            "view_registered" => $view_registered
        );
        cerrar_conexion($conexion);
        return json_encode($respuesta);
    }
    /* Termina función que devuelve datos para leer otra sección dentro de la herramienta de lectura */

    /* Comienza función que devuelve las secciones para la página de edición de libro */
    function obtener_secciones_edit($book_id, $book_status, $json_file)
    {
        // Debe devolver string con HTML
        $respuesta = "";
        $html_status_response = "";
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Segundo, preparamos el SQL
        $sql_secciones = 
        "SELECT s.`id_seccion`, s.`numero_seccion`, s.`titulo_seccion`, s.`contenido_seccion` FROM `seccion` s INNER JOIN `componer_seccion` cs ON (cs.`id_seccion` = s.`id_seccion`) WHERE cs.`id_libro` = $book_id ORDER BY s.`numero_seccion` DESC";
        // Aquí aplicamos MySQL
        try 
        {
            if ($sentencia_secciones = mysqli_query($conexion, $sql_secciones))
            {
                if (mysqli_num_rows($sentencia_secciones) > 0)
                {
                    // Si el status es publicado, no podemos permitir que se borren las secciones
                    if ($book_status == 1)
                    {
                        $html_status_response = "disabled";
                    }
                    // Si no, que no haga nada
                    // Si hay secciones, empecemos a asignar valores
                    $respuesta .= 
                    "<div class='section-intro'>
                        <h4>Secciones</h4>
                        <button class= 'btn' onclick='agregarSeccion();'>Añadir sección/capítulo</button>
                    </div>
                    <div id='section-list'>";
                    while ($row = mysqli_fetch_assoc($sentencia_secciones))
                    {
                        $id_seccion = $row['id_seccion'];
                        $numero_seccion = $row['numero_seccion'];
                        $titulo_seccion = $row['titulo_seccion'];
                        $contenido_seccion = $row['contenido_seccion'];
                        $respuesta .= 
                        "<button class='accordion-button' data-number='$numero_seccion' data-id='$id_seccion' onclick='toggleSection($numero_seccion);'>Sección $numero_seccion: $titulo_seccion</button>
                        <div class='accordion-section' id='section-$numero_seccion'>
                            <div class='section-title-functions'>
                                <input type='text' value='$titulo_seccion' class='section-title-input'>
                                <div class='section-title-buttons'>
                                    <button class='btn update-section' disabled>Actualizar</button>
                                    <button class='btn remove-section' $html_status_response>Eliminar</button>
                                </div>
                            </div>
                            <textarea class='section-content' placeholder='Comienza a escribir...'>$contenido_seccion</textarea>
                        </div>";
                    }
                    $respuesta .= "</div>";
                }
                else
                {
                    // Si no hay secciones o no hay respuesta, entonces que nos muestre un mensaje
                    $respuesta .= 
                    "<div class='section-error'>
                        <h4>No hay secciones inscritas</h4>
                        <p>Este libro no tiene ninguna sección inscrita. Por favor da clic en \"Añadir sección/capítulo\" para insertar la primera.</p>
                        <p>Si esto es un error, por favor contacta al desarrollador.</p>
                    </div>";
                }
            }
        } 
        catch (Exception $e) 
        {
            // Si hay problemas, que muestre el error ahí
            $respuesta .= 
            "<div class='section-error'>
                <h4>Error en el programa:</h4>
                <p>$e</p>
                <p>Por favor contacta al desarrollador.</p>
            </div>";
        }
        finally
        {            
            cerrar_conexion($conexion);
            return $respuesta;
        }
    }
    /* Termina función que devuelve las secciones para la página de edición de libro */
    /* TERMINA CÓDIGO R (READ) */

    /* COMIENZA CÓDIGO U (UPDATE) */
    /* Comienza función que actualiza una sección */
    function update_section($section_id, $section_title, $section_content, $json_file)
    {
        // Debe devolver string con código de respuesta
        $respuesta = "";
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Segundo, preparamos el SQL
        $sql_actualizar_seccion = 
        "UPDATE `seccion` SET `titulo_seccion`='$section_title',`contenido_seccion`='$section_content' WHERE `id_seccion` = $section_id";
        try 
        {
            $sentencia = mysqli_query($conexion, $sql_actualizar_seccion);
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
    /* Termina función que actualiza una sección */
    /* TERMINA CÓDIGO U (UPDATE) */

    /* COMIENZA CÓDIGO D (DELETE) */
    /* Comienza función que borra una sección */
    function borrar_seccion_edit($current_section_id, $json_file)
    {
        // Debe devolver string con código de respuesta
        $respuesta = "";
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Segundo, preparamos el SQL
        $sql_borrar_seccion = 
        "DELETE FROM `seccion` WHERE `id_seccion` = $current_section_id";
        // Tenemos que borrar también de la tabla conexa entre libro y sección
        $sql_borrar_componer_seccion = 
        "DELETE FROM `componer_seccion` WHERE `id_seccion` = $current_section_id";
        // Y tenemos que borrar visualizaciones
        $sql_borrar_visualizaciones = 
        "DELETE FROM `ver_seccion` WHERE `id_seccion` = $current_section_id";
        try 
        {
            $sentencia = mysqli_query($conexion, $sql_borrar_seccion);
            $sentencia = mysqli_query($conexion, $sql_borrar_componer_seccion);
            $sentencia = mysqli_query($conexion, $sql_borrar_visualizaciones);
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
    /* Termina función que borra una función */
    /* TERMINA CÓDIGO D (DELETE) */
?>