<?php 
include '../config/dbc.php';

foreach($_GET as $key => $value) 
{
    $get[$key] = filter($value);
}

/******** EMAIL ACTIVATION LINK**********************/
if(isset($get['user']) && !empty($get['activ_code']) && !empty($get['user']) && is_numeric($get['activ_code']) ) 
{

    $err = array();
    $msg = array();

    $user = mysqli_real_escape_string($link, $get['user']);
    $activ = mysqli_real_escape_string($link, $get['activ_code']);

    //check if activ code and user is valid
    $rs_check = mysqli_query($link, "select id from users where md5_id='$user' and activation_code='$activ'") or die (mysqli_error($link)); 
    $num = mysqli_num_rows($rs_check);
    // Match row found with more than 1 results  - the user is authenticated. 
    if ( $num <= 0 )
    { 
        $err[] = "Sorry no such account exists or activation code invalid.";
        exit();
    }

    if(empty($err))
    {
        // set the approved field to 1 to activate the account
        $rs_activ = mysqli_query($link, "update users set approved='1' WHERE md5_id='$user' AND activation_code = '$activ' ") or die(mysqli_error($link));
        $msg[] = "Thank you. Your account has been activated.";
        $done = 1;
        exit();

    }
}

/******************* ACTIVATION BY FORM**************************/
if ($_POST['doActivate']=='Activate')
{
    $err = array();
    $msg = array();

    $user_email = mysqli_real_escape_string($link, $_POST['user_email']);
    $activ = mysqli_real_escape_string($link, $_POST['activ_code']);
    //check if activ code and user is valid as precaution
    $rs_check = mysqli_query($link, "select id from users where user_email='$user_email' and activation_code='$activ'") or die (mysqli_error($link)); 
    $num = mysqli_num_rows($rs_check);
    // Match row found with more than 1 results  - the user is authenticated. 
    if ( $num <= 0 )
    { 
        $err[] = "Sorry no such account exists or activation code invalid.";
        exit();
    }
    //set approved field to 1 to activate the user
    if(empty($err))
    {
        $rs_activ = mysqli_query($link, "update users set approved='1' WHERE user_email='$user_email' AND activation_code = '$activ' ") or die(mysqli_error($link));
        $msg[] = "Thank you. Your account has been activated.";
        $done = 1;
    }
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>User Account Activation</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" type="text/javascript" src="<?php echo JSCSS_PATH;?>js/jquery-3.3.1.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo JSCSS_PATH;?>js/jquery.validate.min.js"></script>
  <script>
  $(document).ready(function(){
    $("#actForm").validate();
  });
  </script>
<link rel="stylesheet" href="<?php echo JSCSS_PATH;?>css/styles.css">
</head>
<body>
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
    <td width="732" valign="top">
<h3 class="titlehdr">Account Activation</h3>

      <p> 
        <?php
	  /******************** ERROR MESSAGES*************************************************
	  This code is to show error messages 
	  **************************************************************************/
	if(!empty($err))  {
	   echo "<div class=\"msg\">";
	  foreach ($err as $e) {
	    echo "* $e <br>";
	    }
	  echo "</div>";	
	   }
	   if(!empty($msg))  {
	    echo "<div class=\"msg\">" . $msg[0] . "</div>";

	   }	
	  /******************************* END ********************************/	  
	  ?>
      </p>
<?php
if(!$done)
{
?>
      <p>Please enter your email and activation code sent to you to your email 
        address to activate your account. Once your account is activated you can 
        <a href="login.php">login here</a>.</p>
      <form action="activate.php" method="post" name="actForm" id="actForm" >
        <table width="65%" border="0" cellpadding="4" cellspacing="4" class="loginform">
          <tr> 
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr> 
            <td width="36%">Your Email</td>
            <td width="64%"><input name="user_email" type="text" class="required email" id="txtboxn" size="25"></td>
          </tr>
          <tr> 
            <td>Activation code</td>
            <td><input name="activ_code" type="password" class="required" id="txtboxn" size="25"></td>
          </tr>
          <tr> 
            <td colspan="2"> <div align="center"> 
                <p> 
                  <input name="doActivate" type="submit" id="doLogin3" value="Activate">
                </p>
              </div></td>
          </tr>
        </table>
        <div align="center"></div>
        <p align="center">&nbsp; </p>
      </form>
<?php
}
if($done)
{
    echo "<p>Your account is activated you can 
        <a href=\"login.php\">login here</a>.</p>";
}
?>
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
