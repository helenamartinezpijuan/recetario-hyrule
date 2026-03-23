-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-03-2026 a las 10:18:44
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
DROP DATABASE IF EXISTS `recetario_hyrule`;
CREATE DATABASE IF NOT EXISTS `recetario_hyrule` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `recetario_hyrule`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `efectos`
--

CREATE TABLE `efectos` (
  `id_efecto` int(11) NOT NULL COMMENT 'Clave primaria del efecto',
  `id_tipo_efecto` int(11) NOT NULL,
  `descripcion` varchar(150) NOT NULL COMMENT 'Descripción del efecto (power-up)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingredientes`
--

CREATE TABLE `ingredientes` (
  `id_ingrediente` int(11) NOT NULL COMMENT 'Clave primaria del ingrediente',
  `nombre` varchar(50) NOT NULL COMMENT 'Nombre del ingrediente',
  `imagen` varchar(100) NOT NULL COMMENT 'Ruta al archivo de imagen del ingrediente',
  `descripcion` varchar(150) NOT NULL COMMENT 'Breve descripción del ingrediente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingredientes_localizaciones`
--

CREATE TABLE `ingredientes_localizaciones` (
  `id_ingrediente_localizacion` int(11) NOT NULL COMMENT 'Clave primaria de la relación entre ingredientes y su localización en Hyrule.',
  `id_ingrediente` int(11) NOT NULL,
  `id_localizacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `localizaciones`
--

CREATE TABLE `localizaciones` (
  `id_localizacion` int(11) NOT NULL COMMENT 'Clave primaria de la localización',
  `nombre` varchar(50) NOT NULL COMMENT 'Nombre de la localización',
  `imagen` varchar(100) NOT NULL COMMENT 'Ruta al archivo de imagen de la localización',
  `descripcion` varchar(150) NOT NULL COMMENT 'Breve descripción de la localización'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas`
--

CREATE TABLE `recetas` (
  `id_receta` int(11) NOT NULL COMMENT 'Clave primaria de la receta',
  `codigo` varchar(5) NOT NULL COMMENT 'Código (unique) de la receta',
  `nombre` varchar(50) NOT NULL COMMENT 'Nombre de la receta',
  `imagen` varchar(100) NOT NULL COMMENT 'Ruta al archivo de imagen de la receta',
  `descripcion` varchar(500) NOT NULL COMMENT 'Breve descripción de la receta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas_efectos`
--

CREATE TABLE `recetas_efectos` (
  `id_receta_efecto` int(11) NOT NULL COMMENT 'Clave primara de la relación entre recetas y efectos',
  `id_receta` int(11) NOT NULL,
  `id_efecto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas_ingredientes`
--

CREATE TABLE `recetas_ingredientes` (
  `id_receta_ingrediente` int(11) NOT NULL COMMENT 'Clave primaria de la relación entre recetas e ingredientes',
  `id_receta` int(11) NOT NULL,
  `id_ingrediente` int(11) NOT NULL,
  `cantidad` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Cantidad de cada ingrediente en una receta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_efectos`
--

CREATE TABLE `tipos_efectos` (
  `id_tipo_efecto` int(11) NOT NULL COMMENT 'Clave primaria del tipo de efecto',
  `nombre` varchar(50) NOT NULL COMMENT 'Nombre del efecto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD PRIMARY KEY (`id_receta`),
  ADD UNIQUE KEY `CODIGO` (`codigo`);

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
  ADD KEY `id_receta` (`id_receta`,`id_ingrediente`),
  ADD KEY `id_ingrediente` (`id_ingrediente`);

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
  MODIFY `id_efecto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria del efecto';

--
-- AUTO_INCREMENT de la tabla `ingredientes`
--
ALTER TABLE `ingredientes`
  MODIFY `id_ingrediente` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria del ingrediente';

--
-- AUTO_INCREMENT de la tabla `ingredientes_localizaciones`
--
ALTER TABLE `ingredientes_localizaciones`
  MODIFY `id_ingrediente_localizacion` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria de la relación entre ingredientes y su localización en Hyrule.';

--
-- AUTO_INCREMENT de la tabla `inventarios`
--
ALTER TABLE `inventarios`
  MODIFY `id_inventario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria de la relación entre los usuarios y los ingredientes (inventario personal)';

--
-- AUTO_INCREMENT de la tabla `localizaciones`
--
ALTER TABLE `localizaciones`
  MODIFY `id_localizacion` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria de la localización';

--
-- AUTO_INCREMENT de la tabla `recetas`
--
ALTER TABLE `recetas`
  MODIFY `id_receta` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria de la receta';

--
-- AUTO_INCREMENT de la tabla `recetas_efectos`
--
ALTER TABLE `recetas_efectos`
  MODIFY `id_receta_efecto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primara de la relación entre recetas y efectos';

--
-- AUTO_INCREMENT de la tabla `recetas_ingredientes`
--
ALTER TABLE `recetas_ingredientes`
  MODIFY `id_receta_ingrediente` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria de la relación entre recetas e ingredientes';

--
-- AUTO_INCREMENT de la tabla `tipos_efectos`
--
ALTER TABLE `tipos_efectos`
  MODIFY `id_tipo_efecto` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria del tipo de efecto';

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria del usuario';

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
