/* Comienza función para cerrar la herramienta de lectura */
$('#close-read-tool').on('click', function()
{
    /* $('.read-overlay').fadeOut(1000); */
    $('.read-overlay').toggleClass('active');
    $('.read-space').removeClass('dark');
    $('.toggle').removeClass('pushed');
});
/* Termina función para cerrar la herramienta de lectura */

/* Comienza función para abrir la herramienta de lectura */
function activarHerramienta()
{
    // Aquí tengo que jalar los datos de la sección deseada por AJAX. Por lo tanto, esta función tendrá parámetros
    $('.read-overlay').toggleClass('active');
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