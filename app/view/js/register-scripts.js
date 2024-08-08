var picsetter = $('#foto');

/* Comienza código para poner fotos en el selector de imagen */
jQuery(window).on('load', function()
{
    // La URL la debo preparar con una función que jale por AJAX el texto de la base de datos
    picsetter.css('background-image', 'url(./view/img/user-avatar.jpg)');
});

jQuery('#file-selector').change(function () 
{
    // Aquí agregamos el código de ChatGPT
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