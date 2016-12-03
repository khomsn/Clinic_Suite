<?php 
include '../login/dbc.php';
page_protect();

$id = $_SESSION['patdesk'];
$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
$tmp = "tmp_".$id;
$today = date("Y-m-d");
$extid = mysqli_fetch_array(mysqli_query($link, "SELECT extid FROM `parameter`"));
$extid = $extid[0];
$pin = mysqli_query($linkopd, "select MAX(id) from $pttable");
$rid = mysqli_fetch_array($pin);
$_SESSION['mrid'] = $rid[0]; //Set to search for previous record for drug  and Treatment

for($i=1;$i<=10;$i++)
{
    $idrx[$i]="idrx".$i;
    $rx[$i] = "rx".$i;
    $rxg[$i] = "rxg".$i;
}

$tempin = mysqli_query($link, "select * from $tmp ");
while ($row = mysqli_fetch_array($tempin))
{
    for($t10=1;$t10<=10;$t10++)
    {
      $gdid[$t10]=$row[$rxg[$t10]];
    }
}
$ddindex=1;
for($i=1;$i<$t10;$i++)
{
  $cho = mysqli_query($linkcm, "select * from druggeneric where name = '$gdid[$i]' ");
  while($int[$i] = mysqli_fetch_array($cho))
  {
      $itid[$i] = $int[$i]['id'];
      $iti[$i] = $int[$i]['dinteract'];
      if(!empty($iti[$i]))
      {
	  $nci = substr_count($iti[$i], ';');
	  //$str = 'hypertext;language;programming';
	  $charsl = preg_split('/;/', $iti[$i]);
      
	for($b=0;$b<=$nci;$b++)
	{
	  $charslnew[$b] = str_replace('[', '', $charsl[$b]);
	  $charslnew[$b] = str_replace(']', '', $charslnew[$b]);
	  $chars = preg_split('/,/', $charslnew[$b]);
	  $did[$i][$ddindex]=$chars[0];
	  $dil[$i][$ddindex]=$chars[1];
	  $ddindex = $ddindex+1;
	}
      } 
  } 
}

for($i=1;$i<$t10;$i++)
{
  for($j=1;$j<$t10;$j++)
  {
    for($l=1;$l<$ddindex;$l++)
    {
      if($itid[$i]==$did[$j][$l])
      {
	//interact with $itid[$j] with level = $dil[$j][$l]
	if(empty($tempddil[$i]))
	{
	  $tempddil[$i] = $dil[$j][$l];
	  if(!empty($tempddil[$i])) $tempddi[$i] = $j;
	}
	else 
	{
	  $tempddil[$i] = $tempddil[$i].','.$dil[$j][$l];
	  $tempddi[$i] = $tempddi[$i].','.$j;
	}
      }
    }
  }
}

$ordertable = "doctemplate_".$_SESSION['sflc'];

include '../libs/price.php';

for($i=1;$i<=10;$i++)
{
	if($_POST[$i] == 'ลบ')
	{
		//update reserve volume at drug_id
		$redid = $_POST['hidx'.$i];
		
		$resd = mysqli_query($link, "select * from drug_id where id='$redid'");
		while ($resdf = mysqli_fetch_array($resd))
		{
		$oldvol = $resdf['volreserve'];
		
		$rvolnew = $oldvol - $_POST['oldvl'.$i];
		// set reserve to 0 if less than 0
		if($rvolnew < 0) $rvolnew = 0;
		
		mysqli_query($link, "UPDATE drug_id SET
			`volreserve` = '$rvolnew' WHERE `id` ='$redid' LIMIT 1 ;
			") or die(mysqli_error($link));
		}
		
		$rar = $i;
	}
}
if(!empty($rar))
{
    for($i=$rar;$i<=10;$i++)
    {
        $n=$i+1;
        
        $upd = mysqli_fetch_array(mysqli_query($link, "select * from $tmp"));
        //set data value
		if($i<10)
		{
		$idrx = $upd['idrx'.$n];
		$rx= $upd['rx'.$n];
		$rxg= $upd['rxg'.$n];
		$usd = $upd['rx'.$n.'uses'];
		$vld = $upd['rx'.$n.'v'];
		$rxbyd = $upd['rxby'.$n];
		//set header
		$us = "rx".$i."uses";
		$vl = "rx".$i."v";
		$rxby = "rxby".$i;
		mysqli_query($link, "UPDATE $tmp SET
			`idrx$i` = '$idrx',
			`rx$i` = '$rx',
			`rxg$i` = '$rxg',
			`$us` = '$usd',
			`$vl` = '$vld',
			`$rxby` = '$rxbyd'
			") or die(mysqli_error($link));
        }
        if($i==10)
        {
		$us = "rx".$i."uses";
		$vl = "rx".$i."v";
		$rxby = "rxby".$i;
		mysqli_query($link, "UPDATE $tmp SET
			`idrx$i` = '0',
			`rx$i` = '',
			`rxg$i` = '',
			`$us` = '',
			`$vl` = '0',
			`$rxby` = '0'
			") or die(mysqli_error($link));       
        }
    }
        // go on to other step
		header("Location: prescript.php"); 	
    
}
if($_POST['register'] == 'บันทึก') 
{ 
//UPDATE $tmp 
	for($i=1;$i<=10;$i++)
	{
		$us = "rx".$i."uses";
		$vl = "rx".$i."v";
		$usp = $_POST['use'.$i];
		//check if have $ordertable for this staff if not skip
		if($_SESSION['sflc'])
		{
		$sql="SELECT * FROM $ordertable WHERE scode = '$usp'";
		$result = mysqli_query($link,$sql) or die(mysqli_error($link));
		
		if($result)
		{
			while($row=mysqli_fetch_array($result))
			{
				$usp = $row['textconv']."\n";
			}
		}
		}
		$vlp = $_POST['vl'.$i]; //new volume to update
		mysqli_query($link, "UPDATE $tmp SET
			`$us` = '$usp',
			`$vl` = '$vlp'
			") or die(mysqli_error($link));
		//update reserve volume at drug_id check if new volume = old volume
		$oldvlp = $_POST['oldvl'.$i]; //$_SESSION['prxv'.$i];
		if($oldvlp != $vlp)
		{
		$redid = $_POST['hidx'.$i];//$_SESSION['pidrx'.$i];
		$resd = mysqli_query($link, "select * from drug_id where id='$redid'");
		while ($resdf = mysqli_fetch_array($resd))
		{
		$oldrvol = $resdf['volreserve'];
		}
		$rvolnew = $oldrvol + $vlp - $oldvlp;
		//$rvolnew 
		if($rvolnew <=0 ) $rvolnew =0;
		
		mysqli_query($link, "UPDATE drug_id SET
			`volreserve` = '$rvolnew' WHERE `id` ='$redid' LIMIT 1 ;
			") or die(mysqli_error($link));
		}
		// go on to other step
	}

//
          if (ltrim($_POST['inform']) === '') $_POST['inform'] = '';
	  mysqli_query($linkopd, "UPDATE $pttable SET
				  `inform` = '$_POST[inform]' 
				  WHERE `id` = '$rid[0]' 
				  ") or die(mysqli_error($linkopd));

//complete save
	  $checkid = 0;
	  $result = mysqli_query($link, "SELECT * FROM pt_to_doc WHERE ID ='$id' ");
	  while($row = mysqli_fetch_array($result))
	  {
		  // Get Patient information from the list to see doctor.
		  $prefix = $_SESSION['pf'];
		  $fname = $_SESSION['fn'];
		  $lname = $_SESSION['ln'];
		  $checkid = $row['ID'];
	  }
	    if($id == $extid)// Please change ID Here//
	    {
	      $sql_insert = "INSERT INTO `pt_to_drug` (`id`, `prefix`, `fname`, `lname`) VALUES ('$id', '$prefix', '$fname', '$lname')";
	      // Now insert Patient to "pt_to_drug" table
	      mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
	      // Now Delete Patient from "pt_to_doc" table
	      mysqli_query($link, "DELETE FROM pt_to_doc WHERE ID = '$id' ") or die(mysqli_error($link));
	      // go on to other step
	      header("Location: ptlpage.php");
	      exit();
	     }
	  if($checkid != 0)
	  {
	      $sql_insert = "INSERT INTO `pt_to_obs` (`id`, `prefix`, `fname`, `lname`) VALUES ('$id', '$prefix', '$fname', '$lname')";
	      // Now insert Patient to "pt_to_drug" table
	      mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
	  }
	  // Now Delete Patient from "pt_to_doc" table
	  mysqli_query($link, "DELETE FROM pt_to_doc WHERE ID = '$id' ") or die(mysqli_error($link));

	  // go on to other step
	  header("Location: appointment.php"); 
//	  header("Location: prescriptconfirm.php");  
}

?>

<!DOCTYPE html>
<html>
<head>
<title>สั่งยา</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-1.11.2.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
	<link rel="stylesheet" href="../public/js/jquery-ui-themes-1.11.4/themes/smoothness/jquery-ui.css">
<?php 
include '../libs/popup.php';
?>
<link rel="stylesheet" href="../public/js/jquery-ui-themes-1.11.4/themes/smoothness/jquery-ui.css">
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
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="main">
  <tr><td>
  <h3 class="titlehdr">สั่งยา และ การรักษา</h3>
  <form method="post" action="prescript.php" name="regForm" id="regForm">
	  <table style="text-align: left; width: 100%; height: 413px;" border="0" cellpadding="0" cellspacing="0"  class="forms">
		  <tbody>
			  <tr>
				  <td style="width: 80%; vertical-align: middle;">
					  <div style="text-align: center;">
					  <big><big>ชื่อ: &nbsp; 
					  <?php
					  while ($row_settings = mysqli_fetch_array($ptin))
					  {
						  echo $_SESSION['pf'] = $row_settings['prefix'];
						  echo "&nbsp;"; 
						  echo $_SESSION['fn'] = $row_settings['fname'];
						  echo "&nbsp; &nbsp; &nbsp;"; 
						  echo $_SESSION['ln'] = $row_settings['lname'];
					  }				
					  ?>
					  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
					  Diag : 
					  <?php 
					  $today = date("Y-m-d");
					  $ptin = mysqli_query($linkopd, "select * from $pttable where id = '$rid[0]' ");
					  while ($row_settings = mysqli_fetch_array($ptin))
					  {
						  echo $row_settings['ddx']; 
						  $inform = $row_settings['inform']; 
						  $_SESSION['weight']=$row_settings['weight'];
					  }	
					  ?>
					  </big></big>
					  
				  </td>
			  </tr>	
			  <tr>
				  <td>
					  </div>
					  <div style="text-align: center;">
					  ข้อมูล แนะนำ:<br>
					  <textarea cols="80" rows="2" type="text" name="inform" ><?php 
						  echo  $inform; 
						  ?></textarea>
						  <hr style="width: 80%; height: 1px;">
			  <?php 
			  if ($_SESSION['user_accode']%11==0 OR $_SESSION['user_accode']%7==0 OR $_SESSION['patdesk']==$extid)
			  {
			  ?>
			  <a HREF="drugorder.php" onClick="return popup(this,'name','950','600','yes')" ><big>Order</big></a> : 
			  <a HREF="prescriptold.php" onClick="return popup(this,'name','800','600','yes')" >(ยาเก่า)</a> 
			  <?php } else {?><a HREF="prodorder.php" onClick="return popup(this,'name','800','600','yes')" >สั่งผลิตภัณฑ์</a><a 
			  <?php }?><br>
					  <table style="background-color: rgb(255, 204, 153); width: 80%; text-align: center;
							  margin-left: auto; margin-right: auto;" border="1" cellpadding="0" cellspacing="0">									
						  <tr>
							  <th width = 10 >No</th><th width = 250px >ชื่อ+ขนาด</th><th>ชื่อสามัญ</th><th>วิธีใช้</th><th width =50px>จำนวน</th><th>ราคา</th><th>DDI</th><th>DDIL</th><th width = 10>ลบ</th>
						  </tr>
						  <input name="register" value="บันทึก" type="submit" style="visibility: hidden;">
						  <?php 
						  $ptin = mysqli_query($link, "select * from $tmp ");
						  while ($row = mysqli_fetch_array($ptin))
						  {
							  for($i = 1;$i<=10;$i++)
							  {
							  $idrx="idrx".$i;
							  $rx = "rx".$i;
							  $rxg = "rxg".$i;
							  $us = "rx".$i."uses";
							  $rxv = "rx".$i."v";
							  $rxsv = "rx".$i."sv";
							  $rxby = "rxby".$i;
							  echo "<tr><td>";
							  echo $i;
							  echo "</td><td>";
							  echo $row[$rx];
	$din=$row[$idrx];
	echo "<input type=hidden name=hidx$i value=$din>"; //drug id old one
	$did = mysqli_query($link, "select * from drug_id where id=$din");
	while($ck = mysqli_fetch_array($did))
	{
	 $ckeckt=$ck['typen'];
	 $rsvol[$i] = $ck['volreserve'];//get reserve volume
	 $inv[$i] = $ck['volume'];//get inventory volume
	 $prolab = $ck['candp'];//check program lab
	 if($prolab == 2)
	 {
	  	mysqli_query($link, "UPDATE $tmp SET  `prolab` = '1' ") or die(mysqli_error($link));
	  	$_SESSION['prolab']=1;
	 }
	}
	if(($ckeckt == "ยาฉีด") AND ($row[$rxby]==0) )
	{
	 $_SESSION['tr']=1;
	}
	//reset $ckeckt
	$ckeckt="";
							echo "</td><td>";
							echo $row[$rxg];
							echo "</td>";
							echo "<td>";
							echo "<input type=search size=40% name=use$i id=ordertxt$i value='$row[$us]'>";
							echo "</td>";
							echo "<td>";
							echo "<input type=number class=typenumber min=0 max=".($inv[$i]-$rsvol[$i]+$row[$rxv])." step=1 name=vl$i value='$row[$rxv]'>";
							echo "<input type=hidden name=oldvl$i value='$row[$rxv]'>";//this is old volume 
							echo "</td>";
							echo "<td>";
							//echo $DP.$i;
							echo $price[$i];
							echo "</td><td>";
							echo $tempddi[$i];
							echo "</td><td>";
							echo $tempddil[$i];
							echo "</td><td><input type ='submit' name='";
							echo $i;
							echo "' value='ลบ'></td></tr>";
							}
						}
echo "<tr><td></td><td>รวม Lab+Treatment+Drug</td><td>".$alllabprice."</td><td>".$TMPrice."</td><td></td><td>".($allprice+$alllabprice)."</td><td></td><td></td><td></td></tr>";
						?>
					</table>		
					</div>
				</td>
			</tr>
			<tr>
			<td>
				<div style="text-align: center;"><input name="register" value="บันทึก" type="submit"></div>
			</td>
			</tr>
		</tbody>
		</table>
	</form>
<!--menu end-->
</td>
</tr>
</table>
<?php 
if(empty($_SESSION['tr']))
{
    // Now Delete Patient from "pt_to_treatment" table
    mysqli_query($link, "DELETE FROM pt_to_treatment WHERE ptid = '$id' ") or die(mysqli_error($link));
    unset($_SESSION['tr']);
}
else
{
$checkid = mysqli_fetch_row(mysqli_query($link, "SELECT * FROM pt_to_treatment WHERE ptid ='$id' "));
$check = $checkid[0];
if(empty($check))
{
$sql_insert = "INSERT INTO `pt_to_treatment` (`ptid`, `prefix`, `fname`, `lname`) VALUES ('$id', '$_SESSION[pf]', '$_SESSION[fn]', '$_SESSION[ln]')";
// Now insert Patient to "pt_to_drug" table
mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
}
}
if(empty($_SESSION['prolab']))
{
  mysqli_query($link, "UPDATE $tmp SET  `prolab` = '0' ") or die(mysqli_error($link));
}
unset($_SESSION['prolab']);
unset($_SESSION['tr']);
$_SESSION['ORDER']=0;
?>
</body></html>
<?php 
echo "
 <script>
$(function() {
var availableTags = [";
        $ordertable = 'doctemplate_'.$_SESSION['sflc'];

	$sql="SELECT scode FROM $ordertable ORDER BY scode";
	$result = mysqli_query($link,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			echo "'".$row['scode']."',\n";
		}
	}
echo "];";
echo "
$( '#ordertxt' ).autocomplete({source: availableTags});
$( '#ordertxt1' ).autocomplete({source: availableTags});
$( '#ordertxt2' ).autocomplete({source: availableTags});
$( '#ordertxt3' ).autocomplete({source: availableTags});
$( '#ordertxt4' ).autocomplete({source: availableTags});
$( '#ordertxt5' ).autocomplete({source: availableTags});
$( '#ordertxt6' ).autocomplete({source: availableTags});
$( '#ordertxt7' ).autocomplete({source: availableTags});
$( '#ordertxt8' ).autocomplete({source: availableTags});
$( '#ordertxt9' ).autocomplete({source: availableTags});
$( '#ordertxt10' ).autocomplete({source: availableTags});
});
</script>";
?>
