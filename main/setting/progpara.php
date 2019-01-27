<?php 
include '../../config/dbc.php';
page_protect();

$err = array();
$msg = array();

$sql_insert = "
CREATE TABLE IF NOT EXISTS `parameter` (
  `ID` tinyint(4) NOT NULL DEFAULT '1' PRIMARY KEY,
  `name` text COLLATE utf8mb4_unicode_ci,
  `Ename` text COLLATE utf8mb4_unicode_ci,
  `cliniclcid` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `Eaddress` text COLLATE utf8mb4_unicode_ci,
  `tel` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `normprice` smallint(6) NOT NULL DEFAULT '0',
  `fup` smallint(6) NOT NULL DEFAULT '0',
  `tmp` smallint(6) NOT NULL DEFAULT '0',
  `maxcp` smallint(6) NOT NULL DEFAULT '0',
  `Staffp` smallint(4) NOT NULL DEFAULT '0',
  `name_lc` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lcid` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prtopdcard` tinyint(1) NOT NULL DEFAULT '0',
  `df` tinyint(4) NOT NULL DEFAULT '0',
  `dfp` smallint(6) NOT NULL DEFAULT '0',
  `opdidoffset` smallint(6) NOT NULL DEFAULT '0',
  `maskingdrugid` tinyint(1) NOT NULL DEFAULT '0',
  `extid` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";
mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));

if($_POST['submit']=='Set Logo')
{
        $logopath = "../".IMAGE_PATH;        
        if (is_dir($logopath) && is_writable($logopath)) {
        $target_dir = $logopath;
        $target_file = $target_dir .basename($_FILES["fileToUpload"]["name"]);        
        include '../../libs/uploadimage.php';
        
         mysqli_query($link, "UPDATE parameter SET logo = '$target_file' WHERE id = 1");
         
        } else {
            $err[] = FEEDBACK_AVATAR_FOLDER_NOT_WRITEABLE;
        }
    // go on to other step
    if(empty($err)) header("Location: progpara.php");  
       
}


if($_POST['save'] == 'บันทึก') 
{ 

    mysqli_query($link, "TRUNCATE TABLE parameter") or $err[]=(mysqli_error($link));

    // Filter POST data for harmful code (sanitize)
    foreach($_POST as $key => $value)
    {
        $data[$key] = filter($value);
    }
    // assign insertion pattern
    $sql_insert = "INSERT into `parameter`
			    (`name`,`Ename`,`cliniclcid`,`address`,`Eaddress`,`tel`,`mobile`,`email`,`logo`,`normprice`,`fup`,`tmp`,`Staffp`,`name_lc`,`lcid`,`maxcp`,`prtopdcard`,`df`,`dfp`,`opdidoffset`,`maskingdrugid`,`extid`)
			VALUES
			    ('$data[name]','$data[ename]','$data[cliniclcid]','$data[address]','$data[eaddress]','$data[tel]','$data[mobile]','$data[email]','$data[logo]','$data[normprice]','$data[fup]','$data[tmp]',
			    '$data[Staffp]','$data[name_lc]','$data[lcid]','$data[maxcp]','$data[print]','$data[DF]','$data[dfp]','$data[offset]','$data[maskingdrugid]','$data[extid]')";

    // Now insert Patient to "patient_id" table
    mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));

    // go on to other step
    header("Location: progpara.php");  
}

$title = "::Programme initialize::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/popuponpage.css\"/>";
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
		</td><td>
		<p><?php
            if(!empty($err))  
            {
                echo "<div class=\"msg\">";
                foreach ($err as $e) { echo "* Error - $e <br>"; }
                echo "</div>";
            }
            if(!empty($msg))
            {
                foreach ($msg as $me) echo "<div class=\"msg\">" . $me . "</div>";
            }
        ?></p>
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
                $maskingdrugid = $row_settings['maskingdrugid'];
                $extid = $row_settings['extid'];
                }
        ?>
			<h3 class="titlehdr">ตั้งค่า Programme </h3>
				<table style="text-align: center; margin-left: auto; margin-right: auto; width: 80%; background-color: rgb(255, 255, 204);" border="0" cellpadding="2" cellspacing="2">
				<form method="post" action="progpara.php" name="regis" id="regForm" enctype="multipart/form-data">
					<tbody>
						<tr>
							<td style="width: 100%; vertical-align: top; background-color: rgb(255, 255, 204);">
							<h3><p>ตั้งค่าต่างๆ<span class="required">*</span> จำเป็นต้องมี.</p></h3>
							<table style="text-align: center; margin-left: auto; margin-right: auto; width=80%" border=1>
							<tr><td style="text-align: right;" >
							ชื่อสถานพยาบาล*</td>
							<td>
							<input name="name" type="text" id="name"  class="required" value="<?php 
							echo $name; ?>" size="50%">
							</td></tr>
							<tr><td style="text-align: right;" >
							ชื่อสถานพยาบาล*(English)</td>
							<td>
							<input name="ename" type="text" id="ename"  class="required" value="<?php 
							echo $ename; ?>" size="50">
							</td></tr>
							<tr><td style="text-align: right;" >
							ใบอนุญาตเปิดสถานพยาบาล เลขที่.*</td>
							<td>
							<input name="cliniclcid" type="text" id="cliniclcid"  class="required" value="<?php 
							echo $cliniclcid; ?>" size="20">
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
							ที่อยู่*(English)
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
							<tr><td style="text-align: right;" >Logo-Image*</td><td ><?php if(empty($name)) echo "หลังทำการบันทึก จะสามารถ Set Logo ได้"; if(!empty($name)){?><div id="logo" style="height:42px;width:42px;float:right;"><div class='popup' onmouseover='myFunction1()' onmouseout='myFunction1()'><img src="../<?php echo $logo;?>" alt="logo" width="42" height="42"><span class="popuptext" id="myPopup1"><img src="../<?php echo $logo;?>" width="150" height="150"></span></div></div><label for="logo">Select an Logo image from your harddisk:</label><!-- max size 5 MB (as many people directly upload high res pictures from their digicams) --><input type="hidden" name="MAX_FILE_SIZE" value="2000000" /><input type="file" name="fileToUpload" id="fileToUpload"><input type="submit" value="Set Logo" name="submit"><?php }?><input name="logo" type="hidden" id="logo" value="<?php echo $logo; ?>">
							</td>
							</tr>
							<tr><td style="text-align: right;" >ราคาปกติ*</td><td ><input name="normprice" type="number" min=0 id="normprice"  class="required" value="<?php echo $normprice; if(empty($normprice)) echo 0;?>" size="5">
							</td>
							</tr>
							<tr><td style="text-align: right;" >ราคาติดตามอาการ*</td><td ><input name="fup" type="number" min=0 id="fup"  class="required" value="<?php echo $fup; if(empty($fup)) echo 0;?>" size="5">
							</td>
							</tr>
							<tr><td style="text-align: right;" >ราคา Treatment*</td><td ><input name="tmp" type="number" min=0 id="tmp"  class="required" value="<?php echo $tmp; if(empty($tmp)) echo 0;?>" size="5">
							</td>
							</tr>
							<tr><td style="text-align: right;" >ราคา Staff สูงสุด*</td><td ><input name="Staffp" type="number" min=0 id="Staffp"  class="required" value="<?php echo $Staffp; if(empty($Staffp)) echo 0;?>" size="5">
							</td>
							</tr>
							<tr><td style="text-align: right;" >ส่วนลดราคายาสูงสุด*</td><td ><input name="maxcp" type="number" min=0 id="maxcp"  class="required" value="<?php echo $maxcp; if (empty($maxcp)) echo 0; 
							?>" size="5">
							</td>
							</tr>
							<tr><td style="text-align: right;" >
							ใบอนุญาตให้มีไว้ในครอบครองวัตถุออกฤทธิ์ เลขที่.*</td>
							<td>
							<input name="lcid" type="text" id="lcid"  class="required" value="<?php 
							echo $lcid; ?>" size="20"> 
							</td></tr>
							<tr><td style="text-align: right;" >Print OPD Card:</td>
							<td style="text-aling: center;">
							<input type="radio" name="print" value="1" <?php if($prtopdcard==1) echo "checked";?>>Yes 
							<input type="radio" name="print" value="0" <?php if($prtopdcard==0) echo "checked";?>>No
							</td></tr>
							<tr><td style="text-align: right;" >Doctor Fee:</td>
							<td style="text-aling: center;">Auto DF 
							<input type="radio" name="DF" value="1" <?php if($df==1) echo "checked";?>>Yes
							<input type="radio" name="DF" value="0" <?php if($df==0) echo "checked";?>>No, <input type="number" min=0 name="dfp" size="4" value="<?php echo $dfp; if(empty($dfp)) echo 0;?>">บาท
							</td></tr>
							<tr><td style="text-align: right;" >OFFSET for OPD Card ID*</td><td ><input name="offset" type="number" min=0 id="offset"  class="required" value="<?php echo $offset; if(empty($offset)) echo 0;?>" size="6">
							</td></tr>
							<tr><td style="text-align: right;" >Masking Drug ID OPD</td><td >
							<input type="radio" name="maskingdrugid" value="1" <?php if($maskingdrugid==1) echo "checked";?>>Yes 
							<input type="radio" name="maskingdrugid" value="0" <?php if($maskingdrugid==0) echo "checked";?>>No
							</td>
							</tr>
							<tr>
							<td>เลขที่ระเบียนที่ใช้สำหรับขายส่งยา
							</td>
							<td>
							<input name="extid" type="text" id="extid"  value="<?php 
							if (empty($extid)) $extid = 0; echo $extid; ?>" size="15">
							</td></tr>
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
</body></html>
<?php
$popupmaxid = 1;
include '../../libs/popuponpage.php';
?>
