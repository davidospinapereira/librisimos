<?php
    // Este es invocado por AJAX para generar las estadísticas para administradores.
    if (isset($_POST['get_cards']))
    {
        // Tenemos que llamar al database_handler
        require '../model/database_handler.php';
        $json_file = '../model/connection_data.json';
        // Primero, generamos la conexión
        $conexion = abrir_conexion($json_file);
        // Jalamos el ID de usuario
        $user_id = $_POST['user_id'];
        // Consulta SQL compleja: los primeros tres libros que comparten géneros con libros leídos por el usuario, excluyendo los que ya ha leído. Estos son los libros "Recomendados" recomendados para el usuario
        $sql = "SELECT l.id_libro, l.nombre_libro, GROUP_CONCAT(DISTINCT a.nombre_autor ORDER BY a.nombre_autor ASC SEPARATOR ', ') AS autores, l.url_caratula_libro, COUNT(cs.id_seccion) AS cantidad_secciones FROM libro l JOIN generos_libro gl ON l.id_libro = gl.id_libro JOIN genero g ON gl.id_genero = g.id_genero JOIN autores_libro al ON l.id_libro = al.id_libro JOIN autor a ON al.id_autor = a.id_autor JOIN componer_seccion cs ON l.id_libro = cs.id_libro WHERE g.id_genero IN (SELECT DISTINCT gl.id_genero FROM ver_seccion vs JOIN seccion s ON vs.id_seccion = s.id_seccion JOIN componer_seccion cs ON s.id_seccion = cs.id_seccion JOIN generos_libro gl ON cs.id_libro = gl.id_libro WHERE vs.id_usuario = $user_id) AND l.id_libro NOT IN (SELECT DISTINCT cs.id_libro FROM ver_seccion vs JOIN seccion s ON vs.id_seccion = s.id_seccion JOIN componer_seccion cs ON s.id_seccion = cs.id_seccion WHERE vs.id_usuario = $user_id) GROUP BY l.id_libro ORDER BY l.lecturas_libro DESC LIMIT 3";
        // Gracias, ChatGPT
        try 
        {
            // Inicializamos la variable de respuesta que es la que se va a enviar al JS
            $respuesta = "<h3>Recomendados para ti</h3>";
            // Aquí empezamos a ejecutar el comando
            if ($sentencia = mysqli_query($conexion, $sql))
            {
                // obtener array asociativo
                while ($row = mysqli_fetch_assoc($sentencia)) 
                {
                    $id_libro = $row['id_libro'];
                    if ($row['url_caratula_libro'] == null)
                    {
                        $url_caratula = './view/uploads/books/generic-book-cover.jpg';
                    }
                    else
                    {
                        $url_caratula = './' . $row['url_caratula_libro'];
                    }
                    $nombre_libro = $row['nombre_libro'];
                    $autores = $row['autores'];
                    $cantidad_secciones = $row['cantidad_secciones'];
                    $respuesta .= 
                    "
                    <!-- Comienza tarjeta individual -->
                    <a href='index.php?page=book-page&book_id=$id_libro'>
                        <div class='entry'>
                            <img src='$url_caratula' alt='Imagen destacada' class='featured-image'>
                            <div class='details'>
                                <h4 class='title'>$nombre_libro</h4>
                                <p class='author'>Por: <b>$autores</b></p>
                                <p class='sections'>Secciones: <b>$cantidad_secciones</b></p>
                            </div>
                        </div>
                    </a>
                    <!-- Termina tarjeta individual -->
                    ";
                }
            }
            
        } 
        catch (Exception $e) 
        {
            $respuesta = $e;
        }
        finally
        {
            cerrar_conexion($conexion);
            echo $respuesta;
        }
    }
?>