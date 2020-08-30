<?php 
include '../config/dbc.php';

page_protect();


$pin = mysqli_query($link, "SELECT * FROM supplier");
while($ch = mysqli_fetch_array($pin))
{
    $id = $ch['id'];
    $acno = $ch['ac_no'];
    $acno = $acno - 2100 + 21000000;
    $updateac = "UPDATE `supplier` SET  `ac_no` = '$acno' WHERE `id` ='$id' LIMIT 1 ; ";
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
supplier finish
</div>
<!--end menu-->
</div>
</body>
</html>
