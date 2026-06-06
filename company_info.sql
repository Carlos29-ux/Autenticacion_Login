-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 06-06-2026 a las 19:06:13
-- Versión del servidor: 8.4.7
-- Versión de PHP: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `company_info`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesos`
--

DROP TABLE IF EXISTS `accesos`;
CREATE TABLE IF NOT EXISTS `accesos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Usuario` varchar(100) NOT NULL,
  `ipRemoto` varchar(45) NOT NULL,
  `exitoso` tinyint(1) NOT NULL DEFAULT '0',
  `FechaAcceso` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `accesos`
--

INSERT INTO `accesos` (`id`, `Usuario`, `ipRemoto`, `exitoso`, `FechaAcceso`) VALUES
(1, 'juan.perez23@gmail.com', '::1', 1, '2026-06-02 09:22:03'),
(2, 'josephguerra24@gmail.com', '::1', 1, '2026-06-02 09:24:21'),
(3, 'mariag90@gmail.com', '::1', 1, '2026-06-02 18:54:26'),
(4, 'mendozaclara23@gmail.com', '::1', 1, '2026-06-02 19:11:46'),
(5, 'josephm45@gmail.com', '::1', 1, '2026-06-03 09:49:49'),
(6, 'josephcardoze23@gmail.com', '::1', 0, '2026-06-03 10:40:58'),
(7, 'francescom23@gmail.com', '::1', 0, '2026-06-03 10:44:01'),
(8, 'francescom23@gmail.com', '::1', 0, '2026-06-03 10:44:06'),
(9, 'francescom23@gmail.com', '::1', 0, '2026-06-03 10:44:42'),
(10, 'francescom23@gmail.com', '::1', 0, '2026-06-03 10:44:49'),
(11, 'carlosjim12@gmail.com', '::1', 1, '2026-06-03 10:46:24'),
(12, 'gonzalezsam67@gmail.com', '::1', 1, '2026-06-03 10:52:13'),
(13, 'francescomed45@gmail.com', '::1', 1, '2026-06-03 11:17:32'),
(14, 'joseph27@gmail.com', '::1', 0, '2026-06-06 13:43:27'),
(15, 'josephm45@gmail.com', '::1', 1, '2026-06-06 13:43:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(80) NOT NULL,
  `Apellido` varchar(80) NOT NULL,
  `Usuario` varchar(100) NOT NULL,
  `Correo` varchar(150) NOT NULL,
  `HashMagic` varchar(255) NOT NULL,
  `secret_2fa` varchar(100) DEFAULT NULL,
  `Sexo` char(1) NOT NULL DEFAULT 'M',
  `FechaSistema` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_usuario` (`Usuario`),
  UNIQUE KEY `uq_correo` (`Correo`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `Nombre`, `Apellido`, `Usuario`, `Correo`, `HashMagic`, `secret_2fa`, `Sexo`, `FechaSistema`) VALUES
(1, 'Juan', 'Perez', 'juan.perez23@gmail.com', 'juan.perez23@gmail.com', '$2y$13$tLE9/WCIroBAB7GyJbGdQO4YUK6KiJj9ZobwKBX10NdfPPNc1Pzji', '5PRW3DRNLM3245TN', 'M', '2026-06-02 14:08:08'),
(2, 'Joseph', 'Guerra', 'josephguerra24@gmail.com', 'josephguerra24@gmail.com', '$2y$13$dZrK7fs9QW3h8DsJhYumZewwxf1uRao/XAnbPWo6AW5AU7CU1oqFC', 'CBMTYMFAWQ33QJV5', 'M', '2026-06-02 14:23:23'),
(3, 'Maria', 'Gónzalez', 'mariag90@gmail.com', 'mariag90@gmail.com', '$2y$13$KaxTRguCHdre/o9GOgPPkOlc5TFDljUGYIPb.3OTKddx7ozEH1QpO', 'QQE6HAJBMYHTMY3V', 'M', '2026-06-02 23:53:26'),
(4, 'Joseph', 'Guerra', 'joseph27@gmail.com', 'joseph27@gmail.com', '$2y$13$yHJSZIfDBLfqUkGz5mGWQe3.EcdC5OmsRj.mUS/vbIs/48kLCAgTu', 'SCB5GFAI3NRP3JZ6', 'M', '2026-06-02 23:59:53'),
(5, 'Joseph', 'Cardoze', 'joseph78@gmail.com', 'joseph78@gmail.com', '$2y$13$Pyiad2RSvxLlH88bCys8au.y6UkmSxS1L4W2E0dilRkdrsO2O4cQO', 'R67KAUHTDBN5VMEG', 'M', '2026-06-03 00:08:30'),
(6, 'Clara', 'Mendoza', 'mendozaclara23@gmail.com', 'mendozaclara23@gmail.com', '$2y$13$4WkgwO0OxPbf00SJCTaL3uXgA5a063LnH1PLvg/5RsiQx8C/sZRkW', 'IMQ4V2NBNOLUQ354', 'M', '2026-06-03 00:10:31'),
(7, 'Joseph', 'Mendieta', 'josephm45@gmail.com', 'josephm45@gmail.com', '$2y$13$IO0NcfQIbC6MWjWAaKmisuS65HduYNHijKBYHrENj3u2YOj4tpC/i', 'QUPD6DQPFEBV53K2', 'M', '2026-06-03 14:49:26'),
(8, 'Joseph', 'Cardoze', 'josephcardoze23@gmail.com', 'josephcardoze23@gmail.com', '$2y$13$Kt/KOBSCBmZgbcYpACVziOUXrVBWs3rT5VUxVX/.2ArHLnMOOZHvu', 'IYSOD3J6HGBTFVS3', 'M', '2026-06-03 15:39:19'),
(9, 'Francesco', 'Iemma', 'francescom23@gmail.com', 'francescom23@gmail.com', '$2y$13$cmBoKUkGXGxOG2BFqqA7L./XCBO01iXbdd1wYMk12TnsjVMSxzIvu', 'V6C4IIF5UBPQQ4S6', 'M', '2026-06-03 15:43:20'),
(10, 'Carlos', 'Mendieta', 'carlosjim12@gmail.com', 'carlosjim12@gmail.com', '$2y$13$FrebqNVoFPyRY9t/wSEUuuAeK8HHR8wP.Nl1zdDbUMZJH/p5lrssm', 'HHRAWNFOEJVH6J55', 'M', '2026-06-03 15:46:01'),
(11, 'Samuel', 'Gonzaléz', 'gonzalezsam67@gmail.com', 'gonzalezsam67@gmail.com', '$2y$13$Qczb4mSvMytgY7o8vVtPZu88iEdOdmcBXWPqsZt/mHSXxXc3iRrvi', 'M6ZN52RFRQCMU5TI', 'M', '2026-06-03 15:51:53'),
(12, 'Sara', 'Rivera', 'riverasara10@gmail.com', 'riverasara10@gmail.com', '$2y$13$t1SLTi9x9voNmui0mP3rDOJGUE/VUvernAByeFufM0WsQhFlygwGm', 'A4IAO2EMMGE5D6B6', 'M', '2026-06-03 15:55:10'),
(13, 'Francesco', 'Medina', 'francescomed45@gmail.com', 'francescomed45@gmail.com', '$2y$13$MWLUWiM11NKBn0j5QQKz1e9kIysWE.5yRk/uManPLnQxXTkznljl.', '32H7AN46AYFCFR47', 'M', '2026-06-03 16:17:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `verificaciones_2fa`
--

DROP TABLE IF EXISTS `verificaciones_2fa`;
CREATE TABLE IF NOT EXISTS `verificaciones_2fa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Usuario` varchar(100) NOT NULL,
  `ipRemoto` varchar(45) NOT NULL,
  `exitoso` tinyint(1) NOT NULL DEFAULT '0',
  `FechaVerificacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `verificaciones_2fa`
--

INSERT INTO `verificaciones_2fa` (`id`, `Usuario`, `ipRemoto`, `exitoso`, `FechaVerificacion`) VALUES
(1, 'gonzalezsam67@gmail.com', '::1', 1, '2026-06-03 10:52:21'),
(2, 'francescomed45@gmail.com', '::1', 1, '2026-06-03 11:17:43'),
(3, 'josephm45@gmail.com', '::1', 1, '2026-06-06 13:43:54');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
