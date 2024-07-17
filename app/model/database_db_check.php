<?php
    function db_check ($json_file)
    {
        // Para este momento ya hemos comprobado que el archivo JSON existe y que su estructura es correcta. Entonces:
        // Abrimos el contenido del archivo
        $json_data = file_get_contents($json_file);
        // Decodificamos el JSON en un array asociativo
        $data = json_decode($json_data);
        // Configuración de la base de datos con base en el JSON
        $db_host = $data->db_host;
        $db_user = $data->db_user;
        $db_pass = $data->db_pass;
        $db_name = $data->db_name;
        // Verificamos si la base de datos existe
        try 
        {
            $pdo = new PDO("mysql:host=$db_host", $db_user, $db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Consultar las bases de datos
            $stmt = $pdo->query('SHOW DATABASES');
            $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
            // Verificar si la base de datos existe en la lista
            if (in_array($db_name, $databases)) 
            {
                // Si la base de datos existe, verificamos que tenga la estructura deseada.
                $expected_structure = [
                    'usuario' => ['id_usuario', 'nombres_usuario', 'apellidos_usuario', 'fecha_nacimiento_usuario', 'email_usuario', 'url_imagen_usuario', 'login_usuario', 'pass_usuario', 'id_tipo_usuario'],
                    'tipo_usuario' => ['id_tipo_usuario', 'nombre_tipo_usuario'],
                    'libro' => ['id_libro', 'nombre_libro', 'url_caratula_libro', 'sinopsis_libro', 'lecturas_libro'],
                    'generos_libro' => ['id_libro', 'id_genero'],
                    'genero' => ['id_genero', 'nombre_genero'],
                    'seccion' => ['id_seccion', 'numero_seccion', 'titulo_seccion', 'contenido_seccion'],
                    'componer_seccion' => ['id_libro', 'id_seccion'],
                    'ver_seccion' => ['id_usuario', 'id_seccion', 'fecha_lectura_ver_seccion'],
                    'autor' => ['id_autor', 'nombre_autor', 'url_imagen_autor', 'informacion_autor'],
                    'autores_libro' => ['id_libro', 'id_autor']
                ];
                // Conectamos a la base de datos
                try 
                {
                    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } 
                catch (PDOException $e) 
                {
                    //echo 'Error de conexión: ' . $e->getMessage();
                    return 'DB_CONNECTION_ERROR';
                    exit;
                }
                // Verificar la estructura de la base de datos
                $structure_valid = true;
                foreach ($expected_structure as $table => $columns) 
                {
                    // Verificar si la tabla existe
                    $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
                    if ($stmt->rowCount() === 0) 
                    {
                        //echo "La tabla '$table' no existe.<br>";
                        $structure_valid = false;
                        continue;
                    }

                    // Obtener las columnas de la tabla
                    $actual_columns = getTableColumns($pdo, $table);

                    // Verificar si todas las columnas necesarias están presentes
                    foreach ($columns as $column) 
                    {
                        if (!in_array($column, $actual_columns)) 
                        {
                            //echo "La columna '$column' no existe en la tabla '$table'.<br>";
                            $structure_valid = false;
                        }
                    }
                }

                if ($structure_valid) 
                {
                    // Si la estructura de la base de datos es la correcta, retornamos
                    return "DB_EXISTE_CORRECTA";
                } 
                else 
                {
                    // Si la estructura de la base de datos es incorrecta, retornamos
                    return "DB_EXISTE_INCORRECTA";
                }
            } 
            else 
            {
                // Si la base de datos no existe, retornamos
                return "DB_NO_EXISTE";
            }
        } 
        catch (PDOException $e) 
        {
            //echo 'Error de conexión: ' . $e->getMessage();
            return 'DB_CONNECTION_ERROR';
            exit;
        }

    }

    // Función para obtener las columnas de una tabla
    function getTableColumns($pdo, $table) 
    {
        $stmt = $pdo->query("SHOW COLUMNS FROM $table");
        $columns = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
        {
            $columns[] = $row['Field'];
        }
        return $columns;
    }
?>