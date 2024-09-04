/* Comienza inicialización del editor TinyMCE */
tinymce.init
({
    selector: '#contenido',
    language: 'es',
    branding: false,
    menubar: false,
    resize: false,
    plugins: 'code lists',
    toolbar: 'undo redo | styles | bold italic underline | cut copy paste | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | code'
});
/* Termina inicialización del editor TinyMCE */

/* Función para guardar secciones del libro */
$('#guardar-seccion').on('click', function()
{
    // Sacamos el título de la sección
    var titulo = $('#titulo').val();
    // Sacamos el contenido de TinyMCE
    var contenido = tinymce.activeEditor.getContent();
    // Luego, mandamos a AJAX
    $.ajax
    ({
        type: 'POST',
        url: './controller/section-crud.php',
        data:
        {
            save_section: true,
            titulo_seccion: titulo,
            contenido_seccion: contenido,
            // 1 es ojos de serpiente, 2 es 20 poemas de amor y 3 es Cosmos
            id_libro: 3
        },
        async: true,
        success: function(data)
        {
            alert(data);
            $('#titulo').val('');
            tinyMCE.activeEditor.setContent('');
        },
        error: function(error)
        {
            alert(error);
        }
    });
});
/* Termina función para guardar secciones del libro */