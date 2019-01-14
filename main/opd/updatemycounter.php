<?php
include '../../config/dbc.php';
page_protect();
?>
<div id="main">
<table style="text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="5" cellspacing="5">
<?php 
    $disc = mysqli_query($link, "select * from pt_to_doc");
    $i=0;
    while( $rowd = mysqli_fetch_array($disc))
    {
            $i = $i+1;
    }
    $disc = mysqli_query($link, "select * from pt_to_lab");
    $j=0;
    while( $rowd = mysqli_fetch_array($disc))
    {
            $j  = $j+1;
    }
    $disc = mysqli_query($link, "select * from pt_to_treatment");
    $l=0;
    while( $rowd = mysqli_fetch_array($disc))
    {
            $l  = $l+1;
    }
    $disc = mysqli_query($link, "select * from pt_to_drug");
    $k=0;
    while( $rowd = mysqli_fetch_array($disc))
    {
            $k  = $k+1;
    }
    $disc = mysqli_query($link, "select * from pt_to_obs");
    $m=0;
    while( $rowd = mysqli_fetch_array($disc))
    {
            $m  = $m+1;
    }
    $disc = mysqli_query($link, "select * from pt_to_scr");
    $n=0;
    while( $rowd = mysqli_fetch_array($disc))
    {
            $n  = $n+1;
    }
    if($n!=0) echo "<tr><td><span style='color: rgb(204, 0, 0); background-color: #E9F5BF;'>คนไข้รอซักประวัติ ".$n." ฅน</span></td></tr>";
    if($i!=0) echo "<tr><td><span style='color: rgb(204, 0, 0); background-color: #E9F5BF;'>คนไข้รอตรวจ ".$i." ฅน</span></td></tr>";
    if($m!=0) echo "<tr><td><span style='color: rgb(204, 0, 0); background-color: #E9F5BF;'>คนไข้รอสังเกตอาการ ".$m." ฅน</span></td></tr>";
    if($j!=0) echo "<tr><td><span style='color: rgb(204, 0, 0); background-color: #E5E5E5;'>คนไข้รอ Lab ".$j." ฅน</span></td></tr>";
    if($l!=0) echo "<tr><td><span style='color: rgb(204, 0, 0); background-color: #E5E5E5;'>คนไข้รอ Treatment ".$l." ฅน</span></td></tr>";
    if($k!=0) echo "<tr><td><span style='color: rgb(204, 0, 0); background-color: #E9F5BF;'>คนไข้รอรับยา ".$k." ฅน</span></td></tr>";
?>
</table>
</div>
