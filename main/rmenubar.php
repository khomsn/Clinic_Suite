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
<?php include '../libs/popup.php';?>
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
				<br>
				<br>
				<br>
				<br>
				<br>

				<p><strong>Clinic Menu</strong></p>
				<?php 
				$ptin = mysqli_query($link, "select * from $tmp ");
				$row_settings = mysqli_fetch_array($ptin);
				if (ltrim($row_settings['csf']) ==="" or $_SESSION['user_accode']%11!=0)
				{
				echo "<a href='../main/prehist.php' TARGET='MAIN'>ตรวจร่างกายเบื่องต้น</a><br><br>";
				}
				if (ltrim($row_settings['csf']) !=="")
				{
				echo "<a href='../main/histaking.php' TARGET='MAIN'>ประวัติ และ ตรวจร่างกาย</a><br><br>";
				echo "<a href='../main/investigation.php' TARGET='MAIN'>Investigation</a><br><br>";
				  if($row_settings['medcert']==1)echo "<a href='../docform/Medical_Certificate_2551.php' TARGET='MAIN'>Med Certificate</a><br><br>";
				  if($row_settings['medcert']==2)echo "<a href='../docform/Medical_Certificate.php' TARGET='MAIN'>Med Certificate</a><br><br>";
				echo "<a href='../main/treatment.php' TARGET='MAIN'>หัตถการ</a><br><br>";
				echo "<a href='../main/prescript.php' TARGET='MAIN'>สั่งยา</a><br><br>";
				
				echo "<a href='../main/obsnote.php' TARGET='MAIN'>Observe Note</a><br><br>";
				echo "<a href='../main/appointment.php' TARGET='MAIN'>Appointment</a><br><br>";
				}
				?>
				<a HREF="../main/opdpage.php" onClick="return popup(this,'name','800','600','yes');" >OPD Card</a><br><br>
				</div>
				
		<?php 		
			} 
		/*******************************END**************************/
		?>
</body></html>