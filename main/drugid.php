<?php 
include '../login/dbc.php';
page_protect();

if($_POST['register'] == 'ตกลง') 
{ 

$dname =$_POST['dname'];
$dgname = $_POST['dgname'];
$size = $_POST['size'];
$adn = $dname.'-'.$size;

//assign account no. 100000-179999 สินค้า		180000-189999 วัตถุดิบ

$ac = mysqli_query($link, "SELECT * FROM acnumber WHERE (ac_no>=100000 AND ac_no<180000) ORDER BY ac_no ASC");

$mmm=100000;
$maxm=180000;

//assign account no. 180000-189999 วัตถุดิบ
if ($_POST['RawMat'] == '1' ){ $mmm=180000; $maxm =190000;}


while($row = mysqli_fetch_array($ac))
{ 
    if(empty($row['ac_no'])) goto Nextacno;
    if( $mmm < $row['ac_no'] and $row['ac_no']<$maxm )
	  { 
	    $newmmm =$row['ac_no'];
	    if($newmmm==($mmm+1))
	    {
	      $mmm=$row['ac_no'];
	    }
	    else goto Nextacno;
	    
	  }
}
Nextacno:
// assign account number to new product.
$ac = $mmm + 1;

//
$rs_duplicate = mysqli_query($link, "select count(*) as total from drug_id where dname='$_POST[dname]' AND dgname='$_POST[dgname]' AND size='$_POST[size]' ") or die(mysqli_error($link));
list($total) = mysqli_fetch_row($rs_duplicate);

if ($total > 0)
{
$err[] = "ERROR - This Drug already exists. Please Check.";
//header("Location: register.php?msg=$err");
//exit();
}
/***************************************************************************/

if(empty($err)) {
//

//assign ac_no to table acnumber
$sql_insert = "INSERT into `acnumber`
  			(`ac_no`, `name`)
		    VALUES
			('$ac','$adn')";
// Now insert Account number for product to "acnumber" table
mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));

// assign insertion pattern
$sql_insert = "INSERT into `drug_id`
  			(`dname`,`dgname`,`uses`, `size`, `sellprice`, `min_limit`, `typen`, `groupn`, `seti`, `ac_no`, `track`, `disct`,`prod`,`RawMat`,`cat`,`unit`,`candp`)
		    VALUES
			('$_POST[dname]','$_POST[dgname]','$_POST[uses]','$_POST[size]','$_POST[sellprice]','$_POST[min_limit]','$_POST[type]','$_POST[group]','$_POST[set]',
			'$ac','$_POST[track]','$_POST[disct]','$_POST[prod]','$_POST[RawMat]','$_POST[cat]','$_POST[unit]','$_POST[candp]')";

// Now insert into "drug_id" table
mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));

//update druggeneric table
    $imp = mysqli_query($linkcm, "select name from druggeneric WHERE name = '$_POST[dgname]'");

    list($imprs) = mysqli_fetch_row($imp);
    if(empty($imprs))
    {
    $sql_insert = "INSERT into `druggeneric` (name) value ('$_POST[dgname]')";
    mysqli_query($linkcm, $sql_insert) or die("Insertion Failed:" . mysqli_error($linkcm));

    }
// Then get Patient ID to process to other step.
$result = mysqli_query($link, "SELECT * FROM drug_id
 WHERE dname='$dname' AND dgname='$dgname' AND size='$size'");

$row = mysqli_fetch_array($result);
// Pass Patient ID as a session parameter.
//$_SESSION['drug_id']= $row['id'];
$id = "drug_".$row['id'];
$sql_insert ="
			CREATE TABLE `$id` (
			`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			 `date` DATE NOT NULL ,
			 `supplier` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
			 `inv_num` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
			 `volume` INT NOT NULL ,
			 `price` DECIMAL (7,2) NOT NULL ,
			 `customer` INT NOT NULL 
			) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci; ";

// Now create drug information table
mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));

if($_POST['track'] ==1)
{
$id1 = "drug_".$row['id'];
$sql_add = "ALTER TABLE `$id1` ADD `mkname` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
			 ADD `mkplace` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
			 ADD `mklot` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
			 ADD `mkanl` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
			 ADD `mkunit` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ";
 mysqli_query($link, $sql_add) or die("Insertion Failed:" . mysqli_error($link));
	
$id = "tr_drug_".$row['id'];
$sql_insert ="
			CREATE TABLE `$id` (
			 `date` DATE NOT NULL ,
			 `ctz_id` BIGINT NOT NULL ,
			 `pt_id` INT NOT NULL ,
			 `volume` INT NOT NULL 
			) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci; ";
// Now create drug information table
mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
			
}

if($_POST['set'] ==1)
{
$id = "set_drug_".$row['id'];
$sql_insert ="
			CREATE TABLE `$id` (
			 `drugid` SMALLINT NOT NULL ,
			 `volume` SMALLINT NOT NULL ,
			 `uses` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL 
			) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci; ";
// Now create drug information table
mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
			
}
}
// go on to other step
header("Location: drugid.php");  

} 
?>

<!DOCTYPE html>
<html>
<head>
<title>ยาและผลิตภัณฑ์</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-1.3.2.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/validate-1.5.5/jquery.validate.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
<?php 
$formid = "regForm";
include '../libs/validate.php';
include '../libs/popup.php';
include '../libs/autodruggen.php';
?>
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
				include 'drugmenu.php';
			} 
		/*******************************END**************************/
		?></div>
		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
<!--menu-->
			<h3 class="titlehdr">เพิ่ม ทะเบียนยา และ ผลิตภัณฑ์</h3>
			<form method="post" action="drugid.php" name="regForm" id="regForm">
				<table style="text-align: left; width: 703px; height: 413px;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="width: 18px;"></td>
						<td style="width: 646px; vertical-align: middle; background-color: rgb(152, 161, 76);">
							<div style="text-align: center;">ชื่อ* <input tabindex="1" name="dname" class="required" > 
							ชื่อสามัญ <input tabindex="2" name="dgname" id="dgname" class="required" > ขนาด* <input tabindex="3" class="required" name="size" size=10> Unit <select name=unit><option value="เม็ด">เม็ด</option><option value="แผง">แผง</option><option value="ขวด">ขวด</option><option value="หลอด">หลอด</option><option value="กล่อง">กล่อง</option><option value="ซอง">ซอง</option><option value="Set">Set</option><option value="Vial">Vial</option><option value="Ampule">Ampule</option></select><br>
							<input type="radio" name="cat" class="required" value="A">Cat A
							<input type="radio" name="cat" class="required" value="B">Cat B
							<input type="radio" name="cat" class="required" value="C">Cat C
							<input type="radio" name="cat" class="required" value="D">Cat D
							<input type="radio" name="cat" class="required" value="X">Cat X
							<input type="radio" name="cat" class="required" value="N" checked >Cat N
							<hr>
							Course / Programs Lab:<br>
							<input type="radio" name="candp" class="required" value="0">None
							<input type="radio" name="candp" class="required" value="1">Treatment
							<input type="radio" name="candp" class="required" value="2">Programs+Labs
							</div>
							<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;"><br>
							<div style="text-align: center;">วิธีใช้* <textarea tabindex="4" cols="80" rows="3" class="required" name="uses"></textarea>
							<br>
							</div>
							<hr style="width: 80%; height: 2px;"><br>
							<div style="text-align: center;">
							ราคาขาย* <input maxlength="5" size="5" tabindex="6" class="required"  name="sellprice"> บาท
							&nbsp; &nbsp; &nbsp;จำนวนคงคลังขั้นต่ำ*<input maxlength="4" class="required" size="4" tabindex="7" name="min_limit"><br>
							</div>
							<hr style="width: 80%; height: 2px;"><br>
							<div style="text-align: center;">
						<?php	
							$dtype = mysqli_query($link, "SELECT name FROM drug_type");
							$dgroup = mysqli_query($link, "SELECT name FROM drug_group");
						?>
							<a HREF="type.php" onClick="return popup(this,'name','300','500','yes');">ประเภท*</a>&nbsp;
							<select tabindex="8"  name="type">
								<option value="" selected></option>
								<?php while($trow = mysqli_fetch_array($dtype))
								{
									echo "<option value=\"";
									echo $trow['name'];
									echo "\">";
									echo $trow['name']."</option>";
								}
								?>
							</select>
							&nbsp; &nbsp; &nbsp; &nbsp; 
							<a HREF="group.php" onClick="return popup(this,'name','300','500','yes');" >กลุ่ม*</a>
							<select tabindex="9"  name="group">
								<option value="" selected></option>
								<?php while($grow = mysqli_fetch_array($dgroup))
								{
									echo "<option value=\"";
									echo $grow['name'];
									echo "\">";
									echo $grow['name']."</option>";
								}
								?>
							</select>
							<br>
							<input type="checkbox" name="track" value="1">ยาพิเศษ-รายงานการใช้ 
							&nbsp; &nbsp; &nbsp; &nbsp; <input type="checkbox" name="disct" value="1">ลดราคาได้
							&nbsp; &nbsp; &nbsp; &nbsp; <input type="checkbox" name="set" value="1">ชุดยา
							&nbsp; &nbsp; &nbsp; &nbsp; <input type="checkbox" name="prod" value="1">ผลิตภัณฑ์
							&nbsp; &nbsp; &nbsp; &nbsp; <input type="checkbox" name="RawMat" value="1">วัตถุดิบ
							<br>
							</div>
						</td>
						<td style="width: 11px;"></td>
					</tr>
					<tr>
					<td>&nbsp;</td>
					<td><br>
						<br>
						<div style="text-align: center;"><input name="register" value="ตกลง" type="submit"></div>
					</td>
					</tr>
				</tbody>
				</table>
				<br>
			</form>
<!--menu end-->
		</td>
		<td width=25%><br>
				<br>
				<div style="text-align: center;">
				 <h3 class="titlehdr">หมายเหต</h3>
				 <h3 class="myaccount">ผลิตภัณฑ์ คือ สินค้าที่ประกอบเอง</h3>
				 <h3 class="myaccount">ยาพิเศษ คือ ยากลุ่มที่ต้องทำรายงานส่งราชการ</h3>
				 <h3 class="myaccount">ลดราคาได้ คือ สินค้าที่ลดราคาได้</h3>
				 <h3 class="myaccount">ชุดยา คือ ชื่อชุดของยาที่จัดรวมกัน</h3>
				 <h3 class="myaccount">วัตถุดิบ คือ ส่วนที่นำมาประกอบสินค้าหรือขายให้ผู้ป่วยด้วย</h3>
				</div>
		</td>
	</tr>
</table>
<!--end menu-->
</body></html>