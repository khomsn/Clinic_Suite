<div class="myaccount">
<img src="../<?php echo $_SESSION['user_avatar_file']; ?>" />
<a href="../../login/myaccount.php"><p><strong>Main Menu</strong></p></a><br><br>
<a href="../../login/mysettings.php">My Setting</a><br><br>
<?php 
include 'submenusetting.php';
?>
<a href="../../login/logout.php">Logout </a><br><br><br>
<?php
include 'submenuadmin.php';
?>
</div>
