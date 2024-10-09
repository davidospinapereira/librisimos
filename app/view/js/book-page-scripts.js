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
        // Botón de borrar
        botonBorrar(user_id, book_id);
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
                if (respuesta.url_caratula_libro == '' || respuesta.url_caratula_libro == 'undefined' || respuesta.url_caratula_libro == null)
                {
                    respuesta.url_caratula_libro = 'view/uploads/books/generic-book-cover.jpg';
                }
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

/* Comienza función que agrega el botón de dejar de leer sólo a quienes lo ha leído */
function botonBorrar(user_id, book_id)
{
    // Tenemos que llamar a un AJAX
    // Aquí tenemos que invocar AJAX para la tabla de secciones
    $.ajax
    ({
        type: 'POST',
        url: './controller/book-crud.php',
        data:
        {
            is_user_reading: true,
            user_id,
            book_id
        },
        async: true,
        success: function(data)
        {
            $('#funciones').html(data);
        },
        error: function(error)
        {
            // Si hay un error, generemos un mensaje
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
        }
    });
}
/* Termina función que agrega el botón de dejar de leer sólo a quienes lo ha leído */

/* Comienza función para "dejar de leer" */
function dejarDeLeer(user_id, book_id)
{
    // Preparamos la pregunta
    Swal.fire(
        {
            title: 'Dejar de leer este libro',
            html: '<p style="color: black;"><b>ESTA ACCIÓN ES IRREVERSIBLE</b><br/>¿Está seguro?</p>',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Sí",
            denyButtonText: "No"
        }).then((result) => 
        {
            // Si dice que sí
            if (result.isConfirmed) 
            {
                // Llamamos un AJAX
                $.ajax
                ({
                    type: 'POST',
                    url: './controller/book-crud.php',
                    data:
                    {
                        quit_reading: true,
                        user_id,
                        book_id
                    },
                    async: true,
                    success: function(data)
                    {
                        // Tenemos que generarlo por JSON para tener un código
                        if (data = 'SUCCESS')
                        {
                            // Si el código es de éxito, que recargue la tabla
                            continuarLeyendo();
                            // Y que recargue el botón
                            botonBorrar(user_id, book_id);
                        }
                        else
                        {
                            // Si no, que muestre el error
                            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + data + '<br/>Por favor contacte al desarrollador');
                        }
                    },
                    error: function(error)
                    {
                        // Si hay un error, generemos un mensaje
                        mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
                    }
                });
            }
        });
}
/* Termina función para "dejar de leer" */

/* Comienza función para editar libro, exclusiva para administradores */
function editarLibro(book_id)
{
    window.location.href='index.php?page=edit-book&book-id=' + book_id;
}
/* Termina función para editar libro, exclusiva para administradores */

/* Comienza función para borrar libro, exclusiva para administradores */
function borrarLibro(book_id)
{
    alert('Función de borrar libro activada, libro: ' + book_id);
}
/* Termina función para borrar libro, exclusiva para administradores */