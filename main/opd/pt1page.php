<?php 
include '../../config/dbc.php';

page_protect();


$id = $_SESSION['patdesk'];

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
$tmp = "tmp_".$id;

echo "<!DOCTYPE html>
<html>
<head>
<meta content=\"text/html; charset=utf-8\" http-equiv=\"content-type\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/styles.css\"/>
</head><body>";
?>
<div class="center">
<div class="myaccount">
<h2 class="titlehdr"> ข้อมูลการตรวจผู้ป่วย</h2>
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
<br>
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
</div>
<br>
<br>
</body></html>
