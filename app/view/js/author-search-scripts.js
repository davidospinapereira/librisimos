/* Comienza la declaración de variables */
var loading = $('#loading');
var filter_search = $('#filter-input');
var content = $('#content');
/* Termina la declaración de variables */

/* Comienza carga de elementos al cargar la página */
$(document).ready(function()
{
    // Colocamos el nombre de la página desde aquí:
    $(document).prop('title', 'Buscador de autores - Librísimos');
    // Primero, activamos el área de loading
    loading.addClass('active');
    // Luego, hacemos un timeout de medio segundo para que desaparezca el spinner y aparezca el loading
    setTimeout(async function()
    {
        // Luego, generamos el grid de Autores
        await gridAutores(filter_search);
        loading.removeClass('active');
    }, 500);
    /* Comienza carga de buscador AJAX */
    var i = 0;
    filter_search.on('keyup', function()
    {
        i++;
        if($(this).val().length > 2)
        {
            content.html('<!-- Comienza segmento de spinner --><div class="col w100 active" id="loading"><div class="spinner" id="cards-spinner"><span class="loader"></span></div></div><!-- Termina segmento de spinner -->');
            setTimeout(async function()
            {            
                // Luego, generamos el grid de libros
                await gridAutores(filter_search);
                loading.removeClass('active');
            }, 500);
        }
        // Control por si el usuario borra todo el texto de búsqueda seleccionando todo y presionando borrar
        if ($(this).val().length < i)
        {
            content.html('<!-- Comienza segmento de spinner --><div class="col w100 active" id="loading"><div class="spinner" id="cards-spinner"><span class="loader"></span></div></div><!-- Termina segmento de spinner -->');
            setTimeout(async function()
            {            
                // Luego, generamos el grid de libros
                await gridAutores(filter_search);
                loading.removeClass('active');
            }, 500);
        }
    });

    // Esta variación se da para cuando se oprima la "X" en el input
    filter_search.on('search', function(evt)
    {
        content.html('<!-- Comienza segmento de spinner --><div class="col w100 active" id="loading"><div class="spinner" id="cards-spinner"><span class="loader"></span></div></div><!-- Termina segmento de spinner -->');
        setTimeout(async function()
        {            
            // Luego, generamos el grid de libros
            await gridAutores(filter_search);
            loading.removeClass('active');
        }, 500);
    });
    /* Termina carga de buscador AJAX */
});
/* Termina carga de elementos al cargar la página */

/* Comienza función que muestra el grid de autores */
function gridAutores(input)
{
    var entrada = input.val();
    if (input.val() == null)
    {
        console.log("input nulo");
        entrada = "";
    }
    // Quiero hacerla multifuncional, por lo que tenemos que llamar a los términos de búsqueda desde ahora
    $.ajax
    ({
        type: 'POST',
        url: './controller/author-crud.php',
        data:
        {
            author_grid: true,
            input: entrada
        },
        async: true,
        success: function(data)
        {
            console.log(data);
            var contenido = '<!-- Comienza segmento de spinner --><div class="col w100" id="loading"><div class="spinner" id="cards-spinner"><span class="loader"></span></div></div><!-- Termina segmento de spinner -->' + data;
            // Si los datos llegan correctamente, añadimos al select lo que nos envíe el sistema
            content.html(contenido);
        },
        error: function(error)
        {
            // Si hay un error, generemos un mensaje
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
            // Y luego añadimos un pequeño huevo de pascua...
            content.html('<div class="col w100" id="nothing-found"><h2>¡ERROR! Hay un error en el programa</h2><h4>'+ error +'</h4><h4>Por favor consulte al administrador.</h4></div>');
        }
    });
}
/* Termina función que muestra el grid de autores */