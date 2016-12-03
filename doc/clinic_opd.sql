-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 03, 2016 at 02:08 PM
-- Server version: 5.7.12-0ubuntu1-log
-- PHP Version: 7.0.4-7ubuntu2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Database : `clinic_opd`
--
CREATE DATABASE IF NOT EXISTS `clinic_opd`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clinic_opd;

-- --------------------------------------------------------

--
-- Database: `clinic_opd`
--

-- --------------------------------------------------------

--
-- Table structure for table `patient_id`
--

CREATE TABLE `patient_id` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ctz_id` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prefix` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lname` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `bloodgrp` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height` smallint(3) NOT NULL DEFAULT '1',
  `drug_alg_1` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `drug_alg_2` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `drug_alg_3` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `drug_alg_4` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `drug_alg_5` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chro_ill_1` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chro_ill_2` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chro_ill_3` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chro_ill_4` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chro_ill_5` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `concurdrug` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address1` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address2` tinyint(4) NOT NULL DEFAULT '0',
  `address3` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address4` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address5` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode` mediumint(5) NOT NULL DEFAULT '0',
  `hometel` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `log` int(11) NOT NULL DEFAULT '0',
  `staff` tinyint(1) NOT NULL DEFAULT '0',
  `reccomp` smallint(6) NOT NULL DEFAULT '0',
  `fup` tinyint(1) NOT NULL DEFAULT '0',
  `clinic` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='patient information';

