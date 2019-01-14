<?php 
include '../../config/dbc.php';
page_protect();

echo "<div id=\"main\">";
if($_SESSION['price']>='0')
{
    echo "<a href='../cashier/payment.php' TARGET='MAIN'>จ่ายเงิน</a><br><br>";
}
echo "</div>";
?>

