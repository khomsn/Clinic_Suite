<?php 
include '../login/dbc.php';
page_protect();
?>


<!DOCTYPE html>
<html>
<head>
<title>ลบรายการ ผิดพลาด</title>
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
<body style="background-image: url(../image/new.jpg);">
<?php
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr><td width="250" valign="top">
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
			  <p>&nbsp; </p>
			  <p>&nbsp;</p>
			  <p>&nbsp;</p>
			  <p>&nbsp;</p>
		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
<!--menu-->
			<h3 class="titlehdr">ผิดพลาด</h3> <br>
			<h3 class="titlehdr">ไม่สามารถเอาออกจาก บัญชีได้ เพราะ ยังมียอดคงคลัง</h3> <br>
			<h3 class="titlehdr">ไม่สามารถตัดยอดได้  เพราะมียอดคงคลังไม่พอ</h3> <br>
		</td>
</table>
<!--end menu-->
</body></html>