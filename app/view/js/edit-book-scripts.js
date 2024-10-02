/* Comienza definición de variables */
var book_id = $('#book-id').val();
var user_id = $('#user-id').val();
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
        publish_update_btn = '<button class="btn publish-book" id="publish-book">PUBLICAR</button>';
    }
    // De cualquier otra manera hay un error, no debe poner nada más
    var all_buttons = publish_update_btn + '<button class="btn" id="cancel-edit">Cancelar edición</button><button class="btn" id="delete-book">Borrar libro</button>';
    $('#functions').html(all_buttons);
    // Agregamos funciones para cada botón. Debe ser aquí dentro para que se inscriban correctamente
    $('#update-book').on('click', function()
    {
        actualizarLibro(book_id);
    })
    $('#publish-book').on('click', function()
    {
        mensaje('success', '<b>EXITO</b><br/>ID del libro: ' + book_id + '<br/>Status del libro: ' + book_status + '<br/>Botón presionado: PUBLICAR');
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
/* Terminan funciones de llenado de datos */

/* Comienzan funciones de guardado de datos */
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
                // La imagen de portada
                //var formData = new FormData();
                // Variable temporal para la ruta de la imagen
                var url_imagen_save = await rutaImagen('#file-selector', book_cover_url);
                console.log(url_imagen_save);
                // Comenzamos AJAX sólo cuando se haya decidido cuál es la imagen de portada...
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
                        url_imagen_save
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

function rutaImagen(selector_id, book_cover_url)
{
    respuesta = '';
    var book_file = $(selector_id)[0].files[0];
    if (book_file)
    {
        formData.append('book_file', book_file);
        $.ajax
        ({
            url: './controller/picture-save.php', // Archivo PHP para manejar la subida de imagenes
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) 
            {
                // El AJAX retorna la ruta de la imagen para poder usarla o unos códigos particulares, procésalos
                switch (data)
                {
                    case 'WRONG_FORMAT':
                        // No se supone que se llegue hasta aquí por los controles que le tengo al selector
                        mensaje('error', '<b>ERROR</b><br/>El formato de la imagen debe ser JPG o PNG.<br/>Por favor seleccione otra imagen e inténtelo nuevamente.');
                        break;
                    case 'UPLOAD_ERROR':
                        // Error en la subida de archivos
                        mensaje('error', '<b>ERROR</b><br/>Hubo un error en la subida de archivos.<br/>Por favor seleccione otra imagen e inténtelo nuevamente.');
                        break;
                    case 'NO_FILE_RECEIVED':
                        // Error en la subida de archivos
                        mensaje('error', '<b>ERROR</b><br/>No se recibió un archivo.<br/>Por favor inténtelo nuevamente o contacte al desarrollador.');
                        break;
                    default:
                        respuesta = data;
                        break;
                }
            },
            error: function(error) 
            {
                // Si hay un error, guardaré la ruta a la imagen por defecto
                mensaje('error', '<b>ERROR</b><br/>Hubo un error en el programa: <br/>' + error + '.<br/>Por favor contacte al desarrollador.');
                respuesta = 'view/uploads/books/generic-book-cover.jpg';
            }
        });
    }
    else
    {
        // Si no hay archivo entonces es lo que traía
        respuesta = book_cover_url;
    }
    return respuesta;
}
/* Terminan funciones de guardado de datos */