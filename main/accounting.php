<?php 
include '../login/dbc.php';
page_protect();
?>
<!DOCTYPE html>
<html>
<head>
<title>บัญชีและการเงิน</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
  <?php include '../libs/popup.php';?>
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
				include 'accountmenu.php';
			} 
		/*******************************END**************************/
		?>
		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td valign="top"  style="text-align: left;"><p>&nbsp;</p>
		<h3 class="titlehdr">ระบบบัญชีและการเงิน</h3>
		<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
 		<tr>
 		<td width="40%">การใช้งานระบบบัญชี<br>
		ระบบบัญชีประกอบด้วย
		    <ul>
		    <li>บัญชีรายวันท้่วไป</li>
		    <li>บัญชีเงินสด ประจำวัน</li>
		    <li>บัญชีเงินสด ประจำเดือน</li>
		    <li>บัญชีขาย ประจำวัน</li>
		    <li>บัญชีขาย ประจำเดือน</li>
		    <li>งบดุล</li>
		    </ul>
		ในการบันทึกบัญชี ให้ทำการบันทึกใน "ลงบัญชีทั่วไป"
		</td>
		<td bgcolor="#B6B6B4"><font color="#990012"> <p>
		<big>การลงบัญชี ให้ลงเฉพาะส่วนที่ไม่เกี่ยวข้องกับการซื้อขายยาและเวชภัณฑ์ เช่น จ่ายค่าน้ำค่าไฟ จ่ายเงินเดือน นำเงินสดมาลงทุน ฝากถอนเงินธนาคาร</big>
		</p></font></td>
		</tr>
		</table>
		</td>
		<td width="160" valign="top">
			<div class="mypage1">
				<h6 class="titlehdr2" align="center">ประเภทบัญชี</h6>
				<?php 
				/*********************** MYACCOUNT MENU ****************************
				This code shows my account menu only to logged in users. 
				Copy this code till END and place it in a new html or php where
				you want to show myaccount options. This is only visible to logged in users
				*******************************************************************/
				if (isset($_SESSION['user_id']))
				{
					include 'actmenu.php';
					date_default_timezone_set('Asia/Bangkok');
					    $sd = date("d");
					    $sm = date("m");
					    $sy = date("Y");
					    $_SESSION['sd'] = $sd;
					    $_SESSION['sm'] = $sm;
					    $_SESSION['sy'] = $sy;
					
				} 
				/*******************************END**************************/
				?>
			</div>	
		</td>
	</tr>
</table>
<!--end menu-->
</body></html>