<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        // Si hay datos POST, recójalos y popule la página.
        $login = $_POST['login'];
        $email = $_POST['email'];
        ?>
<!-- Aquí cargamos la página que debe quedar como profile-edit y en los campos de correo y login debemos poner los datos POST recolectados... -->
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
        <title>Registro - Librísimos</title>
        <!-- Archivos CSS -->
        <!-- CSS de estilos generales -->
        <link rel="stylesheet" href="./view/css/general-style.css">
        <!-- CSS de estilos de header -->
        <link rel="stylesheet" href="./view/css/header-style.css">
        <!-- CSS de página específica -->
        <link rel="stylesheet" href="./view/css/register-style.css">
        <!-- CSS de estilos de footer -->
        <link rel="stylesheet" href="./view/css/footer-style.css">
        <!-- CSS de estilos responsivos -->
        <link rel="stylesheet" href="./view/css/responsive-style.css">
        <!-- CSS de BoxIcons -->
        <link rel="stylesheet" href="./view/css/boxicons-2.1.4/css/boxicons.min.css">
        <!-- CSS de SweetAlert2 -->
        <link rel="stylesheet" href="./view/css/sweetalert2.min.css">
        <!-- Termina cabeza de archivo -->
    </head>
    <body>
        <!-- Comienza cuerpo del archivo -->
        <!-- Comienza Header -->
        <header class="header">
            <!-- Logo a la izquierda, en texto formateado -->
            <a href="#" class="logo">Libr<span class="red-logo">ísimos</span></a>
            <!-- Esta página no tiene menú de navegación -->
        </header>
        <!-- Termina Header -->
        <!-- Comienza contenido -->
        <section class="contenido" id="inicio">
            <div class="fila" id="presentacion-fotos">
                <div class="col w20" id="foto">
                    <input type="file" name="file-selector" id="file-selector" accept="image/png, image/jpeg" style="display: none;">
                    <button onclick="$('#file-selector').click();" id="selector-archivo">Seleccionar foto</button>
                </div>
                <div class="col w80" id="main-data">
                    <h3><span style="text-transform: uppercase;">USUARIO NUEVO</span></h3>
                    <h5>REGISTRO</h5>
                    <a href="#formulario" class="btn">Editar</a>
                </div>
            </div>
            <div class="fila" id="formulario">
                <div class="col w40">
                    <div class="form-group">
                        <span class="label">Nombre(s) del usuario</span>
                        <input type="text" class="input" name="nombre-usuario" id="nombre-usuario">
                    </div>
                    <div class="form-group">
                        <span class="label">Apellido(s) del usuario</span>
                        <input type="text" class="input" name="apellido-usuario" id="apellido-usuario">
                    </div>
                    <div class="form-group">
                        <span class="label">Fecha de nacimiento del usuario</span>
                        <input type="date" class="input" name="fecha-nacimiento" id="fecha-nacimiento">
                    </div>
                </div>
                <div class="col w40">
                    <div class="form-group">
                        <span class="label">Correo electrónico</span>
                        <input type="email" class="input" name="email-usuario" id="email-usuario" value="<?php echo $email; ?>">
                    </div>
                    <div class="form-group">
                        <span class="label">Nombre de usuario</span>
                        <input type="text" class="input" name="login-usuario" id="login-usuario" value="<?php echo $login; ?>">
                    </div>
                    <div class="form-group">
                        <span class="label">Contraseña</span>
                        <input type="password" class="input" name="pass-usuario" id="pass-usuario">
                    </div>
                    <div class="form-group">
                        <span class="label">Confirmar contraseña</span>
                        <input type="password" class="input" name="conf-pass-usuario" id="conf-pass-usuario">
                    </div>
                </div>
                <div class="col w20">
                    <p style="color: transparent;">Texto falso</p>
                    <button type="submit" id="create-profile">Crear cuenta</button>
                    <button type="submit" id="cancel-profile">Cancelar cuenta</button>
                </div>
            </div>
        </section>
        <!-- Termina contenido -->

        <!-- Comienza footer -->
        <footer class="footer">
            <div class="footer-text">
                <p>Copyright &copy; 2024, para el Servicio Nacional de Aprendizaje.</p>
            </div>
            <div class="footer-back-to-top">
                <a href="#inicio"><i class="bx bx-up-arrow-alt"></i></a>
            </div>
        </footer>
        <!-- Termina footer -->

        <!-- Termina cuerpo del archivo-->

        <!-- Comienza Javascript -->
        <!-- JQuery -->
        <script src="./view/js/jquery-3.7.1.min.js"></script>
        <!-- Javascript de SweetAlert2 -->
        <script src="./view/js/sweetalert2.all.min.js"></script>
        <!-- Javascript general -->
        <script src="./view/js/general-scripts.js"></script>
        <!-- Javascript de la página específica -->
        <script src="./view/js/register-scripts.js"></script>
        <!-- Termina Javascript -->

    </body>
</html>
        <?php
    }
    else
    {
        // Si no hay datos POST, redirija a la página principal, no debe estar aquí...
        header('Location: ../');
        exit();
    }
?>