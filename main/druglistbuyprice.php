<?php 
include '../login/dbc.php';
page_protect();

if($_POST['register'] == 'ดูข้อมูล') 
{ 

// pass drug-id to other page
$_SESSION['drugid'] = $_POST['drugid'];
// go on to other step
header("Location: updatedrugid.php");  

} 

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
<title>รายงาน ราคาซื้อ ยาและผลิตภัณฑ์ </title>
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
			<h3 class="titlehdr">รายการ ยา และ ผลิตภัณฑ์</h3>
			<form method="post" action="druglistbuyprice.php" name="regForm" id="regForm">
				<table style="text-align: center;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="width: 18px;"></td>
						<td style="vertical-align: middle; ">
						<div style="text-align: center;">
						<?php	
								echo "<table class='TFtable' border='1' style='text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);'>";
								echo "<tr><th>เลือก</th>";
								if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0)
								{
								echo "<th>id</th>";
								}
								echo "<th>ชื่อ</th><th>ชื่อสามัญ</th><th>ขนาด</th><th>ราคาซื้อ</th></tr>";
								while($row = mysqli_fetch_array($filter))
								 {
										// Print out the contents of each row into a table
										echo "<tr><th>";
										if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0)
										{
										echo "<input type='radio' name='drugid' value='".$row['id']."' />";
										echo "</th><th>";
										echo $row['id'];
										}
										echo "</th><th>"; 
										echo $row['dname'];
										echo "</th><th>"; 
										echo $row['dgname'];
										echo "</th><th >"; 
										echo $row['size'];
										echo "</th><th style='text-align: right;' >"; 
										$drugtable = "drug_".$row['id'];
										$getsup = mysqli_query($link, "select distinct supplier from $drugtable where supplier!='$_SESSION[clinic]' AND price!='0'");
										$sp=0;
										while($gs = mysqli_fetch_array($getsup))
										{
                                            $sup[$sp]=$gs['supplier'];
                                            $sp=$sp+1;
										}
										
										for($n=0;$n<$sp;$n++)
										{
										$supplier=$sup[$n];
										
										$gr = mysqli_fetch_array(mysqli_query($link, "select MAX(id) from $drugtable WHERE supplier='$supplier' AND price!='0'"));
										$rowid = $gr[0];
										
										$gp = mysqli_query($link, "select * from $drugtable WHERE id = $rowid");
										while($row2 = mysqli_fetch_array($gp))
										{
										 echo "[".$row2['supplier'].":".number_format(($row2['price']/$row2['volume']),2)."]";
										}
										}
										echo "</th></tr>";
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
