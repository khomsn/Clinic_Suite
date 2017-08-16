<?php 
include '../login/dbc.php';
page_protect();
include '../libs/progdate.php';

$filter = mysqli_query($link, "select * from drug_id ");		
while ($row = mysqli_fetch_array($filter))
{
    if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
}
$filter = mysqli_query($link, "select * from drug_id  WHERE seti != 1 ORDER BY `dgname` ASC");

?>

<!DOCTYPE html>
<html>
<head>
<title>รายงานสถิติการใช้ยา </title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<link rel="stylesheet" href="../public/css/styles.css">
<link rel="stylesheet" href="../public/css/table_alt_color.css">
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
				include 'reportmenu.php';
			} 
		/*******************************END**************************/
		?></div>
		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
<!--menu-->
            <form method="post" action="drugusedstat.php" name="regForm" id="regForm">
			<h3 class="titlehdr">รายงานสถิติการใข้ยาในช่วงปี พ.ศ. <?php echo $bsy; //date("Y")+543;
	if ($_SESSION['user_accode']%2==0  and $_SESSION['user_level']>1)
	  {
	    {
	      if($sy>$minyear) echo "&nbsp;<input type='submit' name='todoy' value = '<<'>";
	      echo "&nbsp;<input type='submit' name='todoy' value = '@'>&nbsp;";
	      if ($sy < date("Y"))
	      {
	      echo "<input type='submit' name='todoy' value = '>>'>";
	      }
	    }
	  }?> </h3>
				<table style="text-align: center;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="width: 18px;"></td>
						<td style="vertical-align: middle; ">
						<div style="text-align: center;">
						<?php	
								echo "<table class='TFtable' border='1' style='text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);'>";
								echo "<tr><th>id</th>";
								echo "<th>ชื่อ</th><th>ชื่อสามัญ</th><th>ขนาด</th><th>มค.</th><th>กพ.</th><th>มีค.</th><th>เมย.</th><th>พค.</th><th>มิย.</th><th>กค.</th><th>สค.</th><th>กย.</th><th>ตค.</th><th>พย.</th><th>ธค.</th></tr>";
								while($row = mysqli_fetch_array($filter))
								 {
										// Print out the contents of each row into a table
										echo "<tr><th>";
										echo $drugid = $row['id'];
										echo "</th><th>"; 
										echo $row['dname'];
										echo "</th><th>"; 
										echo $row['dgname'];
										echo "</th><th >"; 
										echo $row['size'];
										echo "</th>";
										for ($i = 1;$i<=12;$i++)
										{
										echo "<th style='text-align: right;' >";
										$getdupmp = mysqli_query($link, "SELECT vol FROM dupm WHERE  drugid='$drugid' AND MONTH(mon) ='$i' AND YEAR(mon) ='$sy' ORDER BY `mon` DESC");
                                            while($gs = mysqli_fetch_array($getdupmp))
                                            {
                                                echo $gs['vol'];
                                            }
										echo "</th>";
										}
										echo "</tr>";
								} 
								echo "</table>";
						?>
							<br>
							</div>
						</td>
						<td>
					</td>
					</tr>
					<tr>
					<td>&nbsp;</td>
					<td>
						<div style="text-align: center;"><?php if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0){?><input name="register" value="ดูข้อมูล" type="submit"><?php }?></div>
					</td>
					</tr>
				</tbody>
				</table>
			</form>
<!--menu end-->
		</td>
<td width=130px><?php include 'reportrmenu.php';?></td></tr>
</table>
<!--end menu-->
</body></html>
