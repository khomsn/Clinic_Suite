<?php 
include '../login/dbc.php';
page_protect();
?>
<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<link rel="stylesheet" href="../public/css/styles.css">
</head>

<body >
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
		?>	<div class="myaccount">
			<div><img src="<?php echo $_SESSION['user_avatar_file']; ?>" /></div><br>
			<a href="../login/myaccount.php" TARGET="_top">Main Menu</a><br><br>
			<p><strong>Counter Menu</strong></p><br>
			<a href="../main/mycounter.php" TARGET="_top">My Counter</a><br><br>
			<a href="../main/pt_to_scr.php">ผู้ป่วยรอซักประวัติ</a><br><br>			
			<a href="../main/p_to_doc.php" TARGET="_top">ผู้ป่วยรอตรวจ</a><br><br>
			<a href="../main/pt_to_obs.php" TARGET="_top">ผู้ป่วยรอสังเกตอาการ</a><br><br>
			<a href="../main/pt_to_lab.php" TARGET="_top">ผู้ป่วยรอ Lab</a><br><br>
			<a href="../main/pt_to_trm.php" TARGET="_top">ผู้ป่วยรอ Treatment</a><br><br>
			<a href="../main/ptodrug.php" TARGET="_top">ผู้ป่วยรอรับยา</a><br><br>
			<br>
			<br>
			<a href="../login/logout.php" TARGET="_top" >Logout </a><br>
			</div>
		<?php 		
			} 
		/*******************************END**************************/
		?>
</body></html>