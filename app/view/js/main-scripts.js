/* Comienza asignación de variables */
var statistics = document.getElementById("statistics");
var user_id = $('#user_id').val();
/* Termina asignación de variables */

/* Comienzan funciones que cargan cuando la página esté lista */
$(document).ready(function()
{
    $('.read-overlay').hide();
    if (statistics)
    {
        // Si existe la sección de statistics es porque el usuario es suscriptor
        cargarEstadisticas();
    }
    continuarLeyendo();
});
/* Terminan funciones que cargan cuando la página esté lista */

/* Comienza función para cerrar la herramienta de lectura */
$('#close-read-tool').on('click', function()
{
    $('.read-overlay').removeClass('active');
    $('.read-overlay').fadeOut("slow");
    $('.read-space').fadeOut("slow");
    $('.read-space').removeClass('dark');
    $('.toggle').removeClass('pushed');
});
/* Termina función para cerrar la herramienta de lectura */

/* Comienza función para abrir la herramienta de lectura */
function activarHerramienta()
{
    // Aquí tengo que jalar los datos de la sección deseada por AJAX. Por lo tanto, esta función tendrá parámetros
    $('.read-overlay').addClass('active');
    $('.read-overlay').fadeIn("slow");
    $('.read-space').fadeIn("slow");
    $('.read-space').removeClass('dark');
    $('.toggle').removeClass('pushed');
}
/* Termina función para abrir la herramienta de lectura */

/* Comienza función para activar el modo oscuro */
$('#dark-toggle').on('click', function()
{
    $('.read-space').toggleClass('dark');
    $('.toggle').toggleClass('pushed');
});
/* Termina función para activar el modo oscuro */

/* Comienzan funciones para las estadísticas en main para administradores */
function cargarEstadisticas()
{
    /* <h3>Usuarios registrados: <b>25</b></h3>
    <h3>Libros publicados: <b>22</b></h3>
    <h3>Libros sin publicar: <b>5</b></h3>
    <h3>Libros leídos: <b>12</b></h3> */
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

/* Comienza función de continuar leyendo */
function continuarLeyendo()
{
    var readingTable = $('#continue-reading-table');
    /* <tr>
            <td data-cell="titulo">Cosmos</td>
            <td data-cell="autor">Carl Sagan</td>
            <td data-cell="genero">Divulgación Científica</td>
            <td data-cell="seccion">13 - ¿Quién hablará en nombre de la Tierra?</td>
            <td data-cell="acciones" class="acciones">
                <span class="button continue" onclick="activarHerramienta()"><div class="tooltip">Continuar leyendo</div><i class='bx bx-book-reader'></i></span>
                <span class="button quit"><div class="tooltip">Dejar de leer</div><i class='bx bx-x' ></i></span>
            </td>
        </tr> */
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
        beforeSend: function()
        {
            // Spinner?
        },
        success: function(data)
        {
            // Mandamos el dato al HTML
            $('#continue-reading-table').append(data);
        },
        error: function(error)
        {  
           // Mandamos el error al HTML
           $('#continue-reading-table').append(error);
        }
    });
}
/* Termina función de continuar leyendo */