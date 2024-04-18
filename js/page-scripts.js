/* Declaración de variables */
var profileWrap = $('.profile-wrap');
/* Termina declaración de variables */

/* Comienza código para abrir y cerrar el menú de perfil */
$('.user-pic').on('click', function()
{
    profileWrap.toggleClass('open-menu');
});
/* Termina código para abrir y cerrar el menú de perfil */
