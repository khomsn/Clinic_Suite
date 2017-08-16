<?php 
include '../login/dbc.php';
page_protect();
$sql = "
CREATE TABLE IF NOT EXISTS `staff` (
  `ID` smallint(6) NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `F_Name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `L_Name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `Eprefix` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `EF_Name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `EL_Name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ctz_id` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `add_hno` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `add_mu` tinyint(4) NOT NULL,
  `add_t` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `add_a` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `add_j` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `add_zip` mediumint(5) NOT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `h_tel` varchar(18) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `posit` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` smallint(6) NOT NULL,
  `regtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `clog` tinyint(4) NOT NULL DEFAULT '0',
  `ch_by` smallint(6) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `ctz_id` (`ctz_id`,`license`,`user_id`),
  KEY `ID` (`ID`)  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

mysqli_query($link, $sql);
?>
<html>
<head>
<title>My Account</title>
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
<body  style="background-image: url(../image/mypage.jpg);">
<?php
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr> 
    <td width="160" valign="top">
<?php 
/*********************** MYACCOUNT MENU ****************************
This code shows my account menu only to logged in users. 
Copy this code till END and place it in a new html or php where
you want to show myaccount options. This is only visible to logged in users
*******************************************************************/
if (isset($_SESSION['user_id'])) 
{
include '../login/menu.php';
}
/*******************************END**************************/
?>
      <p>&nbsp; </p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td  valign="top"  style="text-align: left;"><p>&nbsp;</p>
      <h3 class="titlehdr"><?php echo $_SESSION['user_name']." @ ";
		$rs_settings = mysqli_query($link, "select * from parameter where id='1'");
		while ($row_settings = mysqli_fetch_array($rs_settings))
		{ echo $row_settings['name'];}
		echo " # Active Staff List!";
		?>
      </h3>  
	  <?php	
      if (isset($_GET['msg'])) {
	  echo "<div class=\"error\">$_GET[msg]</div>";
	  }
	?>
	<div>
			<table style="text-align: left; width: 100%; "
			 border="1" cellpadding="0" cellspacing="0">
			  <tbody>
				<tr><td>
				<p><article>
				<?php 
				$stq = mysqli_query($link, "SELECT * FROM staff WHERE status='1' ORDER BY ID ASC");
				while($rows = mysqli_fetch_array($stq))
				 {
				 	echo "<img src=".AVATAR_PATH."st_".$rows['ID'].".jpg width=30 height=30 /> ID:".$rows['ID']." ".$rows['prefix'].$rows['F_Name']." ".$rows['L_Name']." ตำแหน่ง ".$rows['posit'];
				 	if(!empty($rows['license'])) echo " License:".$rows['license'];
				 	echo " CID: ".$rows['ctz_id'];
				 	echo "<br>";
				 }
				 ?>
				</article></p>
				</td></tr>
			  </tbody>
			</table>
	</div>
    </td>
    <td width="122">			
    <table style="text-align: left;" border="1" cellpadding="5" cellspacing="5">
	<tbody>
	      <tr>
		<td style="text-align: right;">
		<?php 
		if($_SESSION['user_accode']%13 == 0)
		{
		echo "<a href='../main/staffreg.php'>";
		?>
		 <img style="border: 0px solid ; width: 120px; height: 120px;" alt="ลงทะเบียนพนักงาน"
		    src="../image/staffadd.jpeg">
		<?php echo  "</a>";?>
	      </td></tr>
	      <tr><td>
	      </td></tr>
	      <tr><td style="text-align: right;">
		<?php echo "<a href='../main/staffsearch.php'>"; ?>
		<img style="border: 0px solid ; width: 120px; height: 120px;" alt="ค้นทะเบียนพนักงาน"
		    src="../image/user-management_3.jpg">
		    <?php 
		    echo "</a>";
		}
		?></td>
	      </tr>
	</tbody>
      </table>
</td>
  </tr>
</table>

</body>
</html>
