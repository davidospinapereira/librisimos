<?php
    /* Comienza Recepción y respuesta de datos AJAX para el segmento de login en inicio */
    if (isset($_POST['login_check']))
    {
        $user = $_POST['user'];
        $pass = $_POST['pass'];
        /* De aquí llamamos a una función que busque los datos en la base de datos. */
        // Invocamos un archivo en model, a ver cómo nos va
        require '../model/database_functions.php';
        // La función usuario_existe retorna un array con código de respuesta, nombre de usuario e id de usuario
        $usuario_existe = usuario_existe($user, $pass, '../model/connection_data.json');
        echo json_encode($usuario_existe);
    }
    else
    {
        echo 'No pasó el login_check';
    }
    /* Termina Recepción y respuesta de datos AJAX para el segmento de login en inicio */
?>