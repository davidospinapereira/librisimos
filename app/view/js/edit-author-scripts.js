var picsetter = $('#image');
var author_id = $('#author-id').val();
var author_image_avatar = 'view/uploads/authors/generic-author-avatar.png';

/* Comienzan funciones automáticas */
$(document).ready(function()
{
    // Colocamos el nombre de la página desde aquí:
    $(document).prop('title', 'Editar autor - Librísimos');
    // Primero, la carga de la imagen dummy de perfil
    picsetter.css('background-image', 'url("' + author_image_avatar + '")');
    // Cargamos los datos del autor
    loadData(author_id);
    // Cargamos las funciones de los botones
    loadFunctions();
});

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

/* Comienza función que carga las operaciones en los botones */
function loadFunctions()
{
    try 
    {
        $('#save-author').on('click', async function()
        {
            // Primero, recogemos los datos escritos
            // Nombre del libro
            var nombreAutor = $('#author-name').val();               
            // El contenido de la sinopsis 
            var descripcionAutor = tinymce.get("description").getContent();
            // Esperamos a que la función de rutaImagen se complete
            var url_imagen_save = await rutaImagen('#file-selector', author_image_avatar);            
            // Luego, comprobamos si nombre está vacío
            // Si sí, generamos mensaje de error
            if(nombreAutor == '')
            {
                mensaje('error', '<b>ERROR</b><br/>No puede quedar el autor sin nombre.');
            }
            // Si no lo están, mandamos a guardar libro
            else
            {
                actualizarAutor(author_id, nombreAutor, descripcionAutor, url_imagen_save);
            }
        });
    } 
    catch (error) 
    {
        mensaje('error', "Error en loadfunctions: " + error);
    }
    $('#cancel-edit').on('click', function()
    {
        cancelarEdicion();
    });
}
/* Termina función que carga las operaciones en los botones */

/* Comienza función para cargar los datos del autor */
function loadData(author_id)
{
    // Llamamos a AJAX
    $.ajax
    ({
        url: './controller/author-crud.php',
        type: 'POST',
        data:
        {
            obtener_autor: true,
            id_autor: author_id // Usamos la ruta que retornó la función asincrónica
        },
        async: true,
        success: function(response)
        {
            var respuesta = JSON.parse(response);
            // Debe devolver un mensaje. Dependiendo del mensaje es lo que hay que hacer.
            if (respuesta.mensaje == 'SUCCESS')
            {
                // Llenamos los datos
                $('#author-name').val(respuesta.nombre_autor);
                $('#id-book').html('ID de autor: ' + author_id);
                console.log(respuesta.url_imagen_autor);
                if (respuesta.url_imagen_autor == null || respuesta.url_imagen_autor == '')
                {
                    $('#image').css('background-image', 'url("./view/uploads/authors/generic-author-avatar.png")');
                }
                else
                {
                    $('#image').css('background-image', 'url("./' + respuesta.url_imagen_autor + '")');
                    author_image_avatar = respuesta.url_imagen_autor;
                }
                $('#description').html(respuesta.informacion_autor);
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
            }
            else
            {
                // Si el mensaje es de error, se genera un mensaje y listo.
                mensaje('error', '<b>ERROR</b><br/>Hubo un error en el programa: <br/>' + respuesta.mensaje + '.<br/>Por favor contacte al desarrollador.');
            }
        },
        error: function(error)
        {
            // Si hay un error, debe devolverlo como mensaje de error.
            mensaje('error', '<b>ERROR</b><br/>Hubo un error en el programa: <br/>' + error + '.<br/>Por favor contacte al desarrollador.');
        }
    });
}
/* Termina función para cargar los datos del autor */

/* Comienza función asincrónica para guardar la ruta de la imagen */
function rutaImagen(selector_id, author_image_url) 
{
    return new Promise((resolve, reject) => 
    {
        var author_file = $(selector_id)[0].files[0];
        var formData = new FormData();
        if (author_file) 
        {
            formData.append('author_file', author_file);
            $.ajax
            ({
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
                    resolve(author_image_url); // Si hay un error, devolvemos la ruta predeterminada
                }
            });
        } 
        else 
        {
            // Si no hay archivo, usamos la ruta existente
            resolve(author_image_url);
        }
    });
}
/* Termina función asincrónica para guardar la ruta de la imagen */

/* Comienza función para actualizar los datos de un autor */
function actualizarAutor(idAutor, nombreAutor, descripcionAutor, url_imagen_save)
{
    // Preguntamos si se está seguro
    Swal.fire
    ({
        title: 'Actualizar autor',
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
            $.ajax
            ({
                url: './controller/author-crud.php',
                type: 'POST',
                data:
                {
                    actualizar_autor: true,
                    id_autor: idAutor,
                    nombre_autor: nombreAutor,
                    descripcion_autor: descripcionAutor,
                    url_imagen_autor: url_imagen_save // Usamos la ruta que retornó la función asincrónica
                },
                async: true,
                success: function(response)
                {
                    // Debe devolver un mensaje. Dependiendo del mensaje es lo que hay que hacer.
                    if (response == 'SUCCESS')
                    {
                        // Si el mensaje es de éxito, se genera un mensaje de éxito
                        mensaje('success', '<b>ÉXITO</b><br/>Autor actualizado satisfactoriamente.<br/>Será redirigido a la página del autor ahora.');
                        // Y se redirige a la página de autor
                        setTimeout(function()
                        {
                            window.location.href = "index.php?page=author-page&author-id=" + idAutor;
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
/* Termina función para actualizar los datos de un autor */