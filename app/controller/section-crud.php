<?php
    require '../model/database_handler.php';
    $json_file = '../model/connection_data.json';
    /* Comienza invocación AJAX */
    if (isset($_POST['read_section']))
    {
        echo generar_datos_lectura($_POST['id_seccion'], $json_file);
    }
    /* Termina invocación AJAX */

    /* Comienza función que devuelve datos para la herramienta de lectura */
    function generar_datos_lectura($id_seccion, $json_file)
    {
        $respuesta = array();
        // Tenemos que hacer varios SQL
        // La idea es que con la id de sección sacamos los datos de sección y el id del libro, con el ID del libro sacamos los datos del libro y los géneros como array y con todo eso generamos un array
        $conexion = abrir_conexion($json_file);
        // Primero, la info de la sección
        $sql = "SELECT `numero_seccion`, `titulo_seccion`, `contenido_seccion` FROM `seccion` WHERE `id_seccion` = $id_seccion";
        // Ejecutamos la sentencia
        $sentencia = mysqli_query($conexion, $sql);
        // Separamos el resultado de la búsqueda en sus componentes, no hay que hacer ifs ni nada, porque sabemos que se va a encontrar
        $consulta = mysqli_fetch_array($sentencia);
        $numero_seccion = $consulta['numero_seccion'];
        $titulo_seccion = $consulta['titulo_seccion'];
        $contenido_seccion = $consulta['contenido_seccion'];
        // Finalmente, cerramos la conexión
        cerrar_conexion($conexion);
        $conexion = abrir_conexion($json_file);
        // Ahora, sigue la ID del libro
        $sql = "SELECT `id_libro` FROM `componer_seccion` WHERE `id_seccion` = $id_seccion";
        // Ejecutamos la sentencia
        $sentencia = mysqli_query($conexion, $sql);
        // Separamos el resultado de la búsqueda en sus componentes
        $consulta = mysqli_fetch_array($sentencia);
        $id_libro = $consulta['id_libro'];
        // Finalmente, cerramos la conexión
        cerrar_conexion($conexion);
        $conexion = abrir_conexion($json_file);
        // Ahora, siguen los datos del libro con el ID del libro
        $sql = "SELECT `nombre_libro` FROM `libro` WHERE `id_libro` = $id_libro";
        // Ejecutamos la sentencia
        $sentencia = mysqli_query($conexion, $sql);
        // Separamos el resultado de la búsqueda en sus componentes
        $consulta = mysqli_fetch_array($sentencia);
        $nombre_libro = $consulta['nombre_libro'];
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
        // Aquí viene averiguar si la sección es la primera, la última o en medio
        // Como la numeración de la sección es diferente a la id de la sección y lo manejaremos programáticamente, podemos manejarlo de esa manera. 
        // Si el número de la sección es 1, entonces es la primera.
        $conexion = abrir_conexion($json_file);
        $sql = "SELECT s.`numero_seccion` FROM `seccion` AS s INNER JOIN `componer_seccion` AS cs ON (cs.`id_seccion` = s.`id_seccion`) WHERE cs.`id_libro` = $id_libro";
        // Generamos la sentencia
        $sentencia = mysqli_query($conexion, $sql);
        // convertimos el resultado en un array asociativo
        $consulta = mysqli_fetch_array($sentencia);
        $key = array_search($numero_seccion, $consulta);
        // Buscamos el número de sección actual
        if ($key !== false) 
        {
            // Obtener todas las claves del array
            $keys = array_keys($consulta);
            // Verificar la posición de la clave
            if (mysqli_num_rows($sentencia) == 1)
            {
                // El arreglo sólo tiene un valor, por lo que el valor es primero y último al tiempo
                $posicion_seccion = "ONLY";
            }
            else if ($key === reset($keys)) 
            {
                // El valor está de primero
                $posicion_seccion = "FIRST";
            } 
            elseif ($key === end($keys)) 
            {
                // El valor está de último
                $posicion_seccion = "LAST";
            } 
            else 
            {
                // El valor está en medio
                $posicion_seccion = "MIDDLE";
            }
        } 
        else 
        {
            // El valor no está en el array, no se supone que se llegue hasta ahí
            $posicion_seccion = "NOT_IN";
        }
        // Si no hay sección con un número mayor al actual, entonces es la última, si no, pues está en medio
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
?>