<?php 
include '../../config/dbc.php';
page_protect();

$id = $_SESSION['spid'];
$sply = mysqli_query($link, "select * from supplier where id='$id' ");
$sptab ="sp_".$id;
if($_POST['register'] == 'แก้ไข') 
{ 

mysqli_query($link, "UPDATE supplier SET
			`agent` = '$_POST[agent]',
			`mobile` = '$_POST[mobile]',
			`email` = '$_POST[email]',
			`paydetail` = '$_POST[paydetail]'
			 WHERE id='$id'
			") or die(mysqli_error($link));

// go on to other step
header("Location: listsp.php");  

}
$title = "Update Supplier";
include '../../main/header.php';
include '../../main/bodyheader.php';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="200" valign="top">
		<?php 
			/*********************** MYACCOUNT MENU ****************************
			This code shows my account menu only to logged in users. 
			Copy this code till END and place it in a new html or php where
			you want to show myaccount options. This is only visible to logged in users
			*******************************************************************/
			if (isset($_SESSION['user_id']))
			{
				include 'spmenu.php';
			} 
		/*******************************END**************************/
		?>
		</td><td>
			<h3 class="titlehdr">Update Supplier</h3>
			<form method="post" action="updatesp.php" name="regForm" id="regForm">
				<table style="text-align: left; width: 703px; height: 413px;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr><td style="width: 646px; vertical-align: middle; background-color: rgb(255, 255, 204);">
							<div style="text-align: center;"><big><big><big><big>ชื่อ: 
						<?php	
						while ($rowsp = mysqli_fetch_array($sply))
						{
							echo $rowsp['name'];
						?>
							</big></big></big></big></div>
							<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;"><br>
							<div style="text-align: center;"><big><big>
							ที่อยู่: &nbsp;<?php echo $rowsp['address'];?></big></big>
							<br><big><big>โทรศัพท์:&nbsp;<?php  echo $rowsp['tel']	?></big></big>
							<hr style="width: 80%; height: 2px;">
							วิธีการชำระเงิน<br>
							<input name="paydetail" type="text" size=50 value="<?php echo $rowsp['paydetail'];?>"></div>
							<hr style="width: 80%; height: 2px;"><br>
							<div style="text-align: center;">
							ตัวแทน: <input tabindex="1" name="agent" value="<?php echo $rowsp['agent'];?>"> 
							&nbsp; &nbsp; &nbsp;โทรศัพท์:<input maxlength="15" size="15" tabindex="2" name="mobile" value="<?php echo $rowsp['mobile'];?>"><br>
							E-mail@<input size="30" tabindex="3" name="email" value="<?php echo $rowsp['email'];?>"><br>
							</div>
						<?php }?>
							<hr style="width: 80%; height: 2px;">
							<br>
							<div style="text-align: center;">
							มียอดค้างจ่ายตามใบส่งของเลขที่:&nbsp;
						<?php 
							$result = mysqli_query($link, "SELECT * FROM $sptab WHERE payment = 0");
							while($row = mysqli_fetch_array($result))
							{
							 if($row['inv_num']!=$invold)
							 {
							//	$msg = urlencode($row['inv_num']);
							      //  echo "<a href = paysupply.php?msg=".$msg.">";
							        echo "<a href = paysupply.php?msg=".$row['inv_num'].">";
								$invold = $row['inv_num'];
								echo $row['inv_num'];
								echo "</a>";
								echo ",&nbsp;";
							 }
							}
						?>
							</div>
                    </td></tr>
					<tr><td><br>
						<br>
						<div style="text-align: center;"><input name="register" value="แก้ไข" type="submit"></div>
					</td>
					</tr>
				</tbody>
				</table>
			</form>
    </td></tr>
</table>
</body></html>
