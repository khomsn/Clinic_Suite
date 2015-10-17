<?php 
include '../login/dbc.php';
page_protect();

$filter = mysqli_query($link, "select * from rawmat ");		
	while ($row = mysqli_fetch_array($filter))
	{
		if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
	}	
$filter = mysqli_query($link, "select * from rawmat  ORDER BY `rmtype` ASC ,`rawcode` ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>รายการ Raw Material</title>
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
<body style="background-image: url(../image/new.jpg);" alink="#000088" link="#006600" vlink="#660000">
<?php
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td colspan="3">&nbsp;</td></tr>
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
			<h3 class="titlehdr">นำเข้า Raw Material</h3>
				<table style="text-align: left;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="width: 18px;"></td>
						<td style=" vertical-align: middle;">
						<div style="text-align: center;">	
												<!--List Patient wait for doctor-->
						<?php
						echo "<table border='1' style='text-align: center; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76)' >";
						echo "<tr><th>Code</th><th>ชื่อ</th><th>ขนาด</th><th>Unit</th><th>Type</th></tr>";
						// keeps getting the next row until there are no more to get
						while($row = mysqli_fetch_array($filter))
						 {
								// Print out the contents of each row into a table
								echo "<tr><th style='text-align: left;'>"; 
								$msg = urlencode($row['id']);
							?>
								<a onClick="return popup(this, 'notes','800','450','yes')" HREF="rawstockin.php
								<?php echo "?msg=".$msg; ?>"><?php echo $row['rawcode'];?></a>
							
						<?php 
								echo "</th><th style='text-align: left;'>"; 
								echo $row['rawname'];
								echo "</th><th>"; 
								echo $row['size'];
								echo "</th><th>"; 
								echo $row['sunit'];
								echo "</th><th>"; 
								echo $row['rmtype'];
								echo "</th></tr>";
						} 
						echo "</table>";
						//////////////////////////
						?>						

							<br>
						</div>
						</td>
						<td style="width: 10px;"></td>
					</tr>
				</tbody>
				</table>
				<br>
<!--menu end-->
		</td>
		<td style="width:260px;vertical-align: top;">
			<form method="post" action="stock.php" name="listForm" id="listForm">
				<div class="pos_r_fix" style="text-align: right;">
						<?php	
							$dtype = mysqli_query($link, "SELECT rmtype FROM rawmat");
							$dgroup = mysqli_query($link, "SELECT name FROM drug_group");
						?>
							ประเภท&nbsp;
							<select name="type">
								<option value="" selected></option>
								<?php while($trow = mysqli_fetch_array($dtype))
								{
									echo "<option value=\"";
									echo $trow['rmtype'];
									echo "\">";
									echo $trow['rmtype']."</option>";
								}
								?>
							</select>
							&nbsp; &nbsp; &nbsp; 
							<br>
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
							</select>&nbsp; &nbsp; &nbsp; &nbsp; 
							<br>
							<input type='submit' name='todo' value='กรอง' >&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
				</div>	
			</form>
		</td>
	</tr>
</table>
<!--end menu-->
</body></html>