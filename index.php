<?php
    // Iniciamos sesión
    session_start();
    // Si hay datos en sesión entonces la sesión está previamente iniciada
    if (isset($_SESSION['user_id'])) 
    {
        header('Location: ./app/index.php?page=main');
        exit();
    }
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
        <title>Librísimos - Lee tus libros en línea</title>
        <!-- Archivos CSS -->
        <!-- CSS de página de inicio, incluye header y footer -->
        <link rel="stylesheet" href="./app/view/css/index-styles.css"> 
        <!-- CSS de BoxIcons -->
        <link rel="stylesheet" href="./app/view/css/boxicons-2.1.4/css/boxicons.min.css">
        <!-- CSS de SweetAlert2 -->
        <link rel="stylesheet" href="./app/view/css/sweetalert2.min.css">
        <!-- Termina cabeza de archivo -->
    </head>
    <body>
        <?php 
            /* Invocamos el archivo de verificación de base de datos, que se encuentra en model */
            require './app/model/database_json_check.php';
            // Verificamos el archivo JSON con los datos de conexión a base de datos.
            $json_check = json_check('./app/model/connection_data.json');
            if ($json_check !== "JSON_EXISTE_CORRECTO")
            {
                /* Si el archivo JSON no existe o es incorrecto, cargamos installer pasándole el valor de $json_check por POST. Para eso usamos JS de la siguiente manera */
                ?>
                <script>
                    const data = 
                    {
                        error_code: '<?php echo $json_check ?>'
                    };
                    const url = './app/installer.php';
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    for (const key in data) 
                    {
                        if (data.hasOwnProperty(key)) 
                        {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = key;
                            input.value = data[key];
                            form.appendChild(input);
                        }
                    }
                    // Agrega el formulario al cuerpo del documento y envíalo
                    document.body.appendChild(form);
                    form.submit();
                </script>
                <?php
            }
            else
            {
                /* El archivo JSON existe y tiene la estructura correcta. Probamos la conexión con la base de datos. */
                //Invocamos el verificador de base de datos
                require './app/model/database_db_check.php';
                // Comprobamos si la base de datos existe
                $db_check = db_check('./app/model/connection_data.json');
                if ($db_check !== "DB_EXISTE_CORRECTA")
                {
                    /* Si la base de datos no existe o no tiene la estructura correcta, cargamos installer pasándole el valor de $db_check por POST. Para eso usamos JS de la siguiente manera */
                    ?>
                    <script>
                        const data = 
                        {
                            error_code: '<?php echo $db_check ?>'
                        };
                        const url = './app/installer.php';
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = url;
                        for (const key in data) 
                        {
                            if (data.hasOwnProperty(key)) 
                            {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = key;
                                input.value = data[key];
                                form.appendChild(input);
                            }
                        }
                        // Agrega el formulario al cuerpo del documento y envíalo
                        document.body.appendChild(form);
                        form.submit();
                    </script>
                    <?php
                }
                else
                {
                    // El JSON existe y tiene la estructura, la base de datos existe y tiene su estructura, por fin cargamos HOME.
                    ?>
        <!-- Comienza cuerpo de archivo -->
        <!-- Comienza Header -->
        <header class="header">
            <!-- Logo a la izquierda, en texto formateado -->
            <a href="#" class="logo">Libr<span class="red-logo">ísimos</span></a>
            <!-- Menú de navegación a la derecha, en enlaces formateados -->
            <nav class="navbar">
                <a href="#inicio" class="nav-active">Inicio</a>
                <a href="#nosotros">Nosotros</a>
                <a href="#servicios">Servicios</a>
                <!-- Botón de acceso para login o registro -->
                <button class="btnLogin">ACCESO</button>
            </nav>
        </header>
        <!-- Termina Header -->
        <!-- Comienza contenido -->
        <section class="contenido">
            <!-- Comienza popup lateral de acceso -->
            <div class="login-popup">
                <!-- Comienza botón de cierre -->
                <span class="icon-close"><i class='bx bx-x'></i></span>
                <!-- Termina botón de cierre -->
                <div class="logreg-box">
                    <!-- Comienza Formulario de login -->
                    <div class="form-box login">
                        <div class="logreg-title">
                            <h2>Acceso</h2>
                            <p>Ingresa para usar la plataforma</p>
                        </div>
                        <form action="#" id="login-form">
                            <div class="input-box">
                                <span class="icon"><i class='bx bxs-user'></i></span>
                                <input type="text" id="login-user" required>
                                <label>Nombre de usuario</label>
                            </div>
                            <div class="input-box">
                                <span class="icon"><i class='bx bxs-lock-alt'></i></span>
                                <input type="password" id="login-pass" required>
                                <label>Contraseña</label>
                            </div>
                            <div class="forgot">
                                <a href="#">¿Olvidaste tu contraseña?</a>
                            </div>
                            <button type="submit" class="btn">INGRESAR</button>
                            <div class="logreg-link">
                                <p>¿No tienes cuenta? <a href="#" class="register-link">Regístrate</a></p>
                            </div>
                        </form>
                    </div>
                    <!-- Termina formulario de login -->
                    <!-- Comienza Formulario de registro -->
                    <div class="form-box register">
                        <div class="logreg-title">
                            <h2>Registro</h2>
                            <p>Ingresa lo siguiente para verificar tu identidad</p>
                        </div>
                        <form action="#" id="register-form">
                            <div class="input-box">
                                <span class="icon"><i class='bx bxs-user'></i></span>
                                <input type="text" required>
                                <label>Nombre de usuario</label>
                            </div>
                            <div class="input-box">
                                <span class="icon"><i class='bx bxs-envelope'></i></span>
                                <input type="email" required>
                                <label>Email</label>
                            </div>
                            <div class="input-box">
                                <span class="icon"><i class='bx bxs-lock-alt'></i></span>
                                <input type="password" required>
                                <label>Contraseña</label>
                            </div>
                            <div class="forgot">
                                <label><input type="checkbox">Acepto los términos y condiciones</label>
                            </div>
                            <button type="submit" class="btn">REGISTRARTE</button>
                            <div class="logreg-link">
                                <p>¿Ya tienes cuenta? <a href="#" class="login-link">Accede</a></p>
                            </div>
                        </form>
                    </div>
                    <!-- Termina Formulario de registro -->
                </div>
            </div>
            <!-- Termina popup lateral de acceso -->
            <!-- Comienza sección inicio -->
            <div class="section" id="inicio">
                <div class="imagen"></div>
                <div class="fila">
                    <h2>Esto es<br/><span>LIBRÍSIMOS</span></h2>
                    <h3>Tu plataforma de lectura en línea, <br/>desde donde quieras.</h3>
                    <a href="#nosotros" class="btn">Conoce más</a>
                </div>
            </div>
            <!-- Termina sección inicio -->
            <!-- Comienza sección nosotros -->
            <div class="section" id="nosotros">
                <div class="fila encabezado">
                    <h2>Quiénes somos</h2>
                </div>
                <div class="fila">
                    <div class="col50" style="padding-right: 30px;">
                        <h3>Somos el nuevo portal de lectura en español</h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora repudiandae quaerat culpa aut eaque qui fugiat est provident aliquid aspernatur veniam modi eligendi laborum tempore excepturi commodi molestiae, quia, consequatur cupiditate praesentium. Excepturi, voluptas ab?</p>
                        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Repudiandae quod suscipit, assumenda repellendus accusantium laboriosam pariatur tenetur vero voluptatum. A accusantium vitae accusamus libero numquam incidunt iure autem quam corrupti.</p>
                    </div>
                    <div class="col50">
                        <img src="./app/view/img/about-ref.jpg" class="image">
                    </div>
                </div>
            </div>
            <!-- Termina sección nosotros -->
            <!-- Comienza sección servicios -->
            <div class="section" id="servicios">
                <div class="fila encabezado">
                    <h2>Nuestros Servicios</h2>
                </div>
                <div class="fila services-container">
                    <div class="service-box">
                        <i class='bx bx-mobile-alt'></i>
                        <h3>Lee donde quieras</h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Maiores nulla cumque eveniet culpa esse dolorum ad modi nesciunt nihil repellendus.</p>
                    </div>
                    <div class="service-box">
                        <i class='bx bx-book-open' ></i>
                        <h3>A tu ritmo</h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Maiores nulla cumque eveniet culpa esse dolorum ad modi nesciunt nihil repellendus.</p>
                    </div>
                    <div class="service-box">
                        <i class='bx bx-globe' ></i>
                        <h3>Siempre más libros</h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Maiores nulla cumque eveniet culpa esse dolorum ad modi nesciunt nihil repellendus.</p>
                    </div>
                </div>
            </div>
            <!-- Termina sección servicios -->
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
        <!-- Termina cuerpo de archivo -->
        <!-- Comienza Javascript -->
        <!-- JQuery -->
        <script src="./app/view/js/jquery-3.7.1.min.js"></script>
        <!-- Javascript de SweetAlert2 -->
        <script src="./app/view/js/sweetalert2.all.min.js"></script>
        <!-- Javascript general -->
        <script src="./app/view/js/index-scripts.js"></script>
        <!-- Termina Javascript -->
                    <?php
                }
            }
        ?>
    </body>
    <!-- Termina HTML -->
</html>