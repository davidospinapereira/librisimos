<?php
    /* Extrayendo del array de perfil variables estáticas para poder modificar */
    $nombres = $perfil['nombres'];
    $apellidos = $perfil['apellidos'];
    if (($nombres == '' || $nombres == null) && ($apellidos == '' || $apellidos == null))
    {
        // Apellidos no pueden quedar en blanco
        $nombre_completo = '(Desconocido)';
    }
    else
    {
        $nombre_completo = $nombres . ' ' . $apellidos;
    }
    $fecha_nacimiento = $perfil['fecha_nacimiento'];
    $email = $perfil['email'];
    $login = $perfil['login'];
    $id_tipo = $perfil['id_tipo'];    
    if($perfil['url_imagen'] == NULL)
    {
        $url_imagen = 'view/uploads/users/user-avatar.png';
    }
    else
    {
        $url_imagen = $perfil['url_imagen'];
    }
    // Para la imagen de perfil, entra al archivo profile-edit-scripts.js
?>
<!-- Comienza contenido -->
<section class="contenido" id="inicio">
            <div class="fila" id="presentacion-fotos">
                <div class="col w20" id="foto">
                    <input type="file" name="file-selector" id="file-selector" accept="image/png, image/jpeg" style="display: none;">
                    <button onclick="$('#file-selector').click();" id="selector-archivo">Seleccionar foto</button>
                </div>
                <div class="col w80" id="main-data">
                    <h6>ID: <span><?php echo $user_id; ?></span></h6>
                    <h3><span style="text-transform: uppercase;"><?php echo $nombre_completo; ?></span></h3>
                    <!-- <h4><span style="text-transform: uppercase;">URL: <?php echo $url_imagen; ?></span></h4> -->
                    <h5>EDITOR DE PERFIL</h5>
                    <a href="#formulario" class="btn">Editar</a>
                </div>
            </div>
            <div class="fila" id="formulario">
                <div class="col w40">
                    <div class="form-group">
                        <span class="label">Nombre(s) del usuario</span>
                        <input type="text" name="nombre-usuario" id="nombre-usuario" value="<?php echo $nombres; ?>">
                    </div>
                    <div class="form-group">
                        <span class="label">Apellido(s) del usuario</span>
                        <input type="text" name="apellido-usuario" id="apellido-usuario" value="<?php echo $apellidos; ?>">
                    </div>
                    <div class="form-group">
                        <span class="label">Fecha de nacimiento del usuario</span>
                        <input type="date" name="fecha-nacimiento" id="fecha-nacimiento" value="<?php echo $fecha_nacimiento; ?>">
                    </div>
                </div>
                <div class="col w40">
                    <div class="form-group">
                        <span class="label">Correo electrónico</span>
                        <input type="email" name="email-usuario" id="email-usuario" value="<?php echo $email; ?>">
                    </div>
                    <div class="form-group">
                        <span class="label">Nombre de usuario</span>
                        <input type="text" name="login-usuario" id="login-usuario" value="<?php echo $login; ?>">
                    </div>
                    <div class="form-group">
                        <span class="label">Contraseña</span>
                        <button type="submit" id="change-pass">Cambiar contraseña</button>
                    </div>
                </div>
                <div class="col w20">
                    <p style="color: transparent;">Texto falso</p>
                    <button type="submit" id="update-data">Actualizar datos</button>
                    <!-- Este botón debe aparecer sólo para los tipos de usuario diferentes de 1 -->
                    <?php
                        if ($id_tipo != 1)
                        {
                            ?><button type="submit" class="delete-button" id="delete-account">Eliminar Cuenta</button><?php
                        }
                    ?>
                </div>
            </div>
        </section>
        <!-- Termina contenido -->
        <input type="hidden" id="user_id_hidden" value="<?php echo $user_id; ?>">
        <input type="hidden" id="url_imagen_hidden" value="<?php echo $url_imagen; ?>">