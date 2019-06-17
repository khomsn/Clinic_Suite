<?php 
include '../config/dbc.php';

page_protect();

$pin = mysqli_query($link, "SELECT * FROM rawmat");
while($ch = mysqli_fetch_array($pin))
{
    $id = $ch['id'];
    //check volume 
    $rawmattable = "rawmat_".$id;
    $chvol = mysqli_query($link, "SELECT * FROM $rawmattable");
    $inv = $outv = 0;
    while($drgvol = mysqli_fetch_array($chvol))
    {
        $inv=$inv+$drgvol['volume'];
        $outv=$outv+$drgvol['customer'];
    }
    $upvol = $inv-$outv;
    
    $updatevol = "UPDATE `rawmat` SET  `volume` = '$upvol' WHERE `id` ='$id' LIMIT 1 ; ";
    mysqli_query($link, $updatevol);
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Fix Rawmat volume</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="content">
<div style="text-align: center;">Rawmat Volume Has been fixed.
</div>
</div>
</body>
</html>
