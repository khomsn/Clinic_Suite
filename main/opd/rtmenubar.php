<?php 
include '../../config/dbc.php';
page_protect();
$id = $_SESSION['pattrm'];
//$pdir = PT_AVATAR_PATH.$id."/";
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
?>
<div class="myaccount">
<div class="ptavatar">
<img src="<?php $avatar = $pdir. "pt_". $id . ".jpg"; echo $avatar; ?>" width="140" height="140" />
</div>
<br>
<br>
<br>
<br>
<br>

<p><strong>Treatment</strong></p>
<?php 
echo "<a href='../opd/treatment.php' TARGET='MAIN'>Treatment วันนี้</a><br><br>";
?>
<a HREF="../opd/opdpage.php" onClick="return popup(this,'name','800','600','yes');" >OPD Card</a><br><br>
</div>

<?php
} 
?>
</body></html>
