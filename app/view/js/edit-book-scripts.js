/* Comienza definición de variables */
var book_id = $('#book-id').val();
var user_id = $('#user-id').val();
var book_status = 0;
bookStatus(book_id, function(status) 
{
    book_status = status;
    // Aquí puedes manejar lo que quieras hacer con el valor "status"
});
/* Termina definición de variables */

/* Comienzan funciones automáticas */
$(document).ready(function()
{
    // Si el estatus está en borrador, los botones cambian
    loadBookData(book_id, book_status);
    // Activamos el spinner
    $('#sections-spinner').addClass('active');
    setTimeout(function () 
    {
        // Si el estatus está en publicado, no debo poder borrar secciones
        loadSections(book_id, book_status);
    }, 500);
});
/* Terminan funciones automáticas */

/* Comienzan efectos para el acordeón */
function toggleSection(section)
{
    var section = $('#section-' + section);
    section.toggleClass('active');
}
/* Terminan efectos para el acordeón */

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

/* Comienza función que carga los datos del libro y los pone en su sitio */
function loadBookData(book_id, book_status)
{
    var book_status_text = '';
    var book_publish_button = '';
    if(book_status == 1)
    {
        // Si es 1 es publicado
        book_status_text = '<span class="status-tile published">PUBLICADO</span>';
    }
    else if(book_status == 2)
    {
        // Si es 2 es borrador
        book_status_text = '<span class="status-tile draft">BORRADOR</span>';
        // Aquí agregamos el html de publicar, en verde
        book_publish_button = '<button class="btn publish-book" id="publish-book">PUBLICAR</button>';
    }
    else
    {
        // Si es cualquier otra cosa
        book_status_text = '<span class="status-tile error">ERROR</span>';
    }
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
            // Modificación: Si el status del libro es borrador debo agregar 
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
            $('#book-status').html(book_status_text);
            $('#genre-list').html(results.generos_html);
            $('#author-list').html(results.autores_html);
            $('#description').html(results.sinopsis_libro);
            /* Comienza instancia de TinyMCE */
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
            /* Termina instancia de TinyMCE */
            // Cargando los botones
            $('#functions').html(
                book_publish_button + 
                '<button class="btn" id="update-book">Actualizar libro</button><button class="btn" id="cancel-edit">Cancelar edición</button><button class="btn" id="delete-book">Borrar libro</button>'
            );
            // Luego, aquí dentro, tenemos que cargar las funciones de los botones
            bookFunctions(book_id, book_status);
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
                    });

                    // Puedes agregar el evento 'change' por si se hace algún cambio mayor, aunque 'keyup' es suficiente
                    editor.on('change', function () 
                    {
                        $(editor.getElement()).closest('.accordion-section').find('.update-section').prop('disabled', false);
                    });
                }
            });
            // Obtenemos los datos de cada botón de actualizar y les aplicamos la función de actualizar...
            // Actualizar la funcionalidad de botones de eliminación
            updateRemoveButtons();
            // Actualizar la funcionalidad de cambio de nombre en la escritura
            updateTitleInputs();
            // Actualizar la funcionalidad de actualización de secciones existentes
            /* $('#section-' + totalSections + '-save').on('click', function()
            {
                var section_number = totalSections; // El número de sección lo crea el sistema y no el usuario
                var section_title = $('#section-' + totalSections + '-title').val();
                // Y si el usuario no escribió un título?
                if (section_title == '' || section_title == null)
                {
                    // Pues el título será el número de la sección, esto se ve en muchos libros
                    section_title = section_number;
                }
                // El contenido de la sección puede incluso ser vacío y deberá poder guardarse
                var section_content = tinymce.get('section-' + totalSections + '-content').getContent();
                saveNewSection(book_id, section_number, section_title, section_content);
            }); */
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
                $(editor.getElement()).closest('.accordion-section').find('.save-section').prop('disabled', false);
            });

            // Puedes agregar el evento 'change' por si se hace algún cambio mayor, aunque 'keyup' es suficiente
            editor.on('change', function () 
            {
                $(editor.getElement()).closest('.accordion-section').find('.save-section').prop('disabled', false);
            });
        }
    });
    // Actualizar la funcionalidad de los botones "Eliminar sección"
    updateRemoveButtons();
    // Actualizar la funcionalidad de cambio de nombre en la escritura
    updateTitleInputs();
    // Agregar la funcionalidad de guardado de la sección en BD
    $('#section-' + totalSections + '-save').on('click', function()
    {
        var section_number = totalSections; // El número de sección lo crea el sistema y no el usuario
        var section_title = $('#section-' + totalSections + '-title').val();
        // Y si el usuario no escribió un título?
        if (section_title == '' || section_title == null)
        {
            // Pues el título será el número de la sección, esto se ve en muchos libros
            section_title = section_number;
        }
        // El contenido de la sección puede incluso ser vacío y deberá poder guardarse
        var section_content = tinymce.get('section-' + totalSections + '-content').getContent();
        saveNewSection(book_id, section_number, section_title, section_content);
    });
}
/* Termina función para agregar secciones */

/* Comienza función para eliminar una sección, con el apoyo de ChatGPT, hay que arreglarla para base de datos */
function updateRemoveButtons() 
{
    $('.remove-section').off('click').on('click', function () 
    {
        var current_section_id = $(this).closest('.accordion-section').prev('.accordion-button').data('id');
        if (typeof current_section_id === 'undefined')
        {
            // Si no existe un data de id es porque la sección es nueva, entonces no hay que hacer nada en BD
            $(this).closest('.accordion-section').prev('.accordion-button').remove(); // Eliminar el botón correspondiente
            $(this).closest('.accordion-section').remove(); // Eliminar la sección correspondiente
        }
        else
        {
            // Si sí existe un data de id es porque la sección existe en la base de datos. Tenemos que preguntar si está seguro
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
                                // Y si se borra, hacemos el borrado de la página
                                $(this).closest('.accordion-section').prev('.accordion-button').remove(); // Eliminar el botón correspondiente
                                $(this).closest('.accordion-section').remove(); // Eliminar la sección correspondiente
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
        // Finalmente, sin importar qué suceda, actualizamos los números de las secciones
        updateSectionNumbers();
    });
}
/* Termina función para eliminar una sección, con el apoyo de ChatGPT */

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

        // Habilitar el botón de "Actualizar" cuando se modifique el título
        $(this).closest('.accordion-section').find('.update-section').prop('disabled', false);
        $(this).closest('.accordion-section').find('.save-section').prop('disabled', false);
    });
}
/* Termina función para actualizar el título en el botón al escribir en el input de título */

/* Comienza función para habilitar el botón de "Actualizar" cuando se realicen cambios en el contenido */
function updateSectionContentInputs() 
{
    tinymce.init({
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
                $(editor.getElement()).closest('.accordion-section').find('.save-section').prop('disabled', false);
            });

            // Puedes agregar el evento 'change' por si se hace algún cambio mayor, aunque 'keyup' es suficiente
            editor.on('change', function () 
            {
                $(editor.getElement()).closest('.accordion-section').find('.update-section').prop('disabled', false);
                $(editor.getElement()).closest('.accordion-section').find('.save-section').prop('disabled', false);
            });
        }
    });
}
/* Termina función para habilitar el botón de "Actualizar" cuando se realicen cambios en el contenido */

/* Comienzan funciones de control para los botones principales del libro */
function bookFunctions(book_id, book_status)
{
    $('#publish-book').on('click', function()
    {
        alert('Botón de update book oprimido para el libro '+ book_id);
        // Primero, preguntamos si el usuario está seguro.
        // Si sí, actuamos
        // Actualizamos los datos base del libro
        // Actualizamos las baldosas, tenemos que recoger un array de las ID de las baldosas
        // Actualizamos los autores, tenemos que recoger un array de las ID de las baldosas
        // Generamos un AJAX único con todo lo lanzado 
        // Si no, no hacemos nada
    });

    $('#update-book').on('click', function()
    {
        alert('Botón de update book oprimido para el libro '+ book_id);
        // Primero, preguntamos si el usuario está seguro.
        // Si sí, actuamos
        // Actualizamos los datos base del libro
        // Recogemos las baldosas de 
        // Si no, no hacemos nada
    });

    $('#cancel-edit').on('click', function()
    {
        alert('Botón de cancel oprimido para el libro '+ book_id);
        // Primero, preguntamos si el usuario está seguro.
        // Si sí, actuamos
        // Actualizamos los datos base del libro
        // Recogemos las baldosas de 
        // Si no, no hacemos nada
    });

    $('#delete-book').on('click', function()
    {
        alert('Botón de delete oprimido para el libro '+ book_id);
        // Primero, preguntamos si el usuario está seguro.
        // Si sí, actuamos
        // Actualizamos los datos base del libro
        // Recogemos las baldosas de 
        // Si no, no hacemos nada
    });
}
/* Terminan funciones de control para los botones principales del libro */

/* Comienza función para actualizar una sección */
function updateSection(section_id, section_title, section_content)
{
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
                    update_section: true,
                    section_id,
                    section_title,
                    section_content
                },
                success: function(response) 
                {
                    if (response == 'SUCCESS') 
                    {
                        // Mostrar mensaje de éxito
                        mensaje('success', '<b>ÉXITO</b><br/>Sección actualizada exitosamente.');
                        // Y reiniciamos el listado de secciones
                        // Reactivamos el spinner
                        $('#section-list').html("");
                        $('#section-list').html
                        (
                            "<div class='spinner active' id='sections-spinner'><span class='loader'></span></div>"
                        );
                        setTimeout(function () 
                        {
                            $('#section-list').html("");
                            // Si el estatus está en publicado, no debo poder borrar secciones
                            loadSections(book_id, book_status);
                        }, 500);
                    } 
                    else 
                    {
                        // Mostrar mensaje de error
                        mensaje('error', '<b>ERROR</b><br/>No se pudo actualizar la sección:<br/>' + response);
                    }
                },
                error: function(error) 
                {
                    // Mostrar mensaje de error en caso de fallo de conexión
                    mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
                }
            });
        }
        // Si no está seguro, no hacemos nada
    });
    
}
/* Termina función para actualizar una sección */

/* Comienza función para registrar una nueva sección */
function saveNewSection(book_id, section_title, section_content)
{
    Swal.fire
    ({
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
            // Si se dice que sí, activamos un AJAX
            $.ajax
            ({
                type: 'POST',
                url: './controller/section-crud.php',
                // Debemos adaptar el creador de sección que tenemos en section-crud.php
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
                        // Cuando tenga éxito, debemos generar un mensaje de éxito
                        mensaje('success', '<b>ÉXITO</b><br/>Sección guardada exitosamente.');
                        // Además, tenemos que recargar el listado de secciones... Y si hay varias secciones que el usuario quiere guardar y tiene listas? Cómo hacemos para que no pierda las otras?
                    }
                    else
                    {
                        // Cuando haya una falla, debemos generar un mensaje de error
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
/* Termina función para registrar una nueva sección */