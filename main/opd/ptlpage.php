<?php 
include '../../config/dbc.php';

page_protect();

include '../../libs/progdate.php';

$id = $_SESSION['patdesk'];
$Patient_id = $id;
include '../../libs/opdxconnection.php';


$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
$tmp = "tmp_".$id;
$today = date("Y-m-d");

$pin = mysqli_query($linkopdx, "SELECT * from $pttable ORDER BY id DESC LIMIT 1");
while ($row_settings = mysqli_fetch_array($pin))
{
    $rid = $row_settings['id'];
}

echo "<!DOCTYPE html>
<html>
<head>
<meta content=\"text/html; charset=utf-8\" http-equiv=\"content-type\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/styles.css\"/>
</head><body>";

?>
<SCRIPT LANGUAGE="JavaScript">
function redirect () { setTimeout("go_now()",3000); }
function go_now ()   { top.window.location.href = "../opd/pt_to_doctor.php"; }
</SCRIPT>
</head>
<body onLoad='redirect()'>
<div class="myaccount">
<div class="center">
<h2 class="titlehdr"> ข้อมูลการตรวจผู้ป่วย ณ วันที่ <?php echo $sd; ?> <?php $m = $sm;
switch ($m)
{
case 1:
echo "มกราคม";
break;
case 2:
echo "กุมภาพันธ์";
break;
case 3:
echo "มีนาคม";
break;
case 4:
echo "เมษายน";
break;
case 5:
echo "พฤษภาคม";
break;
case 6:
echo "มิถุนายน";
break;
case 7:
echo "กรกฎาคม";
break;
case 8:
echo "สิงหาคม";
break;
case 9:
echo "กันยายน";
break;
case 10:
echo "ตุลาคม";
break;
case 11:
echo "พฤศจิกายน";
break;
case 12:
echo "ธันวาคม";
break;
}?> พ.ศ. <?php echo $bsy; //date("Y")+543;?></h2>
<h3>ชื่อ: &nbsp; 
<?php
while ($row_settings = mysqli_fetch_array($ptin))
{
echo $row_settings['fname'];
echo "&nbsp; &nbsp; &nbsp;"; 
echo $row_settings['lname'];
echo "&nbsp; &nbsp; &nbsp;เพศ";
echo $row_settings['gender'];
$date1=date_create(date("Y-m-d"));
$date2=date_create($row_settings['birthday']);
$diff=date_diff($date2,$date1);
echo "&nbsp; &nbsp;อายุ&nbsp; ";
echo $diff->format("%Y ปี %m เดือน %d วัน");

echo "</h3>";
}				
?>
<h4>มาปรึกษาเรื่อง :
<?php
$ptin = mysqli_query($link, "select * from $tmp ");
while ($row_settings = mysqli_fetch_array($ptin))
{
echo $row_settings['csf'];

}
?>
</h4>
</div>
<?php 
$ptin = mysqli_query($linkopdx, "select * from $pttable WHERE id= '$rid' ");
while ($row = mysqli_fetch_array($ptin))
{
echo "<p>";
echo "<u>ประวัติแพ้ยาและสาร:</u> ";
echo $row['drugallergy'];
echo "</p>";
echo "<p>";
echo "<u>ประวัติโรคประจำตัว:</u> ";
echo $row['chronicill'];
echo "</p>";
echo "<p>";
echo "อาการนำ: ";
echo $row['ccp'];
echo "</p><p>";
echo "ประวัติอาการ: ";
echo $row['dofhis'];
echo "</p>";
echo "ตรวจร่างกาย: ";
echo " BP= ";
echo $row['bpsys'];
echo "/";
echo $row['bpdia'];
echo " mmHg  HR= ";
echo $row['hr'];
echo " BPM";
echo "  Temp=";
echo $row['temp'];
echo "°C  RR=";
echo $row['rr'];
echo " /min ";
echo "  BW= ";
echo $row['weight'];
echo " kg";
echo "<p>";
echo $row['phex'];
echo "</p>";
echo "Diag: ";
echo $row['ddx'];
echo "<br> คำแนะนำ: ";
echo $row['inform'];
echo "<br>Treatment:<br> ";
}	
$ptin = mysqli_query($link, "select * from $tmp ");
while ($row = mysqli_fetch_array($ptin))
{

for ($i=1; $i<=4;$i++)
{
if($row['tr'.$i]!="")
{
echo $i; echo ". ";
echo $row['tr'.$i].' '.$row['tr'.$i.'o1'].' '.$row['tr'.$i.'o1v'].$row['tr'.$i.'o2'].' '.$row['tr'.$i.'o2v'].$row['tr'.$i.'o3'].' '.$row['tr'.$i.'o3v'].$row['tr'.$i.'o4'].' '.$row['tr'.$i.'o4v'] ;
echo "<br>";
}
}
echo "ยาและผลิตภัณฑ์: <br>";
for ($i=1;$i<=10;$i++)
{
if($row['rx'.$i] !="")
{
echo $i.'. ';
echo $row['rx'.$i].'('.$row['rxg'.$i].') จำนวน: '.$row['rx'.$i.'v'].' วิธีใช้: '.$row['rx'.$i.'uses'];
echo "<br>";
}
}
}
?>
</div><br></body></html>
