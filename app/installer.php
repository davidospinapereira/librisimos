<?php
    // Verifica si existe el dato POST
    print_r($_POST);
?>

<!DOCTYPE html>
    <html lang="es">
    <!-- Comienza HTML -->
    <head>
        <!-- Comienza cabeza de archivo -->
        <meta charset="UTF-8">
        <!-- Adaptación para que se vea bien en IExplore y Microsoft Edge -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Inicialización de responsividad -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Título de la página en específico -->
        <title>Instalador - Librísimos</title> <!-- Diferencia 1 -->
        <!-- Archivos CSS -->
        <!-- CSS de la página -->
        <link rel="stylesheet" href="./css/installer-style.css">
        <!-- CSS de BoxIcons -->
        <link rel="stylesheet" href="./css/boxicons-2.1.4/css/boxicons.min.css">
        <!-- CSS de SweetAlert2 -->
        <link rel="stylesheet" href="./css/sweetalert2.min.css">
        <!-- Termina cabeza de archivo -->
    </head>
    <body>
        <!-- Comienza cuerpo del archivo -->
        <!-- Comienza Encabezado de página -->
        <header class="header">
            <!-- Logo a la izquierda, en texto formateado -->
            <a href="#" class="logo">Libr<span class="red-logo">ísimos</span></a>
            <!-- Diferencia 3, aquí no hay menú de navegación -->
        </header>
        
        <!-- Termina Encabezado de página -->
        <!-- Comienza contenido -->
        <section class="contenido" id="inicio">
            <!-- Comienza sección de mensajes -->
            <div class="fila" id="seccion-mensajes">
                <div class="col w100 mensaje exito">
                    <div class="mensaje-interno">
                        <div class="icono">
                            <i class='bx bxs-check-circle'></i>
                        </div>
                        <div class="texto">
                            <p>Este es un mensaje de exito. Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus, consectetur?</p>
                        </div>
                    </div>
                </div>
                <div class="col w100 mensaje error">
                    <div class="mensaje-interno">
                        <div class="icono">
                            <i class='bx bxs-x-circle'></i>
                        </div>
                        <div class="texto">
                            <p>Este es un mensaje de error. Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus, consectetur?</p>
                        </div>
                    </div>
                </div>
                <div class="col w100 mensaje info">
                    <div class="mensaje-interno">
                        <div class="icono">
                            <i class='bx bxs-info-circle'></i>
                        </div>
                        <div class="texto">
                            <p>Este es un mensaje informativo. Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus, consectetur?</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Termina sección de mensajes -->
            <!-- Comienza sección de formularios -->
             <div class="fila" id="info-formularios">
                <div class="col w100">
                    <h3>Instalador de base de datos "Librísimos"</h3>
                    <p>Por favor ingresa los datos respectivos en las casillas correspondientes. Luego, da clic en "Probar base de datos". Luego, ingresa el nombre de usuario y contraseña del súper administrador y da clic en "Instalar".</p>
                    <p style="font-size: 11px;">Los datos para el formulario de base de datos son provistos por tu servidor.</p>
                </div>
             </div>
             <div class="fila" id="seccion-formularios">
                <div class="col w50">
                    <div class="form-group">
                        <span class="label">Nombre del servidor</span>
                        <input type="text" name="db-server" id="db-server">
                    </div>
                    <div class="form-group">
                        <span class="label">Nombre de la base de datos</span>
                        <input type="text" name="db-name" id="db-name">
                    </div>
                    <div class="form-group">
                        <span class="label">Nombre de usuario de la base de datos</span>
                        <input type="text" name="db-user" id="db-user">
                    </div>
                    <div class="form-group">
                        <span class="label">Contraseña de la base de datos</span>
                        <input type="text" name="db-pass" id="db-pass">
                    </div>
                    <button type="submit" style="width: 45%;">Probar base de datos</button>
                </div>
                <div class="col w50">
                    <div class="form-group">
                        <span class="label">Nombre de usuario súper administrador</span>
                        <input type="text" name="superadmin-login" id="superadmin-login">
                    </div>
                    <div class="form-group">
                        <span class="label">Contraseña de súper administrador</span>
                        <input type="password" name="superadmin-pass" id="superadmin-pass">
                    </div>
                    <div class="form-group">
                        <span class="label">Confirmar Contraseña de súper administrador</span>
                        <input type="password" name="superadmin-pass-confirm" id="superadmin-pass-confirm">
                    </div>
                    <button type="submit" style="width: 45%;">Instalar</button>
                </div>
             </div>
            <!-- Termina sección de formularios -->
        </section>
        <!-- Termina contenido -->
        <!-- Comienza Pie de página -->
        <footer class="footer">
            <div class="footer-text">
                <p>Copyright &copy; 2024, para el Servicio Nacional de Aprendizaje.</p>
            </div>
            <div class="footer-back-to-top">
                <a href="#inicio"><i class="bx bx-up-arrow-alt"></i></a>
            </div>
        </footer>
        <!-- Termina Pie de página -->
        <!-- Termina cuerpo del archivo-->
        <!-- Comienza Javascript -->
        <!-- JQuery -->
        <script src="./js/jquery-3.7.1.min.js"></script>
        <!-- Javascript de SweetAlert2 -->
        <script src="./js/sweetalert2.all.min.js"></script>
        <!-- Javascript de la página -->
        <script src="./js/installer-scripts.js"></script>
        <!-- Termina Javascript -->
    </body>
</html>