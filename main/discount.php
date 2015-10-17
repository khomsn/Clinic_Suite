<?php 
include '../login/dbc.php';
page_protect();
$id = $_SESSION['patcash'];

if($_POST['add'] == 'เพิ่ม') 
{ 
	// assign insertion pattern
	$dgroup = mysqli_query($link, "SELECT * FROM discount WHERE ctmid = $id ");
	$tyes = 0;
	while($row = mysqli_fetch_array($dgroup))
	{
		if($row['ctmid']==$id) $tyes = 1;
	}	
	// keeps getting the next row until there are no more to get
	if($tyes == 0)
	{
	$sql_insert = "INSERT into `discount`
  			(`ctmid`,`percent`)
		    VALUES
			('$id', '$_POST[percent]' )";

	// Now insert into "discount" table
	mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
	}
	else
	{
		mysqli_query($link, " UPDATE `discount` SET
											`percent` = '$_POST[percent]'
							WHERE `ctmid` = '$id' ") or die(mysqli_error($link));
	}
// go on to other step
header("Location: discount.php");  
}


if($_POST['del'] == 'ลบ' ) 
{ 

	$sql_del = "DELETE FROM discount WHERE ctmid = $id";

	// Now insert into "discount" table
	mysqli_query($link, $sql_del) or die("Insertion Failed:" . mysqli_error($link));

// go on to other step
header("Location: discount.php");  
}
?>

<!DOCTYPE html>
<html>
<head>
<title>กำหนดส่วนลด</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<link rel="stylesheet" href="../public/css/styles.css">
</head>

<body>
<div id="content">
<table width="350px" border="0" cellspacing="0" cellpadding="5" class="main">
	<tr>
		<td><form name="groupofd" method="post" action="discount.php">
				<table border='1' style='text-align: center; margin-left: auto; margin-right: auto;' >
				<tr><th>ชื่อ</th><th>%</th><th>เพิ่ม</th><th>ลบ</th></tr>
				<tr><th width=150><?php 
				$dgroup = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE id = $id ");
						// keeps getting the next row until there are no more to get
				while($row = mysqli_fetch_array($dgroup))
				{
					echo $row['prefix'].'&nbsp;'.$row['fname'].'&nbsp;&nbsp;'.$row['lname'];
				}	
				?>	
				</th><th><input min=0 max=100 step=1 type="number" class="typenumber" name="percent" value="<?php 
				$dct = mysqli_query($link, "SELECT * FROM `discount` WHERE `ctmid` = '$id' ");
				while($row1 = mysqli_fetch_array($dct))
				{
					echo $row1['percent'];
				}				
				?>"></th>
				<th><input type="submit" name="add" value="เพิ่ม"></th><th><input type="submit" name="del" value="ลบ"></th></tr>
				</table>
			</form>
		</td>
	</tr>
</table>
<!--end menu-->
</div>
</body>
</html>