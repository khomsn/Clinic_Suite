<?php 
include '../login/dbc.php';
page_protect();
unset($_SESSION['LLSName']);
unset($_SESSION['SLSName']);
unset($_SESSION['SetNum']);

$sql= "

CREATE TABLE IF NOT EXISTS `lab` (
  `id` smallint(6) NOT NULL,
  `L_Name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `S_Name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `L_Set` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `L_specimen` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Lrunit` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `normal_r` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `r_min` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `r_max` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Linfo` text COLLATE utf8_unicode_ci,
  `price` smallint(6) NOT NULL DEFAULT '0',
  `volume` int(11) NOT NULL DEFAULT '0',
  `ltr` char(1) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `lab`
  ADD UNIQUE KEY `id` (`id`);
  
";

mysqli_query($link, $sql);
?>
<!DOCTYPE html>
<html>
<head>
<title>Laboratory</title>
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
<table width="100%">
  <tr>
    <td width=130px>
      <div class="menur">
		      <?php 
			      /*********************** MYACCOUNT MENU ****************************
			      This code shows my account menu only to logged in users. 
			      Copy this code till END and place it in a new html or php where
			      you want to show myaccount options. This is only visible to logged in users
			      *******************************************************************/
			      if (isset($_SESSION['user_id']))
			      {
				      include 'labmenu.php';
			      } 
		      /*******************************END**************************/
		      ?>
      </div>
    </td>
    <td>
		      <h3 class="titlehdr">Lab Room</h3>
		      <p>การใช้งานระบบห้อง Lab<p>
		      ระบบห้อง Lab ประกอบด้วย
			  <ul>
			  <li>รายการ Lab สามารถทำการ update Lab ได้</li>
			  <li>เพิ่ม รายการ Lab รายตัว</li>
			  <li>เพิ่ม รายการ ชุด Lab </li>
			  <li>ลงผล Lab</li>
			  <li>Lab Statistics</li>
			  </ul>
   </td>
    <td width=130px>
    <?php include 'labrmenu.php';?>
    </td>
  </tr>
</table>
</body></html>
