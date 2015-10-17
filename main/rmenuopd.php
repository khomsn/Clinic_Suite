<?php 
include '../login/dbc.php';
page_protect();
$id = $_SESSION['patdesk'];
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
		?>	
				<div class="myaccount">
				<div class="ptavatar">
				<img src="<?php $avatar = $pdir. "pt_". $id . ".jpg"; echo $avatar; ?>" width="120" height="120" />
				</div>
				</div>
				
		<?php 		
			} 
		/*******************************END**************************/
		?>
</body></html>