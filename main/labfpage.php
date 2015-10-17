<?php 
include '../login/dbc.php';
page_protect();


$id = $_SESSION['patlab'];

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
$tmptable = "tmp_".$id;
$sqlm = mysqli_query($linkopd, "select MAX(id) from $pttable");
$rid = mysqli_fetch_array($sqlm);
$maxid = $rid[0];
$labtable = "labtmp_".$id."_".$maxid;
$allcpprice=0;

if($_POST['Save']=="Save")
{
  $nrow = $_POST['Rownum'];
    for($i=1;$i<=$nrow;$i++)
      {
	    $inid = "inid".$i;
	    $labid = $_POST[$inid];
	    $labresult = $_POST[$labid];
	  //stat part
	    //update volume in lab table
	    //get old volume
	    $myin = mysqli_query($link, "select * from lab WHERE id = $labid");
	    while($oldvol = mysqli_fetch_array($myin))
	    {
	      $oldvolume=$oldvol['volume'];
	      $ltr = $oldvol['ltr'];
	    }
	    
	    if (ltrim($labresult) !== '')
	    {
	      $sql_insert = "UPDATE `$labtable` SET 
					      `Labresult` = '$labresult'
				      WHERE `Labid` ='$labid' LIMIT 1 ; 
				      ";
	      // Now update labtable
	      mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
	    }

	  // RC".$labid.
		  $checkid = "RC".$labid;
		  
	    if($_POST[$checkid]=="1")
	    {	
		if (ltrim($labresult) === '') goto emptyresult;
		
		$myin = mysqli_query($link, "select * from $labtable WHERE Labid = '$labid' order by Labid ASC");
		while($compl = mysqli_fetch_array($myin))
		{
		// for stat
		  $labidin=$compl['Labid'];
		//lab price
		  $allcpprice = $compl['price'];
		  
		  $compl_name = $compl['Labname'];
		  $compl_rs = $compl['Labresult'];
		  if($ltr==1) $compl_rs = $compl_rs.' @('.date('Y-m-d H:i').')'; 
		  $labsaved = $compl['saved'];
	         }
	         
	         if($labsaved == 1) goto labhassaved;
	         
		  $newvolume = $oldvolume+1;
		  $sql_update = "UPDATE `lab` SET 
						  `volume` = '$newvolume'
					  WHERE `id` ='$labidin' LIMIT 1 ; 
					  ";
		  // Now update labtable
		  mysqli_query($link, $sql_update) or die("Update Failed:" . mysqli_error($link));
		  // Now in labstat table
		  // 1st check if this labid exist in this month, if yes-> update, no->insert 
		  //$sd = date("d");
		  $sm = date("m");
		  $sy = date("Y");
		  
		  $myin = mysqli_query($link, "select vol from labstat WHERE labid = '$labidin' AND MONTH(MandY) = '$sm' AND YEAR(MandY) = '$sy' ");
		  $cvolume = mysqli_fetch_array($myin);
		  $oldvolume =$cvolume[0];
		  
		  if(empty($oldvolume)) // not yet record
		  {
		      $sql_insert = "INSERT into `labstat`
			      (`labid`,`MandY`,`vol`)
			  VALUES
			      ('$labidin',now(),'1')"; //order only 1 test each time
		      // Now insert Patient to "patient_id" table
		      mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
		  }
		  else
		  {
		  $newvol = $oldvolume+1;
		  $sql_update = "UPDATE `labstat` SET 
						  `vol` = '$newvol'
					  WHERE `labid` ='$labidin'  AND MONTH(MandY) = '$sm' AND YEAR(MandY) = '$sy' LIMIT 1 ; 
					  ";
		  // Now update labtable
		  mysqli_query($link, $sql_update) or die("Insertion Failed:" . mysqli_error($link));
		  }
		//}
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
		  //update to prevent double insertion
		  {
		    $sql_insert = "UPDATE `$labtable` SET 
						    `saved` = '1'
					    WHERE `Labid` ='$labid' LIMIT 1 ; 
					    ";
		    // Now update labtable
		    mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
		  }
		  //labid has been saved 
		  labhassaved :
		  //rawmat cut for lab
		  
		  include '../libs/rawmatcutforlab.php';
		  
		//Now remove lab id request from table
		    $rmlab = "DELETE FROM  `$labtable` WHERE `Labid` = '$labid'";
		//	  $sqlt = "DROP TABLE `$labtable`";
		    mysqli_query($link, $rmlab) or die("DELETE record Failed:" . mysqli_error($link));
		    
		  //check lcprice for tmp table
		  $pr1 = mysqli_query($link, "SELECT * from $tmptable");
		  while($pr2 = mysqli_fetch_array($pr1))
		  {
		    $lincp = $pr2['licprice'];
		    $lcp = $pr2['lcprice'];
		  }
		  $lincp = $lincp - $allcpprice;
		  $lcp = $lcp + $allcpprice;
		  //update lab @ labid
		  mysqli_query($link, "UPDATE  `$tmptable` SET `licprice` = '$lincp', `lcprice` = '$lcp'") or die(mysqli_error($link));
	       }

	    // empty result do nothing
		emptyresult:
	    
	  }
if($_POST['complete']=="1")
{
    $myla = mysqli_query($link, "select saved from $labtable");
    while($labinfo = mysqli_fetch_array($myla))
    {
      $checksaved = $labinfo['saved'];
      if($checksaved == "0") goto notcomplete;
    }

    //Now remove Pt request table
	$sqlt = "DROP TABLE `$labtable`";
	mysqli_query($link, $sqlt) or die("Empty table Failed:" . mysqli_error($link));
	
	// Now Delete Patient from "pt_to_lab" table
	mysqli_query($link, "DELETE FROM pt_to_lab WHERE ptid = '$id' ") or die(mysqli_error($link));
				
	// Now Delete Patient from "labwait" table
	mysqli_query($link, "DELETE FROM labwait WHERE ptid = '$id' ") or die(mysqli_error($link));

}
    notcomplete:
//header("Location: labselect.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/checkthemall.js"></script>
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
      <h6>Lab ที่ตรวจวันนี้ :</h6>
	<form method="post" action="labfpage.php" name="formMultipleCheckBox1" id="formMultipleCheckBox">
	    <table style="background-color: rgb(255, 204, 153); width: 100%; text-align: center;
			    margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">									
		    <tr>
			    <th>No</th><th >ชื่อ</th><th>Set</th><th>Specimen</th><th>Result</th><th>Unit</th><th>Normal#</th>
			    <th>Min</th><th>Max</th><th><input name="checkAll" type="checkbox" id="checkAll" value="1" onclick="javascript:checkThemAll(this);" />RC</th>
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
			    echo "<input type='text' tabindex='".$n."' name=".$labid." value='";
			    echo $row['Labresult']."'>";
			    echo "</td><td>";
			    echo $labunit;
			    echo "</td><td>";
			    echo $labnomr;
			    echo "</td><td>";
			    echo $labmin;
			    echo "</td><td>";
			    echo $labmax;
			    echo "</td><td>";
			    echo "<input type=checkbox  id='checkBoxes' name=RC".$labid." value='1'>";
			    echo "</td></tr>";
		    }	
		   ?>
	    </table>
	    <div style="text-align:center;">ถ้าลงข้อมูลครบแล้วทุกตัวและต้องการปิด Lab ของวันนี้ให้เลือก "Completed" ด้วย แต่ถ้าต้องการลงข้อมูลอย่างเดียว กด "Save" เท่านั้น<br>ถ้าลงข้อมูลของ Lab เสร็จแล้ว แต่ละตัวให้ Check ที่ ช่อง RC ด้วย ค่า Lab จะไม่ถูกลบ ถ้า มีการสั่ง Lab เพิ่มเติม</div>
	    <table width=100%>
	    <tr><td><input type="checkbox" name="complete" value="1" onclick="javascript:checkThemAll(this);">Completed</td><td><input type="submit" name="Save" value="Save"></td></tr>
	    </table><input type="hidden" name="Rownum" value="<?php echo $n;?>">
	</form>
	</div>
</body></html>