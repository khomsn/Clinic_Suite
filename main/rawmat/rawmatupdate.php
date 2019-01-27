<?php 
include '../../config/dbc.php';
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
			");
// go on to other step
header("Location: rawmatlist.php");  

}
$title = "::ห้องคลังวัตถุดิบ::";
include '../../main/header.php';
include '../../libs/popup.php';
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
		</td><td>
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
?> รูปแบบการสั่งซื้อ:<select name=unit>
<?php
$dpackagetype = mysqli_query($link, "SELECT * FROM packagetype");
// keeps getting the next row until there are no more to get
while($row = mysqli_fetch_array($dpackagetype))
{
    echo "<option value='".$row['name']."'";
    if($row_settings['sunit']==$row['name']) echo "selected";
    echo ">".$row['name']."</option>";
}
?>
</select>
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
