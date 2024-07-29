<?php
    /* Comienzan protocolos de sesión */
    // Iniciar sesión
    session_start();
    // Verificar si el usuario ha iniciado sesion
    if (!isset($_SESSION['user_id'])) 
    {
        header('Location: ../');
        exit();
    }
    /* Terminan protocolos de sesión */

    /* Comienzan invocaciones de archivos necesarios */
    require './controller/user-crud.php';
    require './view/header_template.php';
    require './view/footer_template.php';
    /* Terminan invocaciones de archivos necesarios */

    // Extraemos de la sesión el ID de usuario
    $user_id = $_SESSION['user_id'] ?? null;
    $json_file = './model/connection_data.json';
    
    /* Comienza verificación de si el perfil está completo por ChatGPT */
     // Redirige al editor de perfil si el perfil está incompleto
     if ($user_id && !perfil_completo($user_id, $json_file)) 
     {
        // Redirige al editor de perfil si el perfil está incompleto
        if ($_GET['page'] !== 'profile-edit') 
        {
            header("Location: index.php?page=profile-edit");
            exit(); // Termina el script después de la redirección
        }
    }
    // Cargar la página solicitada
    /* Termina verificación de si el perfil está completo por ChatGPT */

    $page = $_GET['page'] ?? 'main'; // Página predeterminada
    /* Termina verificación de parámetros GET */    

    /* Comienza extracción de datos de perfil */
    $perfil = obtener_perfil($user_id, $json_file);
    /* $respuesta = 
            [
                'nombres' => $consulta['nombres_usuario'],
                'apellidos' => $consulta['apellidos_usuario'],
                'fecha_nacimiento' => $consulta['fecha_nacimiento_usuario'],
                'email' => $consulta['email_usuario'],
                'url_imagen' => $consulta['url_imagen_usuario'],
                'login' => $consulta['login_usuario']
                'id_tipo' => $consulta['id_tipo_usuario']
            ]; */
    /* Termina extracción de datos de perfil */
    // Cargamos el header dinámico
    echo cargar_header($perfil, $page);
    // Cargamos la página interna
    require './view/' . $page . '.php';
    // Cargamos el footer dinámico
    echo cargar_footer($page);
?>

