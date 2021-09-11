-- phpMyAdmin SQL Dump
-- version 3.3.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 19, 2018 at 07:35 PM
-- Server version: 5.1.44
-- PHP Version: 5.2.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fcm_push_notification_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usu` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) NOT NULL,
  `token` varchar(250) NOT NULL DEFAULT 'no_token',
  `dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usu`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id_usu`, `nome`, `token`, `dt`) VALUES
(3, 'Chrome - pc - Douglas', 'f-CAEviRuww:APA91bGeFXWZxZXGeuvM2DDjtWPdpiNb8AB2Qf2RBhjmeIeiobJwEjZTUx27lTSsMC9PJYSz5uiNK_rllvqX_E4VOnH5o-he93rUJ6l5o9BguMS2sdHce4byaXpQc8W2CKO_IEuLrtG7', '2018-08-19 17:25:49');
