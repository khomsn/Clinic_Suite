<?php 
include '../login/dbc.php';
page_protect();


$id = $_SESSION['patcash'];

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");

if($_POST['register'] == 'บันทึก') 
{ 
    if($_POST['medc'])
    {
      $medcert = $_POST['medcert'];
    }
    
    mysqli_query($link, "UPDATE `tmp_$id` SET  `medcert` = '$medcert' ") or die(mysqli_error($link));
 
    $result = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE id='$id' ");
	while($row = mysqli_fetch_array($result))
	{
      // Insert Patient to the list to see doctor.
	      $ID = $row['id'];	$prefix= $row['prefix'];	$F_Name = $row['fname'];	$L_Name = $row['lname'];
	}
    $sql_insert = "INSERT INTO `pt_to_doc` (`ID`, `prefix`, `F_Name`, `L_Name`) VALUES ('$ID', '$prefix', '$F_Name', '$L_Name')";
    // Now insert Patient to "patient_id" table
    mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));

    // Now Delete Patient from "pt_to_drug" table
    mysqli_query($link, "DELETE FROM pt_to_drug WHERE id = '$id' ") or die(mysqli_error($link));
  // go on to other step
  if($_SESSION['sflc']!="0") header("Location: p_to_doc.php"); 
  else header("Location: ptodrug.php");  
} 
?>

<!DOCTYPE html>
<html>
<head>
<title>ประวัติ ตรวจร่างกาย</title>
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
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr><td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
<!--menu-->
			<h3 class="titlehdr">ขอใบรับรองแพทย์</h3>
			<form method="post" action="remedcert.php" name="regForm" id="regForm">
				<table style="text-align: left; width: 703px; height: 413px;" border="1" cellpadding="2" cellspacing="2"  class="forms">
				<tbody>
					<tr>
						<td style="width: 646px; vertical-align: middle;">
							<div style="text-align: center;">
							<h3>ชื่อ: &nbsp; 
							<?php
					while ($row_settings = mysqli_fetch_array($ptin))
					{
						echo $row_settings['fname'];
						echo "&nbsp; &nbsp; &nbsp;"; 
						echo $row_settings['lname'];
						echo "&nbsp; &nbsp; &nbsp;เพศ";
						echo $row_settings['gender'];
						$staff = $row_settings['staff'];
						$date1=date_create(date("Y-m-d"));
						$date2=date_create($row_settings['birthday']);
						$diff=date_diff($date2,$date1);
						echo "&nbsp; &nbsp;อายุ&nbsp; ";
						echo $diff->format("%Y ปี %m เดือน %d วัน");
						echo "</h3>";
					}				
							?>
							<br>
							</div>
							<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
							<br>
							<div style="text-align:center;"> <input type="checkbox" name="medc" value="1"> ใบรับรองแพทย์ <input type=radio name=medcert value=1>ตรวจโรคสมัครงาน <input type=radio name=medcert value=2 checked>ยืนยันตรวจจริง
							<hr style="width: 80%; height: 2px;"><br>
						</td>
					</tr>
					<tr>
					<td>
						<br>
						<div style="text-align: center;"><input name="register" value="บันทึก" type="submit"></div>
					</td>
					</tr>
				</tbody>
				</table>
				<br>
			</form>
<!--menu end-->
		</td>
	</tr>
</table>
<!--end menu-->
</body></html>