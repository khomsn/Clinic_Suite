<div class="myaccount">
<div><img src="../../<?php echo $_SESSION['user_avatar_file']; ?>" /></div>
<br>
<a href="../../login/myaccount.php">Main Menu</a><br><br>
<p><strong>Drug & Product Menu</strong></p><br>
<a href="../../main/pharma/druglist.php">รายการยาและผลิตภัณฑ์</a><br>
<a href='../../main/pharma/drugset.php'>ชุดยา</a><br><br>
<a href='../../main/pharma/drugusestat.php'>Drug/Month</a><br><br>
<a href='../../main/pharma/druglowlife.php'>ยาใกล้หมดอายุ</a><br><br>
<?php
if($_SESSION['user_level']==5)
{
echo "<a href='../../main/pharma/drrsrvreset.php'>Reset Drug Reserved</a><br>";
echo "<a href='../../main/pharma/drugcombset.php'>Drug Comb Set</a><br>";
echo "<a href='../../main/pharma/tr_price_step.php'>ราคาหัตถการ</a><br>";
}
if ($_SESSION['user_accode']%7 == 0 OR $_SESSION['user_accode']%11 == 0)
{
echo "<a href='../../main/pharma/drandillci.php'>ยา-โรค</a><br>";
echo "<a href='../../main/pharma/drandpt.php'>ค้นผู้ป่วยที่ใช้ยา</a><br>";
}
if ($_SESSION['user_accode']%7 == 0)
{
echo "<a href='../../main/pharma/druggprop.php'>Drug Prop</a><br><a href='../../main/pharma/drugid.php'>เพิ่ม รายการยาและผลิตภัณฑ์</a><br>
<a href='../../main/pharma/deldrug.php'>ลบ รายการยาและผลิตภัณฑ์</a><br><a href='../../main/pharma/deldruglistandreactivate.php'>รายการยาที่ถูกลบ</a><br>";
}
if ($_SESSION['user_accode']%7 == 0)
{
  echo "<a href='../../main/pharma/drtouse.php'>เบิกใช้ ยาและผลิตภัณฑ์</a><br>
<a href='../../main/pharma/stock.php'>นำเข้า ยา และ ผลิตภัณฑ์</a><br><br>
<a href='../../main/pharma/druglow.php'>ยาถึงกำหนดซื้อ</a><br><br><br>
<a href='../../main/suppliers/listsp.php'>Suppliers</a><br>
<br>
<br>";
}
?>
<a href="../../login/logout.php">Logout </a><br>
</div>
