<?php 
include '../login/dbc.php';
page_protect();

if($_POST['register'] == 'ดูข้อมูล') 
{ 

// pass drug-id to other page
$_SESSION['drugid'] = $_POST['drugid'];
// go on to other step
header("Location: updatedrugid.php");  

} 

$filter = mysqli_query($link, "select * from drug_id ");		
	while ($row = mysqli_fetch_array($filter))
	{
		if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
	}	
$filter = mysqli_query($link, "select * from drug_id  WHERE seti != 1 ORDER BY `dgname` ASC");

if ($_POST['todo'] == 'กรอง' ) 
{
	if($_POST['type'] != '' AND $_POST['group'] !='' )
	{
		$filter = mysqli_query($link, "select * from drug_id  WHERE seti != 1 AND typen='$_POST[type]' AND  `groupn` ='$_POST[group]' ORDER BY `dgname` ASC ");	
	}	
	if($_POST['type'] != '' AND $_POST['group'] =='' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE seti != 1 AND typen='$_POST[type]' ORDER BY `dgname` ASC ");	
	}	
	if($_POST['group'] !=''  AND  $_POST['type'] == '' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE  seti != 1 AND `groupn` ='$_POST[group]' ORDER BY `dgname` ASC ");	
	}	
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
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
<!--menu-->
			<h3 class="titlehdr">รายการ ยา และ ผลิตภัณฑ์</h3>
			<form method="post" action="druglist.php" name="regForm" id="regForm">
				<table style="text-align: center;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="width: 18px;"></td>
						<td style="vertical-align: middle; ">
						<div style="text-align: center;">
						<?php	
								echo "<table border='1' style='text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);'>";
								echo "<tr><th>เลือก</th>";
								if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0)
								{
								echo "<th>id</th>";
								}
								echo "<th>ชื่อ</th><th>ชื่อสามัญ</th><th>ขนาด</th><th style='background-color:#A9E2F3'>จำนวน</th><th>RSVol</th><th>ราคา</th></tr>";
								while($row = mysqli_fetch_array($filter))
								 {
										// Print out the contents of each row into a table
										echo "<tr><th>";
										if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0)
										{
										echo "<input type='radio' name='drugid' value='".$row['id']."' />";
										echo "</th><th>";
										echo $row['id'];
										}
										echo "</th><th>"; 
										echo $row['dname'];
										echo "</th><th>"; 
										echo $row['dgname'];
										echo "</th><th >"; 
										echo $row['size'];
										echo "</th><th style='text-align: right; background-color:#A9E2F3;'>"; 
										echo $row['volume'];
										echo "</th><th style='text-align: center;'>"; 
										echo $row['volreserve'];
										echo "</th><th style='text-align: right;' >"; 
										echo $row['sellprice'];
										echo "</th></tr>";
								} 
								echo "</table>";
						?>
							<br>
							</div>
						</td>
						<td>
					</td>
					</tr>
					<tr>
					<td>&nbsp;</td>
					<td>
						<div style="text-align: center;"><?php if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0){?><input name="register" value="ดูข้อมูล" type="submit"><?php }?></div>
					</td>
					</tr>
				</tbody>
				</table>
				<br>
						<div class="pos_r_fix" style="text-align: right;">
						<?php	
							$dtype = mysqli_query($link, "SELECT name FROM drug_type");
							$dgroup = mysqli_query($link, "SELECT name FROM drug_group");
						?>
							ประเภท&nbsp;
							<select name="type">
								<option value="" selected></option>
								<?php while($trow = mysqli_fetch_array($dtype))
								{
									echo "<option value=\"";
									echo $trow['name'];
									echo "\">";
									echo $trow['name']."</option>";
								}
								?>
							</select>
							&nbsp; &nbsp; &nbsp; 
							<br>
							กลุ่ม
							<select name="group">
								<option value="" selected></option>
								<?php while($grow = mysqli_fetch_array($dgroup))
								{
									echo "<option value=\"";
									echo $grow['name'];
									echo "\">";
									echo $grow['name']."</option>";
								}
								?>
							</select>
							<br>
							<input type="submit" name='todo' value='กรอง' ></div>	
			</form>
<!--menu end-->
		</td>
		<td width="160"></td>
	</tr>
</table>
<!--end menu-->
</body></html>