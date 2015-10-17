<?php 
/*************** PHP LOGIN SCRIPT V 2.3*********************
(c) Balakrishnan 2009. All Rights Reserved

Usage: This script can be used FREE of charge for any commercial or personal projects. Enjoy!

Limitations:
- This script cannot be sold.
- This script should have copyright notice intact. Dont remove it please...
- This script may not be provided for download except from its original site.

For further usage, please contact me.

***********************************************************/
include 'dbc.php';

$err = array();

foreach($_GET as $key => $value) {
	$get[$key] = filter($value); //get variables are filtered.
}

if ($_POST['doLogin']=='Login')
{

foreach($_POST as $key => $value) {
	$data[$key] = filter($value); // post variables are filtered
}


$user_email = $data['usr_email'];
$pass = $data['pwd'];
$user_email = mysqli_real_escape_string($link,$user_email); 
$pass = mysqli_real_escape_string($link,$pass);

if (strpos($user_email,'@') === false) {
    $user_cond = "user_name='$user_email'";
} else {
      $user_cond = "user_email='$user_email'";
    
}

	
$result = mysqli_query($link, "SELECT `id`,`pwd`,`full_name`,`approved`,`user_level`,`accode`,`staff_id`,`user_background`,`catcenable`,`ddil` FROM users WHERE $user_cond AND `banned` = '0' ") or die (mysqli_error($link)); 
$num = mysqli_num_rows($result);

  // Match row found with more than 1 results  - the user is authenticated. 
    if ( $num > 0 ) { 
	
	list($id,$pwd,$full_name,$approved,$user_level,$user_accode,$staff_id,$bgimage,$catc,$ddil) = mysqli_fetch_row($result);
	
	if(!$approved) {
	//$msg = urlencode("Account not activated. Please check your email for activation code");
	$err[] = FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET;
	
	//header("Location: login.php?msg=$msg");
	 //exit();
	 }
	 
		//check against salt
	if ($pwd === PwdHash($pass,substr($pwd,0,9))) { 
	if(empty($err)){			

     // this sets session and logs user in  
       session_start();
	   session_regenerate_id (true); //prevent against session fixation attacks.

	   // this sets variables in the session 
		$_SESSION['user_id']= $id;  
		$_SESSION['user_name'] = $full_name;
		$_SESSION['user_level'] = $user_level;
		$_SESSION['user_accode'] = $user_accode;
		$_SESSION['staff_id'] = $staff_id;
		$_SESSION['catc'] = $catc;
		$_SESSION['ddil'] = $ddil;
		$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
		
		//update the timestamp and key for cookie
		$stamp = time();
		$ckey = GenKey();
		mysqli_query($link, "update users set `ctime`='$stamp', `ckey` = '$ckey' where id='$id'") or die(mysqli_error($link));
		
		//set a cookie 
		
	   if(isset($_POST['remember'])){
				  setcookie("user_id", $_SESSION['user_id'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("user_key", sha1($ckey), time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("user_name",$_SESSION['user_name'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				   }
		//set avatar file path   
		$target_file_path = AVATAR_PATH . $_SESSION['user_id'] . ".jpg";
		$_SESSION['user_avatar_file'] = $target_file_path;
		$_SESSION['user_background'] = $bgimage;
		//
		$sd = date("d");
		if($sd==28)  header("Location: ../main/drugusestat.php");
		else  header("Location: myaccount.php");
		 }
		}
		else
		{
		//$msg = urlencode("Invalid Login. Please try again with correct user email and password. ");
		$err[] = FEEDBACK_PASSWORD_WRONG;
		//header("Location: login.php?msg=$msg");
		}
	} else {
		$err[] = FEEDBACK_USER_DOES_NOT_EXIST;
	  }		
}
					 
					 

?>
<html>
<head>
<title>Members Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
<script>
$(document).ready(function(){
  $("#logForm").validate();
});
</script>
<script type="text/javascript">
    if(window.top.location != window.location) 
    {
        window.top.location.href = window.location.href; 
    }
</script>        
<link rel="stylesheet" href="../public/css/styles.css">

</head>

<body  style="background-image: url(../image/login.jpeg);">
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr> 
    <td width="160" valign="top"><p>&nbsp;</p>
      <p>&nbsp; </p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td width="732" valign="top"><p>&nbsp;</p>
      <h3 class="titlehdr">Login Users 
      </h3>  
	  <p>
	  <?php
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
	  ?></p>
      <form action="login.php" method="post" name="logForm" id="logForm" >
        <table width="65%" border="0" cellpadding="4" cellspacing="4" class="loginform">
          <tr> 
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr> 
            <td width="28%">Username / Email</td>
            <td width="72%"><input name="usr_email" type="text" class="required" id="txtbox" size="25" autofocus></td>
          </tr>
          <tr> 
            <td>Password</td>
            <td><input name="pwd" type="password" class="required password" id="txtbox" size="25"></td>
          </tr>
          <tr> 
            <td colspan="2"><div align="center">
                <input name="remember" type="checkbox" id="remember" value="1">
                Remember me</div></td>
          </tr>
          <tr> 
            <td colspan="2"> <div align="center"> 
                <p> 
                  <input name="doLogin" type="submit" id="doLogin3" value="Login">
                </p>
                <p><a href="register.php">Register Free</a><font color="#FF6600"> 
                  |</font> <a href="forgot.php">Forgot Password</a> <font color="#FF6600"> 
                  </font></p>
                <p><span style="font: normal 9px verdana">Powered by <a href="http://php-login-script.com">PHP 
                  Login Script v2.3 modified by Khomsn</a></span></p>
              </div></td>
          </tr>
        </table>
        <div align="center"></div>
        <p align="center">&nbsp; </p>
      </form>
      <p>&nbsp;</p>
	   
      </td>
    <td width="196" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
</table>

</body>
</html>