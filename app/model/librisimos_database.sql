CREATE TABLE `usuario` (
	`id_usuario` int NOT NULL AUTO_INCREMENT,
	`nombres_usuario` varchar(50),
	`apellidos_usuario` varchar(50),
	`fecha_nacimiento_usuario` DATE,
	`email_usuario` varchar(50),
	`url_imagen_usuario` varchar(256),
	`login_usuario` varchar(30),
	`pass_usuario` varchar(256),
	`id_tipo_usuario` int NOT NULL,
	PRIMARY KEY (`id_usuario`)
);

CREATE TABLE `tipo_usuario` (
	`id_tipo_usuario` int NOT NULL AUTO_INCREMENT,
	`nombre_tipo_usuario` varchar(30),
	PRIMARY KEY (`id_tipo_usuario`)
);

CREATE TABLE `libro` (
	`id_libro` int NOT NULL AUTO_INCREMENT,
	`nombre_libro` varchar(100),
	`url_caratula_libro` varchar(300),
	`sinopsis_libro` TEXT,
	`lecturas_libro` int,
	PRIMARY KEY (`id_libro`)
);

CREATE TABLE `generos_libro` (
	`id_libro` int,
	`id_genero` int
);

CREATE TABLE `genero` (
	`id_genero` int NOT NULL AUTO_INCREMENT,
	`nombre_genero` varchar(50),
	PRIMARY KEY (`id_genero`)
);

CREATE TABLE `seccion` (
	`id_seccion` int NOT NULL AUTO_INCREMENT,
	`numero_seccion` int,
	`titulo_seccion` varchar(100),
	`contenido_seccion` TEXT,
	PRIMARY KEY (`id_seccion`)
);

CREATE TABLE `componer_seccion` (
	`id_libro` int,
	`id_seccion` int
);

CREATE TABLE `ver_seccion` (
	`id_usuario` int,
	`id_seccion` int,
	`fecha_lectura_ver_seccion` DATE
);

CREATE TABLE `autor` (
	`id_autor` int NOT NULL AUTO_INCREMENT,
	`nombre_autor` varchar(80),
	`url_imagen_autor` varchar(300),
	`informacion_autor` TEXT,
	PRIMARY KEY (`id_autor`)
);

CREATE TABLE `autores_libro` (
	`id_libro` int,
	`id_autor` int
);

ALTER TABLE `usuario` ADD CONSTRAINT `usuario_fk0` FOREIGN KEY (`id_tipo_usuario`) REFERENCES `tipo_usuario`(`id_tipo_usuario`);

ALTER TABLE `generos_libro` ADD CONSTRAINT `generos_libro_fk0` FOREIGN KEY (`id_libro`) REFERENCES `libro`(`id_libro`);

ALTER TABLE `generos_libro` ADD CONSTRAINT `generos_libro_fk1` FOREIGN KEY (`id_genero`) REFERENCES `genero`(`id_genero`);

ALTER TABLE `componer_seccion` ADD CONSTRAINT `componer_seccion_fk0` FOREIGN KEY (`id_libro`) REFERENCES `libro`(`id_libro`);

ALTER TABLE `componer_seccion` ADD CONSTRAINT `componer_seccion_fk1` FOREIGN KEY (`id_seccion`) REFERENCES `seccion`(`id_seccion`);

ALTER TABLE `ver_seccion` ADD CONSTRAINT `ver_seccion_fk0` FOREIGN KEY (`id_usuario`) REFERENCES `usuario`(`id_usuario`);

ALTER TABLE `ver_seccion` ADD CONSTRAINT `ver_seccion_fk1` FOREIGN KEY (`id_seccion`) REFERENCES `seccion`(`id_seccion`);

ALTER TABLE `autores_libro` ADD CONSTRAINT `autores_libro_fk0` FOREIGN KEY (`id_libro`) REFERENCES `libro`(`id_libro`);

ALTER TABLE `autores_libro` ADD CONSTRAINT `autores_libro_fk1` FOREIGN KEY (`id_autor`) REFERENCES `autor`(`id_autor`);











