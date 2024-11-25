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

    if (isset($_POST['obtener_usuarios_listado']))
    {
        echo obtener_usuarios_listado($_POST['termino_busqueda'], $json_file);
    }

    if (isset($_POST['obtener_tipo_usuario']))
    {
        echo obtener_tipo_usuario($_POST['id_usuario'], $json_file);
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

    /* Comienza función para recuperar el listado de usuarios en la página de gestión de usuarios */
    function obtener_usuarios_listado($termino_busqueda, $json_file)
    {
        // Debe devolver string
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql_usuarios = 
        "SELECT u.`id_usuario`, CONCAT(u.`nombres_usuario`, ' ', u.`apellidos_usuario`) AS `nombre_completo`, u.`login_usuario`, COUNT(DISTINCT l.`id_libro`) AS `libros_leidos` FROM `usuario` u LEFT JOIN `ver_seccion` vs ON u.`id_usuario` = vs.`id_usuario` LEFT JOIN `seccion` s ON vs.`id_seccion` = s.`id_seccion` LEFT JOIN `componer_seccion` cs ON s.`id_seccion` = cs.`id_seccion` LEFT JOIN `libro` l ON cs.`id_libro` = l.`id_libro` WHERE u.`nombres_usuario` LIKE '%$termino_busqueda%' OR u.`apellidos_usuario` LIKE '%$termino_busqueda%' OR u.`login_usuario` LIKE '%$termino_busqueda%' GROUP BY u.`id_usuario`";
        try 
        {
            // Si hay respuesta, que la genere
            if ($sentencia = mysqli_query($conexion, $sql_usuarios))
            {
                $respuesta .= 
                '
                    <!-- Encabezado de la tabla -->
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <th>Nombre completo</th>
                            <th>Login de usuario</th>
                            <th>Libros leídos</th>
                            <th>Acciones</th>
                        </tr>
                        <!-- Contenido de la tabla -->
                ';
                while ($row = mysqli_fetch_assoc($sentencia)) 
                {
                    $id_usuario = $row['id_usuario'];
                    $nombre_completo = $row['nombre_completo'];
                    $login_usuario = $row['login_usuario'];
                    $libros_leidos = $row['libros_leidos'];
                    // Control: El súper administrador no puede borrarse a sí mismo
                    $respuesta .= 
                    '
                        <tr>
                            <td data-cell="id" class="id-cell">' . $id_usuario . '</td>
                            <td data-cell="nombre" class="name-cell">' . $nombre_completo . '</td>
                            <td data-cell="login" class="login-cell">' . $login_usuario . '</td>
                            <td data-cell="leidos" class="read-cell">' . $libros_leidos . '</td>
                            <td data-cell="acciones" class="acciones">
                                <span class="button continue" onclick="editarUsuario(' . $id_usuario . ');"><div class="tooltip">Editar usuario</div><i class="bx bx-book-reader"></i></span>
                                <!-- Los administradores no pueden eliminar usuarios, sólo el súper administrador -->
                                <span class="button quit" onclick="borrarUsuario(' . $id_usuario . ')"><div class="tooltip">Eliminar usuario</div><i class="bx bx-x"></i></span>
                                <!-- El súper administrador no puede eliminarse a sí mismo, recuerda poner ese control en el PHP -->
                            </td>
                        </tr>
                    ';
                }
                $respuesta .= '</tbody>';
            }
            // Si no, que ponga un huevito de pascua
            else
            {
                $respuesta = "<div class='col w100' id='nothing-found'><h2>¡OOPS! No se puede recuperar el listado de usuarios.</h2><h4>Por favor contacte al administrador.</h4></div>";
            }
        } 
        catch (Exception $e) 
        {
            $error = $e->getMessage();
            $respuesta = "<div class='col w100' id='nothing-found'><h2>¡ERROR! Hay un error en el programa</h2><h4>$error</h4><h4>Por favor consulte al administrador.</h4></div>";
        }
        finally
        {
            cerrar_conexion($conexion);
            return $respuesta;
        }
    }
    /* Termina función para recuperar el listado de usuarios en la página de gestión de usuarios */

    /* Comienza función que recupera el tipo de usuario para la página de administración de usuarios */
    function obtener_tipo_usuario($id_usuario, $json_file)
    {
        // Debe devolver un número
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql_tipo = "SELECT `id_tipo_usuario` FROM `usuario` WHERE `id_usuario` = $id_usuario";
        try
        {
            if ($sentencia = mysqli_query($conexion, $sql_tipo))
            {
                while ($row = mysqli_fetch_assoc($sentencia))
                {
                    $respuesta = $row['id_tipo_usuario'];
                }
            }
        }
        catch (Exception $e) 
        {
            $error = $e->getMessage();
            $respuesta = $error;
        }
        finally
        {
            cerrar_conexion($conexion);
            return $respuesta;
        }
    }
    /* Termina función que recupera el tipo de usuario para la página de administración de usuarios */
?>