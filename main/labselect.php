<?php 
include '../login/dbc.php';
page_protect();

$ptid = $_SESSION['patdesk'];
if(empty($ptid))
{
$ptid = $_SESSION['patlab'];
}

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$ptid' ");
while($row = mysqli_fetch_array($ptin))
{
$prefix = $row['prefix'];
$fname = $row['fname'];
$lname = $row['lname'];
}

$pttable = "pt_".$ptid;
$tmptable = "tmp_".$ptid;
$pin = mysqli_query($linkopd, "select MAX(id) from $pttable");
$rid = mysqli_fetch_array($pin);
$id = $rid[0]; 
/*
$pin = mysqli_query($linkopd, "select labid FROM $pttable where id = '$id' ");
while($rows = mysqli_fetch_array($pin))
{
 $oldlabid = $rows['labid'];
}
*/
$filter = mysqli_query($link, "select * from lab WHERE `L_Set` !='SETNAME' ORDER BY `id` ASC  ");

if ($_POST['todo'] == 'กรอง' ) 
{
	if($_POST['LSet'] != '' AND $_POST['LSpec'] !='' )
	{
		$filter = mysqli_query($link, "select * from lab WHERE L_Set='$_POST[LSet]' AND `L_Set` !='SETNAME' AND  `L_specimen` ='$_POST[LSpec]' ORDER BY `id` ASC ");	
	}	
	if($_POST['LSet'] != '' AND $_POST['LSpec'] =='' )
	{
		$filter = mysqli_query($link, "select * from lab WHERE L_Set='$_POST[LSet]' AND `L_Set` !='SETNAME'  ORDER BY `id` ASC");	
	}	
	if($_POST['LSpec'] !=''  AND  $_POST['LSet'] == '' )
	{
		$filter = mysqli_query($link, "select * from lab WHERE  `L_specimen` ='$_POST[LSpec]'  AND `L_Set` !='SETNAME' ORDER BY `id` ASC");	
	}
	if($_POST['set'] =='1' )
	{
		$filter = mysqli_query($link, "select * from lab WHERE  `L_Set` ='SETNAME' ORDER BY `id` ASC");	
	}
	
//	header("Location: prescriptnew.php");  
}

elseif ($_POST['todo'] == 'OK' ) 
{
    $i = $_POST['nofrow'];
    for($j=1;$j<=$i;$j++)
    {
    $inline = "lab".$j;
    $inlinex = $inlinex.$inline;
    $lbd = $_POST[$inline];
    if(!empty($lbd))
      { 
	if(empty($labx)){$labx = $_POST[$inline];}
	else {$labx = $labx.",".$_POST[$inline];}
      }
    }
    if(!empty($labx))
    {    
    /*
        if(!empty($oldlabid))
        {
	  $labx = $oldlabid.",".$labx;
        }
		mysqli_query($linkopd, "UPDATE $pttable SET
			`labid` = '$labx'
			WHERE `id` = '$id' LIMIT 1 ;") or die(mysqli_error($linkopd));
    */
//// go on to next step
// inset ptid to pt_to_lab table

	  $checkid = 0;
	  $result = mysqli_query($link, "SELECT * FROM pt_to_lab WHERE ptid ='$ptid' ");
	  while($rowl = mysqli_fetch_array($result))
	  {
		  // Get Patient information from the list to see doctor.
		  $fname = $rowl['fname'];
		  $lname = $rowl['lname'];
		  $checkid = $rowl['ptid'];
	  }
	  if(empty($checkid))
	  {
	  $sql_insert = "INSERT INTO `pt_to_lab` (`ptid`, `prefix`, `fname`, `lname`) VALUES ('$ptid','$prefix','$fname', '$lname')";
	  // Now insert Patient to "pt_to_drug" table
	  mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
	  }
  
/////
/* Create labtemp table*/
//	  $rowid = $_SESSION['ptrowid'];
	  $labtable = "labtmp_".$ptid."_".$id;
	  
	  $sql_insert = "CREATE TABLE IF NOT EXISTS `$labtable` (
					  `Labid` SMALLINT NOT NULL ,
					  `Labname` VARCHAR( 120 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
					  `Labresult` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
					  `price` SMALLINT NOT NULL ,
					  `prog` TINYINT NOT NULL ,
					  `saved` TINYINT NOT NULL ,
					  PRIMARY KEY (`Labid`)
					  ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;
					  ";
	  // Now insert Patient to "patient_id" table
	  mysqli_query($link, $sql_insert) or die("Create table Failed:" . mysqli_error($link));
	  $sqlt = "TRUNCATE `$labtable`";
	  mysqli_query($link, $sqlt) or die("Empty table Failed:" . mysqli_error($link));
	  //insert in to labwait table
	  $result = mysqli_query($link, "SELECT * FROM labwait WHERE ptid ='$ptid' ");
	  while($rowl = mysqli_fetch_array($result))
	  {
		  $checkid = $rowl['ptid'];
	  }
	  if(empty($checkid))
	  {
	  $sqllw = "INSERT into `labwait` (ptid,rid,tablename) value ('$ptid','$id','$labtable')";
	  mysqli_query($link, $sqllw) or die("Insertion Failed:" . mysqli_error($link));
	  }

	  
////insert lab id to labtemp
	  $i = $_POST['nofrow'];
	  for($j=1;$j<=$i;$j++)
	  {
	  $inline = "lab".$j;
	  $lbd = $_POST[$inline];
	  
	  //check for lab set and labname
	  $cond = ($lbd%100 == 0) AND $lbd<5000;//check of lab SETNAME
	      if($cond)
	      {
		$maxid = $lbd+100;
		$pin = mysqli_query($link, "select MAX(id) from lab WHERE `id`< $maxid");
		$rid = mysqli_fetch_array($pin);
		$maxid = $rid[0];
		$stepj = $maxid-$lbd;
		
	//	$msg[] = "maxid is ".$maxid." stepj ".$stepj;
		
		for($n=1;$n<=$stepj;$n++)
		{
		  $lbd = $lbd+1;
		  $labname = "";
		  $filter = mysqli_query($link, "select * from lab WHERE `id`= $lbd"); 
		  while ($row = mysqli_fetch_array($filter))
		  {
		    $labname = $row['S_Name']." [".$row['L_Name']."]";
		    $lprice = $row['price'];
		    $alllabprice = $alllabprice + $lprice;
		  }
		  if(!empty($labname))
		  {
		  $sql_insert = "INSERT INTO `$labtable` (`Labid`,`Labname`,`price`) VALUES ('$lbd','$labname','$lprice')";
		  // Now insert Patient to "pt_to_drug" table
		  mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
		  }
		}
	      }
	      elseif(!empty($lbd))
	      {
		  $filter = mysqli_query($link, "select * from lab WHERE `id`= $lbd"); 
		  while ($row = mysqli_fetch_array($filter))
		  {
		    $labname = $row['S_Name']." [".$row['L_Name']."]";
		    $lprice = $row['price'];
		    $alllabprice = $alllabprice + $lprice;
		  }
		  $sql_insert = "INSERT INTO `$labtable` (`Labid`,`Labname`,`price`) VALUES ('$lbd','$labname','$lprice')";
		  // Now insert Patient to "pt_to_drug" table
		  mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
	      }
	  }
	$msg[] = "Lab Requiest complete!";
	//ACCOUNT PART for lab price
	//update lab @ labid
	mysqli_query($link, "UPDATE  `$tmptable` SET `licprice` = '$alllabprice'") or die(mysqli_error($link));
	
    }
    if(empty($labx))
    {
	  $result = mysqli_query($link, "SELECT * FROM pt_to_lab WHERE ptid ='$ptid' ");
	  while($rowl = mysqli_fetch_array($result))
	  {
		  $checkid = $rowl['ptid'];
	  }
	  if(!empty($checkid))
	  {
	  $sql_insert = "DELETE FROM `pt_to_lab` WHERE `ptid` = '$ptid'";
	  // Now DELETE Patient to "pt_to_lab" table
	  mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
	  }
	  //rm from labwait
	  $sql_insert = "DELETE FROM `labwait` WHERE `ptid` = '$ptid'";
	  // Now DELETE Patient to "pt_to_lab" table
	  mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));

/////
/* Remove labtemp table*/
//	  $rowid = $_SESSION['ptrowid'];
	  $labtable = "labtmp_".$ptid."_".$id;
	  
      $sql_insert = "DROP TABLE $labtable";
      mysqli_query($link, $sql_insert) or die("Create table Failed:" . mysqli_error($link));
      
      	//ACCOUNT PART for lab price
	//update lab @ labid
	mysqli_query($link, "UPDATE  `$tmptable` SET `licprice` = '0'") or die(mysqli_error($link));

    }

  header("Location: labselect.php");   
}


?>

<!DOCTYPE html>
<html>
<head>
<title>Lab Order Form</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-1.3.2.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/checkthemall.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
<?php 
include '../libs/reloadopener.php';
include '../libs/autolsl.php';
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
<form method="post" action="labselect.php" name="formMultipleCheckBox1" id="formMultipleCheckBox">
<br><br>
<div class=pos_r_ls style="text-align: right;">
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
	  <tr><td>
				Lab Set:<input name=LSet id=lsn>
		</td><td>
				Lab Specimen:<input name=LSpec id=lsp>
		</td><td> 
			<input type='checkbox' name='set' value='1' >Lab Set
		</td><td> 
			<input type='submit' name='todo' value='กรอง' >
		</td><td> 
			<input type='submit' name='todo' value='OK' onClick='reloadParent();'/>
		</td><td> 
			<input type="submit" name="todo" value="Close" onClick="reloadParentAndClose();"/>
		</td>
	</tr> 
</table>
</div>
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

<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td>
		<h3 class="titlehdr">Lab ของ <?php echo $fname." ".$lname;?></h3>
			<table style="text-align: left; width: 100%; " border="1" cellpadding="2" cellspacing="2"  class="forms">
				<tbody>
					<tr><th><input name="checkAll" type="checkbox" id="checkAll" value="1" onclick="javascript:checkThemAll(this);" />SL</th><th >ชื่อ</th><th>Set</th><th>Specimen</th><th>Price</th></tr>
					<?php 
					$n = 0;
						while ($row = mysqli_fetch_array($filter))
						{
							$n = $n+1;
							echo "<tr><td>";
							echo "<input type=checkbox name=lab".$n." id='checkBoxes' value=".$row['id'];
							$labtable = "labtmp_".$ptid."_".$id;
							$ptin2 = mysqli_query($link, "select * from $labtable");
							while($row2 = mysqli_fetch_array($ptin2))
							{
							$labidx = $row2['Labid'];
							if($row['id']== $labidx) echo " checked";
							}
							echo " >";
							echo "</td><td>";
							echo $row['S_Name']." [".$row['L_Name']."]";
							echo "</td><td>";
							echo substr($row['L_Set'],5);
							echo "</td><td>";
							echo $row['L_specimen'];
							echo "</td><td>";
							echo $row['price'];
							echo "</td></tr>";
						}
					?>
				</tbody>
			</table>
  </td></tr>
</table>
<input type="hidden" name="nofrow" value="<?php echo $n;?>">
</form>
</body>
</html>