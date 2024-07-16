let sections = document.querySelectorAll('.section');
let navLinks = document.querySelectorAll('header nav a');
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
// Terminan funciones de Sweetalert