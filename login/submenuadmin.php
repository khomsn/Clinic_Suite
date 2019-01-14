<?php
if (checkAdmin())
{
 echo "<a href='../../login/admin.php'>Admin CP</a>"; 
    if($_SESSION['user_accode']%13==0)
    {
    echo "<p><a href=../../main/staff/staff.php>Staff</a></p>";
    }
    if($_SESSION['user_accode']%13==0 AND $_SESSION['staff_id']>0)
    {
    echo "<p> <a href=../../main/setting/progpara.php>Programme Parameter</a></p><hr>";
    echo "<p> <a href=../../main/setting/netcommu.php>ระบบแสดงลำดับ</a></p><hr>";
    echo "<p> <a href=../../main/setting/stcpdrug.php>Staff Co-Pay Drug</a></p>";
    }
}
?>
