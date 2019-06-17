<?php 
include '../config/dbc.php';
page_protect();

$id = $_SESSION['patdesk'];

$Patient_id = $id;
include '../libs/opdxconnection.php';

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
while ($ptinfo = mysqli_fetch_array($ptin))
{
  $prefix = $ptinfo['prefix'];
  $fname = $ptinfo['fname'];
  $lname = $ptinfo['lname'];
  $ctz_id = $ptinfo['ctz_id'];
}
$pttable = "pt_".$id;
$today = date("Y-m-d");
$_SESSION['diag']=$_POST['diag'];
$_SESSION['trmtext']=$_POST['trm'];
$_SESSION['morewhen'] = $_POST['morewhen'];
$_SESSION['moretext'] = $_POST['morelist'];
$_SESSION['moretext1'] = $_POST['morelist1'];
$_SESSION['day'] = $_POST['day'];
if($_POST['rest']=="Y")
{
$_SESSION['rest']= 1;
}
elseif($_POST['rest']=="N")
{
$_SESSION['rest']= 0;
}
if($_POST['finish']=="OK")
{
 $yess = 1;
}

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title></title>
<script language="JavaScript" type="text/javascript" src="../jscss/js/autoclick.js"></script>
<?php
    $agent = $_SERVER['HTTP_USER_AGENT'];
    
if(strlen(strstr($agent,"Firefox")) > 0 ){      
    $browser = 'firefox';
}
if($browser=='firefox'){
    echo "<link rel='stylesheet' href='../jscss/css/medcertfirefox.css'>";
}
else echo "<link rel='stylesheet' href='../jscss/css/medcert.css'>";
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
    echo "docprint.document.write('<link rel=stylesheet href=../jscss/css/medcertfirefox_print.css>');";
}
else echo "docprint.document.write('<link rel=stylesheet href=../jscss/css/medcert_print.css>');";
?>
   docprint.document.write('</head><body onLoad="self.print()">');          
   docprint.document.write(content_vlue);          
   docprint.document.write('</body></html>'); 
   docprint.document.close(); 
   docprint.focus(); 
}
</script>
<style type="text/css">
#text1 { width: 70%; }
#text2 { width: 50%; }
.intext {width: 60%; }
.inno { width: 40px; }
</style>
</head>
<body><div style="background-color:rgba(140,205,0,0.5); display:inline-block;">
<a href="Medical_Certificate_eng.php">English</a></div>
<?php if($yess){?>
<div align="center"><a href="javascript:Clickheretoprint()" id="ATC">Print</a></div><br>
<?php }?>
<div class="style3" id="print_content">
<p class="western"><br>
</p>
<form method="post" action="Medical_Certificate.php" name="regForm" id="regForm">

<div class="page"><div class="subpage">
<?php 
$para = mysqli_query($link, "SELECT * FROM parameter WHERE ID = 1");
while($par = mysqli_fetch_array($para))
{
?>
<div id="logo" style="height:42px;width:42px;float:left;"><img src="<?php echo $par['logo'];?>" alt="logo" width="42" height="42"></div>
<div id="logo" style="height:42px;width:42px;float:right;"></div>
<p class="western" style="text-align:center;">ใบรับรองแพทย์</p>
<p class="western" style="text-align:center; margin-bottom: 0in; line-height: 150%">
<?php 
 echo $par['name']." ใบอนุญาตเลขที่ ".$par['cliniclcid']."<br>".$par['address']." โทร.".$par['tel'];
}
    $date = new DateTime($today);
    $sd = $date->format("d");
    $sm = $date->format("m");
    $sy = $date->format("Y");
    $bsy = $sy +543;
?>
</p>
<p class="western" style="text-align:center;">วันที่ <?php echo $sd." ";
switch ($sm)
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
<p class="western"  style="margin-bottom: 0in; line-height: 150%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้าพเจ้า <u><?php echo $_SESSION['sfname'];?></u> ใบอนุญาตประกอบวิชาชีพเวชกรรมเลขที่ <u><?php echo $_SESSION['sflc'];?></u> ได้ทำการตรวจรักษา 
<u> <?php echo $prefix." ".$fname." ".$lname;?> </u> เมื่อวันที่ <u> <?php echo $sd." ";
switch ($sm)
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
}
echo " ".$bsy;
//pt info
 $ptinfo = mysqli_fetch_array(mysqli_query($linkopdx, "SELECT MAX(id) FROM $pttable"));
 $maxr = $ptinfo[0];
 $ptinfo = mysqli_query($linkopdx, "SELECT * FROM $pttable WHERE id = '$maxr'");
 while($rows= mysqli_fetch_array($ptinfo))
 {
    $dofhis = $rows['dofhis'];
    $ddx = $rows['ddx'];
 }
?> </u> มีรายละเอียดดังนี้</p>
<p class="ctl"  style="margin-bottom: 0in; line-height: 150%"><u>รายละเอียดอาการเจ็บป่วย</u> <?php echo $dofhis;?></p>
<p class="western10"><u>ผลการวินิจฉัยโรค</u> <?php if(!$yess){?><input type="text" name="diag" id="text1" value="<?php $diag = $_SESSION['diag'];
if(empty($diag)) echo $ddx;
else echo $diag;?>"><?php }
else echo $_SESSION['diag'];?> </p>
<p class="western10"><u>การรักษา</u>
<?php 
if(!$yess)
{
?>
<input type="text" name="trm" id="text2" value="<?php if (empty($_SESSION['trmtext'])) echo "ยารับประทานและยาฉีด พร้อมคำแนะนำ"; else echo $_SESSION['trmtext']; ?>">
<?php 
} 
else echo $_SESSION['trmtext'];
?>
</p>
<p class="western10"><u>สรุปความเห็นแพทย์</u></p>
<ol>
	<li><p class="western10">
	ได้มารับการตรวจรักษาจริง<?php if(!$yess) {echo "เมื่อ "; echo "<input type='text' name='morewhen' class='intext'  value='".$_SESSION['morewhen']."'>";} else { if(!empty($_SESSION['morewhen']))echo "เมื่อ "; echo $_SESSION['morewhen'];} ?>
	<?php 
	if(!$yess)
	{
	echo "<li><p class='western10'>";
	?>
	<input type=submit name=rest value="Y"> <input type=submit name=rest value="N">
	<?php 
	echo "สมควรหยุดพักเพื่อรักษาเป็นเวลา ";
	if($_SESSION['rest'])
	  {
	  echo "<input type='number' class='inno' min='1' max='7' name='day' value='1'>";
	  echo " วัน";
	  }
	echo "<li><p class='western10'>..<input type='text' name='morelist' class='intext'  value='";
	echo $_SESSION['moretext'];
	echo "'..</p>";
        if(!empty($_SESSION['moretext']))
        {
	echo "<li><p class='western10'>..<input type='text' name='morelist1' class='intext'  value='";
	echo $_SESSION['moretext1'];
	echo "'..</p>";
        }
	}
	else
	{ 
	  if($_SESSION['rest'])
	  {
	    echo "<li><p class='western10'>";
	    echo "สมควรหยุดพักเพื่อรักษาเป็นเวลา ";
	    echo $_SESSION['day'];
	    echo " วัน ";
	    if($_SESSION['day']>1)
	    {
	    echo "ตั้งแต่ <u>วันนี้ ถึงวันที่ ";
	    $day = $_SESSION['day']-1;
	    $date = date_create($today);
	    date_add($date, date_interval_create_from_date_string($day .'days'));
	    $restday = date_format($date, 'Y-m-d');
	    $date = new DateTime($restday);
	    $sd = $date->format("d");
	    $sm = $date->format("m");
	    $sy = $date->format("Y");
	    $bsy = $sy +543;
	    
	    echo $sd." ";
	      switch ($sm)
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
	      }
	      echo " ".$bsy;	    
	    echo "</u></p>";
	    }
	    else
	    {
	    echo "ในวันที่ ".$sd." ";
	      switch ($sm)
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
	      }
	      echo " ".$bsy;
	    }
	  }
	  if(!empty($_SESSION['moretext']))
	  {
            echo "<li><p class='western10'>".$_SESSION['moretext']."</p>";
           }
	  if(!empty($_SESSION['moretext1']))
	  {
            echo "<li><p class='western10'>".$_SESSION['moretext1']."</p>";
           }
	}
if($yess)
{
 unset($_SESSION['diag']);
 unset($_SESSION['trmtext']);
 unset($_SESSION['morewhen']);
 unset($_SESSION['moretext']);
 unset($_SESSION['moretext1']);
 unset($_SESSION['day']);
 unset($_SESSION['rest']);
}
	
	?>
</ol>
<div class="pos_r">
<p class="western">ลงชื่อ..................................................แพทย์ผู้ตรวจรักษา</p>
<p class="western"><?php echo $_SESSION['sfname'];?></p>
</div>
</div></div>
</div>
<?php if(!$yess){?>
<div style="text-align: right;">ลงข้อมูลเสร็จ กด OK เพื่อดูใบสำเร็จ <input type=submit name="finish" value="OK"  id="firstfocus"></div>
<?php }?>
</form><br>
</body>
</html>
