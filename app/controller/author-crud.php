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
?>