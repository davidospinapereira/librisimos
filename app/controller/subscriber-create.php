<?php
    require '../model/database_handler.php';
    // Función para crear un usuario suscriptor
    function crear_suscriptor($data)
    {
        // Aquí entra como data un array de datos codificados como JSON
        $data_to_process = json_decode($data);
        /* 'nombre' : $('#nombre-usuario').val(),
        'apellido' : $('#apellido-usuario').val(),
        'nacimiento' : $('#fecha-nacimiento').val(),
        'email' : $('#email-usuario').val(),
        'login' : $('#login-usuario').val(),
        'pass' : $('#pass-usuario').val(),
        'imagen' : url_imagen */
        // Preparamos el array de respuesta
        $respuesta = '';
        // Sacamos algunos datos importantes
        // URL de la imagen será el avatar por ahora
        $url_imagen = 'view/img/user-avatar.png';
        // La contraseña debe hashearse
        $pass_hashed = password_hash($data_to_process->pass, PASSWORD_DEFAULT, ['cost' => 10]);
        // Generamos la conexión
        $conexion = abrir_conexion('../model/connection_data.json');
        // Preparamos un statement
        $sql = "INSERT INTO `usuario`(`nombres_usuario`, `apellidos_usuario`, `fecha_nacimiento_usuario`, `email_usuario`, `url_imagen_usuario`, `login_usuario`, `pass_usuario`, `id_tipo_usuario`) VALUES ('$data_to_process->nombre','$data_to_process->apellido','$data_to_process->nacimiento','$data_to_process->email','$url_imagen','$data_to_process->login','$pass_hashed', 3)";
        try 
        {
            // Ejecutamos la sentencia
            $sentencia = mysqli_query($conexion, $sql);
            // Preparamos el código de éxito
            $respuesta = "NEW_USER_CREATED";
        } 
        catch (Exception $e) 
        {
            // Si pasa cualquier cosa preparamos el código de error
            $respuesta = "NEW_USER_ERROR: " . $e;
        }
        // Retornamos la respuesta como array
        return $respuesta;
    }
    
    if (isset($_POST['create_subscriber']))
    {        
        // Si estamos llamando al creador de suscriptores, generamos un código de éxito o uno de error, y luego lo devolvemos al llamado AJAX
        // Es posible que no funcione $_POST, por lo que uso $_REQUEST
        $data = $_REQUEST['data'];
        $respuesta = crear_suscriptor($data);
        echo $respuesta;
        /* echo(json_encode(crear_suscriptor($_REQUEST['data']))); */
    }
?>