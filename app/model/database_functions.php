<?php
    // Antes de empezar, iniciamos el protocolo de sesión
    session_start();
    // Primero que cualquier cosa, importamos el archivo de manejo de la base de datos
    require 'database_handler.php';
    // Comienza función que verifica la existencia del usuario y devuelve nombre de usuario y tipo de usuario
    function usuario_existe($user, $pass, $json_file)
    {
        // Generamos un arreglo de respuesta
        $respuesta = array();
        // Abrimos la conexión
        try 
        {
            $conexion = abrir_conexion($json_file);
            // Preparamos la frase de búsqueda. Necesitamos el nombre, el ID y la contraseña. Sin miedo porque a estas alturas ya sabemos que la base de datos existe y tiene su estructura correcta
            $sql = "SELECT nombres_usuario, id_usuario, pass_usuario FROM usuario WHERE login_usuario = '$user'";
            // Preparamos la sentencia con base en la frase de búsqueda ya generada
            $sentencia = mysqli_query($conexion, $sql);
            // Verificamos si se encontró el usuario
            if (mysqli_num_rows($sentencia) == 1)
            {
                // Separamos el resultado de la búsqueda en tres componentes
                $consulta = mysqli_fetch_array($sentencia);
                $user_db = $consulta['nombres_usuario'];
                $user_id_db = $consulta['id_usuario'];
                $pass_db = $consulta['pass_usuario'];
                // Verificamos la contraseña
                if (password_verify($pass, $pass_db))
                {
                    // Si la contraseña es correcta
                    // Añadimos el ID del usuario a los datos de session
                    $_SESSION['user_id'] = $user_id_db;
                    // Devolvemos un código de éxito, el id de usuario y el nombre para el mensaje de bienvenida
                    $respuesta = 
                    [
                        'codigo' => "EXITO",
                        'id' => $user_id_db,
                        'nombre' => $user_db
                    ];
                }
                else
                {
                    // Si la contraseña no es correcta, devolvemos un código de fracaso
                    $respuesta = 
                    [
                        'codigo' => "PASS_INCORRECT"
                    ];
                }
            }
            else
            {
                // Si el usuario es inválido devolvemos un código de fracaso
                $respuesta = 
                    [
                        'codigo' => "USER_INCORRECT"
                    ];
            }
        } 
        catch (Exception $e) 
        {
            $respuesta = 
            [
                'error' => $e
            ];
        }
        // Cerramos la conexión y la declaración preparada
        $sentencia->close();
        cerrar_conexion($conexion);
        // Sin importar cuál es el valor de la respuesta, la retornamos
        return $respuesta;
    }
    // Termina función de que verifica la existencia del usuario

    // Comienza función que verifica que un login o un email NO existan en la base de datos
    function verificar_registro($login, $email, $json_file)
    {
        // Generamos un arreglo de respuesta
        $respuesta = array();
        $codigo = "";
        // Abrimos la conexión
        try 
        {
            $conexion = abrir_conexion($json_file);
            // Preparamos la frase de búsqueda. Necesitamos el login y el email para poderlos comparar con los datos provistos
            $sql = "SELECT login_usuario, email_usuario FROM usuario WHERE login_usuario = '$login' OR email_usuario = '$email'";
            // Preparamos la sentencia con base en la frase de búsqueda ya generada
            $sentencia = mysqli_query($conexion, $sql);
            // Verificamos si se encontró una fila con el nombre de usuario o el email
            if (mysqli_num_rows($sentencia) == 1)
            {
                // Separamos el resultado de la búsqueda en dos componentes
                $consulta = mysqli_fetch_array($sentencia);
                $login_db = $consulta['login_usuario'];
                $email_db = $consulta['email_usuario'];
                // Si el login existe
                if (($login_db == $login) && ($email_db == $email))
                {
                    $codigo = "LOGIN_AND_EMAIL_EXIST";
                }
                else if ($login_db == $login)
                {
                    $codigo = "LOGIN_EXISTS";
                }
                // Si el email existe
                else if ($email_db == $email)
                {
                    $codigo = "EMAIL_EXISTS";
                }
            }
            else
            {
                // Si no hay filas es que no hay usuarios con ese nombre o ese correo, por lo que el registro es válido
                $codigo = "REGISTER_VALID";
            }
            // Finalmente, añadimos el código al array de respuesta
            $respuesta = 
            [
                'codigo' => $codigo
            ];
        } 
        catch (Exception $e) 
        {
            $respuesta = 
            [
                'error' => $e
            ];
        }
        // Cerramos la conexión y la declaración preparada
        $sentencia->close();
        cerrar_conexion($conexion);
        // Sin importar cuál es el valor de la respuesta, la retornamos
        return $respuesta;
    }
    // Termina función que verifica que un login o un email NO existan en la base de datos
?>