<?php 
include '../login/dbc.php';
page_protect();

$id = $_SESSION['patdesk'];
$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
while ($row_settings = mysqli_fetch_array($ptin))
{
$prefix = $row_settings['prefix'];
$fname = $row_settings['fname'];
$lname = $row_settings['lname'];
}				
$pttable = "pt_".$id;
$tmp = "tmp_".$id;
$today = date("Y-m-d");
$pin = mysqli_query($linkopd, "select MAX(id) from $pttable");
$rid = mysqli_fetch_array($pin);
$_SESSION['mrid'] = $rid[0]; //Set to search for previous record for drug  and Treatment

/**
$pin = mysqli_query($linkopd, "select * from $pttable ");
while ($row_settings = mysqli_fetch_array($pin))
	{
		if($rid < $row_settings['id']) $rid = $row_settings['id'];
	}	
**/
for($i=1;$i<=4;$i++)
{
	if($_POST[$i] == 'ลบ')
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
		$trby ="trby".$i;
 
		mysqli_query($link, "UPDATE $tmp SET
			`$idtrp` = '',
			`$trp` = '',
			`$trvp` = '',
			`$tr1o1p` = '',
			`$tr1o1vp` = '',
			`$tr1o2p` = '',
			`$tr1o2vp` = '',
			`$tr1o3p` = '',
			`$tr1o3vp` = '',
			`$tr1o4p` = '',
			`$tr1o4vp` = '',
			`$trby` = ''
			") or die(mysqli_error($link));
		// go on to other step
		header("Location: treatment.php"); 	
	}
}
if($_POST['register'] == 'บันทึก') 
{
        for($i=1;$i<=4;$i++)
        {
		$trvp = "trv".$i;
		$tr1o1vp ="tr".$i."o1v";
		$tr1o2vp ="tr".$i."o2v";
		$tr1o3vp ="tr".$i."o3v";
		$tr1o4vp ="tr".$i."o4v";
                $trv = "trv".$i;
		mysqli_query($link, "UPDATE $tmp SET
			`$trvp` = '$_POST[$trv]'
			") or die(mysqli_error($link));
        }

if (ltrim($_POST['inform']) === '') $_POST['inform'] = '';

mysqli_query($linkopd, "UPDATE $pttable SET
			`inform` = '$_POST[inform]' 
			WHERE `id` = '$rid[0]' 
			") or die(mysqli_error($linkopd));
// go on to other step
header("Location: prescript.php");  
}
?>

<!DOCTYPE html>
<html>
<head>
<title>สั่งยา</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
<?php include '../libs/popup.php';?>
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
<table width="100%" border="1" cellspacing="0" cellpadding="5" class="main">
  <tr><td>
		<h3 class="titlehdr">สั่งยา และ การรักษา</h3>
		<form method="post" action="treatment.php" name="regForm" id="regForm">
			<table style="text-align: left; width: 100%; height: 413px;" border="1" cellpadding="2" cellspacing="2"  class="forms">
				<tbody>
					<tr>
						<td style="width: 80%; vertical-align: middle;">
							<div style="text-align: center;">
							<big>ชื่อ: &nbsp; 
							<?php
								echo $prefix;
								echo "&nbsp;";
								echo $fname;
								echo "&nbsp; &nbsp; &nbsp;"; 
								echo $lname;
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
							}	
							?>
							</big>
							
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
							 <a HREF="tmnew.php" onClick="return popup(this,'name','1000','600','yes');" ><big><big>Treatment</big></big></a> :  <a 
							 HREF="tmold.php" onClick="return popup(this,'name','1000','600','yes');" > ประวัติ(เก่า)</a><br>
							<table style="background-color: rgb(255, 204, 153); width: 100%; text-align: center;
									margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">									
								<tr>
									<th width = 10 >No</th><th>ชื่อ</th><th width = 35 >จำนวน</th><th width = 15px >Option1</th><th width = 5px>Vol</th>
									<th width = 15px >Option2</th><th width = 5px>Vol</th><th width = 15px >Option3</th><th width = 5px>Vol</th>
									<th width = 15px >Option4</th><th width = 5px>Vol</th><th width = 10>ลบ</th>
								</tr>
								<?php 
								$ptin = mysqli_query($link, "select * from $tmp ");
								while ($row = mysqli_fetch_array($ptin))
								{
                                                                    for($s=1;$s<=4;$s++)
                                                                    {
								        echo "<tr><td>".$s."</td><td>";
									echo $row['tr'.$s]; $_SESSION['tr']=$row['tr'.$s];
									echo "</td>";
									echo "<td>";
									echo "<input type=number class=typenumber  min=0 step=1 name='trv".$s."' value='".$row['trv'.$s]."'>";
									echo "</td>";
									echo "<td>";
									echo $row['tr'.$s.'1o1'];
									echo "</td>";
									echo "<td>";
									echo $row['tr'.$s.'o1v'];
									echo "</td>";
									echo "<td>";
									echo $row['tr'.$s.'o2'];
									echo "</td>";
									echo "<td>";
									echo $row['tr'.$s.'o2v'];
									echo "</td>";
									echo "<td>";
									echo $row['tr'.$s.'o3'];
									echo "</td>";
									echo "<td>";
									echo $row['tr'.$s.'o3v'];
									echo "</td>";
									echo "<td>";
									echo $row['tr'.$s.'o4'];
									echo "</td>";
									echo "<td>";
									echo $row['tr'.$s.'o4v'];
									echo "</td><td><input type ='submit' name='".$s."' value='ลบ'></td></tr>";
                                                                    }
									for($n=1;$n<=4;$n++)
									{
									    if(!empty($row['tr'.$n]))
									    { 
									      if($row['trby'.$n]!=0) $trby=1;
									      else 
									      {
										$trby=0;
										goto bkp;
									      }
									    }
									}
								}
								bkp:
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
  if($trby==0)
  {
      $checkid = mysqli_fetch_row(mysqli_query($link, "SELECT * FROM pt_to_treatment WHERE ptid ='$id' "));
      $check = $checkid[0];
      if(empty($check))
      {
      $sql_insert = "INSERT INTO `pt_to_treatment` (`ptid`, `prefix`,`fname`, `lname`) VALUES ('$id', '$prefix','$fname', '$lname')";
      // Now insert Patient to "pt_to_drug" table
      mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
      }
   }
}
?>
<!--end menu-->
</body></html>