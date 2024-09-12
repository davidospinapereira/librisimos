<?php
    require '../model/database_handler.php';
    $json_file = '../model/connection_data.json';
    /* Comienza invocación AJAX */
    if(isset($_POST['get_genres']))
    {
        echo obtener_generos($json_file);
    }

    if(isset($_POST['obtener_generos_edit']))
    {
        echo obtener_generos_edit($json_file);
    }
    /* Termina invocación AJAX */

    /* Comienza función que obtiene géneros para la página de buscador de libros */
    function obtener_generos($json_file)
    {
        // Debe devolver string
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql = 
        "SELECT `id_genero`, `nombre_genero` FROM `genero`";
        // Ejecutamos la sentencia
        try 
        {
            // Si hay respuesta, que la genere
            if ($sentencia = mysqli_query($conexion, $sql))
            {
                while ($row = mysqli_fetch_assoc($sentencia)) 
                {
                    $id_genero = $row['id_genero'];
                    $nombre_genero = $row['nombre_genero'];
                    $respuesta .= 
                    "
                        <option value='$id_genero'>$nombre_genero</option>
                    ";
                }
            }
            // Si no, que ponga un huevito de pascua
            else
            {
                $respuesta = '<option value="XXX">ERROR - No hay géneros registrados</option>';
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
    /* Termina función que obtiene géneros para la página de buscador de libros */

    /* Comienza función que obtiene géneros para la página de edición de libro */
    function obtener_generos_edit($json_file)
    {
        // Debe devolver string
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql = 
        "SELECT `id_genero`, `nombre_genero`, `color_genero` FROM `genero`";
        // Ejecutamos la sentencia
        try 
        {
            // Si hay respuesta, que la genere
            if ($sentencia = mysqli_query($conexion, $sql))
            {
                while ($row = mysqli_fetch_assoc($sentencia)) 
                {
                    $id_genero = $row['id_genero'];
                    $nombre_genero = $row['nombre_genero'];
                    $color_genero = $row['color_genero'];
                    $respuesta .= 
                    "
                        <option value='$id_genero' data-color='$color_genero'>$nombre_genero</option>
                    ";
                }
            }
            // Si no, que ponga un huevito de pascua
            else
            {
                $respuesta = '<option value="XXX">ERROR - No hay géneros registrados</option>';
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
    /* Termina función que obtiene géneros para la página de edición de libro */
?>