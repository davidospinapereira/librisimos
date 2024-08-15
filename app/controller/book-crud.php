<?php
    require '../model/database_handler.php';
    $json_file = '../model/connection_data.json';
    /* Comienza invocación AJAX */
    if(isset($_POST['get_list']))
    {
        echo generar_tabla($_POST['user_id'], $json_file);
    }
    /* Termina invocación AJAX */

    function generar_tabla($user_id, $json_file)
    {
        // Debe devolver booleano
        $respuesta = '';
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql = 
        "SELECT vs.`id_usuario`, vs.`id_seccion`, vs.`fecha_lectura_ver_seccion`, s.`numero_seccion`, s.`titulo_seccion`, cs.`id_libro`, l.`nombre_libro`, al.`id_autor`, GROUP_CONCAT(DISTINCT(a.`nombre_autor`) SEPARATOR ', ') AS `nombre_autor`, gl.`id_genero`, GROUP_CONCAT(DISTINCT(g.`nombre_genero`) SEPARATOR ', ') AS `nombre_genero` FROM `ver_seccion` AS vs INNER JOIN `seccion` AS s ON (vs.`id_seccion` = s.`id_seccion`) INNER JOIN `componer_seccion` AS cs ON (cs.`id_seccion` = s.`id_seccion`) INNER JOIN `libro` AS l ON (l.`id_libro` = cs.`id_libro`) INNER JOIN `autores_libro` AS al ON (al.`id_libro` = l.`id_libro`) INNER JOIN `autor` AS a ON (a.`id_autor` = al.`id_autor`) INNER JOIN `generos_libro` AS gl ON (gl.`id_libro` = l.`id_libro`) INNER JOIN `genero` AS g ON (g.`id_genero` = gl.`id_genero`) WHERE vs.`id_usuario` = $user_id GROUP BY l.`id_libro` ORDER BY vs.`fecha_lectura_ver_seccion` DESC";
        /* // Ejecutamos la sentencia
        $sentencia = mysqli_query($conexion, $sql); */
        if ($sentencia = mysqli_query($conexion, $sql))
        {
            /* obtener array asociativo */
            while ($row = mysqli_fetch_assoc($sentencia)) 
            {
                $respuesta .= 
                "
                    <tr>
                        <td data-cell='titulo'>".$row['nombre_libro']."</td>
                        <td data-cell='autor'>".$row['nombre_autor']."</td>
                        <td data-cell='genero'>".$row['nombre_genero']."</td>
                        <td data-cell='seccion'>".$row['numero_seccion']." - " . $row['titulo_seccion']. "</td>
                        <td data-cell='acciones' class='acciones'>
                            <span class='button continue' onclick='activarHerramienta(" . $row['id_seccion'] . ")'><div class='tooltip'>Continuar leyendo</div><i class='bx bx-book-reader'></i></span>
                            <span class='button quit' onclick='dejarDeLeer(" . $row['id_libro'] . ", " . $user_id . ")'><div class='tooltip'>Dejar de leer</div><i class='bx bx-x' ></i></span>
                        </td>
                    </tr>
                ";
            }
        }
        // Finalmente, cerramos la conexión
        cerrar_conexion($conexion);
        return $respuesta;
    }
?>