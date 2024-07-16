<?php
    /* Podríamos generar un archivo de constantes... Luego miro eso */
    /* Invocamos con require el archivo de header general que se encuentra en view */
    require './app/view/general_header.php';
    /* Invocamos con require el archivo de footer general que se encuentra en view */
    require './app/view/general_footer.php';
    /* Invocamos el archivo de verificación de base de datos, que se encuentra en model */
    require './app/model/database_json_check.php';
    /* Generamos el header */
    load_header();
    // Verificamos el archivo JSON con los datos de conexión a base de datos.
    $json_check = json_check('./app/model/connection_data.json');
    if ($json_check == "NO_EXISTE")
    {
        // El archivo JSON no existe. Cargamos installer con un mensaje correspondiente.
        echo 'El archivo JSON no existe.<br/>Cargamos installer con un mensaje correspondiente<br/>';
    }
    else if ($json_check == "DECODE_ERROR")
    {
        // El archivo tiene un error de decodificación. Borramos el archivo JSON y cargamos installer con un mensaje correspondiente.
        echo 'El archivo tiene un error de decodificación.<br/>Borramos el archivo JSON y cargamos installer con un mensaje correspondiente<br/>';
    }
    else if ($json_check == "EXISTE_INCORRECTO")
    {
        // El archivo existe pero no tiene la estructura correcta. Borramos el archivo JSON y cargamos installer con un mensaje correspondiente.
        echo 'El archivo existe pero no tiene la estructura correcta.<br/>Borramos el archivo JSON y cargamos installer con un mensaje correspondiente<br/>';
    }
    else
    {
        // El archivo JSON existe y tiene la estructura correcta. Probamos la conexión con la base de datos.
        // Invocamos el controlador de conexión a base de datos
        echo 'El archivo JSON existe y tiene la estructura correcta.<br/>Probamos la conexión con la base de datos.<br/>';
        require './app/model/database_db_check.php';
        $db_check = db_check('./app/model/connection_data.json');
        if ($db_check == "NO_EXISTE")
        {
            // La base de datos no existe. Cargamos installer con un mensaje correspondiente.
            echo 'La base de datos no existe. Cargamos installer con un mensaje correspondiente.<br/>';
        }
        else if ($db_check == "EXISTE_INCORRECTA")
        {
            // La base de datos existe pero no tiene la estructura deseada. Cargamos installer con un mensaje correspondiente.
            echo 'La base de datos existe pero no tiene la estructura deseada. Cargamos installer con un mensaje correspondiente.<br/>';
        }
        else
        {
            // La base de datos existe y tiene la estructura deseada. Por fin, cargamos HOME.
            echo "La base de datos existe y tiene la estructura deseada. Por fin, cargamos HOME.<br/>";
        }
    }
    /* Generamos el footer */
    load_footer();
?>