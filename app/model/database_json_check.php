<?php
    function json_check($json_file)
    {
        // Verificar si el archivo existe
        // Si el archivo no existe
        if (!file_exists($json_file)) 
        {
            // Debemos retornar un valor que distinga
            return "JSON_NO_EXISTE";
        }
        // Si el archivo sí existe
        else
        {
            /* Verificamos si el archivo tiene la estructura deseada */
            // Leemos el contenido del archivo
            $json_data = file_get_contents($json_file);
            // Decodificamos el JSON en un array associativo
            $data = json_decode($json_data, true);
            // Verificar si el JSON fue decodificado correctamente
            if (json_last_error() !== JSON_ERROR_NONE) 
            {
                // Si no tiene la decodificación correcta debemos reportarlo
                return "JSON_DECODE_ERROR";
            }
            else
            {
                // Definimos la estructura requerida
                $required_structure = ['db_host', 'db_user', 'db_pass', 'db_name'];
                // Verificamos si todas las claves necesarias están presentes
                $structure_valid = true;
                foreach ($required_structure as $key) 
                {
                    // A la primera falla en la búsqueda de estructura es que el archivo está mal.
                    if (!array_key_exists($key, $data)) 
                    {
                        $structure_valid = false;
                        break;
                    }
                }
                if ($structure_valid) 
                {
                    // Si el archivo JSON tiene la estructura correcta, reporta esto
                    return "JSON_EXISTE_CORRECTO";
                }
                else
                {
                    // Si el archivo JSON no tiene la estructura correcta, reporta esto otro
                    return "JSON_EXISTE_INCORRECTO";
                }
            }
        }
    }
?>