<?php
    require '../model/database_handler.php';
    $json_file = '../model/connection_data.json';
    /* Comienza invocación AJAX */
    if(isset($_POST['obtener_autores_edit']))
    {
        echo obtener_autores_edit($json_file);
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
?>