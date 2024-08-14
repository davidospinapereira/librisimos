<?php
    // Este es invocado por AJAX para generar las estadísticas para administradores.
    if (isset($_POST['get_stats']))
    {
        // Tenemos que llamar al database_handler
        require '../model/database_handler.php';
        $json_file = '../model/connection_data.json';
        // Generamos tres variables de conteo
        /* SELECT COUNT(*) AS total_usuarios FROM usuario; // da usuarios registrados */
        $sql_registrados = 'SELECT COUNT(*) AS total_usuarios FROM usuario';
        $usuarios = obtener_stats($json_file, $sql_registrados, 'total_usuarios');
        /* SELECT COUNT(*) AS total_publicados FROM libro WHERE clave_estado_libro = 1; // da libros publicados */
        $sql_publicados = 'SELECT COUNT(*) AS total_publicados FROM libro WHERE clave_estado_libro = 1';
        $publicados = obtener_stats($json_file, $sql_publicados, 'total_publicados');
        /* SELECT COUNT(*) AS total_sin_publicar FROM libro WHERE clave_estado_libro = 0; // da libros sin publicar */
        $sql_sin_publicar = 'SELECT COUNT(*) AS total_sin_publicar FROM libro WHERE clave_estado_libro = 0';
        $sin_publicar = obtener_stats($json_file, $sql_sin_publicar, 'total_sin_publicar');
        
        $respuesta = 
        [
            "usuarios" => $usuarios,
            "publicados" => $publicados,
            "sin_publicar" => $sin_publicar
        ];
        echo json_encode($respuesta);
    }

    // Esta es la función que obtiene un stat
    function obtener_stats($json_file, $sql, $parametro)
    {
        // Primero, generamos la conexión
        $conexion = abrir_conexion($json_file);
        // Generamos la sentencia con el SQL que se ha llamado
        try 
        {
            $sentencia = mysqli_query($conexion, $sql);
            if (mysqli_num_rows($sentencia) == 1)
            {
                // Si hay resultados de la sentencia, que siempre los hay
                $consulta = mysqli_fetch_array($sentencia);
                $respuesta = $consulta[$parametro];
            }
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
?>