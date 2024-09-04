/* Comienza la declaración de variables */
var book_id = $('#book-id').val();
var user_id = $('#user-id').val();
var loading = $('#loading');
var cards_loading = $('#cards-loading');
/* Termina la declaración de variables */

$(document).ready(function()
{
    // Primero, activamos las dos áreas de loading
    loading.addClass('active');
    cards_loading.addClass('active');
    setTimeout(function()
    {
        // Llenar datos de la ficha
        llenarDatos(book_id);
        // Llenar datos de la tabla
        llenarTabla(user_id, book_id);
        // Llenar tarjetas
        llenarTarjetas(book_id);
        loading.removeClass('active');
        cards_loading.removeClass('active');
    }, 500);
});

function llenarDatos(book_id)
{
    // Invocamos AJAX para jalar los datos de la ficha del libro
    $.ajax
    ({
        type: 'POST',
        url: './controller/book-crud.php',
        data:
        {
            get_book_data: true,
            book_id
        },
        async: true,
        success: function(data)
        {
            // Me mandará datos en JSON, debo decodificarlos
            var respuesta = jQuery.parseJSON(data);
            // Y luego de decodificados, debo ponerlos en su sitio
            if (respuesta.codigo == 'SUCCESS')
            {
                // Si el código es éxito, sé que vendrá con todos los demás datos
                $('#image').html('<img src="./' + respuesta.url_caratula_libro + '" alt="Carátula del libro" class="book-cover" id="book-cover">');
                $('#title').html(respuesta.nombre_libro);
                $('#genres').html(respuesta.generos_html);
                $('#sinopsis').html('<h4>Sinopsis:</h4><p>' + respuesta.sinopsis_libro + '</p>');
            }
            else
            {
                // Si el código no es de éxito es de error, sé que vendrá con sólo un campo
                if (respuesta.error == 'NO_DATA_GENERATED')
                {
                    mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>No se generaron datos.<br/>Por favor contacte al desarrollador');
                }
                else if (respuesta.error == 'NULL_QUERY')
                {
                    mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>La consulta SQL es nula.<br/>Por favor contacte al desarrollador');
                }
                else
                {
                    mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + respuesta.error + '<br/>Por favor contacte al desarrollador');
                }
            }
        },
        error: function(error)
        {
            // Si hay un error, generemos un mensaje
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
        }
    });
    // Faltan los autores, usamos otro AJAX
    $.ajax
    ({
        type: 'POST',
        url: './controller/book-crud.php',
        data:
        {
            get_author_data: true,
            book_id
        },
        async: true,
        success: function(data)
        {
            $('#author').html(data);
        },
        error: function(error)
        {
            // Si hay un error, generemos un mensaje
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
        }
    });
}

function llenarTabla(user_id, book_id)
{
    // Aquí tenemos que invocar AJAX para la tabla de secciones
    $.ajax
    ({
        type: 'POST',
        url: './controller/book-crud.php',
        data:
        {
            get_book_list: true,
            user_id,
            book_id
        },
        async: true,
        success: function(data)
        {
            var loader_section = "<div class='col w100' id='loading'><div class='spinner' id='cards-spinner'><span class='loader'></span></div></div>"
            $('#listado-secciones').html(loader_section + data);
        },
        error: function(error)
        {
            // Si hay un error, generemos un mensaje
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
        }
    });
}

function llenarTarjetas(book_id)
{
    // Aquí tenemos que invocar AJAX para la sección de libros similares
    $.ajax
    ({
        type: 'POST',
        url: './controller/book-crud.php',
        data:
        {
            get_similar_books: true,
            book_id
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

/* Comienza función de continuar leyendo, traducida a la página de libro específico */
function continuarLeyendo()
{
    // Aquí tenemos que invocar AJAX para la tabla de secciones
    $.ajax
    ({
        type: 'POST',
        url: './controller/book-crud.php',
        data:
        {
            get_book_list: true,
            user_id,
            book_id
        },
        async: true,
        success: function(data)
        {
            var loader_section = "<div class='col w100' id='loading'><div class='spinner' id='cards-spinner'><span class='loader'></span></div></div>"
            $('#listado-secciones').html(loader_section + data);
        },
        error: function(error)
        {
            // Si hay un error, generemos un mensaje
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
        }
    });
}
/* Termina función de continuar leyendo, traducida a la página de libro específico */