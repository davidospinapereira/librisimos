/* Comienza la declaración de variables */
var loading = $('#loading');
var genero = $('#genero');
var filter_search = $('#filter-input');
var content = $('#content');
/* Termina la declaración de variables */

/* Comienza carga de elementos al cargar la página */
$(document).ready(function()
{
    // Colocamos el nombre de la página desde aquí:
    $(document).prop('title', 'Buscador de libros - Librísimos');
    // Primero, llenamos el select con todos los géneros que existen
    llenarSelect();
    // Segundo, activamos el área de loading
    loading.addClass('active');
    // Luego, hacemos un timeout de medio segundo para que desaparezca el spinner y aparezca el loading
    setTimeout(function()
    {
        // Luego, generamos el grid de libros
        gridLibros(genero, filter_search);
        loading.removeClass('active');
    }, 500);
    /* Comienza carga de buscador AJAX */
    genero.on("change", function()
    {
        content.html('<!-- Comienza segmento de spinner --><div class="col w100 active" id="loading"><div class="spinner" id="cards-spinner"><span class="loader"></span></div></div><!-- Termina segmento de spinner -->');
        setTimeout(function()
        {            
            // Luego, generamos el grid de libros
            gridLibros(genero, filter_search);
            loading.removeClass('active');
        }, 500);
    });

    var i = 0;
    filter_search.on('keyup', function()
    {
        i++;
        if($(this).val().length > 2)
        {
            content.html('<!-- Comienza segmento de spinner --><div class="col w100 active" id="loading"><div class="spinner" id="cards-spinner"><span class="loader"></span></div></div><!-- Termina segmento de spinner -->');
            setTimeout(function()
            {            
                // Luego, generamos el grid de libros
                gridLibros(genero, filter_search);
                loading.removeClass('active');
            }, 500);
        }
        // Control por si el usuario borra todo el texto de búsqueda seleccionando todo y presionando borrar
        if ($(this).val().length < i)
        {
            content.html('<!-- Comienza segmento de spinner --><div class="col w100 active" id="loading"><div class="spinner" id="cards-spinner"><span class="loader"></span></div></div><!-- Termina segmento de spinner -->');
            setTimeout(function()
            {            
                // Luego, generamos el grid de libros
                gridLibros(genero, filter_search);
                loading.removeClass('active');
            }, 500);
        }
    });

    // Esta variación se da para cuando se oprima la "X" en el input
    filter_search.on('search', function(evt)
    {
        content.html('<!-- Comienza segmento de spinner --><div class="col w100 active" id="loading"><div class="spinner" id="cards-spinner"><span class="loader"></span></div></div><!-- Termina segmento de spinner -->');
        setTimeout(function()
        {            
            // Luego, generamos el grid de libros
            gridLibros(genero, filter_search);
            loading.removeClass('active');
        }, 500);
    });
    /* Termina carga de buscador AJAX */
});
/* Termina carga de elementos al cargar la página */



function llenarSelect()
{
    $.ajax
    ({
        type: 'POST',
        url: './controller/genre-crud.php',
        data:
        {
            get_genres: true
        },
        async: true,
        success: function(data)
        {
            // Si los datos llegan correctamente, añadimos al select lo que nos envíe el sistema
            genero.append(data);
        },
        error: function(error)
        {
            // Si hay un error, generemos un mensaje
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
            // Y luego añadimos un pequeño huevo de pascua...
            genero.append('<option value="XXX">ERROR - No use esto</option>');
        }
    });
}

function gridLibros(genero, input)
{
    var entrada = input.val();
    // Esto es muy parecido a llenarSelect
    if (input.val() == null)
    {
        console.log("input nulo");
        entrada = "";
    }
    // Quiero hacerla multifuncional, por lo que tenemos que llamar a los términos de búsqueda desde ahora
    $.ajax
    ({
        type: 'POST',
        url: './controller/book-crud.php',
        data:
        {
            book_grid: true,
            genero: genero.find(":selected").val(),
            input: entrada
        },
        async: true,
        success: function(data)
        {
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