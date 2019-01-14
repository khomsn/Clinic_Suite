<?php 
if (($_SESSION['user_accode']%11==0) AND (!empty($_SESSION['sflc'])))
{
echo "<a href=../../main/setting/ordertemplate.php>Order Template</a><br><br>";
echo "<a href=../../main/setting/catcenable.php>DCC & DDI</a><br><br>";
echo "<a href=../../main/setting/maskingid.php>DRUG ID Masking</a><br><br>";
}
if (($_SESSION['user_accode']%7==0) AND ($_SESSION['staff_id']>0))
{
echo "<a href=../../main/setting/stockplacement.php>ตำแหน่ง Stock</a><br><br>";
}
if ($_SESSION['user_accode']%2==0 AND $_SESSION['staff_id']>0)
echo "<a href=../../main/setting/comptemplate.php>รายชื่อบริษัท</a><br><br>";
?>
