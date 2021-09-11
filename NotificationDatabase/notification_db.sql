-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 28-Out-2018 às 04:35
-- Versão do servidor: 5.6.13
-- versão do PHP: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `notification_db`
--
CREATE DATABASE IF NOT EXISTS `notification_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `notification_db`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `id_not` int(11) NOT NULL AUTO_INCREMENT,
  `title_not` varchar(45) NOT NULL,
  `msg_not` varchar(150) NOT NULL,
  `visu_not` int(1) NOT NULL,
  `dt_not` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_not`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `notification`
--

INSERT INTO `notification` (`id_not`, `title_not`, `msg_not`, `visu_not`, `dt_not`) VALUES
(1, 'title notification', 'body da msg, notification', 0, '2018-10-27 22:40:22'),
(2, '2', '2', 0, '2018-10-28 03:50:14');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
