<div class="myaccount">
<div><img src="../../<?php echo $_SESSION['user_avatar_file']; ?>" /></div>
<br>
<a href="../../login/myaccount.php">Main Menu</a><br><br>
<p><strong>Accounting & Cash Menu</strong></p><br>
<?php 
if ($_SESSION['user_accode']%2==0  and $_SESSION['user_level']>1)
{
  echo "<a href='../../main/account/dayacin.php'> ลงบัญชีทั่วไป</a><br>";
}
if ($_SESSION['user_accode']%13==0  and $_SESSION['user_level']>1)
{
  echo "<a href='../../main/account/debcheck.php'> รายชื่อคนไข้ค้างชำระ</a><br>";
}

?>
<br>
<a href="../../login/logout.php">Logout </a><br>
</div>
