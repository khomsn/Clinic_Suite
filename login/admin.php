<?php 
include 'dbc.php';

page_protect();

if(!checkAdmin()) {
header("Location: login.php");
exit();
}

$pin = mysqli_query($link, "select MAX(id) from users ");
$maxrow = mysqli_fetch_array($pin);
$maxid = $maxrow[0];

$page_limit = 10; 


$host  = $_SERVER['HTTP_HOST'];
$host_upper = strtoupper($host);
//$login_path = @ereg_replace('admin','',dirname($_SERVER['PHP_SELF']));echo "3";preg_replace_callback
$login_path = @preg_replace_callback('admin','',dirname($_SERVER['PHP_SELF']));
$path   = rtrim($login_path, '/\\');

// filter GET values
foreach($_GET as $key => $value) {
	$get[$key] = filter($value);
}

foreach($_POST as $key => $value) {
	$post[$key] = filter($value);
}

for($userid = 50;$userid <= $maxid; $userid++)
{
  $u[] = $_POST[$userid];
}

if($post['doBan'] == 'Ban') {

if(!empty($u)) {
	foreach ($u as $uid) {
		$id = filter($uid);
		mysqli_query($link, "update users set banned='1' where id='$id' and `user_name` <> 'admin'");
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];;
 
 header("Location: $ret");
 exit();
}

if($_POST['doUnban'] == 'Unban') {

if(!empty($u)) {
	foreach ($u as $uid) {
		$id = filter($uid);
		mysqli_query($link, "update users set banned='0' where id='$id'");
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];;
 
 header("Location: $ret");
 exit();
}

if($_POST['doDelete'] == 'Delete') {

if(!empty($u)) {
	foreach ($u as $uid) {
		$id = filter($uid);
		mysqli_query($link, "delete from users where id='$id' and `user_name` <> 'admin'");
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];;
 
 header("Location: $ret");
 exit();
}


if($_POST['doSave'] == 'Save')
{


}

if($_POST['doApprove'] == 'Approve') {

if(!empty($u)) {
	foreach ($u as $uid) {
		$id = filter($uid);
		mysqli_query($link, "update users set approved='1' where id='$id'");
		
	list($to_email) = mysqli_fetch_row(mysqli_query($link, "select user_email from users where id='$uid'"));	
 
$message = 
"Hello,\n
Thank you for registering with us. Your account has been activated...\n

*****LOGIN LINK*****\n
http://$host$path/login.php

Thank You

Administrator
$host_upper
______________________________________________________
THIS IS AN AUTOMATED RESPONSE. 
***DO NOT RESPOND TO THIS EMAIL****
";

@mail($to_email, "User Activation", $message,
    "From: \"Member Registration\" <auto-reply@$host>\r\n" .
     "X-Mailer: PHP/" . phpversion()); 
	 
	}
 }
 
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];	 
 header("Location: $ret");
 exit();
}

$rs_all = mysqli_query($link, "select count(*) as total_all from users") or die(mysqli_error($link));
$rs_active = mysqli_query($link, "select count(*) as total_active from users where approved='1'") or die(mysqli_error($link));
$rs_total_pending = mysqli_query($link, "select count(*) as tot from users where approved='0'");						   

list($total_pending) = mysqli_fetch_row($rs_total_pending);
list($all) = mysqli_fetch_row($rs_all);
list($active) = mysqli_fetch_row($rs_active);

?>
<html>
<head>
<title>Administration Main Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
  <script>
  $(document).ready(function(){
    $("#addForm").validate();
  });
  </script>
<link rel="stylesheet" href="../public/css/styles.css">  
</head>

<body>
<div class="pos_l_fix">

<div class="myaccount">
<img src="<?php echo $_SESSION['user_avatar_file']; ?>" />
<a href="../login/myaccount.php"><p><strong>Main Menu</strong></p></a><br><br>
<a href="../login/mysettings.php">My Setting</a><br><br>
<?php 
if ($_SESSION['user_accode']%13==0)
echo "<a href=../main/staff.php>Staff</a><br><br>";
if ($_SESSION['user_accode']%11==0)
{
echo "<a href=../main/ordertemplate.php>Order Template</a><br><br>";
echo "<a href=../main/catcenable.php>Drug Cat C</a><br><br>";
}
if ($_SESSION['user_accode']%2==0)
echo "<a href=../main/comptemplate.php>รายชื่อบริษัท</a><br><br>";
?>
<a href="../login/logout.php">Logout </a><br><br><br>
<?php
if (checkAdmin()) 
{
/*******************************END**************************/
?>
      <p> <a href="../login/admin.php">Admin CP </a></p>

<?php 
}
if($_SESSION['user_accode']%13==0) echo "<p> <a href=progpara.php>Programme Parameter</a></p>";

?>
</div>
</div>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><td style="text-align: center; width: 130px; "></td>
    <td width="74%" valign="top" style="padding: 10px;"><h2><font color="#FF0000">Administration 
        Page</font></h2>
      <table width="100%" border="0" cellpadding="5" cellspacing="0" class="myaccount">
        <tr>
          <td>Total users: <?php echo $all;?></td>
          <td>Active users: <?php echo $active; ?></td>
          <td>Pending users: <?php echo $total_pending; ?></td>
        </tr>
      </table>
      <p><?php 
	  if(!empty($msg)) {
	  echo $msg[0];
	  }
	  ?></p>
      <table width="80%" border="0" align="center" cellpadding="10" cellspacing="0" style="background-color: #E4F8FA;padding: 2px 5px;border: 1px solid #CAE4FF;" >
        <tr>
          <td><form name="form1" method="get" action="admin.php">
              <p align="center">Search 
                <input name="q" type="text" id="q" size="40">
                <br>
                [Type email or user name] </p>
              <p align="center"> 
                <input type="radio" name="qoption" value="pending">
                Pending users 
                <input type="radio" name="qoption" value="recent">
                Recently registered 
                <input type="radio" name="qoption" value="banned">
                Banned users <br>
                <br>
                [You can leave search blank to if you use above options]</p>
              <p align="center"> 
                <input name="doSearch" type="submit" id="doSearch2" value="Search">
              </p>
              </form></td>
        </tr>
      </table>
      <p>
        <?php if ($get['doSearch'] == 'Search') {
	  $cond = '';
	  if($get['qoption'] == 'pending') {
	  $cond = "where `approved`='0' order by date desc";
	  }
	  if($get['qoption'] == 'recent') {
	  $cond = "order by date desc";
	  }
	  if($get['qoption'] == 'banned') {
	  $cond = "where `banned`='1' order by date desc";
	  }
	  
	  if($get['q'] == '') { 
	  $sql = "select * from users $cond"; 
	  } 
	  else { 
	  $sql = "select * from users where `user_email` = '$_REQUEST[q]' or `user_name`='$_REQUEST[q]' ";
	  }

	  
	  $rs_total = mysqli_query($link, $sql) or die(mysqli_error($link));
	  $total = mysqli_num_rows($rs_total);
	  
	  if (!isset($_GET['page']) )
		{ $start=0; } else
		{ $start = ($_GET['page'] - 1) * $page_limit; }
	  
	  $rs_results = mysqli_query($link, $sql . " limit $start,$page_limit") or die(mysqli_error($link));
	  $total_pages = ceil($total/$page_limit);
	  
	  ?>
      <p>Approve -&gt; A notification email will be sent to user notifying activation.<br>
        Ban -&gt; No notification email will be sent to the user. 
      <p><strong>*Note: </strong>Once the user is banned, he/she will never be 
        able to register new account with same email address. 
      <p align="right"> 
        <?php 
	  
	  // outputting the pages
		if ($total > $page_limit)
		{
		echo "<div><strong>Pages:</strong> ";
		$i = 0;
		while ($i < $page_limit)
		{
		
		
		$page_no = $i+1;
		$qstr = ereg_replace("&page=[0-9]+","",$_SERVER['QUERY_STRING']);
		echo "<a href=\"admin.php?$qstr&page=$page_no\">$page_no</a> ";
		$i++;
		}
		echo "</div>";
		}  ?>
		</p>
		<form name "searchform" action="admin.php" method="post" >
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
          <tr bgcolor="#E6F3F9"> 
            <td width="4%"><strong>ID</strong></td>
            <td> <strong>Date</strong></td>
            <td><div align="center"><strong>User Name</strong></div></td>
            <td width="24%"><strong>Email</strong></td>
            <td width="10%"><strong>Approval</strong></td>
            <td width="10%"> <strong>Banned</strong></td>
            <td width="25%">&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td width="10%">&nbsp;</td>
            <td width="17%"><div align="center"></div></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <?php while ($rrows = mysqli_fetch_array($rs_results)) {?>
          <tr> 
            <td><input name="<?php echo $rrows['id']; ?>" type="checkbox" value="<?php echo $rrows['id']; ?>" id="<?php echo $rrows['id']; ?>"></td>
            <td><?php echo $rrows['date']; ?></td>
            <td> <div align="center"><?php echo $rrows['user_name'];?></div></td>
            <td><?php echo $rrows['user_email']; ?></td>
            <td> <span id="approve<?php echo $rrows['id']; ?>"> 
              <?php if(!$rrows['approved']) { echo "Pending"; } else {echo "Active"; }?>
              </span> </td>
            <td><span id="ban<?php echo $rrows['id']; ?>"> 
              <?php if(!$rrows['banned']) { echo "no"; } else {echo "yes"; }?>
              </span> </td>
            <td> <font size="2"><a href="javascript:void(0);" onclick='$.get("do.php",{ cmd: "approve", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#approve<?php echo $rrows['id']; ?>").html(data); });'>Approve</a> 
              <a href="javascript:void(0);" onclick='$.get("do.php",{ cmd: "ban", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#ban<?php echo $rrows['id']; ?>").html(data); });'>Ban</a> 
              <a href="javascript:void(0);" onclick='$.get("do.php",{ cmd: "unban", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#ban<?php echo $rrows['id']; ?>").html(data); });'>Unban</a> 
              <a href="javascript:void(0);" onclick='$("#edit<?php echo $rrows['id'];?>").show("slow");'>Edit</a> 
              </font> </td>
          </tr>
          <tr> 
            <td colspan="7">
			
			<div style="display:none;font: normal 11px arial; padding:10px; background: #e6f3f9" id="edit<?php echo $rrows['id']; ?>">
			
			<input type="hidden" name="id<?php echo $rrows['id']; ?>" id="id<?php echo $rrows['id']; ?>" value="<?php echo $rrows['id']; ?>">
			User Name: <input name="user_name<?php echo $rrows['id']; ?>" id="user_name<?php echo $rrows['id']; ?>" type="text" size="10" value="<?php echo $rrows['user_name']; ?>" >
			User Email:<input id="user_email<?php echo $rrows['id']; ?>" name="user_email<?php echo $rrows['id']; ?>" type="text" size="20" value="<?php echo $rrows['user_email']; ?>" ><br>
			Level: <input id="user_level<?php echo $rrows['id']; ?>" name="user_level<?php echo $rrows['id']; ?>" type="text" size="5" value="<?php echo $rrows['user_level']; ?>" >
<script type="text/javascript">
// Created by: Jay Rumsey | http://www.nova.edu/~rumsey/
// This script downloaded from JavaScriptBank.com

function UpdateRL<?php echo  $rrows['id'];  ?>() {
  var sumRL<?php echo  $rrows['id'];  ?> = 1;
  var gnRL<?php echo  $rrows['id'];  ?>, elRL<?php echo  $rrows['id'];  ?>;
  for (i=1; i<4; i++) {
    gnRL<?php echo  $rrows['id'];  ?> = 'rl<?php echo  $rrows['id'];  ?>'+i;
    elRL<?php echo  $rrows['id'];  ?> = document.getElementById(gnRL<?php echo  $rrows['id'];  ?>);
    if (elRL<?php echo  $rrows['id'];  ?>.checked == true) { sumRL<?php echo  $rrows['id'];  ?> =  Number(elRL<?php echo  $rrows['id'];  ?>.value); }
  }
  document.getElementById('user_level<?php echo $rrows['id']; ?>').value = sumRL<?php echo  $rrows['id'];  ?>;
} 
</script>
<input type="radio"  name="rl<?php echo $rrows['id']; ?>" id="rl<?php echo $rrows['id']; ?>1" value="0" onclick="UpdateRL<?php echo  $rrows['id'];  ?>()" <?php if($rrows['user_level']==0) echo "checked";?>>Guest<sup>0</sup>
<input type="radio"  name="rl<?php echo $rrows['id']; ?>" id="rl<?php echo $rrows['id']; ?>2" value="2" onclick="UpdateRL<?php echo  $rrows['id'];  ?>()" <?php if($rrows['user_level']==2) echo "checked";?>>User<sup>2</sup>
<input type="radio"  name="rl<?php echo $rrows['id']; ?>" id="rl<?php echo $rrows['id']; ?>3" value="5" onclick="UpdateRL<?php echo  $rrows['id'];  ?>()" <?php if($rrows['user_level']==5) echo "checked";?>>Admin<sup>5</sup>
<br>Access Code: <input id="user_accode<?php echo $rrows['id']; ?>" name="user_accode<?php echo $rrows['id']; ?>" type="text" value="<?php echo $rrows['accode'];?>" >
<script type="text/javascript">
// Created by: Jay Rumsey | http://www.nova.edu/~rumsey/
// This script downloaded from JavaScriptBank.com

function UpdateAccode<?php echo  $rrows['id'];  ?>() {
  var sum<?php echo  $rrows['id'];  ?> = 1;
  var gn<?php echo  $rrows['id'];  ?>, elem<?php echo  $rrows['id'];  ?>;
  for (i=1; i<8; i++) {
    gn<?php echo  $rrows['id'];  ?> = 'game<?php echo  $rrows['id'];  ?>'+i;
    elem<?php echo  $rrows['id'];  ?> = document.getElementById(gn<?php echo  $rrows['id'];  ?>);
    if (elem<?php echo  $rrows['id'];  ?>.checked == true) { sum<?php echo  $rrows['id'];  ?> = sum<?php echo  $rrows['id'];  ?> * Number(elem<?php echo  $rrows['id'];  ?>.value); }
  }
  document.getElementById('user_accode<?php echo $rrows['id']; ?>').value = sum<?php echo  $rrows['id'];  ?>;
} 
</script>
<input type="checkbox" id="game<?php echo $rrows['id']; ?>1" value=1 onclick="UpdateAccode<?php echo  $rrows['id'];  ?>()" checked >Staff<sup>1</sup>
<input type="checkbox" id="game<?php echo $rrows['id']; ?>2" value=2 onclick="UpdateAccode<?php echo  $rrows['id'];  ?>()" <?php if($rrows['accode']%2==0) echo "checked";?>>Account<sup>2</sup>
<input type="checkbox" id="game<?php echo $rrows['id']; ?>3" value=3 onclick="UpdateAccode<?php echo  $rrows['id'];  ?>()" <?php if($rrows['accode']%3==0) echo "checked";?>>N-Aids<sup>3</sup>
<input type="checkbox" id="game<?php echo $rrows['id']; ?>4" value=5 onclick="UpdateAccode<?php echo  $rrows['id'];  ?>()" <?php if($rrows['accode']%5==0) echo "checked";?>>Nurse<sup>5</sup>
<input type="checkbox" id="game<?php echo $rrows['id']; ?>5" value=7 onclick="UpdateAccode<?php echo  $rrows['id'];  ?>()"  <?php if($rrows['accode']%7==0) echo "checked";?>>Pharmacist<sup>7</sup>
<input type="checkbox" id="game<?php echo $rrows['id']; ?>6" value=11 onclick="UpdateAccode<?php echo  $rrows['id'];  ?>()" <?php if($rrows['accode']%11==0) echo "checked";?>>Doctor<sup>11</sup>
<input type="checkbox" id="game<?php echo $rrows['id']; ?>7" value=13 onclick="UpdateAccode<?php echo  $rrows['id'];  ?>()" <?php if($rrows['accode']%13==0) echo "checked";?>>Manager<sup>13</sup>
				    
			<br><br>New Password: <input id="pass<?php echo $rrows['id']; ?>" name="pass<?php echo $rrows['id']; ?>" type="text" size="20" value="" > (leave blank)
			<input name="doSave" type="button" id="doSave" value="Save" 
			onclick='$.get("do.php",{ cmd: "edit", pass:$("input#pass<?php echo $rrows['id']; ?>").val(),user_level:$("input#user_level<?php echo $rrows['id']; ?>").val(),user_accode:$("input#user_accode<?php echo $rrows['id']; ?>").val(),user_email:$("input#user_email<?php echo $rrows['id']; ?>").val(),user_name: $("input#user_name<?php echo $rrows['id']; ?>").val(),id: $("input#id<?php echo $rrows['id']; ?>").val() } ,function(data){ $("#msg<?php echo $rrows['id']; ?>").html(data); });'> 
			<a  onclick='$("#edit<?php echo $rrows['id'];?>").hide();' href="javascript:void(0);">close</a>
		 
		  <div style="color:red" id="msg<?php echo $rrows['id']; ?>" name="msg<?php echo $rrows['id']; ?>"></div>
		  </div>
		  
		  </td>
          </tr>
          <?php } ?>
        </table>
	    <p><br>
          <input name="doApprove" type="submit" id="doApprove" value="Approve">
          <input name="doBan" type="submit" id="doBan" value="Ban">
          <input name="doUnban" type="submit" id="doUnban" value="Unban">
          <input name="doDelete" type="submit" id="doDelete" value="Delete">
          <input name="query_str" type="hidden" id="query_str" value="<?php echo $_SERVER['QUERY_STRING']; ?>">
          <strong>Note:</strong> If you delete the user can register again, instead 
          ban the user. </p>
        <p><strong>Edit Users:</strong> To change email, user name or password, 
          you have to delete user first and create new one with same email and 
          user name.</p>
      </form>
	  
	  <?php } ?>
      &nbsp;</p>
	  <?php
	  if($_POST['doSubmit'] == 'Create')
{
$rs_dup = mysqli_query($link, "select count(*) as total from users where user_name='$post[user_name]' OR user_email='$post[user_email]'") or die(mysqli_error($link));
list($dups) = mysqli_fetch_row($rs_dup);

if($dups > 0) {
	die("The user name or email already exists in the system");
	}

if(!empty($_POST['pwd'])) {
  $pwd = $post['pwd'];	
  $hash = PwdHash($post['pwd']);
 }  
 else
 {
  $pwd = GenPwd();
  $hash = PwdHash($pwd);
  
 }

 $sql = "INSERT INTO users (`user_name`,`user_email`,`pwd`,`approved`,`date`,`user_level`,`accode`)
         VALUES ('$post[user_name]','$post[user_email]','$hash','1',now(),'$post[user_level]','$post[user_accode]')";
         
mysqli_query($link, $sql) or die(mysqli_error($link)); 


$subject = EMAIL_UP_SUBJECT . PROJECT;
$message = 
"Thank you for registering with us. Here are your login details...\n<br><br>
User Email: $post[user_email] \n<br>
Passwd: $pwd \n<br><br>

*****LOGIN LINK*****\n<br><br>
<a href=http://$host$path/login.php>http://$host$path/login.php</a><br><br>

Thank You<br><br>

Administrator<br>
$host_upper<br><br>
______________________________________________________<br>
THIS IS AN AUTOMATED RESPONSE. <br>
***DO NOT RESPOND TO THIS EMAIL****
";

if($_POST['send'] == '1') {
      /*-----------------------------------------------------------------------*/
      $toemail = $post[user_email];
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
	    $msg[]=FEEDBACK_ACCOUNT_SUCCESSFULLY_CREATED;
	}
 }
 
 
echo "<div class=\"msg\">User created with password $pwd....done.</div>"; 
}

	  ?>
	  
      <h2><font color="#FF0000">Create New User</font></h2>
      <table width="80%" border="0" cellpadding="5" cellspacing="2" class="myaccount">
        <tr>
          <td><form name="form1" method="post" action="admin.php" id="addForm" >
              <p>User ID 
                <input name="user_name" type="text" id="user_name" class="required">
                (Type the username)</p>
              <p>Email 
                <input name="user_email" type="text" id="user_email" class="required">
              </p>
              <p>User Level 
                <select name="user_level" id="user_level">
                  <option value="2">User</option>
                  <option value="5">Admin</option>
                </select>
              </p>
              <p>User Access Code 
                <select name="user_accode" id="user_accode">
                  <option value="1">Staff</option>
                  <option value="2">Account</option>
                  <option value="3">Naids</option>
                  <option value="5">Nurse</option>
                  <option value="7">Pharmacist</option>
                  <option value="11">Doctor</option>
                  <option value="13">Manager</option>
                </select>
              </p>
              <p>Password 
                <input name="pwd" type="text" id="pwd">
                (if empty a password will be auto generated)</p>
              <p> 
                <input name="send" type="checkbox" id="send" value="1" checked>
                Send Email</p>
              <p> 
                <input name="doSubmit" type="submit" id="doSubmit" value="Create">
              </p>
            </form>
            <p>**All created users will be approved by default.</p></td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td width="12%">&nbsp;</td>
  </tr>
</table>
</body>
</html>
