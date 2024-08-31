<?php
    /* Comienza invocación de código indispensable */
    require '../model/database_handler.php';
    $json_file = '../model/connection_data.json';
    /* Termina invocación de código indispensable */

    /* Comienza invocación AJAX */
    if (isset($_POST['read_section']))
    {
        echo generar_datos_lectura($_POST['id_seccion'], $json_file);
    }
    if (isset($_POST['other_section']))
    {
        echo leer_otra_seccion($_POST['section_number'], $_POST['book_id'], $_POST['user_id'], $json_file);
    }
    if (isset($_POST['save_section']))
    {
        echo guardar_seccion($_POST['titulo_seccion'], $_POST['contenido_seccion'], $_POST['id_libro'], $json_file);
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
            // Primero, viene el SQL 1, que inserta el contenido de la sección:
            $sql1 = "INSERT INTO `seccion` (`titulo_seccion`, `contenido_seccion`, `numero_seccion`) VALUES ('$titulo_seccion', '$contenido_seccion', (SELECT IFNULL(MAX(s.`numero_seccion`), 0) + 1 FROM `seccion` AS s INNER JOIN `componer_seccion` AS cs ON cs.`id_seccion` = s.`id_seccion` WHERE cs.`id_libro` = $id_libro))";
            $sentencia = mysqli_prepare($conexion, $sql1);
            mysqli_stmt_execute($sentencia);
            // Después, viene el SQL 2, que asocia la nueva sección con el libro en la tabla `componer_seccion`:
            $sql2 = "INSERT INTO `componer_seccion` (`id_libro`, `id_seccion`) VALUES ($id_libro, LAST_INSERT_ID())";
            $sentencia = mysqli_prepare($conexion, $sql2);
            mysqli_stmt_execute($sentencia);
            // Si todo está bien, confirma la transacción
            mysqli_commit($conexion);
            $respuesta = "Sección insertada y asociada con éxito.";
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
    function generar_datos_lectura($id_seccion, $json_file)
    {
        $respuesta = array();
        // Tenemos que hacer varios SQL
        // La idea es que con la id de sección sacamos los datos de sección y el id del libro, con el ID del libro sacamos los datos del libro y los géneros como array y con todo eso generamos un array
        $conexion = abrir_conexion($json_file);
        // Primero, la info de la sección
        $sql = "SELECT cs.`id_libro`, l.`nombre_libro`, s.`numero_seccion`, s.`titulo_seccion`, s.`contenido_seccion` FROM `seccion` AS s INNER JOIN `componer_seccion` AS cs ON (cs.`id_seccion` = s.`id_seccion`) INNER JOIN `libro` AS l ON (l.`id_libro`= cs.`id_libro`) WHERE s.`id_seccion` = $id_seccion";
        // Ejecutamos la sentencia
        $sentencia = mysqli_query($conexion, $sql);
        // Separamos el resultado de la búsqueda en sus componentes, no hay que hacer ifs ni nada, porque sabemos que se va a encontrar
        $consulta = mysqli_fetch_array($sentencia);        
        $id_libro = $consulta['id_libro'];
        $nombre_libro = $consulta['nombre_libro'];
        $numero_seccion = $consulta['numero_seccion'];
        $titulo_seccion = $consulta['titulo_seccion'];
        $contenido_seccion = $consulta['contenido_seccion'];
        // Finalmente, cerramos la conexión
        cerrar_conexion($conexion);
        $conexion = abrir_conexion($json_file);
        // Ahora, sigue el autor O LOS AUTORES DEL LIBRO, usaré la función GROUP_CONCAT y un INNER JOIN
        $sql = "SELECT GROUP_CONCAT(DISTINCT(a.`nombre_autor`) SEPARATOR ', ') AS `autor_libro` FROM `autores_libro` AS al INNER JOIN `autor` AS a ON (a.`id_autor` = al.`id_autor`) WHERE al.`id_libro` = $id_libro";
        // Ejecutamos la sentencia
        $sentencia = mysqli_query($conexion, $sql);
        // Separamos el resultado de la búsqueda en sus componentes
        $consulta = mysqli_fetch_array($sentencia);
        $autor_libro = $consulta['autor_libro'];
        // Finalmente, cerramos la conexión
        cerrar_conexion($conexion);
        $conexion = abrir_conexion($json_file);
        // Ahora, siguen los géneros del libro, aquí no puedo usar concatenaciones pero sí un INNER JOIN
        $sql = "SELECT g.`nombre_genero`, g.`color_genero` FROM `generos_libro` AS gl INNER JOIN `genero` AS g ON (g.`id_genero` = gl.`id_genero`) WHERE gl.`id_libro` = $id_libro";
        $generos = "";
        // Ejecutamos la sentencia
        if ($sentencia = mysqli_query($conexion, $sql))
        {
            // obtener array asociativo
            while ($row = mysqli_fetch_assoc($sentencia)) 
            {
                $generos .= "<span style='background-color: #" . $row['color_genero'] . "; border-color: #" . $row['color_genero'] . ";'>" . $row['nombre_genero'] . "</span>";
            }
        }
        // Finalmente, cerramos la conexión
        cerrar_conexion($conexion); 
        // Ahora averiguamos en qué posición se encuentra el número de la sección entre todas las secciones registradas del libro
        $conexion = abrir_conexion($json_file);
        $sql = "SELECT s.`numero_seccion` FROM `seccion` AS s INNER JOIN `componer_seccion` AS cs ON (cs.`id_seccion` = s.`id_seccion`) WHERE cs.`id_libro` = $id_libro";
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
        // Ahora tenemos que armar la respuesta como array
        $respuesta = array
        (
            "book_title" => $nombre_libro,
            "book_author" => $autor_libro,
            "genres" => $generos,
            "section_number" => $numero_seccion,
            "section_title" => $titulo_seccion,
            "section_content" => $contenido_seccion,
            "section_position" => $posicion_seccion,
            "book_id" => $id_libro
        );
        return json_encode($respuesta);
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
    /* TERMINA CÓDIGO R (READ) */

    /* COMIENZA CÓDIGO U (UPDATE) */
    /* TERMINA CÓDIGO U (UPDATE) */

    /* COMIENZA CÓDIGO D (DELETE) */
    /* TERMINA CÓDIGO D (DELETE) */
?>