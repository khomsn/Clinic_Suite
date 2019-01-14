<?php 
include '../../config/dbc.php';
page_protect();

$sql="

CREATE TABLE IF NOT EXISTS `drug_id` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
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
  `staffcanorder` tinyint(1) NOT NULL DEFAULT '0',
  `stcp` TINYINT NOT NULL DEFAULT '0',
  `location` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

mysqli_query($link, $sql);

if(empty($_SESSION['acstrdate']))
{
    $gddmin = mysqli_query($link, "SELECT `date` FROM `daily_account` WHERE id=1 ");
    $gddate = mysqli_fetch_array($gddmin);
    $gdm = $gddate[0];
    $gdm = date_create($gdm);
    $_SESSION['acstrdate'] = date_format($gdm, 'Y-m-d');
}

$title = "::ห้องยา::";
include '../../main/header.php';
include '../../main/bodyheader.php';

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
  <tr><td style="text-align: center; width: 160px; "></td>
      <td valign="top"  style="text-align: left;"><p>&nbsp;</p>
      <h3 class="titlehdr">ระบบห้องยา</h3>
      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
      <tr>
      <td width="40%"><div class="forms">การใช้งานระบบห้องยา<br>
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
	  </ul></div>
      </td>
      <td></td>
      </tr>
      </table>
      </td>
      <td width="160" valign="top">
      </td>
  </tr>
</table>
<!--end menu-->
</body></html>
