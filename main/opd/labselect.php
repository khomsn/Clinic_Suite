<?php 
include '../../config/dbc.php';

page_protect();
$err = array();
$msg = array();

/****** get pt id of lab request************/
$ptid = $_SESSION['patdesk'];
$Patient_id = $ptid;
include '../../libs/opdxconnection.php';

if(empty($ptid))
{
    $ptid = $_SESSION['patlab'];
}
/************************** get pt information*************************/
$ptin = mysqli_query($linkopd, "select * from patient_id where id='$ptid' ");
while($row = mysqli_fetch_array($ptin))
{
    $prefix = $row['prefix'];
    $fname = $row['fname'];
    $lname = $row['lname'];
}

$pttable = "pt_".$ptid;
$tmptable = "tmp_".$ptid;
$pin = mysqli_query($linkopdx, "select MAX(id) from $pttable");
$rid = mysqli_fetch_array($pin);
$presentrid = $rid[0]; 

/********************************************Filter to use with lab**************************/
$filter = mysqli_query($link, "select * from lab WHERE `L_Set` !='SETNAME' ORDER BY `id` ASC ");
$confirm = 1;

$_POST['nofrow'] = mysqli_num_rows($filter);

if($_POST['set'] =='1' )
{
	$filter = mysqli_query($link, "select * from lab WHERE  `L_Set` ='SETNAME' ORDER BY `id` ASC");
	$confirm = 0;
}
if ($_POST['todo'] == 'กรอง' ) 
{
    $confirm = 0;
    //check for search or select, if no search parameter then it is selection
    $namel = ltrim($_POST['LName']);
    $setl = ltrim($_POST['LSet']);
    $specl = ltrim($_POST['LSpec']);
    if(empty($namel) AND empty($setl) AND empty($specl) )
    {
        $_POST['todo'] = 'Select';
        goto SelectionOK;
    }
	if($_POST['LName'] != '' )
	{
		$filter = mysqli_query($link, "select * from lab WHERE (L_Name='$_POST[LName]' OR `S_Name` ='$_POST[LName]' ) AND `L_Set` !='SETNAME' ORDER BY `id` ASC ");
	}	
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
}
/******************************* Do request Lab ************************************/
SelectionOK:

if (($_POST['todo'] == 'Confirm') OR ($_POST['todo'] == 'Close') OR ($_POST['todo'] == 'Select'))
{
    /************ Create labtemp table****************/
	  $labtable = "labtmp_".$ptid."_".$presentrid;
	  
	  $sql_insert = "CREATE TABLE IF NOT EXISTS `$labtable` (
					  `Labid` SMALLINT NOT NULL ,
					  `Labname` VARCHAR( 120 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
					  `Labresult` VARCHAR( 500 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
					  `price` SMALLINT NOT NULL DEFAULT '0',
					  `prog` TINYINT NOT NULL DEFAULT '0',
					  `saved` TINYINT NOT NULL DEFAULT '0',
					  PRIMARY KEY (`Labid`)
					  ) ENGINE = InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
					  ";
	  // Now create table
	  mysqli_query($link, $sql_insert) or $err[]=("Create table Failed:" . mysqli_error($link));

    $i = $_POST['nofrow']; //no of row to process
    for($j=1;$j<=$i;$j++)
    {
    $inline = "lab".$j;
//    $inlinex = $inlinex.$inline;
    $lbd = $_POST[$inline];
    
    if(!empty($lbd)) //check for any line has been checked
      { 
        if(empty($labx))
        {
            $labx = $_POST[$inline];
        }
        else
        {
        $labx = $labx.",".$_POST[$inline];
        }
      }
    }
    
    if(!empty($labx)) // lab id has been requested
    {    
      /****** inset ptid to pt_to_lab table ******/

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
	  // Now insert Patient to "pt_to_lab" table
	  mysqli_query($link, $sql_insert) or $err[]=("Insertion to `pt_to_lab` Failed:" . mysqli_error($link));
	  }
  
	  /***********insert ptid in to labwait table *************/
	  $result = mysqli_query($link, "SELECT * FROM labwait WHERE ptid ='$ptid' ");
	  while($rowl = mysqli_fetch_array($result))
	  {
		  $checkid = $rowl['ptid'];
	  }
	  if(empty($checkid))
	  {
        $sqllw = "INSERT into `labwait` (`ptid`,`rid`,`tablename`) value ('$ptid','$presentrid','$labtable')";
        mysqli_query($link, $sqllw) or $err[]=("Insertion `labwait` Failed:" . mysqli_error($link));
	  }

    /****************insert lab id to $labtable **************************/
	  $i = $_POST['nofrow'];
	  for($j=1;$j<=$i;$j++)
	  {
        $inline = "lab".$j;
        $oldinline = "labold".$j;
        $lbd = $_POST[$inline];
        $lbdold = $_POST[$oldinline];
        
        /* remove unselected for previously selected*/
        if($lbdold!=$lbd)
        {
            $sql_delete = "DELETE FROM $labtable WHERE `Labid` = '$lbdold'";
            // Now DELETE Patient to "pt_to_lab" table
            mysqli_query($link, $sql_delete) or $err[]=("DELETE $labtable Failed:" . mysqli_error($link));
    
        }
        
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
                    //check for lab duplicate
                    $rs_duplicate = mysqli_query($link, "select count(*) as total from $labtable where Labname='$labname' ") or $err[]=(mysqli_error($link));
                    list($total) = mysqli_fetch_row($rs_duplicate);

                    if ($total == 0)
                    {
                        $sql_insert = "INSERT INTO `$labtable` (`Labid`,`Labname`,`price`) VALUES ('$lbd','$labname','$lprice')";
                        // Now insert Patient to "pt_to_drug" table
                        mysqli_query($link, $sql_insert) or $err[]=("Insertion `$labtable` Failed:" . mysqli_error($link));
                    }
                }
            }
        }
        // individual labid request check
        elseif(!empty($lbd))
        {
            $filter = mysqli_query($link, "select * from lab WHERE `id`= $lbd"); 
            while ($row = mysqli_fetch_array($filter))
            {
                $labname = $row['S_Name']." [".$row['L_Name']."]";
                $lprice = $row['price'];
                $alllabprice = $alllabprice + $lprice;
            }
            
            //check for lab duplicate
            $rs_duplicate = mysqli_query($link, "select count(*) as total from $labtable where Labname='$labname' ") or $err[]=(mysqli_error($link));
            list($total) = mysqli_fetch_row($rs_duplicate);

            if ($total == 0)
            {
                $sql_insert = "INSERT INTO `$labtable` (`Labid`,`Labname`,`price`) VALUES ('$lbd','$labname','$lprice')";
                // Now insert Patient to "pt_to_drug" table
                mysqli_query($link, $sql_insert) or $err[]=("Insertion `$labtable` Failed:" . mysqli_error($link));
            }
        }
	 }
	$msg[] = "Lab request complete!";
	//ACCOUNT PART for lab price
	//update lab @ labid
	mysqli_query($link, "UPDATE  `$tmptable` SET `licprice` = '$alllabprice'") or $err[]=(mysqli_error($link));
	
    }
    
/**************************** No lab requested************************/    
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
	  mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));
	  //rm from labwait
	  $sql_insert = "DELETE FROM `labwait` WHERE `ptid` = '$ptid' AND  `rid` = '$presentrid'";
	  // Now DELETE Patient to "pt_to_lab" table
	  mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));
	  }

      /********* Remove labtemp table******************/
	  $labtable = "labtmp_".$ptid."_".$presentrid;
	  
      $sql_insert = "DROP TABLE IF EXISTS $labtable";
      mysqli_query($link, $sql_insert) or $err[]=("Drop Table Failed:" . mysqli_error($link));
      
      	//ACCOUNT PART for lab price
      //update lab @ labid
      mysqli_query($link, "UPDATE  `$tmptable` SET `licprice` = '0'") or $err[]=(mysqli_error($link));

    }
   
$filter = mysqli_query($link, "select * from lab WHERE `L_Set` !='SETNAME' ORDER BY `id` ASC ");
$confirm = 1;
 // header("Location: labselect.php");   
}
$title = "::Lab Order Form::";
include '../../main/header.php';
echo "<script language=\"JavaScript\" type=\"text/javascript\" src=\"../../jscss/js/checkthemall.js\"></script>";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
include '../../libs/reloadopener.php';
include '../../libs/autolsl.php';
include '../../main/bodyheader.php';

?>
<script type='text/javascript'>
 $(document).ready(function() { 
   $('input[name=set]').change(function(){
        $('form').submit();
   });
  });
</script>
<form method="post" action="labselect.php" name="formMultipleCheckBox1" id="formMultipleCheckBox">
<br><br>
<div class=pos_r_ls style="text-align: right;">
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
	  <tr><td>
				Lab Name:<input name='LName' id=lbn autofocus>
		</td><td>
				Lab Set:<input name='LSet' id=lsn>
		</td><td>
				Lab Specimen:<input name='LSpec' id=lsp>
		</td><td width=100px> 
			<input type='radio' name='set' value='1' >Lab Set
		</td><td> 
			<input type='submit' name='todo' value='กรอง' >
		</td>
		<?php
		if($confirm) echo "<td><input type='submit' name='todo' value='Confirm' onClick='reloadParent();'/></td>";
        else echo "<td><input type='submit' name='todo' value='Select'/></td>";
        echo "<td><input type='submit' name='todo' value='Close' onClick='reloadParentAndClose();'/></td>";
        ?>
	</tr> 
</table>
</div>
<?php	
if(!empty($err))
{
    echo "<div class=\"error\">";
    foreach ($err as $e) {echo "* $e <br>";}
    echo "</div>";	
}
if(!empty($msg))
{
    echo "<div class=\"msg\">";
    foreach ($msg as $m) {echo "* $m <br>";}
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
							$oldchecked = 0;
							echo "<tr><td>";
							echo "<input type=checkbox name=lab".$n." id='checkBoxes' value=".$row['id'];
							$labtable = "labtmp_".$ptid."_".$presentrid;
							$ptin2 = mysqli_query($link, "select * from $labtable");
							while($row2 = mysqli_fetch_array($ptin2))
							{
							$labidx = $row2['Labid'];
							if($row['id']== $labidx)
                                {
                                    echo " checked";
                                    $oldchecked = 1;
                                }
							}
							echo " >";
							if($oldchecked)
							{
                            echo "<input type='hidden' name=labold".$n." value=".$row['id'].">";
							}
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
