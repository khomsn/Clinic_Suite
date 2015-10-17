<?php 
/********************** MYSETTINGS.PHP**************************
This updates user settings and password
************************************************************/
include 'dbc.php';
include '../libs/resizeimage.php';

page_protect();

$err = array();
$msg = array();

if($_POST['submit']=='Upload image')
{
        $id = $_SESSION['user_id'];
 //       createAvatar($_FILES['avatar_file']['tmp_name']);
        
        if (is_dir(AVATAR_PATH) && is_writable(AVATAR_PATH)) {
           
            if (!empty ($_FILES['avatar_file']['tmp_name'])) {

                // get the image width, height and mime type
                // btw: why does PHP call this getimagesize when it gets much more than just the size ?
                $image_proportions = getimagesize($_FILES['avatar_file']['tmp_name']);

                // dont handle files > 5MB
                if ($_FILES['avatar_file']['size'] <= 5000000 ) {

                    if ($image_proportions[0] >= 100 && $image_proportions[1] >= 100) {

                        if ($image_proportions['mime'] == 'image/jpeg' || $image_proportions['mime'] == 'image/png') {

                            $target_file_path = AVATAR_PATH . $id . ".jpg";
                               
                            // creates a 44x44px avatar jpg file in the avatar folder
                            // see the function defintion (also in this class) for more info on how to use
                            resize_image($_FILES['avatar_file']['tmp_name'], $target_file_path, 120, 120, 85, true);

                            //$sth = $this->db->prepare("UPDATE users SET user_has_avatar = TRUE WHERE user_id = :user_id");
                            //$sth->execute(array(':user_id' => $_SESSION['user_id']));
                            $sth = mysqli_query($link, "UPDATE users SET user_has_avatar = TRUE WHERE id = $id");

                            $_SESSION['user_avatar_file'] = $target_file_path;

                            $msg[] = FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL;

                        } else {

                            $err[] = FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE;

                        }

                    } else {

                        $err[] = FEEDBACK_AVATAR_UPLOAD_TOO_SMALL;

                    }

                } else {

                    $err[] = FEEDBACK_AVATAR_UPLOAD_TOO_BIG;

                } 
            }  

        } else {
            
            $err[] = FEEDBACK_AVATAR_FOLDER_NOT_WRITEABLE;
            
        }
      

}
if($_POST['submit']=='Reset BackGround')
{
  $id = $_SESSION['user_id'];
  unlink($_SESSION['user_background']);
  $target_file='';
  $sth = mysqli_query($link, "UPDATE users SET user_background = '$target_file' WHERE id = $id");
  $_SESSION['user_background'] = $target_file;
  $msg[]="BackGround image has been Reset to default!";
}
if($_POST['submit']=='Upload BackGround')
{
        $id = $_SESSION['user_id'];
 //       createAvatar($_FILES['BackGround_file']['tmp_name']);
        
        if (is_dir(IMAGE_PATH) && is_writable(IMAGE_PATH)) {
	$target_dir = IMAGE_PATH;
	$target_file = $target_dir ."bg_".$id."-".basename($_FILES["fileToUpload"]["name"]);        
        include '../libs/uploadimage.php';
        
         $sth = mysqli_query($link, "UPDATE users SET user_background = '$target_file' WHERE id = $id");
         $_SESSION['user_background'] = $target_file;
         
        } else {
            
            $err[] = FEEDBACK_AVATAR_FOLDER_NOT_WRITEABLE;
            
        }
      

}

if($_POST['doUpdate'] == 'Update')  
{


$rs_pwd = mysqli_query($link, "select pwd from users where id='$_SESSION[user_id]'");
list($old) = mysqli_fetch_row($rs_pwd);
$old_salt = substr($old,0,9);

//check for old password in md5 format
	if($old === PwdHash($_POST['pwd_old'],$old_salt))
	{
	$newsha1 = PwdHash($_POST['pwd_new']);
	mysqli_query($link, "update users set pwd='$newsha1' where id='$_SESSION[user_id]'");
	$msg[] = "Your new password is updated";
	//header("Location: mysettings.php?msg=Your new password is updated");
	} else
	{
	 $err[] = "Your old password is invalid";
	 //header("Location: mysettings.php?msg=Your old password is invalid");
	}

}

if($_POST['doSave'] == 'Save')  
{
// Filter POST data for harmful code (sanitize)
foreach($_POST as $key => $value) {
	$data[$key] = filter($value);
}


mysqli_query($link, "UPDATE users SET
			`full_name` = '$data[name]',
			`address` = '$data[address]',
			`tel` = '$data[tel]',
			`fax` = '$data[fax]',
			`country` = '$data[country]',
			`website` = '$data[web]'
			 WHERE id='$_SESSION[user_id]'
			") or die(mysqli_error($link));

//header("Location: mysettings.php?msg=Profile Sucessfully saved");
$msg[] = "Profile Sucessfully saved";
 }
 
$rs_settings = mysqli_query($link, "select * from users where id='$_SESSION[user_id]'"); 
?>
<html>
<head>
<title>My Account Settings</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
  <script>
  $(document).ready(function(){
    $("#myform").validate();
	 $("#pform").validate();
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
    <td width="160" valign="top"><div class="pos_l_fix">
<?php 
/*********************** MYACCOUNT MENU ****************************
This code shows my account menu only to logged in users. 
Copy this code till END and place it in a new html or php where
you want to show myaccount options. This is only visible to logged in users
*******************************************************************/
if (isset($_SESSION['user_id'])) 
{
  include 'menu.php';
} 
?></div></td>
    <td width="732" valign="top">
<h3 class="titlehdr">My Account - Settings</h3>
      <p> 
        <?php	
	if(!empty($err))  {
	   echo "<div class=\"msg\">";
	  foreach ($err as $e) {
	    echo "* Error - $e <br>";
	    }
	  echo "</div>";	
	   }
	   if(!empty($msg))  {
	   foreach ($msg as $me)
	    echo "<div class=\"msg\">" . $me . "</div>";

	   }
	  ?>
      </p>
      <p>Here you can make changes to your profile. Please note that you will 
        not be able to change your email which has been already registered.</p>
	  <?php while ($row_settings = mysqli_fetch_array($rs_settings)) {?>
      <form action="mysettings.php" method="post" name="myform" id="myform" enctype="multipart/form-data">
        <table width="90%" border="0" align="center" cellpadding="3" cellspacing="3" class="forms">
          <tr> 
            <td colspan="2"> Your Name / Company Name<br> <input name="name" type="text" id="name"  class="required" value="<?php echo $row_settings['full_name']; ?>" size="50"> 
              <span class="example">Your name or company name</span></td>
          </tr>
          <tr> 
            <td colspan="2">Address <span class="example">(full address with ZIP)</span><br> 
              <textarea name="address" cols="40" rows="4" class="required" id="address"><?php echo $row_settings['address']; ?></textarea> 
            </td>
          </tr>
          <tr> 
            <td>Country</td>
            <td><input name="country" type="text" id="country" value="<?php echo $row_settings['country']; ?>" ></td>
          </tr>
          <tr> 
            <td width="27%">Phone</td>
            <td width="73%"><input name="tel" type="text" id="tel" class="required" value="<?php echo $row_settings['tel']; ?>"></td>
          </tr>
          <tr> 
            <td>Fax</td>
            <td><input name="fax" type="text" id="fax" value="<?php echo $row_settings['fax']; ?>"></td>
          </tr>
          <tr> 
            <td>Website</td>
            <td><input name="web" type="text" id="web" class="optional defaultInvalid url" value="<?php echo $row_settings['website']; ?>"> 
              <span class="example">Example: http://www.domain.com</span></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>User Name</td>
            <td><input name="user_name" type="text" id="web2" value="<?php echo $row_settings['user_name']; ?>" disabled></td>
          </tr>
          <tr> 
            <td>Email</td>
            <td><input name="user_email" type="text" id="web3"  value="<?php echo $row_settings['user_email']; ?>" disabled></td>
          </tr>
          <tr> 
            <td>Avatar</td>
            <td>    
            <div>Your avatar pic (saved on local server): <img src="<?php echo $_SESSION['user_avatar_file']; ?>" /></div>
            </td>
          </tr>
          <tr>
            <td>Upload Avatar
            </td>
            <td>
		<label for="avatar_file">Select an avatar image from your harddisk (will be scaled to 120x120 px):</label>
		<!-- max size 5 MB (as many people directly upload high res pictures from their digicams) -->
		<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
		<input type="file" name="avatar_file"/>
		<input name="submit" type="submit" value="Upload image" />
            </td>
          </tr>
          <tr>
            <td>Upload BackGround
            </td>
            <td>
		<label for="BackGround_file">Select an avatar image from your harddisk:</label><br>
		<!-- max size 5 MB (as many people directly upload high res pictures from their digicams) -->
		<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="submit" value="Upload BackGround" name="submit"> <input type="submit" name="submit" value="Reset BackGround">
            </td>
          </tr>
        </table>
        <p align="center"> 
          <input name="doSave" type="submit" id="doSave" value="Save">
        </p>
      </form>
	  <?php } ?>
      <h3 class="titlehdr">Change Password</h3>
      <p>If you want to change your password, please input your old and new password 
        to make changes.</p>
      <form name="pform" id="pform" method="post" action="">
        <table width="80%" border="0" align="center" cellpadding="3" cellspacing="3" class="forms">
          <tr> 
            <td width="31%">Old Password</td>
            <td width="69%"><input name="pwd_old" type="password" class="required password"  id="pwd_old"></td>
          </tr>
          <tr> 
            <td>New Password</td>
            <td><input name="pwd_new" type="password" id="pwd_new" class="required password"  ></td>
          </tr>
        </table>
        <p align="center"> 
          <input name="doUpdate" type="submit" id="doUpdate" value="Update">
        </p>
        <p>&nbsp; </p>
      </form>
      <p>&nbsp; </p>
      <p>&nbsp;</p>
	   
      <p align="right">&nbsp; </p></td>
    <td width="196" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
</body>
</html>
