<?php 
include '../login/dbc.php';
page_protect();

$id = $_SESSION['drugid'];
$table = "set_drug_".$id;

$filter = mysqli_query($link, "select * from drug_id ");		
	while ($row = mysqli_fetch_array($filter))
	{
		if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
	}
$cat = '(cat = "A" or cat = "B" or cat = "N")';
$filter = mysqli_query($link, "select * from drug_id WHERE seti != 1  AND $cat ");

if ($_POST['todo'] == 'กรอง' ) 
{
	if($_POST['type'] != '' AND $_POST['group'] !='' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE typen='$_POST[type]' AND $cat  AND  `groupn` ='$_POST[group]' ");	
	}	
	if($_POST['type'] != '' AND $_POST['group'] =='' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE typen='$_POST[type]'  AND $cat  ");	
	}	
	if($_POST['group'] !=''  AND  $_POST['type'] == '' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE  `groupn` ='$_POST[group]'  AND $cat  ");	
	}	
}

elseif ($_POST['todo'] == 'OK' or $_POST['todo'] == 'Close') 
{
	$i = 1;
		for($j=1;$j<=$maxdrid;$j++)
		{ 
			if($_POST[$j] ==1)
			{
			$ptin = mysqli_query($link, "select * from drug_id where id='$j' ");
			$uj = "uses".$j;
			$vj = "vol".$j;
			while ($row2 = mysqli_fetch_array($ptin))
				{
					$idrx[$i] =  $row2['id'];
					$rxuses[$i] =  $_POST[$uj];
					$rxv[$i] =  $_POST[$vj];
				}
				$imax = $i;
				$i = $i+1;
			}
		}
	for($i=1;$i<=$imax;$i++)
	{
		$sql_insert = "INSERT INTO $table (`drugid`, `volume`, `uses`) VALUES ('$idrx[$i]', '$rxv[$i]', '$rxuses[$i]')";
		// Now insert into "set_drug_X" table
		mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
	}
}


?>

<!DOCTYPE html>
<html>
<head>
<title>ยา</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
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
<div id="content">
	<form method="post" action="drugsetadd.php" name="regForm" id="regForm">
		<div style="text-align: right;">
						<?php	
							$dtype = mysqli_query($link, "SELECT name FROM drug_type");
							$dgroup = mysqli_query($link, "SELECT name FROM drug_group");
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
							&nbsp; &nbsp; &nbsp; &nbsp; 
							กลุ่ม
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
							</select>&nbsp; &nbsp; &nbsp; &nbsp; 
							<input type='submit' name='todo' value='กรอง' >&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
								<?php 
									echo "<input type='submit' name='todo' value='OK' onClick='reloadParent();'/>";
								?>
									&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; 
									<input type="submit" name="todo" value="Close" onClick="reloadParentAndClose();"/>
		</div>
		<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
		  <tr><td>
					<table style="text-align: left; width: 100%; " border="1" cellpadding="2" cellspacing="2"  class="forms">
						<tbody>
							<tr><th width = 10>เลือก</th><th width =250>ชื่อ+ขนาด</th><th>วิธีใช้</th><th width = 35>จำนวน</th></tr>
							<?php 
								while ($row = mysqli_fetch_array($filter))
								{
									echo "<tr><td><input type=checkbox name="; echo $row[id]; echo " value=1 ></td><td>";
											echo $row['dname'].'-'.$row['size'].'('.$row['dgname'].')' ;
											echo "</td>";
											echo "<td>";
											echo "<input type='text' size= 50 maxlength = 300 name=uses"; echo $row[id]; echo " value='"; echo  $row['uses']; echo "'>";
											echo "<input type='hidden' name=cat"; echo $row[id]; echo " value='"; echo  $row['cat']; echo "'>";
											echo "</td>";
											echo "<td>";
											echo "<input type='number' class='typenumber' min='1' name=vol"; echo $row[id]; echo " value=1"; echo ">";
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
<!--<script>
var content = document.getElementById("content");
window.resizeTo(content.offsetWidth + 30, content.offsetHeight + 50);
</script>-->
</html>
