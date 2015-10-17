<div class="myaccount">
<div><img src="<?php echo $_SESSION['user_avatar_file']; ?>" /></div>
<br>
<a href="../login/myaccount.php">Main Menu</a><br><br>
<p><strong>Drug & Product Menu</strong></p><br>
<a href="../main/druglist.php">รายการยาและผลิตภัณฑ์</a><br>
<a href="../main/drugtracklist.php">รายการยาพิเศษ</a><br>
<a href='../main/drugset.php'>ชุดยา</a><br><br>
<a href='../main/drugusestat.php'>Drug/Month</a><br><br>
<?php
if($_SESSION['user_level']==5)
{
echo "<a href='../main/drrsrvreset.php'>Reset Drug Reserved</a><br>";
echo "<a href='../main/drugcombset.php'>Drug Comb Set</a><br>";
echo "<a href='../main/tr_price_step.php'>ราคาหัตถการ</a><br>";
}
if ($_SESSION['user_accode']%7 == 0 OR $_SESSION['user_accode']%11 == 0)
{
echo "<a href='../main/drandillci.php'>ยา-โรค</a><br>";
echo "<a href='../main/drandpt.php'>ค้นผู้ป่วยที่ใช้ยา</a><br>";
}
if ($_SESSION['user_accode']%7 == 0)
{
echo "<a href='../main/druggprop.php'>Drug Prop</a><br><a href='../main/drugid.php'>เพิ่ม รายการยาและผลิตภัณฑ์</a><br>
<a href='../main/deldrug.php'>ลบ รายการยาและผลิตภัณฑ์</a><br>";
}
if ($_SESSION['user_accode']%7 == 0)
{
  echo "<a href='../main/drtouse.php'>เบิกใช้ ยาและผลิตภัณฑ์</a><br>
<a href='../main/stock.php'>นำเข้า ยา และ ผลิตภัณฑ์</a><br><br>
<a href='../main/druglow.php'>ยาถึงกำหนดซื้อ</a><br><br><br>
<a href='../main/listsp.php'>Supplier</a><br>
<br>
<br>";
}
?>
<a href="../login/logout.php">Logout </a><br>
</div>
