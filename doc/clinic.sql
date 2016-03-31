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
-- Database : `clinic`
--
CREATE DATABASE IF NOT EXISTS `clinic`
  DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE clinic;

-- --------------------------------------------------------

--
-- Privileges
--
-- (activate this statement if necessary)
-- GRANT SELECT, INSERT, DELETE, UPDATE, ALTER ON `clinic`.* TO
--    'pma'@localhost;

-- --------------------------------------------------------
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
-- Database: `clinic`
--

-- --------------------------------------------------------

--
-- Table structure for table `acnumber`
--

CREATE TABLE IF NOT EXISTS `acnumber` (
  `ac_no` int(11) NOT NULL,
  `name` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `ac_no` (`ac_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `drugid` int(10) NOT NULL,
  `mandy` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE IF NOT EXISTS `appointment` (
  `idno` smallint(6) NOT NULL,
  `fudate` date NOT NULL,
  `furs` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `fuby` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='follow up table';

-- --------------------------------------------------------

--
-- Table structure for table `commission`
--

CREATE TABLE IF NOT EXISTS `commission` (
  `sellofmon` int(11) NOT NULL,
  `perofcom` decimal(3,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_account`
--

CREATE TABLE IF NOT EXISTS `daily_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `ac_no_i` int(11) NOT NULL,
  `ac_no_o` int(11) NOT NULL,
  `detail` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `inv_num` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `price` float(9,2) NOT NULL,
  `type` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `bors` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `recordby` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `debtors`
--

CREATE TABLE IF NOT EXISTS `debtors` (
  `ctmid` int(11) NOT NULL,
  `ctmacno` int(11) NOT NULL,
  `price` decimal(7,2) NOT NULL,
  PRIMARY KEY (`ctmid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deleted_drug`
--

CREATE TABLE IF NOT EXISTS `deleted_drug` (
  `id` int(11) NOT NULL,
  `dname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `dgname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `bystid` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE IF NOT EXISTS `discount` (
  `ctmid` mediumint(9) NOT NULL,
  `percent` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drugcombset`
--

CREATE TABLE IF NOT EXISTS `drugcombset` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `drugidin` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `invol` tinyint(4) NOT NULL,
  `drugidout` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `outvol` decimal(3,1) NOT NULL,
  `outsetpoint` decimal(4,1) NOT NULL,
  `outcount` decimal(4,1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `drugtouse`
--

CREATE TABLE IF NOT EXISTS `drugtouse` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `drugid` smallint(6) NOT NULL,
  `volume` smallint(6) NOT NULL,
  `user` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `drug_group`
--

CREATE TABLE IF NOT EXISTS `drug_group` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='group of product' AUTO_INCREMENT=20 ;

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
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `dname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `dgname` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `uses` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `volume` smallint(6) NOT NULL,
  `volreserve` smallint(6) NOT NULL,
  `sellprice` float(8,2) NOT NULL,
  `buyprice` float(7,2) NOT NULL,
  `min_limit` smallint(4) NOT NULL,
  `typen` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `groupn` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `subgroup` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `seti` tinyint(1) NOT NULL DEFAULT '0',
  `ac_no` int(11) NOT NULL,
  `track` tinyint(1) NOT NULL DEFAULT '0',
  `disct` tinyint(1) NOT NULL DEFAULT '0',
  `prod` tinyint(1) NOT NULL DEFAULT '0',
  `RawMat` tinyint(1) NOT NULL DEFAULT '0',
  `cat` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `dinteract` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `unit` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `candp` tinyint(1) NOT NULL,
  `staffcanorder` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `drug_subgroup`
--

CREATE TABLE IF NOT EXISTS `drug_subgroup` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='group of product' AUTO_INCREMENT=5 ;

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
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='type of product' AUTO_INCREMENT=19 ;

--
-- Dumping data for table `drug_type`
--

INSERT INTO `drug_type` (`id`, `name`) VALUES
(18, 'Aesthetics and Beauty'),
(5, 'Fee'),
(6, 'Raw_Mat'),
(1, 'Treatment'),
(8, 'ยากิน-น้ำ'),
(7, 'ยากิน-เม็ด'),
(3, 'ยาฉีด'),
(12, 'ยาทา'),
(16, 'ยาพ่น'),
(15, 'ยาภายนอก'),
(13, 'ยาหยอดตา'),
(14, 'ยาหยอดหู'),
(11, 'ยาเหน็บ'),
(17, 'อุปกรณ์');

-- --------------------------------------------------------

--
-- Table structure for table `dupm`
--

CREATE TABLE IF NOT EXISTS `dupm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `drugid` int(11) NOT NULL,
  `mon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `vol` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lab`
--

CREATE TABLE IF NOT EXISTS `lab` (
  `id` smallint(6) NOT NULL,
  `L_Name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `S_Name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `L_Set` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `L_specimen` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `Lrunit` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `normal_r` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `r_min` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `r_max` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `Linfo` text COLLATE utf8_unicode_ci NOT NULL,
  `price` smallint(6) NOT NULL,
  `volume` int(11) NOT NULL,
  `ltr` tinyint(1) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `labstat`
--

CREATE TABLE IF NOT EXISTS `labstat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `labid` int(11) NOT NULL,
  `MandY` date NOT NULL,
  `vol` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `labwait`
--

CREATE TABLE IF NOT EXISTS `labwait` (
  `ptid` mediumint(9) NOT NULL,
  `rid` smallint(6) NOT NULL,
  `tablename` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `dtr` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `tablename` (`tablename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `table_name` text COLLATE utf8_unicode_ci NOT NULL,
  `column_h` text COLLATE utf8_unicode_ci NOT NULL,
  `row_no` int(11) NOT NULL,
  `old_value` text COLLATE utf8_unicode_ci NOT NULL,
  `new_value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `index` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pt_to_doc`
--

CREATE TABLE IF NOT EXISTS `pt_to_doc` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ID` smallint(6) NOT NULL,
  `prefix` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `F_Name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `L_Name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pt_to_drug`
--

CREATE TABLE IF NOT EXISTS `pt_to_drug` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` int(11) NOT NULL,
  `prefix` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `lname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pt_to_lab`
--

CREATE TABLE IF NOT EXISTS `pt_to_lab` (
  `rtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ptid` int(11) NOT NULL,
  `prefix` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `lname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `ptid` (`ptid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pt_to_obs`
--

CREATE TABLE IF NOT EXISTS `pt_to_obs` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` int(11) NOT NULL,
  `prefix` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `lname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pt_to_scr`
--

CREATE TABLE IF NOT EXISTS `pt_to_scr` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ID` smallint(6) NOT NULL,
  `prefix` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `F_Name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `L_Name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pt_to_treatment`
--

CREATE TABLE IF NOT EXISTS `pt_to_treatment` (
  `rtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ptid` int(11) NOT NULL,
  `prefix` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `lname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `ptid` (`ptid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rawmat`
--

CREATE TABLE IF NOT EXISTS `rawmat` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `rawcode` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `rawname` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `sunit` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `lowlimit` smallint(6) NOT NULL,
  `volume` smallint(6) NOT NULL,
  `ac_no` int(11) NOT NULL,
  `rmfpd` tinyint(1) NOT NULL,
  `rmtype` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'other',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rawmattouse`
--

CREATE TABLE IF NOT EXISTS `rawmattouse` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rawmatid` smallint(6) NOT NULL,
  `volume` smallint(6) NOT NULL,
  `user` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reccompany`
--

CREATE TABLE IF NOT EXISTS `reccompany` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `comdt` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sell_account`
--

CREATE TABLE IF NOT EXISTS `sell_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `day` tinyint(2) NOT NULL,
  `month` tinyint(2) NOT NULL,
  `year` smallint(4) NOT NULL,
  `ctmid` int(11) NOT NULL,
  `ctmacno` int(11) NOT NULL,
  `cash` decimal(7,2) NOT NULL,
  `own` decimal(7,2) NOT NULL,
  `total` decimal(7,2) NOT NULL,
  `ddx` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `tty` float NOT NULL,
  `vsdate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE IF NOT EXISTS `staff` (
  `ID` smallint(6) NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `F_Name` text COLLATE utf8_unicode_ci NOT NULL,
  `L_Name` text COLLATE utf8_unicode_ci NOT NULL,
  `Eprefix` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `Efname` text COLLATE utf8_unicode_ci NOT NULL,
  `Elname` text COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `ctz_id` bigint(13) NOT NULL,
  `birthday` date NOT NULL,
  `add_hno` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `add_mu` tinyint(4) NOT NULL,
  `add_t` text COLLATE utf8_unicode_ci NOT NULL,
  `add_a` text COLLATE utf8_unicode_ci NOT NULL,
  `add_j` text COLLATE utf8_unicode_ci NOT NULL,
  `add_zip` mediumint(5) NOT NULL,
  `mobile` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `h_tel` varchar(18) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `posit` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `license` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` smallint(6) NOT NULL,
  `regtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `clog` tinyint(4) NOT NULL,
  `ch_by` smallint(6) NOT NULL,
  `status` tinyint(1) NOT NULL,
  UNIQUE KEY `ctz_id` (`ctz_id`,`license`,`user_id`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE IF NOT EXISTS `supplier` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `agent` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `ac_no` int(11) NOT NULL,
  `paydetail` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `trpstep`
--

CREATE TABLE IF NOT EXISTS `trpstep` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `drugid` int(11) NOT NULL,
  `firstone` tinyint(4) NOT NULL,
  `init_pr` int(11) NOT NULL,
  `secstep` tinyint(4) NOT NULL,
  `sec_pr` int(11) NOT NULL,
  `tristep` tinyint(4) NOT NULL,
  `tri_pr` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `drugid` (`drugid`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='treatment price step cal' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `md5_id` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `full_name` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `user_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_email` varchar(220) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pwd` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tel` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fax` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `website` text COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `users_ip` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `approved` int(1) NOT NULL DEFAULT '0',
  `activation_code` int(10) NOT NULL DEFAULT '0',
  `banned` int(1) NOT NULL DEFAULT '0',
  `accode` smallint(6) NOT NULL DEFAULT '1',
  `user_level` tinyint(4) NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ckey` tinyint(4) NOT NULL,
  `staff_id` smallint(6) NOT NULL,
  `user_has_avatar` tinyint(1) NOT NULL,
  `user_background` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `catcenable` tinyint(1) NOT NULL,
  `ddil` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email` (`user_email`),
  FULLTEXT KEY `idx_search` (`full_name`,`address`,`user_email`,`user_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=51 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `md5_id`, `full_name`, `user_name`, `user_email`, `pwd`, `address`, `country`, `tel`, `fax`, `website`, `date`, `users_ip`, `approved`, `activation_code`, `banned`, `accode`, `user_level`, `ctime`, `ckey`, `staff_id`, `user_has_avatar`, `user_background`, `catcenable`) VALUES
(50, 'c0c7c76d30bd3dcaefc96f40275bdc0a', 'Administrator', 'admin', 'admin@localhost', 'a3dc56cc996c89050461c26c3a292af2a257d6ebaec02f722', '', '', '', '', '', '2014-05-06', '', 1, 0, 0, 1, 5, '0000-00-00 00:00:00', 0, 2, 0, '', 0),
(49, '', 'demo', 'demo', 'demo@localhost', '46296bc20ddcae364a22ffc072f97c78a59136578be1944ae', 'd', '', 'd', '', '', '2015-10-11', '', 1, 0, 0, 30030, 5, '0000-00-00 00:00:00', 20, 0, 1, '', 0);

-- --------------------------------------------------------

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
