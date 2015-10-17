<?php 
include '../login/dbc.php';
page_protect();

include '../libs/progdate.php';

$id = $_SESSION['patcash'];
$opdidcard = $id;
//
	$result1 = mysqli_query($link, "SELECT ptid FROM  pt_to_treatment WHERE ptid = '$id'");
	if(mysqli_num_rows($result1) != 0) 
	{
	  $_SESSION['pattrm'] = $id;
	  $_SESSION['frompage']= "payment.php";
	  header("Location: trmpage.php ");
	}
//
$ctmid = $id;
$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
$tmp = "tmp_".$id;
$today = date("Y-m-d");
$pin = mysqli_query($linkopd, "select * from $pttable ORDER BY `id` ASC ");
while ($row_settings = mysqli_fetch_array($pin))
	{
		$rid = $row_settings['id'];
		$visitdt = $row_settings['date'];
	}	
if($_POST['OK'] == 'ใบเสร็จรับเงิน')
{
	$_SESSION['paytoday'] = $_POST['ja_qty'];
	$_SESSION['newdeb'] = $_POST['ja_deb'];
	$_SESSION['cashin'] = $_POST['ja_cash'];
	$_SESSION['cashout'] = $_POST['ja_stVis'];
	$_SESSION['vd'] = $visitdt;
//check if not pay yet	
if(mysqli_num_rows(mysqli_query($link, "select * from $tmp"))==0)
{
//paid 0 mean paid
  goto Paid;
}	
//Recheck if PT is in pay-list

$ck1 = mysqli_query($link, "SELECT id FROM pt_to_drug WHERE id='$id'");
$chrow = mysqli_fetch_array($ck1);
if(empty($chrow[0]))
{
$err[]= "ยังไม่เสร็จสิ้นการ รักษา กรูณา ให้ผู้ป่วยรอ ก่อน และ ให้บริการท่านอื่นก่อน";
goto CannotPay;
}

	header("Location: recprint.php ");  
}	
elseif($_POST['OK'] == 'จ่าย')
{
//check if not pay yet
if(mysqli_num_rows(mysqli_query($link, "select * from $tmp"))==0)
{
//paid 0 mean paid
  goto Paid;
}
//Recheck if PT is in pay-list

$ck1 = mysqli_query($link, "SELECT id FROM pt_to_drug WHERE id='$id'");
$chrow = mysqli_fetch_array($ck1);
if(empty($chrow[0]))
{
$err[]= "ยังไม่เสร็จสิ้นการ รักษา กรูณา ให้ผู้ป่วยรอ ก่อน และ ให้บริการท่านอื่นก่อน";
goto CannotPay;
}
//
	$_SESSION['paytoday'] = $_POST['ja_qty'];
	$_SESSION['newdeb'] = $_POST['ja_deb'];
include '../libs/payprocess.php';

      Paid:
	//session_start(); 
	/************ Delete the sessions****************/
	//unset($_SESSION['patcash']);

	if($propdcard)
	{
	$_SESSION['patcash']=$opdidcard;
	header("Location: ../docform/opdcard.php ");
	}
	else
	{
	unset($_SESSION['buyprice']);
	unset($_SESSION['olddeb']);
	unset($_SESSION['patdesk']);
	unset($_SESSION['paytoday']);
	unset($_SESSION['newdeb']);
	unset($_SESSION['price']);
	unset($_SESSION['mrid']);
	unset($_SESSION['patcash']);
	header("Location: thankyou2.php ");
	}
	//unset($_SESSION['patcash']);
}
CannotPay:
?>

<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
<SCRIPT TYPE="text/javascript">
<!-- 
function orderTotal(oform, prefix)
{
// set references to fields
var qty = oform[prefix + "_qty"];
var stHold = oform[prefix + "_stHold"];
var price = oform[prefix + "_price"];
var stVis = oform[prefix + "_stVis"];
var cash = oform[prefix + "_cash"];
var deb = oform[prefix + "_deb"];
var dbHold = oform[prefix + "_dbHold"];

// only bother if the field has contents
if (qty == "")return;

// if the with is not a number (NaN)
// or is zero or less
// everything goes blank
if(isNaN(qty.value) || (qty.value < 0))
   {
   qty.value = "0";
   stHold.value = "0";
   }
   
// else the field is a valid number, so calculate the 
// total order cost and put that value in the 
// hidden subtotal field
else
//   stHold.value = (Math.round(qty.value * price.value * 100))/100;
{
	stHold.value = cash.value - qty.value;
	dbHold.value = price.value - qty.value;
}	
// call the routine which checks if the 
// visible subtotal is correct
visTotal(oform, prefix);
}

// checks if the visible subtotal is correct
// ie, if it equals the hidden subtotal field
function visTotal(oform, prefix)
{
var stHold = oform[prefix + "_stHold"];
var stVis = oform[prefix + "_stVis"];
var deb = oform[prefix + "_deb"];
var dbHold = oform[prefix + "_dbHold"];

if (stVis.value != stHold.value)
{
   stVis.value = stHold.value;
}   
if (deb.value != dbHold.value)
{
   deb.value = dbHold.value;
}   
}
// -->
</SCRIPT>
	<link rel="stylesheet" href="../public/css/styles.css">
</head>

<body >
<form method="post" action="payment.php" name="regForm" id="regForm">
							<div style="text-align: center;">
							<h2 class="titlehdr"> ยอดรวมค่า ยาและผลิตภัณฑ์ ณ วันที่ <?php echo $sd; ?> <?php $m = $sm;
			switch ($m)
			{
				 case 1:
				 echo "มกราคม";
				 break;
 				 case 2:
				 echo "กุมภาพันธ์";
				 break;
				 case 3:
				 echo "มีนาคม";
				 break;
				 case 4:
				 echo "เมษายน";
				 break;
				 case 5:
				 echo "พฤษภาคม";
				 break;
				 case 6:
				 echo "มิถุนายน";
				 break;
				 case 7:
				 echo "กรกฎาคม";
				 break;
				 case 8:
				 echo "สิงหาคม";
				 break;
				 case 9:
				 echo "กันยายน";
				 break;
				 case 10:
				 echo "ตุลาคม";
				 break;
				 case 11:
				 echo "พฤศจิกายน";
				 break;
				 case 12:
				 echo "ธันวาคม";
				 break;
			}?> พ.ศ. <?php echo $bsy; //date("Y")+543;?></h2>
							<h3>ชื่อ: &nbsp; 
							<?php
					while ($row_settings = mysqli_fetch_array($ptin))
					{
									echo $row_settings['fname'];
									$FName = $row_settings['fname'];
									echo "&nbsp; &nbsp; &nbsp;"; 
									echo $row_settings['lname'];
									$LName = $row_settings['lname'];
									$ctzid = $row_settings['ctz_id'];
if(($ctzid<1000000000000))
{
  if(!preg_match('/[a-zA-Z\.]/i', $ctzid))
  {
  $err[]= "ในครั้งหน้า ให้นำบัตร ประชาชนมาด้วย";
  }
}
	  echo "<p>";
	  /******************** ERROR MESSAGES*************************************************
	  This code is to show error messages 
	  **************************************************************************/
	  if(!empty($err))  {
	   echo "<div class=\"msg\">";
	  foreach ($err as $e) {
	    echo "$e <br>";
	    }
	  echo "</div>";	
	   }
	  /******************************* END ********************************/	  
	  echo "</p>";

					}
					$disc = mysqli_query($link, "select * from discount WHERE ctmid = $ctmid ");
					while( $rowd = mysqli_fetch_array($disc))
					{
						echo "   &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;   มีสิทธิส่วนลด ";
						echo $rowd['percent'];
						echo " %";
						$perdc = $rowd['percent']/100;
					}	
					echo "</h3>";
							?>
							</div>
							<table style="background-color: rgb(255, 204, 153); width: 80%; text-align: center;
									margin-left: auto; margin-right: auto;" border="1" cellpadding="3" cellspacing="3">								
								<TR BGCOLOR="#99CCFF"><TH>รายการ</TD><TH>บาท</TD></TR>
								<TR BGCOLOR="#FFFFCC">
									<th>ยอดรวมค่า ยาและผลิตภัณฑ์</th>
									<th><input type="hidden" name="ja_price" value="<?php echo $_SESSION['price'];?>">
									<?php echo $_SESSION['price'];?></th>
								</tr>
								<tr></tr><tr></tr><tr></tr>
								<TR BGCOLOR="#FFFFCC">
									<th><big><big>ยอดที่จ่าย</big></big></th>
									<th><input STYLE="color: #8A0808; font-family: Verdana; font-weight: bold; font-size: 16px; background-color: #088A68; text-align: center;" type="number" name="ja_qty" size="10"  onChange="orderTotal(this.form, 'ja')" value="<?php 
									echo $_SESSION['price'];?>" min=0 max=<?php echo $_SESSION['price'];?>></th>
								</tr>
								<form method="get" action="recprint.php">
								<tr></tr><tr></tr><tr></tr>
								<TR BGCOLOR="#FFFFCC">
									<th>รับมา</th>
									<th><input type="number" min=0 name="ja_cash" size="10" onChange="orderTotal(this.form,'ja')" ></th>
								</tr>
								<tr></tr><tr></tr><tr></tr>
								<TR BGCOLOR="#FFFFCC">
									<th>เงินทอน</th>
									<th><input type="text" name="ja_stVis" size="10" onChange="visTotal(this.form, 'ja')">
									<input type="hidden" name="ja_stHold"></th>
								</tr>
								<tr></tr><tr></tr><tr></tr>
								<TR BGCOLOR="#FFFFCC">
									<th>ยอดค้างชำระ</th>
									<th><input type="text" name="ja_deb" size="10" onChange="visTotal(this.form, 'ja')" >
									<input type="hidden" name="ja_dbHold"></th>
								</tr>
								</form>
							</table>
							<br>
		<div style="text-align: center;">
		<div style="display:none;"><input type="submit" name="OK" value="จ่าย"></div>
			<input type="submit" name="OK" value="ใบเสร็จรับเงิน">* ต้องใช้คู่กับ <a href="../main/remedcert.php" TARGET="MAIN">ใบรับรองแพทย์</a>
			<br>
			<br>
			<input type="submit" name="OK" value="จ่าย">
			<br>
			<br>
		</div>	
</form>
</body></html>