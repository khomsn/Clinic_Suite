<?php 
include '../config/dbc.php';

page_protect();


$pin = mysqli_query($link, "SELECT * FROM sell_account");
while($ch = mysqli_fetch_array($pin))
{
    $id = $ch['id'];
    $ctmid = $ch['ctmid'];
    $ctmacno = $ctmid + 11000000; //คนไข้ค้างชำระ  //start with 11000000 + pt_id
    $updateac = "UPDATE `sell_account` SET  `ctmacno` = '$ctmacno' WHERE `id` ='$id' LIMIT 1 ; ";
    $newac = mysqli_query($link, $updateac);
}
?>
<!DOCTYPE html>
<html>
<head>
<title>ประวัติ ตรวจร่างกาย</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="content">
<div style="text-align: center;">
sell_account update finish!
</div>
</div>
</body>
</html>
