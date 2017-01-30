-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 30-01-2017 a las 19:06:04
-- Versión del servidor: 5.5.54-0ubuntu0.14.04.1
-- Versión de PHP: 5.5.9-1ubuntu4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `layers`
--

--
-- Volcado de datos para la tabla `dataset`
--

INSERT INTO `dataset` (`id`, `name`, `path`, `user_id`, `image`, `public`, `isDefault`) VALUES
(1, 'MNIST', 'Data/MNIST', 1, 'preview.png', 1, 1),
(2, 'CIFAR-10', 'Data/CIFAR-10', 1, 'preview.png', 1, 1),
(3, 'CIFAR-100', 'Data/CIFAR-100', 1, 'preview.png', 1, 1),
(4, 'MIT-UrbanNatural', 'Data/MIT-UrbanNatural', 1, 'preview.png', 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
