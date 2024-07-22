<?php
    /* Todas las funciones serán manejadass por medio de mysqli, porque PDO probó ser incomprensible para mí... */
    
    // Comienza función que abre conexión
    function abrir_conexion($json_file)
    {
        // Primero invocamos el JSON, sin miedo porque a estas alturas ya sabemos que el JSON existe y que está correcto, y que la base de datos está correcta
        // Abrimos el contenido del archivo JSON
        $json_data = file_get_contents($json_file);
        // Decodificamos el JSON en un array asociativo
        $data = json_decode($json_data);
        // Parámetros para la configuración de base de datos con base en el JSON
        $db_host = $data->db_host;
        $db_user = $data->db_user;
        $db_pass = $data->db_pass;
        $db_name = $data->db_name;
        /* // Lista de tablas
        $tabla_usuarios = "usuario"; // Una tabla para usuarios
        error_reporting(1); // No se muestran errores
        // Generamos la conexión
        $conexion = new mysqli($host, $user, $pass, $base); // Host, usuario, contraseña y base de datos
        // Manejo de errores
        if ($conexion -> connect_errno)
        {
            echo "Falla en la conexión a base de datos";
            exit();
        } */

        try
        {
            // Que retorne la conexión ejecutada
            return new mysqli($db_host, $db_user, $db_pass, $db_name); // Host, usuario, contraseña y base de datos
        }
        catch (Exception $e)
        {
            // Si hay falla en la conexión, entonces no retorne nada pero reporte.
            echo "Falla en la conexión a base de datos: " . $e . "<br/>";
            exit();
        }
    }
    // Termina función que abre conexión

    // Comienza función que cierra conexión
    function cerrar_conexion($conexion)
    {
        // Es una sola línea, pero invocarla desde función lo hace más amigable
        mysqli_close($conexion);
    }
    // Termina función que cierra conexión

    // Comienza función que prueba una conexión
    function probar_conexion($host, $name, $user, $pass)
    {
        try
        {
            // Genera la conexión sólo para probarla
            $conexion = new mysqli($host, $user, $pass, $name); // Host, usuario, contraseña y base de datos
            // Y la cerramos de inmediato
            mysqli_close($conexion);
            // Si logramos ambas partes, retornamos true
            return true;
        }
        catch (Exception $e)
        {
            // Si hay falla en la conexión, retornamos false
            return false;
        }
    }
?>