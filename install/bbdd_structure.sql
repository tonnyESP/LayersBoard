-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 30-01-2017 a las 19:05:55
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dataset`
--

CREATE TABLE IF NOT EXISTS `dataset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `path` varchar(220) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image` varchar(120) NOT NULL,
  `public` tinyint(1) NOT NULL,
  `isDefault` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `experiment`
--

CREATE TABLE IF NOT EXISTS `experiment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `user_id` int(11) NOT NULL,
  `const_threads` int(11) NOT NULL,
  `const_batch` int(11) NOT NULL,
  `const_log_filename` varchar(200) NOT NULL,
  `const_seed` int(11) NOT NULL,
  `network_raw` text NOT NULL,
  `script_raw` text NOT NULL,
  `best_result_test` float DEFAULT NULL,
  `best_result_train` float DEFAULT NULL,
  `epoc_best_result_test` int(11) DEFAULT NULL,
  `epoc_best_result_train` int(11) DEFAULT NULL,
  `dataset_id` int(11) NOT NULL,
  `process_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `start_train_date` datetime NOT NULL,
  `end_train_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=75 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `role` int(11) NOT NULL,
  `image` varchar(120) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
