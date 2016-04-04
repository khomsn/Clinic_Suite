<?php 
include '../login/dbc.php';
page_protect();

$id = $_SESSION['patdesk'];
$pid = $id;
$ptin = mysqli_query($linkopd, "SELECT * FROM patient_id where id='$id' ");
$pttable = "pt_".$id;
$tmp = "tmp_".$id;
$today = date("Y-m-d");
$pin = mysqli_query($linkopd, "select * from $pttable ");
while ($row_settings = mysqli_fetch_array($pin))
	{
		if($rid < $row_settings['id']) $rid = $row_settings['id'];
	}
	
if($_POST['rec'] == "DDx") 
{
$_SESSION['history'.$pid]= $_POST['dhist'];
$_SESSION['phex'.$pid]=$_POST['phex'];
header("Location: histaking.php"); 
}
if($_POST['register'] == 'บันทึก') 
{
//check staff
$pstaff=mysqli_fetch_array(mysqli_query($linkopd, "select staff from patient_id where id='$id'"));
$policy = $pstaff[0];//พนักงาน
if(!$policy)
{
$policy = $_POST['policy']; //คนไข้ทั่วไป
}

$diags = $_POST['diag'];
$dhist = $_POST['dhist'];
$diags = mysqli_real_escape_string($linkopd, $diags);
$dhist = mysqli_real_escape_string($linkopd, $dhist);

$chronicill = $_SESSION['chron'];
$drugallergy = $_SESSION['drugale'];
$concurdrug = $_SESSION['concurdrug'];

$chronicill = mysqli_real_escape_string($linkopd, $chronicill);
$drugallergy = mysqli_real_escape_string($linkopd, $drugallergy);
$concurdrug = mysqli_real_escape_string($linkopd, $concurdrug);

mysqli_query($link, "UPDATE $tmp SET `preg` = '$_POST[preg]',`pricepolicy` = '$policy'") or die(mysqli_error($link));
if($_POST['preg'] ==1 )
{
 $dhist = $dhist.' และ กำลังตั้งครรภ์ '.$_POST['pregmon']. ' เดือน';
 $pregmonth = $_POST['pregmon'];
}
mysqli_query($linkopd, "UPDATE $pttable SET
			`dofhis` = '$dhist',
			`phex` = '$_POST[phex]',
			`ddx` = '$diags',
			`bpsys` = '$_POST[bpsys]',
			`bpdia` = '$_POST[bpdia]',
			`temp` = '$_POST[temp]',
			`hr` = '$_POST[hr]',
			`rr` = '$_POST[rr]',
			`doctor` = '$_SESSION[sfname]',
			`dtlc` = '$_SESSION[sflc]',
			`weight` = '$_POST[weight]',
			`height` = '$_POST[height]',
			`chronicill` = '$chronicill',
			`drugallergy` = '$drugallergy',
			`concurdrug` = '$concurdrug'
			 WHERE  id='$rid'
			") or die(mysqli_error($linkopd));
			
// go on to other step
//update height at patient_id use fup to record pregnancy period.
mysqli_query($linkopd, "UPDATE patient_id SET `height` = '$_POST[height]', `fup` = '$pregmonth' where id='$id'") or die(mysqli_error($linkopd));

//update diagnosis table
$d1=$_POST['diag'];
$d1x=$d1[1];
if($d1x!=1 AND $d1x !=" " AND !empty($d1x))
{   
    $imp = mysqli_query($linkcm, "select name from diag WHERE name = '$diags'");

    list($imprs) = mysqli_fetch_row($imp);
    if(empty($imprs))
    {
    $sql_insert = "INSERT into `diag` (name) value ('$diags')";
    mysqli_query($linkcm, $sql_insert) or die("Insertion Failed:" . mysqli_error($linkcm));
    }
}
unset($_SESSION['history'.$pid]);
unset($_SESSION['phex'.$pid]);
//go on 
header("Location: prescript.php");  
}
?>

<html>
<head>
<title>ประวัติ ตรวจร่างกาย</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="../public/js/jquery.autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="../public/css/jquery.autocomplete.css" />
	<link rel="stylesheet" href="../public/css/styles.css">
<?php 
include '../libs/autodiag.php';
include '../libs/popup.php';
?>
</head>
<?php 
if(!empty($_SESSION['user_background']))
{
echo "<body style='background-image: url(".$_SESSION['user_background'].");' alink='#000088' link='#006600' vlink='#660000'>";
}
else
{
?>
<body style="background-image: url(../image/ptbg.jpg);" alink="#000088" link="#006600" vlink="#660000">
<?php
}
?>
<table width="100%" border="1" cellspacing="0" cellpadding="5" class="main">
  <tr><td>
		<h3 class="titlehdr">ประวัติ และ ตรวจร่างกาย</h3>
		<form method="post" action="histaking.php" name="regForm" id="regForm">
			<table style="text-align: left; width: 100%; height: 413px;" border="1" cellpadding="2" cellspacing="2"  class="forms">
				<tbody>
					<tr>
						<td style="width: 80%; vertical-align: middle;">
							<div style="text-align: center;">
							<h2>ชื่อ: &nbsp; 
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
								//concurdrug
								$_SESSION['concurdrug']=$row_settings['concurdrug'];
								//check pregnancy
								$tmpin = mysqli_query($link, "select * from $tmp ");
								$preg =0;
								while($rowt = mysqli_fetch_array($tmpin))
								$preg = $rowt['preg'];
								
								if($row_settings['gender']=="หญิง")
								{
                                                                //get pregdate for fup
                                                                $pregmonth = $row_settings['fup'];
								?>
								    <input type="radio" name="preg" value="1" 
								    <?php 
								    if($preg == 1 OR $pregmonth !=0 )
								    {
								    $ptin2 = mysqli_query($linkopd, "select * from $pttable where  id = '$rid' ");
								    while ($row2 = mysqli_fetch_array($ptin2))
								    {
									    if(empty($_SESSION['history'.$pid]))
									    $hist = $row2['dofhis'];
									    else
									    $hist = $_SESSION['history'.$pid];
								    }
								    $newstring = substr($hist, -72);
								    //echo $str = substr($hist, -72);
								    $newstring = preg_replace('/\D/', '', $newstring);
								    echo "checked";}
								    if($pregmonth > 0 AND $pregmonth <= 10) $newstring = $pregmonth;
								    ?>>ตั้งครรภ์
								    <input type=number name=pregmon min=1 max=10 step=1 class=typenumber value='<?php echo $newstring;?>'>
								    <input type="radio" name="preg" value="0" 
								    <?php if($preg == 0) echo "checked";?>>ไม่ตั้งครรภ์
								<?php 
								}
								
								for($i=1;$i<=5;$i++)
								{
									$dal = "drug_alg_".$i;
									if($row_settings[$dal]=="ปฎิเสธ")
									{
									  $drugalg[$i] = "";
									}
									else $drugalg[$i] = $row_settings[$dal];
									$con = "chro_ill_".$i;
									$chro[$i] = $row_settings[$con];
								}	
								$drugale = join(" , ", $drugalg ) ; 
								$chron = join(" , ", $chro) ;
								
								$_SESSION['drugale'] = $drugale;
								$_SESSION['chron'] = $chron;
								if(empty(str_replace(" , ","",$_SESSION['drugale']))) $_SESSION['drugale'] = "ปฎิเสธ";
								if(empty(str_replace(" , ","",$_SESSION['chron']))) $_SESSION['chron'] = "ปฎิเสธ";
								echo "</h2>";
							}				
							?>
								<span style="color: red;"><a href="drallergy.php" onclick="return popup(this,'name','800','450','yes')">แพ้ยา-สาร</a>:
								<?php echo $drugale;?>
								<a href="chronicill.php" onclick="return popup(this,'name','800','600','yes')">โรคประจำตัว</a>:<?php echo $chron;?></span><br>
								<a href="concurdrug.php" onclick="return popup(this,'name','800','600','yes')">ยาที่ใช้ประจำ</a>:<?php echo $_SESSION['concurdrug'];?></span><br>
							<big><big>มาปรึกษาเรื่อง : 
							<?php 
								$tmpin = mysqli_query($link, "select * from $tmp ");
								while($row_settings = mysqli_fetch_array($tmpin))
								{
								echo $row_settings['csf'];
								$policy = $row_settings['pricepolicy'];
								}
							?>
							</big></big>
							<hr style="width: 80%; height: 2px;"><br>
							<input type="radio" name="policy" value="2" <?php if($policy==2) echo "checked";?>>ตรวจโรค
							<input type="radio" name="policy" value="3" <?php if($policy==3) echo "checked";?>>ทำหัตการ
							<input type="radio" name="policy" value="4" <?php if($policy==4) echo "checked";?>>มาตามนัด</div>

							</div>
							<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
							<div style="text-align: center;">
							<table style="background-color: rgb(255, 204, 153); width: 80%; text-align: center;
									margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
							<tr><td width = 25%>
							<a HREF="heightdisp.php" onClick="return popup(this, 'name','400','600','yes')" >สูง:</a>
							<?php 
							$ptin = mysqli_query($linkopd, "SELECT * FROM patient_id where id='$id' ");
							while ($row_settings = mysqli_fetch_array($ptin))
							{
								echo "<input type=text size=3 name=height value=".$row_settings['height'].">";
								$H = $row_settings['height']/100;
							}	
							echo " cm";
							?>
							<br>
							 <a HREF="weightdisp.php" onClick="return popup(this,'name','400','600','yes')" >หนัก :</a>
							<?php 
							$today = date("Y-m-d");
							$id = $rid -1;
							//session to use for history search;
							//$_SESSION['rid'] = $id;
							//$_SESSION['mrid'] = $id+1;
							
							$ptin = mysqli_query($linkopd, "select * from $pttable where id ='$id' ");
							while ($row_settings = mysqli_fetch_array($ptin))
							{
								$Wo = $row_settings['weight'];
								$dxold = $row_settings['ddx'];
								echo "<input type=hidden name=olddx id='oldDx' value='".$dxold."'>";
							}
							$ptin = mysqli_query($linkopd, "select * from $pttable where  id = '$rid' ");
							while ($row_settings = mysqli_fetch_array($ptin))
							{
								echo "<input type='text' size=3 name='weight' value='";
								echo $row_settings['weight'];
								echo "'/>";
								$W = $row_settings['weight'];
								$bps = $row_settings['bpsys'];
								$bpd = $row_settings['bpdia'];
								$hr = $row_settings['hr'];
								if(empty($_SESSION['history'.$pid]))
								$hist = $row_settings['dofhis'];
								else
								$hist = $_SESSION['history'.$pid];
								if(empty($_SESSION['phex'.$pid]))
								$phex = $row_settings['phex'];
								else
								$phex = $_SESSION['phex'.$pid];
								$rr = $row_settings['rr']; 
								$temp = $row_settings['temp']; 
								$ddx = $row_settings['ddx']; 
								
								echo "&nbsp;<small><small><small>(";
								echo round($W - $Wo,1);
								echo ") kg</small></small></small>";
								
							}
							if($_SESSION['ddx']!='')
							{
							        $ddx = $_SESSION['ddx'];
							}
								?>
								
								</td>
								<td>
								
								<a HREF="bmi.php" onClick="return popup(this, 'name','600','200','no')" >BMI  = 
							<?php 
								echo round($W/($H*$H),2);
							?>
								</a>
								</td>
								<td style="width: 60%;">
								
<span style="font-size: smaller;">							 
								<a href="bp.php" onclick="return popup(this,'name','400','600','yes')">BP</a>=
								<?php 
								echo "<input type='text' size=3 maxlength=3 name='bpsys' value='";								
								echo $bps;
								echo "'>/";
								echo "<input type='text' size=3 maxlength=3 name='bpdia' value='";								
								echo $bpd;
								echo "'> mmHg &nbsp;&nbsp;   HR=";
								echo "<input type='text' size=3 maxlength=3 name='hr' value='";								
								echo $hr;
								echo "'> BPM<br>";
								echo "Temp =";
								echo "<input type='text' size=4 maxlength=4 name='temp' value='";
								echo  $temp;
								echo "'>°C &nbsp;&nbsp;&nbsp;  ";
								echo "RR = ";
								echo "<input type='text' size=4 maxlength=4 name='rr' value='";								
								echo $rr;
								echo "'> / min";
							?>
</span>								
								</td>
								</tr>
								</table>  
								</div>
								<hr style="width: 80%; height: 2px;">
								<div style="text-align: left;">
								<big>ประวัติ:</big><br>
								<textarea autofocus  cols="100%" rows="3" type="text" name="dhist"><?php
								if($preg == 1) echo substr($hist, 0,-72 );
								if($preg == 0) echo $hist;
								?></textarea><br>
								<a HREF="physicalexam.php" onClick="return popup(this,'name','400','600','yes')" >ตรวจร่างกาย</a></big> <span STYLE="Padding-left: 5px; border: 5px groove #ffffff"><big><a HREF="uploadpicture.php" onClick="return popup(this,'name','400','600','yes')" >Picture</a></big></span> <br>
								<textarea cols="70%" rows="5" name="phex" type="text"><?php if(empty($phex)) echo "HEENT:
H:
L:
Abd:
Ext:";
								else 	echo $phex;
								?></textarea>
								<div style="display:none;"><input name="register" value="บันทึก" type="submit"></div>
								<div style="text-align: center;">
								<hr style="width: 80%; height: 2px;">
								<input type=submit name=rec value="DDx" onmouseUp="return popup('ddx.php','DDx','600','400','yes')">
								<input name="diag" type="text" id="diag" size="70%" value="<?php 
								echo $ddx;
								unset($_SESSION['ddx']);
								?>"/>
<script type="text/javascript">
// Created by: Jay Rumsey | http://www.nova.edu/~rumsey/
// This script downloaded from JavaScriptBank.com

function ReDDx() {
  var gn, sum='';
  for (i=1; i<2; i++) {
    
    gn = document.getElementById('oldDx'); sum = gn.value; 
  }
  document.getElementById('diag').value = sum;
} 
</script>
<input type="button" id="game" Value="ReDx" onclick="ReDDx()" >
							</div>
						</td>
					</tr>
					<tr>
					<td>
						<div style="text-align: center;"><input name="register" value="บันทึก" type="submit"></div>
					</td>
					</tr>
				</tbody>
				</table>
			</form>
<!--menu end-->
		</td>
	</tr>
</table>
<!--end menu-->
</body></html>
