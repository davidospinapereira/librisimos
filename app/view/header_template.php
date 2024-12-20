<?php
    function cargar_header($perfil, $page)
    {
        /* Comienza generación de enlaces del menú principal */
        $pag_inicio = ($page == 'main') ? '#' : 'index.php?page=main';
        $pag_busqueda_libros = ($page == 'book-search') ? '#' : 'index.php?page=book-search';
        $pag_busqueda_autores = ($page == 'author-search') ? '#' : 'index.php?page=author-search';
        $pag_mis_libros = ($page == 'my-books') ? '#' : 'index.php?page=my-books';
        $link_inicio = ($page == 'main') ? '<a href="' . $pag_inicio . '" class="nav-active">Inicio</a>' : '<a href="' . $pag_inicio . '">Inicio</a>';
        $link_libros = ($page == 'book-search') ? '<a href="' . $pag_busqueda_libros . '" class="nav-active">Buscar Libros</a>' : '<a href="' . $pag_busqueda_libros . '">Buscar Libros</a>';
        $link_autores = ($page == 'author-search') ? '<a href="' . $pag_busqueda_autores . '" class="nav-active">Buscar Autores</a>' : '<a href="' . $pag_busqueda_autores . '">Buscar Autores</a>';
        $link_mis_libros = ($page == 'my-books') ? '<a href="' . $pag_mis_libros . '" class="nav-active">Mis libros</a>' : '<a href="' . $pag_mis_libros . '">Mis libros</a>';
        // Esta solo sale si el usuario es admin o superadmin (1-SUPERADMIN, 2-ADMIN, 3-USUARIO)
        if ($perfil['id_tipo'] < 3)
        {
            $pag_usuarios = ($page == 'user-search') ? '#' : 'index.php?page=user-search';
            $link_usuarios = ($page == 'user-search') ? '<a href="' . $pag_usuarios . '" class="nav-active">Usuarios</a>' : '<a href="' . $pag_usuarios . '">Usuarios</a>';
        }
        else
        {
            $link_usuarios = '';
        }
        if ($perfil['url_imagen'] == '' || $perfil['url_imagen'] == null)
        {
            $url_imagen = './view/uploads/users/user-avatar.png';
        }
        else
        {
            $url_imagen = './' . $perfil['url_imagen'];
        }
        if ($page == 'main' || $page == 'book-page')
        {
            $css_read_tool = 
            '<!-- CSS de herramienta de lectura -->
        <link rel="stylesheet" href="./view/css/read-tool-style.css">';
        }
        else
        {
            $css_read_tool = '';
        }
        $pag_editor_perfil = ($page == 'profile-edit') ? '#' : 'index.php?page=profile-edit';
        $pag_ayuda = ($page == 'help') ? '#' : 'index.php?page=help';
        $titulo_pagina = ($perfil['id_tipo'] < 3) ? 'Administrador' : 'Usuario';
        /* Termina generación de enlaces del menú principal */
        $html_header = 
        '
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
        <title>' . $titulo_pagina . ' - Librísimos</title>
        <!-- Archivos CSS -->
        <!-- CSS de estilos generales -->
        <link rel="stylesheet" href="./view/css/general-style.css">
        <!-- CSS de estilos de header -->
        <link rel="stylesheet" href="./view/css/header-style.css">
        <!-- CSS de página específica -->
        <link rel="stylesheet" href="./view/css/'. $page . '-style.css">
        <!-- CSS de estilos de footer -->
        <link rel="stylesheet" href="./view/css/footer-style.css">
        ' . $css_read_tool . '
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
            <!-- Menú de navegación a la derecha, en enlaces formateados -->
            <nav class="navbar">
                ' . $link_inicio . '
                ' . $link_libros . '
                ' . $link_autores . '
                ' . $link_mis_libros . '
                <!-- Este sale sólo si el usuario es admin o súper admin -->
                ' . $link_usuarios . '
                <!-- Enlace para "mi perfil" -->
                <img src="' . $url_imagen . '" class="user-pic">
            </nav>
        </header>
        <!-- Comienza submenú "mi perfil" -->
        <div class="profile-wrap">
            <div class="sub-menu">
                <div class="user-info">
                    <img src="' . $url_imagen . '">
                    <h4>' . $perfil['nombres'] . ' ' . $perfil['apellidos'] . '</h4>
                </div>
                <a href="' . $pag_editor_perfil . '" class="sub-menu-link">
                    <i class="bx bx-user-circle"></i>
                    <p>Editar perfil</p>
                </a>
                <a href="' . $pag_ayuda . '" class="sub-menu-link">
                    <i class="bx bxs-help-circle" ></i>
                    <p>Ayuda</p>
                </a>                
                <a href="./controller/logout.php" class="sub-menu-link">
                    <i class="bx bx-log-out-circle" ></i>
                    <p>Cerrar sesión</p>
                </a>
            </div>
        </div>
        <!-- Termina submenú "mi perfil" -->
        <!-- Termina Header -->
        ';
        return $html_header;
    }
?>