<!-- Vamos a generar un guardador rápido de secciones del libro de ojos de serpiente -->
<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guardando secciones del libro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <!-- Titulemos primero -->
        <div style="margin-bottom: 20px"><h3>Guardando secciones del libro</h3></div>
        <!-- Segundo, el input -->
        <h4>Título</h4>
        <input type="text" id="titulo">
        <h4>Contenido</h4>
        <textarea id="contenido" placeholder="Comienza a escribir..."></textarea>
        <button id="guardar-seccion">Guardar sección</button>
        <!-- Tercero, el botón de guardado -->
        <!-- Javascript necesario para el editor -->
        <script src="./view/js/jquery-3.7.1.min.js"></script>
        <script src="./view/js/tinymce/tinymce.min.js"></script>
        <script src="./view/js/test-scripts.js"></script>
    </body>
</html>