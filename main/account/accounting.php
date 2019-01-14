<?php 
include '../../config/dbc.php';
page_protect();

$sql_create = "CREATE TABLE IF NOT EXISTS `daily_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `ac_no_i` int(11) NOT NULL,
  `ac_no_o` int(11) NOT NULL,
  `detail` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `inv_num` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` float(9,2) NOT NULL DEFAULT '0.00',
  `type` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bors` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `recordby` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

mysqli_query($link,$sql_create);

$title = "::บัญชีและการเงิน::";
include '../../main/header.php';
include '../../libs/popup.php';
include '../../main/bodyheader.php';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="160" valign="top">
		<?php 
			if (isset($_SESSION['user_id']))
			{
				include 'accountmenu.php';
			} 
		?>
		</td>
		<td valign="top"  style="text-align: left;"><h3 class="titlehdr">ระบบบัญชีและการเงิน</h3>
		<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
 		<tr>
 		<td width="40%"><div class="mypage1">การใช้งานระบบบัญชี<br>
		ระบบบัญชีประกอบด้วย
		    <ul>
		    <li>ลงบัญชีท้่วไป</li>
		    <li>บัญชีรายวันท้่วไป</li>
		    <li>บัญชีเงินสด ประจำวัน</li>
		    <li>บัญชีเงินสด ประจำเดือน</li>
		    <li>บัญชีขาย ประจำวัน</li>
		    <li>บัญชีขาย ประจำเดือน</li>
		    <li>บัญชีแยกประเภทรายจ่ายประจำเดือน</li>
		    <li>บัญชีกำไรขาดทุนต่อเดือน</li>
		    <li>บัญชีกำไรขาดคงเหลือ</li>
		    <li>งบดุล</li>
		    </ul>
		ในการบันทึกบัญชี ให้ทำการบันทึกใน "ลงบัญชีทั่วไป"
		</div></td>
		<td bgcolor="#9999ff"><font color="#000000"> <p>
		<big>การลงบัญชี ให้ลงเฉพาะส่วนที่ <big><u>ไม่เกี่ยวข้องกับการซื้อขายยาและเวชภัณฑ์</u></big> เช่น จ่ายค่าน้ำค่าไฟ จ่ายเงินเดือน นำเงินสดมาลงทุน ฝากถอนเงินธนาคาร</big>
		</p></font></td>
		</tr>
		</table>
		</td>
		<td width="160" valign="top"><div class="mypage1"><h6 class="titlehdr2" align="center">ประเภทบัญชี</h6>
        <?php
        if (isset($_SESSION['user_id']))
        {
            date_default_timezone_set('Asia/Bangkok');
                $sd = date("d");
                $sm = date("m");
                $sy = date("Y");
                $_SESSION['sd'] = $sd;
                $_SESSION['sm'] = $sm;
                $_SESSION['sy'] = $sy;
            include 'actmenu.php';
        } 
        ?>
		</div></td>
	</tr>
</table>
</body></html>
