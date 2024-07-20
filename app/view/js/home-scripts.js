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
            var respuesta = JSON.parse(data);
            if (respuesta == null)
            {
                var error = 'Los datos ingresados son inválidos.<br/>Por favor verifique e intente nuevamente';
                // Si la respuesta es null, entonces debe dar un mensaje de error
                mensaje('error', error);
            }
            else
            {
                // Sacamos los datos del JSON resultante
                var nombre = respuesta.nombre;
                var id = respuesta.id;
                var token = respuesta.token;
                // Generamos un mensaje con el nombre
                mensaje('success', 'Bienvenid@, ' + nombre + '<br/>Serás redirigido a la página principal en unos segundos<br/>ID: ' + id + '<br/>Token: ' + token);
                // Pasamos id y token a un formulario POST oculto que luego ejecutamos cuando terminen los 3 segundos del mensaje. Podré incorporar datos en session_start de PHP en este archivo?
            }
        },
        error: function(error)
        {
            mensaje('error', error);
        }
    });
    /* Termina Función de AJAX para validación de datos */
});

$("#register-form").submit(function(e)
{
    e.preventDefault();
    // Validación de datos por AJAX
    // Para estos prototipos no se usará el registro
    popupMensaje('Formulario de registro', 'error', 'Para estos prototipos no se usará el formulario de registro.');
});

// Terminan funciones de login