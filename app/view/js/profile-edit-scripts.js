var picsetter = $('#foto');
var user_id = $('#user_id_hidden').val();
var url_imagen = $('#url_imagen_hidden').val();
$('#update-data').on('click', function()
{
    actualizarPerfil();
});

$('#change-pass').on('click', function()
{
    Swal.fire
    ({
        title: "Cambiar contraseña",
        html: 
        '<input type="password" id="old-password-sweet" class="swal2-input" placeholder="Inserte la contraseña antigua" style="width: 80%;" required><input type="password" id="new-password-sweet" class="swal2-input" placeholder="Inserte la nueva contraseña" style="width: 80%;" required><input type="password" id="confirm-password-sweet" class="swal2-input" placeholder="Confirme la nueva contraseña" style="width: 80%;" required>',
        focusConfirm: false,
        width: 800,
        preConfirm: function() 
        {
            // Ahora jalamos los datos que recolectamos
            var pass_viejo = $('#old-password-sweet').val();
            var pass_nuevo = $('#new-password-sweet').val();
            var pass_confirm = $('#confirm-password-sweet').val();
            cambiarPass(pass_viejo, pass_nuevo, pass_confirm);
        },
        confirmButtonText: 'Actualizar contraseña'
    });
});

/* Comienza código para poner fotos en el selector de imagen */
jQuery(window).on('load', function()
{
    // Si la url de la imagen no está definida
    if (url_imagen == null || url_imagen == '')
    {
        picsetter.css('background-image', 'url("./view/uploads/users/user-avatar.png")');
    }
    else
    {
        picsetter.css('background-image', 'url("./' + url_imagen + '")');
    }
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

/* Comienza código para actualizar las contraseñas */
function cambiarPass(pass_viejo, pass_nuevo, pass_confirm)
{
    // Primero, confirmamos que los campos no estén vacíos
    if (pass_viejo == null || pass_nuevo == null || pass_confirm == null || pass_viejo == '' | pass_nuevo== '' || pass_confirm == '')
    {
        // Si hay campos vacíos, 
        mensaje('error', '<b>ERROR</b><br/>Hay campos vacíos.<br/>Por favor verifique e intente nuevamente.');
    }
    else
    {
        // Si los campos no están vacíos, confirmamos que el password corresponda al usuario
        // Para eso tenemos que llamar AJAX
        $.ajax
        ({
            type: 'POST', // Type es el tipo de solicitud
            url: './controller/ajax-user-crud.php',
            data: 
            {
                check_password: true,
                user_id,
                pass_viejo
            },
            async: true, 
            success: function(data)
            {
                
                if (data == 'PASS_CORRECT')
                {
                    // Luego, verificamos que las contraseñas sean las mismas
                    if (pass_nuevo != pass_confirm)
                    {
                        // Si no lo son, sacamos mensaje de error y listo
                        mensaje('error', '<b>ERROR</b><br/>Las contraseñas no son las mismas, por favor verifique e intente nuevamente.');
                    }
                    else
                    {
                        // Si son las mismas, jalamos AJAX NUEVAMENTE
                        $.ajax
                        ({
                            type: 'POST',
                            url: './controller/ajax-user-crud.php',
                            data: 
                            {
                                update_password: true,
                                user_id,
                                pass: pass_nuevo
                            },
                            async: true, 
                            success: function(data)
                            {
                                // Actualizamos contraseña
                                // Si fue exitoso el proceso
                                if (data == 'SUCCESS')
                                {
                                    mensaje('success', '<b>ÉXITO</b><br/>Contraseña actualizada satisfactoriamente.');
                                }
                                // Si no, hay un error, deberá mostrarlo
                                else
                                {
                                    mensaje('error', '<b>ERROR</b><br/>Hubo un error en el software: <br/>' + data + '<br/>Por favor contacte al administrador.');
                                }
                            },
                            error: function(error)
                            {
                                mensaje('error', '<b>ERROR</b><br/>Hubo un error en el software: <br/>' + error + '<br/>Por favor contacte al administrador.');
                            }
                        });
                    }
                }
                // Si no, hay un error, deberá mostrarlo
                else
                {
                    mensaje('error', '<b>ERROR</b><br/>Hubo un error en el software: <br/>' + data + '<br/>Por favor contacte al administrador.');
                }
            },
            error: function(error)
            {
                mensaje('error', '<b>ERROR</b><br/>Hubo un error en el software: <br/>' + error + '<br/>Por favor contacte al administrador.');
            }
        });
    }
}
/* Termina código para actualizar las contraseñas */

/* Comienza código para actualizar el perfil */
function actualizarPerfil()
{
    // Primero, preguntamos si está seguro
    Swal.fire(
        {
            title: 'ACTUALIZAR DATOS',
            html: '<h4 style="color: black;">ESTA ACCIÓN ES IRREVERSIBLE</h4><br/>¿Está seguro?',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Sí",
            denyButtonText: "No"
        }).then((result) => 
        {
            // Si sí está seguro
            if (result.isConfirmed) 
            {
                // Verificamos que todos los datos estén llenos
                if 
                (
                    $('#nombre-usuario').val() == null || $('#nombre-usuario').val() == '' ||
                    $('#apellido-usuario').val() == null || $('#apellido-usuario').val() == '' ||
                    $('#fecha-nacimiento').val() == null || $('#fecha-nacimiento').val() == '' ||
                    $('#email-usuario').val() == null || $('#email-usuario').val() == '' ||
                    $('#login-usuario').val() == null || $('#login-usuario').val() == ''
                )
                // No hace falta verificar la foto, habrá un código adicional para ello
                {
                    // Si no están llenos (O sea, si hay campos vacíos)
                    mensaje('error', '<b>ERROR</b><br/>Hay campos vacíos.<br/>Por favor verifique e intente nuevamente.');
                }
                else
                {
                    // Si sí están llenos
                    // Primero, verificamos si login o email existen... Por medio de otro AJAX pero a algo que ya existe
                    var nombre = $('#nombre-usuario').val();
                    var apellido = $('#apellido-usuario').val();
                    var fecha_nacimiento = $('#fecha-nacimiento-usuario').val();
                    var email = $('#email-usuario').val();
                    var login = $('#login-usuario').val();
                    var url_imagen_save = '';
                    $.ajax
                    ({
                        type: 'POST',
                        url: './controller/register-check.php', 
                        data:
                        {
                            register_check: true,
                            login: login,
                            email: email
                        },
                        async: true,
                        success: function(data)
                        {
                            var respuesta = jQuery.parseJSON(data);
                            // Si sí, devuelve errores y ya
                            switch (respuesta.codigo) 
                            {
                                // Si respuesta.codigo es "LOGIN_AND_EMAIL_EXIST"
                                case 'LOGIN_AND_EMAIL_EXIST':
                                    mensaje('error', '<b>ERROR</b><br/>El nombre de usuario y el correo electrónico ya están tomados.<br/>Por favor seleccione otro nombre de usuario y otro correo e inténtelo nuevamente.');
                                    break;
                                // Si respuesta.codigo es "LOGIN_EXISTS"
                                case 'LOGIN_EXISTS':
                                    mensaje('error', '<b>ERROR</b><br/>El nombre de usuario ya está tomado.<br/>Por favor seleccione otro nombre de usuario e inténtelo nuevamente.');
                                    break;
                                // Si respuesta.codigo es "LOGIN_EXISTS"
                                case 'EMAIL_EXISTS':
                                    mensaje('error', '<b>ERROR</b><br/>El correo electrónico ya está tomado.<br/>Por favor seleccione otro correo electrónico e inténtelo nuevamente.');
                                    break;
                                
                                // Si no, podemos proceder
                                case 'REGISTER_VALID':
                                    // Código AJAX para guardar imágenes en el servidor, provisto por ChatGPT
                                    var formData = new FormData();
                                    var file = $('#file-selector')[0].files[0];
                                    if(file)
                                    {
                                        formData.append('file', file);
                                        $.ajax
                                        ({
                                            url: './controller/picture-save.php', // Archivo PHP para manejar la subida de imagenes
                                            type: 'POST',
                                            data: formData,
                                            processData: false,
                                            contentType: false,
                                            success: function(response) 
                                            {
                                                // El AJAX retorna la ruta de la imagen para poder usarla o unos códigos particulares, procésalos
                                                switch (response)
                                                {
                                                    case 'WRONG_FORMAT':
                                                        // No se supone que se llegue hasta aquí por los controles que le tengo al selector
                                                        mensaje('error', '<b>ERROR</b><br/>El formato de la imagen debe ser JPG o PNG.<br/>Por favor seleccione otra imagen e inténtelo nuevamente.');
                                                        url_imagen_save = 'view/uploads/users/user-avatar.png';
                                                        break;
                                                    case 'UPLOAD_ERROR':
                                                        // Error en la subida de archivos
                                                        mensaje('error', '<b>ERROR</b><br/>El formato de la imagen debe ser JPG o PNG.<br/>Por favor seleccione otra imagen e inténtelo nuevamente.');
                                                        url_imagen_save = 'view/uploads/users/user-avatar.png';
                                                        break;
                                                    case 'NO_FILE_RECEIVED':
                                                        // Error en la subida de archivos
                                                        mensaje('error', '<b>ERROR</b><br/>Hubo un error en la subida de archivos.<br/>Por favor inténtelo nuevamente o contacte al desarrollador.');
                                                        url_imagen_save = 'view/uploads/users/user-avatar.png';
                                                        break;
                                                    default:
                                                        url_imagen_save = response;
                                                        break;
                                                }
                                            },
                                            error: function(error) 
                                            {
                                                // Si hay un error, guardaré la ruta a la imagen por defecto
                                                console.log('Error subiendo la imagen: ' + error);
                                                mensaje('error', '<b>ERROR</b><br/>Hubo un error en el programa: <br/>' + error + '.<br/>Por favor contacte al desarrollador.');
                                                url_imagen_save = 'view/uploads/users/user-avatar.png';
                                            }
                                        });
                                    }
                                    // Después llamamos a AJAX nuevamente para guardar los datos
                                    $.ajax
                                    ({
                                        url: './controller/picture-save.php',
                                        type: 'POST',
                                        // Le pasamos como datos todos los formularios, el ID de usuario y la URL resultante de la imagen
                                        data: 
                                        {
                                            update_profile: true,
                                            nombre,
                                            apellido,
                                            fecha_nacimiento,
                                            email,
                                            login,
                                            url_imagen_save,
                                            user_id
                                        },
                                        async: true, 
                                        success: function(data)
                                        {
                                            // Actualizamos el perfil
                                            // Si hubo éxito hacemos mensaje y redirigimos a main
                                            if (data == 'SUCCESS')
                                            {
                                                mensaje('success', '<b>ÉXITO</b><br/>Perfil actualizado satisfactoriamente.');
                                            }
                                            // Si no, hay un error, deberá mostrarlo
                                            else
                                            {
                                                mensaje('error', '<b>ERROR</b><br/>Hubo un error en el software: <br/>' + data + '<br/>Por favor contacte al administrador.');
                                            }
                                        },
                                        error: function(error)
                                        {
                                            mensaje('error', '<b>ERROR</b><br/>Hubo un error en el software: <br/>' + error + '<br/>Por favor contacte al administrador.');
                                        }
                                    });
                                    break;
                                default:
                                    mensaje('error', '<b>ERROR</b><br/>Hubo un error en el software: <br/>' + respuesta.codigo + '<br/>Por favor contacte al administrador.');
                                    break;
                            }
                        },
                    });
                }
            }
            // Si no, no hacemos nada
        });
    
    
}
/* Termina código para actualizar el perfil */