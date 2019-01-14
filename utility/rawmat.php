<?php 
include '../config/dbc.php';

page_protect();

$pin = mysqli_query($link, "SELECT * FROM rawmat");
while($ch = mysqli_fetch_array($pin))
{
    $id = $ch['id'];
    $idac = $ch['ac_no'];
    $idac = $idac - 180000 +10700000;//180000-189999 วัตถุดิบ  10700000-10999999 วัตถุดิบ 10700000 ตัดยอด //start with 10700000 + rawmat_id
    $updateac = "UPDATE `rawmat` SET  `ac_no` = '$idac' WHERE `id` ='$id' LIMIT 1 ; ";
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
<div style="text-align: center;">rawmat finish
</div>
</div>
</body>
</html>
