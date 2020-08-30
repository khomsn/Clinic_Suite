<?php 
include '../config/dbc.php';

page_protect();

$pin = mysqli_query($link, "SELECT * FROM deleted_drug");
while($ch = mysqli_fetch_array($pin))
{
    $id = $ch['id'];
    $idac = $ch['ac_no'];
    $idac = $idac - 100000 +10300000; //10300000-10699999 สินค้า 10300000 ตัดยอด//start with 10300000 + drug_id
    $updateac = "UPDATE `deleted_drug` SET  `ac_no` = '$idac' WHERE `id` ='$id' LIMIT 1 ; ";
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
<div style="text-align: center;">drug_id finish
</div>
</div>
</body>
</html>
