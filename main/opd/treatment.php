<?php 
include '../../config/dbc.php';

page_protect();

$id = $_SESSION['patdesk'];
if(!$id)
{
$id = $_SESSION['pattrm'];
}

$Patient_id = $id;
include '../../libs/opdxconnection.php';

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
$pin = mysqli_query($linkopdx, "select MAX(id) from $pttable");
$rid = mysqli_fetch_array($pin);
$_SESSION['mrid'] = $rid[0]; //Set to search for previous record for drug  and Treatment

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
			`$idtrp` = '0',
			`$trp` = '',
			`$trvp` = '0',
			`$tr1o1p` = '',
			`$tr1o1vp` = '0',
			`$tr1o2p` = '',
			`$tr1o2vp` = '0',
			`$tr1o3p` = '',
			`$tr1o3vp` = '0',
			`$tr1o4p` = '',
			`$tr1o4vp` = '0',
			`$trby` = '0'
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

mysqli_query($linkopdx, "UPDATE $pttable SET
			`inform` = '$_POST[inform]' 
			WHERE `id` = '$rid[0]' 
			") or die(mysqli_error($linkopdx));
// go on to other step
header("Location: prescript.php");  
}
$title = "::::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
include '../../libs/popup.php';
echo "</head><body>";
?>
<table width="100%" border="1" cellspacing="0" cellpadding="5" class="main">
<tr><td>
		<h3 class="titlehdr">สั่งยา และ การรักษา</h3>
		<form method="post" action="treatment.php" name="regForm" id="regForm">
			<table style="text-align: left; width: 100%; height: 413px;" border="1" cellpadding="2" cellspacing="2"  class="forms">
				<tr><td style="width: 80%; vertical-align: middle;">
                    <div style="text-align: center;"><big>ชื่อ: &nbsp; 
                    <?php
                        echo $prefix;
                        echo "&nbsp;";
                        echo $fname;
                        echo "&nbsp; &nbsp; &nbsp;"; 
                        echo $lname;
                        echo "<br>";
                        echo "Diag :"; 
                        $today = date("Y-m-d");
                        $ptin = mysqli_query($linkopdx, "select * from $pttable where id = '$rid[0]' ");
                        while ($row_settings = mysqli_fetch_array($ptin))
                        {
                            echo $row_settings['ddx']; 
                            $inform = $row_settings['inform']; 
                        }
                    ?>
                    </big></div>
                </td></tr>
                <tr><td><div style="text-align: center;">ข้อมูล แนะนำ:<br>
							<textarea cols="80" rows="2" type="text" name="inform" ><?php echo  $inform;?></textarea>
                            <hr style="width: 80%; height: 1px;">
							<a HREF="tmnew.php" onClick="return popup(this,'name','1000','600','yes');" ><big><big>Treatment</big></big></a> :  
							<a HREF="tmold.php" onClick="return popup(this,'name','1000','600','yes');" > ประวัติ(เก่า)</a><br>
							<table  class='TFtable' style="width: 100%; text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
								<tr><th width = 10 >No</th>
                                    <th>ชื่อ</th>
                                    <th width = 35 >จำนวน</th>
                                    <th width = 15px >Option1</th>
                                    <th width = 5px>Vol</th>
									<th width = 15px >Option2</th>
									<th width = 5px>Vol</th>
									<th width = 15px >Option3</th>
									<th width = 5px>Vol</th>
									<th width = 15px >Option4</th>
									<th width = 5px>Vol</th>
									<th width = 10>ลบ</th>
								</tr>
								<?php 
								$ptin = mysqli_query($link, "select * from $tmp ");
								while ($row = mysqli_fetch_array($ptin))
								{
                                    for($s=1;$s<=4;$s++)
                                    {
								        echo "<tr><td>".$s."</td><td>";
                                        echo $row['tr'.$s];
                                        echo "</td>";
                                        echo "<td>";
                                        echo "<input type=number class=typenumber  min=0 step=1 name='trv".$s."' value='".$row['trv'.$s]."'>";
                                        echo "</td>";
                                        echo "<td>";
                                        echo $row['tr'.$s.'o1'];
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
                                        echo "</td><td>";
                                        if($row['trby'.$s]==0)
                                        {
                                            echo "<input type ='submit' name='".$s."' value='ลบ'>";
                                            $_SESSION['tr']=1;
                                        }
                                        echo "</td></tr>";
                                    }
								}
								?>
							</table>
					</div></td></tr>
					<tr><td><div style="text-align: center;"><input name="register" value="บันทึก" type="submit" id="firstfocus"></div></td></tr>
				</table>
			</form>
</td></tr>
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
      $sql_insert = "INSERT INTO `pt_to_treatment` (`ptid`, `prefix`,`fname`, `lname`) VALUES ('$id', '$prefix','$fname', '$lname')";
      // Now insert Patient to "pt_to_drug" table
      mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
      }
}
unset($_SESSION['tr']);
?><br>
</body></html>
