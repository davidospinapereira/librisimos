var picsetter = $('#image');
var book_cover_url = 'view/uploads/books/generic-book-cover.jpg';

/* Comienzan funciones automáticas */
$(document).ready(function()
{
    // Primero, la carga de la imagen dummy de perfil
    picsetter.css('background-image', 'url("./view/uploads/books/generic-book-cover.jpg")');
    // Cargamos los selects con los géneros
    loadGenres();
    // Cargamos los selects con los autores
    loadAuthors();
    // Cargamos las funciones de los botones
    loadFunctions();
    // Finalmente, lo de TinyMCE
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

/* Comienza invocación de funciones para agregar géneros y autores */
$('#add-genre').on('click', function()
{
    ponerBaldosa(1, $('#available-genres'));
});

$('#add-author').on('click', function()
{
    ponerBaldosa(2, $('#available-authors'));
});
/* Termina invocación de funciones para agregar géneros y autores */

/* Comienza función genérica para añadir baldosas */
function ponerBaldosa(tipo, item)
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
/* Termina función genérica para añadir baldosas */

/* Comienza función genérica para quitar baldosas */
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
/* Termina función genérica para quitar baldosas */

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

/* Comienza función que carga las funciones de los botones */
function loadFunctions()
{
    try 
    {
        $('#save-book').on('click', async function()
        {
            // Primero, recogemos los datos escritos
            // Nombre del libro
            var nombreLibro = $('#book-name').val();               
            // El contenido de la sinopsis 
            var sinopsisLibro = tinymce.get("description").getContent();
            // Esperamos a que la función de rutaImagen se complete
            var url_imagen_save = await rutaImagen('#file-selector', book_cover_url);
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
            // Luego, comprobamos si título, géneros y autores no están vacíos
            // Si sí, generamos mensaje de error
            if(generosLibro.length == 0 || autoresLibro.length == 0 || nombreLibro == '')
            {
                mensaje('error', '<b>ERROR</b><br/>No puede quedar el libro sin título, géneros o autores.');
            }
            // Si no lo están, mandamos a guardar libro
            else
            {
                guardarLibro(nombreLibro, sinopsisLibro, url_imagen_save, generosLibro, autoresLibro);
            }
        });
    } 
    catch (error) 
    {
        console.log("Error en loadfunctions: " + error);
    }
    $('#cancel-edit').on('click', function()
    {
        cancelarEdicion();
    });
}
/* Termina función que carga las funciones de los botones */
/* Terminan efectos visuales */

/* Comienzan funciones de carga de datos */
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

/* Comienza función para cancelar la creación del libro */
function cancelarEdicion()
{
    Swal.fire(
    {
        title: 'Cancelar edición',
        html: '<h4 style="color: black;">Este proceso es irreversible</h4><p style="color: black;">Si cancela sin guardar, todo su progreso se perderá.<br/>¿Está seguro?</p>',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Sí",
        denyButtonText: "No"
    }).then((result) => 
    {
        if (result.isConfirmed) 
        {
            window.location.href = "index.php?page=main";
        }
    });
}
/* Termina función para cancelar la creación del libro */
/* Terminan funciones de carga de datos */

/* Comienzan funciones de guardado de datos */
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

/* Comienza función para guardar un libro */
function guardarLibro(nombreLibro, sinopsisLibro, url_imagen_save, generosLibro, autoresLibro)
{
    Swal.fire
    ({
        title: 'Guardar libro nuevo',
        html: '<h4 style="color: black;">¡ESTE PROCESO ES IRREVERSIBLE!<br/>¿Está seguro?</p>',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Sí",
        denyButtonText: "No"
    }).then(function(result) 
    {
        if (result.isConfirmed) 
        {
            // Si se dice que sí, jalamos los datos de la página 
            // Ahora realizamos la actualización del libro
            $.ajax
            ({
                url: './controller/book-crud.php',
                type: 'POST',
                data:
                {
                    nuevo_libro: true,
                    nombre_libro: nombreLibro,
                    generos_libro: JSON.stringify(generosLibro),
                    autores_libro: JSON.stringify(autoresLibro),
                    sinopsis_libro: sinopsisLibro,
                    url_imagen_libro: url_imagen_save // Usamos la ruta que retornó la función asincrónica
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
/* Termina función para guardar un libro */
/* Terminan funciones de guardado de datos */