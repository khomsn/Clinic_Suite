<?php 
include '../login/dbc.php';
page_protect();

$id = $_SESSION['patdesk'];
$ptin = mysqli_query($linkopd, "SELECT concurdrug FROM patient_id where id='$id' ");
$row_settings = mysqli_fetch_array($ptin);
$concurdrugout = $row_settings[0];
//concurdrug
if(!empty($concurdrugout))
{
    $n = substr_count($concurdrugout, ',');
    //$str = 'hypertext;language;programming';
    $charsl = preg_split('/,/', $concurdrugout);
}

//$pttable = "pt_".$id;
if($_POST['Save']=="Save") 
{
for($i=1;$i<=10;$i++)
{
  $in = 'con_drug_'.$i;
  if(!empty($_POST[$in]))
  {
    if(empty($concurdrug))
    {
      $concurdrug = $_POST[$in];
    } 
    else
    {
      $concurdrug = $concurdrug.','.$_POST[$in];
    }
  }
}

$concurdrug = mysqli_real_escape_string($link, $concurdrug);

$sql_insert = "UPDATE `patient_id` SET `concurdrug` = '$concurdrug' WHERE `id` = '$id' LIMIT 1 ;";
// Now insert Patient to "patient_id" table
mysqli_query($linkopd, $sql_insert) or die("Insertion Failed:" . mysqli_error($linkopd));
header("Location: concurdrug.php"); 
}
?>

<!DOCTYPE html>
<html>
<head>
<title></title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="../public/js/jquery.autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="../public/css/jquery.autocomplete.css" />
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
<article><hgroup><div class="hdrname">บันทึกประวัติยาที่ใช้ประจำ</div></hgroup></article>
<p><div class="hdrname">
ให้ลงข้อมูลเป็น ชื่อจริงของยา (Generic Name) เท่านั้น
ขณะนี้ลงได้ไม่เกิน 10 ชนิด
</div></p>
</div>
</td>
<td style="width: 60%;"><div style="text-align: center;" >
	<form action="concurdrug.php" method="post">
	<?php 
	echo "<table style=' width: 80%; text-align: center; margin-left: auto; margin-right: auto;' border='1' cellpadding='1' cellspacing='1'>";
	echo "<tr><th>Item</th><th>Drug Name or Drug Group</th>";
	echo "<tr><td>1</td><td><input type=text name=con_drug_1 id=concd1 value='".$charsl[0]."'></td></tr>";
	echo "<tr><td>2</td><td><input type=text name=con_drug_2 id=concd2 value='".$charsl[1]."'></td></tr>";
	echo "<tr><td>3</td><td><input type=text name=con_drug_3 id=concd3 value='".$charsl[2]."'></td></tr>";
	echo "<tr><td>4</td><td><input type=text name=con_drug_4 id=concd4 value='".$charsl[3]."'></td></tr>";
	echo "<tr><td>5</td><td><input type=text name=con_drug_5 id=concd5 value='".$charsl[4]."'></td></tr>";
	echo "<tr><td>6</td><td><input type=text name=con_drug_6 id=concd6 value='".$charsl[5]."'></td></tr>";
	echo "<tr><td>7</td><td><input type=text name=con_drug_7 id=concd7 value='".$charsl[6]."'></td></tr>";
	echo "<tr><td>8</td><td><input type=text name=con_drug_8 id=concd8 value='".$charsl[7]."'></td></tr>";
	echo "<tr><td>9</td><td><input type=text name=con_drug_9 id=concd9 value='".$charsl[8]."'></td></tr>";
	echo "<tr><td>10</td><td><input type=text name=con_drug_10 id=concd10 value='".$charsl[9]."'></td></tr>";
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