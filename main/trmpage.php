<?php 
include '../login/dbc.php';
page_protect();


$id = $_SESSION['pattrm'];

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$tmptable = "tmp_".$id;

$tmptin = mysqli_query($link, "select * from $tmptable ");
while ($row = mysqli_fetch_array($tmptin))
{ 
	$trby1 =$row['trby1'];
	$trby2 =$row['trby2'];
	$trby3 =$row['trby3'];
	$trby4 =$row['trby4'];
	$rxby1 =$row['rxby1'];
	$rxby2 =$row['rxby2'];
	$rxby3 =$row['rxby3'];
	$rxby4 =$row['rxby4'];
	$rxby5 =$row['rxby5'];
	$rxby6 =$row['rxby6'];
	$rxby7 =$row['rxby7'];
	$rxby8 =$row['rxby8'];
	$rxby9 =$row['rxby9'];
	$rxby10 =$row['rxby10'];
}

if($_POST['Save']=="Save")
{
  if(!empty($_POST['trby1'])) $trby1 = $_POST['trby1'];
  if(!empty($_POST['trby2'])) $trby2 = $_POST['trby2'];
  if(!empty($_POST['trby3'])) $trby3 = $_POST['trby3'];
  if(!empty($_POST['trby4'])) $trby4 = $_POST['trby4'];
	
  if(!empty($_POST['rxby1'])) $rxby1 = $_POST['rxby1'];
  if(!empty($_POST['rxby2'])) $rxby2 = $_POST['rxby2'];
  if(!empty($_POST['rxby3'])) $rxby3 = $_POST['rxby3'];
  if(!empty($_POST['rxby4'])) $rxby4 = $_POST['rxby4'];
  if(!empty($_POST['rxby5'])) $rxby5 = $_POST['rxby5'];
  if(!empty($_POST['rxby6'])) $rxby6 = $_POST['rxby6'];
  if(!empty($_POST['rxby7'])) $rxby7 = $_POST['rxby7'];
  if(!empty($_POST['rxby8'])) $rxby8 = $_POST['rxby8'];
  if(!empty($_POST['rxby9'])) $rxby9 = $_POST['rxby9'];
  if(!empty($_POST['rxby10'])) $rxby10 = $_POST['rxby10'];
	
 	mysqli_query($link, "UPDATE `$tmptable` SET 
					`trby1` = '$trby1',
					`trby2` = '$trby2',
					`trby3` = '$trby3',
					`trby4` = '$trby4',
					`rxby1` = '$rxby1',
					`rxby2` = '$rxby2',
					`rxby3` = '$rxby3',
					`rxby4` = '$rxby4',
					`rxby5` = '$rxby5',
					`rxby6` = '$rxby6',
					`rxby7` = '$rxby7',
					`rxby8` = '$rxby8',
					`rxby9` = '$rxby9',
					`rxby10` = '$rxby10'
					") or die(mysqli_error($link));    
   if($_POST['complete']=="1")
   {
	  $_SESSION['Completed']=1;
	  // Now Delete Patient from "pt_to_lab" table
	  mysqli_query($link, "DELETE FROM pt_to_treatment WHERE ptid = '$id' ") or die(mysqli_error($link));
	  //redirect to caller page..
	  if($_SESSION['frompage']== "payment.php")
	  {
	    header("Location: payment.php");
	  }
   }
//header("Location: pt_to_trm.php#top");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
</head>

<body >
      <div style="text-align: center;">
      <h2 class="titlehdr"> ข้อมูลผู้ป่วย</h2>
      <h4>ชื่อ: &nbsp; 
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
	  }				
      ?>
      </h4>
      <h6>Treatment วันนี้ :</h6>
	<form method="post" action="trmpage.php" name="regForm" id="regForm">
	    <table style="background-color: rgb(255, 204, 153); width: 100%; text-align: center;
			    margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">									
		    <tr>
			    <th>No</th><th >ชื่อ</th><th>จำนวน</th><th>Option</th><th>จำนวน</th><th>Option</th><th>จำนวน</th><th>Option</th><th>จำนวน</th><th>Option</th><th>จำนวน</th><th>โดย</th>
		    </tr>
		    <?php 
		    $tmptin = mysqli_query($link, "select * from $tmptable ");
		    while ($row = mysqli_fetch_array($tmptin))
		    { 
			    for($n=1;$n<=4;$n++)
			    {
			    $trid = $row['idtr'.$n];
			    $trby =$row['trby'.$n];
			    if( $trid AND !$trby)
			    {
			    echo "<tr><td>";
			    echo $n;
			    echo "</td><td style='text-align:left;'>";
			    echo $row['tr'.$n];
			    echo "</td><td>";
			    echo $row['trv'.$n];
			    echo "</td><td>";
			    echo  $row['tr'.$n.'o1'];
			    echo "</td><td>";
			    echo $row['tr'.$n.'o1v'];
			    echo "</td><td>";
			    echo  $row['tr'.$n.'o2'];
			    echo "</td><td>";
			    echo $row['tr'.$n.'o2v'];
			    echo "</td><td>";
			    echo  $row['tr'.$n.'o3'];
			    echo "</td><td>";
			    echo $row['tr'.$n.'o3v'];
			    echo "</td><td>";
			    echo  $row['tr'.$n.'o4'];
			    echo "</td><td>";
			    echo $row['tr'.$n.'o4v'];
			    echo "</td><td>";
			    echo "<select name='trby".$n."' >";
			    echo "<option value=''></option>";
			    $staff = mysqli_query($link, "SELECT * FROM staff WHERE status='1'");
			    while($st = mysqli_fetch_array($staff))
			    {
			    echo "<option value='".$st['ID']."'>".$st['F_Name']." ".$st['L_Name']."</option>";
			    }
			    echo "</select>";
			    echo "</td></tr>";
			    }
			    }
		    }	
		   ?>
	    </table>
	    <hr>
							<table style="background-color: rgb(255, 204, 153); width: 100%; text-align: center;
									margin-left: auto; margin-right: auto;" border="1" cellpadding="0" cellspacing="0">									
								<tr>
									<th width = 10 >No</th><th width = 250px >ชื่อ+ขนาด</th><th>ชื่อสามัญ</th><th>วิธีใช้</th><th width =50px>จำนวน</th><th>โดย</th>
								</tr>
								<?php 
								$tmptin = mysqli_query($link, "select * from $tmptable ");
								while ($row = mysqli_fetch_array($tmptin))
								{
									for($i = 1;$i<=10;$i++)
									{
									$idrx="idrx".$i;
									$rx = "rx".$i;
									$rxg = "rxg".$i;
									$us = "rx".$i."uses";
									$rxv = "rx".$i."v";
									$rxsv = "rx".$i."sv";
									$rxby = "rxby".$i;
	$din=$row[$idrx];
	$did = mysqli_query($link, "select * from drug_id where id=$din");
	while($ck = mysqli_fetch_array($did))
	{
	 $ckeckt=$ck['typen'];
	}
	if($ckeckt == "ยาฉีด" and ($row[$rxby]==0)) {
									echo "<tr><td>";
									echo $i;
									echo "</td><td>";
									echo $row[$rx];
									echo "</td><td>";
									echo $row[$rxg];
									echo "</td>";
									echo "<td>";
									echo $row[$us];
									echo "</td>";
									echo "<td>";
									echo $row[$rxv];
			    echo "</td><td>";
			    echo "<select name='rxby".$i."' >";
			    echo "<option value=''></option>";
			    $staff = mysqli_query($link, "SELECT * FROM staff WHERE status='1'");
			    while($st = mysqli_fetch_array($staff))
			    {
			    echo "<option value='".$st['ID']."'>".$st['F_Name']." ".$st['L_Name']."</option>";
			    }
			    echo "</select>";
									echo "</td></tr>";
									}
	$ckeckt="";			}
								}
								?>
							</table>		

	    <div style="text-align:center;">ถ้าทำ Treatment เสร็จให้เลือก "Completed" ด้วย แต่ถ้าต้องการลงขอมูลอย่างเดียว กด "Save" เท่านั้น</div>
	    <?php 
	    if(!$_SESSION['Completed'])
	    {
	    ?>
	    <table width=100%>
	    <tr><td><input type="checkbox" name="complete" value="1" checked>Completed</td><td><input type="submit" name="Save" value="Save"></td></tr>
	    </table>
	    <?php
	    }
	    else
	    {
	      unset($_SESSION['Completed']);
	    }
	    ?>
	</form>
	</div>
</body></html>