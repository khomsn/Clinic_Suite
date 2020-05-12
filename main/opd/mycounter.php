<?php 
include '../../config/dbc.php';
page_protect();

$title = "::My Counter::";
include '../../main/header.php';
include '../../main/bodyheader.php';
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() { /// Wait till page is loaded
setInterval(timingLoad, 1500);
function timingLoad() {
$('#main').load('updatemycounter.php #main', function() {
/// can add another function here
});
$('#callpt').load('updateptcalltosv.php #callpt', function() {
/// can add another function here
});
}
}); //// End of Wait till page is loaded
</script>
<div class="pos_l_fix">
<?php 
if (isset($_SESSION['user_id'])) 
{
    include 'countermenu.php';
}
?>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
<tr><td colspan="3">&nbsp;</td></tr>
<tr><td style="text-align: center; width: 130px; "></td>
<td valign="top"  style="text-align: left;"><p>&nbsp;</p>
<h3 class="titlehdr">Welcome <?php echo $_SESSION['user_name'];?></h3>  
<?php
if (isset($_GET['msg'])) {
echo "<div class=\"error\">$_GET[msg]</div>";
}
$rs_settings = mysqli_query($link, "select * from parameter where id='1'");
?>
<table style="text-align: center; width: 100%;" border="0" cellpadding="0" cellspacing="0">
<tr><td><p><h3 class="hdrname"><?php 
while ($row_settings = mysqli_fetch_array($rs_settings))
{ echo $row_settings['name'];echo "<br>" ;}
?>
</h3></p></td>
</tr>
</table>
<br><br><br><br><br>
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
</td><td width="196" valign="top">&nbsp;</td></tr>
<tr><td colspan="3">&nbsp;</td></tr>
</table>
</body>
</html>
