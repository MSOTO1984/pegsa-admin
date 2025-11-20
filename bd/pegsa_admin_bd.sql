create database pegsa_admin_bd;
use pegsa_admin_bd;

CREATE TABLE tab_perfiles (
  codPerfil int NOT NULL AUTO_INCREMENT,
  nomPerfil varchar(60) NOT NULL,
  usuarioCreacion varchar(40) NOT NULL,
  fechaCreacion datetime NOT NULL,
  usuarioEdicion varchar(40) DEFAULT NULL,
  fechaEdicion datetime DEFAULT NULL,
  PRIMARY KEY (codPerfil),
  CONSTRAINT UNIQUE_NOMBRE_PERFILES UNIQUE (nomPerfil)
);

INSERT INTO tab_perfiles (codPerfil, nomPerfil, usuarioCreacion, fechaCreacion, usuarioEdicion, fechaEdicion) VALUES
(1, 'ADMINISTRADOR', '800000001', now(), NULL, NULL),
(2, 'CAPACITADOR', '800000001', now(), NULL, NULL);

CREATE TABLE tab_tipo_estado (
  codTipoEstado int NOT NULL AUTO_INCREMENT,
  nomTipoEstado varchar(80) NOT NULL,
  usuarioCreacion varchar(60) NOT NULL,
  fechaCreacion datetime NOT NULL,
  usuarioEdicion varchar(60) DEFAULT NULL,
  fechaEdicion datetime DEFAULT NULL,
  PRIMARY KEY (codTipoEstado),
  CONSTRAINT UNIQUE_NOMBRE_TIPO_ESTADO UNIQUE (nomTipoEstado)
);

INSERT INTO tab_tipo_estado (codTipoEstado, nomTipoEstado, usuarioCreacion, fechaCreacion, usuarioEdicion, fechaEdicion) VALUES
(1, 'APP', '800000001', now(), NULL, NULL),
(2, 'CAPACITACIONES', '800000001', now(), NULL, NULL),
(3, 'REGISTRO', '800000001', now(), NULL, NULL);

CREATE TABLE tab_estados (
  codEstado int NOT NULL AUTO_INCREMENT,
  nomEstado varchar(100) NOT NULL,
  codTipoEstado int NOT NULL,
  PRIMARY KEY (codEstado),
  CONSTRAINT UNIQUE_NOMBRE_ESTADOS UNIQUE (nomEstado),
  FOREIGN KEY (codTipoEstado) REFERENCES tab_tipo_estado(codTipoEstado)
);

INSERT INTO tab_estados (codEstado, nomEstado, codTipoEstado) VALUES
(1, 'ACTIVO', 1),
(2, 'INACTIVO', 1),
(3, 'CREADA', 2),
(4, 'DADA', 2),
(5, 'CANCELADA', 2),
(10, 'HECHO', 3),
(11, 'NO HECHO', 3);

CREATE TABLE tab_options (
  codOption int NOT NULL,
  nomOption varchar(100) NOT NULL,
  rutOption varchar(100) NOT NULL,
  levOption int NOT NULL DEFAULT '1',
  codParent int DEFAULT NULL,
  icoOption varchar(50) NOT NULL,
  codEstado int NOT NULL,
  ordOption int NOT NULL,
  PRIMARY KEY (codOption),
  FOREIGN KEY (codEstado) REFERENCES tab_estados(codEstado)
);

INSERT INTO tab_options (codOption, nomOption, rutOption, levOption, codParent, icoOption, codEstado, ordOption) VALUES
(100, 'Perfiles', '', 1, 1, 'fa fa-bar-chart-o', 1, 1),
(101, 'Gestionar Perfiles', 'perfil/perfil', 2, 100, '', 1, 1),
(200, 'Usuarios', '', 1, 1, 'fa fa-th', 1, 2),
(201, 'Gestionar Usuarios', 'usuario/gestionarUsuario', 2, 200, '', 1, 1),
(202, 'Actualizar clave', 'usuario/actualizarClave', 2, 200, '', 2, 2),
(300, 'Generales', '', 1, 1, 'fa fa-binoculars', 1, 3),
(301, 'Empleados', 'empleado/gestionarEmpleado', 2, 300, '', 1, 1),
(302, 'Capacitaci&oacute;n', 'capacitacion/capacitacion', 2, 300, '', 1, 2),
(303, 'Asistencias', 'asistencia/asistencia', 2, 300, '', 1, 3);

CREATE TABLE tab_permisos (
  codPermiso int NOT NULL AUTO_INCREMENT,
  codPerfil int NOT NULL,
  codOption int NOT NULL,
  usuarioCreacion varchar(40) NOT NULL,
  fechaCreacion datetime NOT NULL,
  PRIMARY KEY (codPermiso, codPerfil, codOption),
  FOREIGN KEY (codPerfil) REFERENCES tab_perfiles(codPerfil),
  FOREIGN KEY (codOption) REFERENCES tab_options(codOption) 
);

INSERT INTO tab_permisos (codPermiso, codPerfil, codOption, usuarioCreacion, fechaCreacion) VALUES
(1, 1, 101, '800000001', now()),
(2, 1, 201, '800000001', now()),
(3, 1, 301, '800000001', now()),
(4, 2, 302, '800000001', now()),
(5, 2, 303, '800000001', now());

CREATE TABLE tab_paises (
  codPais int NOT NULL AUTO_INCREMENT,
  nomPais varchar(45) NOT NULL,
  abrPais varchar(45) DEFAULT NULL,
  PRIMARY KEY (codPais)
);

INSERT INTO tab_paises (codPais, nomPais, abrPais) VALUES
(1, 'Colombia', 'CO');

CREATE TABLE tab_deptos (
  codDepto int NOT NULL AUTO_INCREMENT,
  nomDepto varchar(45) NOT NULL,
  codPais int NOT NULL,
  PRIMARY KEY (codDepto),
  FOREIGN KEY (codPais) REFERENCES tab_paises(codPais)
);

INSERT INTO tab_deptos (codDepto, nomDepto, codPais) VALUES
(1, 'Antioquia', 1),
(2, 'Atlántico', 1),
(3, 'Bolí­var', 1),
(4, 'Boyacá', 1),
(5, 'Caldas', 1),
(6, 'Cauca', 1),
(7, 'Cesar', 1),
(8, 'Córdoba', 1),
(9, 'Cundinamarca', 1),
(10, 'Guajira', 1),
(11, 'Huila', 1),
(12, 'Magdalena', 1),
(13, 'Meta', 1),
(14, 'Nariño', 1),
(15, 'Norte de Santander', 1),
(16, 'Quindí­o', 1),
(17, 'Risaralda', 1),
(18, 'Santander', 1),
(19, 'Sucre', 1),
(20, 'Tolima', 1),
(21, 'Valle del Cauca', 1),
(22, 'Casanare', 1),
(23, 'San Andrés y Providencia', 1);

CREATE TABLE tab_ciudades (
  codCiudad int NOT NULL AUTO_INCREMENT,
  nomCiudad varchar(45) DEFAULT NULL,
  abrCiudad varchar(45) DEFAULT NULL,
  codDepto int NOT NULL,
  PRIMARY KEY (codCiudad),
  FOREIGN KEY (codDepto) REFERENCES tab_deptos(codDepto)
);

INSERT INTO tab_ciudades (codCiudad, nomCiudad, abrCiudad, codDepto) VALUES
(1, 'Bello', 'BEL', 1),
(2, 'Caldas', 'CAL', 1),
(3, 'Copacabana', 'COP', 1),
(4, 'Envigado', 'ENV', 1),
(5, 'Guarne', 'GUA', 1),
(6, 'Itagui', 'ITA', 1),
(7, 'La Ceja', 'LA ', 1),
(8, 'La Estrella', 'LA ', 1),
(9, 'La Tablaza', 'LA ', 1),
(10, 'Marinilla', 'MAR', 1),
(11, 'Medellí­n', 'MED', 1),
(12, 'Rionegro', 'RIO', 1),
(13, 'Sabaneta', 'SAB', 1),
(14, 'San Antonio de Prado', 'SAN', 1),
(15, 'San Cristóbal', 'SAN', 1),
(16, 'Caucasia', 'CAU', 1),
(17, 'Barranquilla', 'BAR', 2),
(18, 'Malambo', 'MAL', 2),
(19, 'Puerto Colombia', 'PUE', 2),
(20, 'Soledad', 'SOL', 2),
(21, 'Arjona', 'ARJ', 3),
(22, 'Bayunca', 'BAY', 3),
(23, 'Carmen de Bolí­var', 'CAR', 3),
(24, 'Cartagena', 'CAR', 3),
(25, 'Turbaco', 'TUR', 3),
(26, 'Arcabuco', 'ARC', 4),
(27, 'Belencito', 'BEL', 4),
(28, 'Chiquinquirá', 'CHI', 4),
(29, 'Combita', 'COM', 4),
(30, 'Cucaita', 'CUC', 4),
(31, 'Duitama', 'DUI', 4),
(32, 'Mongui', 'MON', 4),
(33, 'Nobsa', 'NOB', 4),
(34, 'Paipa', 'PAI', 4),
(35, 'Puerto Boyacá', 'PUE', 4),
(36, 'Ráquira', 'RÁQ', 4),
(37, 'Samaca', 'SAM', 4),
(38, 'Santa Rosa de Viterbo', 'SAN', 4),
(39, 'Sogamoso', 'SOG', 4),
(40, 'Sutamerchán', 'SUT', 4),
(41, 'Tibasosa', 'TIB', 4),
(42, 'Tinjaca', 'TIN', 4),
(43, 'Tunja', 'TUN', 4),
(44, 'Ventaquemada', 'VEN', 4),
(45, 'Villa de Leiva', 'VIL', 4),
(46, 'La Dorada', 'LA ', 5),
(47, 'Manizales', 'MAN', 5),
(48, 'Villamaria', 'VIL', 5),
(49, 'Caloto', 'CAL', 6),
(50, 'Ortigal', 'ORT', 6),
(51, 'Piendamo', 'PIE', 6),
(52, 'Popayán', 'POP', 6),
(53, 'Puerto Tejada', 'PUE', 6),
(54, 'Santander Quilichao', 'SAN', 6),
(55, 'Tunia', 'TUN', 6),
(56, 'Villarica', 'VIL', 6),
(57, 'Valledupar', 'VAL', 7),
(58, 'Cerete', 'CER', 8),
(59, 'Monterí­a', 'MON', 8),
(60, 'Planeta Rica', 'PLA', 8),
(61, 'Alban', 'ALB', 9),
(62, 'Bogotá', 'BOG', 9),
(63, 'Bojaca', 'BOJ', 9),
(64, 'Bosa', 'BOS', 9),
(65, 'Briceño', 'BRI', 9),
(66, 'Cajicá', 'CAJ', 9),
(67, 'Chí­a', 'CHÍ', 9),
(68, 'Chinauta', 'CHI', 9),
(69, 'Choconta', 'CHO', 9),
(70, 'Cota', 'COT', 9),
(71, 'El Muña', 'EL ', 9),
(72, 'El Rosal', 'EL ', 9),
(73, 'Engativá', 'ENG', 9),
(74, 'Facatativa', 'FAC', 9),
(75, 'Fontibón', 'FON', 9),
(76, 'Funza', 'FUN', 9),
(77, 'Fusagasuga', 'FUS', 9),
(78, 'Gachancipá', 'GAC', 9),
(79, 'Girardot', 'GIR', 9),
(80, 'Guaduas', 'GUA', 9),
(81, 'Guayavetal', 'GUA', 9),
(82, 'La Calera', 'LA ', 9),
(83, 'La Caro', 'LA ', 9),
(84, 'Madrid', 'MAD', 9),
(85, 'Mosquera', 'MOS', 9),
(86, 'Nemocón', 'NEM', 9),
(87, 'Puente Piedra', 'PUE', 9),
(88, 'Puente Quetame', 'PUE', 9),
(89, 'Puerto Bogotá', 'PUE', 9),
(90, 'Puerto Salgar', 'PUE', 9),
(91, 'Quetame', 'QUE', 9),
(92, 'Sasaima', 'SAS', 9),
(93, 'Sesquile', 'SES', 9),
(94, 'Sibaté', 'SIB', 9),
(95, 'Silvania', 'SIL', 9),
(96, 'Simijaca', 'SIM', 9),
(97, 'Soacha', 'SOA', 9),
(98, 'Sopo', 'SOP', 9),
(99, 'Suba', 'SUB', 9),
(100, 'Subachoque', 'SUB', 9),
(101, 'Susa', 'SUS', 9),
(102, 'Tabio', 'TAB', 9),
(103, 'Tenjo', 'TEN', 9),
(104, 'Tocancipa', 'TOC', 9),
(105, 'Ubaté', 'UBA', 9),
(106, 'Usaquén', 'USA', 9),
(107, 'Usme', 'USM', 9),
(108, 'Villapinzón', 'VIL', 9),
(109, 'Villeta', 'VIL', 9),
(110, 'Zipaquirá', 'ZIP', 9),
(111, 'Maicao', 'MAI', 10),
(112, 'Riohacha', 'RIO', 10),
(113, 'Aipe', 'AIP', 11),
(114, 'Neiva', 'NEI', 11),
(115, 'Cienaga', 'CIE', 12),
(116, 'Gaira', 'GAI', 12),
(117, 'Rodadero', 'ROD', 12),
(118, 'Santa Marta', 'SAN', 12),
(119, 'Taganga', 'TAG', 12),
(120, 'Villavicencio', 'VIL', 13),
(121, 'Ipiales', 'IPI', 14),
(122, 'Pasto', 'PAS', 14),
(123, 'Cúcuta', 'CÚC', 15),
(124, 'El Zulia', 'EL ', 15),
(125, 'La Parada', 'LA ', 15),
(126, 'Los Patios', 'LOS', 15),
(127, 'Villa del Rosario', 'VIL', 15),
(128, 'Armenia', 'ARM', 16),
(129, 'Calarcá', 'CAL', 16),
(130, 'Circasia', 'CIR', 16),
(131, 'La Tebaida', 'LA ', 16),
(132, 'Montenegro', 'MON', 16),
(133, 'Quimbaya', 'QUI', 16),
(134, 'Dosquebradas', 'DOS', 17),
(135, 'Pereira', 'PER', 17),
(136, 'Aratoca', 'ARA', 18),
(137, 'Barbosa', 'BAR', 18),
(138, 'Bucaramanga', 'BUC', 18),
(139, 'Floridablanca', 'FLO', 18),
(140, 'Girón', 'GIR', 18),
(141, 'Lebrija', 'LEB', 18),
(142, 'Oiba', 'OIB', 18),
(143, 'Piedecuesta', 'PIE', 18),
(144, 'Pinchote', 'PIN', 18),
(145, 'San Gil', 'SAN', 18),
(146, 'Socorro', 'SOC', 18),
(147, 'Sincelejo', 'SIN', 19),
(148, 'Armero', 'ARM', 20),
(149, 'Buenos Aires', 'BUE', 20),
(150, 'Castilla', 'CAS', 20),
(151, 'Espinal', 'ESP', 20),
(152, 'Flandes', 'FLA', 20),
(153, 'Guamo', 'GUA', 20),
(154, 'Honda', 'HON', 20),
(155, 'Ibagué', 'IBA', 20),
(156, 'Mariquita', 'MAR', 20),
(157, 'Melgar', 'MEL', 20),
(158, 'Natagaima', 'NAT', 20),
(159, 'Payande', 'PAY', 20),
(160, 'Purificación', 'PUR', 20),
(161, 'Saldaña', 'SAL', 20),
(162, 'Tolemaida', 'TOL', 20),
(163, 'Amaime', 'AMA', 21),
(164, 'Andalucí­a', 'AND', 21),
(165, 'Buenaventura', 'BUE', 21),
(166, 'Buga', 'BUG', 21),
(167, 'Buga La Grande', 'BUG', 21),
(168, 'Caicedonia', 'CAI', 21),
(169, 'Cali', 'CAL', 21),
(170, 'Candelaria', 'CAN', 21),
(171, 'Cartago', 'CAR', 21),
(172, 'Cavasa', 'CAV', 21),
(173, 'Costa Rica', 'COS', 21),
(174, 'Dagua', 'DAG', 21),
(175, 'El Carmelo', 'EL ', 21),
(176, 'El Cerrito', 'EL ', 21),
(177, 'El Placer', 'EL ', 21),
(178, 'Florida', 'FLO', 21),
(179, 'Ginebra', 'GIN', 21),
(180, 'Guacarí­', 'GUA', 21),
(181, 'Jamundi', 'JAM', 21),
(182, 'La Paila', 'LA ', 21),
(183, 'La Unión', 'LA ', 21),
(184, 'La Victoria', 'LA ', 21),
(185, 'Loboguerrero', 'LOB', 21),
(186, 'Palmira', 'PAL', 21),
(187, 'Pradera', 'PRA', 21),
(188, 'Roldanillo', 'ROL', 21),
(189, 'Rozo', 'ROZ', 21),
(190, 'San Pedro', 'SAN', 21),
(191, 'Sevilla', 'SEV', 21),
(192, 'Sonso', 'SON', 21),
(193, 'Tulúa', 'TUL', 21),
(194, 'Vijes', 'VIJ', 21),
(195, 'Villa Gorgona', 'VIL', 21),
(196, 'Yotoco', 'YOT', 21),
(197, 'Yumbo', 'YUM', 21),
(198, 'Zarzal', 'ZAR', 21),
(199, 'Yopal', 'YOP', 22),
(200, 'San Andrés', 'SAN', 23);

CREATE TABLE tab_generos (
  codGenero int NOT NULL AUTO_INCREMENT,
  nomGenero varchar(25) NOT NULL,
  sigla varchar(2) NOT NULL,
  PRIMARY KEY (codGenero),
  CONSTRAINT UNIQUE_NOMBRE_GENERO UNIQUE (nomGenero)
);

INSERT INTO tab_generos (codGenero, nomGenero, sigla) VALUES
(1, 'Masculino', 'M'),
(2, 'Femenino', 'F');

CREATE TABLE tab_usuarios (
  codUsuario varchar(40) NOT NULL,
  nomUsuario varchar(100) NOT NULL,
  emailUsuario varchar(100) NOT NULL,
  celUsuario BIGINT(20) NOT NULL,
  codGenero int NOT NULL,
  direccion varchar(100) NOT NULL,
  codCiudad int NOT NULL,
  codPerfil int NOT NULL,
  claveUsuario varchar(200) NOT NULL,
  codEstado int NOT NULL,
  isChangePass Boolean DEFAULT FALSE,
  usuarioCreacion varchar(40) NOT NULL,
  fechaCreacion datetime NOT NULL,
  usuarioEdicion varchar(40) DEFAULT NULL,
  fechaEdicion datetime DEFAULT NULL,
  PRIMARY KEY (codUsuario),
  CONSTRAINT UNIQUE_USUARIO_EMAIL_USUARIOS UNIQUE (codUsuario,emailUsuario),
  FOREIGN KEY (codGenero) REFERENCES tab_generos(codGenero),
  FOREIGN KEY (codCiudad) REFERENCES tab_ciudades(codCiudad),
  FOREIGN KEY (codPerfil) REFERENCES tab_perfiles(codPerfil),
  FOREIGN KEY (codEstado) REFERENCES tab_estados(codEstado) 
);

INSERT INTO tab_usuarios (codUsuario, nomUsuario, emailUsuario, celUsuario, codGenero, direccion, codCiudad, codPerfil, claveUsuario, codEstado, usuarioCreacion, fechaCreacion, usuarioEdicion, fechaEdicion) VALUES
('800000001', 'Juan Cardenas', 'juanCardenaz@gmail.com', 3102627700, 1, 'Calle x Carrera', 62, 1, '72d19a1e69dc5b08bf157de69fe5b4a04475ae63', 1, '800000001', now(), NULL, NULL),
('800000002', 'Natalia Cuesta', 'nataliaCuesta@gmail.com', 3102627701, 2, 'Calle x Carrera', 62, 2, '27ae621adcc185f7962e80214faaace8aa657c0b', 1, '800000001', now(), NULL, NULL);

-- juanCardenaz@gmail.com -> 800000001
-- nataliaCuesta@gmail.com -> 800000002

CREATE TABLE tab_empleados (
  codEmpleado varchar(40) NOT NULL,
  nomEmpleado varchar(100) NOT NULL,
  emailEmpleado varchar(100) NOT NULL,
  celEmpleado BIGINT(20) NOT NULL,
  codGenero int NOT NULL,
  direccion varchar(100) NOT NULL,
  codCiudad int NOT NULL,
  codEstado int NOT NULL,
  usuarioCreacion varchar(40) NOT NULL,
  fechaCreacion datetime NOT NULL,
  usuarioEdicion varchar(40) DEFAULT NULL,
  fechaEdicion datetime DEFAULT NULL,
  PRIMARY KEY (codEmpleado),
  CONSTRAINT UNIQUE_USUARIO_EMAIL_EMPLEADOS UNIQUE (nomEmpleado,emailEmpleado),
  FOREIGN KEY (codGenero) REFERENCES tab_generos(codGenero),
  FOREIGN KEY (codCiudad) REFERENCES tab_ciudades(codCiudad),
  FOREIGN KEY (codEstado) REFERENCES tab_estados(codEstado) 
);

INSERT INTO tab_empleados(codEmpleado, nomEmpleado, emailEmpleado, celEmpleado, codGenero, direccion, codCiudad, codEstado, usuarioCreacion, fechaCreacion)
VALUES
('20000001', 'Carlos Pérez', 'carlos.perez@example.com', 3001112233, 1, 'Calle 10 #5-20', 62, 1, '800000001', NOW()),
('20000002', 'María Gómez', 'maria.gomez@example.com', 3001112234, 2, 'Carrera 15 #8-22', 62, 1, '800000001', NOW()),
('20000003', 'Juan Torres', 'juan.torres@example.com', 3001112235, 1, 'Av 7 #20-11', 62, 1, '800000001', NOW()),
('20000004', 'Laura Sánchez', 'laura.sanchez@example.com', 3001112236, 2, 'Calle 22 #14-90', 62, 1, '800000001', NOW()),
('20000005', 'Pedro Rojas', 'pedro.rojas@example.com', 3001112237, 1, 'Calle 30 #19-15', 62, 1, '800000001', NOW()),
('20000006', 'Ana Castillo', 'ana.castillo@example.com', 3001112238, 2, 'Carrera 12 #33-18', 62, 1, '800000001', NOW()),
('20000007', 'Diego Martínez', 'diego.martinez@example.com', 3001112239, 1, 'Calle 45 #7-09', 62, 1, '800000001', NOW()),
('20000008', 'Lucía Vega', 'lucia.vega@example.com', 3001112240, 2, 'Transversal 9 #10-44', 62, 1, '800000001', NOW()),
('20000009', 'Felipe Navarro', 'felipe.navarro@example.com', 3001112241, 1, 'Calle 5 #3-80', 62, 1, '800000001', NOW()),
('20000010', 'Sofía Duarte', 'sofia.duarte@example.com', 3001112242, 2, 'Carrera 17 #8-40', 62, 1, '800000001', NOW()),
('20000011', 'Miguel Herrera', 'miguel.herrera@example.com', 3001112243, 1, 'Calle 9 #21-33', 62, 1, '800000001', NOW()),
('20000012', 'Camila López', 'camila.lopez@example.com', 3001112244, 2, 'Carrera 4 #3-19', 62, 1, '800000001', NOW()),
('20000013', 'Andrés Cárdenas', 'andres.cardenas@example.com', 3001112245, 1, 'Av 11 #55-12', 62, 1, '800000001', NOW()),
('20000014', 'Valentina Mora', 'valentina.mora@example.com', 3001112246, 2, 'Calle 1 #9-77', 62, 1, '800000001', NOW()),
('20000015', 'Jorge Díaz', 'jorge.diaz@example.com', 3001112247, 1, 'Carrera 40 #22-09', 62, 1, '800000001', NOW()),
('20000016', 'Daniela Ruiz', 'daniela.ruiz@example.com', 3001112248, 2, 'Calle 25 #16-30', 62, 1, '800000001', NOW()),
('20000017', 'Oscar Medina', 'oscar.medina@example.com', 3001112249, 1, 'Calle 6 #2-12', 62, 1, '800000001', NOW()),
('20000018', 'Paula Andrade', 'paula.andrade@example.com', 3001112250, 2, 'Carrera 18 #6-60', 62, 1, '800000001', NOW()),
('20000019', 'Ricardo Silva', 'ricardo.silva@example.com', 3001112251, 1, 'Calle 14 #9-02', 62, 1, '800000001', NOW()),
('20000020', 'Diana León', 'diana.leon@example.com', 3001112252, 2, 'Calle 8 #33-18', 62, 1, '800000001', NOW()),
('20000021', 'Hernán Torres', 'hernan.torres@example.com', 3001112253, 1, 'Carrera 29 #14-01', 62, 1, '800000001', NOW()),
('20000022', 'Karina Robles', 'karina.robles@example.com', 3001112254, 2, 'Calle 3 #17-99', 62, 1, '800000001', NOW()),
('20000023', 'Tomás Aguilar', 'tomas.aguilar@example.com', 3001112255, 1, 'Av 9 #30-27', 62, 1, '800000001', NOW()),
('20000024', 'Elena Pinto', 'elena.pinto@example.com', 3001112256, 2, 'Calle 26 #12-14', 62, 1, '800000001', NOW()),
('20000025', 'Sebastián Nieto', 'sebastian.nieto@example.com', 3001112257, 1, 'Carrera 1 #7-70', 62, 1, '800000001', NOW());

CREATE TABLE tab_tipo_capacitacion (
  codTipoCapacitacion int NOT NULL AUTO_INCREMENT,
  nomTipoCapacitacion varchar(80) NOT NULL,
  usuarioCreacion varchar(60) NOT NULL,
  fechaCreacion datetime NOT NULL,
  usuarioEdicion varchar(60) DEFAULT NULL,
  fechaEdicion datetime DEFAULT NULL,
  PRIMARY KEY (codTipoCapacitacion),
  CONSTRAINT UNIQUE_NOMBRE_TIPO_CAPACITACION UNIQUE (nomTipoCapacitacion)
);

INSERT INTO tab_tipo_capacitacion (codTipoCapacitacion, nomTipoCapacitacion, usuarioCreacion, fechaCreacion, usuarioEdicion, fechaEdicion) VALUES
(1, 'Seguridad en el Trabajo', '800000001', now(), NULL, NULL);

CREATE TABLE tab_capacitaciones (
  codCapacitacion int NOT NULL AUTO_INCREMENT,
  nomCapacitacion varchar(100) NOT NULL,
  fecha varchar(10) NOT NULL,
  tiempo int DEFAULT 0,
  asistencia int DEFAULT 0,
  observacion TEXT DEFAULT NULL,
  codTipoCapacitacion int NOT NULL,
  codUsuario varchar(40) NOT NULL,
  codCiudad int NOT NULL,
  codEstado int NOT NULL,
  usuarioCreacion varchar(40) NOT NULL,
  fechaCreacion datetime NOT NULL,
  usuarioEdicion varchar(40) DEFAULT NULL,
  fechaEdicion datetime DEFAULT NULL,
  PRIMARY KEY (codCapacitacion),
  FOREIGN KEY (codTipoCapacitacion) REFERENCES tab_tipo_capacitacion(codTipoCapacitacion),
  FOREIGN KEY (codUsuario) REFERENCES tab_usuarios(codUsuario),
  FOREIGN KEY (codCiudad) REFERENCES tab_ciudades(codCiudad),
  FOREIGN KEY (codEstado) REFERENCES tab_estados(codEstado) 
);

INSERT INTO tab_capacitaciones (codCapacitacion, nomCapacitacion, fecha, tiempo, asistencia, observacion, codTipoCapacitacion, codUsuario, codCiudad, codEstado, usuarioCreacion, fechaCreacion, usuarioEdicion, fechaEdicion) 
VALUES (1, 'Errores en las alturas', '20/11/2025', 30, 0, 'Los errores en las alturas son fallas humanas o técnicas que ocurren durante trabajos elevados y que pueden generar riesgos graves para la integridad y seguridad.', 2, '800000002', 62, 3, '800000002', '2025-11-12 19:00:19', '20000001', '2025-11-18 20:31:45');

CREATE TABLE tab_asistencias (
  codAsistencia int NOT NULL AUTO_INCREMENT,
  codCapacitacion int NOT NULL,
  codEmpleado varchar(40) NOT NULL,
  fecha varchar(10) NOT NULL,
  codEvaluacion BIGINT(20) DEFAULT NULL,
  usuarioCreacion varchar(40) NOT NULL,
  fechaCreacion datetime NOT NULL,
  usuarioEdicion varchar(40) DEFAULT NULL,
  fechaEdicion datetime DEFAULT NULL,
  PRIMARY KEY (codAsistencia),
  FOREIGN KEY (codCapacitacion) REFERENCES tab_capacitaciones(codCapacitacion),
  FOREIGN KEY (codEmpleado) REFERENCES tab_empleados(codEmpleado) 
);

DELIMITER //

CREATE PROCEDURE registrarAsistencia (
    IN p_codCapacitacion INT,
    IN p_codEmpleado VARCHAR(30),
    IN p_codEvaluacion INT
)
BEGIN
    DECLARE v_contador INT DEFAULT 0;
    DECLARE v_empleados INT DEFAULT 0;
    DECLARE v_codEstado INT DEFAULT 3;
    DECLARE v_asistencia INT DEFAULT 0;

    SELECT asistencia INTO v_contador 
    FROM tab_capacitaciones 
    WHERE codCapacitacion = p_codCapacitacion AND codEstado = 3;

    SELECT COUNT(*) INTO v_empleados 
    FROM tab_empleados 
    WHERE codEstado = 1;

    INSERT INTO tab_asistencias(codCapacitacion, codEmpleado, codEvaluacion, fecha, usuarioCreacion, fechaCreacion) 
    VALUES (p_codCapacitacion, p_codEmpleado, p_codEvaluacion, NOW(), p_codEmpleado, NOW() );

    SET v_asistencia = v_contador + 1;
    IF v_asistencia = v_empleados THEN
        SET v_codEstado = 4;
    END IF;

    UPDATE tab_capacitaciones 
    SET 
        codEstado = v_codEstado,
        asistencia = v_asistencia,
        usuarioEdicion = p_codEmpleado,
        fechaEdicion = NOW()
    WHERE codCapacitacion = p_codCapacitacion AND codEstado = 3;

    COMMIT;

END//

DELIMITER ;
