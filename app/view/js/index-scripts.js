// Comienza definición de variables
const loginPopup = $('.login-popup');
const registerLink = $('.register-link');
const loginLink = $('.login-link');
const btnPopup = $('.btnLogin');
const closeIcon = $('.icon-close');
let sections = document.querySelectorAll('.section');
let navLinks = document.querySelectorAll('header nav a');
// Termina definición de variables

// Comienzan efectos de navegación vertical
window.onscroll = () => 
{
    // Comienza control para que los links del menú de navegación queden activos en cada sección
    sections.forEach(section => 
    {
        let top = window.scrollY;
        let offset = section.offsetTop - 150;
        let height = section.offsetHeight;
        let id = section.getAttribute('id');
        if (top >= offset && top < offset + height)
        {
            navLinks.forEach(link => 
            {
                link.classList.remove('nav-active');
                document.querySelector('header nav a[href*=' + id + ']').classList.add('nav-active');
            });
        }
    });
    // Termina control para que los links del menú de navegación queden activos en cada sección
    // Comienza código de añadido de color en el header en la navegación
    var header = document.querySelector('header');
    header.classList.toggle('sticky', window.scrollY > 0);
    // Comienza código de añadido de color en el header en la navegación
};
// Terminan efectos de navegación vertical

// Comienzan funciones de Sweetalert
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
            timer: 5000,
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
// Terminan funciones de Sweetalert

// Comienza cambio entre el formulario de login y el de registro
registerLink.on("click", function()
{
    loginPopup.addClass("active");
});

loginLink.on("click", function()
{
    loginPopup.removeClass("active");
});
// Termina cambio entre el formulario de login y el de registro

// Comienza control para mostrar o guardar el popup
btnPopup.on("click", function()
{
    loginPopup.addClass("active-popup");
});

closeIcon.on("click", function()
{
    loginPopup.removeClass("active-popup");
    loginPopup.removeClass("active");
});
// Termina control para mostrar o guardar el popup

// Comienzan funciones de login y acceso
// Comienzan funciones de login
$("#login-form").submit(function(e)
{
    // Que el formulario no cargue ninguna página automáticamente
    e.preventDefault();
    // Jalamos los datos del formulario
    var user = $('#login-user').val();
    var pass = $('#login-pass').val();
    /* Función de AJAX para validación de datos */
    $.ajax
    ({
        type: 'POST', // Type es el tipo de solicitud
        url: './app/controller/login-check.php',
        data: 
        {
            login_check: true,
            user: user,
            pass: pass
        },
        /* contentType: 'application/json', // contentType es el tipo de contenido deseado */
        async: true, // async indica si se desea que los datos sean asíncronos. true viene por defecto pero es mejor especificarlo
        success: function(data)
        {
            // Como los datos vienen en JSON, hay que hacer parse para descifrarlos
            var datos = jQuery.parseJSON(data);
            // Sacamos el código de respuesta
            var codigo = datos.codigo;
            // Si el código de la respuesta es de usuario inválido
            if (codigo == 'USER_INCORRECT')
            {
                // Preparamos mensaje de error
                var error = 'Nombre de usuario incorrecto. Por favor verifique e intente nuevamente';
                mensaje('error', error);
            }
            // Si el código de la respuesta es de contraseña inválida
            else if (codigo == 'PASS_INCORRECT')
            {
                // Preparamos mensaje de error
                var error = 'Contraseña incorrecta. Por favor verifique e intente nuevamente';
                mensaje('error', error);
            }
            // De otra manera es éxito
            else
            {
                // Sacamos los datos del JSON resultante
                var nombre = datos.nombre;
                // Generamos un mensaje con el nombre
                mensaje('success', 'Bienvenid@, ' + nombre + '<br/>Serás redirigido a la página principal en unos segundos.');
                // Pasamos id y token a un formulario POST oculto que luego ejecutamos cuando terminen los 3 segundos del mensaje. No hace falta incorporar SESSION en este archivo, la sesión la maneja el archivo de PHP que invocamos por AJAX aquí.
                setTimeout(function() 
                { 
                    window.location.href = "./app/index.php?page=main";
                }, 5000);
            }
        },
        error: function(error)
        {
            mensaje('error', error);
        }
    });
    /* Termina Función de AJAX para validación de datos */
});
// Terminan funciones de login

// Comienzan funciones de registro
$("#register-form").submit(function(e)
{
    // Que el formulario no cargue ninguna página automáticamente
    e.preventDefault();
    // Jalamos los datos del formulario
    var login = $('#register-login').val();
    var email = $('#register-email').val();
    var accept = $('#register-accept');
    // Primero, verifiquemos si el checkbox de términos y condiciones es aceptado
    if (accept.prop('checked'))
    {
        $.ajax
        ({
            type: 'POST',
            // El verificador de registro lo haré aislado porque la vez pasada me dejó de hacer login porque todo lo cargué en el mismo archivo
            url: './app/controller/register-check.php', 
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
                    case 'REGISTER_VALID':
                        mensaje('success', '<b>EXITO</b><br/>Nombre de usuario y correo válidos para registrar. <br/>Será redirigido a la página de registro completo en unos segundos.');
                        setTimeout(function() 
                        {
                            // Generamos un segundo formulario oculto para transferir
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = './app/register.php';
                            const loginField = document.createElement('input');
                            loginField.type = 'hidden';
                            loginField.name = 'login';
                            loginField.value = login;
                            form.appendChild(loginField);
                            const emailField = document.createElement('input');
                            emailField.type = 'hidden';
                            emailField.name = 'email';
                            emailField.value = email;
                            form.appendChild(emailField);
                            document.body.appendChild(form);
                            form.submit();
                            // window.location.href = "./app/register.php";
                        }, 5000);
                        break;
                    default:
                        break;
                }
                /* // Si respuesta.codigo es "LOGIN_AND_EMAIL_EXIST"
                
                if (respuesta.codigo == 'LOGIN_AND_EMAIL_EXIST')
                {
                    mensaje('error', '<b>ERROR</b><br/>El nombre de usuario y el correo electrónico ya están tomados.<br/>Por favor seleccione otro nombre de usuario y otro correo e inténtelo nuevamente.');
                }
                // Si respuesta.codigo es "LOGIN_EXISTS"
                else if (respuesta.codigo == 'LOGIN_EXISTS')
                {
                    mensaje('error', '<b>ERROR</b><br/>El nombre de usuario ya está tomado.<br/>Por favor seleccione otro nombre de usuario e inténtelo nuevamente.');
                }
                // Si respuesta.codigo es "EMAIL_EXISTS"
                else if (respuesta.codigo == 'EMAIL_EXISTS')
                {
                    mensaje('error', '<b>ERROR</b><br/>El correo electrónico ya está tomado.<br/>Por favor seleccione otro correo electrónico e inténtelo nuevamente.');
                }
                else */
            },
            error: function(error)
            {
                mensaje('error', error);
            }
        });
    }
    else
    {
        mensaje('error', '<b>ERROR</b><br/>No ha aceptado los términos y condiciones.<br/>Por favor acéptelos e inténtelo nuevamente');
    }
});
// Terminan funciones de registro