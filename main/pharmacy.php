<?php 
include '../login/dbc.php';
page_protect();

$sql="CREATE TABLE IF NOT EXISTS `drug_id` (
`id` smallint(6) NOT NULL,
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
  `unit` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `candp` tinyint(1) NOT NULL,
  `staffcanorder` tinyint(1) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
mysqli_query($link, $sql);
?>
<!DOCTYPE html>
<html>
<head>
<title>ห้องยา</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<link rel="stylesheet" href="../public/css/styles.css">
</head>
<?php 
if(!empty($_SESSION['user_background']))
{
echo "<body style='background-image: url(".$_SESSION['user_background'].");' alink='#000088' link='#006600' vlink='#660000'>";
}
else
{
?>
<body style="background-image: url(../image/ptbg.jpg);" alink="#000088" link="#006600" vlink="#660000">
<?php
}
?>
<div class="pos_l_fix">
<?php 
/*********************** MYACCOUNT MENU ****************************
This code shows my account menu only to logged in users. 
Copy this code till END and place it in a new html or php where
you want to show myaccount options. This is only visible to logged in users
*******************************************************************/
if (isset($_SESSION['user_id'])) 
{
include 'drugmenu.php';
}
/*******************************END**************************/
?>
</div>

<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr><td style="text-align: center; width: 150px; "></td>
      <td valign="top"  style="text-align: left;"><p>&nbsp;</p>
      <h3 class="titlehdr">ระบบห้องยา</h3>
      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
      <tr>
      <td width="40%">การใช้งานระบบห้องยา<br>
      ระบบห้องยาประกอบด้วย
	  <ul>
	  <li>รายการยาและผลิตภัณฑ์</li>
	  <li>รายการยาพิเศษ</li>
	  <li>ชุดยา</li>
	  <li>เพิ่ม รายการยาและผลิตภัณฑ์</li>
	  <li>ลบ รายการยาและผลิตภัณฑ์</li>
	  <li>เบิกใช้ ยาและผลิตภัณฑ์</li>
	  <li>นำเข้า ยา และ ผลิตภัณฑ์</li>
	  <li>ยาถึงกำหนดซื้อ</li>
	  <li>Supplier</li>
	  </ul>
      </td>
      <td bgcolor="#B6B6B4"><font color="#990012"> <p>
      <big></big>
      </p></font></td>
      </tr>
      </table>
      </td>
      <td width="160" valign="top">
      </td>
  </tr>
</table>
<!--end menu-->
</body></html>