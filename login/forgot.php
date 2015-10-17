<?php 
include 'dbc.php';




/******************* ACTIVATION BY FORM**************************/
if ($_POST['doReset']=='Reset')
{
$err = array();
$msg = array();

foreach($_POST as $key => $value) {
	$data[$key] = filter($value);
}
if(!isEmail($data['user_email'])) {
$err[] = "ERROR - Please enter a valid email"; 
}

$user_email = $data['user_email'];

//check if activ code and user is valid as precaution
$rs_check = mysqli_query($link, "select id from users where user_email='$user_email'") or die (mysqli_error($link)); 
$num = mysqli_num_rows($rs_check);
  // Match row found with more than 1 results  - the user is authenticated. 
    if ( $num <= 0 ) { 
	$err[] = "Error - Sorry no such account exists or registered.";
	//header("Location: forgot.php?msg=$msg");
	//exit();
	}


if(empty($err)) {

$new_pwd = GenPwd();
$pwd_reset = PwdHash($new_pwd);
//$sha1_new = sha1($new);	
//set update sha1 of new password + salt
$rs_activ = mysqli_query($link, "update users set pwd='$pwd_reset' WHERE 
						 user_email='$user_email'") or die(mysqli_error($link));
						 
$host  = $_SERVER['HTTP_HOST'];
$host_upper = strtoupper($host);						 
						 
//send email
$subject = EMAIL_PASSWORDRESET_SUBJECT;
$message = 
"Here are your new password details ...\n<br><br><br>
User Email: $user_email \n<br>
Passwd: $new_pwd \n<br><br><br><br>

Thank You<br><br><br>

Administrator<br>
$host_upper<br><br>
______________________________________________________<br>
THIS IS AN AUTOMATED RESPONSE. <br>
***DO NOT RESPOND TO THIS EMAIL****
";
///****-------------------------------****///
      /*-----------------------------------------------------------------------*/
      $toemail = $user_email;
      /*-----------------------------------------------------------------------*/
     
	require '../libs/PHPMailer-master/PHPMailerAutoload.php';

	//Create a new PHPMailer instance
	$mail = new PHPMailer;

	//Tell PHPMailer to use SMTP
	$mail->isSMTP();

	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 0;

	//Ask for HTML-friendly debug output
	$mail->Debugoutput = 'html';

	//Set the hostname of the mail server
	$mail->Host = EMAIL_SMTP_HOST;

	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	$mail->Port = EMAIL_SMTP_PORT;

	//Set the encryption system to use - ssl (deprecated) or tls
	$mail->SMTPSecure = EMAIL_SMTP_ENCRYPTION;

	//Whether to use SMTP authentication
	$mail->SMTPAuth = EMAIL_SMTP_AUTH;

	//Username to use for SMTP authentication - use full email address for gmail
	$mail->Username = EMAIL_SMTP_USERNAME;

	//Password to use for SMTP authentication
	$mail->Password = EMAIL_SMTP_PASSWORD;

	//Set who the message is to be sent from
	$mail->setFrom(EMAIL_VERIFICATION_FROM_EMAIL, EMAIL_VERIFICATION_FROM_NAME);

	//Set who the message is to be sent to
	$mail->addAddress($toemail);

	//Set the subject line
	$mail->Subject = $subject;

	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));

	$mail->MsgHTML($message);

	//Replace the plain text body with one created manually
	//$mail->AltBody = $message;

	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');

	//send the message, check for errors
	if (!$mail->send()) {
	    $err[]=FEEDBACK_PASSWORD_RESET_MAIL_SENDING_ERROR."Mailer Error: " . $mail->ErrorInfo;
	} else {
	    $msg[]=FEEDBACK_PASSWORD_RESET_MAIL_SENDING_SUCCESSFUL."<p><font size=2 face='Arial, Helvetica, sans-serif'>An activation email 
        has been sent to your email address (dont forget to check your spam folder). 
        Please check your email and click on the activation link.<br>You can <a href='login.php'>login 
        here</a> if you have already activated your account.</font></p>
        <p>&nbsp;</p>";
	}


/*---------------------------------------------*/
						 
//$msg = urlencode();
//header("Location: forgot.php?msg=$msg");						 
//exit();
 }
}
?>
<html>
<head>
<title>Forgot Password</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
  <script>
  $(document).ready(function(){
    $("#actForm").validate();
  });
  </script>
<link rel="stylesheet" href="../public/css/styles.css">
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
<h3 class="titlehdr">Forgot Password</h3>

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
      <p>If you have forgot the account password, you can <strong>reset password</strong> 
        and a new password will be sent to your email address.</p>
	 
      <form action="forgot.php" method="post" name="actForm" id="actForm" >
        <table width="65%" border="0" cellpadding="4" cellspacing="4" class="loginform">
          <tr> 
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr> 
            <td width="36%">Your Email</td>
            <td width="64%"><input name="user_email" type="text" class="required email" id="txtboxn" size="25"></td>
          </tr>
          <tr> 
            <td colspan="2"> <div align="center"> 
                <p> 
                  <input name="doReset" type="submit" id="doLogin3" value="Reset">
                </p>
              </div></td>
          </tr>
        </table>
        <div align="center"></div>
        <p align="center">&nbsp; </p>
      </form>
	  
      <p>&nbsp;</p>
	   
      <p align="left">&nbsp; </p></td>
    <td width="196" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
</table>

</body>
</html>
