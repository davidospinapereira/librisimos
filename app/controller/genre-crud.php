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

    if(isset($_POST['obtener_generos_listado']))
    {
        echo obtener_generos_listado($json_file);
    }

    if(isset($_POST['guardar_genero']))
    {
        echo guardar_genero($_POST['nombre_genero'], $_POST['color_genero'], $json_file);
    }

    if(isset($_POST['borrar_libro']))
    {
        echo borrar_libro($_POST['id_genero'], $json_file);
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

    /* Comienza función que obtiene géneros para la página de administración de géneros */
    function obtener_generos_listado($json_file)
    {
        // Debe devolver string
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql_generos = 
        "SELECT `id_genero`, `nombre_genero`, `color_genero` FROM `genero`";
        try 
        {
            // Si hay respuesta, que la genere
            if ($sentencia = mysqli_query($conexion, $sql_generos))
            {
                $respuesta .= 
                '
                <div id="table-overlay" class=""><span class="loader"></span></div>
                <table class="genre-table">
                    <caption>Géneros existentes</caption>
                    <!-- Encabezado de la tabla -->
                    <tbody>
                        <tr>
                            <th>Nombre</th>
                            <th>Color</th>
                            <th>Acciones</th>
                        </tr>
                        <!-- Contenido de la tabla -->
                ';
                while ($row = mysqli_fetch_assoc($sentencia)) 
                {
                    $id_genero = $row['id_genero'];
                    $nombre_genero = $row['nombre_genero'];
                    $color_genero = $row['color_genero'];
                    $sql_conteo = "SELECT COUNT(`id_libro`) FROM `generos_libro` WHERE `id_genero` = $id_genero";
                    $sentencia_conteo = mysqli_query($conexion, $sql_conteo);
                    $row_conteo = mysqli_fetch_assoc($sentencia_conteo);
                    $conteo = $row_conteo['COUNT(`id_libro`)'];
                    $funcion_eliminar = $conteo == 0 ? 'eliminarGenero(' . $id_genero . ')' : 'mensaje(\'error\', \'<b>ERROR</b><br/>Este género tiene ' . $conteo . ' libros a su nombre.<br/>Debe cambiar los libros de género antes de borrar este género.\')';
                    $respuesta .= 
                    '
                        <tr>
                            <td data-cell="nombre" class="nombre-genero">' . $nombre_genero . ' </td>
                            <td data-cell="color" class="color-genero"><span class="tile" style="background-color: #' . $color_genero . '">#' . $color_genero . '</span></td>
                            <td data-cell="acciones" class="acciones">
                                <span class="button continue" onclick="editarGenero(' . $id_genero . ', \'' . $nombre_genero . '\', \'' . $color_genero . '\')"><div class="tooltip">Editar</div><i class="bx bx-book-reader"></i></span>
                                <span class="button quit" onclick="' . $funcion_eliminar . '"><div class="tooltip">Eliminar</div><i class="bx bx-x"></i></span>
                            </td>
                        </tr>
                    ';
                }
                $respuesta .= '</tbody>
                    </table>';
            }
            // Si no, que ponga un huevito de pascua
            else
            {
                $respuesta = "<div class='col w100' id='nothing-found'><h2>¡OOPS! No hay géneros en el sistema.</h2><h4>Si esto es un error, por favor contacte al administrador.</h4></div>";
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
    /* Termina función que obtiene géneros para la página de administración de géneros */

    /* Comienza función para guardar un género en la página de administración de géneros */
    function guardar_genero($nombre_genero, $color_genero, $json_file)
    {
        // Debe devolver string
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql = 
        "INSERT INTO `genero`(`nombre_genero`, `color_genero`) VALUES ('$nombre_genero', '$color_genero')";
        try 
        {
            // Insertamos el género
            $sentencia = mysqli_query($conexion, $sql);
            // Retornamos el listado renovado
            $respuesta = obtener_generos_listado($json_file);
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
    /* Termina función para guardar un género en la página de administración de géneros */

    /* Comienza función para borrar un género en la página de administración de géneros */
    function borrar_libro($id_genero, $json_file)
    {
        // Debe devolver string
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql = 
        "DELETE FROM `genero` WHERE `id_genero` = $id_genero";
        try 
        {
            // Borramos el género
            $sentencia = mysqli_query($conexion, $sql);
            // Retornamos el listado renovado
            $respuesta = obtener_generos_listado($json_file);
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
    /* Termina función para borrar un género en la página de administración de géneros */
?>