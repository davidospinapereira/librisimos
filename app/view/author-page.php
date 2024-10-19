<?php
    // Si no hay id de autor, debemos redirigir
    if (!isset($_GET['author-id']))
    {
        header('Location: index.php?page=main');
        exit();
    }
    // Si sí, podemos arrancar
?>
        <section class="contenido" id="inicio">
            <!-- Comienza Información principal del autor -->
            <div class="fila" id="main-info">
                <div class="col w20" id="image">
                    
                </div>
                <div class="col w80" id="main-data">
                    <div class="info" id="info">
                        <h3 id="author"></h3>    
                        <h2 id="title"></h2>
                    </div>
                    <div class="sinopsis" id="sinopsis"></div>
                </div>
            </div>
            <!-- Aquí debajo debe ir el botón de dejar de leer, en rojo Y SOLO CUANDO NO SE HAYA LEÍDO -->
            <div class="fila" id="funciones">
            </div>
            <!-- Comienza grid de libros publicados -->
            <div class="fila" id="titulo-relacionados">
                <div class="col w100">
                    <h3 class="section-title">Libros de este autor</h3>
                </div>
                <div class="col w100" id="books-loading">
                    <div class="spinner" id="books-spinner">
                        <span class="loader"></span>
                    </div>
                </div>
            </div>
            <div class="fila" id="cards-grid">
            </div>
            <!-- Termina grid de libros semejantes -->
            <!-- Comienzan Inputs ocultos, para poder pasarle variables al JS -->
            <input type="hidden" value="<?php echo $_GET['author-id'];?>" id="author-id">
            <input type="hidden" value="<?php echo $user_id;?>" id="user-id">
            <!-- Terminan Inputs ocultos -->
        </section>