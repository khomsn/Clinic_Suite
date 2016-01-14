<?php 
include 'dbc.php';
page_protect();

$sql = " CREATE TABLE IF NOT EXISTS `parameter` ( `ID` tinyint(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `Ename` text COLLATE utf8_unicode_ci NOT NULL,
  `cliniclcid` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `Eaddress` text COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `normprice` smallint(6) NOT NULL,
  `fup` smallint(6) NOT NULL,
  `tmp` smallint(6) NOT NULL,
  `maxcp` smallint(6) NOT NULL,
  `Staffp` smallint(4) NOT NULL,
  `name_lc` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `lcid` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `prtopdcard` tinyint(1) NOT NULL,
  `df` tinyint(4) NOT NULL,
  `dfp` smallint(6) NOT NULL,
  `opdidoffset` smallint(6) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; ";

mysqli_query($link, $sql);

if($_POST['save'] == 'บันทึก') 
{ 

mysqli_query($link, "TRUNCATE TABLE parameter") or die(mysqli_error($link));

// Filter POST data for harmful code (sanitize)
foreach($_POST as $key => $value) {
	$data[$key] = filter($value);
}
    // assign insertion pattern
    $sql_insert = "INSERT into `parameter`
			    (`name`,`Ename`,`cliniclcid`,`address`,`Eaddress`,`tel`,`mobile`,`email`,`logo`,`normprice`,`fup`,`tmp`,`Staffp`,`name_lc`,`lcid`,`maxcp`,`prtopdcard`,`df`,`dfp`,`opdidoffset`)
			VALUES
			    ('$data[name]','$data[ename]','$data[cliniclcid]','$data[address]','$data[eaddress]','$data[tel]','$data[mobile]','$data[email]','$data[logo]','$data[normprice]','$data[fup]','$data[tmp]',
			    '$data[Staffp]','$data[name_lc]','$data[lcid]','$data[maxcp]','$data[print]','$data[DF]','$data[dfp]','$data[offset]')";

    // Now insert Patient to "patient_id" table
    mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));

// go on to other step
header("Location: progpara.php");  
}
?>
<html>
<head>
<title>Programme initialize</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
  <script>
  $(document).ready(function(){
    $("#regForm").validate();
  });
  </script>
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
				include 'menu.php';
			} 
		/*******************************END**************************/
		?></div>
		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
<!--menu-->
	<?php 
		$rs_settings = mysqli_query($link, "select * from parameter");
		while ($row_settings = mysqli_fetch_array($rs_settings)) 
			{
			 $name = $row_settings['name'];
			 $ename = $row_settings['Ename'];
			 $cliniclcid = $row_settings['cliniclcid'];
			 $name_lc = $row_settings['name_lc'];
			 $address = $row_settings['address'];
			 $eaddress = $row_settings['Eaddress'];
			 $tel = $row_settings['tel'];
			 $mobile = $row_settings['mobile'];
			 $email = $row_settings['email'];
			 $logo = $row_settings['logo'];
			 $normprice = $row_settings['normprice'];
			 $fup = $row_settings['fup'];
			 $tmp = $row_settings['tmp'];
			 $Staffp = $row_settings['Staffp'];
			 $maxcp = $row_settings['maxcp'];
			 $lcid = $row_settings['lcid'];
			 $prtopdcard = $row_settings['prtopdcard'];
			 $df = $row_settings['df'];
			 $dfp = $row_settings['dfp'];
			 $offset = $row_settings['opdidoffset'];
			}
	?>

			<h3 class="titlehdr">ตั้งค่า Programme </h3>
				<table style="text-align: center; margin-left: auto; margin-right: auto; width: 80%; background-color: rgb(255, 255, 204);" border="0" cellpadding="2" cellspacing="2">
				<form method="post" action="progpara.php" name="regis" id="regForm">
					<tbody>
						<tr>
							<td style="width: 100%; vertical-align: top; background-color: rgb(255, 255, 204);">
							<h3><p>ตั้งค่าต่างๆ<span class="required">*</span> จำเป็นต้องมี.</p></h3>
							<table style="text-align: center; margin-left: auto; margin-right: auto; width=80%" border=1>
							<tr><td style="text-align: right;" >
							ชื่อสถานพยาบาล*</td>
							<td>
							<input name="name" type="text" id="name"  class="required" value="<?php 
							echo $name; ?>" size="50">
							</td></tr>
							<tr><td style="text-align: right;" >
							ชื่อสถานพยาบาล*Eng</td>
							<td>
							<input name="ename" type="text" id="ename"  class="required" value="<?php 
							echo $ename; ?>" size="50">
							</td></tr>
							<tr><td style="text-align: right;" >
							ใบอนุญาตเลขที่.*</td>
							<td>
							<input name="cliniclcid" type="text" id="cliniclcid"  class="required" value="<?php 
							echo $cliniclcid; ?>" size="20">ใบอนุญาตเปิดคลินิก 
							</td></tr>
							<tr><td style="text-align: right;" >
							ชื่อผู้รับอนุญาต*</td>
							<td>
							<input name="name_lc" type="text" id="name_lc"  class="required" value="<?php 
							echo $name_lc; ?>" size="50">
							</td></tr>
							<tr><td style="text-align: right;" >
							ที่อยู่*
							</td><td>
							<textarea name="address" cols="40" rows="2" class="required" id="address"><?php echo $address; ?></textarea> </td></tr>
							<tr><td style="text-align: right;" >
							ที่อยู่*Eng
							</td><td>
							<textarea name="eaddress" cols="40" rows="2" class="required" id="eaddress"><?php echo $eaddress; ?></textarea> </td></tr>
							<tr><td style="text-align: right;" >โทรศัพท์*</td><td ><input name="tel" type="text" id="tel"  class="required" value="<?php echo $tel; ?>" size="30">
							</td>
							</tr>
							<tr><td style="text-align: right;" >โทร-มือถือ*</td><td ><input name="mobile" type="text" id="mobile"  class="required" value="<?php echo $mobile; ?>" size="30">
							</td>
							</tr>
							<tr><td style="text-align: right;" >Email*</td><td ><input name="email" type="text" id="email"  class="required" value="<?php echo $email; ?>" size="50">
							</td>
							</tr>
							<tr><td style="text-align: right;" >Logo*</td><td ><input name="logo" type="text" id="logo"  class="required" value="<?php echo $logo; ?>" size="50">
							</td>
							</tr>
							<tr><td style="text-align: right;" >ราคาปกติ*</td><td ><input name="normprice" type="number" min=0 id="normprice"  class="required" value="<?php echo $normprice; ?>" size="5">
							</td>
							</tr>
							<tr><td style="text-align: right;" >ราคาติดตามอาการ*</td><td ><input name="fup" type="number" min=0 id="fup"  class="required" value="<?php echo $fup; ?>" size="5">
							</td>
							</tr>
							<tr><td style="text-align: right;" >ราคา Treatment*</td><td ><input name="tmp" type="number" min=0 id="tmp"  class="required" value="<?php echo $tmp; ?>" size="5">
							</td>
							</tr>
							<tr><td style="text-align: right;" >ราคา Staff สูงสุด*</td><td ><input name="Staffp" type="number" min=0 id="Staffp"  class="required" value="<?php echo $Staffp; ?>" size="5">
							</td>
							</tr>
							<tr><td style="text-align: right;" >ส่วนลดราคายาสูงสุด*</td><td ><input name="maxcp" type="number" min=0 id="maxcp"  class="required" value="<?php echo $maxcp; 
							//}
							?>" size="5">
							</td>
							</tr>
							<tr><td style="text-align: right;" >
							ใบอนุญาตเลขที่.*</td>
							<td>
							<input name="lcid" type="text" id="lcid"  class="required" value="<?php 
							echo $lcid; ?>" size="20">ใบอนุญาตให้มีไว้ในครอบครองวัตถุออกฤทธิ์ 
							</td></tr>
							<tr><td>Print OPD Card:</td>
							<td style="text-aling: center;">
							<input type="radio" name="print" value="1" <?php if($prtopdcard==1) echo "checked";?>>Yes 
							<input type="radio" name="print" value="0" <?php if($prtopdcard==0) echo "checked";?>>No
							</td></tr>
							<tr><td>Doctor Fee:</td>
							<td style="text-aling: center;">Auto DF 
							<input type="radio" name="DF" value="1" <?php if($df==1) echo "checked";?>>Yes
							<input type="radio" name="DF" value="0" <?php if($df==0) echo "checked";?>>No, <input type="number" min=0 name="dfp" size="4" value="<?php echo $dfp;?>">บาท
							</td></tr>
							<tr><td style="text-align: right;" >OFFSET for OPD Card ID*</td><td ><input name="offset" type="number" min=0 id="offset"  class="required" value="<?php echo $offset; ?>" size="6">
							</td>
							</tr>
							</table>
						</tr>
						<tr>
							<td style="width: 100%; vertical-align: top; background-color: rgb(255, 255, 204);">
								<input type="submit" name="save" value="บันทึก">
							</td>
						</tr>
					</tbody>
				</form>	
				</table>
		</td>
	</tr>
</table>
<!--end menu-->
</body></html>
