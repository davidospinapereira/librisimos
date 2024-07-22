<?php
    // TEST es un archivo diseñado para probar funciones y pulirlas antes de incluirlas en el software terminado.
    // Traemos los datos enviados
    $user = 'admin';
    $pass = 'admin.1234';
    $pass_hashed = password_hash($pass, PASSWORD_DEFAULT);
    echo "$user\n";
    echo "$pass\n";
    echo "$pass_hashed\n";
    // Leemos un archivo donde se encuentre toda la base de datos con tablas, campos y conexiones en formato SQL, en un archivo PHP para más facilidad y seguridad.
    // Con eso creamos la variable $structure
    require './model/database-structure.php';
    // Invocamos el manejador de base de datos
    require './model/database_handler.php';
    // Dentro de un try-catch
    try 
    {
        // Jalamos los datos de JSON
        $json_file = './model/connection_data.json';
        $connection = abrir_conexion($json_file);
        /* Borrar todo el contenido actual de la base de datos */
        // Desactivar las restricciones de clave externa temporalmente
        $result = mysqli_query($connection, "SET FOREIGN_KEY_CHECKS = 0");
        // Obtener todas las tablas de la base de datos
        $tables = array();
        $result = mysqli_query($connection, "SHOW TABLES");
        while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) 
        {
            $tables[] = $row[0];
        }
        // Eliminar todas las tablas
        foreach ($tables as $table) 
        {
            $execution = mysqli_query($connection, "DROP TABLE IF EXISTS $table");
        }
        // Volver a activar las restricciones de clave externa
        $result = mysqli_query($connection, "SET FOREIGN_KEY_CHECKS = 1");
        /* Borrado todo el contenido actual de la base de datos */
        // Generamos la consulta final con el SQL $structure y la función multi_query
        if(mysqli_multi_query($connection, $structure))
        {
            do 
            {
                // Esto es necesario para que `multi_query` continúe ejecutando las siguientes sentencias
                if ($result = mysqli_store_result($connection))
                {
                    while ($row = mysqli_fetch_row($result))
                    {
                        // Puedes procesar los resultados aquí si es necesario
                        printf("%s\n", $row[0]);
                    }
                    // Se vacía la variable $result
                    mysqli_free_result($result);
                }
            } 
            while (mysqli_more_results($connection) && mysqli_next_result($connection));
        }
        else
        {
            echo "Error al ejecutar las consultas: " . mysqli_error($connection);
            exit();
        }
        // Creamos la sentencia para incluir al usuario súper administrador al usuario súper administrador que fue solicitado.
        $incluir_super_admin = 'INSERT INTO `usuario` (`id_usuario`, `nombres_usuario`, `apellidos_usuario`, `fecha_nacimiento_usuario`, `email_usuario`, `url_imagen_usuario`, `login_usuario`, `pass_usuario`, `id_tipo_usuario`) VALUES (NULL, NULL, NULL, NULL, NULL, NULL, \'' . $user . '\', \'' . $pass_hashed . '\', \'1\');';
        // Ejecutamos la sentencia creada
        $result = mysqli_query($connection, $incluir_super_admin);
        // Cerramos conexión
        cerrar_conexion($connection);
        // Reportamos éxito en la labor
        echo "EXITO";
    } 
    catch (Exception $e) 
    {
        // Reportamos error en la labor
        echo "ERROR: " . $e;
    }
?>