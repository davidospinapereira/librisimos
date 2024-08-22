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

    if (isset($_POST['update_profile']))
    {
        echo update_profile($_POST['nombre'], $_POST['apellido'], $_POST['fecha_nacimiento'], $_POST['email'], $_POST['login'], $_POST['url_imagen_save'], $_POST['user_id'], $json_file);
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
?>