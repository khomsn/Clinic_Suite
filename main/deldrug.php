<?php 
include '../login/dbc.php';
page_protect();

$filter = mysqli_query($link, "select * from drug_id ");		
	while ($row = mysqli_fetch_array($filter))
	{
		if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
	}	
$filter = mysqli_query($link, "select * from drug_id WHERE track = '0' AND volume='0' AND min_limit<'0' ORDER BY `dgname` ASC ");

if ($_POST['todo'] == 'กรอง' ) 
{
	if($_POST['type'] != '' AND $_POST['group'] !='' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE typen='$_POST[type]' AND  `groupn` ='$_POST[group]' AND  track = '0' AND volume='0' AND min_limit<'0' ORDER BY `dgname` ASC");	
	}	
	if($_POST['type'] != '' AND $_POST['group'] =='' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE typen='$_POST[type]' AND  track = '0' AND volume='0' AND min_limit<'0' ORDER BY `dgname` ASC ");	
	}	
	if($_POST['group'] !=''  AND  $_POST['type'] == '' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE  `groupn` ='$_POST[group]' AND  track = '0' AND volume='0' AND min_limit<'0' ORDER BY `dgname` ASC");	
	}	

}

if($_POST['register'] == 'ลบข้อมูล') 
{ 
	$id = $_POST['drugid'];
	$acno = mysqli_query($link, "SELECT * FROM drug_id WHERE id = $id");
	while ($row = mysqli_fetch_array($acno))
	{
		$dacno = $row['ac_no'];
		$sety = $row['seti'];
		$vol = $row['volume'];
		// info for loging
		$dname = $row['dname'];
		$dgname = $row['dgname'];
		$size = $row['size'];
	}
	echo $vol;
	if($vol > '0')
	{

	header("Location: del_error.php");  
	}

	if ($vol =='0')
	{

	$sql_del = "DELETE FROM drug_id WHERE id = $id";

	// Now delete drug information from drug_id table
	mysqli_query($link, $sql_del) or die("Deletion Failed:" . mysqli_error($link));

	if($sety == 1)
	{
	$setid = "set_drug_".$id;
	$sql_drop ="DROP TABLE `$setid`" ;
	mysqli_query($link, $sql_drop) or die("Insertion Failed:" . mysqli_error($link));
	}

	$tid = "drug_".$id;
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
	
	//loging del item
	$sql_insert = "INSERT into `deleted_drug` 
			(`id`,`dname`,`dgname`, `size`, `ac_no`,`bystid` ) 
			VALUES 
			('$id','$dname','$dgname','$size','$dacno','$_SESSION[staff_id]')";
	// Now insert 
	mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
	// go on to other step
	
	header("Location: deldrug.php");  

	} 
}
?>

<!DOCTYPE html>
<html>
<head>
<title>ลบรายการยาและผลิตภัณฑ์</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<link rel="stylesheet" href="../public/css/styles.css">
<link rel="stylesheet" href="../public/css/table_alt_color.css">
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
  <tr><td width="250" valign="top"><div class="pos_l_fix">
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
		?></div>		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
<!--menu-->
			<h3 class="titlehdr">ลบ ทะเบียนยา และ ผลิตภัณฑ์</h3>
			<form method="post" action="deldrug.php" name="regForm" id="regForm">
				<table style="text-align: center;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="width: 18px;"></td>
						<td style="vertical-align: middle; ">
						<div style="text-align: center;">
						<?php	
								echo "<table class='TFtable' border='1' style='text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);'>";
								echo "<tr> <th>เลือก</th><th>ชื่อ</th> <th>ชื่อสามัญ</th><th>ขนาด</th><th> คงคลัง</th></tr>";
								while($row = mysqli_fetch_array($filter))
								 {
										// Print out the contents of each row into a table
										echo "<tr><th>";
						?>							
										<input type="radio" name="drugid" value="<?php	echo $row['id']; ?>" />
						<?php
										echo "</th><th>"; 
										echo $row['dname'];
										echo "</th><th>"; 
										echo $row['dgname'];
										echo "</th><th>"; 
										echo $row['size'];
										echo "</th><th>"; 
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
						<div style="text-align: center;"><input name="register" value="ลบข้อมูล" type="submit"></div>
					</td>
					</tr>
				</tbody>
				</table>
				<br>
<!--menu end-->
		</td>
		<td style="width:160px;vertical-align: top;"">
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
							</select>&nbsp; &nbsp; &nbsp; &nbsp; 
							<br>
							<input type='submit' name='todo' value='กรอง' >&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
				</div>	
			</form>
		</td>
	</tr>
</table>
<!--end menu-->
</body></html>
