<?php 
include '../login/dbc.php';
page_protect();

if($_POST['register'] == 'รายงาน บจ 9') 
{ 

// pass drug-id to other page
$_SESSION['drugid'] = $_POST['drugid'];
// go on to other step
//header("Location: ../docform/bj9.php");  
 ?>
 <script>
 window.open(" ../docform/bj9.php");
 </script>
 <?php 
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
				include 'reportmenu.php';
			} 
		/*******************************END**************************/
		?></div>		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
<!--menu-->
			<h3 class="titlehdr">รายการ ยาพิเศษต้องรายงานการใช้</h3>
			<form method="post" action="drugtracklist.php" name="regForm" id="regForm">
				<table style="text-align: center; width: 703px; height: 413px;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="width: 18px;"></td>
						<td style="width: 646px; vertical-align: middle; ">
						<div style="text-align: center;">
						<?php	
							$dtype = mysqli_query($link, "SELECT * FROM drug_id WHERE track = '1' ");
								echo "<table border='1' style='text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);'>";
								echo "<tr> <th>เลือก</th><th>ชื่อ</th><th>ชื่อสามัญ</th><th>ขนาด</th><th>จำนวน</th></tr>";
								while($row = mysqli_fetch_array($dtype))
								 {
										// Print out the contents of each row into a table
										echo "<tr><th>";
						?>							
										<input type="radio" name="drugid" value="<?php	echo $row['id']; ?>" />
						<?php
										echo "</th><th width=150>"; 
										echo $row['dname'];
										echo "</th><th width=150>"; 
										echo $row['dgname'];
										echo "</th><th width=50>"; 
										echo $row['size'];
										echo "</th><th width=50>"; 
										echo $row['volume'];
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
						<div style="text-align: center;"><a href="drugtracklist8.php">รายงาน บจ 8</a> 
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="register" value="รายงาน บจ 9" type="submit"></div>
					</td>
					</tr>
				</tbody>
				</table>
				<br>
			</form>
<!--menu end-->
		</td>
    <td width=130px>
    <?php include 'reportrmenu.php';?>
    </td>
	</tr>
</table>
<!--end menu-->
</body></html>
