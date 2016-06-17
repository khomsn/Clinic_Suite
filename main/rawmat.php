<?php 
include '../login/dbc.php';
page_protect();
$sql = "

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

ALTER TABLE `rawmat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);
  
ALTER TABLE `rawmat`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT;

";

mysqli_query($link, $sql);

?>
<!DOCTYPE html>
<html>
<head>
<title>ห้องคลังวัตถุดิบ</title>
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
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr><td width="160" valign="top">
		<?php 
			/*********************** MYACCOUNT MENU ****************************
			This code shows my account menu only to logged in users. 
			Copy this code till END and place it in a new html or php where
			you want to show myaccount options. This is only visible to logged in users
			*******************************************************************/
			if (isset($_SESSION['user_id']))
			{
				include 'rawmatmenu.php';
			} 
		/*******************************END**************************/
		?>
		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td valign="top"  style="text-align: left;"><p>&nbsp;</p>
		<h3 class="titlehdr">ระบบ Raw Material</h3>
		<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
 		<tr>
 		<td width="40%">การใช้งานระบบวัตถุดิบ<br>
		ระบบ RawMat ประกอบด้วย
		    <ul>
		    <li>รายการ RawMat</li>
		    <li>เพิ่ม รายการ RawMat</li>
		    <li>ลบ รายการ RawMat</li>
		    <li>เบิกใช้ RawMat</li>
		    <li>นำเข้า RawMat</li>
		    <li>RawMat ถึงกำหนดซื้อ</li>
		    <li>Supplier</li>
		    </ul>
		</td>
		</tr>
		</table>
		</td>
		<td width="160" valign="top">
		</td>
	</tr>
</table>
<!--end menu-->
</body></html>
