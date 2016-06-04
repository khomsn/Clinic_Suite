<?php 
include '../login/dbc.php';
page_protect();

$filter = mysqli_query($link, "select * from rawmat ");		
	while ($row = mysqli_fetch_array($filter))
	{
		if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
	}	
$filter = mysqli_query($link, "select * from rawmat  WHERE `volume`<=0 ORDER BY `rmtype` ASC ,`rawcode` ASC ,`rawname` ASC");

if($_POST['register'] == 'ลบข้อมูล') 
{ 
	$id = $_POST['rawid'];
	$acno = mysqli_query($link, "SELECT * FROM rawmat WHERE id = $id");
	while ($row = mysqli_fetch_array($acno))
	{
		$dacno = $row['ac_no'];
		$vol = $row['volume'];
		// info for loging
		$rawcode = $row['rawcode'];
		$rawname = $row['rawname'];
		$size = $row['size'];
	}
	echo $vol;
	if($vol > '0')
	{

	header("Location: del_error.php");  
	}

	if ($vol =='0')
	{

	$sql_del = "DELETE FROM rawmat WHERE id = $id";

	// Now delete drug information from rawmat table
	mysqli_query($link, $sql_del) or die("Deletion Failed:" . mysqli_error($link));

	$tid = "rawmat_".$id;
	$ftid = mysqli_query($link, "select * from $tid");
	while($row = mysqli_fetch_array($ftid))
	{
	 if($row['price']!=0)
	 {
	  goto NextStep;
	 }
	}
	//if have price don't drop table *use in account system ในการคำนวน กำไร
	$sql_drop ="DROP TABLE `$tid`" ;
	mysqli_query($link, $sql_drop) or die("Insertion Failed:" . mysqli_error($link));
	// Delete Ac No
	NextStep:
	$sql_del = "DELETE FROM acnumber WHERE ac_no = $dacno";
	// Now remove drug information table
	mysqli_query($link, $sql_del) or die("Insertion Failed:" . mysqli_error($link));
	
	header("Location: rawmatdel.php");  
	} 
}
?>

<!DOCTYPE html>
<html>
<head>
<title>ลบรายการ RawMat</title>
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
<body style="background-image: url(../image/new.jpg);" alink="#000088" link="#006600" vlink="#660000">
<?php
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr><td width="250" valign="top"><div class="pos_l_fix">
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
		?></div>		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
<!--menu-->
			<h3 class="titlehdr">ลบ ทะเบียน RawMat</h3>
			<form method="post" action="rawmatdel.php" name="regForm" id="regForm">
				<table style="text-align: center;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="width: 18px;"></td>
						<td style="vertical-align: middle; ">
						<div style="text-align: center;">
						<?php	
								echo "<table border='1' style='text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);'>";
								echo "<tr><th>เลือก</th><th>Code</th><th>ชื่อ</th><th>ขนาด</th><th>คงคลัง</th><th>Type</th></tr>";
								while($row = mysqli_fetch_array($filter))
								 {
										// Print out the contents of each row into a table
										echo "<tr><th>";
						?>							
										<input type="radio" name="rawid" value="<?php	echo $row['id']; ?>" />
						<?php
										echo "</th><th>"; 
										echo $row['rawcode'];
										echo "</th><th>"; 
										echo $row['rawname'];
										echo "</th><th>"; 
										echo $row['size'];
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
						<td style="width: 11px;"></td>
					</tr>
					<tr>
					<td>&nbsp;</td>
					<td><br>
						<br>
						<div style="text-align: center;"><input name="register" value="ลบข้อมูล" type="submit"></div>
					</td>
					</tr>
				</tbody>
				</table>
		</form>
		</td>
	</tr>
</table>
<!--end menu-->
</body></html>
