<?php 
include '../../config/dbc.php';
page_protect();

$id = $_SESSION['patlab'];

$_SESSION['patdesk']=$_SESSION['patlab'];

$pdir = "../".PT_AVATAR_PATH;

echo "<!DOCTYPE html>
<html>
<head>
<meta content=\"text/html; charset=utf-8\" http-equiv=\"content-type\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/styles.css\"/>";
include '../../libs/popup.php';
echo "</head><body>";
if (isset($_SESSION['user_id']))
{
    echo "<div class=\"myaccount\"><div class=\"ptavatar\">";
    echo "<img src='";
    $avatar = $pdir. "pt_". $id . ".jpg";
    echo $avatar;
    echo "' width='120' height='120' /></div>";
    echo "<hr><p><strong>LAB Menu</strong></p>";
    echo "<a href='../opd/investigation.php' TARGET='MAIN'>Lab วันนี้</a><br><br>";
    echo "<a HREF='../opd/opdpage.php' onClick=\"return popup(this,'name','800','600','yes');\" >OPD Card</a><br><br></div>";
} 
echo "</body></html>";
?>

