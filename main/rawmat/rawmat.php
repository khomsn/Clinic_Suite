<?php 
include '../../config/dbc.php';
page_protect();
$sql = "

CREATE TABLE IF NOT EXISTS `rawmat` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `rawcode` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rawname` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sunit` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lowlimit` smallint(6) NOT NULL DEFAULT '0',
  `volume` smallint(6) NOT NULL DEFAULT '0',
  `ac_no` int(11) NOT NULL,
  `rmfpd` tinyint(1) NOT NULL DEFAULT '0',
  `rmtype` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `location` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

mysqli_query($link, $sql);

$title = "::ห้องคลังวัตถุดิบ::";
include '../../main/header.php';
include '../../main/bodyheader.php';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
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
		</td><td valign="top"  style="text-align: left;"><p>&nbsp;</p>
		<h3 class="titlehdr">ระบบ Raw Material</h3>
		<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
 		<tr><td width="40%"><div class="forms">การใช้งานระบบวัตถุดิบ<br>
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
		</div></td><td></td></tr>
		</table>
		</td>
		<td width="160" valign="top">
		</td>
	</tr>
</table>
<!--end menu-->
</body></html>
