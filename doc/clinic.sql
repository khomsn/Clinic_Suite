-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 03, 2016 at 02:09 PM
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
-- Database : `clinic`
--
CREATE DATABASE IF NOT EXISTS `clinic`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clinic;

-- --------------------------------------------------------

--
-- Database: `clinic`
--

-- --------------------------------------------------------

--
-- Table structure for table `acnumber`
--

CREATE TABLE IF NOT EXISTS `acnumber` (
  `ac_no` int(11) NOT NULL,
  `name` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `acnumber`
--

INSERT INTO `acnumber` (`ac_no`, `name`) VALUES
(1001, 'เงินสด'),
(4001, 'รายได้อื่นๆ-รับ');

-- --------------------------------------------------------

--
-- Table structure for table `allrsupm`
--

CREATE TABLE IF NOT EXISTS `allrsupm` (
  `id` int(11) NOT NULL,
  `drugid` int(10) NOT NULL,
  `mandy` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE IF NOT EXISTS `appointment` (
  `idno` smallint(6) NOT NULL,
  `fudate` date NOT NULL,
  `furs` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fuby` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='follow up table';

-- --------------------------------------------------------

--
-- Table structure for table `commission`
--

CREATE TABLE IF NOT EXISTS `commission` (
  `sellofmon` int(11) NOT NULL,
  `perofcom` decimal(3,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_account`
--

CREATE TABLE IF NOT EXISTS `daily_account` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `ac_no_i` int(11) NOT NULL,
  `ac_no_o` int(11) NOT NULL,
  `detail` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `inv_num` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` float(9,2) NOT NULL,
  `type` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bors` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `recordby` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `debtors`
--

CREATE TABLE IF NOT EXISTS `debtors` (
  `ctmid` int(11) NOT NULL,
  `ctmacno` int(11) NOT NULL,
  `price` decimal(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deleted_drug`
--

CREATE TABLE IF NOT EXISTS `deleted_drug` (
  `id` int(11) NOT NULL,
  `dname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dgname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ac_no` INT NOT NULL,
  `dtime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bystid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE IF NOT EXISTS `discount` (
  `ctmid` mediumint(9) NOT NULL,
  `percent` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drugcombset`
--

CREATE TABLE IF NOT EXISTS `drugcombset` (
  `id` tinyint(4) NOT NULL,
  `drugidin` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invol` tinyint(4) NOT NULL,
  `drugidout` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `outvol` decimal(3,1) NOT NULL,
  `outsetpoint` decimal(4,1) NOT NULL,
  `outcount` decimal(4,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drugtouse`
--

CREATE TABLE IF NOT EXISTS `drugtouse` (
  `id` smallint(6) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `drugid` smallint(6) NOT NULL,
  `volume` smallint(6) NOT NULL,
  `user` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drug_group`
--

CREATE TABLE IF NOT EXISTS `drug_group` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='group of product';

--
-- Dumping data for table `drug_group`
--

INSERT INTO `drug_group` (`id`, `name`) VALUES
(16, 'ACEI'),
(12, 'Acetaminophen'),
(5, 'Aminoglycosides'),
(18, 'ARBS'),
(17, 'Benzodiazepines'),
(14, 'Beta-Blocker'),
(13, 'Ca-C-Blocker'),
(3, 'Carbapenems'),
(2, 'Cephalosporins'),
(6, 'Fluoroquinolones'),
(19, 'GI-PPI'),
(9, 'Lincosamides'),
(4, 'Macrolides'),
(1, 'Penicillins'),
(15, 'Statins'),
(10, 'Streptogramins'),
(7, 'Sulfonamides'),
(8, 'Tetracyclines'),
(11, 'Tricyclics');

-- --------------------------------------------------------

--
-- Table structure for table `drug_id`
--

CREATE TABLE IF NOT EXISTS `drug_id` (
  `id` smallint(6) NOT NULL,
  `dname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dgname` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uses` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `indication` text COLLATE utf8mb4_unicode_ci,
  `size` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume` smallint(6) NOT NULL DEFAULT '0',
  `volreserve` smallint(6) NOT NULL DEFAULT '0',
  `sellprice` float(8,2) NOT NULL DEFAULT '0.00',
  `min_limit` smallint(4) NOT NULL DEFAULT '0',
  `typen` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `groupn` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subgroup` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seti` tinyint(1) NOT NULL DEFAULT '0',
  `ac_no` int(11) NOT NULL,
  `track` tinyint(1) NOT NULL DEFAULT '0',
  `disct` tinyint(1) NOT NULL DEFAULT '0',
  `prod` tinyint(1) NOT NULL DEFAULT '0',
  `RawMat` tinyint(1) NOT NULL DEFAULT '0',
  `cat` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `dinteract` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `candp` tinyint(1) NOT NULL DEFAULT '0',
  `staffcanorder` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drug_subgroup`
--

CREATE TABLE IF NOT EXISTS `drug_subgroup` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='group of product';

--
-- Dumping data for table `drug_subgroup`
--

INSERT INTO `drug_subgroup` (`id`, `name`) VALUES
(2, 'Antileprotic'),
(1, 'Antituberculous'),
(4, 'Orphenadrine'),
(3, 'Sulfonamide');

-- --------------------------------------------------------

--
-- Table structure for table `drug_type`
--

CREATE TABLE IF NOT EXISTS `drug_type` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='type of product';

--
-- Dumping data for table `drug_type`
--

INSERT INTO `drug_type` (`id`, `name`) VALUES
(14, 'Aesthetics and Beauty'),
(5, 'Fee'),
(6, 'Raw_Mat'),
(1, 'Treatment'),
(3, 'ยากิน-น้ำ'),
(2, 'ยากิน-เม็ด'),
(4, 'ยาฉีด'),
(7, 'ยาทา'),
(8, 'ยาพ่น'),
(9, 'ยาภายนอก'),
(10, 'ยาหยอดตา'),
(12, 'ยาหยอดหู'),
(11, 'ยาเหน็บ'),
(13, 'อุปกรณ์');

-- --------------------------------------------------------

--
-- Table structure for table `dupm`
--

CREATE TABLE IF NOT EXISTS `dupm` (
  `id` int(11) NOT NULL,
  `drugid` int(11) NOT NULL,
  `mon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `vol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab`
--

CREATE TABLE IF NOT EXISTS `lab` (
  `id` smallint(6) NOT NULL,
  `L_Name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `S_Name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `L_Set` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `L_specimen` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Lrunit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `normal_r` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `r_min` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `r_max` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Linfo` text COLLATE utf8mb4_unicode_ci,
  `price` smallint(6) NOT NULL DEFAULT '0',
  `volume` int(11) NOT NULL DEFAULT '0',
  `ltr` char(1) COLLATE utf8mb4_unicode_ci  NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `labstat`
--

CREATE TABLE IF NOT EXISTS `labstat` (
  `id` int(11) NOT NULL,
  `labid` int(11) NOT NULL,
  `MandY` date NOT NULL,
  `vol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `labwait`
--

CREATE TABLE IF NOT EXISTS `labwait` (
  `ptid` mediumint(9) NOT NULL,
  `rid` smallint(6) NOT NULL,
  `tablename` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dtr` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `ID` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `table_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `column_h` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `row_no` int(11) NOT NULL,
  `old_value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_value` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maskid`
--

CREATE TABLE IF NOT EXISTS `maskid` (
  `id` int(11) NOT NULL,
  `drugid` int(11) NOT NULL,
  `dname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dgname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mask` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parameter`
--

CREATE TABLE IF NOT EXISTS `parameter` (
  `ID` tinyint(4) NOT NULL DEFAULT '1',
  `name` text COLLATE utf8mb4_unicode_ci,
  `Ename` text COLLATE utf8mb4_unicode_ci,
  `cliniclcid` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `Eaddress` text COLLATE utf8mb4_unicode_ci,
  `tel` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `normprice` smallint(6) NOT NULL DEFAULT '0',
  `fup` smallint(6) NOT NULL DEFAULT '0',
  `tmp` smallint(6) NOT NULL DEFAULT '0',
  `maxcp` smallint(6) NOT NULL DEFAULT '0',
  `Staffp` smallint(4) NOT NULL DEFAULT '0',
  `name_lc` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lcid` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prtopdcard` tinyint(1) NOT NULL DEFAULT '0',
  `df` tinyint(4) NOT NULL DEFAULT '0',
  `dfp` smallint(6) NOT NULL DEFAULT '0',
  `opdidoffset` smallint(6) NOT NULL DEFAULT '0',
  `maskingdrugid` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pt_to_doc`
--

CREATE TABLE IF NOT EXISTS `pt_to_doc` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ID` smallint(6) NOT NULL,
  `prefix` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `F_Name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `L_Name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pt_to_drug`
--

CREATE TABLE IF NOT EXISTS `pt_to_drug` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` int(11) NOT NULL,
  `prefix` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fname` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pt_to_lab`
--

CREATE TABLE IF NOT EXISTS `pt_to_lab` (
  `rtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ptid` int(11) NOT NULL,
  `prefix` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pt_to_obs`
--

CREATE TABLE IF NOT EXISTS `pt_to_obs` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` int(11) NOT NULL,
  `prefix` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fname` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pt_to_scr`
--

CREATE TABLE IF NOT EXISTS `pt_to_scr` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ID` smallint(6) NOT NULL,
  `prefix` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `F_Name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `L_Name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pt_to_treatment`
--

CREATE TABLE IF NOT EXISTS `pt_to_treatment` (
  `rtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ptid` int(11) NOT NULL,
  `prefix` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rawmat`
--

CREATE TABLE IF NOT EXISTS `rawmat` (
  `id` tinyint(4) NOT NULL,
  `rawcode` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rawname` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sunit` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lowlimit` smallint(6) NOT NULL DEFAULT '0',
  `volume` smallint(6) NOT NULL DEFAULT '0',
  `ac_no` int(11) NOT NULL,
  `rmfpd` tinyint(1) NOT NULL DEFAULT '0',
  `rmtype` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rawmattouse`
--

CREATE TABLE IF NOT EXISTS `rawmattouse` (
  `id` smallint(6) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rawmatid` smallint(6) NOT NULL,
  `volume` smallint(6) NOT NULL,
  `user` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reccompany`
--

CREATE TABLE IF NOT EXISTS `reccompany` (
  `id` int(11) NOT NULL,
  `comname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comdt` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sell_account`
--

CREATE TABLE IF NOT EXISTS `sell_account` (
  `id` int(11) NOT NULL,
  `day` tinyint(2) NOT NULL,
  `month` tinyint(2) NOT NULL,
  `year` smallint(4) NOT NULL,
  `ctmid` int(11) NOT NULL,
  `ctmacno` int(11) NOT NULL,
  `cash` decimal(7,2) NOT NULL,
  `own` decimal(7,2) NOT NULL,
  `total` decimal(7,2) NOT NULL,
  `ddx` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tty` float NOT NULL,
  `vsdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE IF NOT EXISTS `staff` (
  `ID` smallint(6) NOT NULL,
  `prefix` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `F_Name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `L_Name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `Eprefix` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `EF_Name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `EL_Name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ctz_id` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `add_hno` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `add_mu` tinyint(4) NOT NULL,
  `add_t` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `add_a` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `add_j` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `add_zip` mediumint(5) NOT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `h_tel` varchar(18) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `posit` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` smallint(6) NOT NULL,
  `regtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `clog` tinyint(4) NOT NULL DEFAULT '0',
  `ch_by` smallint(6) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE IF NOT EXISTS `supplier` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ac_no` int(11) NOT NULL,
  `paydetail` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trpstep`
--

CREATE TABLE IF NOT EXISTS `trpstep` (
  `id` int(11) NOT NULL,
  `drugid` int(11) NOT NULL,
  `firstone` tinyint(4) NOT NULL,
  `init_pr` int(11) NOT NULL,
  `secstep` tinyint(4) NOT NULL,
  `sec_pr` int(11) NOT NULL,
  `tristep` tinyint(4) NOT NULL,
  `tri_pr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='treatment price step cal';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) NOT NULL,
  `md5_id` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `full_name` tinytext COLLATE utf8mb4_unicode_ci,
  `user_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_email` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `pwd` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `address` text COLLATE utf8mb4_unicode_ci,
  `country` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tel` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` text COLLATE utf8mb4_unicode_ci,
  `date` date NOT NULL DEFAULT '2014-05-06',
  `users_ip` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved` int(1) NOT NULL DEFAULT '0',
  `activation_code` int(10) NOT NULL DEFAULT '0',
  `banned` int(1) NOT NULL DEFAULT '0',
  `accode` smallint(6) NOT NULL DEFAULT '1',
  `user_level` tinyint(4) NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ckey` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_id` smallint(6) NOT NULL DEFAULT '0',
  `user_has_avatar` tinyint(1) NOT NULL DEFAULT '0',
  `user_background` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catcenable` tinyint(1) NOT NULL DEFAULT '0',
  `ddil` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `md5_id`, `full_name`, `user_name`, `user_email`, `pwd`, `address`, `country`, `tel`, `fax`, `website`, `date`, `users_ip`, `approved`, `activation_code`, `banned`, `accode`, `user_level`, `ctime`, `ckey`, `staff_id`, `user_has_avatar`, `user_background`, `catcenable`, `ddil`) VALUES
(49, '', 'demo', 'demo', 'demo@localhost', '64a4f7c2d92ac20aea68659e66c8a01db00c7be7a91bdafa1', 'd', '', 'd', '', '', '2015-10-11', '', 1, 0, 0, 30030, 5, '2016-06-03 08:03:56', 'u2yowv3', 0, 1, '', 0, 0),
(50, 'c0c7c76d30bd3dcaefc96f40275bdc0a', 'Administrator', 'admin', 'admin@localhost', 'a3dc56cc996c89050461c26c3a292af2a257d6ebaec02f722', '', '', '', '', '', '2014-05-06', '', 1, 0, 0, 1, 5, '2016-06-03 08:03:51', '', 2, 0, '', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acnumber`
--
ALTER TABLE `acnumber`
  ADD UNIQUE KEY `ac_no` (`ac_no`);

--
-- Indexes for table `allrsupm`
--
ALTER TABLE `allrsupm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `daily_account`
--
ALTER TABLE `daily_account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `debtors`
--
ALTER TABLE `debtors`
  ADD PRIMARY KEY (`ctmid`);

--
-- Indexes for table `deleted_drug`
--
ALTER TABLE `deleted_drug`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `drugcombset`
--
ALTER TABLE `drugcombset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `drugtouse`
--
ALTER TABLE `drugtouse`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drug_group`
--
ALTER TABLE `drug_group`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `drug_id`
--
ALTER TABLE `drug_id`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drug_subgroup`
--
ALTER TABLE `drug_subgroup`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `drug_type`
--
ALTER TABLE `drug_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `dupm`
--
ALTER TABLE `dupm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `lab`
--
ALTER TABLE `lab`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `labstat`
--
ALTER TABLE `labstat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `labwait`
--
ALTER TABLE `labwait`
  ADD UNIQUE KEY `tablename` (`tablename`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `index` (`ID`);

--
-- Indexes for table `maskid`
--
ALTER TABLE `maskid`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parameter`
--
ALTER TABLE `parameter`
  ADD KEY `ID` (`ID`);

--
-- Indexes for table `pt_to_doc`
--
ALTER TABLE `pt_to_doc`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `pt_to_drug`
--
ALTER TABLE `pt_to_drug`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pt_to_lab`
--
ALTER TABLE `pt_to_lab`
  ADD UNIQUE KEY `ptid` (`ptid`);

--
-- Indexes for table `pt_to_obs`
--
ALTER TABLE `pt_to_obs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pt_to_scr`
--
ALTER TABLE `pt_to_scr`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `pt_to_treatment`
--
ALTER TABLE `pt_to_treatment`
  ADD UNIQUE KEY `ptid` (`ptid`);

--
-- Indexes for table `rawmat`
--
ALTER TABLE `rawmat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `rawmattouse`
--
ALTER TABLE `rawmattouse`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reccompany`
--
ALTER TABLE `reccompany`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `sell_account`
--
ALTER TABLE `sell_account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD UNIQUE KEY `ctz_id` (`ctz_id`,`license`,`user_id`),
  ADD KEY `ID` (`ID`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `trpstep`
--
ALTER TABLE `trpstep`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `drugid` (`drugid`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_email` (`user_email`);
ALTER TABLE `users` ADD FULLTEXT KEY `idx_search` (`full_name`,`address`,`user_email`,`user_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allrsupm`
--
ALTER TABLE `allrsupm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `daily_account`
--
ALTER TABLE `daily_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `drugcombset`
--
ALTER TABLE `drugcombset`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `drugtouse`
--
ALTER TABLE `drugtouse`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `drug_group`
--
ALTER TABLE `drug_group`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `drug_id`
--
ALTER TABLE `drug_id`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `drug_subgroup`
--
ALTER TABLE `drug_subgroup`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `drug_type`
--
ALTER TABLE `drug_type`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `dupm`
--
ALTER TABLE `dupm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `labstat`
--
ALTER TABLE `labstat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `maskid`
--
ALTER TABLE `maskid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rawmat`
--
ALTER TABLE `rawmat`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rawmattouse`
--
ALTER TABLE `rawmattouse`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reccompany`
--
ALTER TABLE `reccompany`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sell_account`
--
ALTER TABLE `sell_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `ID` smallint(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `trpstep`
--
ALTER TABLE `trpstep`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
