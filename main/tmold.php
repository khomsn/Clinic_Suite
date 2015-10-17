<?php 
include '../login/dbc.php';
page_protect();

$id = $_SESSION['patdesk'];
$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
$tmp = "tmp_".$id;

$mrid = $_SESSION['mrid']-1;

if ($_POST['todo'] == '<<' )
{
	$id = $_SESSION['rid'];
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
		for($j=1;$j<=4;$j++)
		{ 
			if($_POST[$j] ==1)
			{
			$ptin = mysqli_query($linkopd, "select * from $pttable where id='$id' ");
			$idtrg = "idtr".$j;
			$trg = "tr".$j;
			$trvg = "trv".$j;
			$tr1og = "tr".$j."o1";
			$tr1o1vg = "tr".$j."o1v";
			$tr2og = "tr".$j."o2";
			$tr2o1vg = "tr".$j."o2v";
			$tr3og = "tr".$j."o3";
			$tr3o1vg = "tr".$j."o3v";
			$tr4og = "tr".$j."o4";
			$tr4o1vg = "tr".$j."o4v";
			while ($row2 = mysqli_fetch_array($ptin))
				{
					$idtr[$i] =  $row2[$idtrg];
					$tr[$i] =  $row2[$trg];
					$trv[$i] =  $row2[$trvg];
					$tr1o[$i] =  $row2[$tr1og];
					$tr1o1v[$i] =  $row2[$tr1o1vg];
					$tr2o[$i] =  $row2[$tr2og];
					$tr2o1v[$i] =  $row2[$tr2o1vg];
					$tr3o[$i] =  $row2[$tr3og];
					$tr3o1v[$i] =  $row2[$tr3o1vg];
					$tr4o[$i] =  $row2[$tr4og];
					$tr4o1v[$i] =  $row2[$tr4o1vg];
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
 
		mysqli_query($link, "UPDATE $tmp SET
			`$idtrp` = '$idtr[$i]',
			`$trp` = '$tr[$i]',
			`$trvp` = '$trv[$i]',
			`$tr1o1p` = '$tr1o[$i]',
			`$tr1o1vp` = '$tr1o1v[$i]',
			`$tr1o2p` = '$tr2o[$i]',
			`$tr1o2vp` = '$tr2o1v[$i]',
			`$tr1o3p` = '$tr3o[$i]',
			`$tr1o3vp` = '$tr3o1v[$i]',
			`$tr1o4p` = '$tr4o[$i]',
			`$tr1o4vp` = '$tr4o1v[$i]'
			") or die(mysqli_error($link));
	}
	
	$_SESSION['rid'] = $_SESSION['rid'] - 1;
	header("Location: tmold.php");  
}
elseif ($_POST['todo'] == 'Last' )
{
	$_SESSION['rid'] = $mrid;
	$rid = $_SESSION['rid'];
	header("Location: tmold.php");  
}

elseif ($_POST['todo'] == '>>' ) 
{
	$id = $_SESSION['rid'];
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
		for($j=1;$j<=4;$j++)
		{ 
			if($_POST[$j] ==1)
			{
			$ptin = mysqli_query($linkopd, "select * from $pttable where id='$id' ");
			$idtrg = "idtr".$j;
			$trg = "tr".$j;
			$trvg = "trv".$j;
			$tr1og = "tr".$j."o1";
			$tr1o1vg = "tr".$j."o1v";
			$tr2og = "tr".$j."o2";
			$tr2o1vg = "tr".$j."o2v";
			$tr3og = "tr".$j."o3";
			$tr3o1vg = "tr".$j."o3v";
			$tr4og = "tr".$j."o4";
			$tr4o1vg = "tr".$j."o4v";
			while ($row2 = mysqli_fetch_array($ptin))
				{
					$idtr[$i] =  $row2[$idtrg];
					$tr[$i] =  $row2[$trg];
					$trv[$i] =  $row2[$trvg];
					$tr1o[$i] =  $row2[$tr1og];
					$tr1o1v[$i] =  $row2[$tr1o1vg];
					$tr2o[$i] =  $row2[$tr2og];
					$tr2o1v[$i] =  $row2[$tr2o1vg];
					$tr3o[$i] =  $row2[$tr3og];
					$tr3o1v[$i] =  $row2[$tr3o1vg];
					$tr4o[$i] =  $row2[$tr4og];
					$tr4o1v[$i] =  $row2[$tr4o1vg];
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
 
		mysqli_query($link, "UPDATE $tmp SET
			`$idtrp` = '$idtr[$i]',
			`$trp` = '$tr[$i]',
			`$trvp` = '$trv[$i]',
			`$tr1o1p` = '$tr1o[$i]',
			`$tr1o1vp` = '$tr1o1v[$i]',
			`$tr1o2p` ='$tr2o[$i]',
			`$tr1o2vp` = '$tr2o1v[$i]',
			`$tr1o3p` = '$tr3o[$i]',
			`$tr1o3vp` = '$tr3o1v[$i]',
			`$tr1o4p` = '$tr4o[$i]',
			`$tr1o4vp` = '$tr4o1v[$i]'
			") or die(mysqli_error($link));
	}
	
	$_SESSION['rid'] = $_SESSION['rid'] + 1;

	header("Location: tmold.php");  
}
elseif ($_POST['todo'] == 'OK' or $_POST['todo'] == 'Close') 
{
	$id = $_SESSION['rid'];
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
		for($j=1;$j<=4;$j++)
		{ 
			if($_POST[$j] ==1)
			{
			//check for reload
			$reload=1;
			//
			$ptin = mysqli_query($linkopd, "select * from $pttable where id='$id' ");
			$idtrg = "idtr".$j;
			$trg = "tr".$j;
			$trvg = "trv".$j;
			$tr1og = "tr".$j."o1";
			$tr1o1vg = "tr".$j."o1v";
			$tr2og = "tr".$j."o2";
			$tr2o1vg = "tr".$j."o2v";
			$tr3og = "tr".$j."o3";
			$tr3o1vg = "tr".$j."o3v";
			$tr4og = "tr".$j."o4";
			$tr4o1vg = "tr".$j."o4v";
			while ($row2 = mysqli_fetch_array($ptin))
				{
					$idtr[$i] =  $row2[$idtrg];
					$tr[$i] =  $row2[$trg];
					$trv[$i] =  $row2[$trvg];
					$tr1o[$i] =  $row2[$tr1og];
					$tr1o1v[$i] =  $row2[$tr1o1vg];
					$tr2o[$i] =  $row2[$tr2og];
					$tr2o1v[$i] =  $row2[$tr2o1vg];
					$tr3o[$i] =  $row2[$tr3og];
					$tr3o1v[$i] =  $row2[$tr3o1vg];
					$tr4o[$i] =  $row2[$tr4og];
					$tr4o1v[$i] =  $row2[$tr4o1vg];
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
 
		mysqli_query($link, "UPDATE $tmp SET
			`$idtrp` = '$idtr[$i]',
			`$trp` = '$tr[$i]',
			`$trvp` = '$trv[$i]',
			`$tr1o1p` = '$tr1o[$i]',
			`$tr1o1vp` = '$tr1o1v[$i]',
			`$tr1o2p` = '$tr2o[$i]',
			`$tr1o2vp` = '$tr2o1v[$i]',
			`$tr1o3p` = '$tr3o[$i]',
			`$tr1o3vp` = '$tr3o1v[$i]',
			`$tr1o4p` = '$tr4o[$i]',
			`$tr1o4vp` = '$tr4o1v[$i]'
			") or die(mysqli_error($link));
	}
}

?>

<!DOCTYPE html>
<html>
<head>
<title>ประวัติ Treatment</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/checkthemall.js"></script>
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
?>
<form method="post" action="tmold.php" name="formMultipleCheckBox" id="formMultipleCheckBox">
<div style="text-align: right;">
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
        <tr><td width =25% style="text-align: right;">    
		<?php 
		$rid = $_SESSION['rid'];
		if($rid > 1){ echo "<input type='submit' name='todo' value='<<' "; if($reload) echo "onClick='reloadParent();'"; echo "/>";}
		?>
	    </td><td width =25% style="text-align: center;">
		<input type='submit' name='todo' value='Last' >
	    </td><td width =25% style="text-align: left;">
	        <?php 
		if($rid < $mrid) { echo "<input type='submit' name='todo' value='>>' "; if($reload) echo "onClick='reloadParent();'"; echo "/>";}
		?>
	    </td><td width =80 style="text-align: center;">
		<input type='submit' name='todo' value='OK' onClick='reloadParent();'/>
	    </td><td width =40 style="text-align: right;">
		<input type="submit" name="todo" value="Close" onClick="reloadParentAndClose();"/>
	</td></tr>
</table>
</div>
<table width="100%" border="1" cellspacing="0" cellpadding="5" class="main">
  <tr><td>
		<h3 class="titlehdr">ประวัติ Treatment ของ <?php
		
							while ($row_settings = mysqli_fetch_array($ptin))
							{
								echo $row_settings['fname']; 
								echo "&nbsp;"; 
								echo $row_settings['lname'];
							}	
							$pin = mysqli_query($linkopd, "select * from $pttable WHERE id = '$rid' ");
							while ($row_settings = mysqli_fetch_array($pin))
							{
								echo " &nbsp; เมื่อวันที่ ";
								$date = new DateTime($row_settings['date']);
								$sd = $date->format("d");
								$sm = $date->format("m");
								$sy = $date->format("Y");
								$bsy = $sy+543;
								echo $sd;
								echo " ";
								$m = $sm;
								switch ($m)
								{
									 case 1:
									 echo "มกราคม";
									 break;
									 case 2:
									 echo "กุมภาพันธ์";
									 break;
									 case 3:
									 echo "มีนาคม";
									 break;
									 case 4:
									 echo "เมษายน";
									 break;
									 case 5:
									 echo "พฤษภาคม";
									 break;
									 case 6:
									 echo "มิถุนายน";
									 break;
									 case 7:
									 echo "กรกฎาคม";
									 break;
									 case 8:
									 echo "สิงหาคม";
									 break;
									 case 9:
									 echo "กันยายน";
									 break;
									 case 10:
									 echo "ตุลาคม";
									 break;
									 case 11:
									 echo "พฤศจิกายน";
									 break;
									 case 12:
									 echo "ธันวาคม";
									 break;
								}
								echo " พ.ศ.";
								echo $bsy; //date("Y")+543;
								echo " ณ ".$row_settings['clinic'];
							}				
							?></h3>
			<form id="formMultipleCheckBox" name="formMultipleCheckBox"> 
			<table style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2"  class="forms">
				<tbody>
					<tr><th width = 10><input name="checkAll" type="checkbox" id="checkAll" value="1" onclick="javascript:checkThemAll(this);" /></th><th width=10>No</th><th >ชื่อ</th><th width =35>จำนวน</th>
					<th width = 10>Option1</th><th width = 5>Vol</th><th width =10>Option2</th><th width = 5>Vol</th>
					<th width = 10>Option3</th><th width = 5>Vol</th><th width =10 >Option4</th><th width = 5>Vol</th></tr>
					<?php 
						$ptin = mysqli_query($linkopd, "select * from $pttable WHERE id = '$rid' ");
						while ($row = mysqli_fetch_array($ptin))
						{
						      for($i=1;$i<=4;$i++)
						      {
							  $tr = "tr".$i;
							  $trv = "trv".$i;
							  $tro1 = "tr".$i."o1";
							  $tro1v = "tr".$i."o1v";
							  $tro2 = "tr".$i."o2";
							  $tro2v = "tr".$i."o2v";
							  $tro3 = "tr".$i."o3";
							  $tro3v = "tr".$i."o3v";
							  $tro4 = "tr".$i."o4";
							  $tro4v = "tr".$i."o4v";
							  
							  if(!empty($row[$tr]))
							  {
								echo "<tr><td>";?><input type="checkbox" name="<?php echo $i;?>" id="checkBoxes" value=1 ></td><td><?php echo $i."</td><td>";
									echo $row[$tr];
									echo "</td>";
									echo "<td>";
									echo $row[$trv];
									echo "</td>";
									echo "<td>";
									echo $row[$tro1];
									echo "</td>";
									echo "<td>";
									echo $row[$tro1v];
									echo "</td>";
									echo "<td>";
									echo $row[$tro2];
									echo "</td>";
									echo "<td>";
									echo $row[$tro2v];
									echo "</td>";
									echo "<td>";
									echo $row[$tro3];
									echo "</td>";
									echo "<td>";
									echo $row[$tro3v];
									echo "</td>";
									echo "<td>";
									echo $row[$tro4];
									echo "</td>";
									echo "<td>";
									echo $row[$tro4v];
									echo "</td></tr>";
							  }
							}
						}	
					?>
				</tbody>
			</table>
<!--menu end-->	
		</td>
	</tr>
</table>
      </form>
</form>
</body>

</html>