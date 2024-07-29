<?php
    session_start();
    session_destroy(); // Destruir todas las sesiones
    header('Location: ../../');
    exit();
?>