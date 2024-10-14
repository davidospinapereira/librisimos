<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    // Procesar archivo para usuario
    if (isset($_FILES['user_file'])) 
    {
        $file = $_FILES['user_file'];
        if ($file['error'] === UPLOAD_ERR_OK) 
        {
            // Ajustar la ruta para salir de la carpeta "controller" e ir a "view/uploads/users/"
            $uploadDir = __DIR__ . '/../view/uploads/users/';

            // Verificar si la carpeta existe, si no, crearla
            if (!file_exists($uploadDir)) 
            {
                mkdir($uploadDir, 0777, true);
            }

            $imageType = exif_imagetype($file['tmp_name']);

            // Verificar si el archivo es una imagen JPEG o PNG
            if ($imageType == IMAGETYPE_JPEG || $imageType == IMAGETYPE_PNG) 
            {
                list($width, $height) = getimagesize($file['tmp_name']);

                // Calcular las nuevas dimensiones manteniendo la proporción
                $newWidth = 500;
                $newHeight = 500;

                if ($width > $height) 
                {
                    $newHeight = ($height / $width) * $newWidth;
                } 
                else 
                {
                    $newWidth = ($width / $height) * $newHeight;
                }

                // Crear una nueva imagen en blanco
                $dst = imagecreatetruecolor(500, 500);

                // Cargar la imagen desde el archivo
                if ($imageType == IMAGETYPE_JPEG) 
                {
                    $src = imagecreatefromjpeg($file['tmp_name']);
                } 
                else 
                {
                    $src = imagecreatefrompng($file['tmp_name']);
                }

                // Rellenar el fondo con blanco
                $white = imagecolorallocate($dst, 255, 255, 255);
                imagefill($dst, 0, 0, $white);

                // Redimensionar la imagen
                imagecopyresampled($dst, $src, (500 - $newWidth) / 2, (500 - $newHeight) / 2, 0, 0, $newWidth, $newHeight, $width, $height);

                // Guardar la imagen redimensionada como JPEG
                $newFilename = $uploadDir . uniqid() . '.jpg';
                imagejpeg($dst, $newFilename, 90);

                // Liberar memoria
                imagedestroy($dst);
                imagedestroy($src);

                // Devolver la ruta relativa del archivo guardado para su uso en la base de datos
                $relativePath = 'view/uploads/users/' . basename($newFilename);
                echo $relativePath;
                exit();
            } 
            else 
            {
                // Formato de archivo equivocado
                echo 'WRONG_FORMAT';
                exit();
            }
        } 
        else 
        {
            // Error al subir el archivo
            echo 'UPLOAD_ERROR';
            exit();
        }
    }

    // Procesar archivo para autor
    if (isset($_FILES['author_file'])) 
    {
        $file = $_FILES['author_file'];
        if ($file['error'] === UPLOAD_ERR_OK) 
        {
            // Ajustar la ruta para salir de la carpeta "controller" e ir a "view/uploads/users/"
            $uploadDir = __DIR__ . '/../view/uploads/authors/';

            // Verificar si la carpeta existe, si no, crearla
            if (!file_exists($uploadDir)) 
            {
                mkdir($uploadDir, 0777, true);
            }

            $imageType = exif_imagetype($file['tmp_name']);

            // Verificar si el archivo es una imagen JPEG o PNG
            if ($imageType == IMAGETYPE_JPEG || $imageType == IMAGETYPE_PNG) 
            {
                list($width, $height) = getimagesize($file['tmp_name']);

                // Calcular las nuevas dimensiones manteniendo la proporción
                $newWidth = 500;
                $newHeight = 500;

                if ($width > $height) 
                {
                    $newHeight = ($height / $width) * $newWidth;
                } 
                else 
                {
                    $newWidth = ($width / $height) * $newHeight;
                }

                // Crear una nueva imagen en blanco
                $dst = imagecreatetruecolor(500, 500);

                // Cargar la imagen desde el archivo
                if ($imageType == IMAGETYPE_JPEG) 
                {
                    $src = imagecreatefromjpeg($file['tmp_name']);
                } 
                else 
                {
                    $src = imagecreatefrompng($file['tmp_name']);
                }

                // Rellenar el fondo con blanco
                $white = imagecolorallocate($dst, 255, 255, 255);
                imagefill($dst, 0, 0, $white);

                // Redimensionar la imagen
                imagecopyresampled($dst, $src, (500 - $newWidth) / 2, (500 - $newHeight) / 2, 0, 0, $newWidth, $newHeight, $width, $height);

                // Guardar la imagen redimensionada como JPEG
                $newFilename = $uploadDir . uniqid() . '.jpg';
                imagejpeg($dst, $newFilename, 90);

                // Liberar memoria
                imagedestroy($dst);
                imagedestroy($src);

                // Devolver la ruta relativa del archivo guardado para su uso en la base de datos
                $relativePath = 'view/uploads/authors/' . basename($newFilename);
                echo $relativePath;
                exit();
            } 
            else 
            {
                // Formato de archivo equivocado
                echo 'WRONG_FORMAT';
                exit();
            }
        } 
        else 
        {
            // Error al subir el archivo
            echo 'UPLOAD_ERROR';
            exit();
        }
    }

    // Procesar archivo para libros
    if (isset($_FILES['book_file'])) 
    {
        $file = $_FILES['book_file'];
        if ($file['error'] === UPLOAD_ERR_OK) {
            // Ajustar la ruta para salir de la carpeta "controller" e ir a "view/uploads/books/"
            $uploadDir = __DIR__ . '/../view/uploads/books/';

            // Verificar si la carpeta existe, si no, crearla
            if (!file_exists($uploadDir)) 
            {
                mkdir($uploadDir, 0777, true);
            }

            $imageType = exif_imagetype($file['tmp_name']);

            // Verificar si el archivo es una imagen JPEG o PNG
            if ($imageType == IMAGETYPE_JPEG || $imageType == IMAGETYPE_PNG) 
            {
                list($width, $height) = getimagesize($file['tmp_name']);

                // Calcular las nuevas dimensiones manteniendo la proporción
                $newWidth = 667;
                $newHeight = 1000;

                if ($width > $height) 
                {
                    $newHeight = ($height / $width) * $newWidth;
                } 
                else 
                {
                    $newWidth = ($width / $height) * $newHeight;
                }

                // Crear una nueva imagen en blanco
                $dst = imagecreatetruecolor(667, 1000);

                // Cargar la imagen desde el archivo
                if ($imageType == IMAGETYPE_JPEG) 
                {
                    $src = imagecreatefromjpeg($file['tmp_name']);
                } 
                else 
                {
                    $src = imagecreatefrompng($file['tmp_name']);
                }

                // Rellenar el fondo con blanco
                $white = imagecolorallocate($dst, 255, 255, 255);
                imagefill($dst, 0, 0, $white);

                // Redimensionar la imagen
                imagecopyresampled($dst, $src, (667 - $newWidth) / 2, (1000 - $newHeight) / 2, 0, 0, $newWidth, $newHeight, $width, $height);

                // Guardar la imagen redimensionada como JPEG
                $newFilename = $uploadDir . uniqid() . '.jpg';
                imagejpeg($dst, $newFilename, 90);

                // Liberar memoria
                imagedestroy($dst);
                imagedestroy($src);

                // Devolver la ruta relativa del archivo guardado para su uso en la base de datos
                $relativePath = 'view/uploads/books/' . basename($newFilename);
                echo $relativePath;
                exit();
            } 
            else 
            {
                // Formato de archivo equivocado
                echo 'WRONG_FORMAT';
                exit();
            }
        } 
        else 
        {
            // Error al subir el archivo
            echo 'UPLOAD_ERROR';
            exit();
        }
    }

    // Si no se recibió ningún archivo
    if (!isset($_FILES['user_file']) && !isset($_FILES['book_file'])) 
    {
        echo 'NO_FILE_RECEIVED';
        exit();
    }
}
?>