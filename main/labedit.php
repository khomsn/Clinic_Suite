<?php 
include '../login/dbc.php';
page_protect();

if(!($_SESSION['accode']%13==0 or $_SESSION['accode']%5==0)) header("Location: lablist.php");

$labid = $_SESSION['labid'];

if($labid %100 ==0 and $labid<5000) 
{
    header("Location: labgroupedit.php");
}
else
{
/* Lab id start from 1x-- to 49-- for lab set.
* for individual lab start from 5000 to 9999.
*/
$id = $labid;
}
/*
//search for id
$lin = mysqli_query($link, "SELECT id FROM lab WHERE id > 9999 ORDER BY id ASC");
while($rows=mysqli_fetch_array($lin)) 
{
	$idnew = $rows['id'];
	$step = $idnew - $id;
	if($step <= 1) 
	{
		$id = $idnew;
	}
	else { goto JPoint1;}
}

JPoint1:
if($idnew<10000) {
$id = 10000;
}
else { $id = $id + 1;}
 */

if($_POST['Save']=='Save') 
{

/************ Lab Name CHECK ************************************
This code does a second check on the server side if the email already exists. It 
queries the database and if it has any existing email it throws user email already exists
*******************************************************************/

$rs_duplicate = mysqli_query($link, "select count(*) as total from lab where L_Name='$_POST[L_Name]' AND L_specimen='$_POST[L_specimen]'") or die(mysqli_error($link));
list($total) = mysqli_fetch_row($rs_duplicate);

if ($total > 1)
{
$err[] = "ERROR - Lab Name with Lab Specimen already exists. Please check.";
//header("Location: register.php?msg=$err");
//exit();
}
/***************************************************************************/
if(empty($err))
{
	//check for lab set change
	if($_POST['L_Set'] != $_SESSION['L_Set_old'])
	{
	  $newset = $_POST['L_Set'];
	  $msl = mysqli_query($link, "SELECT MAX(id) FROM lab WHERE `L_Set` = '$newset' ORDER BY `id` ASC");
	  $maxid = mysqli_fetch_array($msl);
	  $idnew = $maxid[0]+1;
	  if(empty($newset))
	  {
	  $idold=5000;
	  $msl = mysqli_query($link, "SELECT id FROM lab WHERE `L_Set` = '$newset' and id < $idnew ORDER BY `id` ASC");
	  while($maxid = mysqli_fetch_array($msl))
	    {
	      $idnew1 = $maxid['id'];
	      $step = $idnew1 - $idold;
	      if($step > 1) 
	      {
	      $idnew = $idold+1;
	      goto Jpoint;
	      }
	      $idold = $idnew1;
	    }
	  }
	  Jpoint:
	  
	  $mslabidp = mysqli_query($link, "SELECT `id` FROM labidp WHERE `labid` = '$id' ");
	  $olabidp = mysqli_fetch_array($mslabidp);
	  $olabid = $olabidp[0];
	}
	if(empty($_POST['ltr'])) $_POST['ltr']=0;
	
//update lab @ labid
	mysqli_query($link, "UPDATE lab SET
				`L_Name` = '$_POST[L_Name]',
				`S_Name` = '$_POST[S_Name]',
				`L_specimen` = '$_POST[L_specimen]',
				`Lrunit` = '$_POST[Lrunit]',
				`normal_r` = '$_POST[normal_r]',
				`r_min` = '$_POST[r_min]',
				`r_max` = '$_POST[r_max]',
				`Linfo` = '$_POST[Linfo]',
				`L_Set` = '$_POST[L_Set]',
				`price` = '$_POST[L_price]',
				`ltr` = '$_POST[ltr]'
				WHERE id='$id'
				") or die(mysqli_error($link));

	if(!empty($idnew))
	{
//update lab @ labid
	mysqli_query($link, "UPDATE lab SET
				`id` = '$idnew'
				WHERE `L_Name` = '$_POST[L_Name]' AND `S_Name` = '$_POST[S_Name]'
				") or die(mysqli_error($link));
//update labidp @ labid

/*	mysqli_query($link, "UPDATE labidp SET
				`labid` = '$idnew'
				WHERE `id`='$olabid'
				") or die(mysqli_error($link));
*/
	$_SESSION['labid'] = $idnew;
	}
    $msg[] = $_POST['L_Name']." with ".$_POST['L_specimen']." Specimen updated successfully.";
}
    header("Location: labedit.php");
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Lab</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-1.3.2.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/validate-1.5.5/jquery.validate.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
<?php 
include '../libs/autolsl.php';
$formid = "regForm";
include '../libs/validate.php';

?>
</head>
<body>
<table width="100%">
  <tr>
    <td width=150px>
      <div class="pos_l_fix">
		      <?php 
			      /*********************** MYACCOUNT MENU ****************************
			      This code shows my account menu only to logged in users. 
			      Copy this code till END and place it in a new html or php where
			      you want to show myaccount options. This is only visible to logged in users
			      *******************************************************************/
			      if (isset($_SESSION['user_id']))
			      {
				      include 'labmenu.php';
			      } 
		      /*******************************END**************************/
		      ?>
      </div>
    </td>
    <td>

		    แก้ไข Lab เป็นรายตัว ถ้าต้องการเพิ่มเป็นสมาชิกของชุด Lab Set ก็ให้กำหนดค่า Lab Set ได้เลย
	 <?php	
	 if(!empty($err))  {
	   echo "<div class=\"msg\">";
	  foreach ($err as $e) {
	    echo "* $e <br>";
	    }
	  echo "</div>";	
	   }
	 if(!empty($msg))  {
	   echo "<div class=\"msg\">";
	  foreach ($msg as $m) {
	    echo "* $m <br>";
	    }
	  echo "</div>";	
	   }
	 ?> 

		    <form action="labedit.php" method="post" name="regForm" id="regForm" >
		    <table>
	<?php 

	    $lab_in = mysqli_query($link, "select * from lab where id=$labid");
	    while ($rows = mysqli_fetch_array($lab_in))
		  {?>
		    <tr><td>Lab Set </td><td><input type="text" name="L_Set" id="lsn" value="<?php echo $rows['L_Set']; $_SESSION['L_Set_old'] = $rows['L_Set'];?>"></td></tr>
		    <tr><td>ชื่อเต็ม </td><td><input type="text" name="L_Name" class="required" value="<?php echo $rows['L_Name'];?>"></td></tr>
		    <tr><td>ชื่อย่อย </td><td><input type="text" name="S_Name" value="<?php echo $rows['S_Name'];?>"></td></tr>
		    <tr><td>ชนิดสิ่งส่งตรวจ </td><td><input type="text" name="L_specimen" class="required" value="<?php echo $rows['L_specimen'];?>"></td></tr>
		    <tr><td>หน่วยของ Lab </td><td><input type="text" name="Lrunit" value="<?php echo $rows['Lrunit'];?>"></td></tr>
		    <tr><td>ผลปกติ </td><td><input type="text" name="normal_r" value="<?php echo $rows['normal_r'];?>"></td></tr>
		    <tr><td>ค่าต่ำ </td><td><input type="text" name="r_min" value="<?php echo $rows['r_min'];?>"></td></tr>
		    <tr><td>ค่าสูง </td><td><input type="text" name="r_max" value="<?php echo $rows['r_max'];?>"></td></tr>
		    <tr><td>ข้อมูลของ Lab </td><td><textarea name="Linfo" rows="5" cols="60"><?php echo $rows['Linfo'];?></textarea></td></tr>
		    <tr><td>ราคาขาย </td><td><input type="number" name="L_price" size="7" min="0" step="1" value="<?php 
		    echo $rows['price'];
		    ?>"></td></tr>
		    <tr><td>บันทึกเวลา </td><td><input type="checkbox" name="ltr" value="1" <?php if($rows['ltr']==1) echo "checked";?>></td></tr>
		    <?php 
		    }
		    ?>
		    </table><br>
		    <div style="text-align:center;"><?php 
		    if(empty($msg))
		    { 
		    
		    echo "<input type=submit name=Save value=Save>";
		    
		    }?></div>
		    </form>
   </td>
    <td width=130px>
    <?php include 'labrmenu.php';?>
    </td>
  </tr>
</table>
</body>
</html>
