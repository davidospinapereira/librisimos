/* Comienza definición de variables */
var book_id = $('#book-id').val();
var user_id = $('#user-id').val();
/* Termina definición de variables */

/* Comienzan funciones automáticas */
$(document).ready(function()
{
    // De profile-edit-scripts, tienes que cargar la imagen en su sitio
    loadBookData(book_id);
    loadSections(book_id);
    tinymce.init
    ({
        selector: '#description',
        language: 'es',
        branding: false,
        menubar: false,
        resize: false,
        height: 200,
        plugins: 'code lists',
        toolbar: 'undo redo | bold italic underline | cut copy paste | alignleft aligncenter alignright alignjustify | code',
        license_key: 'gpl'
    });
});
/* Terminan funciones automáticas */

/* Comienzan efectos para el acordeón */
function toggle(section)
{
    var section = $('#' + section);
    section.toggleClass('active');
}
/* Terminan efectos para el acordeón */

/* Comienza función que carga los datos del libro y los pone en su sitio */
function loadBookData(book_id)
{
    // Aquí vamos
    alert('ID del libro - Datos del libro: ' + book_id);
}
/* Termina función que carga los datos del libro y los pone en su sitio */

/* Comienza función que carga las secciones del libro y genera tantas secciones como sea necesario */
function loadSections(book_id)
{
    // Aquí vamos
    alert('ID del libro - Secciones del libro: ' + book_id);
}
/* Termina función que carga las secciones del libro y genera tantas secciones como sea necesario */