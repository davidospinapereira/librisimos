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
    if(isset($_POST['cargar_botones']))
    {
        echo cargar_botones($_POST['id_autor'], $_POST['id_usuario'], $json_file);
    }
    if(isset($_POST['author_grid']))
    {
        echo author_grid($_POST['input'], $json_file);
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

    /* Comienza función para colocar los botones de función en la página de autor */
    function cargar_botones($id_autor, $id_usuario, $json_file)
    {
        // Debe devolver String
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql_tipo_usuario = 
        "SELECT tu.`id_tipo_usuario` FROM `tipo_usuario` tu INNER JOIN `usuario` u ON (u.`id_tipo_usuario` = tu.`id_tipo_usuario`) WHERE u.`id_usuario` = $id_usuario";
        try 
        {
            if ($sentencia_tipo = mysqli_query($conexion, $sql_tipo_usuario))
            {
                $respuesta .= "<div class='col w100'>";
                // Sólo debe haber un valor encontrado
                $row = mysqli_fetch_assoc($sentencia_tipo);
                if ($row['id_tipo_usuario'] < 3)
                {
                    $respuesta .= " <button class='control' onclick='editarAutor($id_autor)';'>Editar Autor</button>";
                    /* window.location.href='index.php?page=edit-page&book-id=$book_id'; */
                }
                $respuesta .= "</div>";
            }
        }        
        catch (Exception $e) 
        {
            // Si hay un error, que me lo muestre
            $respuesta .= "<div class='col w100'>Error en el programa:<br/> $e->getMessage()</div>";
        }
        finally
        {
            cerrar_conexion($conexion);
            return $respuesta;
        }
    }
    /* Termina función para colocar los botones de función en la página de autor */

    /* Comienza función que retorna un grid de autores en la página de búsqueda de autor */
    function author_grid($termino_busqueda, $json_file)
    {
        // Debe devolver código HTML en un string
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Convertimos el término de búsqueda en entidades HTML para que no haya errores con tildes con los textos con tildes y acentos.
        $termino_busqueda = html_entity_decode($termino_busqueda, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // Luego preparamos un statement
        $sql_grid_autores = 
        "SELECT a.`id_autor`, a.`nombre_autor`, a.`url_imagen_autor`, a.`informacion_autor`, COUNT(DISTINCT l.`id_libro`) AS `cantidad_libros` FROM `autor` a LEFT JOIN `autores_libro` al ON a.`id_autor` = al.`id_autor` LEFT JOIN `libro` l ON al.`id_libro` = l.`id_libro` WHERE (LOWER(a.`nombre_autor`) COLLATE utf8_general_ci LIKE '%$termino_busqueda%' OR LOWER(a.`informacion_autor`) COLLATE utf8_general_ci LIKE '%$termino_busqueda%') GROUP BY a.`id_autor`";
        // Ejecutamos la sentencia
        try 
        {
            // Si hay respuesta, que la genere
            if ($sentencia = mysqli_query($conexion, $sql_grid_autores))
            {
                $respuesta .= "<div class='col w100' id='cards-grid'>";
                if (mysqli_num_rows($sentencia) > 0) 
                {
                    while ($row = mysqli_fetch_assoc($sentencia)) 
                    {
                        $id_autor = $row['id_autor'];
                        if ($row['url_imagen_autor'] != '' || $row['url_imagen_autor'] != NULL)
                        {
                            $url_imagen_autor = $row['url_imagen_autor'];
                        }
                        else
                        {
                            $url_imagen_autor = './view/uploads/authors/generic-author-avatar.png';
                        }
                        $nombre_autor = $row['nombre_autor'];
                        $cantidad_libros = $row['cantidad_libros'];
                        $texto_libros = '';
                        // Texto variable dependiendo de la cantidad de libros registrados
                        if ($cantidad_libros == 0 || $cantidad_libros == '0')
                        {
                            $texto_libros = '(No hay libros registrados)';
                        }
                        else if ($cantidad_libros == 1 || $cantidad_libros == '1')
                        {
                            $texto_libros = '1 libro registrado';
                        }
                        else
                        {
                            $texto_libros = $cantidad_libros . ' libros registrados';
                        }
                        $informacion_autor = substr($row['informacion_autor'], 0, 45) . "...";
                        $respuesta .= 
                        "
                        <div class='card'>
                            <a href='index.php?page=author-page&author-id=$id_autor'>
                                <div class='poster'>
                                    <img src='./$url_imagen_autor'>
                                </div>
                                <div class='details'>
                                    <h2>$nombre_autor</h2>
                                    <div class='sections'>
                                        <span>$texto_libros</span>
                                    </div>
                                    <div class='sinopsis'>
                                        <p>$informacion_autor</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        ";
                    }
                    $respuesta .= "</div>";
                }
                // Si no, que mande la sección de nada encontrado
                else
                {
                    $respuesta = '<div class="col w100" id="nothing-found"><h2>¡OOPS! Su búsqueda no arrojó ningún resultado</h2><h4>Por favor cambie los parámetros de búsqueda.</h4></div>';
                }
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
    /* Termina función que retorna un grid de autores en la página de búsqueda de autor */
?>