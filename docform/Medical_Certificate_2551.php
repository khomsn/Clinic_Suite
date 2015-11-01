<?php 
include '../login/dbc.php';
page_protect();

$id = $_SESSION['patdesk'];
$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
while ($ptinfo = mysqli_fetch_array($ptin))
{
  $prefix = $ptinfo['prefix'];
  $fname = $ptinfo['fname'];
  $lname = $ptinfo['lname'];
  $ctz_id = $ptinfo['ctz_id'];
  $height = $ptinfo['height'];
  $address1 = $ptinfo['address1'];
  $address2 = $ptinfo['address2'];
  $address3 = $ptinfo['address3'];
  $address4 = $ptinfo['address4'];
  $address5 = $ptinfo['address5'];
  $zip = $ptinfo['zipcode'];
}
$pttable = "pt_".$id;
$today = date("Y-m-d");
if($_POST['cil']=="ไม่มี")
{
$_SESSION['cil'] = 0;
}
elseif($_POST['cil']=="มี")
{
$_SESSION['cil'] = 1;
}
if($_POST['acsx']=="ไม่มี")
{
$_SESSION['acsx'] = 0;
}
elseif($_POST['acsx']=="มี")
{
$_SESSION['acsx'] = 1;
}
if($_POST['admit']=="ไม่มี")
{
$_SESSION['admit'] = 0;
}
elseif($_POST['admit']=="มี")
{
$_SESSION['admit'] = 1;
}
$_SESSION['ciltext']=$_POST['ciltext'];
$_SESSION['acsxtext']=$_POST['acsxtext'];
$_SESSION['admittext']=$_POST['admittext'];
$_SESSION['imphtext']=$_POST['imphtext'];
if($_POST['phyex']=="ปกติ")
{
$_SESSION['phyex'] = 0;
}
elseif($_POST['phyex']=="ผิดปกติ")
{
$_SESSION['phyex'] = 1;
}
$_SESSION['phyextext']=$_POST['phyextext'];
$_SESSION['moretext'] = $_POST['morelist'];
$_SESSION['commenttext'] = $_POST['comment'];
if($_POST['finish']=="OK")
{
 $yess = 1;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>ใบรับรองแพทย์</title>
<script language="JavaScript" type="text/javascript" src="../public/js/autoclick.js"></script>
<?php
    $agent = $_SERVER['HTTP_USER_AGENT'];
    
if(strlen(strstr($agent,"Firefox")) > 0 ){      
    $browser = 'firefox';
}
if($browser=='firefox'){
    echo "<link rel='stylesheet' href='../public/css/medcert2551firefox.css'>";
}
else echo "<link rel='stylesheet' href='../public/css/medcert2551.css'>";
?>
<script language="javascript">
function Clickheretoprint()
{ 
  var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
      disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25"; 
  var content_vlue = document.getElementById("print_content").innerHTML; 
  
  var docprint=window.open("","",disp_setting); 
   docprint.document.open(); 
   docprint.document.write('<html><head><title>Print</title>'); 
<?php
if($browser=='firefox'){
    echo "docprint.document.write('<link rel=stylesheet href=../public/css/medcert2551firefox_prt.css>');";
}
else echo "docprint.document.write('<link rel=stylesheet href=../public/css/medcert2551_prt.css>');";
?>
   docprint.document.write('</head><body onLoad="self.print()">');          
   docprint.document.write(content_vlue);          
   docprint.document.write('</body></html>'); 
   docprint.document.close(); 
   docprint.focus(); 
}
</script>
<style type="text/css">
.text1 { width: 50%; }
</style>
</head>
<body lang="th-TH" text="#000000" dir="ltr" style="background: transparent">
<?php if($yess){?>
<div align="center"><a href="javascript:Clickheretoprint()" id="ATC">Print</a></div><br>
<?php }?>
<div class="style3" id="print_content">
<form method="post" action="Medical_Certificate_2551.php" name="regForm" id="regForm">

<div class="page"><div class="subpage">

<table width=100% border=<?php if($yess) echo "0"; else echo "1"?>>
<tr><td>
<p class="western" align="center"><b>ใบรับรองแพทย์</b></p>
<p class="western"><i><b>ส่วนที่ ๑ ของผู้ขอรับใบรับรองสุขภาพ</b></i></p>
<p class="western"  style="margin-bottom: 0in; line-height: 150%">ข้าพเจ้า ......
<?php 
 $ptname =$prefix." ".$fname." ".$lname;
echo $prefix." ".$fname." ".$lname;
?>......<br>
สถานที่อยู่ (ที่สามารถติดต่อได้)...
<?php 
echo $address1." หมู่ที่ ".$address2." ต. ".$address3." อ. ".$address4." จ. ".$address5." ".$zip;
?>...<br>
<?php 
if(preg_match('/^[0-9][0-9]*$/', $ctz_id)) 
{ 
  if($ctz_id>1000000000000)    echo "หมายเลขบัตรประชาชน........";
  else
  {
  echo " โปรดตรวจสอบ เลขประจำตัวใหม่ XXXXXXXXXXXXXXXXXXXX"; 
  break;
  }
}
else echo "หมายเลข PASSPORT.....";
echo $ctz_id;
?>........ข้าพเจ้าขอใบรับรองสุขภาพโดยมีประวัติสุขภาพดังนี้<br>
๑.โรคประจำตัว <?php if(!$yess){?><input type=submit name=cil value="ไม่มี"> <input type=submit name=cil value="มี">
<?php
}
if(!$_SESSION['cil'])
{ echo "<img src='../image/ccb.jpg' width=15 height=15> ไม่มี <img src='../image/checkbox.jpg' width=15 height=15> มี(ระบุ).....................................................................................";
}
if($_SESSION['cil'])
{
echo "<img src='../image/checkbox.jpg' width=15 height=15> ไม่มี <img src='../image/ccb.jpg' width=15 height=15> มี(ระบุ)...";
if(!$yess){
      echo "<input type='text' name='ciltext' class='text1' value='";
      echo $_SESSION['ciltext'];
      echo "'>";
      }
else echo $_SESSION['ciltext'];
echo "...";
}
?><br>
๒.อุบัติเหตุ และ ผ่าตัด <?php if(!$yess){?><input type=submit name=acsx value="ไม่มี"> <input type=submit name=acsx value="มี">
<?php 
}
if(!$_SESSION['acsx'])
{ echo "<img src='../image/ccb.jpg' width=15 height=15> ไม่มี <img src='../image/checkbox.jpg' width=15 height=15> มี(ระบุ).....................................................................................";
}
if($_SESSION['acsx'])
{
echo "<img src='../image/checkbox.jpg' width=15 height=15> ไม่มี <img src='../image/ccb.jpg' width=15 height=15> มี(ระบุ)...";
if(!$yess){
      echo "<input type='text' name='acsxtext' class='text1' value='";
      echo $_SESSION['acsxtext'];
      echo "'>";
      }
else echo $_SESSION['acsxtext'];
echo "...";
}
?><br>
๓.เคยเข้ารับการรักษาในโรงพยาบาล <?php if(!$yess){?><input type=submit name=admit value="ไม่มี"> <input type=submit name=admit value="มี">
<?php 
}
if(!$_SESSION['admit'])
{ echo "<img src='../image/ccb.jpg' width=15 height=15> ไม่มี <img src='../image/checkbox.jpg' width=15 height=15> มี(ระบุ).....................................................................";
}
if($_SESSION['admit'])
{
echo "<img src='../image/checkbox.jpg' width=15 height=15> ไม่มี <img src='../image/ccb.jpg' width=15 height=15> มี(ระบุ)...";
if(!$yess){
      echo "<input type='text' name='admittext' class='text1' value='";
      echo $_SESSION['admittext'];
      echo "'>";
      }
 else echo $_SESSION['admittext'];
      echo "...";
}
?><br>
๔.ประวัติอื่นที่สำคัญ..............<?php if(!$yess){?><input type='text' name='imphtext' class='text1' value='<?php 
echo $_SESSION['imphtext'];?>'><?php } else echo $_SESSION['imphtext'];?>...................................</p>
<p class="western" align="right" style="margin-bottom: 0in; line-height: 100%">ลงชื่อ...........................................................................</p>
<?php 
    $date = new DateTime($today);
    $sd = $date->format("d");
    $sm = $date->format("m");
    $sy = $date->format("Y");
    $bsy = $sy +543;
?>
<p class="ctl">(ในกรณีเด็กที่ไม่สามารถรับรองตนเองได้ให้ผู้ปกครองลงนามรับรองแทนได้)
  วันที่ <?php echo $sd;?> เดือน <?php switch ($sm)
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
}?> พ.ศ. <?php echo $bsy;?></p>
</td></tr>
<tr><td>
<hr>
<p class="western"><i><b>ส่วนที่ ๒ ของแพทย์</b></i></p>
<p class="western" style="margin-bottom: 0in; line-height: 150%">สถานที่ตรวจ 
<u><?php 
$para = mysqli_query($link, "SELECT * FROM parameter WHERE ID = 1");
while($par = mysqli_fetch_array($para))
{
 $clinic = $par['name'];
 $cliniclcid =$par['cliniclcid'];
 echo $par['name']." ".$par['address']." โทร.".$par['tel'];
}
?></u> </p><p class="western">วันที่ <?php echo $sd;?> เดือน <?php switch ($sm)
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
}?> พ.ศ.
 <?php echo $bsy;?> *</p>
<p class="western">ข้าพเจ้า 
<u><?php 
 echo $_SESSION['sfname'];
?></u> ใบอนุญาตประกอบวิชาชีพเวชกรรมเลขที่ <u><?php echo $_SESSION['sflc'];?></u> </p>
<p class="western" style="margin-bottom: 0in; line-height: 150%">สถานที่ประกอบวิชาชีพเวชกรรม <u><?php echo $clinic;?></u> ใบอนุญาตเลขที่ <u><?php echo $cliniclcid;?></u><br>ได้ตรวจร่างกาย <u><?php echo $ptname;?></u> แล้วเมื่อวันที่ <?php echo $sd;?> เดือน <?php switch ($sm)
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
}?> พ.ศ <?php echo $bsy;?> มีรายละเอียดดังนี้<br>
น้ำหนักตัว..
<?php 
 $ptinfo = mysqli_fetch_array(mysqli_query($linkopd, "SELECT MAX(id) FROM $pttable"));
 $maxr = $ptinfo[0];
 $ptinfo = mysqli_query($linkopd, "SELECT * FROM $pttable WHERE id = '$maxr'");
 while($rows= mysqli_fetch_array($ptinfo))
 {
  $weight = $rows['weight'];
  $bp = $rows['bpsys']."/".$rows['bpdia'];
  $hr = $rows['hr'];
  echo $weight;
 }
 ?>.กก.
 ความสูง..<?php echo $height;?>.เซนติเมตร
 ความดันโลหิต..<?php echo $bp;?>.มม.ปรอท
ชีพจร..<?php echo $hr;?>.ครั้ง/นาที<br>
สภาพร่างกายทั่วไปอยู่ในเกณฑ์ <?php if(!$yess){?><input type=submit name=phyex value="ปกติ"> <input type=submit name=phyex value="ผิดปกติ">
<?php 
}
if(!$_SESSION['phyex'])
{ echo "<img src='../image/ccb.jpg' width=15 height=15>  ปกติ <img src='../image/checkbox.jpg' width=15 height=15> ผิดปกติ(ระบุ)..........................................";
}
if($_SESSION['phyex'])
{
echo "<img src='../image/checkbox.jpg' width=15 height=15>  ปกติ <img src='../image/ccb.jpg' width=15 height=15> ผิดปกติ(ระบุ)...";
if(!$yess){
      echo "<input type='text' name='phyextext' class='text1' value='";
      echo $_SESSION['phyextext'];
      echo "'>";
      }
else echo $_SESSION['phyextext'];
echo "...";
}
?></p>
<p class="western" style="margin-bottom: 0in; line-height: 150%">ขอรับรองว่าบุคคลดังกล่าว ไม่เป็นผู้มีร่างกายทุพพลภาพจนไม่สามารถปฏิบัติหน้าที่ได้ ไม่ปรากฏอาการของโรคจิต หรือจิตฟั่นเฟือน  หรือปัญญาอ่อน ไม่ปรากฏ อาการของการติดยาเสพติดให้โทษ และอาการของโรคพิษสุราเรื้อรัง และไม่ปรากฏอาการและอาการแสดงของโรคต่อไปนี้</p>
<ol>
	<li><p class="western">
	โรคเรื้อนในระยะติดต่อหรือในระยะที่ปรากฏอาการเป็นที่รังเกียจแก่สังคม</p>
	<li><p class="western">
	วัณโรคในระยะอันตราย</p>
	<li><p class="western">
	โรคเท้าช้างในระยะที่ปรากฏอาการเป็นที่รังเกียจแก่สังคม</p>
	<?php 
	if(!$yess)
	{
	echo "<li><p class='western'>..<input type='text' name='morelist' class='text1' value='";
	echo $_SESSION['moretext'];
	echo "'..</p>";
	}
	else
	{
	  if(!empty($_SESSION['moretext'])) echo "<li><p class='western'>..".$_SESSION['moretext']."..</p>";
	}
	?>
	<li><p class="western">...........................................................................................................................................................</p>
</ol>
<p class="western" style="margin-bottom: 0in; line-height: 150%">สรุปความเห็นและข้อแนะนำของแพทย์.......
<?php 
	if(!$yess)
	{
	echo "<input type='text' name='comment' class='text1' value='";
	echo $_SESSION['commenttext'];
	echo "'";
	}
	else
	{
	  if(!empty($_SESSION['commenttext'])) echo $_SESSION['commenttext'];
	  else echo "สุขภาพและร่างกายอยู่ในเกณฑ์ดีตามอายุ";
	}
	?>.......</p>
<p class="western" style="margin-bottom: 0in; line-height: 0.14in"><br></p>
<p class="western"  align="right" style="margin-bottom: 0in; line-height: 100%">ลงชื่อ.........................................................แพทย์ผู้ตรวจร่างกาย</p>
<p class="ctl" style="margin-bottom: 0in; line-height: 150%"><i><b>หมายเหตุ</b> (๑)ต้องเป็นแพทย์ซึ่งได้ขึ้นทะเบียนรับใบอนุญาตประกอบวิชาชีพเวชกรรม (๒)ให้แสดงว่าเป็นผู้มีร่างกายสมบูรณ์เพียงใด (๓)แบบฟอร์มนี้ได้รับการรับรองจากมติคณะกรรมการแพทยสภาในการประชุมครั้งที่
 ๘/๒๕๕๑
วันที่ ๑๔ สิงหาคม ๒๕๕๑ <br>*ใบรับรองแพทย์ฉบับนี้ให้ใช้ได้ ๑ เดือนนับแต่วันที่ตรวจร่างกาย</i></p></span></font></font>
</td></tr></table></div></div></div>
<?php if(!$yess){?>
<div style="text-align: right;">ลงข้อมูลเสร็จ กด OK เพื่อดูใบสำเร็จ <input type=submit name="finish" value="OK"></div>
<?php }
if($yess)
{
unset($_SESSION['cil']);
unset($_SESSION['acsx']);
unset($_SESSION['admit']);
unset($_SESSION['phyex']);
}
?>
</form>
</body>
</html>