<?php 
include '../login/dbc.php';
page_protect();

$id = $_SESSION['rawmatid'];

$stock_in = mysqli_query($link, "select * from rawmat where id='$id' ");

if($_POST['register'] == 'แก้ไข') 
{ 

mysqli_query($link, "UPDATE rawmat SET
			`lowlimit` = '$_POST[lowlimit]',
			`sunit` = '$_POST[unit]',
			`rmfpd` = '$_POST[rmpd]',
			`rmtype` = '$_POST[rwtype]'
			WHERE id='$id'
			") or die(mysqli_error($link));
// go on to other step
header("Location: rawmatlist.php");  

} 
?>

<!DOCTYPE html>
<html>
<head>
<title>ห้องคลังวัตถุดิบ</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
<?php include '../libs/popup.php'; ?>
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
		<td>
<!--menu-->
			<h3 class="titlehdr">แก้ไข ทะเบียน RawMat</h3>
			<form method="post" action="rawmatupdate.php" name="regForm" id="regForm">
				<table style="text-align: left; width: 703px; height: 413px;" border="0" cellpadding="2" cellspacing="2"  class="forms">
<tbody>
<tr>
<td style="width: 18px;"></td>
<td style="width: 646px; vertical-align: middle;">
<div style="text-align: center;">
<?php

while ($row_settings = mysqli_fetch_array($stock_in))
{
   echo $row_settings['rawcode'];
   echo "<br>";
   echo $row_settings['rawname'];
   echo "<br>";
   echo "ขนาด: &nbsp";
   echo $row_settings['size'];
?> Unit <select name=unit><option <?php if($row_settings['sunit']=='ml') echo "selected";?> value="ml">ml</option><option <?php if($row_settings['sunit']=='gram') echo "selected";?> value="gram">gram</option><option <?php if($row_settings['sunit']=='กระปุก') echo "selected";?> value="กระปุก">กระปุก</option><option <?php if($row_settings['sunit']=='กล่อง') echo "selected";?> value="กล่อง">กล่อง</option><option <?php if($row_settings['sunit']=='ซอง') echo "selected";?> value="ซอง">ซอง</option><option <?php if($row_settings['sunit']=='Set') echo "selected";?> value="Set">Set</option><option <?php if($row_settings['sunit']=='ขวด') echo "selected";?> value="ขวด">ขวด</option><option <?php if($row_settings['sunit']=='ลัง') echo "selected";?> value="ลัง">ลัง</option></select>
      <hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
      จำนวนคงคลังขั้นต่ำ
      <input class="typenumber" type=number maxlength="4" size="4" name="lowlimit" value="<?php
   echo $row_settings['lowlimit'];
   ?>"><br>
</div><hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
<div style="text-align: center;">ส่วนประกอบของผลิตภัณฑ์:<input type="radio" tabindex="5" name="rmpd"  <?php if($row_settings['rmfpd']==1) echo "checked ";?>class="required" value="1">Yes<input type="radio" tabindex="5" name="rmpd"  <?php if($row_settings['rmfpd']==0) echo "checked ";?>class="required" value="0">No<br>ประเภท:<input type="radio" tabindex="6" name="rwtype"  <?php if($row_settings['rmtype']=='lab') echo "checked";?> class="required" value="lab">Lab<input type="radio" tabindex="6" name="rwtype"  <?php if($row_settings['rmtype']=='ความงาม') echo "checked";?> class="required" value="ความงาม">ความงาม<input type="radio" tabindex="6" name="rwtype"  <?php if($row_settings['rmtype']=='other') echo "checked";?> class="required" value="other">อื่นๆ
</div>
<?php }   ?>						</td>
						<td style="width: 11px;"></td>
					</tr>
					<tr>
					<td></td>
					<td><br>
						<br>
						<div style="text-align: center;"><input name="register" value="แก้ไข" type="submit"></div>
					</td>
					</tr>
				</tbody>
				</table>
				<br>
			</form>
<!--menu end-->
		</td>
		<td width="60"></td>
	</tr>
</table>
<!--end menu-->
</body></html>
