/* Comienza función para cerrar la herramienta de lectura */
$('#close-read-tool').on('click', function()
{
    $('.read-overlay').removeClass('active');
    $('.read-overlay').fadeOut("slow");
    $('.read-space').fadeOut("slow");
    $('.read-space').removeClass('dark');
    $('.toggle').removeClass('pushed');
    $('#read-tool-previous').prop('disabled', false);
    $('#read-tool-next').prop('disabled', false);
});
/* Termina función para cerrar la herramienta de lectura */

/* Comienza función para abrir la herramienta de lectura */
function activarHerramienta(section_id, user_id)
{
    // Aquí tengo que jalar los datos de la sección deseada por AJAX. Por lo tanto, esta función tendrá parámetros
    // Primero, hacemos visible la herramienta de lectura
    $('.read-overlay').addClass('active');
    $('.read-overlay').fadeIn("slow");
    $('.read-space').fadeIn("slow");
    $('.read-space').removeClass('dark');
    $('.toggle').removeClass('pushed');
    // Después, hacemos visible el spinner
    // Ahora, invocamos el AJAX
    $.ajax
    ({
        type: 'POST',
        url: './controller/section-crud.php',
        async: true,
        data: 
        {
            read_section: true,
            section_id,
            user_id
        },
        beforeSend: function()
        {
            $('#reader-overlay').addClass('active');
            /* $('#reader-overlay').fadeIn("slow"); */
        },
        success: function(data)
        {
            // Tenemos que decodificar el JSON resultante
            var results = JSON.parse(data);
            if (results.codigo = 'SUCCESS')
            {
                // Aquí debemos sacar los datos, organizarlos en la herramienta de lectura
                $('#read-tool-book-title').text(results.book_title);
                $('#read-tool-book-author').html('<h4>' + results.book_author + '</h4>');
                // <span style="background-color: blue; border-color: blue;">Divulgación científica</span><span style="background-color: blueviolet; border-color: blueviolet;">Fantasía</span>
                $('#read-tool-book-genres').html(results.genres);
                // <h4>Parte/Capítulo: <b>13</b></h4>
                $('#read-tool-section-chapter').html('<h4>Parte/Capítulo: <b>' + results.section_number + '</b></h4>');
                // <h4>¿Quién hablará en nombre de la Tierra?</h4>
                $('#read-tool-section-title').html('<h4>' + results.section_title + '</h4>');
                $('#read-tool-section-content').html(results.section_content);
                // Para los botones
                switch (results.section_position) 
                {
                    case 'FIRST':
                        // Si la sección es la primera, no puede haber sección anterior
                        $('#read-tool-previous').prop('disabled', true);
                        $('#read-tool-next').on('click', function()
                        {
                            leerSeccionDesdeReader(parseInt(results.section_number) + 1, parseInt(results.book_id), parseInt(user_id))
                        });
                        break;
                    case 'MIDDLE':
                        $('#read-tool-previous').on('click', function()
                        {
                            leerSeccionDesdeReader(parseInt(results.section_number) - 1, parseInt(results.book_id), parseInt(user_id))
                        });
                        $('#read-tool-next').on('click', function()
                        {
                            leerSeccionDesdeReader(parseInt(results.section_number) + 1, parseInt(results.book_id), parseInt(user_id))
                        });
                        break;
                    case 'LAST':
                        // Si la sección es la última, debe haber sección anterior
                        $('#read-tool-previous').on('click', function()
                        {
                            leerSeccionDesdeReader(parseInt(results.section_number) - 1, parseInt(results.book_id), parseInt(user_id))
                        });
                        // Si la sección es la última, no puede haber sección siguiente
                        $('#read-tool-next').prop('disabled', true);
                        break;
                    default:
                        // Si la sección es única o si hay un error, no puede haber sección siguiente ni anterior
                        $('#read-tool-previous').prop('disabled', true);
                        $('#read-tool-next').prop('disabled', true);
                        break;
                }
            }
            else
            {
                mensaje('error', '<b>ERROR</b><br/>Hubo un error en el programa:<br/>' + results.error + '<br/>Por favor comuníquese con el administrador o el desarrollador.');
            }
        },
        error: function(error)
        {
            // Generamos un mensaje de error
            mensaje('error', '<b>ERROR</b><br/>Hubo un error en el programa:<br/>' + error + '<br/>Por favor comuníquese con el administrador o el desarrollador.');
            $('#read-tool-section-content').html('<b>ERROR</b><br/>Hubo un error en el programa:<br/>' + error + '<br/>Por favor comuníquese con el administrador o el desarrollador.');
        },
        complete: function()
        {
            // Recargamos las tablas            
            continuarLeyendo();
            // Luego, quitamos el spinner
            $('#reader-overlay').fadeOut("slow");
            $('#reader-overlay').removeClass('active');
        }
    });
}
/* Termina función para abrir la herramienta de lectura */

/* Comienza función para activar el modo oscuro */
$('#dark-toggle').on('click', function()
{
    $('.read-space').toggleClass('dark');
    $('.toggle').toggleClass('pushed');
});
/* Termina función para activar el modo oscuro */

/* Comienza función para leer otra sección desde la misma herramienta de lectura */
function leerSeccionDesdeReader(section_number, book_id, user_id)
{
    // Primero, hacemos visible el spinner
    $('#reader-overlay').addClass('active');
    $('#reader-overlay').fadeIn("slow");
    // Después, reinicializamos los botones
    $('#read-tool-previous').prop('disabled', false);
    $('#read-tool-next').prop('disabled', false);
    // Luego, llamamos al AJAX muy similar a activarHerramienta
    $.ajax
    ({
        type: 'POST',
        url: './controller/section-crud.php',
        async: true,
        data: 
        {
            other_section: true,
            section_number,
            book_id,
            user_id
        },
        success: function(data)
        {
            // Sólo recargamos la parte interna, por lo que la función debe ser diferente
            var results = JSON.parse(data);
            $('#read-tool-section-chapter').html('<h4>Parte/Capítulo: <b>' + results.section_number + '</b></h4>');
            // <h4>¿Quién hablará en nombre de la Tierra?</h4>
            $('#read-tool-section-title').html('<h4>' + results.section_title + '</h4>');
            $('#read-tool-section-content').html(results.section_content);
            // Recargamos la tabla detrás de la herramienta de lectura
            switch (results.section_position) 
            {
                case 'FIRST':
                    // Si la sección es la primera, no puede haber sección anterior
                    $('#read-tool-previous').prop('disabled', true);
                    $('#read-tool-next').on('click', function()
                    {
                        leerSeccionDesdeReader(parseInt(results.section_number) + 1, book_id, user_id);
                    });
                    break;
                case 'MIDDLE':
                    $('#read-tool-previous').on('click', function()
                    {
                        leerSeccionDesdeReader(parseInt(results.section_number) - 1, book_id, user_id);
                    });
                    $('#read-tool-next').on('click', function()
                    {
                        leerSeccionDesdeReader(parseInt(results.section_number) + 1, book_id, user_id);
                    });
                    break;
                case 'LAST':
                    // Si la sección es la última, debe haber sección anterior
                    $('#read-tool-previous').on('click', function () 
                    {
                        leerSeccionDesdeReader(parseInt(results.section_number) - 1, book_id, user_id);
                    });
                    // Si la sección es la última, no puede haber sección siguiente
                    $('#read-tool-next').prop('disabled', true);
                    break;
                default:
                    // Si la sección es única o si hay un error, no puede haber sección siguiente ni anterior
                    $('#read-tool-previous').prop('disabled', true);
                    $('#read-tool-next').prop('disabled', true);
                    break;
            }
        },
        error: function(error)
        {
            // Generamos un mensaje de error
            mensaje('error', '<b>ERROR</b><br/>Hubo un error en el programa:<br/>' + error + '<br/>Por favor comuníquese con el administrador o el desarrollador.');
            $('#read-tool-section-content').html('<b>ERROR</b><br/>Hubo un error en el programa:<br/>' + error + '<br/>Por favor comuníquese con el administrador o el desarrollador.');
        },
        complete: function()
        {
            // Recargamos la tabla de continuar leyendo, en home es una, en book-page tiene que ser otra con el mismo nombre
            continuarLeyendo();
            // Luego, quitamos el spinner
            $('#reader-overlay').fadeOut("slow");
            $('#reader-overlay').removeClass('active');
        }
    });
}
/* Termina función para leer otra sección desde la misma herramienta de lectura */