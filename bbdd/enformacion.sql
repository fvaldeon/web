-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-12-2019 a las 20:49:52
-- Versión del servidor: 5.5.32
-- Versión de PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `enformacion`
--
CREATE DATABASE IF NOT EXISTS `enformacion` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `enformacion`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gimnasios`
--

DROP TABLE IF EXISTS `gimnasios`;
CREATE TABLE IF NOT EXISTS `gimnasios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `mixto` tinyint(1) DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `taquillas`
--

DROP TABLE IF EXISTS `taquillas`;
CREATE TABLE IF NOT EXISTS `taquillas` (
  `id_taquilla` int(11) NOT NULL,
  PRIMARY KEY (`id_taquilla`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `taquillas`
--

INSERT INTO `taquillas` (`id_taquilla`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nif` varchar(15) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `telefono` varchar(40) DEFAULT NULL,
  `id_taquilla` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_taquilla` (`id_taquilla`),
  UNIQUE KEY `nif` (`nif`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nif`, `nombre`, `apellidos`, `telefono`, `id_taquilla`) VALUES
(4, '1234R', 'Juan', 'Perez', '23452345', 2),
(5, '4567T', 'Laura', 'Garcia', '2452354', 4),
(6, '753567T', 'Pablo', 'Lopez', '2436347', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_gimnasio`
--

DROP TABLE IF EXISTS `usuario_gimnasio`;
CREATE TABLE IF NOT EXISTS `usuario_gimnasio` (
  `id_usuario` int(11) NOT NULL DEFAULT '0',
  `id_gimnasio` int(11) NOT NULL DEFAULT '0',
  `fecha_matriculacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`,`id_gimnasio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
