<?php 
include '../login/dbc.php';
page_protect();

?>

<!DOCTYPE html>
<html>
<head>
<title>รายการ Raw Material</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<link rel="stylesheet" href="../public/css/styles.css">
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
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td colspan="3" >&nbsp;</td></tr>
  <tr><td width="160" valign="top"><div class="pos_l_fix">
		<?php 
			/*********************** MYACCOUNT MENU ****************************
			This code shows my account menu only to logged in users. 
			Copy this code till END and place it in a new html or php where
			you want to show myaccount options. This is only visible to logged in users
			*******************************************************************/
			if (isset($_SESSION['user_id']))
			{
				include 'rawmatmenu.php';
			} 
		/*******************************END**************************/
		?></div>
		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
<!--menu-->
			<h3 class="titlehdr">รายการ วัตถุดิบ ที่ถึงจุดสั่งซื้อ</h3>
			<form method="post" action="rawmatlist.php" name="regForm" id="regForm">
				<table style="text-align: center;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="width: 18px;"></td>
						<td style="vertical-align: middle; ">
						<div style="text-align: center;">
						<?php	
							$dtype = mysqli_query($link, "SELECT * FROM rawmat WHERE volume <= lowlimit ORDER BY `rmtype` ASC ,`rawcode` ASC ,`rawname` ASC");
								echo "<table border='1' style='text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);'>";
								echo "<tr><th>No</th><th>Code</th><th>ชื่อ</th><th>ขนาด</th><th>จำนวน</th><th>ร้าน</th><th>จำนวนที่สั่ง</th><th>Unit</th><th>Type</th><th>BP-S</th></tr>";
								$i=1;
								while($row = mysqli_fetch_array($dtype))
								 {
										// Print out the contents of each row into a table
										echo "<tr><th>";
										echo $i;
										echo "</th><th>"; 
										echo $row['rawcode'];
										echo "</th><th>"; 
										echo $row['rawname'];
										echo "</th><th>"; 
										echo $row['size'];
										echo "</th><th>"; 
										echo $row['volume'];
										echo "</th><th>";
								  $rawmattable = "rawmat_".$row['id'];
								  $spname = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM $rawmattable ORDER BY id DESC LIMIT 1;"));
										echo $spname['supplier'];
										echo "</th><th>";
										echo $spname['volume'];
										echo "</th><th>";
										echo $row['sunit'];
								echo "</th><th>"; 
								echo $row['rmtype'];
								echo "</th><th>"; 
										$supplierold = ''; //initialize
        $getprice = mysqli_query($link, "select * from $rawmattable WHERE supplier!='$_SESSION[clinic]' AND price!='0' ORDER BY `id` DESC ,`supplier` DESC ,`price` DESC");
										while($row2 = mysqli_fetch_array($getprice))
										{
                                                                                    $suppliernew = $row2['supplier'];
                                                                                    $pos = strpos($supplierold, $suppliernew);
                                                                                    if($pos === false)
                                                                                    {
                                                                                    echo "[".$row2['supplier'].":".number_format(($row2['price']/$row2['volume']),2)."]";
                                                                                    }
                                                                                    $supplierold = $supplierold." : ".$row2['supplier'];                                                                                    
										}
								echo "</th></tr>";
										$i = $i+1;
								} 
								echo "</table>";
						?>
							<br>
							</div>
						</td>
						<td style="width: 11px;"></td>
					</tr>
				</tbody>
				</table>
				<br>
			</form>
<!--menu end-->
		</td>
		<td width="160"></td>
	</tr>
</table>
<!--end menu-->
</body></html>