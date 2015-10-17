<?php 
include '../login/dbc.php';
page_protect();

unset($_SESSION['LLSName']);
unset($_SESSION['SLSName']);
unset($_SESSION['SetNum']);

/* Lab id start from 1x-- to 49-- for lab set.
* for individual lab start from 50xx to 99xx.
*/
$id = 5000;
//search for id
$lin = mysqli_query($link, "SELECT id FROM lab WHERE id > 5000 ORDER BY id ASC");
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
if($idnew<5001) {
$id = 5001;
}
else { $id = $id + 1;}
 

if($_POST['Save']=='Save') 
{

/************ Lab Name CHECK ************************************
This code does a second check on the server side if the email already exists. It 
queries the database and if it has any existing email it throws user email already exists
*******************************************************************/

$rs_duplicate = mysqli_query($link, "select count(*) as total from lab where L_Name='$_POST[L_Name]' AND L_specimen='$_POST[L_specimen]'") or die(mysqli_error($link));
list($total) = mysqli_fetch_row($rs_duplicate);

if ($total > 0)
{
$err[] = "ERROR - Lab Name with Lab Specimen already exists. Please check.";
//header("Location: register.php?msg=$err");
//exit();
}
/***************************************************************************/
if(empty($err))
{
    // Set up lab data in lab table
	    $sql_insert = "INSERT into `lab`
			    (`id`, `L_Name`, `S_Name`, `L_specimen`, `Lrunit`, `normal_r`, `r_min`, `r_max`, `Linfo`, `price`, `ltr`)
			VALUES
			    ('$id','$_POST[L_Name]','$_POST[S_Name]','$_POST[L_specimen]','$_POST[Lrunit]','$_POST[normal_r]','$_POST[r_min]','$_POST[r_max]','$_POST[Linfo]','$_POST[L_price]','$_POST[labtime]')";
    // Now insert Lab table
    mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
   $msg[] = $_POST['L_Name']." with ".$_POST['L_specimen']." Specimen created successful.";
}

}

?>
<!DOCTYPE html>
<html>
<head>
<title>Lab</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
<?php 
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

		    เพิ่ม Lab เป็นรายตัว ถ้าต้องการเพิ่มเป็นชุดให้เพิ่มใน Lab Set
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

		    <form action="labadd.php" method="post" name="regForm" id="regForm" >
		    <table>
		    <tr><td>ชื่อเต็ม </td><td><input type="text" name="L_Name" class="required"></td></tr>
		    <tr><td>ชื่อย่อย </td><td><input type="text" name="S_Name"></td></tr>
		    <tr><td>ชนิดสิ่งส่งตรวจ </td><td><input type="text" name="L_specimen" class="required"></td></tr>
		    <tr><td>หน่วยของ Lab </td><td><input type="text" name="Lrunit"></td></tr>
		    <tr><td>ผลปกติ </td><td><input type="text" name="normal_r"></td></tr>
		    <tr><td>ค่าต่ำ </td><td><input type="text" name="r_min"></td></tr>
		    <tr><td>ค่าสูง </td><td><input type="text" name="r_max"></td></tr>
		    <tr><td>ข้อมูลของ Lab </td><td><textarea name="Linfo" rows="5" cols="60"></textarea></td></tr>
		    <tr><td>ราคาขาย </td><td><input type="number" name="L_price" size="7" min="0" step="1"></td></tr>
		    <tr><td>บันทึกเวลา </td><td><input type="checkbox" name="labtime" size="8" value="1"></td></tr>
		    </table><br>
		    <div style="text-align:center;"><input type="submit" name="Save" value="Save"></div>
		    </form>
   </td>
    <td width=130px>
    <?php include 'labrmenu.php';?>
    </td>
  </tr>
</table>
</body>
</html>