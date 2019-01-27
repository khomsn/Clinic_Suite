<?php 
include '../../config/dbc.php';

page_protect();

$id = $_SESSION['patdesk'];
$Patient_id = $id;
include '../../libs/opdxconnection.php';

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
$tmp = "tmp_".$id;
$today = date("Y-m-d");

$pin = mysqli_query($linkopdx, "select MAX(id) from $pttable");
$rid = mysqli_fetch_array($pin);
$_SESSION['mrid'] = $rid[0]; //Set to search for previous record for drug  and Treatment

$ordertable = "doctemplate_".$_SESSION['sflc'];

include '../../libs/price.php';

for($i=1;$i<=10;$i++)
{
	if($_POST[$i] == 'ลบ')
	{
		//
		//update reserve volume at drug_id
		$redid = $_POST['hidx'.$i];
		
		$resd = mysqli_query($link, "select * from drug_id where id='$redid'");
		while ($resdf = mysqli_fetch_array($resd))
		{
		$oldvol = $resdf['volreserve'];
		
		$rvolnew = $oldvol - $_POST['oldvl'.$i];
		// set reserve to 0 if less than 0
		if($rvolnew < 0) $rvolnew = 0;
		
		mysqli_query($link, "UPDATE drug_id SET `volreserve` = '$rvolnew' WHERE `id` ='$redid' LIMIT 1 ;");
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
			");
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
			");       
        }
    }
        // go on to other step
		header("Location: prescriptconfirm.php");
}

if($_POST['register'] == 'บันทึก-ยืนยัน') 
{ 
//update at $tmp for staff policy
if($_SESSION['staff']==1 or $_SESSION['staff']==9)
{
    mysqli_query($link, "UPDATE $tmp SET `pricepolicy` = '$_POST[policy]'");
    unset($_SESSION['staff']);
}
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
		$result = mysqli_query($link,$sql) or die(mysqli_error());
		
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
		
		mysqli_query($link, "UPDATE drug_id SET `volreserve` = '$rvolnew' WHERE `id` ='$redid' LIMIT 1 ;");
		}
		// go on to other step
	}
    if (ltrim($_POST['inform']) === '') $_POST['inform'] = '';
    
	  mysqli_query($linkopdx, "UPDATE $pttable SET `inform` = '$_POST[inform]' WHERE `id` = '$rid[0]' ");

//complete save

	  $checkid = 0;
	  $result = mysqli_query($link, "SELECT * FROM pt_to_obs WHERE id ='$id' ");
	  while($row = mysqli_fetch_array($result))
	  {
		  $prefix = $row['prefix'];
		  $fname = $row['fname'];
		  $lname = $row['lname'];
		  $checkid = $row['id'];
	  }
	  if($checkid != 0)
	  {
	  $sql_insert = "INSERT INTO `pt_to_drug` (`id`, `prefix`, `fname`, `lname`) VALUES ('$id', '$prefix', '$fname', '$lname')";
	  // Now insert Patient to "pt_to_drug" table
	  mysqli_query($link, $sql_insert);
	  }
	  // Now Delete Patient from "pt_to_doc" table
	  mysqli_query($link, "DELETE FROM pt_to_obs WHERE id = '$checkid' ");

	  if(!empty($_SESSION['medcert'])) goto Medcert;
	  // go on to other step
	  header("Location: ptlpage.php");  
}
Medcert :
if($_SESSION['medcert']=='1'){ header("Location: ../../docform/Medical_Certificate_2551.php");}
if($_SESSION['medcert']=='2'){ header("Location: ../../docform/Medical_Certificate.php");}

$title = "::สั่งยา::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../libs/popup.php';
include '../../libs/autoorder.php';
include '../../libs/iframeautofocus.php';
?>
</head><body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="main">
  <tr><td>
    <h3 class="titlehdr">สั่งยา และ การรักษา !!!!ยืนยันการสั่งยา!!!</h3>
    <form method="post" action="prescriptconfirm.php" name="regForm" id="regForm">
      <table style="text-align: left; width: 100%; height: 413px;" border="0" cellpadding="0" cellspacing="0"  class="forms">
	<tbody>
	<tr><td style="width: 80%; vertical-align: middle;"><div style="text-align: center;">
	    <big><big>ชื่อ: &nbsp; 
	    <?php
	    
	      while ($row_settings = mysqli_fetch_array($ptin))
	      {
				    echo $prefix = $row_settings['prefix'];
				    echo "&nbsp;"; 
				    echo $fname = $row_settings['fname'];
				    echo "&nbsp; &nbsp; &nbsp;"; 
				    echo $lname = $row_settings['lname'];
				    $_SESSION['staff'] = $row_settings['staff'];
	      }				
	      ?>
	      &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
	      Diag : 
	      <?php 
	      $today = date("Y-m-d");
	      $ptin = mysqli_query($linkopdx, "select * from $pttable where id = '$rid[0]' ");
	      while ($row_settings = mysqli_fetch_array($ptin))
	      {
		      echo $row_settings['ddx']; 
		      $inform = $row_settings['inform']; 
		      $_SESSION['weight']=$row_settings['weight'];
	      }	
	    ?></big></big>
	</div></td></tr>
        <tr><td><div style="text-align: center;">
		  ข้อมูล แนะนำ:<br>
		  <textarea cols="80" rows="2" type="text" name="inform" ><?php  echo  $inform; ?></textarea>
		  <hr style="width: 80%; height: 1px;">
		  <?php 
		  if ($_SESSION['user_accode']%11==0 OR $_SESSION['user_accode']%7==0)
		  {
		  ?>
		<a HREF="drugorder.php" onClick="return popup(this,'name','950','600','yes')" ><big>Order</big></a> : 
		<a HREF="prescriptold.php" onClick="return popup(this,'name','800','600','yes')" >(ยาเก่า)</a> 
		<?php } else {?><a HREF="prodorder.php" onClick="return popup(this,'name','800','600','yes')" >สั่งผลิตภัณฑ์</a><a 
		<?php }?><br>
		  <table class='TFtable' style="width: 80%; text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="0" cellspacing="0">
		    <tr><th width = 10 >No</th><th width = 250px >ชื่อ+ขนาด</th><th>ชื่อสามัญ</th><th>วิธีใช้</th><th width =50px>จำนวน</th><th width = 10>ลบ</th></tr>
		    <input name="register" value="บันทึก-ยืนยัน" type="submit" style="visibility: hidden;">
			<?php 
			  $ptin = mysqli_query($link, "select * from $tmp ");
			  while ($row = mysqli_fetch_array($ptin))
			  {
				  $_SESSION['medcert']=$row['medcert'];
				  $pricepolicy = $row['pricepolicy'];
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
				  echo "<input type=search size=40% name=use$i id=ordertxt$i value='$row[$us]'";
				  if($i==10) echo " autofocus";
				  echo ">";
				  echo "</td>";
				  echo "<td>";
				  echo "<input type=number class=typenumber min=0 max=".($row[$rxsv]-$rsvol[$i]+$row[$rxv])." step=1 name=vl$i value='$row[$rxv]'>";
				  echo "<input type=hidden name=oldvl$i value='$row[$rxv]'>";//this is old volume 
				  echo "</td><td>";
				  if($row[$rxby] == 0)
				  {
				  echo "<input type ='submit' name='";
				  echo $i;
				  echo "' value='ลบ'>";
				  }
				  echo "</td></tr>";
				  }
			  }
			?>
		</table>
	</div></td></tr>
	<tr><td><div style="text-align: center;">
		<?php 
		if($_SESSION['staff']==1)
		{
		echo "<input type=radio name=policy value=1";
		if($pricepolicy==1) echo " checked ";
		echo ">Free (Staff)<input type=radio name=policy value=2";
		if($pricepolicy==2) echo " checked ";
		echo ">ราคาปกติ";
		echo "<hr>";
		}
                if($_SESSION['staff']==9)
                {
                echo "<input type=radio name=policy value=1";
                if($pricepolicy==1) echo " checked ";
                echo ">Free (Staff)<input type=radio name=policy value=9";
                if($pricepolicy==9) echo " checked ";
                echo ">ราคาปกติ";
                echo "<hr>";
                }

		?><input name="register" value="บันทึก-ยืนยัน" type="submit" id="firstfocus">
	</div></td></tr>
	</tbody>
      </table>
    </form>
  </td></tr>
</table>
<?php 
if(empty($_SESSION['tr']))
{
    // Now Delete Patient from "pt_to_treatment" table
    mysqli_query($link, "DELETE FROM pt_to_treatment WHERE ptid = '$id' ");
    unset($_SESSION['tr']);
}
else
{
$checkid = mysqli_fetch_row(mysqli_query($link, "SELECT * FROM pt_to_treatment WHERE ptid ='$id' "));
$check = $checkid[0];
if(empty($check))
{
$sql_insert = "INSERT INTO `pt_to_treatment` (`ptid`, `prefix`, `fname`, `lname`) VALUES ('$id', '$prefix', '$fname', '$lname')";
mysqli_query($link, $sql_insert);
}
}
if(empty($_SESSION['prolab']))
{
  mysqli_query($link, "UPDATE $tmp SET  `prolab` = '0' ");
}
unset($_SESSION['prolab']);
unset($_SESSION['tr']);
$_SESSION['ORDER']=0;
?><br><br>
</body></html>
