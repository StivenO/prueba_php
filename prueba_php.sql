-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 06-08-2023 a las 12:19:42
-- Versión del servidor: 8.0.31
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `prueba_php`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) NOT NULL,
  `Referencia` varchar(100) NOT NULL,
  `Precio` int NOT NULL,
  `Peso` int NOT NULL,
  `Categoria` varchar(100) NOT NULL,
  `Stock` int NOT NULL,
  `FechaCreacion` date NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`ID`, `Nombre`, `Referencia`, `Precio`, `Peso`, `Categoria`, `Stock`, `FechaCreacion`) VALUES
(1, 'Producto 1', 'REF-001', 100, 500, 'Categoría 1', 40, '2023-08-01'),
(2, 'Producto 2', 'REF-002', 251, 750, 'Categoría 4', 5, '0000-00-00'),
(3, 'Producto 3', 'REF-003', 150, 300, 'Categoría 1', 8, '2023-08-03'),
(4, 'Producto 4', 'REF-004', 400, 600, 'Categoría 3', 14, '2023-08-04'),
(5, 'Producto 5', 'REF-005', 250, 400, 'Categoría 2', 4, '2023-08-05'),
(18, 'Producto 6', '11', 1, 1, 'ejemplo', 29, '2023-08-06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

DROP TABLE IF EXISTS `ventas`;
CREATE TABLE IF NOT EXISTS `ventas` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `ID_Producto` int NOT NULL,
  `Precio` int NOT NULL,
  `Cantidad` int NOT NULL,
  `Fecha_Venta` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `ID_Producto` (`ID_Producto`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`ID`, `ID_Producto`, `Precio`, `Cantidad`, `Fecha_Venta`) VALUES
(1, 1, 10, 2, '2023-08-06 10:49:15'),
(2, 18, 1, 1, '2023-08-06 11:20:45'),
(3, 3, 150, 3, '2023-08-06 11:20:45'),
(4, 18, 1, 10, '2023-08-06 11:21:35'),
(5, 3, 150, 3, '2023-08-06 11:21:35'),
(6, 18, 1, 10, '2023-08-06 11:22:15'),
(7, 3, 150, 3, '2023-08-06 11:22:15'),
(8, 18, 1, 10, '2023-08-06 11:22:42'),
(9, 3, 150, 3, '2023-08-06 11:22:42'),
(10, 2, 251, 10, '2023-08-06 11:22:56'),
(11, 2, 251, 150, '2023-08-06 11:23:17'),
(12, 1, 100, 10, '2023-08-06 11:23:17'),
(13, 22, 1, 1, '2023-08-06 11:24:29'),
(14, 23, 1, 1, '2023-08-06 11:24:29'),
(15, 5, 250, 6, '2023-08-06 12:15:23');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
