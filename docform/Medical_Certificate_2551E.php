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
<div><a href="Medical_Certificate_2551.php">ไทย</a></div>
<form method="post" action="Medical_Certificate_2551E.php" name="regForm" id="regForm">
<div id="print_content">
<table width=100% border=<?php if($yess) echo "0"; else echo "1"?>><tr><td>
<div class="page">
<div class="subpage">
<p class="mainheader">MEDICAL CERTIFICATE</p>
<?php
echo "<div style='float:right;'>";
    $avatar = $pdir. "pt_". $id . ".jpg";
echo "<img src='". $avatar."' width='120' height='120' />";
echo "</div>";
?>
<p class="subheader">PART 1: Person who seek medical certification</p>
<p class="ptinfo"> I am ...
<?php 

 $ptname =$prefix." ".$fname." ".$lname;
echo $prefix." ".$fname." ".$lname;

?>....<br>
Live at:...
<?php 
echo $address1;
if (!empty($addstr)) echo " Road.".$addstr;
if ($address2 !=0) echo " Mo. ".$address2;
echo " T. ".$address3." D. ".$address4." P. ".$address5." ".$zip;
?>...<br>
<?php

if (empty($ctz_id)) $ctz_id=0;

if(preg_match('/^[0-9][0-9]*$/', $ctz_id, $matches))
{
    if($ctz_id > 1000000000000) 
        echo "Identification No...";
    else
    {
        echo " Please check ID XXXXXXXXXXXXX ";
        exit();
    }
}
else echo "PASSPORT No...";

echo $ctz_id;

?>...I seek medical certification, I have health history as stated here:<br>
1. Chronic Illness <?php if(!$yess){?><input type=submit name=cil value="ไม่มี"> <input type=submit name=cil value="มี">
<?php
}
if(!$_SESSION['cil'])
{ echo "<img src='../image/ccb.jpg' width=15 height=15> No <img src='../image/checkbox.jpg' width=15 height=15> Yes(Specify).....................................................................................";
}
if($_SESSION['cil'])
{
echo "<img src='../image/checkbox.jpg' width=15 height=15> No <img src='../image/ccb.jpg' width=15 height=15> Yes(Specify)...";
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
2. Accident or Surgical <?php if(!$yess){?><input type=submit name=acsx value="ไม่มี"> <input type=submit name=acsx value="มี">
<?php 
}
if(!$_SESSION['acsx'])
{ echo "<img src='../image/ccb.jpg' width=15 height=15> No <img src='../image/checkbox.jpg' width=15 height=15> Yes(Specify).....................................................................................";
}
if($_SESSION['acsx'])
{
echo "<img src='../image/checkbox.jpg' width=15 height=15> No <img src='../image/ccb.jpg' width=15 height=15> Yes(Specify)...";
if(!$yess){
      echo "<input type='text' name='acsxtext' class='text1' value='";
      echo $_SESSION['acsxtext'];
      echo "'>";
      }
else echo $_SESSION['acsxtext'];
echo "...";
}
?><br>
3. Had admited to Hospital <?php if(!$yess){?><input type=submit name=admit value="ไม่มี"> <input type=submit name=admit value="มี">
<?php 
}
if(!$_SESSION['admit'])
{ echo "<img src='../image/ccb.jpg' width=15 height=15> No <img src='../image/checkbox.jpg' width=15 height=15> Yes(Specify).....................................................................";
}
if($_SESSION['admit'])
{
echo "<img src='../image/checkbox.jpg' width=15 height=15> No <img src='../image/ccb.jpg' width=15 height=15> Yes(Specify)...";
if(!$yess){
      echo "<input type='text' name='admittext' class='text1' value='";
      echo $_SESSION['admittext'];
      echo "'>";
      }
 else echo $_SESSION['admittext'];
      echo "...";
}
?><br>
4. Importance information.....<?php if(!$yess){?><input type='text' name='imphtext' class='text1' value='<?php 
echo $_SESSION['imphtext'];?>'><?php } else echo $_SESSION['imphtext'];?>...................................</p>
<p class="signplace">Sign...........................................................................</p>
<?php 
    $date = new DateTime($today);
    $sd = $date->format("d");
    $sm = $date->format("m");
    $sy = $date->format("Y");
?>
<p class="comment">(Parents can sign for thier child.)
  <?php echo $sd;?> <?php switch ($sm)
{
  case 1:
  echo "January";
  break;
  case 2:
  echo "February";
  break;
  case 3:
  echo "March";
  break;
  case 4:
  echo "April";
  break;
  case 5:
  echo "May";
  break;
  case 6:
  echo "June";
  break;
  case 7:
  echo "July";
  break;
  case 8:
  echo "August";
  break;
  case 9:
  echo "September";
  break;
  case 10:
  echo "October";
  break;
  case 11:
  echo "November";
  break;
  case 12:
  echo "December";
  break;
}?> <?php echo $sy;?></p>
<hr>
<p class="subheader">PART 2. MEDICAL DOCTOR</p>
<p class="klpresent">Examination Place. 
<u><?php 
$para = mysqli_query($link, "SELECT * FROM parameter WHERE ID = 1");
while($par = mysqli_fetch_array($para))
{
 $clinic = $par['Ename'];
 $cliniclcid =$par['cliniclcid'];
 echo $par['Ename']." ".$par['Eaddress']." Tel.".$par['tel'];
}
?></u> </p><p class="klpresent"> <?php echo $sd;?> <?php switch ($sm)
{
  case 1:
  echo "January";
  break;
  case 2:
  echo "February";
  break;
  case 3:
  echo "March";
  break;
  case 4:
  echo "April";
  break;
  case 5:
  echo "May";
  break;
  case 6:
  echo "June";
  break;
  case 7:
  echo "July";
  break;
  case 8:
  echo "August";
  break;
  case 9:
  echo "September";
  break;
  case 10:
  echo "October";
  break;
  case 11:
  echo "November";
  break;
  case 12:
  echo "December";
  break;
}?> <?php echo $sy;?> *</p>
<p class="klpresent">I am  
<u><?php 
 echo $_SESSION['Esfname'];
?></u> held Thai Medical License number <u><?php echo $_SESSION['sflc'];?></u> </p>
<p class="klpresent">working as MEDICAL DOCTOR staff at <u><?php echo $clinic;?></u> license number <u><?php echo $cliniclcid;?></u>.<br>I had physical examination of this person, <u><?php echo $ptname;?></u> , on date <?php echo $sd;?> <?php switch ($sm)
{
  case 1:
  echo "January";
  break;
  case 2:
  echo "February";
  break;
  case 3:
  echo "March";
  break;
  case 4:
  echo "April";
  break;
  case 5:
  echo "May";
  break;
  case 6:
  echo "June";
  break;
  case 7:
  echo "July";
  break;
  case 8:
  echo "August";
  break;
  case 9:
  echo "September";
  break;
  case 10:
  echo "October";
  break;
  case 11:
  echo "November";
  break;
  case 12:
  echo "December";
  break;
}?> <?php echo $sy;?> with these details<br>
Body weight..
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
 ?>.kg.
 Height..<?php echo $height;?>.cm
 Blood Pressure..<?php echo $bp;?>.mmHg
Heart Rate..<?php echo $hr;?>.BPM<br>
General physical is <?php if(!$yess){?><input type=submit name=phyex value="ปกติ"> <input type=submit name=phyex value="ผิดปกติ">
<?php 
}
if(!$_SESSION['phyex'])
{ echo "<img src='../image/ccb.jpg' width=15 height=15>  Normal <img src='../image/checkbox.jpg' width=15 height=15> Abnormal(Specify)..........................................";
}
if($_SESSION['phyex'])
{
echo "<img src='../image/checkbox.jpg' width=15 height=15>  Normal <img src='../image/ccb.jpg' width=15 height=15> Abnormal(Specify)...";
if(!$yess){
      echo "<input type='text' name='phyextext' class='text1' value='";
      echo $_SESSION['phyextext'];
      echo "'>";
      }
else echo $_SESSION['phyextext'];
echo "...";
}
?></p>
<p class="klpresent">I certify that this person has 
<?php if(!$yess){?><input type=submit name=disable value="ปกติ"> <input type=submit name=disable value="ผิดปกติ">
<?php 
}
if(!$_SESSION['disable']){ echo "no physical abnormality that can not perform general activity, ";}

?>
<?php if(!$yess){?><input type=submit name=psycho value="จิตปกติ"> <input type=submit name=psycho value="จิตผิดปกติ">
<?php 
}
if(!$_SESSION['psycho']){ echo "has no mental illness or mental disorder, ";}

?>
<?php if(!$yess){?><input type=submit name=downs value="ปัญญาปกติ"> <input type=submit name=downs value="ปัญญาอ่อน">
<?php 
}
if(!$_SESSION['downs']){ echo "or has low IQ., ";}

?>
<?php if(!$yess){?><input type=submit name=addic value="ไม่ติดยา"> <input type=submit name=addic value="ติดยา">
<?php 
}
if(!$_SESSION['addic']){ echo "has no drug addicted symptom, ";}

?>
<?php if(!$yess){?><input type=submit name=alcoh value="พิษสุราไม่ปรากฎ"> <input type=submit name=alcoh value="พิษสุราปรากฎ">
<?php 
}
if(!$_SESSION['alcoh']){ echo "and has no Alcoholic symptom";}

?>
. And has NO Sign and Symptom of these diseases:
<ol class="num">
<?php
if(!$yess){ echo "<li><input type=submit name=lepro value='ไม่เป็นเรื้อน'><input type=submit name=lepro value='เป็นเรื้อน'>"; }
if(!$_SESSION['lepro']){if($yess) echo "<li>"; echo "Leprosy";}
if(!$yess){ echo "<li><input type=submit name=tb value='ไม่เป็นวัณโรค'><input type=submit name=tb value='เป็นวัณโรค'>";}
if(!$_SESSION['tb']){if($yess) echo "<li>"; echo "Tuberculosis (TB) in Infectable-stage";}
if(!$yess){ echo "<li><input type=submit name=Brugia value='ไม่เป็นเท้าช้าง'><input type=submit name=Brugia value='เป็นเท้าช้าง'>";}
if(!$_SESSION['Brugia']){if($yess) echo "<li>"; echo "Elephantiasis/Filariasis with abnormally appearance to public";}
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
<p class="klpresent">DOCTOR: Conclusion : <?php 
	if(!$yess)
	{
	echo "<input type='text' name='conclusion' class='text1' value='";
	echo $_SESSION['conclusion'];
	echo "'>";
	}
	?>
and advice :
<?php 
	if(!$yess)
	{
	echo "<input type='text' name='comment' class='text1' value='";
	echo $_SESSION['commenttext'];
	echo "'>";
	}
	else
	{
	  if($_SESSION['disable']){echo "has physical abnormally that can not perform any normal duty,"; $ms=1;}
	  if($_SESSION['psycho']){echo "has Sign and Symptom of mental illness or mental disorder,";$ms=1;}
	  if($_SESSION['downs']){echo "has low IQ., ";$ms=1;}
	  if($_SESSION['addic']){echo "has Sign and Symptom of drug addicted, ";$ms=1;}
	  if($_SESSION['alcoh']){echo "has Alcoholic Symptom, ";$ms=1;}
	  if($_SESSION['lepro']){echo "has Leprosy in Infectable stage,";$ms=1;}
	  if($_SESSION['tb']){echo "has Tuberculosis (TB), ";$ms=1;}
	  if($_SESSION['Brugia']){echo "has Elephantiasis/Filariasis with abnormally appearance to public,";$ms=1;}
    if(!empty($_SESSION['conclusion'])) echo $_SESSION['conclusion'];
    else 
    {
        if($_SESSION['cil']){
            echo "This person has chronic illness : ".$_SESSION['ciltext'];
            if(!$_SESSION['disable']){echo ", has good physical that can perform limited range of activities according to age-group.";}
        } else {
            if(!$ms) echo "This person has good health and good physical that can perform full range of activities according to age-group.";
        }
    }
    if (!empty($_SESSION['commenttext'])) echo $_SESSION['commenttext'];
	}
	?>.......</p>
<br>
<p class="signplace">Sign.........................................................Doctor</p>
<p class="reminder"><b>(PS) </b>This must be the Licensed Medical Doctor of Thailand<br>*** THIS CETIFICATE IS VALID ONLY 1 MONTH FROM ISSUED ***</p>
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
