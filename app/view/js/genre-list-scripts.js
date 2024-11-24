/* Comienza declaración de variables */
var addGenre = $("#add-genre");
var colorPicker = $("#color-picker");
var colorValue = $("#color-value");
/* Termina declaración de variables */

/* Comienzan funciones de carga automática */
$(document).ready(function () 
{
    /* Cargamos listado */
    cargarListado();
    /* Control para el botón de agregar género */
    addGenre.on("click", function()
    {
        guardarGenero();
    });
    /* Control para el selector de color */
    colorPicker.on("input", function() 
    {
        // Obtiene el color seleccionado en HEX
        let selectedColor = $(this).val();  
        // Remueve el # del código HEX
        let hexWithoutHash = selectedColor.replace("#", "");
        // Muestra el valor en el input de texto
        colorValue.val(hexWithoutHash);
    });
});
/* Terminan funciones de carga automática */

/* Comienza función de cargado de listado */
function cargarListado()
{
    $.ajax
    ({
        type: 'POST',
        url: './controller/genre-crud.php',
        async: true,
        data: 
        {
            obtener_generos_listado: true
        },
        // Tal vez tengamos que usar un beforesend, cuando la tabla tenga muchos libros
        beforeSend: function()
        {
            $('#table-overlay').addClass('active');
        },
        success: function(data)
        {
            // Mandamos el dato al HTML con demora para que se vea
            setTimeout(function()
            {
                $('#table-overlay').removeClass('active');
                $('#genre-table').html('');
                $('#genre-table').html(data);
            }, 500);
        },
        error: function(error)
        {  
           // Mandamos el error al HTML
           $('#genre-table').html(error);
        }
    });
}
/* Termina función de cargado de listado */

/* Comienza función de guardado de género */
function guardarGenero()
{
    // Primero, declaramos variables con los valores actuales
    var genreName = $("#nombre-genero").val();
    var genreColor = colorValue.val().toUpperCase();
    // Si el nombre está vacío o el color es blanco, digamos que no
    if (genreName == '' || genreName == undefined || genreColor == 'FFFFFF')
    {
        mensaje('error', '<b>ERROR</b><br/>No debe haber valores vacíos');
        // Si lo que está vacío es el campo de nombre debe enfocarse en el nombre
        if (genreName == '' || genreName == undefined)
        {
            $("#nombre-genero").focus();
        }
        // Como no tengo forma de hacer focus en el selector de color, entonces mejor no hago nada más
    }
    else
    {
        // Preparamos la pregunta
        Swal.fire
        ({
            title: 'Guardar Género',
            html: '<p style="color: black;"><b>ESTA ACCIÓN ES IRREVERSIBLE</b><br/>¿Está seguro?</p>',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Sí",
            denyButtonText: "No"
        }).then((result) => 
        {
            // Si dice que sí
            if (result.isConfirmed) 
            {
                // Vaciamos la tabla
                $('#genre-table').html('');
                $('#genre-table').html('<div id="table-overlay" class=""><span class="loader"></span></div>');
                // Llamamos un AJAX
                $.ajax
                ({
                    type: 'POST',
                    url: './controller/genre-crud.php',
                    async: true,
                    data:
                    {
                        guardar_genero: true,
                        nombre_genero: genreName,
                        color_genero: genreColor
                    },
                    // Tal vez tengamos que usar un beforesend, cuando la tabla tenga muchos libros
                    beforeSend: function()
                    {
                        $('#table-overlay').addClass('active');
                    },
                    success: function(data)
                    {
                        // Mandamos el dato al HTML con demora para que se vea
                        setTimeout(function()
                        {
                            $('#table-overlay').removeClass('active');
                            $('#genre-table').html('');
                            $('#genre-table').html(data);
                            $("#color-picker").val("#ffffff"); // Blanco predeterminado
                            $("#color-value").val("FFFFFF");  // Código HEX sin #
                            mensaje('success', '<b>ÉXITO</b><br/>Género guardado satisfactoriamente.');
                        }, 500);
                    },
                    error: function(error)
                    {  
                        // Mandamos el error al HTML
                        $('#genre-table').html(error);
                    }
                });
            }
        });
    }
}
/* Termina función de guardado de género */

/* Comienza función de edición de género */
/* Termina función de edición de género */

/* Comienza función de borrado de género */
function eliminarGenero(genreID)
{
    // Preparamos la pregunta
    Swal.fire
    ({
        title: 'Borrar Género',
        html: '<p style="color: black;"><b>ESTA ACCIÓN ES IRREVERSIBLE</b><br/>¿Está seguro?</p>',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Sí",
        denyButtonText: "No"
    }).then((result) => 
    {
        // Si dice que sí
        if (result.isConfirmed) 
        {
            // Vaciamos la tabla
            $('#genre-table').html('');
            $('#genre-table').html('<div id="table-overlay" class=""><span class="loader"></span></div>');
            // Llamamos un AJAX
            $.ajax
            ({
                type: 'POST',
                url: './controller/genre-crud.php',
                async: true,
                data:
                {
                    borrar_libro: true,
                    id_genero: genreID
                },
                // Tal vez tengamos que usar un beforesend, cuando la tabla tenga muchos libros
                beforeSend: function()
                {
                    $('#table-overlay').addClass('active');
                },
                success: function(data)
                {
                    // Mandamos el dato al HTML con demora para que se vea
                    setTimeout(function()
                    {
                        $('#table-overlay').removeClass('active');
                        $('#genre-table').html('');
                        $('#genre-table').html(data);
                        mensaje('success', '<b>ÉXITO</b><br/>Género borrado satisfactoriamente.');
                    }, 500);
                },
                error: function(error)
                {  
                    // Mandamos el error al HTML
                    $('#genre-table').html(error);
                }
            });
        }
    });
}
/* Termina función de borrado de género */