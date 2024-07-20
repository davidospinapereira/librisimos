<?php
    // Primero que cualquier cosa, importamos el archivo de manejo de la base de datos
    require 'database_handler.php';
    // Comienza función que verifica la existencia del usuario y devuelve nombre de usuario y tipo de usuario
    function usuario_existe($user, $pass, $json_file)
    {
        // Generamos un arreglo de respuesta
        $respuesta = array();
        // Abrimos la conexión
        $conexion = abrir_conexion($json_file);
        // Preparamos la frase de búsqueda. Necesitamos el nombre, el ID y la contraseña. Sin miedo porque a estas alturas ya sabemos que la base de datos existe y tiene su estructura correcta
        $sql = "SELECT nombres_usuario, id_usuario, pass_usuario FROM usuario WHERE login_usuario = '$user'";
        // Preparamos la sentencia con base en la frase de búsqueda ya generada
        $sentencia = mysqli_query($conexion, $sql);
        // Verificamos si se encontró el usuario
        /* $consulta = null;
        while ($consulta = mysqli_fetch_array($sentencia))
        {
            $nombre = 
        } */
        if (mysqli_num_rows($sentencia) == 1)
        {
            // Separamos el resultado de la búsqueda en tres componentes
            $consulta = mysqli_fetch_array($sentencia);
            $username = $consulta['nombres_usuario'];
            $user_id = $consulta['id_usuario'];
            $password = $consulta['pass_usuario'];
            /* $sentencia->bind_result($username, $user_type, $hashed_password);
            $sentencia->fetch(); */
            // Verificamos la contraseña
            if ($pass == $password)
            {
                // Si la contraseña es correcta
                // Combinaremos el login y el password en un token
                $token = $user . $pass;
                // Generamos un hash que podremos usar en session_start
                $hash_token = password_hash($token, PASSWORD_DEFAULT);
                // Generamos un array con el nombre, el tipo de usuario y el token
                $respuesta = 
                [
                    'nombre' => $username,
                    'id' => $user_id,
                    'token' => $hash_token
                ];
            }
            else
            {
                // Si la contraseña no es correcta
                $respuesta = null;
            }
        }
        else
        {
            // Si el usuario es inválido
            $respuesta = null;
        }
        // Sin importar cuál es el valor de la respuesta, la retornamos
        return $respuesta;
        // Cerramos la conexión y la declaración preparada
        $sentencia->close();
        cerrar_conexion($conexion);
    }
    // Termina función de que verifica la existencia del usuario
?>