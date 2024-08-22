/* Comienza asignación de variables */
var statistics = document.getElementById("statistics");
var side_cards = document.getElementById("side-cards");
var user_id = $('#user_id').val();
/* Termina asignación de variables */

/* Comienzan funciones que cargan cuando la página esté lista */
$(document).ready(function()
{
    $('.read-overlay').hide();
    if (statistics)
    {
        // Si existe la sección de statistics es porque el usuario es administrador
        cargarEstadisticas();
    }
    if(side_cards)
    {
        // Si existe la sección de side-cards es porque el usuario es suscriptor
        cargarTarjetas(user_id);
    }
    continuarLeyendo();
});
/* Terminan funciones que cargan cuando la página esté lista */

/* Comienzan funciones para las estadísticas en main para administradores */
function cargarEstadisticas()
{
    $.ajax
    ({
        type: 'POST',
        url: './controller/stats-getter.php',
        async: true,
        data: 
        {
            get_stats: true,
        },
        beforeSend: function()
        {
            $('#stats-spinner').addClass('active');
            $('#stats-data').removeClass('active');
        },
        success: function(data)
        {
            // Tenemos que decodificar el JSON resultante
            var results = JSON.parse(data);
            // Tenemos que poner los datos en una cadena de caracteres
            var texto = 
            '<h3>Usuarios registrados: <b>' + results.usuarios + '</b></h3><h3>Libros publicados: <b>' + results.publicados + '</b></h3><h3>Libros sin publicar: <b>' + results.sin_publicar + '</b></h3>';
            // Tenemos que poner esa cadena de caracteres en su lugar, borrando todo lo que hubiere previamente
            $('#stats-data').html('');
            $('#stats-data').html(texto);
        },
        error: function(error)
        {
            // Generamos un mensaje de error
            mensaje('error', '<b>ERROR</b><br/>Hubo un error en el programa:<br/>' + error + '<br/>Por favor comuníquese con el administrador o el desarrollador.');
        },
        complete: function()
        {
            $('#stats-spinner').removeClass('active');
            $('#stats-data').addClass('active');
        }
    });
    // Luego, los ponemos en el div de estadísticas
}
/* Terminan funciones para las estadísticas en main para administradores */

/* Comienzan funciones para las tarjetas en main para suscriptores */
function cargarTarjetas(user_id)
{
    $.ajax
    ({
        type: 'POST',
        url: './controller/cards-getter.php',
        async: true,
        data: 
        {
            get_cards: true,
            user_id
        },
        beforeSend: function()
        {
            $('#cards-spinner').addClass('active');
            $('#cards-data').removeClass('active');
        },
        success: function(data)
        {
            // El código completo será recibido como HTML en una variable string
            $('#cards-data').html('');
            $('#cards-data').html(data);
        },
        error: function(error)
        {
            // Generamos un mensaje de error
            mensaje('error', '<b>ERROR</b><br/>Hubo un error en el programa:<br/>' + error + '<br/>Por favor comuníquese con el administrador o el desarrollador.');
        },
        complete: function()
        {
            $('#cards-spinner').removeClass('active');
            $('#cards-data').addClass('active');
        }
    });
}
/* Terminan funciones para las tarjetas en main para suscriptores */

/* Comienza función de continuar leyendo */
function continuarLeyendo()
{
    $.ajax
    ({
        type: 'POST',
        url: './controller/book-crud.php',
        async: true,
        data: 
        {
            get_list: true,
            user_id
        },
        // Tal vez tengamos que usar un beforesend, cuando la tabla tenga muchos libros
        beforeSend: function()
        {
            $('#table-overlay').addClass('active');
        },
        success: function(data)
        {
            // Mandamos el dato al HTML
            $('#table-overlay').removeClass('active');
            $('#continue-reading-table').html('');
            $('#continue-reading-table').html(data);
        },
        error: function(error)
        {  
           // Mandamos el error al HTML
           $('#continue-reading-table').append(error);
        }
    });
}
/* Termina función de continuar leyendo */

/* Comienza función de dejar de leer */
function dejarDeLeer(book_id, user_id)
{
    // Debemos preguntar si se está seguro
    Swal.fire(
        {
            title: 'DEJAR DE LEER',
            html: '<b>ESTA ACCIÓN ES IRREVERSIBLE</b><br/>¿Está seguro?',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Sí",
            denyButtonText: "No"
        }).then((result) => 
        {
            if (result.isConfirmed) 
            {
                // Si la respuesta es afirmativa
                // Debe invocarse AJAX para eliminar
                $.ajax
                ({
                    type: 'POST',
                    url: './controller/book-crud.php',
                    async: true,
                    data: 
                    {
                        delete_read: true,
                        book_id,
                        user_id
                    },
                    // Tal vez tengamos que usar un beforesend, cuando la tabla tenga muchos libros
                    beforeSend: function()
                    {
                        $('#table-overlay').addClass('active');
                    },
                    success: function(data)
                    {
                        // Debemos tener un control
                        if (data != 'SUCCESS')
                        {
                            mensaje('error', '<b>ERROR: </b><br/>Hubo un error en el programa:<br/>' + data + '<br/>Por favor comuníquese con el desarrollador o con el administrador');
                        }
                        // Luego debe recargarse la tabla
                        continuarLeyendo();
                    },
                    error: function(error)
                    {  
                    // Mandamos el error al HTML
                    mensaje('error', '<b>ERROR: </b><br/>Hubo un error en el programa:<br/>' + error + '<br/>Por favor comuníquese con el desarrollador o con el administrador');
                    }
                });
            }
            // Si la respuesta es negativa, no haga nada
        });
}
/* Termina función de dejar de leer */

