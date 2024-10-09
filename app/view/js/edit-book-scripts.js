/* Comienza definición de variables */
var book_id = $('#book-id').val();
var user_id = $('#user-id').val();
var picsetter = $('#image');
var book_cover_url = 'view/uploads/books/generic-book-cover.jpg';
var book_status = 0;
bookStatus(book_id, function(status) 
{
    book_status = status;
    // Aquí puedes manejar lo que quieras hacer con el valor "status"
});
/* Termina definición de variables */

/* Comienza función que retorna el status del libro para poder operar con él */
function bookStatus(book_id, callback)
{
    $.ajax
    ({
        type: 'POST',
        url: './controller/book-crud.php',
        data: 
        {
            get_book_status: true,
            book_id
        },
        async: true, // Mantén esto como true, ya que las solicitudes AJAX son asíncronas
        success: function(data) 
        {
            callback(Number(data)); // Llama al callback con el resultado
        },
        error: function(error) 
        {
            // Si hay un error, generamos un mensaje
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
            callback(0); // Devuelve 0 en caso de error
        }
    });
}
/* Termina función que retorna el status del libro para poder operar con él */

/* Comienzan funciones automáticas */
$(document).ready(function()
{
    // Primero, la información del libro
    loadBookData(book_id, book_status);
    // Luego, los botones principales
    loadMainButtons(book_id, book_status);
    // Luego, ponemos en activo el spinner
    $('#sections-spinner').addClass('active');
    // Luego, jalamos las secciones existentes y las demoramos medio segundo como mínimo
    setTimeout(function()
    {
        loadSections(book_id, book_status);
    }, 500);    
});
/* Terminan funciones automáticas */

/* Comienzan efectos visuales */
/* Comienza código de vista previa provisto por ChatGPT */
jQuery('#file-selector').change(function(e) 
{
    var reader = new FileReader();
    reader.onload = function(event) 
    {
        picsetter.css('background-image', 'url(' + event.target.result + ')');
    }
    reader.readAsDataURL(e.target.files[0]);
});
/* Termina código de vista previa provisto por ChatGPT */

/* Comienzan efectos para el acordeón */
function toggleSection(section)
{
    var section = $('#section-' + section);
    section.toggleClass('active');
}
/* Terminan efectos para el acordeón */

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

/* Comienza función que permite que se active el botón de actualizar cuando se cambie el título */
function updateTitleInputs() 
{
    $('.section-title-input').off('input').on('input', function () 
    {
        var title = $(this).val(); // Obtener el valor del input
        var sectionNumber = $(this).closest('.accordion-section').prev('.accordion-button').attr('data-number'); // Obtener el número de la sección
        $(this).closest('.accordion-section').prev('.accordion-button').text(`Sección ${sectionNumber}: ${title}`);

        // Habilitar el botón de "Actualizar" cuando se modifique el título
        $(this).closest('.accordion-section').find('.update-section').prop('disabled', false);
        $(this).closest('.accordion-section').find('.save-section').prop('disabled', false);
    });
}
/* Termina función que permite que se active el botón de actualizar cuando se cambie el título */

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
/* Terminan efectos visuales */

/* Comienzan funciones de llenado de datos */
/* Comienza función que carga los datos base del libro */
function loadBookData(book_id, book_status)
{
    var book_status_span = '';
    // Si el status es 1 es publicado
    if (book_status == 1)
    {
        book_status_span = '<span class="status-tile published">PUBLICADO</span>';
    }
    // Si el status es 2 es borrador
    else if (book_status == 2)
    {
        book_status_span = '<span class="status-tile draft">BORRADOR</span>';
    }
    // Si el status es cualquier otra cosa es un error
    else
    {
        book_status_span = '<span class="status-tile error">ERROR</span>';
    }
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
            // Nombre del libro
            $('#book-name').val(results.nombre_libro);
            // Carátula del libro, con la opción de qué pasa si no tiene carátula registrada
            if (results.url_caratula_libro == null || results.url_caratula_libro == '')
            {
                $('#image').css('background-image', 'url("./view/uploads/books/generic-book-cover.jpg")');
            }
            else
            {
                $('#image').css('background-image', 'url("./' + results.url_caratula_libro + '")');
                book_cover_url = results.url_caratula_libro;
            }
            // ID del libro
            $('#id-book').html('ID de libro: ' + book_id);
            // Status del libro
            $('#book-status').html(book_status_span);
            // Géneros del libro
            $('#genre-list').html(results.generos_html);
            // Autores del libro
            $('#author-list').html(results.autores_html);
            // Sinopsis del libro
            $('#description').html(results.sinopsis_libro);
            // Aplicando TinyMCE a la sinopsis del libro
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
        },
        error: function(error)
        {
            // Si hay un error, generemos un mensaje
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
        }
    });
    // Cargamos los selects con los géneros
    loadGenres();
    // Cargamos los selects con los autores
    loadAuthors();
}
/* Termina función que carga los datos base del libro */

/* Comienza función que carga los botones principales */
function loadMainButtons(book_id, book_status)
{
    var publish_update_btn = '';
    // Si el status es 1 está publicado
    if (book_status == 1)
    {
        publish_update_btn = '<button class="btn" id="update-book">Actualizar libro</button>';
    }
    // Si el status es 2 está en borrador
    else if (book_status == 2)
    {
        publish_update_btn = '<button class="btn save-draft" id="save-draft">Guardar Borrador</button><button class="btn publish-book" id="publish-book">PUBLICAR</button>';
    }
    // De cualquier otra manera hay un error, no debe poner nada más
    var all_buttons = publish_update_btn + '<button class="btn" id="cancel-edit">Cancelar edición</button><button class="btn" id="delete-book">Borrar libro</button>';
    $('#functions').html(all_buttons);
    // Agregamos funciones para cada botón. Debe ser aquí dentro para que se inscriban correctamente
    $('#update-book').on('click', function()
    {
        actualizarLibro(book_id);
    })
    $('#save-draft').on('click', function()
    {
        actualizarLibro(book_id);
    })
    $('#publish-book').on('click', function()
    {
        publicarLibro(book_id);
    })
    $('#cancel-edit').on('click', function()
    {
        mensaje('success', '<b>EXITO</b><br/>ID del libro: ' + book_id + '<br/>Status del libro: ' + book_status + '<br/>Botón presionado: CANCELAR');
    })
    $('#delete-book').on('click', function()
    {
        mensaje('success', '<b>EXITO</b><br/>ID del libro: ' + book_id + '<br/>Status del libro: ' + book_status + '<br/>Botón presionado: BORRAR');
    })
}
/* Termina función que carga los botones principales */

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

/* Comienza función que carga las secciones existentes en su respectivo espacio */
function loadSections(book_id, book_status)
{
    // Aquí vamos, recibimos un HTML que insertamos
    $.ajax
    ({
        type: 'POST',
        url: './controller/section-crud.php',
        data:
        {
            obtener_secciones_edit: true,
            book_id,
            book_status
        },
        async: true,
        success: function(data)
        {
            $('#book-sections-data').html('');
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
                license_key: 'gpl',
                setup: function (editor) 
                {
                    // Evento que se activa cuando el contenido cambia (cuando el usuario escribe)
                    editor.on('keyup', function () 
                    {
                        // Habilitar el botón de "Actualizar" cuando se modifique el contenido
                        $(editor.getElement()).closest('.accordion-section').find('.update-section').prop('disabled', false);
                        // Cuando se haga un cambio en el contenido debe desactivarse el botón de añadir secciones
                        $('#add-section-btn').prop('disabled', true);
                    });
                    // Puedes agregar el evento 'change' por si se hace algún cambio mayor, aunque 'keyup' es suficiente
                    editor.on('change', function () 
                    {
                        $(editor.getElement()).closest('.accordion-section').find('.update-section').prop('disabled', false);
                        $('#add-section-btn').prop('disabled', true);
                    });
                }
            });
            // Obtenemos los datos de cada botón de actualizar y les aplicamos la función de actualizar...
            // Actualizar la funcionalidad de cambio de nombre en la escritura
            updateTitleInputs();
            // La función de remover secciones guardadas en la base de datos debe existir aquí porque puede ser un libro no publicado
            updateRemoveButtons();
            // Debemos actualizar después de esto la funcionalidad de actualizar secciones existentes.
            updateUpdateSections();
        },
        error: function(error)
        {
            // Si hay un error, generemos un mensaje
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
            // Y luego añadimos un pequeño huevo de pascua...
            $('#section-list').append('<h4><b>ERROR</b></h4><p>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador</p>');
        }
    });
}
/* Termina función que carga las secciones existentes en su respectivo espacio */

/* Comienza función para agregar secciones */
function agregarSeccion() 
{
    // Primero que nada, tenemos que añadir el editor de sección
    // Obtener el número de la nueva sección basado en el total de secciones actuales
    var totalSections = $('#section-list .accordion-button').length + 1;
    // Crear el HTML de la nueva sección
    var newSection = `
    <button class="accordion-button" data-number="${totalSections}" onclick="toggleSection(${totalSections});">Sección ${totalSections}: </button>
    <div class="accordion-section active" id="section-${totalSections}">
        <div class="section-title-functions">
            <input type="text" value="" placeholder="Escribe el título..." class="section-title-input" id="section-${totalSections}-title">
            <div class="section-title-buttons">
                <button class="btn save-section" id="section-${totalSections}-save" disabled>Guardar</button>
                <button class="btn remove-section">Eliminar</button>
            </div>
        </div>
        <textarea class='section-content' placeholder='Comienza a escribir...' id="section-${totalSections}-content"></textarea>
    </div>`;
    // Insertar la nueva sección al inicio del contenedor de secciones
    $('#section-list').prepend(newSection);
    // Instanciar el editor TinyMCE en el nuevo textarea
    tinymce.init
    ({
        selector: `.section-content`,
        language: 'es',
        branding: false,
        menubar: false,
        resize: false,
        height: 350,
        plugins: 'code lists',
        toolbar: 'undo redo | styles | bold italic underline | cut copy paste | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | code',
        license_key: 'gpl',
        setup: function (editor)
        {
            // Evento que se activa cuando el contenido cambia (cuando el usuario escribe)
            editor.on('keyup', function () 
            {
                // Habilitar el botón de "Actualizar" cuando se modifique el contenido
                $(editor.getElement()).closest('.accordion-section').find('.save-section').prop('disabled', false);
            });

            // Puedes agregar el evento 'change' por si se hace algún cambio mayor, aunque 'keyup' es suficiente
            editor.on('change', function () 
            {
                $(editor.getElement()).closest('.accordion-section').find('.save-section').prop('disabled', false);
            });
        }
    });
    // Luego, tenemos que deshabilitar el botón de agregar una nueva sección
    $('#add-section-btn').prop('disabled', true);
    // Después, la función de guardar sección
    $('#section-' + totalSections + '-save').on('click', function()
    {
        var section_title = $('#section-' + totalSections + '-title').val();
        // Y si el usuario no escribió un título?
        if (section_title == '' || section_title == null)
        {
            // Pues el título será el número de la sección, esto se ve en muchos libros
            section_title = section_number;
        }
        // El contenido de la sección puede incluso ser vacío y deberá poder guardarse
        var section_content = tinymce.get('section-' + totalSections + '-content').getContent();
        saveNewSection(book_id, section_title, section_content);
    });
    // Adicionalmente, si existe un div llamado "section-error", tenemos que eliminarlo
    $('.section-error').remove();
    // Finalmente, las funciones adicionales
    // Actualizar la funcionalidad de los botones "Eliminar sección"
    updateRemoveButtons();
    // Actualizar la funcionalidad de cambio de nombre en la escritura
    updateTitleInputs();
}
/* Termina función para agregar secciones */
/* Terminan funciones de llenado de datos */

/* Comienzan funciones de guardado de datos */
/* Comienza función para publicar el libro en sus datos base */
function publicarLibro(book_id)
{
    // Los géneros seleccionados
    var generosLibro = [];
    $('#genre-list span').each(function () 
    {
        // Obtener el ID del span (ej. genero-1 => 1)
        var id = $(this).attr('id').split('-')[1];        
        // Ponemos el id en una variable de array
        generosLibro.push(id);
    });
    // Los autores seleccionados
    var autoresLibro = [];
    $('#author-list span').each(function () 
    {
        // Obtener el ID del span (ej. genero-1 => 1)
        var id = $(this).attr('id').split('-')[1];        
        // Ponemos el id en una variable de array
        autoresLibro.push(id);
    });
    if(generosLibro.length == 0 || autoresLibro.length == 0)
    {
        mensaje('error', '<b>ERROR</b><br/>No puede quedar el libro sin géneros ni sin autores.');
    }
    else
    {
        Swal.fire
        ({
            title: 'Publicar libro',
            html: '<h4 style="color: black;">¡ESTE PROCESO ES IRREVERSIBLE!<br/>El libro publicado será visible para todos los usuarios.<br/>¿Está seguro?</p>',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Sí",
            denyButtonText: "No"
        }).then(async (result) => 
        {
            if (result.isConfirmed) 
            {
                // Si se dice que sí, jalamos los datos de la página
                // El nombre del libro
                var nombreLibro = $('#book-name').val();                
                // El contenido de la sinopsis 
                var sinopsisLibro = tinymce.get("description").getContent();
                // Esperamos a que la función de rutaImagen se complete
                var url_imagen_save = await rutaImagen('#file-selector', book_cover_url);
                // Ahora realizamos la actualización del libro
                $.ajax
                ({
                    url: './controller/book-crud.php',
                    type: 'POST',
                    data:
                    {
                        publish_book: true,
                        id_libro: book_id,
                        nombre_libro: nombreLibro,
                        generos_libro: JSON.stringify(generosLibro),
                        autores_libro: JSON.stringify(autoresLibro),
                        sinopsis_libro: sinopsisLibro,
                        url_imagen_save: url_imagen_save // Usamos la ruta que retornó la función asincrónica
                    },
                    async: true,
                    success: function(response)
                    {
                        // Debe devolver un mensaje. Dependiendo del mensaje es lo que hay que hacer.
                        if (response == 'SUCCESS')
                        {
                            // Si el mensaje es de éxito, se genera un mensaje de éxito
                            mensaje('success', '<b>ÉXITO</b><br/>Libro publicado satisfactoriamente.<br/>Será redirigido a la página del libro ahora.');
                            // Y se redirige a la página de libro
                            setTimeout(function()
                            {
                                window.location.href = "index.php?page=book-page&book-id=" + book_id;
                            }, 5000);
                        }
                        else
                        {
                            // Si el mensaje es de error, se genera un mensaje y listo.
                            mensaje('error', '<b>ERROR</b><br/>Hubo un error en el programa: <br/>' + response + '.<br/>Por favor contacte al desarrollador.');
                        }
                    },
                    error: function(error)
                    {
                        // Si hay un error, debe devolverlo como mensaje de error.
                        mensaje('error', '<b>ERROR</b><br/>Hubo un error en el programa: <br/>' + error + '.<br/>Por favor contacte al desarrollador.');
                    }
                });
            }
            // Si no está seguro, no hacemos nada
        });
    }
}
/* Termina función para publicar el libro en sus datos base */

/* Comienza función para actualizar el libro en sus datos base */
function actualizarLibro(book_id)
{
    // Los géneros seleccionados
    var generosLibro = [];
    $('#genre-list span').each(function () 
    {
        // Obtener el ID del span (ej. genero-1 => 1)
        var id = $(this).attr('id').split('-')[1];        
        // Ponemos el id en una variable de array
        generosLibro.push(id);
    });
    // Los autores seleccionados
    var autoresLibro = [];
    $('#author-list span').each(function () 
    {
        // Obtener el ID del span (ej. genero-1 => 1)
        var id = $(this).attr('id').split('-')[1];        
        // Ponemos el id en una variable de array
        autoresLibro.push(id);
    });
    if(generosLibro.length == 0 || autoresLibro.length == 0)
    {
        mensaje('error', '<b>ERROR</b><br/>No puede quedar el libro sin géneros ni sin autores.');
    }
    else
    {
        Swal.fire
        ({
            title: 'Actualizar libro',
            html: '<h4 style="color: black;">¡ESTE PROCESO ES IRREVERSIBLE!<br/>Si tiene cambios en alguna de las secciones, se perderá si no salva primero.<br/>¿Está seguro?</p>',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Sí",
            denyButtonText: "No"
        }).then(async (result) => 
        {
            if (result.isConfirmed) 
            {
                // Si se dice que sí, jalamos los datos de la página
                // El nombre del libro
                var nombreLibro = $('#book-name').val();                
                // El contenido de la sinopsis 
                var sinopsisLibro = tinymce.get("description").getContent();
                // Esperamos a que la función de rutaImagen se complete
                var url_imagen_save = await rutaImagen('#file-selector', book_cover_url);
                // Ahora realizamos la actualización del libro
                $.ajax
                ({
                    url: './controller/book-crud.php',
                    type: 'POST',
                    data:
                    {
                        update_book: true,
                        id_libro: book_id,
                        nombre_libro: nombreLibro,
                        generos_libro: JSON.stringify(generosLibro),
                        autores_libro: JSON.stringify(autoresLibro),
                        sinopsis_libro: sinopsisLibro,
                        url_imagen_save: url_imagen_save // Usamos la ruta que retornó la función asincrónica
                    },
                    async: true,
                    success: function(response)
                    {
                        // Debe devolver un mensaje. Dependiendo del mensaje es lo que hay que hacer.
                        if (response == 'SUCCESS')
                        {
                            // Si el mensaje es de éxito, se genera un mensaje de éxito
                            mensaje('success', '<b>ÉXITO</b><br/>Libro actualizado satisfactoriamente.<br/>Será redirigido a la página del libro ahora.');
                            // Y se redirige a la página de libro
                            setTimeout(function()
                            {
                                window.location.href = "index.php?page=book-page&book-id=" + book_id;
                            }, 5000);
                        }
                        else
                        {
                            // Si el mensaje es de error, se genera un mensaje y listo.
                            mensaje('error', '<b>ERROR</b><br/>Hubo un error en el programa: <br/>' + response + '.<br/>Por favor contacte al desarrollador.');
                        }
                    },
                    error: function(error)
                    {
                        // Si hay un error, debe devolverlo como mensaje de error.
                        mensaje('error', '<b>ERROR</b><br/>Hubo un error en el programa: <br/>' + error + '.<br/>Por favor contacte al desarrollador.');
                    }
                });
            }
            // Si no está seguro, no hacemos nada
        });
    }
}
/* Termina función para actualizar el libro en sus datos base */

/* Comienza función asincrónica para guardar la ruta de la imagen */
function rutaImagen(selector_id, book_cover_url) 
{
    return new Promise((resolve, reject) => 
    {
        var book_file = $(selector_id)[0].files[0];
        var formData = new FormData();
        if (book_file) 
        {
            formData.append('book_file', book_file);
            $.ajax({
                url: './controller/picture-save.php', // Archivo PHP para manejar la subida de imagenes
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) 
                {
                    // El AJAX retorna la ruta de la imagen o algunos códigos de error, los procesamos
                    switch (data) {
                        case 'WRONG_FORMAT':
                            mensaje('error', '<b>ERROR</b><br/>El formato de la imagen debe ser JPG o PNG.<br/>Por favor seleccione otra imagen e inténtelo nuevamente.');
                            reject('Error: Formato incorrecto');
                            break;
                        case 'UPLOAD_ERROR':
                            mensaje('error', '<b>ERROR</b><br/>Hubo un error en la subida de archivos.<br/>Por favor seleccione otra imagen e inténtelo nuevamente.');
                            reject('Error: Fallo en la subida');
                            break;
                        case 'NO_FILE_RECEIVED':
                            mensaje('error', '<b>ERROR</b><br/>No se recibió un archivo.<br/>Por favor inténtelo nuevamente.');
                            reject('Error: No se recibió archivo');
                            break;
                        default:
                            resolve(data); // Resuelve con la ruta de la imagen
                    }
                },
                error: function(error) 
                {
                    mensaje('error', '<b>ERROR</b><br/>Hubo un error en el programa:<br/>' + error + '.<br/>Por favor contacte al desarrollador.');
                    resolve(book_cover_url); // Si hay un error, devolvemos la ruta predeterminada
                }
            });
        } 
        else 
        {
            // Si no hay archivo, usamos la ruta existente
            resolve(book_cover_url);
        }
    });
}
/* Termina función asincrónica para guardar la ruta de la imagen */

/* Comienza función para guardar una sección nueva */
function saveNewSection(book_id, section_title, section_content)
{
    // Preguntamos si se está seguro
    Swal.fire
    ({
        title: 'Guardar sección',
        html: '<h4 style="color: black;">Este proceso es irreversible</h4><p style="color: black;">¿Está seguro?</p>',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Sí",
        denyButtonText: "No"
    }).then((result) => 
    {
        if (result.isConfirmed) 
        {
            // Mandamos AJAX si se está seguro
            $.ajax
            ({
                type: 'POST',
                url: './controller/section-crud.php',
                data:
                {
                    guardar_seccion: true,
                    titulo_seccion: section_title,
                    contenido_seccion: section_content,
                    id_libro: book_id
                },
                async: true,
                success: function(data)
                {
                    if (data == 'SUCCESS')
                    {
                        // Si es exitoso el guardado, debe mandar mensaje de éxito
                        mensaje('success', '<b>ÉXITO</b><br/>Sección guardada exitosamente.');
                    }
                    else
                    {
                        // Si es cualquier otra cosa, debe mandarla como mensaje
                        mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + data + '<br/>Por favor contacte al desarrollador');
                    }
                },
                error: function(error)
                {
                    mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
                }
            });
        }
        // Si no está seguro, no hacemos nada
    });
    // Sin importar cuál sea el mensaje, se recarga el spinner
    // Primero, quitamos el contenido de las secciones
    $('#section-list').html('');
    // Luego, agregamos el loader, activo de una vez
    $('#section-list').html('<div class="spinner active" id="sections-spinner"><span class="loader"></span></div>');
    // Luego cargamos el listado de secciones
    setTimeout(function()
    {
        loadSections(book_id, book_status);
    }, 500);
    // Finalmente, actualizamos los números de las secciones
    updateSectionNumbers();
}
/* Termina función para guardar una sección nueva */

/* Comienza función para actualizar secciones existentes */
function updateUpdateSections()
{
    $('.update-section').off('click').on('click', function() 
    {
        Swal.fire(
        {
            title: 'Actualizar sección',
            html: '<h4 style="color: black;">Este proceso es irreversible</h4><p style="color: black;">¿Está seguro?</p>',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Sí",
            denyButtonText: "No"
        }).then((result) => 
        {
            if (result.isConfirmed) 
            {
                // Obtener el ID de la sección
                var section_id = $(this).closest('.accordion-section').prev('.accordion-button').data('id');
                
                // Obtener el título de la sección
                var section_title = $(this).closest('.accordion-section').find('.section-title-input').val();
                
                // Obtener el contenido de TinyMCE
                var editor_id = $(this).closest('.accordion-section').find('textarea').attr('id');
                var section_content = tinymce.get(editor_id).getContent();
                
                // Verificar si se han ingresado datos
                if (section_title.trim() === '' || section_content.trim() === '') 
                    {
                    mensaje('error', '<b>ERROR</b><br/>Por favor, completa el título y el contenido de la sección antes de actualizar.');
                    return;
                }

                // Llamada AJAX para actualizar la sección
                $.ajax
                ({
                    type: 'POST',
                    url: './controller/section-crud.php', // Ruta al archivo PHP que procesa la actualización
                    data: 
                    {
                        update_section: true,
                        section_id: section_id,
                        section_title: section_title,
                        section_content: section_content
                    },
                    success: function(data) 
                    {
                        if (data === 'SUCCESS') 
                        {
                            // Deshabilitar el botón de "Actualizar" después de que se complete la actualización
                            mensaje('success', '<b>ÉXITO</b><br/>Sección actualizada exitosamente.');
                            $(this).prop('disabled', true); // Deshabilitar el botón
                            $('#add-section-btn').prop('disabled', false); // Rehabilitamos el botón de añadir sección
                        } 
                        else 
                        {
                            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + data + '<br/>Por favor contacte al desarrollador');
                        }
                    }.bind(this), // Asegurarse de que "this" se refiera al botón dentro de la función success
                    error: function(error) 
                    {
                        mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
                    }
                });
            }
            // Si no está seguro, no hacemos nada
        });
    });
}
/* Termina función para actualizar secciones existentes */

/* Terminan funciones de guardado de datos */

/* Comienzan funciones de borrado de datos */
function updateRemoveButtons()
{
    // Esta función sólo aplicará en libros en borrador o en secciones nuevas
    $('.remove-section').off('click').on('click', function () 
    {
        var current_section_id = $(this).closest('.accordion-section').prev('.accordion-button').data('id');
        // Destruir la instancia de TinyMCE antes de eliminar la sección
        var editor_id = $(this).closest('.accordion-section').find('textarea').attr('id');
        if (tinymce.get(editor_id)) 
        {
            tinymce.get(editor_id).remove();
        }
        if (typeof current_section_id != 'undefined')
        {
            // Si el id es diferente de undefine es porque existe un data de id, por lo tanto la sección existe en la base de datos. Tenemos que preguntar si está seguro
            Swal.fire(
            {
                title: 'Eliminar sección guardada',
                html: '<h4 style="color: black;">Este proceso es irreversible</h4><p style="color: black;">¿Está seguro?</p>',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "Sí",
                denyButtonText: "No"
            }).then((result) => 
            {
                if (result.isConfirmed) 
                {
                    // Si está seguro entonces borramos de base de datos
                    $.ajax
                    ({
                        type: 'POST',
                        url: './controller/section-crud.php',
                        data:
                        {
                            borrar_seccion_edit: true,
                            current_section_id
                        },
                        async: true,
                        success: function(data)
                        {
                            if (data == 'SUCCESS')
                            {
                                mensaje('success', '<b>ÉXITO</b><br/>Sección borrada exitosamente.');
                            }
                            else
                            {
                                // Y si no se borra, capturamos el error dentro de un mensaje
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
                // Si no está seguro, no hacemos nada
            });
        }
        // Si no, no importa, igual haremos lo mismo. 
        // Primero, quitamos el contenido de las secciones
        $('#section-list').html('');
        // Luego, agregamos el loader, activo de una vez
        $('#section-list').html('<div class="spinner active" id="sections-spinner"><span class="loader"></span></div>');
        // Luego cargamos el listado de secciones
        setTimeout(function()
        {
            loadSections(book_id, book_status);
        }, 500);
        // Finalmente, actualizamos los números de las secciones
        updateSectionNumbers();
    });
}
/* Terminan funciones de borrado de datos */