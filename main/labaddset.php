<?php 
include '../login/dbc.php';
page_protect();


if($_POST['Next']=='Next') 
{
    /************ Lab Name CHECK ************************************
    This code does a second check on the server side if the email already exists. It 
    queries the database and if it has any existing email it throws user email already exists
    *******************************************************************/

    $rs_duplicate = mysqli_query($link, "select count(*) as total from lab where L_Name='$_POST[LLSName]'") or die(mysqli_error($link));
    list($total) = mysqli_fetch_row($rs_duplicate);

    if ($total > 0)
    {
	$err[] = "ERROR - LabSet Name [".$_POST['LLSName']."] already exists. Please check.";
	//header("Location: register.php?msg=$err");
	//exit();
    }
    /***************************************************************************/
    if(empty($err))
    {
	//get Set Name
	$_SESSION['LLSName'] = $_POST['LLSName'];
	$_SESSION['SLSName'] = $_POST['SLSName'];
	//get Set Number
	$_SESSION['SetNum'] = $_POST['Ssize'];
    }
	
}
if($_POST['Back']=='Back') 
{
	//get Set Name
	unset($_SESSION['LLSName']);
	unset($_SESSION['SLSName']);
	//get Set Number
	unset($_SESSION['SetNum']);
	/* Lab id start from 1xx to 99xx for lab set.
	* for individual lab start from 10000 to 32767.
	*/
//go on 
header("Location: labaddset.php");  

}

	/* Lab id start from 1xx to 99xx for lab set.
	* for individual lab start from 10000 to 32767.
	*/

$setnumber=$_SESSION['SetNum'];

$id = 10;
//search for id
$lin = mysqli_query($link, "SELECT id FROM lab WHERE id > 999 AND id < 5000 ORDER BY id ASC");

while($rows=mysqli_fetch_array($lin)) 
{
    $idnew = $rows['id']/100;
    $step = $idnew - $id;
    if($step <= 1) 
    {
	    $id = $idnew;
    }
    else { goto JPoint1;}
}

JPoint1:
if($idnew<10) {
$id = 1000;
}
else { $id = ceil($id)*100;}


if($_POST['Save']=='Save') 
{
  for($i=1;$i<=$setnumber;$i++)
      {
      
	$LLName =$_POST['L_Name'.$i];
	  
	/************ Lab Name CHECK ************************************
	This code does a second check on the server side if the email already exists. It 
	queries the database and if it has any existing email it throws user email already exists
	*******************************************************************/

	$rs_duplicate = mysqli_query($link, "select count(*) as total from lab where L_Name='$LLName' AND L_specimen ='$_POST[L_specimen]'") or die(mysqli_error($link));
	list($total) = mysqli_fetch_row($rs_duplicate);
	if ($total > 0)
	{
	    $err[] = "ERROR - LabSet Name [".$_POST['LLSName']."] already exists. Please check.";
	    //header("Location: register.php?msg=$err");
	    //exit();
	}
	/***************************************************************************/
      }
    if(empty($err))
    {
      $LSN = $id."-".$_SESSION['SLSName'];
      // Set up lab data in lab table
	      $sql_insert = "INSERT into `lab`
			      (`id`, `L_Name`, `S_Name`,`L_Set`, `L_specimen`,`Linfo`,`price`)
			  VALUES
			      ('$id','$_SESSION[LLSName]','$_SESSION[SLSName]','SETNAME','$_POST[L_specimen]','$_POST[Linfo]','$_POST[L_price]')";
      // Now insert Lab table for set name
      mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));

    
      for($i=1;$i<=$setnumber;$i++)
      {
      
	  $LLName =$_POST['L_Name'.$i];
	  $SLName =$_POST['S_Name'.$i];
	  $Lrunit =$_POST['Lrunit'.$i];
	  $norr =$_POST['normal_r'.$i];
	  $rmin =$_POST['r_min'.$i];
	  $rmax =$_POST['r_max'.$i];
	  $price = $_POST['L_price'.$i];
	  $id = $id +1;
	  
	  // Set up lab data in lab table
		  $sql_insert = "INSERT into `lab`
				  (`id`, `L_Name`, `S_Name`, `L_Set`, `L_specimen`, `Lrunit`, `normal_r`, `r_min`, `r_max`, `Linfo`, `price`)
			      VALUES
				  ('$id','$LLName','$SLName','$LSN','$_POST[L_specimen]','$Lrunit','$norr','$rmin','$rmax','$_POST[Linfo]','$price')";
	  // Now insert Lab table
	  mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
       }
	//go on 
	unset($_SESSION['LLSName']);
	unset($_SESSION['SLSName']);
	//get Set Number
	unset($_SESSION['SetNum']);

	header("Location: labaddset.php");  
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Lab Set</title>
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
	    เพิ่ม Lab Set ถ้าต้องการเพิ่มเป็นรายตัวให้เพิ่มใน Lab
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

	    <form action="labaddset.php" method="post" name="regForm" id="regForm" >
	    <?php 
	    if(empty($_SESSION['LLSName']))
	    {
	    echo "ชื่อชุด Lab: <input type=text name=LLSName class=required><br>";
	    echo "ชื่อย่อ Lab: <input type=text name=SLSName class=required><br>";
	    }
	    if(empty($_SESSION['SetNum']))
	      {
		    echo "จำนวนสมาชิก Lab: <input type='number' name='Ssize' class=required>";
	      }
	    if(!empty($_SESSION['LLSName']) AND !empty($_SESSION['SetNum']))
	    {
		echo "<table border=1 width=100% >";
		//$setnumber = ceil($setnumber/2);
		for($i=1;$i<=$setnumber;$i++)
		{
		  $j=$i+1;
		  echo  "<tr><td>ชื่อเต็ม </td><td><input type='text' tabindex=".$i." name='L_Name".$i."' class='required'></td>";
		  echo  "<td>ชื่อเต็ม </td><td>";
		  if($j<=$setnumber) echo "<input type='text' tabindex=".$j." name='L_Name".$j."' class='required'>";
		  echo "</td></tr>";
		  echo  "<tr><td>ชื่อย่อย </td><td><input type='text' tabindex=".$i."  name='S_Name".$i."'></td>";
		  if($j<=$setnumber) echo  "<td>ชื่อย่อย </td><td><input type='text' tabindex=".$j."  name='S_Name".$j."'>";
		  echo "</td></tr>";
		  echo  "<tr><td>หน่วยของ Lab </td><td><input type='text' tabindex=".$i."  name='Lrunit".$i."'></td>";
		  if($j<=$setnumber) echo  "<td>หน่วยของ Lab </td><td><input type='text' tabindex=".$j."  name='Lrunit".$j."'>";
		  echo  "</td></tr>";
		  echo  "<tr><td>ผลปกติ </td><td><input type='text' tabindex=".$i."  name='normal_r".$i."'></td>";
		  if($j<=$setnumber) echo  "<td>ผลปกติ </td><td><input type='text' tabindex=".$j."  name='normal_r".$j."'>";
		  echo "</td></tr>";
		  echo  "<tr><td>ค่าต่ำ </td><td><input type='text' tabindex=".$i."  name='r_min".$i."'></td>";
		  if($j<=$setnumber) echo  "<td>ค่าต่ำ </td><td><input type='text'  tabindex=".$j." name='r_min".$j."'>";
		  echo "</td></tr>";
		  echo  "<tr><td>ค่าสูง </td><td><input type='text' tabindex=".$i."  name='r_max".$i."'></td>";
		  if($j<=$setnumber) echo  "<td>ค่าสูง </td><td><input type='text'  tabindex=".$j." name='r_max".$j."'>";
		  echo "</td></tr>";
		  echo  "<tr><td>ราคาขายรายตัว </td><td><input type='number' tabindex=".$i."  name='L_price".$i."' size='7' min='0' step='1'></td>";
		  if($j<=$setnumber) echo  "<td>ราคาขายรายตัว </td><td><input type='number' tabindex=".$j." name='L_price".$j."' size='7' min='0' step='1'>";
		  echo "</td></tr>";
		  $i=$i+1;
		
		}
		echo "</table>";
	    echo "<table border=1 width=100%>";
		  echo  "<tr><td>ชนิดสิ่งส่งตรวจ </td><td><input type='text' name='L_specimen'></td></tr>";
		  echo  "<tr><td>ข้อมูลของ Lab </td><td><textarea name='Linfo' rows='5' cols='60'></textarea></td></tr>";
		  echo  "<tr><td>ราคาขายทั้งชุด </td><td><input type='number' name='L_price' size='7' min='0' step='1'></td></tr>";
	    echo "</table><br>";
	    }
	    ?>
	    <div style="text-align:center;">
	    <?php 
	    if($setnumber==0) 
	    {
	      echo "<input type='submit' name='Next' value='Next'>";
	    }
	    else 
	    {
	      echo "<input type='button' name='Back' value='Back'>";
	      echo "<input type='submit' name='Save' value='Save'>";
	    }
	    ?>
	    </div>
	    </form>
   </td>
    <td width=130px>
    <?php include 'labrmenu.php';?>
    </td>
  </tr>
</table>
</body>
</html>