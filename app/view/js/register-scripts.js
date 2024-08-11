/* Comienza declaración de variables */
var picsetter = $('#foto');
// Array con los inputs para control de vacíos
const nombres = [];
nombres.push('nombre-usuario', 'apellido-usuario', 'fecha-nacimiento', 'email-usuario', 'login-usuario', 'pass-usuario', 'conf-pass-usuario');
/* Termina declaración de variables */

/* Comienza código para remover los colores rojos de cada input */
for(const nombre in nombres)
{
    $('#' + nombres[nombre]).on('click', function() 
    {
        $('#' + nombres[nombre]).removeClass('danger');
    });
}
/* Termina código para remover los colores rojos de cada input */

/* Comienza código para poner fotos en el selector de imagen */
jQuery(window).on('load', function()
{
    // La URL la debo preparar con una función que jale por AJAX el texto de la base de datos
    picsetter.css('background-image', 'url(./view/img/user-avatar.jpg)');
});

jQuery('#file-selector').change(function () 
{
    // Aquí agregamos el código de ChatGPT
    var file = this.files[0];
    var reader = new FileReader();
    console.log(reader.result);
    reader.onloadend = function ()
    {
        picsetter.css('background-image', 'url("' + reader.result + '")');
    }
    if (file)
    {
        reader.readAsDataURL(file);
    }
    else
    {}
});
/* Termina código para poner fotos en el selector de imagen */

/* Comienza código para verificar datos */
$('#create-profile').on('click', function()
{
    // Quitamos la clase danger de todos los inputs
    for(const nombre in nombres)
    {
        $('#' + nombres[nombre]).on('click', function() 
        {
            $('#' + nombres[nombre]).removeClass('danger');
        });
    }
    // Si hay campos vacíos
    if(camposVacios())
    {
        mensaje('error', '<b>ERROR</b><br/>Hay campos vacíos. Por favor verifique <br/>e intente nuevamente.');
    }
    // Si no, todos los campos están llenos
    else
    {
        // Si la contraseña y su confirmación son iguales
        if($('#pass-usuario').val() == $('#conf-pass-usuario').val())
        {
            Swal.fire
            (
                {
                title: 'CONFIRMAR CREACIÓN DE CUENTA',
                html: '<b>ESTA ACCIÓN ES IRREVERSIBLE</b><br/>Por favor verifique sus datos antes de continuar.',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "Sí",
                denyButtonText: "No"
                }
            )
            .then
            (
                function(result) 
                {
                    if (result.isConfirmed) 
                    {
                        // Se activa la función para crear una cuenta nueva
                        crearPerfil();
                    }
                }
            );
        }
        // Si no, no podemos continuar
        else
        {
            mensaje('error', '<b>ERROR</b><br/>Los campos de contraseña no son iguales. <br/>Por favor verifique e intente nuevamente.');
        }
    }
});

function camposVacios()
{
    var respuesta = false;
    for (const nombre in nombres) 
    {
        if ($('#' + nombres[nombre]).val() == '')
        {
            $('#' + nombres[nombre]).addClass('danger');
            respuesta = true;
        }
    }
    return respuesta;
}
/* Termina código para verificar datos */

/* Comienza código AJAX para crear cuenta */
function crearPerfil()
{
    // Aún tengo que generar la función para guardar la imagen en servidor y obtener la ruta final
    var postData = 
    {
        'nombre' : $('#nombre-usuario').val(),
        'apellido' : $('#apellido-usuario').val(),
        'nacimiento' : $('#fecha-nacimiento').val(),
        'email' : $('#email-usuario').val(),
        'login' : $('#login-usuario').val(),
        'pass' : $('#pass-usuario').val()/* ,
        'imagen' : url_imagen, */
    };
    var arrayJSON = JSON.stringify(postData);
    $.ajax
    ({
        type: 'POST', // Type es el tipo de solicitud
        url: './controller/subscriber-create.php',
        data: 
        {
            create_subscriber: true,
            data: arrayJSON
        },
        /* contentType: 'application/json', // contentType es el tipo de contenido deseado */
        async: true, // async indica si se desea que los datos sean asíncronos. true viene por defecto pero es mejor especificarlo
        success: function(data)
        {
            // Si el código es de éxito entonces debemos avisar y redirigir a inicio
            if (data == 'NEW_USER_CREATED')
            {
                mensaje('success', '<b>ÉXITO</b><br/>Usuario creado satisfactoriamente.<br/>Será redirigido a la página de inicio.<br/>Por favor inicie sesión desde ahí.');
                setTimeout(function() 
                {
                    window.location.href = "../";
                }, 5000);
            }
            // Si no, hay un error, deberá mostrarlo
            else
            {
                mensaje('error', '<b>ERROR</b><br/>Hubo un error en el software: <br/>' + data + '<br/>Por favor contacte al administrador.');
            }
        },
        error: function(error)
        {
            mensaje('error', error);
        }
    });
}
/* Termina código AJAX para crear cuenta */

/* Comienza código para cancelar el creador de cuenta */
$('#cancel-profile').on('click', function()
{
    Swal.fire
    (
        {
        title: 'CANCELAR CREACIÓN DE CUENTA',
        html: '<b>ESTA ACCIÓN ES IRREVERSIBLE</b><br/>¿Está segur@ de esto?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Sí",
        denyButtonText: "No"
        }
    )
    .then
    (
        function(result) 
        {
            if (result.isConfirmed) 
            {
                // Redirigimos a index, por fuera de todo
                window.location.href = "../";
            }
        }
    );
});
/* Termina código para cancelar el creador de cuenta */