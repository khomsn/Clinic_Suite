<div class="myaccount">
<div><img src="../../<?php echo $_SESSION['user_avatar_file']; ?>" /></div>
<br>
<a href="../../login/myaccount.php">Main Menu</a><br><br>
<p><strong>RawMat Menu</strong></p><br>
<a href="../../main/rawmat/rawmatlist.php">รายการ RawMat</a><br>
<a href='../../main/rawmat/rawmatusestat.php'>RawMat/Month</a><br><br>
<?php
if ($_SESSION['user_accode']%7 == 0)
{
echo "<a href='../../main/rawmat/rawmatid.php'>เพิ่ม รายการ RawMat</a><br>
<a href='../../main/rawmat/rawmatdel.php'>ลบ รายการ RawMat</a><br>";
}
if ($_SESSION['user_accode']%7 == 0 OR $_SESSION['user_accode']%3 == 0)
{
echo "<a href='../../main/rawmat/rawtouse.php'>เบิกใช้ RawMat</a><br>
<a href='../../main/rawmat/rawstock.php'>นำเข้า RawMat</a><br><br>
<a href='../../main/rawmat/rawmatlow.php'>RawMat ถึงกำหนดซื้อ</a><br><br><br>";
}
if ($_SESSION['user_accode']%7 == 0)
{
echo "<a href='../../main/suppliers/listsp.php'>Supplier</a><br><br><br>";
}
?>
<a href="../../login/logout.php">Logout</a><br>
</div>
