/* Declaración de variables */
var profileWrap = $('.profile-wrap');
var picsetter = $('#foto');
/* Termina declaración de variables */

/* Comienza código para abrir y cerrar el menú de perfil */
$('.user-pic').on('click', function()
{
    profileWrap.toggleClass('open-menu');
});
/* Termina código para abrir y cerrar el menú de perfil */

/* Comienza código para poner fotos en el selector de imagen */
jQuery(window).on('load', function()
{
    picsetter.css('background-image', "url('../img/user-avatar.jpg')");
});

jQuery('#file-selector').change(function () 
{
    var file = this.files[0];
    var reader = new FileReader();
    console.log(reader.result);
    reader.onloadend = function ()
    {
        picsetter.css('background-image', 'url("' + reader.result + '")');
    }
    if (file)
    {
        reader.readAsDataURL(file);
    }
    else
    {}
});
/* Termina código para poner fotos en el selector de imagen */