<?php
    $structure = '
        CREATE TABLE `autor` (
        `id_autor` int(11) NOT NULL,
        `nombre_autor` varchar(80) DEFAULT NULL,
        `url_imagen_autor` varchar(300) DEFAULT NULL,
        `informacion_autor` text DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

        CREATE TABLE `autores_libro` (
        `id_libro` int(11) DEFAULT NULL,
        `id_autor` int(11) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

        CREATE TABLE `componer_seccion` (
        `id_libro` int(11) DEFAULT NULL,
        `id_seccion` int(11) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

        CREATE TABLE `genero` (
        `id_genero` int(11) NOT NULL,
        `nombre_genero` varchar(50) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

        CREATE TABLE `generos_libro` (
        `id_libro` int(11) DEFAULT NULL,
        `id_genero` int(11) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

        CREATE TABLE `libro` (
        `id_libro` int(11) NOT NULL,
        `nombre_libro` varchar(100) DEFAULT NULL,
        `url_caratula_libro` varchar(300) DEFAULT NULL,
        `sinopsis_libro` text DEFAULT NULL,
        `lecturas_libro` int(11) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

        CREATE TABLE `seccion` (
        `id_seccion` int(11) NOT NULL,
        `numero_seccion` int(11) DEFAULT NULL,
        `titulo_seccion` varchar(100) DEFAULT NULL,
        `contenido_seccion` text DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

        CREATE TABLE `tipo_usuario` (
        `id_tipo_usuario` int(11) NOT NULL,
        `nombre_tipo_usuario` varchar(30) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

        INSERT INTO `tipo_usuario` (`id_tipo_usuario`, `nombre_tipo_usuario`) VALUES
        (1, \'SUPER ADMINISTRADOR\'),
        (2, \'ADMINISTRADOR\'),
        (3, \'USUARIO\');

        CREATE TABLE `usuario` (
        `id_usuario` int(11) NOT NULL,
        `nombres_usuario` varchar(50) DEFAULT NULL,
        `apellidos_usuario` varchar(50) DEFAULT NULL,
        `fecha_nacimiento_usuario` date DEFAULT NULL,
        `email_usuario` varchar(50) DEFAULT NULL,
        `url_imagen_usuario` varchar(256) DEFAULT NULL,
        `login_usuario` varchar(30) NOT NULL,
        `pass_usuario` varchar(256) NOT NULL,
        `id_tipo_usuario` int(11) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

        CREATE TABLE `ver_seccion` (
        `id_usuario` int(11) DEFAULT NULL,
        `id_seccion` int(11) DEFAULT NULL,
        `fecha_lectura_ver_seccion` date DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

        ALTER TABLE `autor`
        ADD PRIMARY KEY (`id_autor`);

        ALTER TABLE `autores_libro`
        ADD KEY `autores_libro_fk0` (`id_libro`),
        ADD KEY `autores_libro_fk1` (`id_autor`);

        ALTER TABLE `componer_seccion`
        ADD KEY `componer_seccion_fk0` (`id_libro`),
        ADD KEY `componer_seccion_fk1` (`id_seccion`);

        ALTER TABLE `genero`
        ADD PRIMARY KEY (`id_genero`);

        ALTER TABLE `generos_libro`
        ADD KEY `generos_libro_fk0` (`id_libro`),
        ADD KEY `generos_libro_fk1` (`id_genero`);

        ALTER TABLE `libro`
        ADD PRIMARY KEY (`id_libro`);

        ALTER TABLE `seccion`
        ADD PRIMARY KEY (`id_seccion`);

        ALTER TABLE `tipo_usuario`
        ADD PRIMARY KEY (`id_tipo_usuario`);

        ALTER TABLE `usuario`
        ADD PRIMARY KEY (`id_usuario`),
        ADD UNIQUE KEY `login_usuario` (`login_usuario`),
        ADD KEY `usuario_fk0` (`id_tipo_usuario`);

        ALTER TABLE `ver_seccion`
        ADD KEY `ver_seccion_fk0` (`id_usuario`),
        ADD KEY `ver_seccion_fk1` (`id_seccion`);

        ALTER TABLE `autor`
        MODIFY `id_autor` int(11) NOT NULL AUTO_INCREMENT;

        ALTER TABLE `genero`
        MODIFY `id_genero` int(11) NOT NULL AUTO_INCREMENT;

        ALTER TABLE `libro`
        MODIFY `id_libro` int(11) NOT NULL AUTO_INCREMENT;

        ALTER TABLE `seccion`
        MODIFY `id_seccion` int(11) NOT NULL AUTO_INCREMENT;

        ALTER TABLE `tipo_usuario`
        MODIFY `id_tipo_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

        ALTER TABLE `usuario`
        MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

        ALTER TABLE `autores_libro`
        ADD CONSTRAINT `autores_libro_fk0` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`),
        ADD CONSTRAINT `autores_libro_fk1` FOREIGN KEY (`id_autor`) REFERENCES `autor` (`id_autor`);

        ALTER TABLE `componer_seccion`
        ADD CONSTRAINT `componer_seccion_fk0` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`),
        ADD CONSTRAINT `componer_seccion_fk1` FOREIGN KEY (`id_seccion`) REFERENCES `seccion` (`id_seccion`);

        ALTER TABLE `generos_libro`
        ADD CONSTRAINT `generos_libro_fk0` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`),
        ADD CONSTRAINT `generos_libro_fk1` FOREIGN KEY (`id_genero`) REFERENCES `genero` (`id_genero`);

        ALTER TABLE `usuario`
        ADD CONSTRAINT `usuario_fk0` FOREIGN KEY (`id_tipo_usuario`) REFERENCES `tipo_usuario` (`id_tipo_usuario`);

        ALTER TABLE `ver_seccion`
        ADD CONSTRAINT `ver_seccion_fk0` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
        ADD CONSTRAINT `ver_seccion_fk1` FOREIGN KEY (`id_seccion`) REFERENCES `seccion` (`id_seccion`);
        COMMIT;
    ';
?>