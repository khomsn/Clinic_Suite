<?php 

include '../login/dbc.php';
page_protect();
?>

<html>

<head>
<head>
<title>Patient to see doctor</title>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">

<link rel="stylesheet" href="../public/css/styles.css">
</head>

<body>

<!--add menu -->
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr> 
    <td width="160" valign="top">
<?php 
/*********************** MYACCOUNT MENU ****************************
This code shows my account menu only to logged in users. 
Copy this code till END and place it in a new html or php where
you want to show myaccount options. This is only visible to logged in users
*******************************************************************/
if (isset($_SESSION['user_id'])) {?>

<?php
include 'clinicmenu.php';
?>

<?php } 
/*******************************END**************************/
?>
 </td>
    <td width="" valign="top">
      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
	<tr> 
	      <td colspan="3">&nbsp;</td>
	</tr>
	<tr> 
<td width="732" valign="top">
	<h3 class="titlehdr">Thank you</h3>
	<h3><strong>ลงทะเบียน และ เข้าสู่การตรวจ เรียบร้อยแล้ว <br>Your registration is now complete!</strong></h3>
	<p align="right">&nbsp; </p>
	<?php
	$PID = $_SESSION['Patient_id'];
	//check id at any point of service..
	$result1 = mysqli_query($link, "SELECT id FROM pt_to_scr WHERE id = $PID");
	if(mysqli_num_rows($result1) != 0)
	{
	  $err[] = "ผู้ป่วยอยู่ในระบบการบริการแล้ว";
	  goto checkfound;
	}
	$result1 = mysqli_query($link, "SELECT id FROM pt_to_obs WHERE id = $PID");
	if(mysqli_num_rows($result1) != 0)
	{
	  $err[] = "ผู้ป่วยอยู่ในระบบการบริการแล้ว";
	  goto checkfound;
	}
	$result1 = mysqli_query($link, "SELECT id FROM pt_to_drug WHERE id = $PID");
	if(mysqli_num_rows($result1) == 0) 
	{
	  $result = mysqli_query($linkopd, "SELECT * FROM patient_id
	      WHERE id='$PID' ");
	    echo "<table border='1'>";
	    echo "<tr><th>เลขทะเบียน</th><th>ยศ</th><th>ชื่อ</th><th>นามสกุล</th></tr>";
	    // keeps getting the next row until there are no more to get
      while($row = mysqli_fetch_array($result))
      {
		    // Print out the contents of each row into a table
		    echo "<tr><th>"; 
		    echo $row['id'];
		    echo "</th><th>";
		    echo $row['prefix'];
		    echo "</th><th width=150>"; 
		    echo $row['fname'];
		    echo "</th><th width=150>"; 
		    echo $row['lname'];
		    echo "</th></tr>"; 
		    //get staff condition
		    $staffyn = $row['staff'];
		    
	    // Insert Patient to the list to see doctor.
		    $ID = $row['id']; $prefix= $row['prefix']; $F_Name = $row['fname']; $L_Name = $row['lname']; $ctzid = $row['ctz_id'];
		    if ($ctzid < 1000000000000) $msg[] = "กรุณา ใส่ เลขประจำตัวประชาชนด้วย";
	    //check is Patient in the pt_to_doc list? If not go on.
	    
	    $result1 = mysqli_query($link, "SELECT ID FROM pt_to_scr WHERE ID = $ID");
//	    $result1 = mysqli_query($link, "SELECT ID FROM pt_to_doc WHERE ID = $ID");
	    if(mysqli_num_rows($result1) == 0) 
	    {// row not found, do stuff...
		    $sql_insert = "INSERT INTO `pt_to_scr` (`ID`, `prefix`, `F_Name`, `L_Name`) VALUES ('$ID', '$prefix', '$F_Name', '$L_Name')";
		    // Now insert Patient to "patient_id" table
		    mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
		    $sql_insert = "CREATE TABLE IF NOT EXISTS `tmp_$ID` (
						      `preg` TINYINT NOT NULL ,
						    `csf` VARCHAR( 120 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						    `idtr1` SMALLINT NOT NULL ,
						    `tr1` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `trv1` SMALLINT NOT NULL ,
						      `tr1o1` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `tr1o1v` TINYINT NOT NULL ,
						      `tr1o2` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `tr1o2v` TINYINT NOT NULL ,
						      `tr1o3` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `tr1o3v` TINYINT NOT NULL ,
						      `tr1o4` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `tr1o4v` TINYINT NOT NULL ,
						      `trby1` SMALLINT NOT NULL ,
						      `idtr2` SMALLINT NOT NULL ,
						      `tr2` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `trv2` SMALLINT NOT NULL ,
						      `tr2o1` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `tr2o1v` TINYINT NOT NULL ,
						      `tr2o2` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `tr2o2v` TINYINT NOT NULL ,
						      `tr2o3` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `tr2o3v` TINYINT NOT NULL ,
						      `tr2o4` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `tr2o4v` TINYINT NOT NULL ,
						      `trby2` SMALLINT NOT NULL ,
						      `idtr3` SMALLINT NOT NULL ,
						      `tr3` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `trv3` SMALLINT NOT NULL ,
						      `tr3o1` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `tr3o1v` TINYINT NOT NULL ,
						      `tr3o2` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `tr3o2v` TINYINT NOT NULL ,
						      `tr3o3` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `tr3o3v` TINYINT NOT NULL ,
						      `tr3o4` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `tr3o4v` TINYINT NOT NULL ,
						      `trby3` SMALLINT NOT NULL ,
						      `idtr4` SMALLINT NOT NULL ,
						      `tr4` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `trv4` SMALLINT NOT NULL ,
						      `tr4o1` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `tr4o1v` TINYINT NOT NULL ,
						      `tr4o2` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `tr4o2v` TINYINT NOT NULL ,
						      `tr4o3` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `tr4o3v` TINYINT NOT NULL ,
						      `tr4o4` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `tr4o4v` TINYINT NOT NULL ,
						      `trby4` SMALLINT NOT NULL ,
						      `idrx1` SMALLINT NOT NULL ,
						      `rx1` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rxg1` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx1uses` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx1v` SMALLINT( 3 ) NOT NULL ,
						      `rx1sv` SMALLINT NOT NULL ,
						      `rxby1` SMALLINT NOT NULL ,
						      `idrx2` SMALLINT NOT NULL ,
						      `rx2` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rxg2` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx2uses` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx2v` SMALLINT( 3 ) NOT NULL ,
						      `rx2sv` SMALLINT NOT NULL ,
						      `rxby2` SMALLINT NOT NULL ,
						      `idrx3` SMALLINT NOT NULL ,
						      `rx3` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rxg3` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx3uses` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx3v` SMALLINT( 3 ) NOT NULL ,
						      `rx3sv` SMALLINT NOT NULL ,
						      `rxby3` SMALLINT NOT NULL ,
						      `idrx4` SMALLINT NOT NULL ,
						      `rx4` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rxg4` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx4uses` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx4v` SMALLINT( 3 ) NOT NULL ,
						      `rx4sv` SMALLINT NOT NULL ,
						      `rxby4` SMALLINT NOT NULL ,
						      `idrx5` SMALLINT NOT NULL ,
						      `rx5` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rxg5` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx5uses` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx5v` SMALLINT( 3 ) NOT NULL ,
						      `rx5sv` SMALLINT NOT NULL ,
						      `rxby5` SMALLINT NOT NULL ,
						      `idrx6` SMALLINT NOT NULL ,
						      `rx6` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rxg6` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx6uses` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx6v` SMALLINT( 3 ) NOT NULL ,
						      `rx6sv` SMALLINT NOT NULL ,
						      `rxby6` SMALLINT NOT NULL ,
						      `idrx7` SMALLINT NOT NULL ,
						      `rx7` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rxg7` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx7uses` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx7v` SMALLINT( 3 ) NOT NULL ,
						      `rx7sv` SMALLINT NOT NULL ,
						      `rxby7` SMALLINT NOT NULL ,
						      `idrx8` SMALLINT NOT NULL ,
						      `rx8` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rxg8` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx8uses` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx8v` SMALLINT( 3 ) NOT NULL ,
						      `rx8sv` SMALLINT NOT NULL ,
						      `rxby8` SMALLINT NOT NULL ,
						      `idrx9` SMALLINT NOT NULL ,
						      `rx9` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rxg9` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx9uses` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx9v` SMALLINT( 3 ) NOT NULL ,
						      `rx9sv` SMALLINT NOT NULL ,
						      `rxby9` SMALLINT NOT NULL ,
						      `idrx10` SMALLINT NOT NULL ,
						      `rx10` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rxg10` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx10uses` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
						      `rx10v` SMALLINT( 3 ) NOT NULL ,
						      `rx10sv` SMALLINT NOT NULL ,
						      `rxby10` SMALLINT NOT NULL ,
						      `licprice` smallint(6) NOT NULL,
						      `lcprice` smallint(6) NOT NULL,
						      `pricepolicy` tinyint(4) NOT NULL,
						      `medcert` tinyint(4) NOT NULL,
						      `course` tinyint(1) NOT NULL,
						      `prolab` tinyint(1) NOT NULL
						    ) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;
						    ";
		    // Now insert Patient to "patient_id" table
		    mysqli_query($link, $sql_insert) or die("Create table Failed:" . mysqli_error($link));
	    } 
    }
	echo "</table>";
	}
  else $err[] = "ผู้ป่วยอยู่ในระบบการบริการแล้ว";
  checkfound:
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
</td>
  </tr>
</table>
</body>
</html>
