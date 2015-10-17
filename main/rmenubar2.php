<?php 
include '../login/dbc.php';
page_protect();
$id = $_SESSION['patdesk'];

$tmp = "tmp_".$id;
//$pdir = PT_AVATAR_PATH.$id."/";
$pdir = PT_AVATAR_PATH;
 
?>
<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
<?php
include '../libs/popup.php';
include '../libs/refreshpay.php';
?>
</head>

<body >
<div class="pt_avatar">
<img src="<?php $avatar = $pdir. "pt_". $id . ".jpg"; echo $avatar; ?>" width="120" height="120" />
</div>

<br>
<br>
<br>
		<?php 
			/*********************** MYACCOUNT MENU ****************************
			This code shows my account menu only to logged in users. 
			Copy this code till END and place it in a new html or php where
			you want to show myaccount options. This is only visible to logged in users
			*******************************************************************/
			if (isset($_SESSION['user_id']))
			{
		?>		<div class="myaccount">
				<p><strong>Clinic Menu</strong></p>
				<?php 
				$ptin = mysqli_query($link, "select * from $tmp ");
				$row_settings = mysqli_fetch_array($ptin);
				if ($row_settings['csf'] =="")
				{
				echo "<a href='../main/prehist.php' TARGET='MAIN'>ตรวจร่างกายเบื่องต้น</a><br><br>";
				}
				?>
				<a HREF="../main/opdpage.php" onClick="return popup(this,'name','800','600','yes');" >OPD Card</a><br><br>
				<a href="../main/remedcert.php" TARGET="MAIN">ขอใบรับรองแพทย์</a><br><br>
				<a href="../main/treatment.php" TARGET="MAIN">หัตถการ</a><br><br>
				<a href="../main/prescript.php" TARGET="MAIN">สั่งยา</a><br><br>
				<a href="../main/ptpay.php" TARGET="MAIN">สรุปรายการยา</a><br><br><br>
				<a href="../main/pay.php" TARGET="MAIN">ยอดเงินรวม</a><br><br>
				<?php 
				if($_SESSION['price']>='0')
				{
				  echo "<a href='../main/payment.php' TARGET='MAIN'>จ่ายเงิน</a><br><br>";
				}
				if($_SESSION['user_accode']%13==0)
				{
				?>
				<a HREF="discount.php" onClick="return popup(this,'name','400','150','yes');" >กำหนดส่วนลด</a><br><br>
				<?php 
				}
				?>
				</div>
		<?php 		
			} 
		/*******************************END**************************/
		?>
</body></html>