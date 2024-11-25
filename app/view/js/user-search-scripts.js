/* Comienza declaración de variables */
var filterSearch = $('#filter-input');
var idTipoActual = $('#id-tipo').val();
/* Termina declaración de variables */
/* Comienzan funciones de carga automática */
$(document).ready(function()
{
    // Colocamos el nombre de la página desde aquí:
    $(document).prop('title', 'Administrador de usuarios - Librísimos');
    // Primero, cargamos el listado
    cargarListado(filterSearch);
    // Luego, preparamos la función de búsqueda AJAX con control para que sea un mínimo de 3 caracteres
    var i = 0;
    filterSearch.on('keyup', function()
    {
        i++;
        if ($(this).val().length > 2)
        {
            cargarListado(filterSearch);
        }
        // Control por si el usuario borra todo el texto de búsqueda seleccionando todo y presionando borrar
        if ($(this).val().length < i)
        {
            cargarListado(filterSearch);
            i = 0;
        }
    });
    // Esta variación se da para cuando se oprima la "X" en el input
    filterSearch.on('search', function(evt)
    {
        cargarListado(filterSearch);
    });
    /* Termina carga de buscador AJAX */
});
/* Terminan funciones de carga automática */

/* Comienza función genérica de carga de tabla */
function cargarListado(filterSearch)
{
    $.ajax
    ({
        type: 'POST',
        url: './controller/ajax-user-crud.php',
        async: true,
        data: 
        {
            obtener_usuarios_listado: true,
            termino_busqueda: filterSearch.val()
        },
        // Tal vez tengamos que usar un beforesend, cuando la tabla tenga muchos libros
        beforeSend: function()
        {
            $('#table-overlay').addClass('active');
            $('#users-table').html('');
        },
        success: function(data)
        {
            // Mandamos el dato al HTML con demora para que se vea
            setTimeout(function()
            {
                $('#table-overlay').removeClass('active');
                $('#users-table').html(data);
            }, 500);
        },
        error: function(error)
        {  
           // Mandamos el error al HTML
           $('#genre-table').html(error);
        }
    });
}
/* Termina función genérica de carga de tabla */

/* Comienza función para editar usuario */
function editarUsuario(idUsuario)
{
    $.ajax
    ({
        type: 'POST',
        url: './controller/ajax-user-crud.php',
        async: true,
        data: 
        {
            obtener_tipo_usuario: true,
            id_usuario: idUsuario
        },
        success: function(data)
        {
            // Debe retornarnos un número, pero retorna un string, cómo lo convierto en numérico?
            var respuesta = Number(data);
            // Control: El usuario súper administrador no puede ser modificado por nadie que no sea el súper administrador mismo
            if (respuesta != NaN)
            {
                if (respuesta == 1 && idTipoActual != data)
                {
                    // Si el tipo de usuario a editar es súper administrador, y el usuario actual no es súper administrador, saca mensaje de error
                    mensaje('error', '<b>ERROR</b><br/>Este usuario no puede ser modificado por nadie que no sea súper administrador');
                }
                else
                {
                    // Si no, redirigimos a la página de editar usuario específico
                    window.location.href = 'index.php?page=new-user&user-id=' + idUsuario;
                }
            }
            else
            {
                mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + data + '<br/>Por favor contacte al desarrollador');
            }
        },
        error: function(error)
        {  
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
        }
    });
}
/* Termina función para editar usuario */

/* Comienza función para eliminar usuario */
function borrarUsuario(idUsuario)
{
    $.ajax
    ({
        type: 'POST',
        url: './controller/ajax-user-crud.php',
        async: true,
        data: 
        {
            obtener_tipo_usuario: true,
            id_usuario: idUsuario
        },
        success: function(data)
        {
            // Debe retornarnos un número, pero retorna un string, cómo lo convierto en numérico?
            var respuesta = Number(data);
            // Control: El usuario súper administrador no puede ser borrado bajo ninguna circunstancia
            if (respuesta != NaN)
            {
                if (respuesta == 1)
                {
                    // Si el tipo de usuario a editar es súper administrador, y el usuario actual no es súper administrador, saca mensaje de error
                    mensaje('error', '<b>ERROR</b><br/>El usuario súper administrador no puede ser borrado bajo ninguna circunstancia.');
                }
                else if (respuesta == 2 && idTipoActual == 2)
                {
                    // Control: Los usuarios administradores no pueden ser borrados por usuarios administradores
                    mensaje('error', '<b>ERROR</b><br/>Los usuarios administradores no pueden ser borrados por otros administradores.');
                }
                else
                {
                    // Si no, preguntamos
                    // Si el usuario dice que sí, entonces borramos al usuario Y TODAS LAS SECCIONES QUE LEYÓ
                    mensaje('success', '<b>PRUEBA</b><br/>Tipo de usuario: ' + idTipoActual + '<br/>Tipo de usuario a borrar: ' + respuesta);
                }
            }
            else
            {
                // Si la respuesta no es un número que se coloque en un mensaje de error
                mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + data + '<br/>Por favor contacte al desarrollador');
            }
        },
        error: function(error)
        {  
            mensaje('error', '<b>ERROR</b><br/>Hay un error en el programa:<br/>' + error + '<br/>Por favor contacte al desarrollador');
        }
    });
}
/* Termina función para eliminar usuario */