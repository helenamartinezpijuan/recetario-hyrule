-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-03-2026 a las 13:31:39
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `recetario_hyrule`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `efectos`
--

CREATE TABLE `efectos` (
  `id_efecto` int(11) NOT NULL COMMENT 'Clave primaria del efecto',
  `id_tipo_efecto` int(11) NOT NULL,
  `imagen` varchar(100) NOT NULL COMMENT 'Ruta al archivo de imagen del efecto.',
  `descripcion` varchar(150) NOT NULL COMMENT 'Descripción del efecto (power-up)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `efectos`
--

INSERT INTO `efectos` (`id_efecto`, `id_tipo_efecto`, `imagen`, `descripcion`) VALUES
(1, 1, '', 'Aumenta la resistencia a la electricidad'),
(2, 2, '', 'Aumenta la resistencia al calor'),
(3, 3, '', 'Aumenta la resistencia al fuego'),
(4, 4, '', 'Aumenta la resistencia al frío'),
(5, 5, '', 'Aumenta la defensa'),
(6, 6, '', 'Aumenta la velocidad'),
(7, 7, '', 'Aumenta el ataque'),
(8, 8, '', 'Aumenta el sigilo'),
(9, 9, '', 'Aporta resistencia extra'),
(10, 10, '', 'Recupera la resistencia'),
(11, 11, '', 'Otorga corazones extra'),
(12, 12, '', 'Recupera corazones sin efectos adicionales');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingredientes`
--

CREATE TABLE `ingredientes` (
  `id_ingrediente` int(11) NOT NULL COMMENT 'Clave primaria del ingrediente',
  `nombre` varchar(50) NOT NULL COMMENT 'Nombre del ingrediente',
  `imagen` varchar(100) NOT NULL COMMENT 'Ruta al archivo de imagen del ingrediente',
  `descripcion` varchar(250) NOT NULL COMMENT 'Breve descripción del ingrediente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingredientes`
--

INSERT INTO `ingredientes` (`id_ingrediente`, `nombre`, `imagen`, `descripcion`) VALUES
(1, 'Carne de caza', 'carne-caza.webp', 'Carne cruda obtenida de animales pequeños como jabalíes o zorros. Un ingrediente básico para cocinar.'),
(2, 'Carne de caza XL', 'carne-caza-xl.webp', 'Un corte de carne de mayor tamaño, proveniente de animales grandes como osos o alces. Muy nutritiva.'),
(3, 'Carne de caza XXL', 'carne-caza-xxl.webp', 'El corte de carne más grande y jugoso, obtenido de los animales más imponentes de Hyrule.'),
(4, 'Carne de ave', 'carne-ave.webp', 'Pechuga de ave cruda, obtenida de pájaros y gallinas. Una carne blanca y magra.'),
(5, 'Carne de ave XL', 'carne-ave-xl.webp', 'Una generosa porción de carne de ave, perfecta para platos contundentes.'),
(6, 'Carne de ave XXL', 'carne-ave-xxl.webp', 'La pieza más grande de carne de ave, tierna y llena de sabor.'),
(7, 'Pescado', 'pescado.webp', 'Pescado fresco de río o lago. Un ingrediente versátil para una gran variedad de platos.'),
(8, 'Lubina de Hyrule', 'lubina-hyrule.webp', 'Un pescado común que se encuentra en los ríos y lagos de toda Hyrule. Sencillo pero sabroso.'),
(9, 'Salmón vivaz', 'salmon-vivaz.webp', 'Un pez de agua fría con aletas de un vibrante color rojo. Muy apreciado por su sabor.'),
(10, 'Carpa tricolor', 'carpa-tricolor.webp', 'Una carpa ornamental de escamas brillantes de tres colores. Su carne es un manjar exquisito.'),
(11, 'Dorada', 'dorada.webp', 'Un pez de escamas doradas que habita en el mar de Necluda. Su carne es firme y deliciosa.'),
(12, 'Cangrejo', 'cangrejo.webp', 'Un crustáceo que se esconde en las rocas cerca del agua. Su carne es dulce y sabrosa.'),
(13, 'Arroz de Hyrule', 'arroz-hyrule.webp', 'Arroz de grano corto y textura pegajosa, cultivado en las praderas de Hyrule. La base de muchos platos.'),
(14, 'Trigo de Tabanta', 'trigo-tabanta.webp', 'Un cereal de alta calidad cultivado en la región de Tabanta. Perfecto para repostería.'),
(15, 'Caña de azúcar', 'cana-azucar.webp', 'Una planta alta de la que se extrae un jugo dulce. Esencial para preparar postres.'),
(16, 'Halita', 'sal-gema.webp', 'Sal cristalizada extraída de las minas de Hebra. Realza el sabor natural de cualquier ingrediente.'),
(17, 'Manteca de cabra', 'manteca-cabra.webp', 'Una grasa láctea rica y cremosa, elaborada con leche de cabra. Aporta untuosidad a los platos.'),
(18, 'Manzana', 'manzana.webp', 'La fruta más común de Hyrule. Dulce, refrescante y fácil de encontrar en cualquier bosque.'),
(19, 'Frambuesa', 'frambuesa.webp', 'Fruta que proviene de regiones frías y nevadas. Posee un sabor dulce.'),
(20, 'Coco', 'coco.webp', 'El fruto de las palmeras de la costa de Necluda. Su carne es fibrosa y su agua, refrescante.'),
(21, 'Fruta Electro', 'fruta-electro.webp', 'Esta fruta amarilla y puntiaguda almacena electricidad estática en su interior.'),
(22, 'Baya ígnea', 'baya-ignea.webp', 'Una baya roja que crece en las regiones más cálidas. Al comerla, produce un agradable calor corporal.'),
(23, 'Sandía Gélida', 'sandia-gelida.webp', 'Una sandía de pulpa azulada que crece en el desierto. Su interior está siempre frío y refrescante.'),
(24, 'Hierba de Hyrule', 'hierba-hyrule.webp', 'La hierba más común en Hyrule. Se puede cocinar para obtener un bocado rápido.'),
(25, 'Hierba Gélida', 'hierba-gelida.webp', 'Una hierba de aroma fresco que crece en las cumbres más altas de las montañas.'),
(26, 'Hierba Ígnea', 'hierba-ignea.webp', 'Una hierba que retiene el calor del suelo volcánico de la región de Eldin.'),
(27, 'Hierba Electro', 'hierba-electro.webp', 'Una hierba con pequeñas cargas eléctricas que crece cerca de los depósitos de minerales.'),
(28, 'Flor sigilosa', 'flor-sigilosa.webp', 'Una flor azulada con un aroma apenas perceptible que crece en zonas umbrías.'),
(29, 'Princesa de la Calma', 'princesa-calma.webp', 'Una flor extremadamente rara y bella que crece en lo más profundo de los bosques de Hyrule.'),
(30, 'Zanahoria Rauda', 'zanahoria-rauda.webp', 'Una zanahoria de color púrpura vibrante que crece en las praderas de Hyrule.'),
(31, 'Zanahoria Briosa', 'zanahoria-briosa.webp', 'Esta zanahoria de color claro es muy apreciada por los jinetes de la región de Tabanta.'),
(32, 'Calabaza Robusta', 'calabaza-robusta.webp', 'Una calabaza de piel anaranjada y carne muy firme que crece en la aldea de Kakariko.'),
(33, 'Plátano Recio', 'platano-recio.webp', 'El cultivo estrella de la región de Farone. Muy dulce y energético.'),
(34, 'Huevo de ave', 'huevo-ave.webp', 'Un huevo de gallina común. Muy nutritivo y esencial para tortillas y repostería.'),
(35, 'Halita', 'halita.webp', 'Un condimento mineral extraído de las rocas. Se usa para dar un toque crujiente y salado a los platos.'),
(36, 'Miel de vigor', 'miel-vigor.webp', 'Miel de abejas extra dulce con propiedades tonificantes. Muy apreciada en repostería.'),
(37, 'Seta de Hyrule', 'seta-hyrule.webp', 'La seta más común y versátil. Crece en troncos y rocas por toda Hyrule.'),
(38, 'Seta Gélida', 'seta-gelida.webp', 'Una seta que se siente fría al tacto y crece en las zonas más elevadas de las montañas.'),
(39, 'Seta Ígnea', 'seta-ignea.webp', 'Una seta que crece en las faldas de la Montaña de la Muerte, resistiendo las altas temperaturas.'),
(40, 'Seta Electro', 'seta-electro.webp', 'Una seta de color amarillo que emite pequeñas chispas y crece cerca de zonas con actividad eléctrica.'),
(41, 'Seta Rauda', 'seta-rauda.webp', 'Una seta de color azul pálido que crece en las zonas húmedas cerca de cascadas y ríos.'),
(42, 'Seta Recia', 'seta-recia.webp', 'Una seta de color rojo intenso que crece en las laderas de las montañas rocosas.'),
(43, 'Seta Vigorosa', 'seta-vigorosa.webp', 'Una seta de color verde brillante que crece en los claros de los bosques después de la lluvia.'),
(44, 'Seta Briosa', 'seta-briosa.webp', 'Una seta con un sombrero amarillo y moteado que crece en las praderas soleadas.'),
(45, 'Seta Robusta', 'seta-robusta.webp', 'Una seta de color marrón oscuro con una textura especialmente firme, común en zonas boscosas.'),
(46, 'Seta Sigilosa', 'seta-sigilosa.webp', 'Una seta de color grisáceo que crece en lugares sombríos y cuevas poco iluminadas.'),
(47, 'Trucha Gélida', 'trucha-gelida.webp', 'Una trucha de aletas azules que habita en los ríos helados de las montañas de Hebra.'),
(48, 'Trucha Ígnea', 'trucha-ignea.webp', 'Un pez de aletas rojas que nada en las aguas calientes de la Cordillera de Eldin.'),
(49, 'Trucha Electro', 'trucha-electro.webp', 'Un pez que habita en los lagos de la región de Faron, donde abundan los rayos.'),
(50, 'Trucha Sigilosa', 'trucha-sigilosa.webp', 'Un pez de color oscuro que se mueve sigilosamente por las aguas tranquilas de los estanques.'),
(51, 'Carpa Recia', 'carpa-recia.webp', 'Una carpa de tonos rojizos y aletas fuertes que nada en los ríos de corriente rápida.'),
(52, 'Carpa Robusta', 'carpa-robusta.webp', 'Una carpa de escamas particularmente duras que habita en las profundidades de los lagos.'),
(53, 'Dorada Recia', 'dorada-recia.webp', 'Esta dorada tiene escamas de un tono cobrizo más intenso y habita en aguas marinas profundas.'),
(54, 'Dorada Robusta', 'dorada-robusta.webp', 'Una variedad de dorada con escamas más gruesas que se encuentra en las costas rocosas.'),
(55, 'Lubina Vigorosa', 'lubina-vigorosa.webp', 'Una lubina de escamas verdosas que nada en las desembocaduras de los ríos.'),
(56, 'Lubina Vivaz', 'lubina-vivaz.webp', 'Una lubina que almacena una gran cantidad de energía vital, común en la costa de Necluda.'),
(57, 'Cangrejo Recio', 'cangrejo-recio.webp', 'Un cangrejo de caparazón rojizo y pinzas muy fuertes que se esconde entre las rocas.'),
(58, 'Cangrejo Vigoroso', 'cangrejo-vigoroso.webp', 'Este cangrejo de movimientos rápidos habita en las pozas de marea de la costa.'),
(59, 'Caracol Vivaz', 'caracol-vivaz.webp', 'Un caracol marino de concha rosada que se adhiere a las rocas en la orilla del mar.'),
(60, 'Durian Vivaz', 'durian-vivaz.webp', 'Una fruta de color verde y amarillo, de olor fuerte pero sabor delicioso, que crece en la selva de Faron.'),
(61, 'Trufa Vivaz', 'trufa-vivaz.webp', 'Una trufa de olor intenso que crece bajo tierra en los bosques más frondosos de Hyrule.'),
(62, 'Trufón Vivaz', 'trufon-vivaz.webp', 'La variedad más grande y poderosa de la trufa, extremadamente difícil de encontrar.'),
(63, 'Rábano Vivaz', 'rabano-vivaz.webp', 'Un rábano de raíz gruesa y color rosado que crece en las tierras altas de la región de Hebra.'),
(64, 'Rábano Vivaz Grande', 'rabano-vivaz-gigante.webp', 'Un rábano de tamaño excepcional que solo crece en las condiciones más perfectas.'),
(65, 'Semilla de loto rauda', 'semilla-loto-rauda.webp', 'La semilla de una planta acuática que flota en la superficie de los estanques y lagos.'),
(66, 'Grillo Vigoroso', 'grillo-vigoroso.webp', 'Un grillo que canta por las noches y se esconde entre la hierba alta de las praderas.'),
(67, 'Rana Briosa', 'rana-briosa.webp', 'Una rana de colores vivos que habita en las charcas después de las lluvias.'),
(68, 'Rana Rauda', 'rana-rauda.webp', 'Una rana extremadamente rápida y difícil de atrapar que vive cerca de cascadas.'),
(69, 'Lagarto Ignífugo', 'lagarto-ignifugo.webp', 'Un lagarto de escamas anaranjadas que vive cerca de la lava en la Montaña de la Muerte.'),
(70, 'Lagarto Vivaz', 'lagarto-vivaz.webp', 'Un lagarto de tonos verdes que toma el sol sobre las rocas en días soleados.'),
(71, 'Lagarto Raudo', 'lagarto-raudo.webp', 'Un lagarto de patas traseras muy desarrolladas que corre velozmente por el suelo del desierto.'),
(72, 'Mariposa Gélida', 'mariposa-gelida.webp', 'Una mariposa de alas azules que revolotea en las cumbres nevadas durante el breve verano.'),
(73, 'Mariposa Ígnea', 'mariposa-ignea.webp', 'Una mariposa de alas rojas que habita cerca de las fumarolas volcánicas.'),
(74, 'Mariposa Electro', 'mariposa-electro.webp', 'Una mariposa amarilla que deja un rastro de pequeñas chispas en los prados de Faron.'),
(75, 'Libélula Gélida', 'libelula-gelida.webp', 'Una libélula que vuela sobre los lagos helados de la región de Hebra.'),
(76, 'Libélula Ígnea', 'libelula-ignea.webp', 'Una libélula que sobrevuela las pozas de agua caliente de la Cordillera de Eldin.'),
(77, 'Libélula Electro', 'libelula-electro.webp', 'Una libélula que absorbe electricidad estática del ambiente en la selva de Faron.'),
(78, 'Luciérnaga Sigilosa', 'luciernaga-sigilosa.webp', 'Una luciérnaga que emite una luz tenue y parpadeante en las cuevas más oscuras.'),
(79, 'Parte de monstruo', 'parte-monstruo.webp', 'Partes de monstruos como cuernos, garras o dientes. Útiles para crear elixires.'),
(80, 'Escarabajo Vigoroso', 'escarabajo-vigoroso.webp', 'Un escarabajo brillante que se posa en los árboles durante la noche.'),
(81, 'Escarabajo Recio', 'escarabajo-recio.webp', 'Un escarabajo de fuertes mandíbulas que se encuentra en los troncos caídos.'),
(82, 'Escarabajo Robusto', 'escarabajo-robusto.webp', 'Un escarabajo de duro caparazón que habita bajo las piedras en las regiones rocosas.'),
(83, 'Cangrejo robusto', 'cangrejo-robusto.webp', 'Cangrejo de efecto protector que se encuentra principalmente en las playas del Mar de Necluda y de Necluda oriental.'),
(84, 'Fruto de árbol', 'fruto-arbol.webp', 'Fruto encontrado en los nidos de aves, a menudo junto a sus huevos, en lugares como el bosque de los espíritus y el bosque Kolog.'),
(85, 'Leche fresca', 'leche-fresca.webp', 'Ingrediente que proviene de las vacas y cabras de la aldea Hatelia, obtenida en la tienda de la aldea.'),
(86, 'Especias Goron', 'especias-goron.webp', 'Ingrediente de gran cantidad de recetas que se puede conseguir en la tienda de Ciudad Goron.'),
(87, 'Caracol sigiloso', 'caracol-sigiloso.webp', 'Se caracteriza por brillar intensamente de color verde durante la noche.'),
(88, 'Violeta rauda', 'violeta-rauda.webp', 'Florece sobre todo en acantilados. Es muy nutritiva y, utilizada como ingrediente, aumenta la velocidad.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingredientes_localizaciones`
--

CREATE TABLE `ingredientes_localizaciones` (
  `id_ingrediente_localizacion` int(11) NOT NULL COMMENT 'Clave primaria de la relación entre ingredientes y su localización en Hyrule.',
  `id_ingrediente` int(11) NOT NULL,
  `id_localizacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingredientes_localizaciones`
--

INSERT INTO `ingredientes_localizaciones` (`id_ingrediente_localizacion`, `id_ingrediente`, `id_localizacion`) VALUES
(1, 1, 20),
(2, 2, 1),
(3, 2, 2),
(4, 2, 5),
(5, 2, 9),
(6, 3, 1),
(7, 3, 2),
(8, 3, 3),
(9, 3, 9),
(10, 4, 20),
(11, 5, 1),
(12, 5, 2),
(13, 5, 7),
(14, 6, 3),
(15, 6, 7),
(18, 7, 20),
(19, 8, 7),
(20, 8, 8),
(21, 8, 13),
(22, 8, 14),
(23, 9, 1),
(24, 9, 2),
(25, 9, 9),
(26, 9, 10),
(27, 10, 13),
(28, 10, 14),
(29, 10, 16),
(30, 12, 20),
(31, 13, 20),
(32, 14, 20),
(33, 15, 20),
(34, 17, 20),
(39, 18, 6),
(40, 18, 7),
(41, 18, 8),
(42, 18, 14),
(43, 19, 2),
(44, 19, 6),
(45, 19, 17),
(46, 20, 11),
(47, 20, 15),
(48, 20, 19),
(49, 21, 17),
(50, 21, 18),
(51, 21, 19),
(52, 22, 3),
(53, 22, 4),
(54, 22, 18),
(55, 23, 18),
(56, 24, 7),
(57, 24, 8),
(58, 24, 13),
(59, 24, 14),
(60, 25, 1),
(61, 25, 2),
(62, 25, 9),
(63, 25, 17),
(64, 26, 3),
(65, 26, 4),
(66, 26, 18),
(67, 27, 17),
(68, 27, 18),
(69, 27, 19),
(70, 28, 6),
(71, 28, 13),
(72, 28, 14),
(73, 29, 6),
(74, 29, 9),
(75, 29, 16),
(76, 29, 19),
(79, 30, 16),
(80, 31, 7),
(81, 31, 19),
(82, 32, 16),
(83, 33, 19),
(94, 34, 20),
(35, 35, 20),
(95, 36, 6),
(96, 36, 7),
(97, 36, 8),
(98, 36, 19),
(99, 37, 6),
(100, 37, 7),
(101, 37, 8),
(102, 37, 13),
(103, 38, 1),
(104, 38, 2),
(105, 38, 9),
(106, 39, 3),
(107, 39, 4),
(108, 39, 17),
(109, 40, 5),
(110, 40, 17),
(111, 40, 19),
(112, 41, 7),
(113, 41, 17),
(114, 42, 5),
(115, 42, 6),
(116, 42, 7),
(117, 43, 6),
(118, 43, 7),
(119, 43, 8),
(120, 44, 6),
(121, 44, 7),
(122, 44, 8),
(123, 45, 13),
(124, 45, 14),
(125, 46, 9),
(126, 46, 12),
(127, 46, 13),
(128, 47, 1),
(129, 47, 2),
(130, 48, 3),
(131, 48, 4),
(132, 49, 1),
(133, 49, 7),
(134, 50, 4),
(135, 50, 6),
(136, 51, 5),
(137, 51, 10),
(138, 52, 10),
(139, 52, 14),
(140, 53, 11),
(141, 53, 15),
(142, 54, 11),
(143, 54, 15),
(144, 55, 7),
(145, 55, 8),
(146, 56, 13),
(147, 56, 15),
(148, 57, 11),
(149, 57, 15),
(150, 58, 11),
(151, 58, 15),
(156, 59, 11),
(157, 59, 15),
(84, 60, 13),
(85, 60, 19),
(158, 61, 6),
(159, 61, 7),
(160, 62, 1),
(161, 62, 2),
(162, 62, 6),
(86, 63, 1),
(87, 63, 2),
(88, 63, 7),
(89, 64, 1),
(90, 64, 2),
(91, 64, 5),
(93, 65, 9),
(92, 65, 12),
(163, 66, 20),
(164, 67, 12),
(165, 67, 19),
(166, 68, 9),
(167, 68, 19),
(168, 69, 3),
(169, 69, 4),
(170, 70, 20),
(171, 71, 18),
(172, 72, 1),
(173, 72, 2),
(174, 73, 3),
(175, 73, 4),
(176, 74, 19),
(177, 75, 2),
(178, 76, 3),
(179, 77, 19),
(180, 78, 6),
(181, 78, 13),
(182, 79, 20),
(183, 80, 6),
(184, 80, 19),
(185, 81, 6),
(186, 81, 19),
(187, 82, 6),
(188, 82, 19),
(152, 83, 11),
(153, 83, 15),
(16, 84, 20),
(17, 85, 20),
(36, 86, 3),
(37, 86, 4),
(38, 86, 20),
(154, 87, 10),
(155, 87, 13),
(78, 88, 2),
(77, 88, 17);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventarios`
--

CREATE TABLE `inventarios` (
  `id_inventario` int(11) NOT NULL COMMENT 'Clave primaria de la relación entre los usuarios y los ingredientes (inventario personal)',
  `id_ingrediente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `cantidad` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Cantidad de cada ingrediente en el inventario personal del usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventarios`
--

INSERT INTO `inventarios` (`id_inventario`, `id_ingrediente`, `id_usuario`, `cantidad`) VALUES
(1, 45, 1, 3),
(2, 13, 1, 2),
(3, 1, 1, 5),
(4, 61, 1, 2),
(5, 4, 2, 3),
(6, 32, 2, 1),
(7, 86, 2, 1),
(8, 15, 3, 2),
(9, 13, 3, 5),
(10, 85, 3, 1),
(11, 37, 3, 1),
(12, 11, 4, 1),
(13, 51, 4, 1),
(14, 28, 4, 1),
(15, 44, 4, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `localizaciones`
--

CREATE TABLE `localizaciones` (
  `id_localizacion` int(11) NOT NULL COMMENT 'Clave primaria de la localización',
  `nombre` varchar(50) NOT NULL COMMENT 'Nombre de la localización',
  `region` enum('Hebra','Eldin','Akkala','Centro de Hyrule','Lanayru','Necluda','Gerudo','Farone','Sin region') NOT NULL COMMENT 'Listado con las regiones de Hyrule',
  `imagen` varchar(100) NOT NULL COMMENT 'Ruta al archivo de imagen de la localización',
  `descripcion` varchar(500) NOT NULL COMMENT 'Breve descripción de la localización'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `localizaciones`
--

INSERT INTO `localizaciones` (`id_localizacion`, `nombre`, `region`, `imagen`, `descripcion`) VALUES
(1, 'Confines de Tabanta', 'Hebra', 'confines-tabanta.png', 'Subregión de Hebra situada en la parte noroeste del mapa. La raza autóctona de la zona son los Orni, junto con otros animales que se han adaptado a las condiciones meteorológicas.'),
(2, 'Cordillera de Hebra', 'Hebra', 'cordillera-hebra.png', 'Región montañosa situada al noroeste de Hyrule, caracterizada por frío extremo, picos nevados imponentes y densas ventiscas.'),
(3, 'Valle de Eldin', 'Eldin', 'valle-eldin.png', 'El Valle o Cañón de Eldin ocupa la mayor parte de la región de Eldin. Se caracteriza por un clima intensamente caluroso y sus aguas termales.'),
(4, 'Cordillera de Eldin', 'Eldin', 'cordillera-eldin.png', 'Región montañosa situada al noroeste de Eldin. Se caracteriza por ser una zona sin vegetación y por sus altas cumbres y un terreno difícil de atravesar.'),
(5, 'Sierra de Akkala', 'Akkala', 'sierra-akkala.png', 'Región boscosa junto al mar de Akkala, caracterizada por sus árboles que, como en el resto de la región de Akkala, poseen una coloración otoñal permanente.'),
(6, 'Gran Bosque de Hyrule', 'Centro de Hyrule', 'gran-bosque-hyrule.png', 'Extensa región boscosa que comprende la mayor parte del Centro de Hyrule. Se caracteriza por sus colinas onduladas y sus bosques, notablemente albergando entre ellos el Bosque Perdido.'),
(7, 'Colinas de Hyrule', 'Centro de Hyrule', 'colinas-hyrule.png', 'Pequeña región montañosa del Centro de Hyrule sin asentamientos Hylianos. Historicamente, un lugar donde los visitantes y sus caballos podían descansar.'),
(8, 'Llanura de Hyrule', 'Centro de Hyrule', 'llanura-hyrule.png', 'La Pradera o Llanura de Hyrule es un gran campo abierto situado en el centro de Hyrule. Es la región central que conecta con todos los lugares importantes del reino.'),
(9, 'Monte Lanayru', 'Lanayru', 'monte-lanayru.png', 'Gran montaña sagrada situada al sur de la región Lanayru, caracterizada por una temperatura extremadamente fría.'),
(10, 'Manantial de Lanayru', 'Lanayru', 'manantial-lanayru.png', 'Región montañosa situada al noreste de la frontera. Se caracteriza por sus cascadas y rios, y es el hogar de los Zora.'),
(11, 'Mar de Lanayru', 'Lanayru', 'mar-lanayru.png', 'Mar que se encuentra en la costa este de Hyrule, rodeado por el Mar de Akkala al norte y el Mar de Necluda al sur.'),
(12, 'Pantano de Lanayru', 'Lanayru', 'pantano-lanayru.png', 'Ciénaga de agua superficial conformada por pequeñas islas.'),
(13, 'Necluda Occidental', 'Necluda', 'necluda-occidental.png', 'Región boscosa y montañosa situada al sureste de Hyrule, con un terreno pantanoso y húmedo.'),
(14, 'Necluda Oriental', 'Necluda', 'necluda-oriental.png', 'Región montañosa caracterizada por un clima templado y un terreno fértil, ideal para la agricultura.'),
(15, 'Mar de Necluda', 'Necluda', 'mar-necluda.png', 'Extensa zona costera al sureste de Hyrule, caracterizada por playas rocosas y acantilados.'),
(16, 'Kakariko', 'Necluda', 'kakariko.png', 'Asentamiento Sheikah situado en Necluda Occidental, rodeado por montañas. Hogar de Impa.'),
(17, 'Cordillera de Gerudo', 'Gerudo', 'cordillera-gerudo.png', 'Región montañosa que rodea y aísla el Desierto de Gerudo, caracterizada por sus temperaturas extremas.'),
(18, 'Desierto de Gerudo', 'Gerudo', 'desierto-gerudo.png', 'Vasta zona desértica situada al suroeste de Hyrule, caracterizada por sus tormentas de arena.'),
(19, 'Pradera de Farone', 'Farone', 'pradera-farone.png', 'Región tropical y húmeda, caracterizada por densas selvas y numerosos ríos y cascadas.'),
(20, 'Sin especificar', 'Sin region', 'hyrule.jpg', 'Incluye todas las regiones');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas`
--

CREATE TABLE `recetas` (
  `id_receta` int(11) NOT NULL COMMENT 'Clave primaria de la receta',
  `nombre` varchar(50) NOT NULL COMMENT 'Nombre de la receta',
  `imagen` varchar(100) NOT NULL COMMENT 'Ruta al archivo de imagen de la receta',
  `descripcion` varchar(500) NOT NULL COMMENT 'Breve descripción de la receta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recetas`
--

INSERT INTO `recetas` (`id_receta`, `nombre`, `imagen`, `descripcion`) VALUES
(1, 'Arroz con cangrejo', 'arroz-cangrejo.webp', 'Un plato hecho con arroz Hyliano y cangrejo. El dulce sabor del cangrejo se ha absorbido en cada grano de arroz, dando como resultado un plato increíblemente reconfortante.'),
(2, 'Arroz con carne', 'arroz-carne.webp', 'Carne y arroz cocinados juntos para crear un plato que te reconforta por dentro. Utilizar carne de primera calidad hará que este plato sea mucho más sabroso.'),
(3, 'Arroz con carne XL', 'arroz-carne-xl.webp', 'Un plato hecho con mucha carne y arroz. La carne se ha cocinado hasta que está tan tierna que se deshace en la boca.'),
(4, 'Arroz con carne XXL', 'arroz-carne-xxl.webp', 'Una receta preparada con carne de calidad superior y arroz. Al comerlo, la carne se deshace en la boca y el sabor te llena de energía.'),
(5, 'Arroz con huevo frito', 'arroz-huevo.webp', 'Un plato de arroz sencillo pero reconfortante. Se cocina salteando el arroz con verduras y se corona con un huevo frito.'),
(6, 'Arroz con marisco', 'arroz-marisco.webp', 'Arroz Hyliano cocinado con marisco fresco. El marisco se ha cocinado lentamente para extraer todo su sabor.'),
(7, 'Arroz con salmón', 'arroz-salmón.webp', 'El ingrediente principal de este plato es el salmón. El salmón, ligeramente dulce, combina perfectamente con el arroz.'),
(8, 'Arroz con setas', 'arroz-setas.webp', 'Un plato elaborado salteando arroz Hyliano con setas. Las setas aportan un sabor y aroma característicos.'),
(9, 'Arroz con verduras', 'arroz-verduras.webp', 'Este plato se prepara salteando arroz Hyliano con verduras de temporada. Las verduras aportan color y textura.'),
(10, 'Arroz especiado', 'arroz-especiado.webp', 'Arroz Hyliano cocinado con especias de la región de Gerudo. El aroma de las especias abre el apetito.'),
(11, 'Arroz especiado con carne', 'arroz-especiado-carne.webp', 'Arroz especiado cocinado con carne. La carne absorbe las especias y el resultado es un plato muy sabroso.'),
(12, 'Arroz especiado con carne XL', 'arroz-especiado-carne-xl.webp', 'Una versión más contundente del arroz especiado con carne. Contiene mayor cantidad de carne de primera calidad.'),
(13, 'Arroz especiado con carne XXL', 'arroz-especiado-carne-xxl.webp', 'La versión más grande del arroz especiado con carne. La carne de primera calidad y las especias crean una combinación perfecta.'),
(14, 'Bolas de arroz con carne', 'bolas-arroz-carne.webp', 'Bolas de arroz rellenas de carne. Son fáciles de transportar y muy nutritivas.'),
(15, 'Bolas de arroz con pescado', 'bolas-arroz-pescado.webp', 'Bolas de arroz rellenas de pescado. El pescado aporta un sabor delicado al arroz.'),
(16, 'Bolas de arroz con setas', 'bolas-arroz-setas.webp', 'Bolas de arroz mezcladas con setas. Las setas le dan un sabor terroso y característico.'),
(17, 'Bolas de arroz con verdura', 'bolas-arroz-verdura.webp', 'Bolas de arroz con verduras picadas. Una opción saludable y deliciosa.'),
(18, 'Bolas de arroz con monstruo', 'bolas-arroz-monstruo.webp', 'Bolas de arroz con partes de monstruo. Tienen un aspecto sospechoso pero son comestibles.'),
(19, 'Brocheta de carne', 'brocheta-carne.webp', 'Carne ensartada en un pincho y asada a la parrilla. La cocción uniforme resalta el sabor de la carne.'),
(20, 'Brocheta de carne y setas', 'brocheta-carne-setas.webp', 'Una brocheta que combina carne y setas. Las setas absorben los jugos de la carne.'),
(21, 'Brocheta de pescado', 'brocheta-pescado.webp', 'Pescado fresco ensartado y asado a la parrilla. El calor resalta el sabor natural del pescado.'),
(22, 'Brocheta de pescado y setas', 'brocheta-pescado-setas.webp', 'Brocheta que alterna pescado y setas. Una combinación perfecta de sabores del mar y la tierra.'),
(23, 'Brocheta de setas', 'brocheta-setas.webp', 'Setas variadas ensartadas y asadas. El asado potencia el sabor de las setas.'),
(24, 'Brocheta de carne variada', 'brocheta-carne-variada.webp', 'Una brocheta que combina diferentes tipos de carne. Cada bocado tiene un sabor distinto.'),
(25, 'Cangrejo a la parrilla', 'cangrejo-parrilla.webp', 'Cangrejo cocinado a la parrilla. La cáscara crujiente protege la carne tierna y jugosa.'),
(26, 'Carne a la parrilla', 'carne-parrilla.webp', 'Carne asada a la parrilla. Simple pero deliciosa, resalta el sabor natural de la carne.'),
(27, 'Carne a la parrilla XL', 'carne-parrilla-xl.webp', 'Un generoso trozo de carne asada a la parrilla. Jugosa y tierna por dentro, crujiente por fuera.'),
(28, 'Carne a la parrilla XXL', 'carne-parrilla-xxl.webp', 'Un enorme trozo de carne de primera calidad asado a la parrilla. Un festín para los amantes de la carne.'),
(29, 'Carne al vapor', 'carne-vapor.webp', 'Carne cocinada al vapor para mantener su jugosidad. Una opción saludable y deliciosa.'),
(30, 'Carne con pescado', 'carne-pescado.webp', 'Carne y pescado cocinados juntos. La combinación de sabores del mar y la montaña.'),
(31, 'Carne con pescado XL', 'carne-pescado-xl.webp', 'Una generosa porción de carne y pescado cocinados juntos. Muy nutritivo y sabroso.'),
(32, 'Carne con pescado XXL', 'carne-pescado-xxl.webp', 'La combinación definitiva de carne y pescado en su máxima expresión. Muy energético.'),
(33, 'Comida sospechosa', 'comida-sospechosa.webp', 'Has intentado cocinar pero algo ha salido mal. El aspecto es desagradable y el olor no es mejor.'),
(34, 'Experimento fallido', 'experimento-fallido.webp', 'Un experimento culinario que salió terriblemente mal. Parece incomible pero quizás...'),
(35, 'Frutas pochadas', 'frutas-pochadas.webp', 'Frutas cocinadas lentamente en un almíbar. El resultado es una fruta tierna y muy dulce.'),
(36, 'Manzanas a la mantequilla', 'manzanas-mantequilla.webp', 'Manzanas salteadas en mantequilla. El calor carameliza los azúcares naturales de la manzana.'),
(37, 'Pescado al vapor', 'pescado-vapor.webp', 'Pescado fresco cocinado al vapor. Se conserva todo el sabor y la textura delicada del pescado.'),
(38, 'Pescado a la parrilla', 'pescado-parrilla.webp', 'Pescado fresco asado a la parrilla. La piel queda crujiente y la carne jugosa.'),
(39, 'Salteado de frutas y setas', 'salteado-frutas-setas.webp', 'Una combinación sorprendente de frutas dulces y setas salteadas. El contraste de sabores es único.'),
(40, 'Salteado de montaña', 'salteado-montaña.webp', 'Un salteado con ingredientes típicos de las zonas montañosas. Muy energético y reconfortante.'),
(41, 'Salteado de montaña variado', 'salteado-montaña-variado.webp', 'Una versión más completa del salteado de montaña. Incluye una gran variedad de ingredientes.'),
(42, 'Setas al vapor', 'setas-vapor.webp', 'Setas cocinadas al vapor. Se conserva todo su sabor y propiedades.'),
(43, 'Setas a la parrilla', 'setas-parrilla.webp', 'Setas asadas a la parrilla. El calor resalta su sabor umami.'),
(44, 'Setas glaseadas', 'setas-glaseadas.webp', 'Setas cocinadas con una salsa dulce que las glasea. Un bocado delicioso.'),
(45, 'Sopa de verduras', 'sopa-verduras.webp', 'Una sopa reconfortante hecha con verduras frescas. Perfecta para entrar en calor.'),
(46, 'Tortilla', 'tortilla.webp', 'Huevos batidos y cocinados hasta obtener una textura esponjosa. Simple pero deliciosa.'),
(47, 'Verduras a la parrilla', 'verduras-parrilla.webp', 'Verduras variadas asadas a la parrilla. El calor carameliza los azúcares naturales.'),
(48, 'Brocheta de setas Gélidas', 'brocheta-setas.webp', 'Setas gélidas ensartadas y asadas. Protegen del calor gracias a su temperatura fría.'),
(49, 'Brocheta de setas Ígneas', 'brocheta-setas.webp', 'Setas ígneas ensartadas y asadas. Su calor interior protege de las bajas temperaturas.'),
(50, 'Brocheta de setas Electro', 'brocheta-setas.webp', 'Setas electro ensartadas y asadas. Las pequeñas chispas protegen de la electricidad.'),
(51, 'Brocheta de setas Raudas', 'brocheta-setas.webp', 'Setas raudas ensartadas y asadas. Aumentan la velocidad de quien las consume.'),
(52, 'Brocheta de setas Recias', 'brocheta-setas.webp', 'Setas recias ensartadas y asadas. Fortalecen el cuerpo y aumentan el ataque.'),
(53, 'Brocheta de setas Vigorosas', 'brocheta-setas.webp', 'Setas vigorosas ensartadas y asadas. Recuperan la resistencia al instante.'),
(54, 'Brocheta de setas Briosas', 'brocheta-setas.webp', 'Setas briosas ensartadas y asadas. Otorgan una resistencia adicional.'),
(55, 'Brocheta de setas Robustas', 'brocheta-setas.webp', 'Setas robustas ensartadas y asadas. Aumentan la defensa de quien las come.'),
(56, 'Brocheta de setas Sigilosas', 'brocheta-setas.webp', 'Setas sigilosas ensartadas y asadas. Ayudan a moverse sin hacer ruido.'),
(57, 'Setas Gélidas a la parrilla', 'setas-parrilla.webp', 'Setas gélidas asadas a la parrilla con sal. Protegen del calor extremo.'),
(58, 'Setas Ígneas a la parrilla', 'setas-parrilla.webp', 'Setas ígneas asadas a la parrilla con sal. Protegen del frío más intenso.'),
(59, 'Setas Electro a la parrilla', 'setas-parrilla.webp', 'Setas electro asadas a la parrilla con sal. Resisten la electricidad estática.'),
(60, 'Setas Raudas a la parrilla', 'setas-parrilla.webp', 'Setas raudas asadas a la parrilla con sal. Aumentan la velocidad de movimiento.'),
(61, 'Setas Recias a la parrilla', 'setas-parrilla.webp', 'Setas recias asadas a la parrilla con sal. Potencian la fuerza de ataque.'),
(62, 'Setas Vigorosas a la parrilla', 'setas-parrilla.webp', 'Setas vigorosas asadas a la parrilla con sal. Restauran la resistencia.'),
(63, 'Setas Briosas a la parrilla', 'setas-parrilla.webp', 'Setas briosas asadas a la parrilla con sal. Otorgan resistencia extra.'),
(64, 'Setas Robustas a la parrilla', 'setas-parrilla.webp', 'Setas robustas asadas a la parrilla con sal. Aumentan la capacidad defensiva.'),
(65, 'Setas Sigilosas a la parrilla', 'setas-parrilla.webp', 'Setas sigilosas asadas a la parrilla con sal. Favorecen el sigilo.'),
(66, 'Setas Gélidas al vapor', 'setas-vapor.webp', 'Setas gélidas cocinadas al vapor con hierbas. Su frescor protege del calor.'),
(67, 'Setas Ígneas al vapor', 'setas-vapor.webp', 'Setas ígneas cocinadas al vapor con hierbas. Su calor protege del frío.'),
(68, 'Setas Electro al vapor', 'setas-vapor.webp', 'Setas electro cocinadas al vapor con hierbas. Protegen de la electricidad.'),
(69, 'Setas Raudas al vapor', 'setas-vapor.webp', 'Setas raudas cocinadas al vapor con hierbas. Aumentan la velocidad.'),
(70, 'Setas Recias al vapor', 'setas-vapor.webp', 'Setas recias cocinadas al vapor con hierbas. Aumentan el ataque.'),
(71, 'Setas Vigorosas al vapor', 'setas-vapor.webp', 'Setas vigorosas cocinadas al vapor con hierbas. Recuperan resistencia.'),
(72, 'Setas Briosas al vapor', 'setas-vapor.webp', 'Setas briosas cocinadas al vapor con hierbas. Otorgan resistencia extra.'),
(73, 'Setas Robustas al vapor', 'setas-vapor.webp', 'Setas robustas cocinadas al vapor con hierbas. Aumentan la defensa.'),
(74, 'Setas Sigilosas al vapor', 'setas-vapor.webp', 'Setas sigilosas cocinadas al vapor con hierbas. Favorecen el sigilo.'),
(75, 'Setas Gélidas glaseadas', 'setas-glaseadas.webp', 'Setas gélidas glaseadas con azúcar. Su frescor protege del calor.'),
(76, 'Setas Ígneas glaseadas', 'setas-glaseadas.webp', 'Setas ígneas glaseadas con azúcar. Su calor protege del frío.'),
(77, 'Setas Electro glaseadas', 'setas-glaseadas.webp', 'Setas electro glaseadas con azúcar. Protegen de la electricidad.'),
(78, 'Setas Raudas glaseadas', 'setas-glaseadas.webp', 'Setas raudas glaseadas con azúcar. Aumentan la velocidad.'),
(79, 'Setas Recias glaseadas', 'setas-glaseadas.webp', 'Setas recias glaseadas con azúcar. Aumentan el ataque.'),
(80, 'Setas Vigorosas glaseadas', 'setas-glaseadas.webp', 'Setas vigorosas glaseadas con azúcar. Recuperan resistencia.'),
(81, 'Setas Briosas glaseadas', 'setas-glaseadas.webp', 'Setas briosas glaseadas con azúcar. Otorgan resistencia extra.'),
(82, 'Setas Robustas glaseadas', 'setas-glaseadas.webp', 'Setas robustas glaseadas con azúcar. Aumentan la defensa.'),
(83, 'Setas Sigilosas glaseadas', 'setas-glaseadas.webp', 'Setas sigilosas glaseadas con azúcar. Favorecen el sigilo.'),
(84, 'Brocheta de carne con setas Gélidas', 'brocheta-carne-setas.webp', 'Carne y setas gélidas en brocheta. Combinación nutritiva que protege del calor.'),
(85, 'Brocheta de carne con setas Ígneas', 'brocheta-carne-setas.webp', 'Carne y setas ígneas en brocheta. Protege del frío mientras alimenta.'),
(86, 'Brocheta de carne con setas Electro', 'brocheta-carne-setas.webp', 'Carne y setas electro en brocheta. Resistencia eléctrica y nutrición.'),
(87, 'Brocheta de carne con setas Raudas', 'brocheta-carne-setas.webp', 'Carne y setas raudas en brocheta. Aumenta la velocidad al comer.'),
(88, 'Brocheta de carne con setas Recias', 'brocheta-carne-setas.webp', 'Carne y setas recias en brocheta. Potencia el ataque significativamente.'),
(89, 'Brocheta de carne con setas Vigorosas', 'brocheta-carne-setas.webp', 'Carne y setas vigorosas en brocheta. Recupera la resistencia.'),
(90, 'Brocheta de carne con setas Briosas', 'brocheta-carne-setas.webp', 'Carne y setas briosas en brocheta. Otorga resistencia extra.'),
(91, 'Brocheta de carne con setas Robustas', 'brocheta-carne-setas.webp', 'Carne y setas robustas en brocheta. Aumenta la defensa.'),
(92, 'Brocheta de carne con setas Sigilosas', 'brocheta-carne-setas.webp', 'Carne y setas sigilosas en brocheta. Favorece el movimiento silencioso.'),
(93, 'Brocheta de pescado con setas Gélidas', 'brocheta-pescado-setas.webp', 'Pescado fresco y setas gélidas. Sabor marino con protección contra el calor.'),
(94, 'Brocheta de pescado con setas Ígneas', 'brocheta-pescado-setas.webp', 'Pescado fresco y setas ígneas. Protección contra el frío y sabor del mar.'),
(95, 'Brocheta de pescado con setas Electro', 'brocheta-pescado-setas.webp', 'Pescado fresco y setas electro. Resistencia a la electricidad.'),
(96, 'Brocheta de pescado con setas Raudas', 'brocheta-pescado-setas.webp', 'Pescado fresco y setas raudas. Aumenta la velocidad.'),
(97, 'Brocheta de pescado con setas Recias', 'brocheta-pescado-setas.webp', 'Pescado fresco y setas recias. Potencia el ataque.'),
(98, 'Brocheta de pescado con setas Vigorosas', 'brocheta-pescado-setas.webp', 'Pescado fresco y setas vigorosas. Recupera resistencia.'),
(99, 'Brocheta de pescado con setas Briosas', 'brocheta-pescado-setas.webp', 'Pescado fresco y setas briosas. Resistencia extra.'),
(100, 'Brocheta de pescado con setas Robustas', 'brocheta-pescado-setas.webp', 'Pescado fresco y setas robustas. Aumenta la defensa.'),
(101, 'Brocheta de pescado con setas Sigilosas', 'brocheta-pescado-setas.webp', 'Pescado fresco y setas sigilosas. Favorece el sigilo.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas_efectos`
--

CREATE TABLE `recetas_efectos` (
  `id_receta_efecto` int(11) NOT NULL COMMENT 'Clave primara de la relación entre recetas y efectos',
  `id_receta` int(11) NOT NULL,
  `id_efecto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recetas_efectos`
--

INSERT INTO `recetas_efectos` (`id_receta_efecto`, `id_receta`, `id_efecto`) VALUES
(1, 1, 12),
(2, 2, 12),
(3, 3, 12),
(4, 4, 12),
(5, 5, 12),
(6, 6, 11),
(7, 7, 11),
(8, 8, 5),
(9, 9, 5),
(10, 10, 4),
(11, 11, 4),
(12, 12, 4),
(13, 13, 4),
(14, 14, 12),
(15, 15, 12),
(16, 16, 5),
(17, 17, 5),
(18, 18, 12),
(19, 19, 12),
(20, 20, 7),
(21, 21, 12),
(22, 22, 5),
(23, 23, 5),
(24, 24, 7),
(25, 25, 12),
(26, 26, 12),
(27, 27, 12),
(28, 28, 12),
(29, 29, 5),
(30, 30, 12),
(31, 31, 12),
(32, 32, 12),
(33, 33, 12),
(34, 34, 12),
(35, 35, 12),
(36, 36, 12),
(37, 37, 5),
(38, 38, 12),
(39, 39, 6),
(40, 40, 7),
(41, 41, 7),
(42, 42, 5),
(43, 43, 5),
(44, 44, 5),
(45, 45, 5),
(46, 46, 12),
(47, 47, 5),
(48, 48, 2),
(49, 49, 4),
(50, 50, 1),
(51, 51, 6),
(52, 52, 7),
(53, 53, 10),
(54, 54, 9),
(55, 55, 5),
(56, 56, 8),
(57, 57, 2),
(58, 58, 4),
(59, 59, 1),
(60, 60, 6),
(61, 61, 7),
(62, 62, 10),
(63, 63, 9),
(64, 64, 5),
(65, 65, 8),
(66, 66, 2),
(67, 67, 4),
(68, 68, 1),
(69, 69, 6),
(70, 70, 7),
(71, 71, 10),
(72, 72, 9),
(73, 73, 5),
(74, 74, 8),
(75, 75, 2),
(76, 76, 4),
(77, 77, 1),
(78, 78, 6),
(79, 79, 7),
(80, 80, 10),
(81, 81, 9),
(82, 82, 5),
(83, 83, 8),
(84, 84, 2),
(85, 85, 4),
(86, 86, 1),
(87, 87, 6),
(88, 88, 7),
(89, 89, 10),
(90, 90, 9),
(91, 91, 5),
(92, 92, 8),
(93, 93, 2),
(94, 94, 4),
(95, 95, 1),
(96, 96, 6),
(97, 97, 7),
(98, 98, 10),
(99, 99, 9),
(100, 100, 5),
(101, 101, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas_ingredientes`
--

CREATE TABLE `recetas_ingredientes` (
  `id_receta_ingrediente` int(11) NOT NULL COMMENT 'Clave primaria de la relación entre recetas e ingredientes',
  `id_receta` int(11) NOT NULL,
  `id_ingrediente` int(11) NOT NULL,
  `cantidad` tinyint(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Cantidad de cada ingrediente en una receta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recetas_ingredientes`
--

INSERT INTO `recetas_ingredientes` (`id_receta_ingrediente`, `id_receta`, `id_ingrediente`, `cantidad`) VALUES
(1, 1, 13, 1),
(2, 1, 17, 1),
(3, 1, 35, 1),
(4, 1, 12, 1),
(5, 2, 13, 1),
(6, 2, 1, 1),
(7, 3, 13, 1),
(8, 3, 2, 1),
(9, 4, 13, 1),
(10, 4, 3, 1),
(11, 5, 13, 1),
(12, 5, 34, 1),
(13, 6, 13, 1),
(14, 6, 7, 2),
(15, 7, 13, 1),
(16, 7, 9, 1),
(17, 8, 13, 1),
(18, 8, 37, 2),
(19, 9, 13, 1),
(20, 9, 24, 2),
(21, 10, 13, 1),
(22, 10, 22, 1),
(23, 10, 35, 1),
(24, 11, 13, 1),
(25, 11, 1, 1),
(26, 11, 22, 1),
(27, 12, 13, 1),
(28, 12, 2, 1),
(29, 12, 22, 1),
(30, 13, 13, 1),
(31, 13, 3, 1),
(32, 13, 22, 1),
(33, 14, 13, 1),
(34, 14, 1, 1),
(35, 15, 13, 1),
(36, 15, 7, 1),
(37, 16, 13, 1),
(38, 16, 37, 1),
(39, 17, 13, 1),
(40, 17, 24, 1),
(41, 18, 13, 1),
(42, 18, 79, 1),
(43, 19, 1, 1),
(44, 20, 1, 1),
(45, 20, 37, 1),
(46, 21, 7, 1),
(47, 22, 7, 1),
(48, 22, 37, 1),
(49, 23, 37, 3),
(50, 24, 1, 2),
(51, 24, 4, 1),
(52, 25, 12, 1),
(53, 25, 35, 1),
(54, 26, 1, 1),
(55, 27, 2, 1),
(56, 28, 3, 1),
(57, 29, 1, 1),
(58, 29, 24, 1),
(59, 30, 1, 1),
(60, 30, 7, 1),
(61, 31, 2, 1),
(62, 31, 8, 1),
(63, 32, 3, 1),
(64, 32, 9, 1),
(65, 33, 79, 1),
(66, 33, 24, 1),
(67, 34, 79, 2),
(68, 35, 18, 2),
(69, 36, 18, 1),
(70, 36, 17, 1),
(71, 37, 7, 1),
(72, 37, 24, 1),
(73, 38, 7, 1),
(74, 39, 18, 1),
(75, 39, 37, 1),
(76, 40, 37, 1),
(77, 40, 1, 1),
(78, 41, 37, 2),
(79, 41, 1, 1),
(80, 41, 4, 1),
(81, 42, 37, 1),
(82, 42, 24, 1),
(83, 43, 37, 1),
(84, 43, 35, 1),
(85, 44, 37, 1),
(86, 44, 15, 1),
(87, 45, 24, 3),
(88, 45, 35, 1),
(89, 46, 34, 2),
(90, 47, 24, 1),
(91, 48, 38, 3),
(92, 49, 39, 3),
(93, 50, 40, 3),
(94, 51, 41, 3),
(95, 52, 42, 3),
(96, 53, 43, 3),
(97, 54, 44, 3),
(98, 55, 45, 3),
(99, 56, 46, 3),
(100, 57, 38, 1),
(101, 57, 35, 1),
(102, 58, 39, 1),
(103, 58, 35, 1),
(104, 59, 40, 1),
(105, 59, 35, 1),
(106, 60, 41, 1),
(107, 60, 35, 1),
(108, 61, 42, 1),
(109, 61, 35, 1),
(110, 62, 43, 1),
(111, 62, 35, 1),
(112, 63, 44, 1),
(113, 63, 35, 1),
(114, 64, 45, 1),
(115, 64, 35, 1),
(116, 65, 46, 1),
(117, 65, 35, 1),
(118, 66, 38, 1),
(119, 66, 24, 1),
(120, 67, 39, 1),
(121, 67, 24, 1),
(122, 68, 40, 1),
(123, 68, 24, 1),
(124, 69, 41, 1),
(125, 69, 24, 1),
(126, 70, 42, 1),
(127, 70, 24, 1),
(128, 71, 43, 1),
(129, 71, 24, 1),
(130, 72, 44, 1),
(131, 72, 24, 1),
(132, 73, 45, 1),
(133, 73, 24, 1),
(134, 74, 46, 1),
(135, 74, 24, 1),
(136, 75, 38, 1),
(137, 75, 15, 1),
(138, 76, 39, 1),
(139, 76, 15, 1),
(140, 77, 40, 1),
(141, 77, 15, 1),
(142, 78, 41, 1),
(143, 78, 15, 1),
(144, 79, 42, 1),
(145, 79, 15, 1),
(146, 80, 43, 1),
(147, 80, 15, 1),
(148, 81, 44, 1),
(149, 81, 15, 1),
(150, 82, 45, 1),
(151, 82, 15, 1),
(152, 83, 46, 1),
(153, 83, 15, 1),
(154, 84, 1, 1),
(155, 84, 38, 1),
(156, 85, 1, 1),
(157, 85, 39, 1),
(158, 86, 1, 1),
(159, 86, 40, 1),
(160, 87, 1, 1),
(161, 87, 41, 1),
(162, 88, 1, 1),
(163, 88, 42, 1),
(164, 89, 1, 1),
(165, 89, 43, 1),
(166, 90, 1, 1),
(167, 90, 44, 1),
(168, 91, 1, 1),
(169, 91, 45, 1),
(170, 92, 1, 1),
(171, 92, 46, 1),
(172, 93, 7, 1),
(173, 93, 38, 1),
(174, 94, 7, 1),
(175, 94, 39, 1),
(176, 95, 7, 1),
(177, 95, 40, 1),
(178, 96, 7, 1),
(179, 96, 41, 1),
(180, 97, 7, 1),
(181, 97, 42, 1),
(182, 98, 7, 1),
(183, 98, 43, 1),
(184, 99, 7, 1),
(185, 99, 44, 1),
(186, 100, 7, 1),
(187, 100, 45, 1),
(188, 101, 7, 1),
(189, 101, 46, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_efectos`
--

CREATE TABLE `tipos_efectos` (
  `id_tipo_efecto` int(11) NOT NULL COMMENT 'Clave primaria del tipo de efecto',
  `nombre` varchar(50) NOT NULL COMMENT 'Nombre del efecto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_efectos`
--

INSERT INTO `tipos_efectos` (`id_tipo_efecto`, `nombre`) VALUES
(1, 'Electrizante'),
(2, 'Gélido'),
(3, 'Ignífugo'),
(4, 'Picante'),
(5, 'Protector'),
(6, 'Raudo'),
(7, 'Recio'),
(8, 'Sigiloso'),
(9, 'Tonificante'),
(10, 'Vigoroso'),
(11, 'Vivaz'),
(12, 'Sin efecto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL COMMENT 'Clave primaria del usuario',
  `user` varchar(50) NOT NULL COMMENT 'Nombre de usuario',
  `pass` varchar(50) NOT NULL COMMENT 'Contraseña (encriptada) del usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `user`, `pass`) VALUES
(1, 'test_user_01', '1234'),
(2, 'test_user_02', '1234'),
(3, 'test_user_03', '1234'),
(4, 'test_user_04', '1234');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `efectos`
--
ALTER TABLE `efectos`
  ADD PRIMARY KEY (`id_efecto`),
  ADD KEY `ID_TIPO_EFECTO` (`id_tipo_efecto`);

--
-- Indices de la tabla `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD PRIMARY KEY (`id_ingrediente`);

--
-- Indices de la tabla `ingredientes_localizaciones`
--
ALTER TABLE `ingredientes_localizaciones`
  ADD PRIMARY KEY (`id_ingrediente_localizacion`),
  ADD KEY `id_ingrediente` (`id_ingrediente`,`id_localizacion`),
  ADD KEY `id_localizacion` (`id_localizacion`);

--
-- Indices de la tabla `inventarios`
--
ALTER TABLE `inventarios`
  ADD PRIMARY KEY (`id_inventario`),
  ADD KEY `ID_INGREDIENTE_USUARIO` (`id_ingrediente`,`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `localizaciones`
--
ALTER TABLE `localizaciones`
  ADD PRIMARY KEY (`id_localizacion`);

--
-- Indices de la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD PRIMARY KEY (`id_receta`);

--
-- Indices de la tabla `recetas_efectos`
--
ALTER TABLE `recetas_efectos`
  ADD PRIMARY KEY (`id_receta_efecto`),
  ADD KEY `id_receta` (`id_receta`,`id_efecto`),
  ADD KEY `id_efecto` (`id_efecto`);

--
-- Indices de la tabla `recetas_ingredientes`
--
ALTER TABLE `recetas_ingredientes`
  ADD PRIMARY KEY (`id_receta_ingrediente`),
  ADD KEY `index_receta_ingrediente` (`id_receta`,`id_ingrediente`) USING BTREE;

--
-- Indices de la tabla `tipos_efectos`
--
ALTER TABLE `tipos_efectos`
  ADD PRIMARY KEY (`id_tipo_efecto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `USER` (`user`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `efectos`
--
ALTER TABLE `efectos`
  MODIFY `id_efecto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria del efecto', AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `ingredientes`
--
ALTER TABLE `ingredientes`
  MODIFY `id_ingrediente` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria del ingrediente', AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT de la tabla `ingredientes_localizaciones`
--
ALTER TABLE `ingredientes_localizaciones`
  MODIFY `id_ingrediente_localizacion` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria de la relación entre ingredientes y su localización en Hyrule.', AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT de la tabla `inventarios`
--
ALTER TABLE `inventarios`
  MODIFY `id_inventario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria de la relación entre los usuarios y los ingredientes (inventario personal)', AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `localizaciones`
--
ALTER TABLE `localizaciones`
  MODIFY `id_localizacion` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria de la localización', AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `recetas`
--
ALTER TABLE `recetas`
  MODIFY `id_receta` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria de la receta', AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT de la tabla `recetas_efectos`
--
ALTER TABLE `recetas_efectos`
  MODIFY `id_receta_efecto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primara de la relación entre recetas y efectos', AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT de la tabla `recetas_ingredientes`
--
ALTER TABLE `recetas_ingredientes`
  MODIFY `id_receta_ingrediente` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria de la relación entre recetas e ingredientes', AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT de la tabla `tipos_efectos`
--
ALTER TABLE `tipos_efectos`
  MODIFY `id_tipo_efecto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria del tipo de efecto', AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria del usuario', AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `efectos`
--
ALTER TABLE `efectos`
  ADD CONSTRAINT `efectos_ibfk_1` FOREIGN KEY (`id_tipo_efecto`) REFERENCES `tipos_efectos` (`id_tipo_efecto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ingredientes_localizaciones`
--
ALTER TABLE `ingredientes_localizaciones`
  ADD CONSTRAINT `ingredientes_localizaciones_ibfk_1` FOREIGN KEY (`id_ingrediente`) REFERENCES `ingredientes` (`id_ingrediente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ingredientes_localizaciones_ibfk_2` FOREIGN KEY (`id_localizacion`) REFERENCES `localizaciones` (`id_localizacion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `inventarios`
--
ALTER TABLE `inventarios`
  ADD CONSTRAINT `inventarios_ibfk_1` FOREIGN KEY (`id_ingrediente`) REFERENCES `ingredientes` (`id_ingrediente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inventarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `recetas_efectos`
--
ALTER TABLE `recetas_efectos`
  ADD CONSTRAINT `recetas_efectos_ibfk_1` FOREIGN KEY (`id_receta`) REFERENCES `recetas` (`id_receta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `recetas_efectos_ibfk_2` FOREIGN KEY (`id_efecto`) REFERENCES `efectos` (`id_efecto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `recetas_ingredientes`
--
ALTER TABLE `recetas_ingredientes`
  ADD CONSTRAINT `recetas_ingredientes_ibfk_1` FOREIGN KEY (`id_receta`) REFERENCES `recetas` (`id_receta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `recetas_ingredientes_ibfk_2` FOREIGN KEY (`id_ingrediente`) REFERENCES `ingredientes` (`id_ingrediente`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
