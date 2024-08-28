<?php
    /* Comienzan Declaraciones */
    require '../model/database_handler.php';
    $json_file = '../model/connection_data.json';
    /* Terminan declaraciones */

    /* Comienzan llamados AJAX */
    if (isset($_POST['check_password']))
    {
        echo check_password($_POST['user_id'], $_POST['pass_viejo'], $json_file);
    }

    if (isset($_POST['update_password']))
    {
        echo update_password($_POST['user_id'], $_POST['pass'], $json_file);
    }

    if (isset($_POST['check_login_profile']))
    {
        echo check_login($_POST['email'], $_POST['login'], $_POST['user_id'], $json_file);
    }

    if (isset($_POST['update_profile']))
    {
        echo update_profile($_POST['nombre'], $_POST['apellido'], $_POST['fecha'], $_POST['email'], $_POST['login'], $_POST['url_imagen_save'], $_POST['user_id'], $json_file);
    }

    if (isset($_POST['delete_profile']))
    {
        echo delete_profile($_POST['user_id'], $json_file);
    }
    /* Terminan llamados AJAX */

    /* Comienza función para chequear contraseña */
    function check_password($user_id, $password, $json_file)
    {
        // Primero, tenemos que hacer un password hash en la contraseña
        /* $password_hashed = password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]); */
        $conexion = abrir_conexion($json_file);
        // SQL para jalar la contraseña
        $sql = "SELECT pass_usuario FROM usuario WHERE id_usuario = $user_id";
        try 
        {
            // Preparamos la sentencia con base en la frase de búsqueda ya generada
            $sentencia = mysqli_query($conexion, $sql);
            // Si la sentencia arroja resultados, comenzamos a comparar
            if (mysqli_num_rows($sentencia) == 1)
            {
                // Separamos el resultado en un array
                $consulta = mysqli_fetch_array($sentencia);
                $pass_db = $consulta['pass_usuario'];
                // Hacemos password verify con el password que traemos
                if (password_verify($password, $pass_db))
                {
                    // Si el password es correcto, retorna código de éxito
                    $respuesta = "PASS_CORRECT";
                }
                else
                {
                    // Si no, retorna incorrecto
                    $respuesta = "La contraseña es incorrecta.";
                }
            }
            // Si no, debemos sacar mensaje de error, no se supone que llegue hasta aquí
        } 
        catch (Exception $e) 
        {
            // Si hay una falla, retornamos el error
            $respuesta = $e;
        }
        finally
        {
            // Cerramos la conexión y la declaración preparada
            cerrar_conexion($conexion);
            // Sin importar cuál es el valor de la respuesta, la retornamos
            return $respuesta;
        }
    }
    /* Termina función para chequear contraseña */

    /* Comienza función para actualizar contraseña */
    function update_password($user_id, $password, $json_file)
    {
        // Primero, hacemos password_hash al password que recibimos
        $password_hashed = password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
        // Luego, invocamos la conexión
        $conexion = abrir_conexion($json_file);
        $respuesta = '';
        // Después, preparamos el SQL
        $sql = "UPDATE `usuario` SET `pass_usuario`='$password_hashed' WHERE `id_usuario` = $user_id";
        try 
        {
            // Ejecutamos el SQL, este no tiene que devolver nada
            $sentencia = mysqli_query($conexion, $sql);
            // Si la ejecución fue correcta, generamos mensaje de éxito
            $respuesta = 'SUCCESS';
        } 
        catch (Exception $e) 
        {
            // Si la ejecución fue incorrecta, generamos mensaje de error
            $respuesta = $e;
        }
        finally
        {
            // Cerramos conexión
            cerrar_conexion($conexion);
            // Sin importar cuál es el valor de la respuesta, la retornamos
            return $respuesta;
        }
    }
    /* Termina función para actualizar contraseña */

    /* Comienza función para verificar si el login y el email ya están tomados y no son los propios */
    function check_login($email, $login, $user_id, $json_file)
    {
        // Primero, invocamos la conexión
        $conexion = abrir_conexion($json_file);
        // Frase de búsqueda con la restricción establecida, si con esta frase aparecen resultados, es porque están tomados por otra cuenta y no pueden usarse.
        $sql = "SELECT `email_usuario`, `login_usuario` FROM `usuario` WHERE (login_usuario = '$login' OR email_usuario = '$email') AND `id_usuario` <> $user_id";
        $respuesta = '';
        try 
        {
            // Preparamos la sentencia con base en la frase de búsqueda ya generada
            $sentencia = mysqli_query($conexion, $sql);
            if (mysqli_num_rows($sentencia) == 1)
            {
                // Separamos el resultado en un array
                $consulta = mysqli_fetch_array($sentencia);
                $email_db = $consulta['email_usuario'];
                $login_db = $consulta['login_usuario'];
                // Hacemos password verify con el password que traemos
                if (($login == $login_db) && ($email == $email_db))
                {
                    // Si tanto login como email aparecen es porque ambos están tomados
                    $respuesta = "DATA_INVALID";
                }
                else if ($login == $login_db)
                {
                    // Si sólo el login aparece es porque el login ya está tomado
                    $respuesta = "LOGIN_INVALID";
                }
                else if ($email == $email_db)
                {
                    // Si sólo el email aparece es porque el email ya está tomado
                    $respuesta = "EMAIL_INVALID";
                }
                else
                {
                    // Si no aparecen los dos juntos, y no aparecen por separado, es porque ambos datos están disponibles y pueden usarse.
                    $respuesta = "DATA_VALID";
                }
            }
        } 
        catch (Exception $e) 
        {
            $respuesta = $e;
        }
        finally
        {
            // Cerramos conexión
            cerrar_conexion($conexion);
            // Sin importar cuál es el valor de la respuesta, la retornamos
            return $respuesta;
        }
    }
    /* Termina función para verificar si el login y el email ya están tomados y no son los propios */

    /* Comienza función para actualizar perfil */
    function update_profile($nombre, $apellido, $fecha_nacimiento, $email, $login, $url_imagen, $user_id, $json_file)
    {
        // Primero, invocamos la conexión
        $conexion = abrir_conexion($json_file);
        $respuesta = '';
        // Después, preparamos el SQL
        $sql = "UPDATE `usuario` SET `nombres_usuario`='$nombre', `apellidos_usuario`='$apellido',`fecha_nacimiento_usuario`='$fecha_nacimiento',`email_usuario`='$email',`url_imagen_usuario`='$url_imagen',`login_usuario`='$login' WHERE `id_usuario` = $user_id";
        try 
        {
            // Ejecutamos el SQL, este no tiene que devolver nada
            $sentencia = mysqli_query($conexion, $sql);
            // Si la ejecución fue correcta, generamos mensaje de éxito
            $respuesta = 'SUCCESS';
        } 
        catch (Exception $e) 
        {
            // Si la ejecución fue incorrecta, generamos mensaje de error
            $respuesta = $e;
        }
        finally
        {
            // Cerramos conexión
            cerrar_conexion($conexion);
            // Sin importar cuál es el valor de la respuesta, la retornamos
            return $respuesta;
        }
    }
    /* Termina función para actualizar perfil */

    /* Comienza función para eliminar perfil */
    function delete_profile($user_id, $json_file)
    {
        // Esta función no solo elimina el perfil, sino además todo lo que el usuario ha leído, con lo que se evita dejar datos basura en la base de datos
        // Primero, invocamos la conexión
        $conexion = abrir_conexion($json_file);
        $respuesta = '';
        // SQL1 borra al usuario mismo
        $sql1 = "DELETE FROM `usuario` WHERE `id_usuario` = $user_id";
        // SQL2 borra todas las visualizaciones de todos los libros que el usuario ha leído, basado sólo en el id de usuario
        $sql2 = "DELETE FROM `ver_seccion` WHERE `id_usuario` = $user_id";
        try 
        {
            // Ejecutamos el SQL, este no tiene que devolver nada
            $sentencia = mysqli_query($conexion, $sql1);
            $sentencia = mysqli_query($conexion, $sql2);
            // Si la ejecución fue correcta, generamos mensaje de éxito
            $respuesta = 'SUCCESS';
        } 
        catch (Exception $e) 
        {
            // Si la ejecución fue incorrecta, generamos mensaje de error
            $respuesta = $e;
        }
        finally
        {
            // Cerramos conexión
            cerrar_conexion($conexion);
            // Sin importar cuál es el valor de la respuesta, la retornamos
            return $respuesta;
        }
    }
    /* Termina función para eliminar perfil */
?>