<?php 
include '../../config/dbc.php';
page_protect();
$title = "::My Counter::";
include '../../main/header.php';
echo "</head><body>";
if (isset($_SESSION['user_id']))
{
?><div style="margin: 0 left; width:165px;" class="myaccount">
<div><img src="../../<?php echo $_SESSION['user_avatar_file']; ?>" width="160" height="160"/></div><br>
<a href="../../login/myaccount.php" TARGET="_top">Main Menu</a><br>
<a href="../../main/register/search_pt.php" TARGET="_top">เวชระเบียน</a><br>
<p><strong>Clinic Menu</strong></p>
<a href="../opd/pt_to_doctor.php" TARGET="_top">ผู้ป่วยรอตรวจ</a><br>
<a href="../opd/pt_to_obs.php" TARGET="_top">ผู้ป่วยรอสังเกตอาการ</a><br>
<a href="../opd/pt_to_lab.php" TARGET="_top">ผู้ป่วยรอตรวจ LAB</a><br>
<a href="../opd/pt_to_trm.php" TARGET="_top">ผู้ป่วยรอ Treatment</a><br>
<a href="../opd/pt_to_drug.php" TARGET="_top">ผู้ป่วยรอรับยา</a><br>
<br>
<a href="../../login/logout.php" TARGET="_top">Logout </a>
<br><br>
</div>
<?php
} 
?>
</body></html>
