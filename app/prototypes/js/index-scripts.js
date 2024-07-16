// Comienza definición de variables
const loginPopup = $('.login-popup');
const registerLink = $('.register-link');
const loginLink = $('.login-link');
const btnPopup = $('.btnLogin');
const closeIcon = $('.icon-close');
// Termina definición de variables

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

// Prueba del sweetalert
Swal.fire(
    {
        title: '¡Página de muestra!',
        html: 'Para probar el acceso, <br/>intente usar las credenciales:<br/><b>admin&nbsp;&nbsp;&nbsp;&nbsp;admin<br/>david&nbsp;&nbsp;&nbsp;&nbsp;david<br/></b>También pruebe usar cualquier otra credencial.',
        icon: 'success',
        confirmButtonText: 'Aceptar'
    });

// Comienzan funciones de login y acceso
// Comienzan funciones de login
$("#login-form").submit(function(e)
{
    // Que el formulario no cargue ninguna página automáticamente
    e.preventDefault();
    // Jalamos los datos del formulario
    var user = document.getElementById('login-user').value;
    var pass = document.getElementById('login-pass').value;
    // Función de AJAX para validación de datos
    // Función temporal para prototipos: Un if sencillo
    // Si los datos son correctos da mensaje de éxito
    if (user == "admin" && pass == "admin")
    {
        // Primero, que cargue el mensaje. Dura 3000 ms
        mensaje('success', 'Bienvenido, ADMIN. Serás redirigido a la página principal.');
        // Segundo, que redirija a la página de admin luego de 3000 ms. Esto sucede con setTimeOut
        
        //let espera = setInterval(()=>{console.log(Date())},500)
        
        setTimeout(function() 
        { 
            //clearInterval(espera)
            window.location.href = "admin-page.html";
        }, 3000);
    }
    else if (user == 'david' && pass == 'david')
    {
        // Primero, que cargue el mensaje. Dura 3000 ms
        mensaje('success', 'Bienvenido, David. Serás redirigido a tu página principal.');
        // Segundo, que redirija a la página de admin luego de 3000 ms. Esto sucede con setTimeOut
        setTimeout(function() 
        { 
            window.location.href = "user-page.html";
        }, 3000);
    }
    // Si no, da mensaje de error  
    else
    {
        mensaje('error', 'Datos de acceso incorrectos, intente nuevamente.');
    }
});

$("#register-form").submit(function(e)
{
    e.preventDefault();
    // Validación de datos por AJAX
    // Para estos prototipos no se usará el registro
    popupMensaje('Formulario de registro', 'error', 'Para estos prototipos no se usará el formulario de registro.');
});


// Terminan funciones de login
// Comienzan funciones de acceso
// Terminan funciones de acceso
// Terminan funciones de login y acceso