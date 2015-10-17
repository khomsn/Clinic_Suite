<?php 
include '../login/dbc.php';
page_protect();

if($_POST['reset']=="Reset")
{
  		mysqli_query($link, "UPDATE drug_id SET
			`volreserve` = '0'
			") or die(mysqli_error($link));
}
?>

<!DOCTYPE html>
<html>
<head>
<title>รายการยาและผลิตภัณฑ์</title>
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
  <tr><td colspan="3" >&nbsp;</td></tr>
  <tr><td width="160" valign="top"><div class="pos_l_fix">
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
		?></div>
		</td>
		<td>
<!--menu-->
			<h3 class="titlehdr">Reset Reserve Volume of Drugs to 0</h3>
			<form method="post" action="drrsrvreset.php" name="regForm" id="regForm">
			  <input type="submit" name=reset value="Reset">
			</form>
<!--menu end-->
		</td>
		<td width="160"></td>
	</tr>
</table>
<!--end menu-->
</body></html>