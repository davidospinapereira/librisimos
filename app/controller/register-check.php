<?php
    /* Comienza Recepción y respuesta de datos AJAX para el segmento de login en inicio */
    if (isset($_POST['register_check']))
    {
        $login = $_POST['login'];
        $email = $_POST['email'];
        // Invocamos el archivo que busca los datos solicitados en la base de datos
        require '../model/database_functions.php';
        // Ahora invocamos la función verificar_registro para ver si el usuario y/o el email existen en la base de datos
        $respuesta = verificar_registro($login, $email, '../model/connection_data.json');
        // Se supone que debe devolver un array que debemos cifrar como JSON
        echo json_encode($respuesta);
    }
    else
    {
        echo 'No pasó el register_check';
    }
    /* Termina Recepción y respuesta de datos AJAX para el segmento de login en inicio */
?>