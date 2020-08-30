<?php 
include '../config/dbc.php';

page_protect();

$dupmin = mysqli_query($link, "SELECT * FROM dupm WHERE rmonth = MONTH(NOW()) AND ryear = YEAR(NOW())");
while($dupmo = mysqli_fetch_array($dupmin))
{
    echo $idstat = $dupmo['id'];
    echo "+".$did = $dupmo['drugid'];
    echo "+".$newvol = $dupmo['vol'];
    echo "<br>";
}
/*
echo "TEST";
$drgid = 157;
$vld = 1;

$dupmin = mysqli_query($link, "SELECT * FROM dupm WHERE drugid = '$drgid' AND rmonth = MONTH(NOW()) AND ryear = YEAR(NOW())");
while($dupmo = mysqli_fetch_array($dupmin))
{
    $idstat1 = $dupmo['id'];
    $newvol = $dupmo['vol'] + $vld;
}
if(is_null($idstat1))
{
    echo "is Null";
    $sql_insert = "INSERT INTO `dupm` (`drugid`, `ryear`, `rmonth`, `vol`) VALUES ('$drgid',YEAR(NOW()),MONTH(NOW()),'$vld')";
    mysqli_query($link, $sql_insert);
}
else
{
echo "Not Null";
$newvol = $newvol+1;
    $sql_update = "UPDATE `dupm` SET `vol` = '$newvol'  WHERE id='$idstat';";
    mysqli_query($link, $sql_update);
}
*/
//$pin = mysqli_query($link, "SELECT * FROM dupm");
/*
while($ch = mysqli_fetch_array($pin))
{
    $id = $ch['id'];
    $input = $ch['mon']; 
    $date = strtotime($input); 
    echo $year = date('Y',$date);
    echo "+";
    echo $month = date('m',$date);
    echo "+";
    $updatevol = "UPDATE `dupm` SET  `year` = $year, `month` = $month WHERE `id` ='$id' LIMIT 1 ; ";
    mysqli_query($link, $updatevol);
}
*/
?>
<!DOCTYPE html>
<html>
<head>
<title>Fix Drug volume</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="content">
<div style="text-align: center;">Drug Volume Has been fixed.
</div>
</div>
</body>
</html>
