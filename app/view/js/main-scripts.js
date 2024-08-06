/* Comienza función que prepara la herramienta de lectura */
$(document).ready(function()
{
    $('.read-overlay').hide();
});
/* Termina función que prepara la herramienta de lectura */

/* Comienza función para cerrar la herramienta de lectura */
$('#close-read-tool').on('click', function()
{
    $('.read-overlay').removeClass('active');
    $('.read-overlay').fadeOut("slow");
    $('.read-space').fadeOut("slow");
    $('.read-space').removeClass('dark');
    $('.toggle').removeClass('pushed');
});
/* Termina función para cerrar la herramienta de lectura */

/* Comienza función para abrir la herramienta de lectura */
function activarHerramienta()
{
    // Aquí tengo que jalar los datos de la sección deseada por AJAX. Por lo tanto, esta función tendrá parámetros
    $('.read-overlay').addClass('active');
    $('.read-overlay').fadeIn("slow");
    $('.read-space').fadeIn("slow");
    $('.read-space').removeClass('dark');
    $('.toggle').removeClass('pushed');
}
/* Termina función para abrir la herramienta de lectura */

/* Comienza función para activar el modo oscuro */
$('#dark-toggle').on('click', function()
{
    $('.read-space').toggleClass('dark');
    $('.toggle').toggleClass('pushed');
});