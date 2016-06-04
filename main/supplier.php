<?php 
include '../login/dbc.php';
page_protect();

if(($_POST['register'] == 'ตกลง')  AND (ltrim($_POST['name']!== '')))
{ 

$email = mysqli_real_escape_string($link,$_POST['email']);
//assign account no. 2101-2999 เจ้าหนี้ ซื้อ ยา และ อุปกรณ์
$ac = mysqli_query($link, "SELECT * FROM supplier");
$mmm=2100;
while($row = mysqli_fetch_array($ac))
{ if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
$ac = $mmm + 1;

//assign ac_no to table acnumber
$sql_insert = "INSERT into `acnumber`
  			(`ac_no`, `name`)
		    VALUES
			('$ac','$_POST[name]')";
// Now insert Patient to "patient_id" table
mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));

// assign insertion pattern
$sql_insert = "INSERT into `supplier`
  			(`name`,`address`,`tel`, `email`, `agent`, `mobile` , `ac_no` )
		    VALUES
			('$_POST[name]','$_POST[address]','$_POST[tel]','$email','$_POST[agent]','$_POST[mobile]','$ac')";

// Now insert Patient to "patient_id" table
mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));

// Then get Supplier ID to process to other step.
$result = mysqli_query($link, "SELECT * FROM supplier
 WHERE name='$_POST[name]' ");

$row = mysqli_fetch_array($result);

$id = "sp_".$row['id'];
$sql_insert ="

CREATE TABLE `$id` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date` date NOT NULL,
  `inid` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `inv_num` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(9,2) NOT NULL,
  `payment` tinyint(1) NOT NULL,
  `duedate` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

";

// Now create drug information table
mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
// go on to other step
header("Location: supplier.php");  

} 
?>

<!DOCTYPE html>
<html>
<head>
<title>บันทึก Supplier</title>
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
<body style="background-image: url(../image/ptbg.jpg);" alink="#000088" link="#006600" vlink="#660000">
<?php
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr><td width="200" valign="top">
		<?php 
			/*********************** MYACCOUNT MENU ****************************
			This code shows my account menu only to logged in users. 
			Copy this code till END and place it in a new html or php where
			you want to show myaccount options. This is only visible to logged in users
			*******************************************************************/
			if (isset($_SESSION['user_id']))
			{
				include 'spmenu.php';
			} 
		/*******************************END**************************/
		?>
			  <p>&nbsp; </p>
			  <p>&nbsp;</p>
			  <p>&nbsp;</p>
			  <p>&nbsp;</p>
		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
<!--menu-->
			<h3 class="titlehdr">บันทึก Supplier</h3>
			<form method="post" action="supplier.php" name="regForm" id="regForm">
				<table style="text-align: left; width: 703px; height: 413px;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="width: 18px;"></td>
						<td style="width: 646px; vertical-align: middle; background-color: rgb(152, 161, 76);">
							<div style="text-align: center;">ชื่อ* <input size="50" tabindex="1" name="name" class="required" > 
							</div>
							<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;"><br>
							<div style="text-align: center;">ที่อยู่* <textarea tabindex="2" cols="80" rows="3" class="required" name="address"></textarea>
							<br>โทรศัพท์* <input maxlength="20" size="20" class="required" tabindex="3" name="tel">
							</div>
							<hr style="width: 80%; height: 2px;"><br>
							<div style="text-align: center;">
							ตัวแทน* <input tabindex="4" class="required"  name="agent" > 
							&nbsp; &nbsp; &nbsp;โทรศัพท์*<input maxlength="15" class="required" size="15" tabindex="5" name="mobile" ><br>
							E-mail@*<input size="30" tabindex="6" name="email" ><br>
							</div>
							<hr style="width: 80%; height: 2px;"><br>
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
		<td width="60"></td>
	</tr>
</table>
<!--end menu-->
</body></html>
