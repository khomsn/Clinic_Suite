<?php
include '../../config/dbc.php';
page_protect();

echo "<div id=\"callpt\">";
$getptid = mysqli_query($link, "select * from `queuesystem` WHERE  `ToIp` = '$_SESSION[user_ip]'");
while ($row_settings = mysqli_fetch_array($getptid))
{
    $ptid = $row_settings['ptid'];
    $fromip = $row_settings['FromIp'];
    if($ptid)
    {
        echo "<div class=\"pos_r_tr_big\">";
        echo "เชิญ ";
        
        $getptname = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE id ='$ptid'");
        while ($ptinfo = mysqli_fetch_array($getptname))
        {
            echo "<div STYLE='color: #0B610B; font-family: Verdana; font-weight: bold; font-size: 30px;'>".$ptinfo['prefix']." ".$ptinfo['fname']." ".$ptinfo['lname']."</div>";
        }
        echo " เข้าห้องตรวจ";
        echo "</div>";
    }
}

echo "</div>";
?>
