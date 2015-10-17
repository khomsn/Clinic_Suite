<?php 
include '../login/dbc.php';
page_protect();

if($_POST['register'] == 'ดูข้อมูล') 
{ 

// pass drug-id to other page
$_SESSION['spid'] = $_POST['spid'];
// go on to other step
header("Location: updatesp.php");  

} 
?>

<!DOCTYPE html>
<html>
<head>
<title>Supplier Lists</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
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
  <tr><td width="200" valign="top"><div class="pos_l_fix">
		<?php 
			/*********************** MYACCOUNT MENU ****************************
			This code shows my account menu only to logged in users. 
			Copy this code till END and place it in a new html or php where
			you want to show myaccount options. This is only visible to logged in users
			*******************************************************************/
			if (isset($_SESSION['user_id']))
			{
				include 'spmenu.php';
			} 
		/*******************************END**************************/
		?></div>
		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
<!--menu-->
			<h3 class="titlehdr">ผู้จำหน่าย ยา ผลิตภัณฑ์ และวัตถุดิบ</h3>
			<form method="post" action="listsp.php" name="regForm" id="regForm">
				<table style="text-align: center; width: 703px; height: 413px;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="width: 18px;"></td>
						<td style="width: 646px; vertical-align: middle; ">
						<div style="text-align: center;">
						<?php	
							$dtype = mysqli_query($link, "SELECT * FROM supplier ORDER BY `name` ASC  ");
								echo "<table border='1' style='text-align: center; margin-left: auto; margin-right: auto;background-color: rgb(255, 255, 204);'>";
								echo "<tr> <th>เลือก</th><th>ชื่อ</th><th>โทรศัพท์</th><th>ตัวแทน</th><th>เบอร์ติดต่อ</th></tr>";
								while($row = mysqli_fetch_array($dtype))
								 {
										// Print out the contents of each row into a table
										echo "<tr><th>";
						?>							
										<input type="radio" name="spid" value="<?php	echo $row['id']; ?>" />
						<?php
										echo "</th><th width=150>"; 
										echo $row['name'];
										echo "</th><th width=80>"; 
										echo $row['tel'];
										echo "</th><th width=150>"; 
										echo $row['agent'];
										echo "</th><th width=80>"; 
										echo $row['mobile'];
										echo "</th></tr>";
								} 
								echo "</table>";
						?>
							<br>
							</div>
						</td>
						<td style="width: 11px;"></td>
					</tr>
					<tr>
					<td>&nbsp;</td>
					<td><br>
						<br>
						<div style="text-align: center;"><input name="register" value="ดูข้อมูล" type="submit"></div>
					</td>
					</tr>
				</tbody>
				</table>
				<br>
			</form>
<!--menu end-->
		</td>
		<td width="160"></td>
	</tr>
</table>
<!--end menu-->
</body></html>