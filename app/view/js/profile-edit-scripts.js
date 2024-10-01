var picsetter = $('#foto');
var user_id = $('#user_id_hidden').val();
var url_imagen = $('#url_imagen_hidden').val();

/* Comienza código que se carga cuando la página ha cargado completamente */
// Carga de imagen de perfil en vista previa
jQuery(window).on('load', function()
{
    // Si la url de la imagen no está definida
    if (url_imagen == null || url_imagen == '')
    {
        url_imagen = 'view/uploads/users/user-avatar.png';
        picsetter.css('background-image', 'url("./view/uploads/users/user-avatar.png")');
    }
    else
    {
        picsetter.css('background-image', 'url("./' + url_imagen + '")');
    }
});
/* Comienza código que se carga cuando la página ha cargado completamente */

/* Comienzan códigos para activar funciones con botones */
// Actualizar perfil
$('#update-data').on('click', function()
{
    actualizarPerfil();
});

// Cambiar contraseña
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

// Eliminar cuenta
$('#delete-account').on('click', function()
{
    eliminarCuenta();
});
/* Terminan códigos para activar funciones con botones */

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
    // Antes que nada, tomemos variables
    var nombre = $('#nombre-usuario').val();
    var apellido = $('#apellido-usuario').val();
    var fecha = $('#fecha-nacimiento').val();
    var email = $('#email-usuario').val();
    var login = $('#login-usuario').val();
    var url_imagen_save = '';
    // Primero, debemos verificar si hay campos vacíos
    if 
    (
        nombre == null || nombre == '' ||
        apellido == null || apellido == '' ||
        fecha == null || fecha == '' ||
        email == null || email == '' ||
        login == null || login == ''
    )
    // No hace falta verificar la foto, hay código para que nunca esté vacío ese campo
    {
        // Si hay campos vacíos
        mensaje('error', '<b>ERROR</b><br/>Hay campos vacíos.<br/>Por favor verifique e intente nuevamente.');
    }
    else
    {
        // Si todos los campos están llenos
        // Debemos verificar si el nombre de login o el correo de login existen Y SON DE OTRO USUARIO
        // Para eso llamamos un AJAX
        $.ajax
        ({
            type: 'POST',
            url: './controller/ajax-user-crud.php',
            data:
            {
                check_login_profile: true,
                email,
                login,
                user_id
            },
            async: true,
            success: function(data)
            {
                // Debe generar un mensaje de éxito o unos de error
                if (data = 'DATA_VALID')
                {
                    // Si los datos son válidos podemos hacer el siguiente código
                    // Tercero, debemos preguntar si se está seguro
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
                        // Si se dice que sí, comenzamos el proceso
                        // Primero, el código AJAX de ChatGPT para subir imágenes al servidor
                        var formData = new FormData();
                        var user_file = $('#file-selector')[0].files[0];
                        if(user_file)
                        {
                            formData.append('user_file', user_file);
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
                                            url_imagen_save = 'view/uploads/users/user-avatar.png';
                                            break;
                                        case 'UPLOAD_ERROR':
                                            // Error en la subida de archivos
                                            mensaje('error', '<b>ERROR</b><br/>Hubo un error en la subida de archivos.<br/>Por favor seleccione otra imagen e inténtelo nuevamente.');
                                            url_imagen_save = 'view/uploads/users/user-avatar.png';
                                            break;
                                        case 'NO_FILE_RECEIVED':
                                            // Error en la subida de archivos
                                            mensaje('error', '<b>ERROR</b><br/>No se recibió un archivo.<br/><br/>Por ahora, la imagen será reemplazada por un avatar genérico.Por favor inténtelo nuevamente o contacte al desarrollador.');
                                            url_imagen_save = 'view/uploads/users/user-avatar.png';
                                            break;
                                        default:
                                            url_imagen_save = data;
                                            // Segundo, el código AJAX para actualizar el perfil
                                            $.ajax
                                            ({
                                                type: 'POST',
                                                url: './controller/ajax-user-crud.php',
                                                data:
                                                {
                                                    update_profile: true,
                                                    nombre,
                                                    apellido,
                                                    fecha,
                                                    email,
                                                    login,
                                                    url_imagen_save,
                                                    user_id
                                                },
                                                async: true,
                                                success: function(data)
                                                {
                                                    // El código retorna un mensaje de éxito o uno de fracaso
                                                    // Si el mensaje es éxito, avisamos y redirigimos a main
                                                    // Actualizamos el perfil
                                                    // Si hubo éxito hacemos mensaje y redirigimos a main
                                                    if (data == 'SUCCESS')
                                                    {
                                                        mensaje('success', '<b>ÉXITO</b><br/>Perfil actualizado satisfactoriamente.<br/>Será redirigido a la página principal ahora.');
                                                        setTimeout(function()
                                                        {
                                                            window.location.href = "index.php?page=main";
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
                                                    mensaje('error', '<b>ERROR</b><br/>Hubo un error en la actualización de perfil:<br/>' + error + '<br/>por favor contacte al administrador');
                                                }
                                            });  
                                            break;
                                    }
                                },
                                error: function(error) 
                                {
                                    // Si hay un error, guardaré la ruta a la imagen por defecto
                                    mensaje('error', '<b>ERROR</b><br/>Hubo un error en el programa: <br/>' + error + '.<br/>Por ahora, la imagen será reemplazada por un avatar genérico.<br/>Por favor contacte al desarrollador.');
                                    url_imagen_save = 'view/uploads/users/user-avatar.png';
                                }
                            });
                             
                        }
                        else
                        {
                            // Si no hay archivo, es que no se seleccionó ningún archivo de imagen. Entonces, repetimos el AJAX pero con el dato genérico
                            $.ajax
                            ({
                                type: 'POST',
                                url: './controller/ajax-user-crud.php',
                                data:
                                {
                                    update_profile: true,
                                    nombre,
                                    apellido,
                                    fecha,
                                    email,
                                    login,
                                    url_imagen_save: url_imagen,
                                    user_id
                                },
                                async: true,
                                success: function(data)
                                {
                                    // El código retorna un mensaje de éxito o uno de fracaso
                                    // Si el mensaje es éxito, avisamos y redirigimos a main
                                    // Actualizamos el perfil
                                    // Si hubo éxito hacemos mensaje y redirigimos a main
                                    if (data == 'SUCCESS')
                                    {
                                        mensaje('success', '<b>ÉXITO</b><br/>Perfil actualizado satisfactoriamente.<br/>Será redirigido a la página principal ahora.');
                                        setTimeout(function()
                                        {
                                            window.location.href = "index.php?page=main";
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
                                    mensaje('error', '<b>ERROR</b><br/>Hubo un error en la actualización de perfil:<br/>' + error + '<br/>por favor contacte al administrador');
                                }
                            });  
                        }
                    });
                    // Si se dice que no, que no haga nada
                }
                else if (data = 'LOGIN_INVALID')
                {
                    // Si el login es inválido
                    mensaje('error', '<b>ERROR</b><br/>El nombre de usuario ya está tomado. Por favor verifique la información ingresada<br/>e intente nuevamente.');
                }
                else if (data = 'EMAIL_INVALID')
                {
                    // Si el correo es inválido
                    mensaje('error', '<b>ERROR</b><br/>El correo electrónico ya está tomado. Por favor verifique la información ingresada<br/e intente nuevamente.');
                }
                else if (data = 'DATA_INVALID')
                {
                    // Si tanto login como correo son inválidos
                    mensaje('error', '<b>ERROR</b><br/>El correo electrónico y el nombre de usuario ya están tomados. Por favor verifique la información ingresada<br/e intente nuevamente.');
                }
                else
                {
                    // Si el mensaje generado es cualquier otra cosa
                    mensaje('error', '<b>ERROR</b><br/>Hubo un error verificando el nombre de login y el correo electrónico<br/>' + data + '<br/>por favor contacte al administrador');
                }
            },
            error: function(error) 
            {
                // Si hay un error, debemos generarlo
                mensaje('error', '<b>ERROR</b><br/>Hubo un error verificando el nombre de login y el correo electrónico<br/>' + error + '<br/>por favor contacte al administrador');
            }
        });
    }
}
/* Termina código para actualizar el perfil */

/* Comienza código para borrar el perfil */
function eliminarCuenta()
{
    // Se tiene que comenzar con la pregunta
    Swal.fire(
    {
        title: 'ELIMINAR CUENTA',
        html: '<h4 style="color: black;">ESTA ACCIÓN ES IRREVERSIBLE</h4><br/>¿Está seguro?',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Sí",
        denyButtonText: "No"
    }).then((result) => 
    {
        // Si se dice que sí se carga un AJAX
        $.ajax
        ({
            type: 'POST',
            url: './controller/ajax-user-crud.php',
            data:
            {
                delete_profile: true,
                user_id
            },
            async: true,
            success: function(data)
            {
                // Si se tiene éxito
                if (data == 'SUCCESS')
                {
                    // Se manda un mensaje y se envía a logout, que cerrará la sesión y mandará a index externo.
                    mensaje('info', '<b>CUENTA ELIMINADA</b><br/>Lamentamos que te vayas.<br/>Serás dirigido a la página principal.');
                    setTimeout(function()
                    {
                        window.location.href = "./controller/logout.php";
                    }, 5000);
                }
                // Si no se tiene éxito
                else
                {
                    mensaje('error', '<b>ERROR</b><br/>Hubo un error en el programa: <br/>' + data + '<br/>Por favor contacta al administrador o al desarrollador.');
                }
                // Se manda la respuesta como mensaje de error
            },
            error: function(error)
            {
                // Si hay un error
                // Se manda la respuesta como mensaje de error
                mensaje('error', '<b>ERROR</b><br/>Hubo un error en el programa: <br/>' + error + '<br/>Por favor contacta al administrador o al desarrollador.');
            }
        });
    });    
}
/* Termina código para borrar el perfil */