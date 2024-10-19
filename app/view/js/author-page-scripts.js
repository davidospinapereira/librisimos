/* Comienza la declaración de variables */
var author_id = $('#author-id').val();
var user_id = $('#user-id').val();
var books_loading = $('#books-loading');
/* Termina la declaración de variables */

/* Comienzan funciones automáticas */
$(document).ready(function()
{
    // Primero, activamos las dos áreas de loading
    books_loading.addClass('active');
    setTimeout(async function()
    {
        // Título de la página
        $(document).prop('title', 'Página de autor - Librísimos');
        // Cargamos los datos
        await cargarDatos(author_id);
        // Cargamos los botones
        await cargarBotones(user_id, author_id);
        // Cargamos los libros escritos por el autor
        await cargarLibros(author_id);
        // Quitamos el spinner
        books_loading.removeClass('active');
    }, 500);
});
/* Terminan funciones automáticas */

/* Comienza función que carga los datos del autor */
function cargarDatos(author_id)
{
    // Primero, jalamos los datos por AJAX
    $.ajax
    ({
        type: 'POST',
        url: './controller/author-crud.php',
        data:
        {
            obtener_autor: true,
            id_autor: author_id
        },
        async: true,
        success: function(data)
        {
            // Me mandará datos en JSON, debo decodificarlos
            var respuesta = jQuery.parseJSON(data);
            // Y luego de decodificados, debo ponerlos en su sitio
            if (respuesta.mensaje == 'SUCCESS')
            {
                // Colocamos el nombre de la página desde aquí:
                $(document).prop('title', 'Autor: ' + respuesta.nombre_autor + ' - Librísimos');
                // Si el código es éxito, sé que vendrá con todos los demás datos
                if (respuesta.url_imagen_autor == '' || respuesta.url_imagen_autor == 'undefined' || respuesta.url_imagen_autor == null)
                {
                    respuesta.url_imagen_autor = 'view/uploads/authors/generic-author-avatar.png';
                }
                $('#image').html('<img src="./' + respuesta.url_imagen_autor + '" alt="Carátula del libro" class="book-cover" id="book-cover">');
                $('#author').html('ID del autor: <b>' + author_id + '</b>');
                $('#title').html(respuesta.nombre_autor);
                $('#sinopsis').html('<h4>Descripción:</h4><p>' + respuesta.informacion_autor + '</p>');
            }
            else
            {
                // Colocamos el nombre de la página desde aquí:
                $(document).prop('title', 'Error en página de libro - Librísimos');
                // Si el código no es de éxito es de error, sé que vendrá con sólo un campo
                mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + respuesta.mensaje + '<br/>Por favor contacte al desarrollador');
            }
        },
        error: function(error)
        {
            // Si hay un error, generemos un mensaje
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
        }
    });
}
/* Termina función que carga los datos del autor */

/* Comienza función que carga los botones de función */
function cargarBotones(user_id, author_id)
{
    // Primero, jalamos los datos por AJAX
    $.ajax
    ({
        type: 'POST',
        url: './controller/author-crud.php',
        data:
        {
            cargar_botones: true,
            id_autor: author_id,
            id_usuario: user_id
        },
        async: true,
        success: function(data)
        {
            // Si hay un error, lo montará ahí
            $('#funciones').html(data);
        },
        error: function(error)
        {
            $('#funciones').html('<div class="col w100">Error en el programa:<br/>' + error + '</div>');
            // Si hay un error, generemos un mensaje
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
        }
    });
}
/* Termina función que carga los botones de función */

/* Comienza función para acceder a la página de editar autor, exclusiva para administradores */
function editarAutor(idAutor)
{
    // Sólo hay que redirigir y listo.
    window.location.href = "index.php?page=edit-author&author-id=" + idAutor;
}
/* Termina función para acceder a la página de editar autor, exclusiva para administradores */

/* Comienza función que carga los libros escritos por el autor actual */
function cargarLibros(idAutor)
{
    // Aquí tenemos que invocar AJAX para la sección de libros
    $.ajax
    ({
        type: 'POST',
        url: './controller/book-crud.php',
        data:
        {
            get_author_books: true,
            author_id: idAutor
        },
        async: true,
        success: function(data)
        {
            $('#cards-grid').html(data);
        },
        error: function(error)
        {
            // Si hay un error, generemos un mensaje
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
        }
    });
}
/* Termina función que carga los libros escritos por el autor actual */