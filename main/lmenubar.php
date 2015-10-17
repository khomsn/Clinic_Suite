<?php 
include '../login/dbc.php';
page_protect();
?>
<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
</head>

<body >
		<?php  
			/*********************** MYACCOUNT MENU ****************************
			This code shows my account menu only to logged in users. 
			Copy this code till END and place it in a new html or php where
			you want to show myaccount options. This is only visible to logged in users
			*******************************************************************/
			if (isset($_SESSION['user_id']))
			{
		?>		<div class="myaccount">
				<div><img src="<?php echo $_SESSION['user_avatar_file']; ?>" width="88" height="88"/></div><br>
				<a href="../login/myaccount.php" TARGET="_top">Main Menu</a><br>
				<p><strong>Clinic Menu</strong></p>
				<a href="../main/search_pt.php" TARGET="_top">ค้นหารายชื่อผู้ป่วย</a><br>
				<a href="../main/PIDregister.php" TARGET="_top">ลงทะเบียนผู้ป่วยใหม่</a><br><br>
				<a href="../main/p_to_doc.php" TARGET="_top">ผู้ป่วยรอตรวจ</a><br>
				<a href="../main/pt_to_obs.php" TARGET="_top">ผู้ป่วยรอสังเกตอาการ</a><br>
				<a href="../main/pt_to_lab.php" TARGET="_top">ผู้ป่วยรอตรวจ LAB</a><br>
				<a href="../main/pt_to_trm.php" TARGET="_top">ผู้ป่วยรอ Treatment</a><br>
				<a href="../main/ptodrug.php" TARGET="_top">ผู้ป่วยรอรับยา</a><br>
				<br>
				<a href="../login/logout.php" TARGET="_top">Logout </a><br>
				</div>
		<?php 		
			} 
		/*******************************END**************************/
		?>
</body></html>