<?php 
include '../login/dbc.php';
page_protect();

$id = $_SESSION['patcash'];

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
//
$today = date("Y-m-d");
$pin = mysqli_query($linkopd, "select MAX(id) from $pttable ");
$maxrow = mysqli_fetch_row($pin);
$maxid = $maxrow[0];
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html>
<head profile="http://dublincore.org/documents/dcmi-terms/">
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8"/>
<title xml:lang="th_TH.UTF-8">OPD Card</title>
<meta name="DCTERMS.title" content="" xml:lang="th_TH.UTF-8"/>
<meta name="DCTERMS.language" content="th_TH.UTF-8" scheme="DCTERMS.RFC4646"/>
<meta name="DCTERMS.source" content="http://xml.openoffice.org/odf2xhtml"/>
<meta name="DCTERMS.issued" content="2014-06-02T11:20:18.323329400" scheme="DCTERMS.W3CDTF"/>
<meta name="DCTERMS.modified" content="2014-06-02T17:41:05.641561120" scheme="DCTERMS.W3CDTF"/>
<meta name="DCTERMS.provenance" content="" xml:lang="th_TH.UTF-8"/>
<meta name="DCTERMS.subject" content="," xml:lang="th_TH.UTF-8"/>
<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" hreflang="en"/>
<link rel="schema.DCTERMS" href="http://purl.org/dc/terms/" hreflang="en"/>
<link rel="schema.DCTYPE" href="http://purl.org/dc/dcmitype/" hreflang="en"/>
<link rel="schema.DCAM" href="http://purl.org/dc/dcam/" hreflang="en"/>
<link href="../public/css/opdcard.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../public/css/medcert.css">
<script language="JavaScript" type="text/javascript" src="../public/js/autoclick.js"></script>

<script language="javascript">
function Clickheretoprint()
{ 
  var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
      disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25"; 
  var content_vlue = document.getElementById("print_content").innerHTML; 
  
  var docprint=window.open("","",disp_setting); 
   docprint.document.open(); 
   docprint.document.write('<html><head><title>Print</title>'); 
   docprint.document.write('<link href="../public/css/opdcard.css" rel="stylesheet" type="text/css">'); 
   docprint.document.write('<link rel="stylesheet" href="../public/css/medcert_print.css">'); 
   docprint.document.write('</head><body onLoad="self.print()">');          
   docprint.document.write(content_vlue);          
   docprint.document.write('</body></html>'); 
   docprint.document.close(); 
   docprint.focus(); 
}
</script>
</head>
<body dir="ltr" style="max-width:5.8299in;margin-top:0.25in; margin-bottom:0.25in; margin-left:0.75in; margin-right:0.25in; background-color:transparent; writing-mode:lr-tb; ">

<div align="center"><a href="javascript:Clickheretoprint()" id="ATC">Print</a></div><br>
<div id="print_content">
<div class="page"><div class="subpage">
<?php 
$pin = mysqli_query($link, "select * from parameter where id='1'");
while ($row = mysqli_fetch_array($pin))
{
echo "<p class=P3>".$row['name']."</p>";
echo "<p class=P3>".$row['address']."</p>";
}
?>
<p class="P5">
							ข้อมูลการตรวจผู้ป่วย ณ วันที่ <?php 
							$pin = mysqli_query($linkopd, "select * from $pttable WHERE id= '$maxid' ");
							while ($row = mysqli_fetch_array($pin))
							{
									$date = new DateTime($row['date']);
									$sd = $date->format("d");
									$sm = $date->format("m");
									$sy = $date->format("Y");
									$hms = $date->format("G:i:s");
									$bsy = $sy +543;
							}	
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
			}?> พ.ศ. <?php echo $bsy; echo "  เวลา ";echo $hms; echo " น." //date("Y")+543; ?></p>
<p class="P5">ชื่อ: &nbsp; 
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
					}				
							?>
</p>
					<?php 
							$ptin = mysqli_query($linkopd, "select * from $pttable WHERE id= '$maxid' ");
							while ($row = mysqli_fetch_array($ptin))
							{
								echo "<p class=P1><span class=T1>";
								echo "ประวัติแพ้ยาและสาร:</span><span class=T2> ";
								echo $row['drugallergy'];
								//echo "</span></p>";
								echo " <span class=T1>";
								echo "ประวัติโรคประจำตัว:</span><span class=T2> ";
								echo $row['chronicill'];
								echo "</span></p>";
								echo "<p class=P1><span class=T1>";
								echo "อาการนำ:</span><span class=T2> ";
								echo $row['ccp'];
								echo "</span></p>";
								echo "<p class=P1><span class=T1>";
								echo "ประวัติอาการ:</span><span class=T2> ";
								echo $row['dofhis'];
								echo "</span></p>";
								echo "<p class=P1><span class=T1>ตรวจร่างกาย:</span><span class=T2> ";
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
								echo "</span><p class=P2>";
								echo $row['phex'];
								echo "</p>";
//
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

//

								echo "<p class=P1><span class=T1>Diag:</span><span class=T2> ";
								echo $row['ddx'];
								echo "</span></p><p class=P1><span class=T1>คำแนะนำ:</span><span class=T2> ";
								echo $row['inform'];
								$progs = $row['obsandpgnote'];
								echo "</span></p><p class=P4>Treatment:</p> ";
								for ($i=1; $i<=4;$i++)
								{
								if($row['tr'.$i]!="")
								{
									echo "<p class=P1>";
									echo $i; echo ". ";
									echo $row['tr'.$i].' '.$row['trv'.$i].' '.$row['tr'.$i.'o1'].' '.$row['tr'.$i.'o1v'].$row['tr'.$i.'o2'].' '.$row['tr'.$i.'o2v'].$row['tr'.$i.'o3'].' '.$row['tr'.$i.'o3v'].$row['tr'.$i.'o4'].' '.$row['tr'.$i.'o4v'] ;
									echo "</p>";
									//echo "<br>";
								}
								}
								echo "<p class=P4>ยาและผลิตภัณฑ์:</p>";
								for ($i=1;$i<=10;$i++)
								{
									if($row['rx'.$i] !="")
									{
										echo "<p class=P1>";
										echo $i.'. ';
										echo $row['rx'.$i].'('.$row['rxg'.$i].') จำนวน: '.$row['rx'.$i.'v'].' วิธีใช้: '.$row['rx'.$i.'uses'];
										echo "</p>";
									}
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
							    //progression note
							    if (ltrim($progs) !== '')
							    {
							    echo "บันทึกสังเกตุอาการ: "; 
							    echo $progs;
							    }
								
							}
							
	echo "<p class=P1> </p>";
	echo "<p class=P1><span class=T1>ตรวจรักษาโดย</span> ".$doctor."          __________________________</p>";
	echo "<p class=P1>จ่ายยาโดย ".$stfname." ".$stlname."</p>";
	unset($_SESSION['patcash']);
	unset($_SESSION['buyprice']);
	unset($_SESSION['olddeb']);
	unset($_SESSION['patdesk']);
	unset($_SESSION['paytoday']);
	unset($_SESSION['newdeb']);
	unset($_SESSION['price']);
	unset($_SESSION['mrid']);
	?>
</div></div></div>
</body>
</html>