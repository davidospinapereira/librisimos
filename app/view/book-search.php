        <section class="contenido" id="inicio">
            <div class="fila">
                <div class="col w70" id="intro">
                    <h3>Este es nuestro <br/><span>BUSCADOR DE LIBROS EN LÍNEA</span></h3>
                    <h5>Por favor escribe el nombre o usa los filtros disponibles.</h5>
                </div>
            </div>
            <!-- Comienza sección de búsqueda -->
            <div class="fila">
                <!-- Comienza sección de filtros de búsqueda -->
                <div class="col w100" id="title">
                    <h3>Filtros de búsqueda</h3>
                </div>
                <!-- Termina sección de filtros de búsqueda -->
            </div>
            <div class="fila">
                <div class="col w30" id="filter-select">
                    <!-- Aquí vendrá un filtro de búsqueda con una función de AJAX -->
                    <select name="select" id="genero">
                        <option value="0">Seleccione un género</option>
                    </select>
                </div>
                <div class="col w70" id="filter-search">
                    <input type="search" placeholder="Título, valor o palabras clave..." class="search-filter" id="filter-input">
                </div>
            </div>
            <!-- Comienza la sección de contenido -->
            <div class="fila" id="content">
                <!-- Comienza segmento de spinner -->
                <div class="col w100" id="loading">
                    <div class="spinner" id="cards-spinner">
                        <span class="loader"></span>
                    </div>
                </div>
                <!-- Termina segmento de spinner -->
                <!-- Comienza grid de tarjetas -->
                <!-- Termina grid de tarjetas -->
                <!-- Comienza sección de nada encontrado o de error de sistema -->
                <!-- Termina sección de nada encontrado o de error de sistema -->
            </div>
            <!-- Termina la sección de contenido -->
            </div>
            <!-- Termina sección de búsqueda -->
        </section>