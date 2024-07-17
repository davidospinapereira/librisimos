/* Comienzan Scripts generales */
window.onscroll = () => 
    {
        // Comienza código de añadido de color en el header en la navegación, por JQuery no funciona tan bien
        var header = document.querySelector('header');
        header.classList.toggle('sticky', window.scrollY > 0);
        // Comienza código de añadido de color en el header en la navegación
    };
/* Terminan Scripts generales */

/* Comienzan Scripts específicos */
/* Terminan Scripts específicos */

/* Comienzan funciones de SweetAlert */
// Comienza Función de mensaje pequeño
function mensaje(icon, mensaje)
{
    Swal.fire(
        {
            toast: true,
            icon: icon,
            text: mensaje,
            animation: false,
            position: 'bottom-start',
            showConfirmButton: false,
            timer: 3000,
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
            text: mensaje,
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