/* Comienza declaración de variables locales */
/* Termina declaración de variables locales */
var seccionMensajes = $('#seccion-mensajes');
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
//Termina función que muestra mensajes en la sección de mensajes de la página
/* Terminan Scripts específicos */