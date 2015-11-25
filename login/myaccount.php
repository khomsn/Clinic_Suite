<?php 
include 'dbc.php';
page_protect();

if(empty($_SESSION['scheight']))
{
  include 'checkscreen.php';
}
$sf_settings = mysqli_query($link, "select * from staff where ID = $_SESSION[staff_id]");
while ($sf = mysqli_fetch_array($sf_settings, MYSQLI_BOTH))
{
if($sf['gender']=="ชาย" and $sf['posit']=="แพทย์") $prefix = "นายแพทย์ ";
if($sf['gender']=="หญิง" and $sf['posit']=="แพทย์") $prefix = "แพทย์หญิง ";

$_SESSION['sfname'] = $prefix.$sf['F_Name']." ".$sf['L_Name'];
$_SESSION['sflc'] = $sf['license'];
}

?>
<!DOCTYPE html>
<html>
<head>
<title>My Account</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">

<link rel="stylesheet" href="../public/css/styles.css">
</head>
<?php 
if(!empty($_SESSION['user_background']))
{
echo "<body style='background-image: url(".$_SESSION['user_background']." ); background-size: cover;'>";
}
else
{
?>
<body  style="background-image: url(../image/mypage.jpg); background-size: cover;">
<?php
}
?>
<div class="pos_l_fix">
<?php 
/*********************** MYACCOUNT MENU ****************************
This code shows my account menu only to logged in users. 
Copy this code till END and place it in a new html or php where
you want to show myaccount options. This is only visible to logged in users
*******************************************************************/
if (isset($_SESSION['user_id'])) 
{
include 'menu.php';
}
/*******************************END**************************/
?>
</div>
<table border=0 style="width: 100%; ">
<tr>
<td style="text-align: center; width: 130px; "></td><td>
      <h3 class="titlehdr">Welcome <?php echo $_SESSION['user_name']; echo " on Screen ".$_SESSION['scwidth'].'x'.$_SESSION['scheight'];?></h3>  
	  <?php	
      if (isset($_GET['msg'])) {
	  echo "<div class=\"error\">$_GET[msg]</div>";
	  }
		$rs_settings = mysqli_query($link, "select * from parameter where id='1'");
	?>
			<table style="text-align: center; width: 100%; "
			 border="0" cellpadding="0" cellspacing="0">
			  <tbody>
				<tr><td>
				<p><h3 class="hdrname"><?php 
				while ($row_settings = mysqli_fetch_array($rs_settings))
				{ 
				  echo $_SESSION['clinic'] = $row_settings['name'];
				  echo "<br>" ;
				  $_SESSION['opdidoffset'] = $row_settings['opdidoffset'];
				}
				?>
			   <!--<img style="width: 390px; height: 390px;" alt="" src="../image/logo.jpeg">-->
				</h3></p></td>
				</tr>
				<tr>
				<td><a href="../main/mycounter.php"><img style="border: 0px solid ; width: 120px; height: 120px;" alt="คลินิก" src="../image/clinic.gif"></a></td>
				<td><a href="../main/pharmacy.php"><img style="border: 0px solid ; width: 120px; height: 120px;" alt="คลังยา และ ผลิตภัณฑ์" src="../image/drug.png"></a></td></tr>
				<tr><td><a href="../main/lab.php"><img	style="border: 0px solid ; width: 120px; height: 120px;" alt="Lab" src="../image/lab.jpg"></a></td><td style="text-align: center;">
				<a href="../main/rawmat.php"><img	style="border: 0px solid ; width: 120px; height: 120px;" alt="RawMat" src="../image/rawmat.jpeg"></a></td>
				</tr>
				<tr><td><a href="../main/accounting.php"><img	style="border: 0px solid ; width: 120px; height: 120px;" alt="บัญชีและการเงิน" src="../image/account.gif"></a></td>
				<td><a href="../main/report.php"><img	style="border: 0px solid ; width: 120px; height: 120px;" alt="รายงานต่างๆ" src="../image/report.jpeg"></a></td></tr>
			  </tbody>
			</table>
</td><td style="text-align: center; width: 130px; "></td></tr>
</table>
</body>
</html>
