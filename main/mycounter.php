<?php 
include '../login/dbc.php';
page_protect();

?>
<html>
<head>
<title>My Counter</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<meta http-equiv="refresh" content="60; URL=mycounter.php">
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
        <link rel="stylesheet" href="../public/css/styles.css">
<?php include '../libs/refreshrate.php';?>
</head>
<?php 
if(!empty($_SESSION['user_background']))
{
echo "<body style='background-image: url(".$_SESSION['user_background'].");' alink='#000088' link='#006600' vlink='#660000'>";
}
else
{
?>
<body  style="background-image: url(../image/24.jpg);" alink="#000088" link="#006600" vlink="#660000">
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
include 'countermenu.php';
}
/*******************************END**************************/
?>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr><td style="text-align: center; width: 130px; "></td>
      <td valign="top"  style="text-align: left;"><p>&nbsp;</p>
      <h3 class="titlehdr">Welcome <?php echo $_SESSION['user_name'];?></h3>  
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
				{ echo $row_settings['name'];echo "<br>" ;}
				?>
			<!--	<img style="width: 390px; height: 109px;" alt="" src="../image/logotext.gif">-->
				</h3></p></td>
				</tr>
			  </tbody>
			</table>
			<br><br><br><br><br>
			<div style="text-align: center;">
			<table style="text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="5" cellspacing="5">
			  <tbody>
				<?php 
					$disc = mysqli_query($link, "select * from pt_to_doc");
					$i=0;
					while( $rowd = mysqli_fetch_array($disc))
					{
						$i = $i+1;
					}
					$disc = mysqli_query($link, "select * from pt_to_lab");
					$j=0;
					while( $rowd = mysqli_fetch_array($disc))
					{
						$j  = $j+1;
					}
					$disc = mysqli_query($link, "select * from pt_to_treatment");
					$l=0;
					while( $rowd = mysqli_fetch_array($disc))
					{
						$l  = $l+1;
					}
					$disc = mysqli_query($link, "select * from pt_to_drug");
					$k=0;
					while( $rowd = mysqli_fetch_array($disc))
					{
						$k  = $k+1;
					}
					$disc = mysqli_query($link, "select * from pt_to_obs");
					$m=0;
					while( $rowd = mysqli_fetch_array($disc))
					{
						$m  = $m+1;
					}
					$disc = mysqli_query($link, "select * from pt_to_scr");
					$n=0;
					while( $rowd = mysqli_fetch_array($disc))
					{
						$n  = $n+1;
					}
					if($n!=0) echo "<tr><td><span style='color: rgb(204, 0, 0); background-color: #E9F5BF;'>คนไข้รอซักประวัติ ".$n." ฅน</span></td></tr>";
					if($i!=0) echo "<tr><td><span style='color: rgb(204, 0, 0); background-color: #E9F5BF;'>คนไข้รอตรวจ ".$i." ฅน</span></td></tr>";
					if($m!=0) echo "<tr><td><span style='color: rgb(204, 0, 0); background-color: #E9F5BF;'>คนไข้รอสังเกตอาการ ".$m." ฅน</span></td></tr>";
					if($j!=0) echo "<tr><td><span style='color: rgb(204, 0, 0); background-color: #E5E5E5;'>คนไข้รอ Lab ".$j." ฅน</span></td></tr>";
					if($l!=0) echo "<tr><td><span style='color: rgb(204, 0, 0); background-color: #E5E5E5;'>คนไข้รอ Treatment ".$l." ฅน</span></td></tr>";
					if($k!=0) echo "<tr><td><span style='color: rgb(204, 0, 0); background-color: #E9F5BF;'>คนไข้รอรับยา ".$k." ฅน</span></td></tr>";
				?>
			  </tbody>
			</table>
			</div>
      </td>
    <td width="196" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
</table>

</body>
</html>
