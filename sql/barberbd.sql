-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 22-08-2024 a las 18:23:01
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `barberbd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tcitas`
--

CREATE TABLE `tcitas` (
  `IdCitas` int NOT NULL,
  `IdUser` int NOT NULL,
  `DiaMesAnio` date NOT NULL,
  `Hora` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tusuarios`
--

CREATE TABLE `tusuarios` (
  `IdUser` int NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Correo` varchar(50) NOT NULL,
  `Numero` varchar(20) NOT NULL,
  `PasswordU` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tusuarios`
--

INSERT INTO `tusuarios` (`IdUser`, `Nombre`, `Correo`, `Numero`, `PasswordU`) VALUES
(2, 'Juan B', 'XXXXXXXXXX@gmail.com', '+XXXXXXXXXXXXX', '$2y$10$dsEvT.VFoLQjgmNh0EL1ue.7o5VoBhlGRZygBGBA8rjsybnpbHw7m'),
(5, 'Enrique', 'enriqueadmin@gmail.com', '+5215555555555', '$2y$10$hs2mvbxRDbWymjhw7Tn7nuMTPCyktN3tAlN17n6yzI1Fkc88DhfFu');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tcitas`
--
ALTER TABLE `tcitas`
  ADD PRIMARY KEY (`IdCitas`);

--
-- Indices de la tabla `tusuarios`
--
ALTER TABLE `tusuarios`
  ADD PRIMARY KEY (`IdUser`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tcitas`
--
ALTER TABLE `tcitas`
  MODIFY `IdCitas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `tusuarios`
--
ALTER TABLE `tusuarios`
  MODIFY `IdUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
