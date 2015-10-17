<?php 
include '../login/dbc.php';
page_protect();

if($_POST['register'] == 'ดูข้อมูล') 
{ 

// pass drug-id to other page
$_SESSION['rawmatid'] = $_POST['rawmatid'];
// go on to other step
header("Location: rawmatupdate.php");  

} 

$filter = mysqli_query($link, "select * from rawmat ");		
	while ($row = mysqli_fetch_array($filter))
	{
		if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
	}	
$filter = mysqli_query($link, "select * from rawmat  ORDER BY `rmtype` ASC ,`rawcode` ASC ,`rawname` ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>ห้องคลังวัตถุดิบ</title>
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
				include 'rawmatmenu.php';
			} 
		/*******************************END**************************/
		?></div>
		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
<!--menu-->
			<h3 class="titlehdr">รายการ RawMat</h3>
			<form method="post" action="rawmatlist.php" name="regForm" id="regForm">
				<table style="text-align: center;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="width: 18px;"></td>
						<td style="vertical-align: middle; ">
						<div style="text-align: center;">
						<?php	
								echo "<table border='1' style='text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);'>";
								echo "<tr> <th>เลือก</th><th>Code</th> <th>ชื่อ</th><th>ขนาด</th><th>unit</th><th>Volume</th><th>Type</th></tr>";
								while($row = mysqli_fetch_array($filter))
								 {
										// Print out the contents of each row into a table
										echo "<tr><th>";
										if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0)
										{
										echo "<input type='radio' name='rawmatid' value='".$row['id']."' />";
										echo $row['id'];
										}
										echo "</th><th>"; 
										echo $row['rawcode'];
										echo "</th><th>"; 
										echo $row['rawname'];
										echo "</th><th >"; 
										echo $row['size'];
										echo "</th><th>"; 
										echo $row['sunit'];
										echo "</th><th>"; 
										echo $row['volume'];
								echo "</th><th>"; 
								echo $row['rmtype'];
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
			</form>
<!--menu end-->
		</td>
		<td width="160"></td>
	</tr>
</table>
<!--end menu-->
</body></html>