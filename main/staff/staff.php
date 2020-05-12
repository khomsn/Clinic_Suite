<?php 
include '../../config/dbc.php';
page_protect();

$sql = "CREATE TABLE IF NOT EXISTS `staff` (
  `ID` smallint(6) NOT NULL AUTO_INCREMENT,
  `prefix` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `F_Name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `L_Name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `Eprefix` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `EF_Name` text COLLATE utf8mb4_unicode_ci,
  `EL_Name` text COLLATE utf8mb4_unicode_ci,
  `gender` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ctz_id` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `add_hno` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `add_mu` tinyint(4) NOT NULL DEFAULT '0',
  `add_str` text COLLATE utf8mb4_unicode_ci,
  `add_t` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `add_a` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `add_j` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `add_zip` mediumint(5) NOT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `h_tel` varchar(18) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `posit` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` smallint(6) NOT NULL DEFAULT '0',
  `regtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `clog` tinyint(4) NOT NULL DEFAULT '0',
  `ch_by` smallint(6) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `ctz_id` (`ctz_id`,`license`),
  PRIMARY KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

mysqli_query($link, $sql);

$sql_select =  mysqli_query($link, "SELECT * FROM `initdb` WHERE `refname`='staff' ORDER BY `id`" );
$tableversion = mysqli_num_rows($sql_select);
if(!$tableversion)
{
    $sql  = "INSERT INTO `initdb` (`refname`, `version`) VALUES (\'staff\', \'1\')";
    mysqli_query($link, $sql);
}

$title = "::Staff::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/popuponpagestaff.css\"/>";
include '../../main/bodyheader.php';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="160" valign="top"><div class="pos_l_fix">
<?php 
/*********************** MYACCOUNT MENU ****************************
This code shows my account menu only to logged in users. 
Copy this code till END and place it in a new html or php where
you want to show myaccount options. This is only visible to logged in users
*******************************************************************/
if (isset($_SESSION['user_id'])) 
{
    include '../../login/menu_admam.php';
}
/*******************************END**************************/
?></div>
    </td><td  valign="top"  style="text-align: left;"><p>&nbsp;</p>
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
	<div><table style="text-align: left; width: 100%; " border="0" cellpadding="0" cellspacing="0" class="TFtable">
			  <tbody>
				<?php 
				$stq = mysqli_query($link, "SELECT * FROM staff WHERE status='1' ORDER BY ID ASC");
				while($rows = mysqli_fetch_array($stq))
				 {
                    echo "<tr><td>";
                    echo "<div class='popup' onmouseover='myFunction".$rows['ID']."()' onmouseout='myFunction".$rows['ID']."()'>";
				 	echo "<img src=\"../".AVATAR_PATH."st_".$rows['ID'].".jpg\" width=30 height=30 />";
				 	echo "<span class=\"popuptext\" id=\"myPopup".$rows['ID']."\">";
				 	echo "<img src=\"../".AVATAR_PATH."st_".$rows['ID'].".jpg\" width=150 height=150 />";
				 	echo "</span>";
				 	echo "</div>";
				 	echo " ID:".$rows['ID']." ".$rows['prefix'].$rows['F_Name']." ".$rows['L_Name']." ตำแหน่ง ".$rows['posit'];
				 	if(!empty($rows['license'])) echo " License:".$rows['license'];
				 	echo " CID: ".$rows['ctz_id'];
				 	echo "</td></tr>";
				 	$popupmaxid = $rows['ID'];
				 }
				 ?>
			  </tbody>
			</table>
	</div>
    </td><td width="160"><div class="pos_r_fix"><?php include 'stmenurt.php';?></div></td>
  </tr>
</table>
</body>
</html>
<?php
include '../../libs/popuponpage.php';
?>
