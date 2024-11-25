<?php
    $id_tipo = $perfil['id_tipo'];
    // Si un usuario suscriptor trata de acceder a esta cuenta fraudulentamente, devuelva a main
    if ($id_tipo > 2)
    {
        header('Location: index.php?page=main');
        exit();
    }
?>

<section class="contenido">
    <div class="fila">
        <div class="col w70" id="intro">
            <h3>Este es nuestro <br/><span>LISTADO DE USUARIOS</span></h3>
            <h5>Por favor usa las opciones disponibles.</h5>
        </div>
    </div>
    <div class="fila" id="controles">
        <!-- Comienzan funciones disponibles, cambian dependiendo del tipo de usuario -->
        <div class="col w100" id="functions">
            <h3>Funciones disponibles</h3>
            <div id="listado-funciones">
                <a href="index.php?page=new-user" class="btn">Nuevo usuario</a>
            </div>
        </div>
        <!-- Terminan funciones disponibles -->
    </div>
    <div class="fila" id="busqueda">
        <!-- Comienza filtro de búsqueda AJAX -->
        <div class="col w100" id="filter-search">
            <h3>Búsqueda de usuarios existentes</h3>
            <input type="search" placeholder="Nombre completo o nombre de usuario..." class="search-filter" id="filter-input">
        </div>
        <!-- Termina filtro de búsqueda AJAX -->
    </div>
    <div class="fila" id="listado">        
        <!-- Comienza listado de usuarios -->
        <div class="col w100" id="users">
            <div id="table-overlay" class=""><span class="loader"></span></div>
            <table class="users-table" id="users-table">
                
            </table>
        </div>
        <!-- Termina listado de usuarios -->
    </div>
    <input type="hidden" id="id-tipo" value="<?php echo $id_tipo = $perfil['id_tipo']; ?>">
</section>