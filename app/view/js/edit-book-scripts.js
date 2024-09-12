/* Comienza definición de variables */
var book_id = $('#book-id').val();
var user_id = $('#user-id').val();
/* Termina definición de variables */

/* Comienzan funciones automáticas */
$(document).ready(function()
{
    // De profile-edit-scripts, tienes que cargar la imagen en su sitio
    loadBookData(book_id);
    loadGenres();
    loadAuthors();
    resetUsed(1);
    resetUsed(2);
    loadSections(book_id);

    /* Comienzan instancias de TinyMCE */
    tinymce.init
    ({
        selector: '#description',
        language: 'es',
        branding: false,
        menubar: false,
        resize: false,
        height: 200,
        plugins: 'code lists',
        toolbar: 'undo redo | bold italic underline | cut copy paste | alignleft aligncenter alignright alignjustify | code',
        license_key: 'gpl'
    });
    /* Terminan instancias de TinyMCE */
});
/* Terminan funciones automáticas */

/* Comienzan efectos para el acordeón */
function toggleSection(section)
{
    var section = $('#section-' + section);
    section.toggleClass('active');
}
/* Terminan efectos para el acordeón */

/* Comienza función que carga los datos del libro y los pone en su sitio */
function loadBookData(book_id)
{
    // Nos vamos de una, tenemos que cargar AJAX
    // Los datos son sólo el ID del libro
    $.ajax
    ({
        type: 'POST',
        url: './controller/book-crud.php',
        data:
        {
            get_book_edit_data: true,
            book_id
        },
        async: true,
        success: function(data)
        {
            // Tenemos que poner cada cosa en su lugar, primero que nada trayendo el JSON
            results = JSON.parse(data);
            $('#book-name').val(results.nombre_libro);
            if (results.url_caratula_libro == null || results.url_caratula_libro == '')
            {
                $('#image').css('background-image', 'url("./view/uploads/books/generic-book-cover.jpg")');
            }
            else
            {
                $('#image').css('background-image', 'url("./' + results.url_caratula_libro + '")');
            }
            $('#id-book').html('ID de libro: ' + book_id);
            $('#genre-list').html(results.generos_html);
            $('#author-list').html(results.autores_html);
            $('#description').html(results.sinopsis_libro);
        },
        error: function(error)
        {
            // Si hay un error, generemos un mensaje
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
        }
    });
}
/* Termina función que carga los datos del libro y los pone en su sitio */

/* Comienza función que carga las secciones del libro y genera tantas secciones como sea necesario */
function loadSections(book_id)
{
    // Aquí vamos, recibimos un HTML que insertamos
    $.ajax
    ({
        type: 'POST',
        url: './controller/section-crud.php',
        data:
        {
            obtener_secciones_edit: true,
            book_id
        },
        async: true,
        success: function(data)
        {
            $('#book-sections-data').html(data);
            // Instanciamos TinyMCE por este lado, sólo así me da
            tinymce.init
            ({
                selector: '.section-content',
                language: 'es',
                branding: false,
                menubar: false,
                resize: false,
                height: 350,
                plugins: 'code lists',
                toolbar: 'undo redo | styles | bold italic underline | cut copy paste | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | code',
                license_key: 'gpl'
            });
        },
        error: function(error)
        {
            // Si hay un error, generemos un mensaje
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
            // Y luego añadimos un pequeño huevo de pascua...
            $('#available-genres').append('<option value="XXX">ERROR - No use esto</option>');
        }
    });
}
/* Termina función que carga las secciones del libro y genera tantas secciones como sea necesario */

/* Comienza función que quita una baldosa */
function quitarBaldosa(tipo, id)
{
    // Si tipo es 1, es género
    if (tipo == 1)
    {
        $('#genero-' + id).remove();
    }
    // Si no, es autor
    else
    {
        $('#autor-' + id).remove();
    }
    // Sin importar cuál sea, hay que resetear los usados de ese tipo
    resetUsed(tipo);
}
/* Termina función que quita una baldosa */

/* Comienza función que carga en el select de géneros a todos los géneros */
function loadGenres()
{
    // Esto lo llamamos por AJAX obtener_generos_edit
    $.ajax
    ({
        type: 'POST',
        url: './controller/genre-crud.php',
        data:
        {
            obtener_generos_edit: true
        },
        async: true,
        success: function(data)
        {
            // Si los datos llegan correctamente, añadimos al select lo que nos envíe el sistema
            $('#available-genres').append(data);
        },
        error: function(error)
        {
            // Si hay un error, generemos un mensaje
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
            // Y luego añadimos un pequeño huevo de pascua...
            $('#available-genres').append('<option value="XXX">ERROR - No use esto</option>');
        }
    });
}
/* Termina función que carga en el select de géneros a todos los géneros */

/* Comienza función que carga en el select de géneros a todos los autores */
function loadAuthors()
{
    // Esto lo llamamos por AJAX obtener_generos_edit
    $.ajax
    ({
        type: 'POST',
        url: './controller/author-crud.php',
        data:
        {
            obtener_autores_edit: true
        },
        async: true,
        success: function(data)
        {
            // Si los datos llegan correctamente, añadimos al select lo que nos envíe el sistema
            $('#available-authors').append(data);
        },
        error: function(error)
        {
            // Si hay un error, generemos un mensaje
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
            // Y luego añadimos un pequeño huevo de pascua...
            $('#available-authors').append('<option value="XXX">ERROR - No use esto</option>');
        }
    });
}
/* Termina función que carga en el select de géneros a todos los autores */

/* Comienza función genérica que resetea los options de géneros o autores */
function resetUsed(tipo)
{
    // Si el tipo es 1 es géneros
    // Si no, es autores
}
/* Termina función genérica que resetea los options de géneros o autores */