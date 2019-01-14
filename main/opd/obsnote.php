<?php 
include '../../config/dbc.php';

page_protect();

$staff = mysqli_query($link, "select * from staff WHERE ID = ' $_SESSION[staff_id]' ");
while($row_vl = mysqli_fetch_array($staff))
{
  $stfname = $row_vl['F_Name'];
  $stlname = $row_vl['L_Name'];
  $stpref = $row_vl['prefix'];
  $stposit = $row_vl['posit'];
}

$stb = $stpref.' '.$stfname.' '.$stlname.', '.$stposit;

$id = $_SESSION['patdesk'];
$Patient_id = $id;
include '../../libs/opdxconnection.php';

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
$today = date("Y-m-d");
$pin = mysqli_query($linkopdx, "select * from $pttable ");
while ($row_settings = mysqli_fetch_array($pin))
	{
		if($rid < $row_settings['id']) $rid = $row_settings['id'];
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

$oldprog = $_POST['oldprog'];
$newprog = $_POST['newprog'];
$oldprog = mysqli_real_escape_string($linkopd, $oldprog);
$newprog = mysqli_real_escape_string($linkopd, $newprog);
if (ltrim($newprog) === '')
{
$progupdate = $oldprog;
}
if (ltrim($newprog) !== '')
{
$newprog = "[".date('Y-m-d H:i:s')."] ".$newprog." #By ".$stb." [END]";
$progupdate = $oldprog.$newprog."\n";
}

if (ltrim($progupdate) === '')$progupdate = '';

mysqli_query($linkopdx, "UPDATE $pttable SET
			`obsandpgnote` = '$progupdate'
			 WHERE  id='$rid'
			") or die(mysqli_error($linkopdx));

//header("Location: obsnote.php");  
}
$title = "::OPD Note::";
include '../../main/header.php';
?>
</head><body>
<table width="100%" border="1" cellspacing="0" cellpadding="5" class="main">
<tr><td>
  <h3 class="titlehdr">Observe Note</h3>
  <form method="post" action="obsnote.php" name="regForm" id="regForm">
    <table style="text-align: left; width: 100%; height: 413px;" border="1" cellpadding="2" cellspacing="2"  class="forms">
      <tbody>
	<tr><td style="width: 80%; vertical-align: middle;">
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
	      }
	      ?>
	      </h2>
	      <?php 
	      $ptin = mysqli_query($linkopdx, "select * from $pttable where  id = '$rid' ");
	      while ($row_settings = mysqli_fetch_array($ptin))
	      {
	      echo $hist = $row_settings['dofhis'];
	      echo "<hr style='width: 80%; height: 2px;'>";
	      $prog = $row_settings['obsandpgnote'];
	      }
	      if (ltrim($prog) === '') goto emptyresult;
	      // have Observed Note
	      echo "<textarea cols='100%' rows='15' type='text' name='oldprog'>";
	      echo  $prog;
	      echo "</textarea>";
	      echo "<hr style='width: 80%; height: 2px;'>";
	      emptyresult :
	      ?>New Note<br>
	      <textarea cols="100%" rows="10" type="text" name="newprog" autofocus id="firstfocus"></textarea>
	      </div>
	</td></tr>
	<tr><td><div style="text-align: center;"><input name="register" value="บันทึก" type="submit"></div>
	</td></tr>
      </tbody>
    </table>
  </form>
</td></tr>
</table><br>
</body></html>
