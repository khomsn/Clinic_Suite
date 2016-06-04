<?php 
include '../login/dbc.php';
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


$filter = mysqli_query($link, "select * from drug_id ");		
	while ($row = mysqli_fetch_array($filter))
	{
		if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
	}	
$filter = mysqli_query($link, "select * from drug_id WHERE typen='Treatment' AND $fdout  ORDER BY `dname` ASC  ");

if ($_POST['todo'] == 'กรอง' ) 
{
	if($_POST['type'] != '' AND $_POST['group'] !='' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE typen='$_POST[type]' AND $fdout AND  `groupn` ='$_POST[group]' ORDER BY `dname` ASC ");	
	}	
	if($_POST['type'] != '' AND $_POST['group'] =='' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE typen='$_POST[type]' AND $fdout ORDER BY `dname` ASC");	
	}	
	if($_POST['group'] !=''  AND  $_POST['type'] == '' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE  `groupn` ='$_POST[group]' AND $fdout  ORDER BY `dname` ASC");	
	}	
}

elseif ($_POST['todo'] == 'OK' or $_POST['todo'] == 'Close' ) 
{
	$pin = mysqli_query($link, "select * from $tmp ");
	while ($row = mysqli_fetch_array($pin))
	{	
		for($i=1;$i<=4;$i++)
		{
			$cn = "idtr".$i;
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
			$trv1 = "trv".$j;
			$opt1 = "Opt1".$j;
			$vol1 = "vol1".$j;
			$opt2 = "Opt2".$j;
			$vol2 = "vol2".$j;
			$opt3 = "Opt3".$j;
			$vol3 = "vol3".$j;
			$opt4 = "Opt4".$j;
			$vol4 = "vol4".$j;
			while ($row2 = mysqli_fetch_array($ptin))
				{
					$idtr[$i] =  $row2['id'];
					$tr[$i] =  $row2['dname'];
					$trv[$i] =  $_POST[$trv1];
					$Opt1[$i] = $_POST[$opt1];
					$Vol1[$i] = $_POST[$vol1];
					$Opt2[$i] = $_POST[$opt2];
					$Vol2[$i] = $_POST[$vol2];
					$Opt3[$i] = $_POST[$opt3];
					$Vol3[$i] = $_POST[$vol3];
					$Opt4[$i] = $_POST[$opt4];
					$Vol4[$i] = $_POST[$vol4];
				}
				$imax = $i;
				$i = $i+1;
			}
		}
	}
	for($i=$imin;$i<=$imax;$i++)
	{
		$idtrp ="idtr".$i;
		$trp = "tr".$i;
		$trvp = "trv".$i;
		$tr1o1p = "tr".$i."o1";
		$tr1o1vp ="tr".$i."o1v";
 		$tr1o2p = "tr".$i."o2";
		$tr1o2vp ="tr".$i."o2v";
 		$tr1o3p = "tr".$i."o3";
		$tr1o3vp ="tr".$i."o3v";
 		$tr1o4p = "tr".$i."o4";
		$tr1o4vp ="tr".$i."o4v";
		//check for empty volume then change to 0.
		if(empty($Vol1[$i])) $Vol1[$i]=0;
		if(empty($Vol2[$i])) $Vol2[$i]=0;
		if(empty($Vol3[$i])) $Vol3[$i]=0;
		if(empty($Vol4[$i])) $Vol4[$i]=0;
 
		mysqli_query($link, "UPDATE $tmp SET
			`$idtrp` = '$idtr[$i]',
			`$trp` = '$tr[$i]',
			`$trvp` = '$trv[$i]',
			`$tr1o1p` = '$Opt1[$i]',
			`$tr1o1vp` = '$Vol1[$i]',
			`$tr1o2p` = '$Opt2[$i]',
			`$tr1o2vp` = '$Vol2[$i]',
			`$tr1o3p` = '$Opt3[$i]',
			`$tr1o3vp` = '$Vol3[$i]',
			`$tr1o4p` = '$Opt4[$i]',
			`$tr1o4vp` = '$Vol4[$i]'
			") or die(mysqli_error($link));
	}

}


?>

<!DOCTYPE html>
<html>
<head>
<title>Treatment</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
<?php include '../libs/reloadopener.php';?>
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
?><br><br>
<form method="post" action="tmnew.php" name="regForm" id="regForm">
<div class=pos_r_tr style="text-align: right;">
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
	  <tr><td>
			<?php	
				$dtype = mysqli_query($link, "SELECT name FROM drug_type");
				$dgroup = mysqli_query($link, "SELECT name FROM drug_group WHERE name LIKE '%Treatment%'");
			?>
				ประเภท&nbsp;
				<select name="type">
					<option value="Treatment" selected>Treatment</option>
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
			<input type='submit' name='todo' value='กรอง' >
		</td><td> 
			<input type='submit' name='todo' value='OK' onClick='reloadParent();'/>
		</td><td> 
			<input type="submit" name="todo" value="Close" onClick="reloadParentAndClose();"/>
		</td>
	</tr> 
</table>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td>
		<h3 class="titlehdr">Treatment ของ <?php echo $fname." ".$lname;?> ไม่เกิน 4 รายการ
		
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
			<table style="text-align: left; width: 100%; " border="1" cellpadding="2" cellspacing="2"  class="forms">
				<tbody>
					<tr><th width = 10>เลือก</th><th width =350>ชื่อ</th><th width =35>จำนวน</th><th width=40>Opt1</th><th width = 5>Vol</th>
					<th width=40>Opt2</th><th width = 5>Vol</th>
					<th width=40>Opt3</th><th width = 5>Vol</th><th width=40>Opt4</th><th width = 5>Vol</th></tr>
					<?php    
                                                
                                                for($i=1;$i<=4;$i++)
                                                {
                                                    $idtrp ="idtr".$i;
                                                    $pin = mysqli_query($link, "select $idtrp from $tmp ");
                                                    $row = mysqli_fetch_array($pin);
                                                    $trid[$i] = $row[0];
                                                }
						while ($row = mysqli_fetch_array($filter))
						{
							echo "<tr><td><input type=checkbox name="; echo $row[id]; echo " value=1 ></td><td>";
                                                        echo $row['dname'];
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<input type='text' size= 3 maxlength = 4 value='1' name=trv"; echo $row[id]; echo ">";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<input type='text' size= 10  name=Opt1"; echo $row[id]; echo ">";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<input type='text' size= 4 maxlength = 5 name=vol1"; echo $row[id]; echo ">";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<input type='text' size= 10  name=Opt2"; echo $row[id]; echo ">";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<input type='text' size= 4 maxlength = 5 name=vol2"; echo $row[id]; echo ">";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<input type='text' size= 10  name=Opt3"; echo $row[id]; echo ">";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<input type='text' size= 4 maxlength = 5 name=vol3"; echo $row[id]; echo ">";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<input type='text' size= 10  name=Opt4"; echo $row[id]; echo ">";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<input type='text' size= 4 maxlength = 5 name=vol4"; echo $row[id]; echo ">";
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
</body>
</html>
