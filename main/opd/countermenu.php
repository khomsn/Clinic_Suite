<?php
include '../../libs/refreshptcall.php';
?>
<div class="myaccount">
<div><img src="../../<?php echo $_SESSION['user_avatar_file']; ?>" /></div>
<br>

<a href="../../login/myaccount.php">Main Menu</a><br><br>
<a href="../../main/opd/mycounter.php"><p><strong>Counter Menu</strong></p></a><br>
<a href="../../main/register/search_pt.php">เวชระเบียน</a><br><br>

<a href="../../main/opd/pt_to_doctor.php">ผู้ป่วยรอตรวจ</a><br><br>
<a href="../../main/opd/pt_to_obs.php">ผู้ป่วยรอสังเกตอาการ</a><br><br>
<a href="../../main/opd/pt_to_lab.php">ผู้ป่วยรอตรวจ LAB</a><br><br>
<a href="../../main/opd/pt_to_trm.php">ผู้ป่วยรอ Treatment</a><br><br>
<a href="../../main/opd/pt_to_drug.php">ผู้ป่วยรอรับยา</a><br><br>
<br>
<a href="../../login/logout.php">Logout </a><br>
</div>
<div id="callpt"></div>

