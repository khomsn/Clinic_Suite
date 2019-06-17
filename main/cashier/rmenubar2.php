<?php 
include '../../config/dbc.php';
page_protect();
$id = $_SESSION['patdesk'];

$tmp = "tmp_".$id;
//$pdir = PT_AVATAR_PATH.$id."/";
$pdir = "../".PT_AVATAR_PATH;

if(empty($_SESSION['price'])) $_SESSION['price']= -0.0000001;

$title = "::::";
include '../../main/header.php';
include '../../libs/popup.php';
echo "</head><body>";
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() { /// Wait till page is loaded
setInterval(timingLoad, 2000);
function timingLoad() {
$('#main').load('updaterb2.php #main', function() {
/// can add another function here
});
}
}); //// End of Wait till page is loaded
</script>
<div class="myaccount">
<div class="pt_avatar"><img src="<?php $avatar = $pdir. "pt_". $id . ".jpg"; echo $avatar; ?>" width="140" height="140" /></div>
<?php 
if (isset($_SESSION['user_id']))
{
?><p><strong>Clinic Menu</strong></p>
<?php 
$ptin = mysqli_query($link, "select * from $tmp ");
$row_settings = mysqli_fetch_array($ptin);
if ($row_settings['csf'] =="")
{
echo "<a href='../opd/prehist.php' TARGET='MAIN'>ตรวจร่างกายเบื่องต้น</a><br><br>";
}
if($_SESSION['user_accode']%11==0 or $_SESSION['user_accode']%7==0){
echo "<hr>";
echo "<a href='../opd/prescript.php' TARGET='MAIN'>สั่งยา</a><br>";
echo "<hr>";
}
?>
<a HREF="../opd/opdpage.php" onClick="return popup(this,'name','800','600','yes');" >OPD Card</a><br><br>
<a href="../cashier/remedcert.php" TARGET="MAIN">ขอใบรับรองแพทย์</a><br><br>
<hr>
<a href="../cashier/ptpay.php" TARGET="MAIN">สรุปรายการยา</a><br><br><br>
<a href="../cashier/pay.php" TARGET="MAIN">ยอดเงินรวม</a><br><br>
<hr>
<div id="main"><!--List Patient wait to pay--></div>
<?php
if($_SESSION['user_accode']%13==0)
{
?>
<hr>
<a HREF="discount.php" onClick="return popup(this,'name','400','150','yes');" >กำหนดส่วนลด</a><br><br>
<?php 
}
?>
</div>
<?php
} 
?><br><br><br>
</body></html>
