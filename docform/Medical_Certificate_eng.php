<?php 
include '../config/dbc.php';

//include '../../libs/prince/prince.php';

//$prince = new Prince('/usr/bin/prince');

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
    
/*if(strlen(strstr($agent,"Firefox")) > 0 ){      
    $browser = 'firefox';
}
if($browser=='firefox'){
    echo "<link rel='stylesheet' href='../jscss/css/medcertfirefox.css'>";
}
else echo "<link rel='stylesheet' href='../jscss/css/medcert.css'>";
*/
echo "<link rel='stylesheet' href='../jscss/css/medcert.css'>";
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
/*if($browser=='firefox'){
    echo "docprint.document.write('<link rel=stylesheet href=../jscss/css/medcertfirefox_print.css>');";
}
else echo "docprint.document.write('<link rel=stylesheet href=../jscss/css/medcert_print.css>');";
*/
echo "docprint.document.write('<link rel=stylesheet href=../jscss/css/medcert_print.css>');";
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
<a href="Medical_Certificate.php">ไทย</a></div>
<?php if($yess){?>
<div align="center"><a href="javascript:Clickheretoprint()" id="ATC">Print</a></div><br>
<?php }?>
<form method="post" action="Medical_Certificate_eng.php" name="regForm" id="regForm">
<div class="style3" id="print_content">
<div class="page"><div class="subpage">
<?php 
$para = mysqli_query($link, "SELECT * FROM parameter WHERE ID = 1");
while($par = mysqli_fetch_array($para))
{
?>
<div id="logo" style="height:42px;width:42px;float:left;"><img src="<?php echo $par['logo'];?>" alt="logo" width="42" height="42"></div>
<div id="logo" style="height:42px;width:42px;float:right;"></div>
<p class="westerncentalign">Medical Certificate</p>
<p class="westerncentalign">
<?php 
 echo $par['Ename'].", License number ".$par['cliniclcid']."<br>".$par['Eaddress']." Tel.".$par['tel'];
}
    $date = new DateTime($today);
    $sd = $date->format("d");
    $sm = $date->format("m");
    $sy = $date->format("Y");
?>
</p>
<p class="westerncentalign">Date <?php echo $sd." ";
switch ($sm)
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
}
echo " ".$sy;?></p>
<p class="westernjustalign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I, <u><?php echo $_SESSION['Esfname'];?></u> held Thai Medical License number <u><?php echo $_SESSION['sflc'];?></u>.  I had examined and treated <br>
<u><nobr><?php echo $prefix." ".$fname." ".$lname;?></nobr></u> on date <u> <?php echo $sd." ";
switch ($sm)
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
}
echo " ".$sy;
//pt info
 $ptinfo = mysqli_fetch_array(mysqli_query($linkopdx, "SELECT MAX(id) FROM $pttable"));
 $maxr = $ptinfo[0];
 $ptinfo = mysqli_query($linkopdx, "SELECT * FROM $pttable WHERE id = '$maxr'");
 while($rows= mysqli_fetch_array($ptinfo))
 {
    $dofhis = $rows['dofhis'];
    $ddx = $rows['ddx'];
    $dofhis = mysqli_real_escape_string($linkopdx, $dofhis);
    $dofhis = str_replace("\\r\\n","<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$dofhis);
 }
?> </u> by this information.</p>
<p class="ctl"><u>Illness Information:</u> <?php echo $dofhis;?></p>
<p class="westernleftalign"><u>Impression/Diagnosis:</u> <?php if(!$yess){?><input type="text" name="diag" id="text1" value="<?php $diag = $_SESSION['diag'];
if(empty($diag)) echo $ddx;
else echo $diag;?>"><?php }
else echo $_SESSION['diag'];?> </p>
<p class="westernleftalign"><u>Treatment and Advice:</u>
<?php 
if(!$yess)
{
?>
<input type="text" name="trm" id="text2" value="<?php if (empty($_SESSION['trmtext'])) echo "Oral Medication and Injection Medication"; else echo $_SESSION['trmtext']; ?>">
<?php 
} 
else echo $_SESSION['trmtext'];
?>
</p>
<div class="drcon"><u>Summarize doctors opinion/Information:</u>
<ol class='ajt'>
	<li>
	Truely came for medical treatment <?php if(!$yess) {echo "on "; echo "<input type='text' name='morewhen' class='intext'  value='".$_SESSION['morewhen']."'>";} else { if(!empty($_SESSION['morewhen']))echo "on "; echo $_SESSION['morewhen'];} ?>.
	<?php 
	if(!$yess)
	{
        echo "<li><input type='submit' name='rest' value='Y'><input type='submit' name='rest' value='N'>";
        echo "Should be rested for ";
        if($_SESSION['rest']){echo "<input type='number' class='inno' min='1' max='7' name='day' value='1'> days";}
        echo "<li>..<input type='text' name='morelist' class='intext'  value='".$_SESSION['moretext']."'>..";
        if(!empty($_SESSION['moretext']))
        {
            echo "<li>..<input type='text' name='morelist1' class='intext'  value='".$_SESSION['moretext1']."'>..";
        }
	}
	else
	{ 
	  if($_SESSION['rest'])
	  {
	    echo "<li>";
	    echo "Should be rested for ";
	    echo $_SESSION['day'];

	    if($_SESSION['day']>1)
	    {
	    echo " days from <u>today to ";
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
                }
                echo " ".$sy;
	    echo "</u>.";
	    }
	    else
	    {
	    echo " day on date ".$sd." ";
	      switch ($sm)
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
                }
                echo " ".$sy.".";
	    }
	  }
	  if(!empty($_SESSION['moretext'])) echo "<li>".$_SESSION['moretext'];
	  if(!empty($_SESSION['moretext1'])) echo "<li>".$_SESSION['moretext1'];
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
</ol></div>
<div class="pos_r">
<p class="westernleftalign">Sign..................................................Medical Doctor</p>
<p class="westernleftalign"><?php echo $_SESSION['Esfname']." M.D.";?></p>
</div>
</div></div>
</div>
<?php if(!$yess){?>
<div style="text-align: right;">ลงข้อมูลเสร็จ กด OK เพื่อดูใบสำเร็จ <input type=submit name="finish" value="OK"  id="firstfocus"></div>
<?php }?>
</form><br>
</body>
</html>
