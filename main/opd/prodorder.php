<?php 
include '../../config/dbc.php';

page_protect();

$id = $_SESSION['patdesk'];
$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
while($row = mysqli_fetch_array($ptin)) 
{
	$fname =$row['fname'];
	$lname =$row['lname'];
	$dl1 = $row['drug_alg_1'];
	$dl2 = $row['drug_alg_2'];
	$dl3 = $row['drug_alg_3'];
	$dl4 = $row['drug_alg_4'];
	$dl5 = $row['drug_alg_5'];
}
$pttable = "pt_".$id;
$tmp = "tmp_".$id;
$mrid = $_SESSION['mrid']-1;

//check preg condition
$preg = mysqli_query($link, "select preg from $tmp");

while ($row = mysqli_fetch_array($preg)) $preg=$row['preg'];

if($preg==1)
{
$cat = '(cat = "A" or cat = "B" or cat = "N")';
}
else $cat=1;
//
if(!empty($dl1)) {$fdo1 = 'dgname != "'.$dl1.'"'; $fdo = $fdo1;}
if(!empty($dl2)) {$fdo2 = 'dgname != "'.$dl2.'"'; $fdo = $fdo." AND ".$fdo2;}
if(!empty($dl3)) {$fdo3 = 'dgname != "'.$dl3.'"'; $fdo = $fdo." AND ".$fdo3;}
if(!empty($dl4)) {$fdo4 = 'dgname != "'.$dl4.'"'; $fdo = $fdo." AND ".$fdo4;}
if(!empty($dl5)) {$fdo5 = 'dgname != "'.$dl5.'"'; $fdo = $fdo." AND ".$fdo5;}

if(!empty($dl1)) {$fgo1 = 'groupn != "'.$dl1.'"'; $fgo = $fgo1;}
if(!empty($dl2)) {$fgo2 = 'groupn != "'.$dl2.'"'; $fgo = $fgo." AND ".$fgo2;}
if(!empty($dl3)) {$fgo3 = 'groupn != "'.$dl3.'"'; $fgo = $fgo." AND ".$fgo3;}
if(!empty($dl4)) {$fgo4 = 'groupn != "'.$dl4.'"'; $fgo = $fgo." AND ".$fgo4;}
if(!empty($dl5)) {$fgo5 = 'groupn != "'.$dl5.'"'; $fgo = $fgo." AND ".$fgo5;}

if(!empty($dl1)) {$fsg1 = 'subgroup != "'.$dl1.'"'; $fsg = $fsg1;}
if(!empty($dl2)) {$fsg2 = 'subgroup != "'.$dl2.'"'; $fsg = $fsg." AND ".$fsg2;}
if(!empty($dl3)) {$fsg3 = 'subgroup != "'.$dl3.'"'; $fsg = $fsg." AND ".$fsg3;}
if(!empty($dl4)) {$fsg4 = 'subgroup != "'.$dl4.'"'; $fsg = $fsg." AND ".$fsg4;}
if(!empty($dl5)) {$fsg5 = 'subgroup != "'.$dl5.'"'; $fsg = $fsg." AND ".$fsg5;}

if (!empty($fdo) and !empty($fgo) and !empty($fsg))
{
$fdout = "(".$fdo.") AND (".$fgo.") AND (".$fsg.")";
}
elseif(!empty($fdo) and !empty($fgo) and empty($fsg))
{
$fdout = "(".$fdo.") AND (".$fgo.")";
}
elseif(!empty($fdo) and empty($fgo) and !empty($fsg))
{
$fdout = "(".$fdo.") AND (".$fsg.")";
}
elseif(empty($fdo) and !empty($fgo) and !empty($fsg))
{
$fdout = "(".$fgo.") AND (".$fsg.")";
}
elseif(!empty($fdo) and empty($fgo) and empty($fsg))
{
$fdout = $fdo;
}
elseif(empty($fdo) and empty($fgo) and !empty($fsg))
{
$fdout = $fsg;
}
elseif(empty($fdo) and !empty($fgo) and empty($fsg))
{
$fdout = $fgo;
}
else
{
$fdout = 1;
}



$filter = mysqli_query($link, "select * from drug_id");		
	while ($row = mysqli_fetch_array($filter))
	{
		if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
	}	
$filter = mysqli_query($link, "select * from drug_id WHERE typen!='Treatment' AND $cat AND $fdout AND staffcanorder='1' ORDER BY `dgname` ASC");

if ($_POST['todo'] == 'กรอง' ) 
{
	if($_POST['type'] != '' AND $_POST['group'] !='' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE typen='$_POST[type]' AND $cat AND $fdout  AND staffcanorder='1' AND  `groupn` ='$_POST[group]' ORDER BY `dgname` ASC");	
	}	
	if($_POST['type'] != '' AND $_POST['group'] =='' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE typen='$_POST[type]'  AND $cat AND $fdout  AND staffcanorder='1' ORDER BY `dgname` ASC");	
	}	
	if($_POST['group'] !=''  AND  $_POST['type'] == '' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE  `groupn` ='$_POST[group]'  AND $cat AND $fdout  AND staffcanorder='1' ORDER BY `dgname` ASC");	
	}	
	if($_POST['dgname1'] != '')
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE dgname='$_POST[dgname1]'  AND $cat AND $fdout  AND staffcanorder='1' ORDER BY `dgname` ASC");	
	}	
//	header("Location: prodorder.php");  
}

elseif ($_POST['todo'] == 'OK' or $_POST['todo'] == 'Close') 
{
	$pin = mysqli_query($link, "select * from $tmp ");
	while ($row = mysqli_fetch_array($pin))
	{	
		for($i=1;$i<=10;$i++)
		{
			$cn = "idrx".$i;
			if($row[$cn] == 0)
			{
				$imin = $i;
				break 1;
			}
		}
//		echo $cn; echo  "and"; echo $imin;
		for($j=1;$j<=$maxdrid;$j++)
		{ 
			if($_POST[$j] ==1)
			{
			$ptin = mysqli_query($link, "select * from drug_id where id='$j' ");
			$uj = "uses".$j;
			$vj = "vol".$j;
			$svj = "svol".$j;
			
			while ($row2 = mysqli_fetch_array($ptin))
				{
					$idrx[$i] =  $row2['id'];
					$rx[$i] =  $row2['dname'].'-'.$row2['size'];
					$rxg[$i] = $row2['dgname'];
					$rxuses[$i] =  $_POST[$uj];
					$rxv[$i] =  $_POST[$vj];
					$rxsv[$i] =  $_POST[$svj];
					$candp = $row2['candp'];
					if($candp ==1){$_SESSION['course']=1;}
					if($candp ==2){$_SESSION['prolab']=1;}
				}
				$imax = $i;
				$i = $i+1;
			}
		}
	}
	for($i=$imin;$i<=$imax;$i++)
	{
		$us = "rx".$i."uses";
		$vl = "rx".$i."v";
		$svl = "rx".$i."sv";
		$idp = $idrx[$i];
		$rxp = $rx[$i];
		$rgp = $rxg[$i];
		$usp = $rxuses[$i];
		$vlp = $rxv[$i];
		$svlp = $rxsv[$i];
		if(empty($vlp)) $vlp=0;
		if(empty($svlp)) $svlp=0;
		mysqli_query($link, "UPDATE $tmp SET
			`idrx$i` = '$idp',
			`rx$i` = '$rxp',
			`rxg$i` = '$rgp',
			`$us` = '$usp',
			`$vl` = '$vlp',
			`$svl` = '$svlp'
			") ;
	}
	if($_SESSION['prolab'])
	{
                mysqli_query($link, "UPDATE $tmp SET `prolab` = '1'");
                unset($_SESSION['prolab']);
	}
	if($_SESSION['course'])
	{
                mysqli_query($link, "UPDATE $tmp SET `course` = '1'");
                unset($_SESSION['course']);
	}

}


?>
<!DOCTYPE html>
<html>
<head>
<title>ยา</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<link rel="stylesheet" type="text/css" href="../public/js/jquery-ui-1.12.1.custom/jquery-ui.min.css" />
<link rel="stylesheet" href="../../public/css/styles.css">
<script language="JavaScript" type="text/javascript" src="../../public/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../public/js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<?php 
include '../../libs/popup.php';
include '../../libs/autoorder.php';
?>
</head>
<?php
include './bodyheader.php';
?>
<div id="content">
<form method="post" action="prodorder.php" name="regForm" id="regForm">
<div class="pos_fixed" style="text-align: right;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="main">
<tr><td >
		<?php	
			$dtype = mysqli_query($link, "SELECT name FROM drug_type");
			$dgroup = mysqli_query($link, "SELECT name FROM drug_group");
			$dgname = mysqli_query($linkcm, "SELECT name FROM druggeneric");
		?>
			ประเภท&nbsp;
			<select name="type">
				<option value="" selected></option>
				<?php while($trow = mysqli_fetch_array($dtype))
				{
					echo "<option value=\"";
					echo $trow['name'];
					echo "\">";
					echo $trow['name']."</option>";
				}
				?>
			</select>
	</td><td>
		กลุ่ม
		<select name="group">
			<option value="" selected></option>
			<?php while($grow = mysqli_fetch_array($dgroup))
			{
				echo "<option value=\"";
				echo $grow['name'];
				echo "\">";
				echo $grow['name']."</option>";
			}
			?>
		</select>
	</td><td>
		DGen Name:
		<select name="dgname1">
			<option value="" selected></option>
			<?php while($grow = mysqli_fetch_array($dgname))
			{
				echo "<option value=\"";
				echo $grow['name'];
				echo "\">";
				echo $grow['name']."</option>";
			}
			?>
		</select>

	</td><td width= 25% style="text-align: center;" > 
		<input type='submit' name='todo' value='กรอง' >
	</td><td width =80 style="text-align: center;">
		<?php 
		echo "<input type='submit' name='todo' value='OK' onClick='reloadParent();'/>";
		?>
	</td><td width=40> 
		<input type="submit" name="todo" value="Close" onClick="reloadParentAndClose();"/>
	</td>
</tr>
</table>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="main">
  <tr><td>
		<h3 class="titlehdr">สั่งยาของ <?php 	echo $fname; 
							echo "&nbsp; &nbsp; &nbsp;"; 
							echo $lname;
					       ?></h3>
		<h3><div style="text-align: center;"><?php if($dl1!='' or $dl2!='' or $dl3 != '' or $dl4 !='' or $dl5 !='')
		{ 
		  echo "แพ้ยา: ";
		  echo  $dl1; echo '&nbsp'; 
		  echo  $dl2; echo '&nbsp;';
		  echo  $dl3; echo '&nbsp;';
		  echo  $dl4; echo '&nbsp;'; 
		  echo  $dl5;
		}
		?>
		</div></h3>	
			<table style="text-align: left; width: 98%; " border="1" cellpadding="2" cellspacing="2"  class="forms">
				<tbody>
					<tr><th width = 10>เลือก</th><th>ชื่อ+ขนาด</th><th>ชื่อสามัญ</th><th>คลัง</th><th>วิธีใช้</th><th width = 5>จำนวน</th></tr>
					<?php 
						while ($row = mysqli_fetch_array($filter))
						{
							echo "<tr><td><input type=checkbox name="; echo $row[id]; echo " value=1 ></td><td>";
									echo $row['dname'].'-'.$row['size'];
									echo "</td>";
									echo "<td>";
									echo $row['dgname'];
									echo "</td>";
									echo "<td>";
									$maxvl = $row['volume'];
									echo "<input type='hidden' name=svol"; echo $row[id]; echo " value='"; echo  $maxvl; echo "'>";
									echo $row['volume'];
									echo "</td>";
									echo "<td>";
									echo "<input type='search' size= 40 maxlength = 300 name=uses"; echo $row[id]; echo " value='"; echo  $row['uses']; echo "'>";
									echo "</td>";
									echo "<td>";
									echo "<input type='number' min=0 max='".$maxvl."' step='1' size= 10 maxlength = 5 name=vol"; echo $row[id]; echo " value="; echo ">";
									echo "</td></tr>";
						}	
					?>
				</tbody>
			</table>
<!--menu end-->
		</td>
	</tr>
</table>
</form>
</div>
<!--end menu-->
</body>
</html>
