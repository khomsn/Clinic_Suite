<?php 

include '../config/dbc.php';
page_protect();
$id = $_SESSION['patdesk'];
$pdir = "../".PT_AVATAR_PATH;

$Patient_id = $id;
include '../libs/opdxconnection.php';

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
while ($ptinfo = mysqli_fetch_array($ptin))
{
  $prefix = $ptinfo['prefix'];
  $fname = $ptinfo['fname'];
  $lname = $ptinfo['lname'];
  $ctz_id = $ptinfo['ctz_id'];
  $height = $ptinfo['height'];
  $addstr = $ptinfo['addstr'];
  $address1 = $ptinfo['address1'];
  $address2 = $ptinfo['address2'];
  $address3 = $ptinfo['address3'];
  $address4 = $ptinfo['address4'];
  $address5 = $ptinfo['address5'];
  $zip = $ptinfo['zipcode'];
  for ($x=1;$x<=5;$x++)
  {
    if (!empty($ptinfo['chro_ill_'.$x]))        $chronic = $chronic . $ptinfo['chro_ill_'.$x].' ';
  }  
}
$pttable = "pt_".$id;
$today = date("Y-m-d");
if(!empty($chronic)) $_POST['cil']="มี";
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
if($_POST['disable']=="ปกติ")
{
$_SESSION['disable'] = 0;
}
elseif($_POST['disable']=="ผิดปกติ")
{
$_SESSION['disable'] = 1;
}
if($_POST['psycho']=="จิตปกติ")
{
$_SESSION['psycho'] = 0;
}
elseif($_POST['psycho']=="จิตผิดปกติ")
{
$_SESSION['psycho'] = 1;
}
if($_POST['downs']=="ปัญญาปกติ")
{
$_SESSION['downs'] = 0;
}
elseif($_POST['downs']=="ปัญญาอ่อน")
{
$_SESSION['downs'] = 1;
}
if($_POST['addic']=="ไม่ติดยา")
{
$_SESSION['addic'] = 0;
}
elseif($_POST['addic']=="ติดยา")
{
$_SESSION['addic'] = 1;
}
if($_POST['alcoh']=="พิษสุราไม่ปรากฎ")
{
$_SESSION['alcoh'] = 0;
}
elseif($_POST['alcoh']=="พิษสุราปรากฎ")
{
$_SESSION['alcoh'] = 1;
}
if($_POST['lepro']=="ไม่เป็นเรื้อน")
{
$_SESSION['lepro'] = 0;
}
elseif($_POST['lepro']=="เป็นเรื้อน")
{
$_SESSION['lepro'] = 1;
}
if($_POST['tb']=="ไม่เป็นวัณโรค")
{
$_SESSION['tb'] = 0;
}
elseif($_POST['tb']=="เป็นวัณโรค")
{
$_SESSION['tb'] = 1;
}
if($_POST['Brugia']=="ไม่เป็นเท้าช้าง")
{
$_SESSION['Brugia'] = 0;
}
elseif($_POST['Brugia']=="เป็นเท้าช้าง")
{
$_SESSION['Brugia'] = 1;
}

$_SESSION['phyextext']=$_POST['phyextext'];
for($mt=1;$mt<6;$mt++)
{
$_SESSION['moretext'.$mt] = $_POST['morelist'.$mt];
}
$_SESSION['commenttext'] = $_POST['comment'];
$_SESSION['conclusion'] = $_POST['conclusion'];
if($_POST['finish']=="OK")
{
 $yess = 1;
}

$title = "::ใบรับรองแพทย์::";
include '../main/header.php';
if($yess)
echo "<link rel='stylesheet' href='../jscss/css/medcert2551_prt.css'>";
else
echo "<link rel='stylesheet' href='../jscss/css/medcert2551.css'>";
/*
$agent = $_SERVER['HTTP_USER_AGENT'];
    
if(strlen(strstr($agent,"Firefox")) > 0 )
{      
    $browser = 'firefox';
}
if($browser=='firefox')
{
    echo "<link rel='stylesheet' href='../jscss/css/medcert2551firefox.css'>";
}
//else echo "<link rel='stylesheet' href='../jscss/css/medcert2551.css'>";
*/
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
/*
if($browser=='firefox'){
    echo "docprint.document.write('<link rel=stylesheet href=../jscss/css/medcert2551firefox_prt.css>');";
}
*/
echo "docprint.document.write('<link rel=stylesheet href=../jscss/css/medcert2551_prt.css>');";
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
<script language="JavaScript" type="text/javascript" src="../jscss/js/autoclick.js"></script>
<?php 
echo "</head><body>";
if($yess)
{
  echo "<div align=\"center\"><a href=\"javascript:Clickheretoprint()\" id=\"ATC\">Print</a></div><br>";
}
?>
<form method="post" action="Medical_Certificate_2551.php" name="regForm" id="regForm">
<div id="print_content">
<table width=100% border=<?php if($yess) echo "0"; else echo "1"?>><tr><td>
<div class="page">
<div class="subpage">
<p class="mainheader">ใบรับรองแพทย์</p>
<?php
echo "<div style='float:right;'>";
    $avatar = $pdir. "pt_". $id . ".jpg";
echo "<img src='". $avatar."' width='120' height='120' />";
echo "</div>";
?>
<p class="subheader">ส่วนที่ ๑ ของผู้ขอรับใบรับรองสุขภาพ</p>
<p class="ptinfo">ข้าพเจ้า ...
<?php 

 $ptname =$prefix." ".$fname." ".$lname;
echo $prefix." ".$fname." ".$lname;

?>....<br>
สถานที่อยู่ (ที่สามารถติดต่อได้)...
<?php 
echo $address1;
if (!empty($addstr)) echo " ถ.".$addstr;
if ($address2 !=0) echo " หมู่ที่ ".$address2;
echo " ต. ".$address3." อ. ".$address4." จ. ".$address5." ".$zip;
?>...<br>
<?php

if (empty($ctz_id)) $ctz_id=0;

if(preg_match('/^[0-9][0-9]*$/', $ctz_id, $matches))
{
    if($ctz_id > 1000000000000) 
        echo "หมายเลขบัตรประชาชน...";
    else
    {
        echo " โปรดตรวจสอบ เลขประจำตัวใหม่ XXXXXXXXXXXXX ";
        exit();
    }
}
else echo "หมายเลข PASSPORT...";

echo $ctz_id;

?>...ข้าพเจ้าขอใบรับรองสุขภาพโดยมีประวัติสุขภาพดังนี้<br>
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
      if (!empty($chronic) AND ($y != 1))
      {
        echo $chronic;
        $y=1;
      }
      else echo $_SESSION['ciltext'];
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
<p class="signplace">ลงชื่อ...........................................................................</p>
<?php 
    $date = new DateTime($today);
    $sd = $date->format("d");
    $sm = $date->format("m");
    $sy = $date->format("Y");
    $bsy = $sy +543;
?>
<p class="comment">(ในกรณีเด็กที่ไม่สามารถรับรองตนเองได้ให้ผู้ปกครองลงนามรับรองแทนได้)
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
<hr>
<p class="subheader">ส่วนที่ ๒ ของแพทย์</p>
<p class="klpresent">สถานที่ตรวจ 
<u><?php 
$para = mysqli_query($link, "SELECT * FROM parameter WHERE ID = 1");
while($par = mysqli_fetch_array($para))
{
 $clinic = $par['name'];
 $cliniclcid =$par['cliniclcid'];
 echo $par['name']." ".$par['address']." โทร.".$par['tel'];
}
?></u> </p><p class="klpresent">วันที่ <?php echo $sd;?> เดือน <?php switch ($sm)
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
<p class="klpresent">ข้าพเจ้า 
<u><?php 
 echo $_SESSION['sfname'];
?></u> ใบอนุญาตประกอบวิชาชีพเวชกรรมเลขที่ <u><?php echo $_SESSION['sflc'];?></u> </p>
<p class="klpresent">สถานที่ประกอบวิชาชีพเวชกรรม <u><?php echo $clinic;?></u> ใบอนุญาตเลขที่ <u><?php echo $cliniclcid;?></u><br>ได้ตรวจร่างกาย <u><?php echo $ptname;?></u> แล้วเมื่อวันที่ <?php echo $sd;?> เดือน <?php switch ($sm)
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
 $ptinfo = mysqli_fetch_array(mysqli_query($linkopdx, "SELECT MAX(id) FROM $pttable"));
 $maxr = $ptinfo[0];
 $ptinfo = mysqli_query($linkopdx, "SELECT * FROM $pttable WHERE id = '$maxr'");
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
<p class="klpresent">ขอรับรองว่าบุคคลดังกล่าว 
<?php if(!$yess){?><input type=submit name=disable value="ปกติ"> <input type=submit name=disable value="ผิดปกติ">
<?php 
}
if(!$_SESSION['disable']){ echo "ไม่เป็นผู้มีร่างกายทุพพลภาพจนไม่สามารถปฏิบัติหน้าที่ได้ ";}

?>
<?php if(!$yess){?><input type=submit name=psycho value="จิตปกติ"> <input type=submit name=psycho value="จิตผิดปกติ">
<?php 
}
if(!$_SESSION['psycho']){ echo "ไม่ปรากฏอาการของโรคจิต หรือจิตฟั่นเฟือน ";}

?>
<?php if(!$yess){?><input type=submit name=downs value="ปัญญาปกติ"> <input type=submit name=downs value="ปัญญาอ่อน">
<?php 
}
if(!$_SESSION['downs']){ echo "หรือปัญญาอ่อน ";}

?>
<?php if(!$yess){?><input type=submit name=addic value="ไม่ติดยา"> <input type=submit name=addic value="ติดยา">
<?php 
}
if(!$_SESSION['addic']){ echo "ไม่ปรากฏ อาการของการติดยาเสพติดให้โทษ  ";}

?>
<?php if(!$yess){?><input type=submit name=alcoh value="พิษสุราไม่ปรากฎ"> <input type=submit name=alcoh value="พิษสุราปรากฎ">
<?php 
}
if(!$_SESSION['alcoh']){ echo "และอาการของโรคพิษสุราเรื้อรัง ";}

?>
และไม่ปรากฏอาการและอาการแสดงของโรคต่อไปนี้
<ol class="num">
<?php
if(!$yess){ echo "<li><input type=submit name=lepro value='ไม่เป็นเรื้อน'><input type=submit name=lepro value='เป็นเรื้อน'>"; }
if(!$_SESSION['lepro']){if($yess) echo "<li>"; echo "โรคเรื้อนในระยะติดต่อหรือในระยะที่ปรากฏอาการเป็นที่รังเกียจแก่สังคม";}
if(!$yess){ echo "<li><input type=submit name=tb value='ไม่เป็นวัณโรค'><input type=submit name=tb value='เป็นวัณโรค'>";}
if(!$_SESSION['tb']){if($yess) echo "<li>"; echo "วัณโรคในระยะอันตราย";}
if(!$yess){ echo "<li><input type=submit name=Brugia value='ไม่เป็นเท้าช้าง'><input type=submit name=Brugia value='เป็นเท้าช้าง'>";}
if(!$_SESSION['Brugia']){if($yess) echo "<li>"; echo "โรคเท้าช้างในระยะที่ปรากฏอาการเป็นที่รังเกียจแก่สังคม";}
if(!$yess)
{
echo "<li><input type='text' name='morelist1' class='text1' value='";
echo $_SESSION['moretext1'];
echo "'>";
    for($mt=1;$mt<5;$mt++)
    {
        if(!empty($_SESSION['moretext'.$mt]))
        {
            echo "<li><input type='text' name='morelist".($mt+1)."' class='text1' value='";
            echo $_SESSION['moretext'.($mt+1)];
            echo "'>";
        }
    }
}
else
{
    for($mt=1;$mt<6;$mt++)
    {
    if(!empty($_SESSION['moretext'.$mt])) echo "<li>".$_SESSION['moretext'.$mt];
    }
}
?>
	<li>...........................................................................................................................................................
</ol></p>
<p class="klpresent">สรุปความเห็น<?php 
	if(!$yess)
	{
	echo "<input type='text' name='conclusion' class='text1' value='";
	echo $_SESSION['conclusion'];
	echo "'>";
	}
	?>
และข้อแนะนำของแพทย์.......
<?php 
	if(!$yess)
	{
	echo "<input type='text' name='comment' class='text1' value='";
	echo $_SESSION['commenttext'];
	echo "'>";
	}
	else
	{
	  if($_SESSION['disable']){echo "เป็นผู้มีร่างกายทุพพลภาพจนไม่สามารถปฏิบัติหน้าที่ได้ "; $ms=1;}
	  if($_SESSION['psycho']){echo "ปรากฏอาการของโรคจิต หรือจิตฟั่นเฟือน ";$ms=1;}
	  if($_SESSION['downs']){echo "ปรากฏอาการปัญญาอ่อน ";$ms=1;}
	  if($_SESSION['addic']){echo "ปรากฏอาการของการติดยาเสพติดให้โทษ ";$ms=1;}
	  if($_SESSION['alcoh']){echo "ปรากฏอาการของโรคพิษสุราเรื้อรัง ";$ms=1;}
	  if($_SESSION['lepro']){echo "เป็นโรคเรื้อนในระยะติดต่อหรือในระยะที่ปรากฏอาการเป็นที่รังเกียจแก่สังคม ";$ms=1;}
	  if($_SESSION['tb']){echo "เป็นวัณโรคในระยะอันตราย ";$ms=1;}
	  if($_SESSION['Brugia']){echo "เป็นโรคเท้าช้างในระยะที่ปรากฏอาการเป็นที่รังเกียจแก่สังคม ";$ms=1;}
    if(!empty($_SESSION['conclusion'])) echo $_SESSION['conclusion'];
    else 
    {
        if($_SESSION['cil']){
            echo " มีโรคประจำตัวเป็น ".$_SESSION['ciltext'];
            if(!$_SESSION['disable']){echo " ร่างกายอยู่ในเกณฑ์ดีตามอายุ ";}
        } else {
            if(!$ms) echo "สุขภาพและร่างกายอยู่ในเกณฑ์ดีตามอายุ ";
        }
    }
    if (!empty($_SESSION['commenttext'])) echo $_SESSION['commenttext'];
	}
	?>.......</p>
<br>
<p class="signplace">ลงชื่อ.........................................................แพทย์ผู้ตรวจร่างกาย</p>
<p class="reminder"><b>หมายเหตุ</b> (๑)ต้องเป็นแพทย์ซึ่งได้ขึ้นทะเบียนรับใบอนุญาตประกอบวิชาชีพเวชกรรม (๒)ให้แสดงว่าเป็นผู้มีร่างกายสมบูรณ์เพียงใด (๓)แบบฟอร์มนี้ได้รับการรับรองจากมติคณะกรรมการแพทยสภาในการประชุมครั้งที่ ๘/๒๕๕๑ วันที่ ๑๔ สิงหาคม ๒๕๕๑ <br>*ใบรับรองแพทย์ฉบับนี้ให้ใช้ได้ ๑ เดือนนับแต่วันที่ตรวจร่างกาย</p>
</div>
</div>
</td></tr></table>
</div>
<?php if(!$yess){?>
<div style="text-align: right;">ลงข้อมูลเสร็จ กด OK เพื่อดูใบสำเร็จ <input type=submit name="finish" value="OK"  id="firstfocus"></div>
</form>
<br>
</body>
</html>
<?php }
if($yess)
{
unset($_SESSION['cil']);
unset($_SESSION['acsx']);
unset($_SESSION['admit']);
unset($_SESSION['phyex']);
unset($_SESSION['disable']);
unset($_SESSION['psycho']);
unset($_SESSION['downs']);
unset($_SESSION['addic']);
unset($_SESSION['alcoh']);
unset($_SESSION['lepro']);
unset($_SESSION['tb']);
unset($_SESSION['Brugia']);
unset($_SESSION['conclusion']);
unset($_SESSION['commenttext']);
}
?>
