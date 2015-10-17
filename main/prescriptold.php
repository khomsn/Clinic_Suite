<?php 
include '../login/dbc.php';
page_protect();

$id = $_SESSION['patdesk'];
$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
while($row = mysqli_fetch_array($ptin)) 
{
	$dl1 = $row['drug_alg_1'];
	$dl2 = $row['drug_alg_2'];
	$dl3 = $row['drug_alg_3'];
	$dl4 = $row['drug_alg_4'];
	$dl5 = $row['drug_alg_5'];
}

$pttable = "pt_".$id;
$tmp = "tmp_".$id;

$pin = mysqli_query($link, "select * from $tmp ");
while ($row = mysqli_fetch_array($pin))
{	
//get preg condition
$preg = $row['preg'];
//
}

$mrid = $_SESSION['mrid']-1;

if ($_POST['todo'] == '<<' )
{
    //check for reloadParent
    $reload =1;
    //
include '../libs/ckoldpr.php';
 
	$_SESSION['rid'] = $_SESSION['rid'] - 1;
	header("Location: prescriptold.php");  
}
elseif ($_POST['todo'] == 'Last' )
{
	$_SESSION['rid'] = $mrid;
	$rid = $_SESSION['rid'];
	header("Location: prescriptold.php");  
}
elseif ($_POST['todo'] == '>>' ) 
{
include '../libs/ckoldpr.php';	
	$_SESSION['rid'] = $_SESSION['rid'] + 1;

	header("Location: prescriptold.php");  
}
elseif ($_POST['todo'] == 'OK' OR $_POST['todo'] == 'Close') 
{
include '../libs/ckoldpr.php';
}

?>

<!DOCTYPE html>
<html>
<head>
<title>ประวัติยาเก่า</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/checkthemall.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
<?php include '../libs/reloadopener.php';?>
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
<form method="post" action="prescriptold.php" name="formMultipleCheckBox" id="formMultipleCheckBox">
<div style="text-align: right;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="main">
<tr><td width =25% style="text-align: right;">
	      <?php 
		$rid = $_SESSION['rid'];
		if($rid > 1){ echo "<input type='submit' name='todo' value='<<' "; if($reload) echo "onClick='reloadParent();'"; echo "/>";} ?>
	</td><td width =25% style="text-align: center;">
	      <?php 	echo "<input type='submit' name='todo' value='Last' >"; ?>
	</td><td width =25% style="text-align: left;">
	      <?php if($rid < $mrid){ echo "<input type='submit' name='todo' value='>>' "; if($reload) echo "onClick='reloadParent();'"; echo "/>";} ?>
	</td><td width =80 style="text-align: center;">
		<input type='submit' name='todo' value='OK' onClick='reloadParent();'/>
	</td><td width =40 style="text-align: right;">
		<input type="submit" name="todo" value="Close" onClick="reloadParentAndClose();"/>
</td></tr>
</table>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="main">
  <tr><td>
		<h3 class="titlehdr">ประวัติยาของ <?php
		$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
		while ($row_settings = mysqli_fetch_array($ptin))
		{
			echo $row_settings['fname']; 
			echo "&nbsp;"; 
			echo $row_settings['lname'];
		}	
		$pin = mysqli_query($linkopd, "select * from $pttable WHERE id = '$rid' ");
		while ($row_settings = mysqli_fetch_array($pin))
		{
			echo " &nbsp; เมื่อวันที่ ";
			$date = new DateTime($row_settings['date']);
			$sd = $date->format("d");
			$sm = $date->format("m");
			$sy = $date->format("Y");
			$bsy = $sy+543;
			echo $sd;
			echo " ";
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
			}
			echo " พ.ศ.";
			echo $bsy; //date("Y")+543;
			echo " ณ ".$row_settings['clinic'];
		}				
		?></h3>
<form id="formMultipleCheckBox" name="formMultipleCheckBox">
      <table style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2"  class="forms">
	      <tbody>
		      <tr><th width = 10><input name="checkAll" type="checkbox" id="checkAll" value="1" onclick="javascript:checkThemAll(this);" /></th><th width=10>No</th><th width =250>ชื่อ+ขนาด</th><th>ชื่อสามัญ</th><th>วิธีใช้</th><th width = 35>จำนวน</th></tr>
		      <?php 
			      $ptin = mysqli_query($linkopd, "select * from $pttable WHERE id = '$rid' ");
			      while ($row = mysqli_fetch_array($ptin))
			      {
				      for($i=1;$i<=10;$i++)
				      {
					      $rx ="rx".$i;
					      $rxgn ="rxg".$i;
					      $us = "rx".$i."uses";
					      $rxv ="rx".$i."v";
					      $y=1;//initialize needed!
		if($row[$rx]!=""){
				  if(!empty($dl1)) { if($row[$rxgn] != $dl1) $y = 1;else {$y=0; goto BKP;}}
				  if(!empty($dl2)) { if($row[$rxgn] != $dl2) $y = 1;else {$y=0; goto BKP;}}
				  if(!empty($dl3)) { if($row[$rxgn] != $dl3) $y = 1;else {$y=0; goto BKP;}}
				  if(!empty($dl4)) { if($row[$rxgn] != $dl4) $y = 1;else {$y=0; goto BKP;}}
				  if(!empty($dl5)) { if($row[$rxgn] != $dl5) $y = 1;else {$y=0; goto BKP;}}

				  $dgn = $row[$rxgn];
				  
				  $dgr = mysqli_query($link, "select groupn from drug_id WHERE dgname = '$dgn' ");
				  while($row1 = mysqli_fetch_array($dgr)) 
				  {
				    $cond = $row1['groupn'];
				  if(!empty($dl1) AND !empty($cond)) { if($row1['groupn'] != $dl1) $y = 1;else {$y=0; goto BKP1;}}
				  if(!empty($dl2) AND !empty($cond)) { if($row1['groupn'] != $dl2) $y = 1;else {$y=0; goto BKP1;}}
				  if(!empty($dl3) AND !empty($cond)) { if($row1['groupn'] != $dl3) $y = 1;else {$y=0; goto BKP1;}}
				  if(!empty($dl4) AND !empty($cond)) { if($row1['groupn'] != $dl4) $y = 1;else {$y=0; goto BKP1;}}
				  if(!empty($dl5) AND !empty($cond)) { if($row1['groupn'] != $dl5) $y = 1;else {$y=0; goto BKP1;}}
					  
				  }
				  BKP1:
				  $dgr = mysqli_query($link, "select subgroup from drug_id WHERE dgname = '$dgn' ");
				  while($row1 = mysqli_fetch_array($dgr)) 
				  {
				  $cond = $row1['subgroup'];
				  if(!empty($dl1) AND !empty($cond)) { if($row1['subgroup'] != $dl1) $y = 1;else {$y=0; goto BKP;}}
				  if(!empty($dl2) AND !empty($cond)) { if($row1['subgroup'] != $dl2) $y = 1;else {$y=0; goto BKP;}}
				  if(!empty($dl3) AND !empty($cond)) { if($row1['subgroup'] != $dl3) $y = 1;else {$y=0; goto BKP;}}
				  if(!empty($dl4) AND !empty($cond)) { if($row1['subgroup'] != $dl4) $y = 1;else {$y=0; goto BKP;}}
				  if(!empty($dl5) AND !empty($cond)) { if($row1['subgroup'] != $dl5) $y = 1;else {$y=0; goto BKP;}}
					  
				  }
				  // goto point
				  BKP://	echo "Y=".$y;
				  //
			      if($y)
			      { echo "<tr><td>";?><input type="checkbox" name="<?php echo $i;?>" id="checkBoxes" value=1 ></td><td><?php echo $i."</td><td>";
			      }
			      else 	echo "<tr><td>No</td><td>$i</td><td>";							
					  echo $row[$rx];
					  echo "</td>";
					  echo "<td>";
					  echo $row[$rxgn];
					  echo "</td>";
					  echo "<td>";
					  echo $row[$us];
					  echo "</td>";
					  echo "<td>";
					  echo $row[$rxv];
					  echo "</td></tr>";
				  }
				}
			      }	
		      ?>
	      </tbody>
      </table>
</form>
		</td>
	</tr>
</table>
</form>
</body>
</html>