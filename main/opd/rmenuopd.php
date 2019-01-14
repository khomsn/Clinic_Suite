<?php 
include '../../config/dbc.php';
page_protect();
$id = $_SESSION['patdesk'];
$pdir = "../".PT_AVATAR_PATH;
echo "<!DOCTYPE html>
<html>
<head>
<meta content=\"text/html; charset=utf-8\" http-equiv=\"content-type\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/styles.css\"/>";
echo "</head><body>";
if (isset($_SESSION['user_id']))
{
    echo "<div class=\"myaccount\"><div class=\"ptavatar\"><img src=\"";
    $avatar = $pdir. "pt_". $id . ".jpg";
    echo $avatar;
    echo "\" width=\"140\" height=\"140\" /></div></div><br></body></html>";
}
?>
