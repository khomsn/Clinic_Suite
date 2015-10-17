<?php 
/********************** MYSETTINGS.PHP**************************
This updates user settings and password
************************************************************/
include '../login/dbc.php';
page_protect();

$err = array();
$msg = array();

if($_POST['doSave'] == 'Save')  
{
// Filter POST data for harmful code (sanitize)
foreach($_POST as $key => $value) {
	$data[$key] = filter($value);
}


mysqli_query($link, "UPDATE users SET
			`catcenable` = '$data[catc]',
			`ddil` = '$data[ddil]'
			 WHERE id='$_SESSION[user_id]'
			") or die(mysqli_error($link));

//header("Location: mysettings.php?msg=Profile Sucessfully saved");
$msg[] = "Profile Sucessfully saved";
$_SESSION['catc'] = $data['catc'];
$_SESSION['ddil'] = $data['ddil'];
 }
 
$rs_settings = mysqli_query($link, "select * from users where id='$_SESSION[user_id]'");
while ($row_settings = mysqli_fetch_array($rs_settings))
{
 $catcenb = $row_settings['catcenable'];
 $ddil = $row_settings['ddil'];
 $_SESSION['catc'] = $catcenb;
 $_SESSION['ddil'] = $ddil;
}
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
  include '../login/menu.php';
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
      <form name="pform" id="pform" method="post" action="">
      <h3 class="titlehdr">Drug Category C Property</h3>
      <p>Enable or Disable use of Drug Category C in Pregnancy woman!!  Category C is not enable by default. If you want to use this Category C please enable it, but it not recomment to do so. This Category may be save to use in 4-7 months of pregnant.</p>
        <table width="80%" border="0" align="center" cellpadding="3" cellspacing="3" class="forms">
          <tr> 
            <td width="31%">Category C status</td>
            <td width="69%"><input type="radio" tabindex="1" name="catc" class="required" value="1" <?php if ($catcenb ==1) echo "checked";?>>Enable 
	    <input type="radio" tabindex="1" name="catc" class="required" value="0" <?php if ($catcenb ==0) echo "checked";?>>Disable</td>
	</tr>
        </table>
      <h3 class="titlehdr">Drug Interaction Level</h3>
      <p>Enable or Disable Use and Adjust Level of Drug to Drug Interaction. Drug to Drug interaction Level is divided into 7 Levels; which are (+/-)1 = Minor, (+/-)2 = Significant, (+/-)3 = Serious, and 5 = Contraindicated. + means increase effect each other, - means decrease effect each other.  In this setting, you are allowed to set up to Level |3|, but it is not recommented to do so.
      <hr>No DDI means Select No DDI at all, L1-L3 DDI means select upto +/-1 to +/-3 DDI</p>
        <table width="80%" border="0" align="center" cellpadding="3" cellspacing="3" class="forms">
          <tr> 
            <td width="31%">DDI</td>
            <td width="69%">
            <input type="radio" tabindex="2" name="ddil" class="required" value="0" <?php if ($ddil ==0) echo "checked";?>>No DDI 
	    <input type="radio" tabindex="2" name="ddil" class="required" value="1" <?php if ($ddil ==1) echo "checked";?>>L1-DDI
	    <input type="radio" tabindex="2" name="ddil" class="required" value="2" <?php if ($ddil ==2) echo "checked";?>>L2-DDI
	    <input type="radio" tabindex="2" name="ddil" class="required" value="3" <?php if ($ddil ==3) echo "checked";?>>L3-DDI
	    </td>
	</tr>
        </table>
        <p align="center"> 
          <input name="doSave" type="submit" id="doSave" value="Save">
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
