<?php 
include '../login/dbc.php';
page_protect();

$id = $_SESSION['patdesk'];
$ptin = mysqli_query($linkopd, "SELECT * FROM patient_id where id='$id' ");
//$pttable = "pt_".$id;
if($_POST['Save']=="Save") 
{
 for($i=1;$i<=5;$i++)
 {
  if(empty($alg[$i]))
    {
      for($j=$i;$j<=5;$j++)
      {
	$dgn = "dgn".$j;
	if(!empty($_POST[$dgn]))
	{
	  $dgnv = $_POST[$dgn];
	  goto jp1;
	}
	else
	{
	$dgnv ="";
	}
      }
      jp1 :
      //empty  $_POST[$dgn];
      $_POST[$dgn] = "";
      
      $alg[$i] = $dgnv;
    }
 }
	$sql_insert = "UPDATE `patient_id` SET 
				`drug_alg_1` = '$alg[1]',
				`drug_alg_2` = '$alg[2]',
				`drug_alg_3` = '$alg[3]',
				`drug_alg_4` = '$alg[4]',
				`drug_alg_5` = '$alg[5]'
				WHERE `id` = '$id' LIMIT 1 ; 
				";
// Now insert Patient to "patient_id" table
mysqli_query($linkopd, $sql_insert) or die("Insertion Failed:" . mysqli_error($linkopd));
header("Location: drallergy.php"); 
}
?>

<!DOCTYPE html>
<html>
<head>
<title></title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-1.3.2.min.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
<?php
include '../libs/autodruggen.php';
?>
</head>

<body>
<table>
<tr>
<td>
<div align="left" style="background-color: #B61CE6; max-width: 50; " name="pageinfor1">
<article><hgroup><div class="hdrname">บันทึกประวัติการแพ้ยา</div></hgroup></article>
<p><div class="hdrname">
ถ้าแพ้ยาที่เป็น กลุ่มยา 
ให้ลงข้อมูลเป็นกลุ่มยา 
จะสะดวกในการกรองการสั่งยา 
แต่ถ้าแพ้เฉพาะตัวให้ลงเป็นตัวๆ ไป 
ขณะนี้ลงได้ไม่เกิน 5 ชนิด
</div></p>
</div>
</td>
<td style="width: 60%;"><div style="text-align: center;" >
	<form action="drallergy.php" method="post">
	<?php 
	echo "<table style=' width: 80%; text-align: center; margin-left: auto; margin-right: auto;' border='1' cellpadding='1' cellspacing='1'>";
	echo "<tr><th>Item</th><th>Drug Name or Drug Group</th>";
	while ($row_settings = mysqli_fetch_array($ptin))
	{
		echo "<tr><td>1</td><td><input type=text name=dgn1 id=dgname1 value=".$row_settings['drug_alg_1']."></td></tr>";
		echo "<tr><td>2</td><td><input type=text name=dgn2 id=dgname2 value=".$row_settings['drug_alg_2']."></td></tr>";
		echo "<tr><td>3</td><td><input type=text name=dgn3 id=dgname3 value=".$row_settings['drug_alg_3']."></td></tr>";
		echo "<tr><td>4</td><td><input type=text name=dgn4 id=dgname4 value=".$row_settings['drug_alg_4']."></td></tr>";
		echo "<tr><td>5</td><td><input type=text name=dgn5 id=dgname5 value=".$row_settings['drug_alg_5']."></td></tr>";
	}
	
	echo "</table>";
	?>
	<br>
	<input type="submit" name="Save" value="Save">
	</form>
	</div></td>
</tr>
</table>


<!--end menu-->
</body>
</html>