<?php 
include '../login/dbc.php';
page_protect();

$id = $_SESSION['patdesk'];

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;

$pin = mysqli_query($linkopd, "select MAX(id) from $pttable");
$rid = mysqli_fetch_array($pin);

$pin = mysqli_query($linkopd, "select `inform` from $pttable  WHERE `id` = '$rid[0]' ");
$inf = mysqli_fetch_array($pin);
$finject = $inf[0];

if($_POST['set']=="SET")
{
  if($_POST['calfup']== 1)
 {
    if (strstr($finject, '#'))
    {
      $finject = substr($finject, 0, strpos($finject, "#"));
      $msg[] = "รายการนัดทั้งหมดถูกยกเลิกแล้ว";
    }
   $finject = mysqli_real_escape_string($linkopd, $finject);

   mysqli_query($linkopd, "UPDATE $pttable SET `inform` = '$finject' WHERE `id` = '$rid[0]' ") or die(mysqli_error($linkopd));
 }
 else
 {
  if($_POST['fupinj3']==5)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัด ฉีดยา ต่อเนื่อง 5 วัน #";
  if($_POST['fupinj3']==4)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัด ฉีดยา ต่อเนื่อง 4 วัน #";
  if($_POST['fupinj3']==3)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัด ฉีดยา ต่อเนื่อง 3 วัน #";
  if($_POST['fupinj3']==2)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัด ฉีดยา ต่อเนื่อง 2 วัน #";
  if($_POST['fupinj3']==1)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัด ฉีดยา ต่อเนื่อง 1 วัน #";
  if($_POST['fupinj3time']==3)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัดฉีดยา ทุก 24 ชม #";
  if($_POST['fupinj3time']==2)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัดฉีดยา ทุก 12 ชม #";
  if($_POST['fupinj3time']==1)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัดฉีดยา ทุก 8 ชม #";
 if($_POST['fup']==90)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัดติดตามอีก 3 เดือน #";
 if($_POST['fup']==60)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัดติดตามอีก 2 เดือน #";
 if($_POST['fup']==30)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัดติดตามอีก 1 เดือน #";
 if($_POST['fup']==21)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัดติดตามอีก 21 วัน #";
 if($_POST['fup']==14)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัดติดตามอีก 14 วัน #";
 if($_POST['fup']==7)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัดติดตามอีก 7 วัน #";
 if($_POST['fup']==5)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัดติดตามอีก 5 วัน #";
 if($_POST['fup']==3)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัดติดตามอีก 3 วัน #";
 if($_POST['fup']==1)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัดติดตาม วันพรุ่งนี้ #";
 if($_POST['lab']==1)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัด ตรวจ Lab งดอาหาร 12 ชม กินยาความดันและน้ำเปล่าได้ก่อนมา #";
 if($_POST['lab']==2)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัด ตรวจ Lab งดอาหาร 6 ชม กินยาความดันและน้ำเปล่าได้ก่อนมา #";
 if($_POST['lab']==3)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัด ตรวจ Lab ไม่ต้องงดอาหาร #";
 //
 $fdate = $_POST['fupdate'];
 //echo $fdate->format('Y-m-d');
 $datetime = new DateTime();
 $datetime = $datetime->format('m/d/Y');
    $sd = substr($fdate, 3, -5);
    $sm = substr($fdate, 0, 2);
    $sy = substr($fdate, -4); 
    $sye = $sy+543;
 $date = new DateTime($sy.'-'.$sm.'-'.$sd);
 $date = $date->format('m/d/Y');
 if($date !== $datetime)
 {
   switch ($sm)
    {
    case 1: $m =  "มกราคม"; break;
    case 2: $m =  "กุมภาพันธ์";break;
    case 3: $m =  "มีนาคม";break;
    case 4: $m =  "เมษายน";break;
    case 5:$m =  "พฤษภาคม";break;
    case 6:$m =  "มิถุนายน";break;
    case 7:$m =  "กรกฎาคม";break;
    case 8:$m =  "สิงหาคม";break;
    case 9:$m =  "กันยายน";break;
    case 10:$m =  "ตุลาคม";break;
    case 11:$m =  "พฤศจิกายน";break;
    case 12:$m =  "ธันวาคม";break;
    } 
      $finject = $finject ."# นัดติดตาม วันที่ ".$sd." ".$m." ".$sye." #";
  }
  //<input type=checkbox name=fupin1 value=1>นัด ฉีดยาคุมกำเนิด
  if($_POST['fupin1'] == 3)
  {
  $datetime = new DateTime();
  //echo $datetime->format('Y-m-d');
  $datetime->add(new DateInterval('P84D'));
  $hinjd = $datetime->format('d');
  $hinjm = $datetime->format('m');
  $hinjy = $datetime->format('Y');
  //echo $hinjd .$hinjm.$hinjy;
  $sye = $hinjy+543;
   switch ($hinjm)
    {
    case 1: $m =  "มกราคม"; break;
    case 2: $m =  "กุมภาพันธ์";break;
    case 3: $m =  "มีนาคม";break;
    case 4: $m =  "เมษายน";break;
    case 5:$m =  "พฤษภาคม";break;
    case 6:$m =  "มิถุนายน";break;
    case 7:$m =  "กรกฎาคม";break;
    case 8:$m =  "สิงหาคม";break;
    case 9:$m =  "กันยายน";break;
    case 10:$m =  "ตุลาคม";break;
    case 11:$m =  "พฤศจิกายน";break;
    case 12:$m =  "ธันวาคม";break;
    } 
  $finject = $finject ."# นัด ฉีดยาคุมกำเนิด วันที่ ".$hinjd." ".$m." ".$sye." #";
  }
   //<input type=checkbox name=fupin1 value=4>นัด ฉีดยาคุมกำเนิด
  if($_POST['fupin1'] == 1)
  {
  $datetime = new DateTime();
  //echo $datetime->format('Y-m-d');
  $datetime->add(new DateInterval('P28D'));
  $hinjd = $datetime->format('d');
  $hinjm = $datetime->format('m');
  $hinjy = $datetime->format('Y');
  //echo $hinjd .$hinjm.$hinjy;
  $sye = $hinjy+543;
   switch ($hinjm)
    {
    case 1: $m =  "มกราคม"; break;
    case 2: $m =  "กุมภาพันธ์";break;
    case 3: $m =  "มีนาคม";break;
    case 4: $m =  "เมษายน";break;
    case 5:$m =  "พฤษภาคม";break;
    case 6:$m =  "มิถุนายน";break;
    case 7:$m =  "กรกฎาคม";break;
    case 8:$m =  "สิงหาคม";break;
    case 9:$m =  "กันยายน";break;
    case 10:$m =  "ตุลาคม";break;
    case 11:$m =  "พฤศจิกายน";break;
    case 12:$m =  "ธันวาคม";break;
    } 
  $finject = $finject ."# นัด ฉีดยาคุมกำเนิด วันที่ ".$hinjd." ".$m." ".$sye." หรือกำลังมีประจำเดือน #";
  }
 //<input type=checkbox name=fupin2 value=2>นัด Vaccine TT
 if($_POST['fupin2']==2)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัด Vaccine TT บาดทะยัก อีก 1 และ 6 เดือน #";
 if($_POST['fupin3']==3)//นัด ฉีดยา ต่อเนื่อง
  $finject = $finject ."# นัด Vaccine พิษสุนัขบ้า นับจากวันนี้#";
   
   //<input type=checkbox name=fupin3 value=3>นัด Vaccine พิษสุนัขบ้า

  $finject = mysqli_real_escape_string($linkopd, $finject);

 mysqli_query($linkopd, "UPDATE $pttable SET `inform` = '$finject' WHERE `id` = '$rid[0]' ") or die(mysqli_error($linkopd));
 // go on to other step
  unset($_SESSION['medcert']);
  header("Location: prescriptconfirm.php");   
 }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
<link rel="stylesheet" href="../public/js/jquery-ui-1.11.2.custom/jquery-ui.css">
<script src="../public/js/jquery-ui-1.11.2.custom/external/jquery/jquery.js"></script>
<script src="../public/js/jquery-ui-1.11.2.custom/jquery-ui.js"></script>
<script>
$(function() {
$( "#datepicker" ).datepicker();
});
</script>
</head>

<body >
<div style="text-align: center;">
<h2 class="titlehdr"> ข้อมูลการนัดผู้ป่วย </h2>
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
echo $finject;
    if(!empty($msg))  {
      echo "<div class=\"msg\">";
    foreach ($msg as $m) {
      echo "* $m <br>";
      }
    echo "</div>";	
      }

}
/*
$date = new DateTime('2000-01-01');
$date->add(new DateInterval('P7Y5M4DT4H3M2S'));
echo $date->format('Y-m-d H:i:s') . "\n";
*/
/*
$datetime = new DateTime();
echo $datetime->format('Y-m-d');
$datetime->add(new DateInterval('P7Y5M4DT4H3M2S'));
echo $datetime->format('Y-m-d');
*/
?>
<form method="post" action="appointment.php" name="regForm" id="regForm">
<br>
<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
<input type=radio name=fupinj3 value=5>นัด ฉีดยา ต่อเนื่องอีก 5 วัน
<input type=radio name=fupinj3 value=4>นัด ฉีดยา ต่อเนื่องอีก 4 วัน
<input type=radio name=fupinj3 value=3>นัด ฉีดยา ต่อเนื่องอีก 3 วัน
<input type=radio name=fupinj3 value=2>นัด ฉีดยา ต่อเนื่องอีก 2 วัน
<input type=radio name=fupinj3 value=1>นัด ฉีดยา ต่อเนื่องอีก 1 วัน
<br>
<input type=radio name=fupinj3time value=3>นัดฉีดยา ทุก 24 ชม.
<input type=radio name=fupinj3time value=2>นัดฉีดยา ทุก 12 ชม.
<input type=radio name=fupinj3time value=1>นัดฉีดยา ทุก 8 ชม.
<input type=radio name=fupinj3time value=0>นัด ไม่จำกัดเวลา 


<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
<input type=radio name=fup value=90>นัด 3 เดือน
<input type=radio name=fup value=60>นัด 2 เดือน
<input type=radio name=fup value=30>นัด 1 เดือน
<input type=radio name=fup value=21>นัด 3 สัปดาห์
<input type=radio name=fup value=14>นัด 2 สัปดาห์
<input type=radio name=fup value=7>นัด 1 สัปดาห์
<input type=radio name=fup value=5>นัด 5 วัน
<input type=radio name=fup value=3>นัด 3 วัน
<input type=radio name=fup value=1>นัด 1 วัน

<h4>นัดวันที่ : <input name=fupdate type="text" id="datepicker" value="<?php $duedate=date_create($duedate);$duedate=date_format($duedate,"m/d/Y");echo $duedate;?>">
<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
<input type=checkbox name=lab value=1>นัด ตรวจ Lab งดอาหาร 12 ชม
<input type=checkbox name=lab value=2>นัด ตรวจ Lab งดอาหาร 6 ชม
<input type=checkbox name=lab value=3>นัด ตรวจ Lab ไม่ต้องงดอาหาร

<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
<input type=checkbox name=fupin1 value=3>นัด ฉีดยาคุมกำเนิด 3 เดือน
<input type=checkbox name=fupin1 value=1>นัด ฉีดยาคุมกำเนิด 1 เดือน
<input type=checkbox name=fupin2 value=2>นัด Vaccine TT
<input type=checkbox name=fupin3 value=3>นัด Vaccine พิษสุนัขบ้า

<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
<input type=checkbox name=calfup value=1>ยกเลิกนัด

<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">

<input type=submit name=set value="SET">
</h4>
</form>
</div>
</body></html>