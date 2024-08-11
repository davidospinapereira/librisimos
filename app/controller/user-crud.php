<?php
    require './model/database_handler.php';
    /* Comienzan funciones C del CRUD de usuarios */
    // C: CREATE
    
    /* Terminan funciones C del CRUD de usuarios */

    /* Comienzan funciones R del CRUD de usuarios */
    // R: READ
    // Función para verificar si un perfil está completo
    function perfil_completo($id, $json_file)
    {
        // Debe devolver booleano
        $respuesta = true;
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql = "SELECT nombres_usuario, apellidos_usuario, fecha_nacimiento_usuario, email_usuario FROM `usuario` WHERE id_usuario = $id";
        // Ejecutamos la sentencia
        $sentencia = mysqli_query($conexion, $sql);
        // Sabemos que encontraremos resultados, pero tenemos que colocar condicional
        if (mysqli_num_rows($sentencia) == 1)
        {
            // Separamos el resultado de la búsqueda en sus componentes
            $consulta = mysqli_fetch_array($sentencia);
            // Podríamos recorrer el array con un for, pero como conocemos lo que va a sacar, será mejor con un if
            if ($consulta['nombres_usuario'] == null || $consulta['apellidos_usuario'] == null || $consulta['fecha_nacimiento_usuario'] == null || $consulta['email_usuario'] == null )
            {
                $respuesta = false;
            }
        }
        // Finalmente, cerramos la conexión
        cerrar_conexion($conexion);
        return $respuesta;
    }

    // Función para obtener un perfil completo de usuario
    function obtener_perfil($id, $json_file)
    {
        // Debe devolver array
        $respuesta = array();
        // Primero, debemos generar la conexión
        $conexion = abrir_conexion($json_file);
        // Luego preparamos un statement
        $sql = "SELECT nombres_usuario, apellidos_usuario, fecha_nacimiento_usuario, email_usuario, url_imagen_usuario, login_usuario, id_tipo_usuario FROM `usuario` WHERE id_usuario = $id";
        // Ejecutamos la sentencia
        $sentencia = mysqli_query($conexion, $sql);
        // Sabemos que encontraremos resultados, pero tenemos que colocar condicional
        if (mysqli_num_rows($sentencia) == 1)
        {
            // Separamos el resultado de la búsqueda en sus componentes
            $consulta = mysqli_fetch_array($sentencia);
            // Generamos el array de sentencia con la forma que debemos ponerle
            $respuesta = 
            [
                'nombres' => $consulta['nombres_usuario'],
                'apellidos' => $consulta['apellidos_usuario'],
                'fecha_nacimiento' => $consulta['fecha_nacimiento_usuario'],
                'email' => $consulta['email_usuario'],
                'url_imagen' => $consulta['url_imagen_usuario'],
                'login' => $consulta['login_usuario'],
                'id_tipo' => $consulta['id_tipo_usuario']
            ];
        }
        // Finalmente, cerramos la conexión
        cerrar_conexion($conexion);
        return $respuesta;
    }

    // Función AJAX para extraer la URL de la imagen almacenada en la base de datos
    function obtener_foto_perfil($id, $json_file)
    {
        // Será muy parecida a la función de perfil completo
        
    }
    /* Terminan funciones R del CRUD de usuarios */

    /* Comienzan funciones U del CRUD de usuarios */
    // U: UPDATE
    /* Terminan funciones U del CRUD de usuarios */

    /* Comienzan funciones D del CRUD de usuarios */
    // D: DELETE
    /* Terminan funciones D del CRUD de usuarios */
?>