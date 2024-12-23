<?php
    $id_tipo = $perfil['id_tipo'];
    // Si un usuario suscriptor trata de acceder a esta cuenta fraudulentamente, devuelva a main
    if ($id_tipo > 2)
    {
        header('Location: index.php?page=main');
        exit();
    }
?>

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
                        <input type="email" class="input" name="email-usuario" id="email-usuario">
                    </div>
                    <div class="form-group">
                        <span class="label">Nombre de usuario</span>
                        <input type="text" class="input" name="login-usuario" id="login-usuario">
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
                    <button type="submit" id="cancel-profile">Cancelar</button>
                </div>
            </div>
        </section>
        <!-- Termina contenido -->