<?php 
include '../config/dbc.php';

page_protect();
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
<?php
$payall=0;

for ($i=1;$i<=30600;$i++)
{
$Patient_id = $i;
include '../libs/opdxconnection.php';
$pttable = "pt_".$i;
$pin = mysqli_query($linkopdx, "SELECT * FROM $pttable");
while($ch = mysqli_fetch_array($pin))
{
    $str_arr = explode (" ", $ch['date']);
    if ($str_arr[0] == "2019-06-17")
    {
    echo " + ".$i;
    echo " + ";
    $pin2 = mysqli_query($link, "SELECT * FROM sell_account where vsdate = '$ch[date]'");
    while($ch2 = mysqli_fetch_array($pin2))
    {
        
    echo $ch2['vsdate'];
    echo "+";
    echo $ch2['pay'];
    echo "+";
    $payall = $payall+$ch2['pay'];
    //$ctmacno = $i + 11000000; //คนไข้ค้างชำระ  //start with 11000000 + pt_id
    //$updateac = "INSERT INTO `sell_account` (`id`, `day`, `month`, `year`, `ctmid`, `ctmacno`, `payby_acno`, `pay`, `own`, `total`, `ddx`, `tty`, `vsdate`) VALUES (NULL, '17', '6', '2019', '$i', '$ctmacno', '10000001', '0', '0', '0', '$ch[ddx]', '0', '$ch[date]');";

   // $newac = mysqli_query($link, $updateac);
    }
    echo "<br>";
    }
}
}
echo $payall;
?>
</div>
</div>
</body>
</html>
