/* Comienza declaración de variables locales */
/* Termina declaración de variables locales */
var seccionMensajes = $('#seccion-mensajes');
var dbServer = $('#db-server');
var dbName = $('#db-name');
var dbUser = $('#db-user');
var dbPass = $('#db-pass');
var dbSuperLogin = $('#superadmin-login');
var dbSuperPass = $('#superadmin-pass');
var dbSuperPassConfirm = $('#superadmin-pass-confirm');
/* Comienzan Scripts generales */
window.onscroll = () => 
    {
        // Comienza código de añadido de color en el header en la navegación, por JQuery no funciona tan bien
        var header = document.querySelector('header');
        header.classList.toggle('sticky', window.scrollY > 0);
        // Comienza código de añadido de color en el header en la navegación
    };
/* Terminan Scripts generales */

/* Comienzan funciones de SweetAlert */
// Comienza Función de mensaje pequeño
function mensaje(icon, mensaje)
{
    Swal.fire(
        {
            toast: true,
            icon: icon,
            html: mensaje,
            animation: false,
            position: 'bottom-start',
            showConfirmButton: false,
            timer: 3000,
            width: 'auto',
            timerProgressBar: true,
            didOpen: (toast) => 
            {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        }
    );
}
// Termina función de mensaje pequeño
// Comienza función de mensaje grande
function popupMensaje(title, icon, mensaje)
{
    Swal.fire(
        {
            title: title,
            html: mensaje,
            icon: icon,
            confirmButtonText:'OK'
        }
    );
}
// Termina función de mensaje grande
// Comienza función de pregunta
// Usa esta función como modelo pero no la invoques directamente...
function popupPregunta(pregunta)
{
    let respuesta = true;
    Swal.fire(
        {
            title: pregunta,
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Sí",
            denyButtonText: "No"
        }).then((result) => 
        {
            if (result.isConfirmed) 
            {
              respuesta = true;
            } else if (result.isDenied) 
            {
              respuesta = false;
            }
        });
    return respuesta;
}
// Termina función de pregunta
/* Terminan funciones de Sweetalert */

/* Comienzan Scripts específicos */
// Comienza función que muestra un mensaje en la página
// Termina función que muestra un mensaje en la página
// Al cargar la página deberá mostrarme el mensaje de error
// // error puede ser 'JSON_NO_EXISTE', 'JSON_DECODE_ERROR', 'JSON_EXISTE_INCORRECTO', 'DB_CONNECTION_ERROR', 'DB_NO_EXISTE', 'DB_CONNECTION_ERROR' y 'DB_EXISTE_INCORRECTA'
$( document ).ready(function() 
{
    $('#btn-save-file').css('display', 'none');
    seccionMensajes.children().remove();
    // Dependiendo del error se debe llenar la parte de mensajes con uno u otro mensaje.
    switch(error)
    {
        // Si no existe el archivo de JSON
        case 'JSON_NO_EXISTE':
            mostrarMensaje('info', 'bx bxs-info-circle', 'El archivo de configuración no existe.<br/>Por favor inserta los datos de conexión a la base de datos e instala la base de datos.');
            // Deshabilitando el formulario de base de datos
            $('#superadmin-login').attr('disabled', 'disabled');
            $('#superadmin-pass').attr('disabled', 'disabled');
            $('#superadmin-pass-confirm').attr('disabled', 'disabled');
            $('#btn-install-database').prop("disabled", true);
            break;
        // Si existe el JSON pero hay un error decodificando
        case 'JSON_DECODE_ERROR':
            mostrarMensaje('error', 'bx bxs-x-circle', 'Hay un error de decodificación del archivo de configuración.<br/>Por favor contacta al desarrollador.');
            // Deshabilitando todos los formularios y botones, porque esto es un error externo.
            $('#db-server').attr('disabled', 'disabled');
            $('#db-name').attr('disabled', 'disabled');
            $('#db-user').attr('disabled', 'disabled');
            $('#db-pass').attr('disabled', 'disabled');
            $('#btn-test-connection').prop("disabled", true);
            $('#superadmin-login').attr('disabled', 'disabled');
            $('#superadmin-pass').attr('disabled', 'disabled');
            $('#superadmin-pass-confirm').attr('disabled', 'disabled');
            $('#btn-install-database').prop("disabled", true);
            break;
        // Si existe el JSON pero tiene el formato incorrecto
        case 'JSON_EXISTE_INCORRECTO':
            mostrarMensaje('error', 'bx bxs-x-circle', 'El archivo de configuración no tiene el formato apropiado.<br/>Por favor inserta los datos de conexión a la base de datos e instala nuevamente.');
            // Deshabilitando el formulario de base de datos
            $('#superadmin-login').attr('disabled', 'disabled');
            $('#superadmin-pass').attr('disabled', 'disabled');
            $('#superadmin-pass-confirm').attr('disabled', 'disabled');
            $('#btn-install-database').prop("disabled", true);
            // También debo borrar el archivo JSON existente
            break;
        // Si hay un error de conexión a la base de datos
        case 'DB_CONNECTION_ERROR':
            mostrarMensaje('error', 'bx bxs-x-circle', 'Hay un error de conexión a la base de datos.<br/>Por favor contacta al desarrollador.');
            // Deshabilitando todos los formularios y botones, porque esto es un error externo.
            $('#db-server').attr('disabled', 'disabled');
            $('#db-name').attr('disabled', 'disabled');
            $('#db-user').attr('disabled', 'disabled');
            $('#db-pass').attr('disabled', 'disabled');
            $('#btn-test-connection').prop("disabled", true);
            $('#superadmin-login').attr('disabled', 'disabled');
            $('#superadmin-pass').attr('disabled', 'disabled');
            $('#superadmin-pass-confirm').attr('disabled', 'disabled');
            $('#btn-install-database').prop("disabled", true);
            break;
        // Si la base de datos no existe
        case 'DB_NO_EXISTE':
            mostrarMensaje('error', 'bx bxs-x-circle', 'La base de datos no existe.<br/>Por favor créala desde tu servidor y/o contacta al desarrollador.');
            // Jalando los datos JSON al formulario de JSON
            // Deshabilitando el formulario de base de datos
            $('#superadmin-login').attr('disabled', 'disabled');
            $('#superadmin-pass').attr('disabled', 'disabled');
            $('#superadmin-pass-confirm').attr('disabled', 'disabled');
            $('#btn-install-database').prop("disabled", true);
            break;
        // Si la base de datos existe pero tiene la estructura incorrecta
        case 'DB_EXISTE_INCORRECTA':
            mostrarMensaje('info', 'bx bxs-info-circle', 'El archivo de configuración no existe.<br/>Por favor inserta los datos de conexión a la base de datos e instala la base de datos.');
            // Jalando los datos JSON al formulario de JSON
            // Deshabilitando el formulario de base de datos
            $('#superadmin-login').attr('disabled', 'disabled');
            $('#superadmin-pass').attr('disabled', 'disabled');
            $('#superadmin-pass-confirm').attr('disabled', 'disabled');
            $('#btn-install-database').prop("disabled", true);
            break;
        // Si llega cualquier otro tipo de valor. No se supone que se llegue a esta instancia.
        default:
            mostrarMensaje('info', 'bx bxs-info-circle', 'Parámetro inicial no reconocido.<br/>Por favor, contacta a tu desarrollador');
            // Deshabilitando todos los formularios y botones, porque esto es un error externo.
            $('#db-server').attr('disabled', 'disabled');
            $('#db-name').attr('disabled', 'disabled');
            $('#db-user').attr('disabled', 'disabled');
            $('#db-pass').attr('disabled', 'disabled');
            $('#btn-test-connection').prop("disabled", true);
            $('#superadmin-login').attr('disabled', 'disabled');
            $('#superadmin-pass').attr('disabled', 'disabled');
            $('#superadmin-pass-confirm').attr('disabled', 'disabled');
            $('#btn-install-database').prop("disabled", true);
            break;
    }
});

// Comienza función que muestra mensajes en la sección de mensajes de la página
// 'info', 'bx bxs-info-circle'
// 'error', 'bx bxs-x-circle'
// 'exito', 'bx bxs-check-circle'
function mostrarMensaje(mode, icon, mensaje)
{
    // Primero, borramos todo
    var htmlMensaje = 
    '<div class=\"col w100 mensaje ' + mode + '\"><div class=\"mensaje-interno\"><div class=\"icono\"><i class=\'' + icon + '\'></i></div><div class=\"texto\"><p>' + mensaje + '</p></div></div></div>';
    seccionMensajes.html(htmlMensaje);
}
// Termina función que muestra mensajes en la sección de mensajes de la página

// Comienza función que verifica que los campos vacíos de JSON necesarios estén llenos
function camposJsonVacios()
{
    // El campo contraseña puede estar vacío porque este software también puede ejecutarse en phpmyadmin
    return (dbServer.val() == "" || dbName.val() == "" || dbUser.val() == "");
}
// Termina función que verifica que los campos vacíos de JSON necesarios estén llenos

// Comienza función que verifica que los campos de base de datos necesarios estén llenos
function camposDBVacios()
{
    // El campo contraseña puede estar vacío porque este software también puede ejecutarse en phpmyadmin
    return (dbSuperLogin.val() == "" || dbSuperPass.val() == "" || dbSuperPassConfirm.val() == "");
}
// Termina función que verifica que los campos de base de datos necesarios estén llenos

// Comienza función de botón de probar base de datos
$('#btn-test-connection').on( "click", function() 
{
    // Si hay valores vacíos en host, nombre de base de datos, usuario y contraseña,
    if (camposJsonVacios())
    {
        // Rebotemos un error
        mensaje('error', '<b>Error</b>: Hay campos vacíos.<br/>Por favor revise los datos ingresados e intente nuevamente');
    }
    else
    {
        // Mandamos los datos a AJAX a un archivo
        $.ajax
        ({
            type: 'POST',
            url: './controller/installer-functions.php',
            data:
            {
                // El archivo recibe los datos
                check_connection: true,
                server: dbServer.val(),
                name: dbName.val(),
                user: dbUser.val(),
                pass: dbPass.val()
            },
            async: true,
            success: function(data)
            {
                // El archivo verifica que esos datos sí sean correctos
                // El archivo devuelve un código que indique si sí o si no
                console.log(data)
                if (data == "EXITO")
                {
                    // Si sí, habilita el botón de instalar JSON y deshabilita el botón de probar conexión
                    $('#btn-test-connection').prop("disabled", true);
                    $('#btn-test-connection').css('display', 'none');
                    $('#btn-save-file').prop("disabled", false);
                    $('#btn-save-file').css('display', 'block');
                    // Deshabilitamos los formularios para que no haya errores luego
                    $('#db-server').attr('disabled', 'disabled');
                    $('#db-name').attr('disabled', 'disabled');
                    $('#db-user').attr('disabled', 'disabled');
                    $('#db-pass').attr('disabled', 'disabled');
                    // Mandamos mensajes de éxito
                    mostrarMensaje('exito', 'bx bxs-check-circle', '<b>ÉXITO</b><br/>Datos viables. Puede instalar la conexión.');
                    mensaje('success', '<b>ÉXITO</b><br/>Datos viables. Puede instalar la conexión.');
                }
                else
                {
                    // Si no, mensaje informativo
                    mensaje('error', '<b>ERROR</b><br/>Datos erróneos. Por favor verifique los datos<br/>e intente nuevamente o contacte al desarrollador.')
                }
            },
            error: function(error)
            {
                // Si falla el AJAX, entonces ponga un mensaje en el sistema
                console.log(error)
                mensaje('error', '<b>ERROR</b><br/>Error de comunicación con el servidor:<br/>' + error + '<br/>Por favor contacte al desarrollador.');
            }
        });
        // popupMensaje('Datos', 'success', texto);
        // $('#btn-save-file').css('display', 'block');
    }
});
// Termina función de botón de probar conexión

// Comienza función de guardado de JSON
$('#btn-save-file').on( "click", function() 
{
    // Está seguro el usuario de hacer esto?
    Swal.fire(
        {
            title: 'Datos de configuración',
            html: '<b>Servidor</b>: \"' + dbServer.val() + '\"<br/>' + '<b>Base de datos</b>: \"' + dbName.val() + '\"<br/>' + '<b>Usuario</b>: \"' + dbUser.val() + '\"<br/>' + '<b>Contraseña</b>: \"' + dbPass.val() + '\"<br/>' + '<b>ESTA ACCIÓN ES IRREVERSIBLE</b><br/>¿Está seguro de continuar?',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Sí",
            denyButtonText: "No"
        }).then((result) => 
        {
            if (result.isConfirmed) 
            {
                // Llamamos a AJAX
                $.ajax
                ({
                    type: 'POST',
                    // Invocamos installer-functions.php
                    url: './controller/installer-functions.php',
                    // El archivo recibe los datos
                    data:
                    {
                        // El archivo recibe los datos
                        install_json: true,
                        server: dbServer.val(),
                        name: dbName.val(),
                        user: dbUser.val(),
                        pass: dbPass.val()
                    },
                    async: true,
                    success: function(data)
                    {
                        // El archivo genera el JSON y lo guarda en su sitio correspondiente.
                        // El archivo retorna un código que indique si sí lo logró o si no lo logró
                        if (data == "EXITO")
                        {
                            // Si sí lo logró, deshabilita el botón de generar JSON, habilita el formulario de base de datos
                            $('#btn-save-file').prop("disabled", true);
                            // Deshabilitamos los formularios para que no haya errores luego
                            $('#superadmin-login').attr('disabled', false);
                            $('#superadmin-pass').attr('disabled', false);
                            $('#superadmin-pass-confirm').attr('disabled', false);
                            $('#btn-install-database').prop("disabled", false);
                            // Mandamos mensajes de éxito
                            mostrarMensaje('exito', 'bx bxs-check-circle', '<b>ÉXITO</b><br/>Archivo de configuración guardado. Puede iniciar la instalación de la base de datos.');
                            mensaje('success', '<b>ÉXITO</b><br/>Archivo de configuración guardado.<br/>Puede iniciar la instalación de la base de datos.');
                        }
                        else
                        {
                            // Si no lo logró, genera un mensaje de error avisando que hubo un error.
                            mensaje('error', '<b>ERROR</b><br/>Datos erróneos. Por favor verifique los datos<br/>e intente nuevamente o contacte al desarrollador.')
                        }
                    },
                    error: function(error)
                    {
                        // Si falla el AJAX, entonces ponga un mensaje en el sistema
                        mensaje('error', '<b>ERROR</b><br/>Error de comunicación con el servidor:<br/>' + error + '<br/>Por favor contacte al desarrollador.');
                    }
                });
            } 
            else if (result.isDenied) 
            {
              // Mensaje de error
              mensaje('error', '<b>DATOS NO INSTALADOS</b><br/>Por favor verifique su información e intente nuevamente.')
            }
        });    
});
// Termina función de guardado de JSON

// Comienza función de instalación de base de datos
$('#btn-install-database').on( "click", function()
{
    // Primero, tenemos que verificar si hay campos vacíos aquí
    if (camposDBVacios())
    {
        // Si hay campos vacíos, mostramos un mensaje de error
        mensaje('error', '<b>Error</b>: Hay campos vacíos.<br/>Por favor revise los datos ingresados e intente nuevamente');
    }
    else
    {
        // Si no están vacíos podemos operar
        // Si no son iguales las contraseñas
        if (dbSuperPass.val() != dbSuperPassConfirm.val())
        {
            // Emitimos un mensaje de error
            mensaje('error', '<b>Error</b>: Las contraseñas no son iguales.<br/>Por favor revise los datos ingresados e intente nuevamente');
        }
        // Si las contraseñas sí son iguales
        else
        {
            var login = dbSuperLogin.val();
            var pass = dbSuperPass.val();
            // Debemos preguntar si está seguro.
            Swal.fire(
            {
                title: 'Datos iniciales - Base de datos',
                html: '<b>Base de datos</b>: \"' + dbName.val() + '\"<br/><b>Usuario Administrador</b>: \"' + dbSuperLogin.val() + '\"<br/><b>Contraseña</b>: <i>OCULTA POR SEGURIDAD</i><br/><b>¡EL CONTENIDO DE ESTA BASE DE DATOS SERÁ BORRADO!</b><br/><b>ESTA ACCIÓN ES IRREVERSIBLE</b><br/>¿Está seguro de continuar?',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "Sí",
                denyButtonText: "No"
            }).then((result) => 
            {
                if (result.isConfirmed) 
                {
                    // Llamamos a AJAX
                    $.ajax
                    ({
                        type: 'POST',
                        // Invocamos installer-functions.php
                        url: './controller/installer-functions.php',
                        // El archivo recibe los datos
                        data:
                        {
                            install_dbase: true,
                            user: login,
                            pass: pass
                        },
                        async: true,
                        success: function(data)
                        {
                            // El archivo lee el SQL completo y genera las tablas y las conexiones entre ellas.
                            // Luego, el archivo usa los datos recibidos para generar la estructura completa, y el usuario súper administrador, y agregar todo a la base de datos.
                            // El archivo retorna un código que indique si sí lo logró o si no lo logró
                            if (data == "EXITO")
                            {
                                // Si sí lo logró
                                // Deshabilita todos los campos y botones
                                $('#db-server').attr('disabled', 'disabled');
                                $('#db-name').attr('disabled', 'disabled');
                                $('#db-user').attr('disabled', 'disabled');
                                $('#db-pass').attr('disabled', 'disabled');
                                $('#btn-test-connection').prop("disabled", true);
                                $('#btn-save-file').prop("disabled", true);
                                $('#superadmin-login').attr('disabled', 'disabled');
                                $('#superadmin-pass').attr('disabled', 'disabled');
                                $('#superadmin-pass-confirm').attr('disabled', 'disabled');
                                $('#btn-install-database').prop("disabled", true);
                                // Mensaje de éxito
                                mensaje('success', '<b>EXITO</b><br/>Base de datos instalada.<br/>Será enviado a la página principal dentro de tres segundos.');
                                // Reenviamos a la página principal
                                setTimeout(function() 
                                { 
                                    window.location.href = "../";
                                }, 3000);
                            }
                            else
                            {
                                // Si no lo logró, genera un mensaje de error avisando que hubo un error.
                                mensaje('error', '<b>ERROR</b><br/>Base de datos no instalada. Por favor verifique los datos<br/>e intente nuevamente o contacte al desarrollador.');
                            }
                        },
                        error: function(error)
                        {
                            // Si falla el AJAX, entonces ponga un mensaje en el sistema
                            mensaje('error', '<b>ERROR</b><br/>Error de comunicación con el servidor:<br/>' + error + '<br/>Por favor contacte al desarrollador.');
                        }
                    });
                } 
                else if (result.isDenied) 
                {
                    // Mensaje de error
                    mensaje('error', '<b>DATOS NO INSTALADOS</b><br/>Por favor verifique su información e intente nuevamente.')
                }
            });
        }
    }
});
// Termina función de instalación de base de datos
/* Terminan Scripts específicos */