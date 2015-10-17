<?php 
include '../login/dbc.php';
page_protect();


$id = $_SESSION['ptid'];

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;

$maxid = $_SESSION['rid'];
$ld = mysqli_fetch_row(mysqli_query($linkopd, "select date from $pttable where id='$maxid' "));
$date = $ld[0];

$labtable = $_SESSION['ltb'];//"labtmp_".$id."_".$maxid;

if($_POST['Save']=="Save")
{
$nrow = $_POST['Rownum'];
    for($i=1;$i<=$nrow;$i++)
      {
      $inid = "inid".$i;
      $labid = $_POST[$inid];
      $labresult = $_POST[$labid];
      
 	$sql_insert = "UPDATE `$labtable` SET 
					`Labresult` = '$labresult'
				WHERE `Labid` ='$labid' LIMIT 1 ; 
				";
	// Now update labtable
	mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
     
      }
   if($_POST['complete']=="1")
   {
      // for stat
      $i=1;
      //
      $myin = mysqli_query($link, "select * from $labtable order by Labid ASC");
      while($compl = mysqli_fetch_array($myin))
      {
      // for stat
         $Labidin[$i]=$compl['Labid'];
         $i=$i+1;
      //
	if(empty($compl_name)) $compl_name = $compl['Labname'];
	else $compl_name = $compl_name.";".$compl['Labname'];
	if(empty($compl_rs)) $compl_rs = $compl['Labresult'];
	else $compl_rs = $compl_rs.";".$compl['Labresult'];
      }
      //stat part
      for($j=1;$j<$i;$j++)
      {
	//update volume in lab table
	//get old volume
	$myin = mysqli_query($link, "select volume from lab WHERE id = $Labidin[$j]");
	while($oldvol = mysqli_fetch_array($myin))
	{
	$oldvolume = $oldvol['volume'];
	}
	$newvol = $oldvolume+1;
	
	$sql_update = "UPDATE `lab` SET 
					`volume` = '$newvol'
				WHERE `id` ='$Labidin[$j]' LIMIT 1 ; 
				";
	// Now update labtable
	mysqli_query($link, $sql_update) or die("Insertion Failed:" . mysqli_error($link));
	// Now in labstat table
	// 1st check if this labid exist in this month, if yes-> update, no->insert 
	//$sd = date("d");
	$sm = date("m");
	$sy = date("Y");
	
	$myin = mysqli_query($link, "select vol from labstat WHERE labid = $Labidin[$j] AND MONTH(MandY) = '$sm' AND YEAR(MandY) = '$sy' ");
	$cvolume = mysqli_fetch_array($myin);
	$oldvolume =$cvolume[0];
	
	if(empty($oldvolume)) // not yet record
	{
	    $sql_insert = "INSERT into `labstat`
		    (`labid`,`MandY`,`vol`)
		VALUES
		    ('$Labidin[$j]',now(),'1')"; //order only 1 test each time
	    // Now insert Patient to "patient_id" table
	    mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
	}
	else
	{
	$newvol = $oldvolume+1;
	$sql_update = "UPDATE `labstat` SET 
					`vol` = '$newvol'
				WHERE `labid` ='$Labidin[$j]'  AND MONTH(MandY) = '$sm' AND YEAR(MandY) = '$sy' LIMIT 1 ; 
				";
	// Now update labtable
	mysqli_query($link, $sql_update) or die("Insertion Failed:" . mysqli_error($link));
	}
      }
      //
      $myla = mysqli_query($linkopd, "select * from $pttable Where `id` ='$maxid'");
      while($labinfo = mysqli_fetch_array($myla))
      {
	$Labname=$labinfo['labid'];
	$Labresult=$labinfo['labresult'];
      }
      if(!empty($Labname)) $compl_name = $Labname.";".$compl_name;
      if(!empty($Labresult)) $compl_rs = $Labresult.";".$compl_rs;
      
	$sql_insert = "UPDATE `$pttable` SET 
					`labid` = '$compl_name',
					`labresult` = '$compl_rs'
				WHERE `id` ='$maxid' LIMIT 1 ; 
				";
	// Now update pttable
	mysqli_query($linkopd, $sql_insert) or die("Insertion Failed:" . mysqli_error($linkopd));
      
      //Now remove Pt request table
      	  $sqlt = "DROP TABLE `$labtable`";
	  mysqli_query($link, $sqlt) or die("Empty table Failed:" . mysqli_error($link));
	  // Now Delete Patient from "pt_to_lab" table if present
	  mysqli_query($link, "DELETE FROM pt_to_lab WHERE ptid = '$id' ") or die(mysqli_error($link));
	  // Now Delete Patient from "labwait" table
	  mysqli_query($link, "DELETE FROM labwait WHERE ptid = '$id' ") or die(mysqli_error($link));
      //rawmat cut process start
      //rawmat cut process end
   }
header("Location: labwait.php");
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Laboratory List</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<link rel="stylesheet" href="../public/css/styles.css">
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
      <h6>Lab ที่ตรวจวันที่ : <?php echo $date?></h6>
	<form method="post" action="labrfpage.php" name="regForm" id="regForm">
	    <table style="background-color: rgb(255, 204, 153); width: 100%; text-align: center;
			    margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">									
		    <tr>
			    <th>No</th><th >ชื่อ</th><th>Set</th><th>Specimen</th><th>Result</th><th>Unit</th><th>Normal#</th>
			    <th>Min</th><th>Max</th>
		    </tr>
		    <?php 
		    $n=0;
		    $ptin = mysqli_query($link, "select * from $labtable ");
		    while ($row = mysqli_fetch_array($ptin))
		    { 
			    $n=$n+1;
			    $labid = $row['Labid'];
			    echo "<tr><td>";
			    echo $n;
			    echo "<input type=hidden name=inid".$n." value=".$labid.">";
			    echo "</td><td style='text-align:left;'>";
			    echo $row['Labname'];
			    $lab = mysqli_query($link, "select * from lab where id=$labid");
			    while ($labinfo = mysqli_fetch_array($lab))
			    {
			    $labset = $labinfo['L_Set'];
			    $labspec = $labinfo['L_specimen'];
			    $labnomr = $labinfo['normal_r'];
			    $labunit = $labinfo['Lrunit'];
			    $labmin = $labinfo['r_min'];
			    $labmax = $labinfo['r_max'];
			    }
			    $labset =  substr($labset,5);
			    
			    echo "</td><td>";
			    echo $labset;
			    echo "</td><td>";
			    echo $labspec;
			    echo "</td><td>";
			    echo "<input type='text' name=".$labid." value='";
			    echo $row['Labresult']."'>";
			    echo "</td><td>";
			    echo $labunit;
			    echo "</td><td>";
			    echo $labnomr;
			    echo "</td><td>";
			    echo $labmin;
			    echo "</td><td>";
			    echo $labmax;
			    echo "</td></tr>";
		    }	
		   ?>
	    </table>
	    <div style="text-align:center;">ถ้าลงข้อมูลครบแล้วทุกตัวและต้องการปิด Lab ของวันนี้ให้เลือก "Completed" ด้วย แต่ถ้าต้องการลงขอมูลอย่างเดียว กด "Save" เท่านั้น</div>
	    <table width=100%>
	    <tr><td><input type="checkbox" name="complete" value="1">Completed</td><td><input type="submit" name="Save" value="Save"></td></tr>
	    </table><input type="hidden" name="Rownum" value="<?php echo $n;?>">
	</form>
	</div>
    
    
   </td>
    <td width=130px>
    <?php include 'labrmenu.php';?>
    </td>
  </tr>
</table>
</body></html>