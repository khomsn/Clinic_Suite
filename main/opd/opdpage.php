<?php 
include '../../config/dbc.php';

page_protect();

$id = $_SESSION['patdesk'];
$Patient_id = $id;
include '../../libs/opdxconnection.php';


$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
//
$today = date("Y-m-d");
$pin = mysqli_query($linkopdx, "select MAX(id) from $pttable where clinic='$_SESSION[clinic]'");
$maxrow = mysqli_fetch_array($pin);

if($maxrow[0]!=$_SESSION['mrid'])
{
    $_SESSION['mrid'] = $maxrow[0];
    $_SESSION['rid'] = $maxrow[0];
}
if ($_POST['todo'] == '<<' )
{
	$_SESSION['rid'] = $_SESSION['rid'] - 1;
}
if ($_POST['todo'] == '>>' ) 
{
	$_SESSION['rid'] = $_SESSION['rid'] +1;
}
if ($_POST['todo'] == 'Last' )
{
	$_SESSION['rid'] = $_SESSION['mrid'];
}

//masking id
$di=1;
$maskin = mysqli_query($link, "select * from maskid");
while ($row = mysqli_fetch_array($maskin))
{
    $didin[$di]=$row['drugid'];
    $mask = $row['mask'];
    $di=$di+1;
}

$title = "::My Counter::";
include '../../main/header.php';

?>
<script language="javascript">
function Clickheretoprint()
{ 
  var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
      disp_setting+="scrollbars=yes,width=800, height=600, left=100, top=25"; 
  var content_vlue = document.getElementById("print_content").innerHTML; 
  
  var docprint=window.open("","",disp_setting); 
   docprint.document.open(); 
   docprint.document.write("<html><head><title>OPD Card</title>"); 
   docprint.document.write("<link rel='stylesheet' href='../../jscss/css/recform_print.css'/>"); 
   docprint.document.write("</head><body onLoad='self.print()'>");          
   docprint.document.write(content_vlue);          
   docprint.document.write("</body></html>"); 
   docprint.document.close(); 
   docprint.focus(); 
}
</script>
</head>
<body><div class="myaccount">
<form method="post" action="opdpage.php" name="regForm" id="regForm">
<div style="text-align: center;">
<table width=100%>
<tr><td width=33%>
<?php 
      if($_SESSION['rid'] > 1) echo "<input type='submit' name='todo' value='<<' >";
      echo "</td><td width=33%>";
      if($_SESSION['rid'] < $_SESSION['mrid']) echo "<input type='submit' name='todo' value='>>' >";
      echo "</td><td width=33%>";
      echo "<input type='submit' name='todo' value='Last' >";
      echo "</td></tr>";
?>
</table>
</div>
</form>
<br>
  <div align="center"><input type="submit" name="OK" value="Print" onClick="javascript:Clickheretoprint()" ></div>
  <div class="style3" id="print_content">
  <div style="text-align: center;">

<?php 
  $pin = mysqli_query($linkopdx, "select * from $pttable WHERE id= '$_SESSION[rid]' ");
  while ($row = mysqli_fetch_array($pin))
  {
      $date = new DateTime($row['date']);
      $sd = $date->format("d");
      $sm = $date->format("m");
      $sy = $date->format("Y");
      $hms = $date->format("G:i:s");
      $bsy = $sy +543;
      $clinic = $row['clinic'];
  }
echo "<h2 class='titlehdr'>";
echo $clinic;
echo "<br>ข้อมูลการตรวจผู้ป่วย ณ วันที่ ";  
echo $sd." "; 
$m = $sm;
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
  }?> พ.ศ. <?php echo $bsy; echo "  เวลา ";echo $hms; echo " น." //date("Y")+543; ?></h2>
<h3>ชื่อ:&nbsp;
<?php
  while ($row_settings = mysqli_fetch_array($ptin))
  {
      echo $row_settings['fname'];
      echo "&nbsp; &nbsp; &nbsp;"; 
      echo $row_settings['lname'];
      echo "&nbsp; &nbsp; &nbsp;เพศ";
      echo $row_settings['gender'];
      //$date1=date_create(date("Y-m-d"));
      $date2=date_create($row_settings['birthday']);
      $diff=date_diff($date2,$date);
      echo "&nbsp; &nbsp;อายุ&nbsp; ";
      echo $diff->format("%Y ปี %m เดือน %d วัน");
  echo "</h3>";
  }
?>
</div>
<?php 
    $ptin = mysqli_query($linkopdx, "select * from $pttable WHERE id= '$_SESSION[rid]' ");
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
	    echo "<u>อาการนำ:</u> ";
	    echo $row['ccp'];
	    echo "</p>";
	    echo "<p align=justify>";
	    echo "<u>ประวัติอาการ:</u> ";
	    echo $row['dofhis'];
	    echo "</p>";
	    echo "<u>ตรวจร่างกาย:</u> ";
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
	    $progs = $row['obsandpgnote'];
	    ///
	echo "<p>";
	$labidr=$row['labid'];
	$labrsr=$row['labresult'];
	if(!empty($labidr))
	{
	    $n = substr_count($labidr, ';');
	    //$str = 'hypertext;language;programming';
	    $charsl = preg_split('/;/', $labidr);
	}
	if(!empty($labrsr))
	{
	    $n = substr_count($labrsr, ';');
	    //$str = 'hypertext;language;programming';
	    $charsr = preg_split('/;/', $labrsr);
	    echo "<u>Lab:</u> ";
    //	print_r($charsr);
	}
  $filter = mysqli_query($link, "select * from lab WHERE `L_Set` !='SETNAME' ORDER BY `id` ASC  ");
  while ($labinfo = mysqli_fetch_array($filter))
  {
//	  $labset = $labinfo['L_Set'];
//	  $labset =  substr($labset,5);
//	  $labspec = $labinfo['L_specimen'];
//	  $labnomr = $labinfo['normal_r'];
	  $labunit = $labinfo['Lrunit'];
//	  $labmin = $labinfo['r_min'];
//	  $labmax = $labinfo['r_max'];
	  $lname = $labinfo['S_Name'];
	  $lname1 = $labinfo['S_Name']." [".$labinfo['L_Name']."]";
	  for ($i=0;$i<=$n;$i++)
	  {
	    $cond = $charsl[$i];
	  if($lname1==$charsl[$i])
	    {
////	      echo "<tr><td>";
//	      echo $i+1;
//	      echo "</td><td style='text-align:left;'>";
//	      echo $charsl[$i];
//	      echo "</td><td>";
//	      echo $labset;
//	      echo "</td><td>";
//	      echo $labspec;
//	      echo "</td><td>";
	      echo $lname."=";
	      echo $charsr[$i]." ";
//	      echo "</td><td>";
	      echo $labunit." ";
//	      echo "</td><td>";
//	      echo $labnomr;
//	      echo "</td><td>";
//	      echo $labmin;
//	      echo "</td><td>";
//	      echo $labmax;
//	      echo "</td></tr>";
	    }
	  } 
 }
	    ///
	    echo "</p>";
	    echo "<u>Diag:</u> ";
	    echo $row['ddx'];
	    echo "<br> <u>คำแนะนำ:</u> ";
	    echo $row['inform'];
	    echo "<br><u>Treatment:</u>";
	    echo "<ol>";
	    for ($i=1; $i<=4;$i++)
	    {
	    if($row['tr'.$i]!="")
	    {
		    echo "<li>";
		    echo $row['tr'.$i].' '.$row['trv'.$i].' '.$row['tr'.$i.'o1'].' '.$row['tr'.$i.'o1v'].$row['tr'.$i.'o2'].' '.$row['tr'.$i.'o2v'].$row['tr'.$i.'o3'].' '.$row['tr'.$i.'o3v'].$row['tr'.$i.'o4'].' '.$row['tr'.$i.'o4v'] ;
		    echo "</li>";
	    }
	    echo "</ol>";
	    }
	    echo "<u>ยาและผลิตภัณฑ์:</u>";
	    echo "<ol>";
	    for ($i=1;$i<=10;$i++)
	    {
                if($mask == 0)
                {
//                for($j=1;$j<$di;$j++)
                {
//                    if($row['idrx'.$i] != "$didin[$j]")
                    {
                        if($row['rx'.$i] !="")
                        {
                                echo "<li>";
                                if($row['rxby'.$i]!=0) echo $row['rx'.$i].'('.$row['rxg'.$i].'<sup>'.$row['rxby'.$i].'</sup>'.') จำนวน: '.$row['rx'.$i.'v'].' วิธีใช้: '.$row['rx'.$i.'uses'];
                                else echo $row['rx'.$i].'('.$row['rxg'.$i].') จำนวน: '.$row['rx'.$i.'v'].' วิธีใช้: '.$row['rx'.$i.'uses'];
                                echo "</li>";
                        }
                    }
                }
                }
                if($mask == 1)
                {
                for($j=1;$j<$di;$j++)
                {
                    if($row['idrx'.$i] != "$didin[$j]")
                    {
                        if($row['rx'.$i] !="")
                        {
                                echo "<li>";
                                if($row['rxby'.$i]!=0) echo $row['rx'.$i].'('.$row['rxg'.$i].'<sup>'.$row['rxby'.$i].'</sup>'.') จำนวน: '.$row['rx'.$i.'v'].' วิธีใช้: '.$row['rx'.$i.'uses'];
                                else echo $row['rx'.$i].'('.$row['rxg'.$i].') จำนวน: '.$row['rx'.$i.'v'].' วิธีใช้: '.$row['rx'.$i.'uses'];
                                echo "</li>";
                        }
                    }
                }
                }
	    }
	    echo "</ol>";
	    //progression note
	    if (ltrim($progs) !== '')
	    {
	    echo "บันทึกสังเกตอาการ: "; 
	    echo $progs;
	    }
	    $doctor = $row['doctor'];
	    $drlc = $row['dtlc'];
	    $disprx = $row['disprxby'];
	    //$trx = ;
	    $staff = mysqli_query($link, "select * from staff WHERE ID = '$disprx' ");
	    while($row_vl = mysqli_fetch_array($staff))
	    {
	      $stfname = $row_vl['F_Name'];
	      $stlname = $row_vl['L_Name'];
	    }
    }
  echo "<br>";
  echo "ตรวจรักษาโดย ".$doctor;
  echo "<br>จ่ายยาโดย ".$stfname." ".$stlname;
?>
</div><br></div>
</body>
</html>
