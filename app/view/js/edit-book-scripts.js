/* Comienza definición de variables */
var book_id = $('#book-id').val();
var user_id = $('#user-id').val();
/* Termina definición de variables */

/* Comienzan funciones automáticas */
$(document).ready(function()
{
    // De profile-edit-scripts, tienes que cargar la imagen en su sitio
    loadBookData(book_id);
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
    loadGenres();
    loadAuthors();
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
            updateRemoveButtons();
            // Actualizar la funcionalidad de cambio de nombre en la escritura
            updateTitleInputs();
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
    setTimeout(function()
    {
        resetUsed(1);
    }, 500);
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
    setTimeout(function()
    {
        resetUsed(2);
    }, 500);
}
/* Termina función que carga en el select de géneros a todos los autores */

/* Comienza función genérica que resetea y deshabilita los options usados de géneros o autores */
function resetUsed(tipo)
{
    // Variables para generalización
    var disponibles = '';
    var listado = '';
    // Si el tipo es 1 es géneros
    if (tipo == 1)
    {
        disponibles = 'available-genres';
        listado = 'genre-list';
    }
    // Si no, es autores    
    else
    {
        disponibles = 'available-authors';
        listado = 'author-list';
    }
    // Habilitar todas las opciones
    $('#' + disponibles + ' option').prop('disabled', false);

    // Recorrer los span dentro del div con id establecido en listado
    $('#' + listado + ' span').each(function () 
    {
        // Obtener el ID del span (ej. genero-1 => 1)
        var id = $(this).attr('id').split('-')[1];        
        // Deshabilitar la opción en el select con el valor correspondiente
        $('#' + disponibles + ' option[value="' + id + '"]').prop('disabled', true);
    });
}
/* Termina función genérica que resetea y deshabilita los options usados de géneros o autores */

/* Comienza invocación de funciones para agregar géneros y autores */
$('#add-genre').on('click', function()
{
    agregarElementoLista(1, $('#available-genres'));
});

$('#add-author').on('click', function()
{
    agregarElementoLista(2, $('#available-authors'));
});
/* Termina invocación de funciones para agregar géneros y autores */

/* Comienza función genérica para agregar las opciones del select a los listados cuando se de clic en el botón de añadir */
function agregarElementoLista(tipo, item)
{
    var html = '';
    var valElemento = item.find(":selected").val();
    var nombreElemento = item.find(":selected").text();
    var tipoElemento = 'autor-';
    var colorElemento = '';
    var colorHTML = '';
    var objetivo = '#author-list';
    // Si el tipo es 1, lo agrega en géneros y toma en cuenta el color
    if (tipo == 1)
    {
        colorElemento = item.find(":selected").data('color');
        colorHTML = 'style="background-color: #' + colorElemento + ';" ';
        tipoElemento = 'genero-';
        objetivo = '#genre-list';
    }
    if (valElemento != 'X')
    {
        // Si el valor seleccionado no es "X" entonces actuamos
        html = '<span ' + colorHTML + 'id = "' + tipoElemento + valElemento + '">' + nombreElemento + ' <i class="bx bx-x icon-close" onclick="quitarBaldosa(' + tipo + ', ' + valElemento + ')"></i></span>';
        $(objetivo).append(html);
        resetUsed(tipo);
        if (tipo == 1)
        {
            $('#available-genres').val('X');
        }
        else
        {
            $('#available-authors').val('X');
        }
    }
}
/* Termina función genérica para agregar las opciones del select a los listados cuando se de clic en el botón de añadir */

/* Comienza función para agregar secciones */
function agregarSeccion() 
{
    // Obtener el número de la nueva sección basado en el total de secciones actuales
    var totalSections = $('#section-list .accordion-button').length + 1;
    // Crear el HTML de la nueva sección
    var newSection = `
    <button class="accordion-button" data-number="${totalSections}" onclick="toggleSection(${totalSections});">Sección ${totalSections}: </button>
    <div class="accordion-section" id="section-${totalSections}">
        <div class="section-title-functions">
            <input type="text" value="" placeholder="Escribe el título..." class="section-title-input">
            <button class="btn remove-section" onclick="eliminarSeccion('new');">Eliminar sección</button>
        </div>
        <textarea class='section-content' placeholder='Comienza a escribir...'></textarea>
    </div>`;
    // Insertar la nueva sección al inicio del contenedor de secciones
    $('#section-list').prepend(newSection);

    // Instanciar el editor TinyMCE en el nuevo textarea
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
    // Actualizar la funcionalidad de los botones "Eliminar sección"
    updateRemoveButtons();
    // Actualizar la funcionalidad de cambio de nombre en la escritura
    updateTitleInputs();
}
/* Termina función para agregar secciones */

/* Comienza función para eliminar una sección, con el apoyo de ChatGPT, hay que corregirla */
function updateRemoveButtons() 
{
    $('.remove-section').off('click').on('click', function () 
    {
        $(this).closest('.accordion-section').prev('.accordion-button').remove(); // Eliminar el botón correspondiente
        $(this).closest('.accordion-section').remove(); // Eliminar la sección correspondiente
        // Actualizar los números de las secciones
        updateSectionNumbers();
    });
}
/* Termina función para eliminar una sección, con el apoyo de ChatGPT, hay que corregirla */

/* Comienza función para actualizar los números de las secciones */
function updateSectionNumbers() 
{
    $('#section-list .accordion-button').each(function (index) 
    {
        var newNumber = $('#section-list .accordion-button').length - index;
        $(this).attr('data-number', newNumber);
        var titleText = $(this).text().split(":")[1]; // Mantener el título si ya tiene uno
        $(this).text(`Sección ${newNumber}: ${titleText || ''}`);
    });
    updateRemoveButtons();
}
/* Termina función para actualizar los números de las secciones */

/* Comienza función para actualizar el título en el botón al escribir en el input de título */
function updateTitleInputs() 
{
    $('.section-title-input').off('input').on('input', function () 
    {
        var title = $(this).val(); // Obtener el valor del input
        var sectionNumber = $(this).closest('.accordion-section').prev('.accordion-button').attr('data-number'); // Obtener el número de la sección
        $(this).closest('.accordion-section').prev('.accordion-button').text(`Sección ${sectionNumber}: ${title}`);
    });
}
/* Termina función para actualizar el título en el botón al escribir en el input de título */