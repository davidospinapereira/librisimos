<?php
    require '../model/database_handler.php';
    $json_file = '../model/connection_data.json';
    /* Comienza invocación AJAX */
    if(isset($_POST['obtener_autores_edit']))
    {
        echo obtener_autores_edit($json_file);
    }
    if(isset($_POST['nuevo_autor']))
    {
        echo nuevo_autor($_POST['nombre_autor'], $_POST['descripcion_autor'], $_POST['url_imagen_autor'], $json_file);
    }
    if(isset($_POST['obtener_autor']))
    {
        echo obtener_autor($_POST['id_autor'], $json_file);
    }
    if(isset($_POST['actualizar_autor']))
    {
        echo actualizar_autor($_POST['id_autor'], $_POST['nombre_autor'], $_POST['descripcion_autor'], $_POST['url_imagen_autor'], $json_file);
    }
    /* Terminan invocaciones AJAX */

    /* Comienza función que obtiene autores para la página de edición de libro */
    function obtener_autores_edit($json_file)
    {
        // Debe devolver string
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql = 
        "SELECT `id_autor`, `nombre_autor` FROM `autor`";
        // Ejecutamos la sentencia
        try 
        {
            // Si hay respuesta, que la genere
            if ($sentencia = mysqli_query($conexion, $sql))
            {
                while ($row = mysqli_fetch_assoc($sentencia)) 
                {
                    $id_autor = $row['id_autor'];
                    $nombre_autor = $row['nombre_autor'];
                    $respuesta .= 
                    "
                        <option value='$id_autor'>$nombre_autor</option>
                    ";
                }
            }
            // Si no, que ponga un huevito de pascua
            else
            {
                $respuesta = '<option value="XXX">ERROR - No hay autores registrados</option>';
            }
        } 
        catch (Exception $e) 
        {
            $respuesta = "ERROR: " . $e;
        }
        finally
        {
            cerrar_conexion($conexion);
            return $respuesta;
        }
    }
    /* Termina función que obtiene autores para la página de edición de libro */

    /* Comienza función para registar un autor nuevo y retornar su ID */
    function nuevo_autor($nombre_autor, $descripcion_autor, $url_imagen_autor, $json_file)
    {
        // Debe devolver JSON con mensaje y número
        $respuesta = array();
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql_nuevo_autor = 
        "INSERT INTO `autor` (`nombre_autor`, `url_imagen_autor`, `informacion_autor`) VALUES ('$nombre_autor', '$url_imagen_autor', '$descripcion_autor')";
        // Ejecutamos la sentencia
        try 
        {
            // Si hay respuesta, que la genere
            if ($sentencia = mysqli_query($conexion, $sql_nuevo_autor))
            {
                // Obtener el ID del libro recién insertado
                $id_libro = mysqli_insert_id($conexion);
                $respuesta = array
                (
                    "mensaje" => "SUCCESS",
                    "author_id" => $id_libro
                );
            }
            // Si no, que saque un error
            else
            {
                $respuesta = array
                (
                    "mensaje" => "ERROR: No se pudo guardar. <br/> Intente nuevamente o contacte al administrador"
                );
            }
        } 
        catch (Exception $e) 
        {
            $respuesta = array
            (
                "mensaje" => "ERROR: " . $e
            );
        }
        finally
        {
            cerrar_conexion($conexion);
            return json_encode($respuesta);
        }
    }
    /* Termina función para registar un autor nuevo y retornar su ID */

    /* Comienza función que retorna los datos de un autor */
    function obtener_autor($id_autor, $json_file)
    {
        // Debe devolver un JSON con mensaje, nombre, URL de imagen, y descripción
        $respuesta = array();
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql_obtener_autor = 
        "SELECT `nombre_autor`, `url_imagen_autor`, `informacion_autor` FROM `autor` WHERE `id_autor` = $id_autor";
        // Ejecutamos la sentencia
        try 
        {
            // Si hay respuesta, que la genere
            if ($sentencia = mysqli_query($conexion, $sql_obtener_autor))
            {
                // Convertimos el resultado en un array asociativo
                $row = mysqli_fetch_assoc($sentencia);
                $respuesta = array
                (
                    "mensaje" => "SUCCESS",
                    "nombre_autor" => $row['nombre_autor'],
                    "url_imagen_autor" => $row['url_imagen_autor'],
                    "informacion_autor" => $row['informacion_autor']
                );
            }
            // Si no, que saque un error
            else
            {
                $respuesta = array
                (
                    "mensaje" => "ERROR: No se pudo cargar la información. <br/> Intente nuevamente o contacte al administrador"
                );
            }
        } 
        catch (Exception $e) 
        {
            $respuesta = array
            (
                "mensaje" => "ERROR: " . $e
            );
        }
        finally
        {
            cerrar_conexion($conexion);
            return json_encode($respuesta);
        }
    }
    /* Termina función que retorna los datos de un autor */

    /* Comienza función para actualizar los datos de un autor */
    function actualizar_autor($id_autor, $nombre_autor, $descripcion_autor, $url_imagen_autor, $json_file)
    {
        // Debe devolver string
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql_actualizar_autor = 
        "UPDATE `autor` SET `nombre_autor`='$nombre_autor',`url_imagen_autor`='$url_imagen_autor',`informacion_autor`='$descripcion_autor' WHERE `id_autor` = $id_autor";
        // Try-Catch
        try 
        {
            // Ejecutamos la sentencia
            if (mysqli_query($conexion, $sql_actualizar_autor)) 
            {
                // Retornamos mensaje de éxito
                $respuesta = "SUCCESS";
            } 
            else 
            {
                // Si hay error en la inserción
                $respuesta = 'ERROR: ' . mysqli_error($conexion);
            }
        }
        catch (Exception $e) 
        {
            $respuesta = $e->getMessage();
        }
        finally
        {
            cerrar_conexion($conexion);
            return $respuesta;
        }
    }
    /* Termina función para actualizar los datos de un autor */
?>