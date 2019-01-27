<?php 
include '../../config/dbc.php';

page_protect();

$id = $_SESSION['patdesk'];
$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
//$pttable = "pt_".$id;
if($_POST['Save']=="Save") 
{
 for($i=1;$i<=5;$i++)
 {
  if(empty($alg[$i]))
    {
      for($j=$i;$j<=5;$j++)
      {
	$dgn = "dgname".$j;
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
 for($i=1;$i<=5;$i++)
 {
  if(empty($chrl[$i]))
    {
      for($j=$i;$j<=5;$j++)
      {
	$chrln = "chroil".$j;
	if(!empty($_POST[$chrln]))
	{
	  $chrlnv = $_POST[$chrln];
	  goto jp2;
	}
	else
	{
	$chrlnv ="";
	}
      }
      jp2 :
      //empty  $_POST[$chrln];
      $_POST[$chrln] = "";
      
      $chrl[$i] = $chrlnv;
    }
 }

	$sql_insert = "UPDATE `patient_id` SET 
				`drug_alg_1` = '$alg[1]',
				`drug_alg_2` = '$alg[2]',
				`drug_alg_3` = '$alg[3]',
				`drug_alg_4` = '$alg[4]',
				`drug_alg_5` = '$alg[5]',
				`chro_ill_1` = '$chrl[1]',
				`chro_ill_2` = '$chrl[2]',
				`chro_ill_3` = '$chrl[3]',
				`chro_ill_4` = '$chrl[4]',
				`chro_ill_5` = '$chrl[5]'
				WHERE `id` ='$id' LIMIT 1 ; 
				";
mysqli_query($linkopd, $sql_insert);
header("Location: chronicill.php"); 
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Chronic Condition</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<link rel="stylesheet" type="text/css" href="../../jscss/js/jquery-ui-1.12.1.custom/jquery-ui.min.css" />
<link rel="stylesheet" href="../../jscss/css/styles.css">
<script language="JavaScript" type="text/javascript" src="../../jscss/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../../jscss/js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<?php
include '../../libs/autodruggen.php';
?>
</head>
<body>
<table>
<tr><td>
<div align="left" style="background-color: #B61CE6; max-width: 50; " name="pageinfor1">
<article><hgroup><div class="hdrname">บันทึกโรคประจำตัว</div></hgroup></article>
<p><div class="hdrname">
โรคประจำตัวที่มีผลต่อการใช้ยา ให้ลงยาที่ไม่สามารถใช้ได้ในประวัติแพ้ยาด้วย ถ้าแพ้ยาที่เป็น กลุ่มยา 
ให้ลงข้อมูลเป็นกลุ่มยา 
จะสะดวกในการกรองการสั่งยา 
แต่ถ้าแพ้เฉพาะตัวให้ลงเป็นตัวๆ ไป 
ขณะนี้ลงได้ไม่เกิน 5 ชนิด
</div></p>
</div>
</td>
<td style="width: 60%;"><div style="text-align: center;" >
	<form action="chronicill.php" method="post">
	<table border=1>
	<tr><td>
	<?php 
	echo "<table style=' width: 80%; text-align: center; margin-left: auto; margin-right: auto;' border='1' cellpadding='1' cellspacing='1'>";
	echo "<tr><th>Item</th><th>ชื่อยา/กลุ่มยา</th>";
	while ($row_settings = mysqli_fetch_array($ptin))
	{
		echo "<tr><td>1</td><td><input type=text name=dgname1 id=dggsname1 value='".$row_settings['drug_alg_1']."'></td></tr>";
		echo "<tr><td>2</td><td><input type=text name=dgname2 id=dggsname2 value='".$row_settings['drug_alg_2']."'></td></tr>";
		echo "<tr><td>3</td><td><input type=text name=dgname3 id=dggsname3 value='".$row_settings['drug_alg_3']."'></td></tr>";
		echo "<tr><td>4</td><td><input type=text name=dgname4 id=dggsname4 value='".$row_settings['drug_alg_4']."'></td></tr>";
		echo "<tr><td>5</td><td><input type=text name=dgname5 id=dggsname5 value='".$row_settings['drug_alg_5']."'></td></tr>";
	}
	
	echo "</table>";
	?>
	</td></tr>
	<tr><td>
	<?php 
	echo "<table style=' width: 80%; text-align: center; margin-left: auto; margin-right: auto;' border='1' cellpadding='1' cellspacing='1'>";
	echo "<tr><th>Item</th><th>โรคประจำตัว</th>";
	$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
	while ($row2 = mysqli_fetch_array($ptin))
	{
		echo "<tr><td>1</td><td><input type=text name=chroil1 id=chroil1 value=\"".$row2['chro_ill_1']."\"></td></tr>";
		echo "<tr><td>2</td><td><input type=text name=chroil2 id=chroil2 value=\"".$row2['chro_ill_2']."\"></td></tr>";
		echo "<tr><td>3</td><td><input type=text name=chroil3 id=chroil3 value=\"".$row2['chro_ill_3']."\"></td></tr>";
		echo "<tr><td>4</td><td><input type=text name=chroil4 id=chroil4 value=\"".$row2['chro_ill_4']."\"></td></tr>";
		echo "<tr><td>5</td><td><input type=text name=chroil5 id=chroil5 value=\"".$row2['chro_ill_5']."\"></td></tr>";
	}
	
	echo "</table>";
	?>
	</td></tr>
	</table>
	<br>
	<input type="submit" name="Save" value="Save">
	</form>
	</div>
</td></tr>
</table>
</body>
</html>
