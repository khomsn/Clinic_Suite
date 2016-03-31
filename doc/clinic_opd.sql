-- --------------------------------------------------------
-- SQL Commands to set up the pmadb as described in the documentation.
--
-- This file is meant for use with MySQL 5 and above!
--
-- This script expects the user pma to already be existing. If we would put a
-- line here to create him too many users might just use this script and end
-- up with having the same password for the controluser.
--
-- This user "pma" must be defined in config.inc.php (controluser/controlpass)
--
-- Please don't forget to set up the tablenames in config.inc.php
--

-- --------------------------------------------------------

--
-- Database : `clinic_opd`
--
CREATE DATABASE IF NOT EXISTS `clinic_opd`
  DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE clinic_opd;

-- --------------------------------------------------------

--
-- Privileges
--
-- (activate this statement if necessary)
-- GRANT SELECT, INSERT, DELETE, UPDATE, ALTER ON `clinic_opd`.* TO
--    'pma'@localhost;

-- --------------------------------------------------------

-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 11, 2015 at 06:45 PM
-- Server version: 5.5.44-0ubuntu0.14.04.1-log
-- PHP Version: 5.5.9-1ubuntu4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `clinic_opd`
--

-- --------------------------------------------------------

--
-- Table structure for table `patient_id`
--
CREATE TABLE IF NOT EXISTS `patient_id` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ctz_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `prefix` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `lname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `bloodgrp` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `height` smallint(3) NOT NULL DEFAULT '1',
  `drug_alg_1` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `drug_alg_2` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `drug_alg_3` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `drug_alg_4` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `drug_alg_5` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `chro_ill_1` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `chro_ill_2` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `chro_ill_3` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `chro_ill_4` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `chro_ill_5` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `concurdrug` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `address1` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `address2` tinyint(4) NOT NULL,
  `address3` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `address4` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `address5` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` mediumint(5) NOT NULL,
  `hometel` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `log` int(11) NOT NULL,
  `staff` tinyint(1) NOT NULL,
  `reccomp` smallint(6) NOT NULL,
  `fup` tinyint(1) NOT NULL,
  `clinic` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='patient information' AUTO_INCREMENT=1 ;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
