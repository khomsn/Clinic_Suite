<?php 
include '../login/dbc.php';
page_protect();

if($_POST['register'] == 'ตกลง') 
{ 

$rawcode =$_POST['rawcode'];
$rawname = $_POST['rawname'];
$size = $_POST['size'];
$adn = $rawcode.'-'.$rawname.'-'.$size;

//assign account no. 180000-189999 วัตถุดิบ
$ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no>180000 AND ac_no<190000 ORDER BY ac_no ASC");

$mmm=180000;
$maxm =190000;
// ตรวจสอบ ac_no จาก acname ก่อน 
//???????
//

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
$rs_duplicate = mysqli_query($link, "select count(*) as total from rawmat where rawcode='$_POST[rawcode]' AND rawname='$_POST[rawname]' AND size='$_POST[size]' ") or die(mysqli_error($link));
list($total) = mysqli_fetch_row($rs_duplicate);

if ($total > 0)
{
$err[] = "ERROR - This RawMat Code already exists. Please Check.";
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
$sql_insert = "INSERT into `rawmat`
  			(`rawcode`,`rawname`,`size`,`sunit`, `lowlimit`,`ac_no`,`rmfpd`,`rmtype`)
		    VALUES
			('$_POST[rawcode]','$_POST[rawname]','$_POST[size]','$_POST[unit]','$_POST[min_limit]','$ac','$_POST[rmpd]','$_POST[rwtype]')";

// Now insert Patient to "patient_id" table
mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));

// Then get Patient ID to process to other step.
$result = mysqli_query($link, "SELECT * FROM rawmat
 WHERE rawcode='$rawcode' AND rawname='$rawname' AND size='$size'");

$row = mysqli_fetch_array($result);
// Pass Patient ID as a session parameter.
//$_SESSION['rawmat']= $row['id'];
$id = "rawmat_".$row['id'];
$sql_insert ="

CREATE TABLE `$id` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`date` DATE NOT NULL ,
`supplier` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`inv_num` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`volume` INT NOT NULL ,
`price` DECIMAL (7,2) NOT NULL ,
`customer` INT NOT NULL DEFAULT '0'
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci; 

";

// Now create drug information table
mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
}
// go on to other step
header("Location: rawmatid.php");  

} 
?>

<!DOCTYPE html>
<html>
<head>
<title>ห้องคลังวัตถุดิบ</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
<?php 
include '../libs/popup.php';
$formid = "regForm";
include '../libs/validate.php';

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
				include 'rawmatmenu.php';
			} 
		/*******************************END**************************/
		?></div>
		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
<!--menu-->
			<h3 class="titlehdr">เพิ่ม ทะเบียน RawMat</h3>
			<form method="post" action="rawmatid.php" name="regForm" id="regForm">
				<table style="text-align: left; width: 703px; height: 413px;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="width: 18px;"></td>
						<td style="width: 646px; vertical-align: middle; background-color: rgb(152, 161, 76);">
							<div style="text-align: center;">Code* <input tabindex="1" name="rawcode" class="required" > 
							ชื่อ <input tabindex="2" name="rawname" id="rawname" class="required" > ขนาด* <input tabindex="3" class="required" name="size" size=10> Unit <select name=unit tabindex="4"><option value="ml">ml</option><option value="gram">gram</option><option value="กระปุก">กระปุก</option><option value="กล่อง">กล่อง</option><option value="ซอง">ซอง</option><option value="Set">Set</option><option value="ขวด">ขวด</option><option value="ลัง">ลัง</option></select><br>ส่วนประกอบของผลิตภัณฑ์:<input type="radio" tabindex="5" name="rmpd" class="required" value="1">Yes<input type="radio" tabindex="5" name="rmpd" class="required" value="0">No<br>ประเภท:<input type="radio" tabindex="6" name="rwtype" class="required" value="lab">Lab<input type="radio" tabindex="6" name="rwtype" class="required" value="ความงาม">ความงาม<input type="radio" tabindex="6" name="rwtype" class="required" value="other">อื่นๆ
							</div>
							<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;"><br>
							<div style="text-align: center;">
							จำนวนคงคลังขั้นต่ำ*<input class="typenumber" type="number" tabindex="6" name="min_limit" value=0><br>
							</div>
							<hr style="width: 80%; height: 2px;"><br>
						</td>
						<td style="width: 11px;"></td>
					</tr>
					<tr>
					<td>&nbsp;</td>
					<td><br>
						<br>
						<div style="text-align: center;"><input name="register" value="ตกลง" type="submit" tabindex="7"></div>
					</td>
					</tr>
				</tbody>
				</table>
				<br>
			</form>
<!--menu end-->
		</td>
	</tr>
</table>
<!--end menu-->
</body></html>
