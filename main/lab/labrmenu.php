<?php
echo "<a href=\"../lab/lablist.php\"><img style=\"border: 0px solid ; width: 120px; height: 120px;\" alt=\"Lab\" src=\"../../image/lab.jpg\"></a><br>";
if($_SESSION['accode']%13==0 or $_SESSION['accode']%5==0)
{
    echo "<a href=\"../lab/labwait.php\"><img style=\"border: 0px solid ; width: 120px; height: 120px;\" alt=\"Waiting Lab\" src=\"../../image/wait1.jpg\"></a><br>";
    echo "<a href=\"../lab/labadd.php\"><img style=\"border: 0px solid ; width: 120px; height: 120px;\" alt=\"addlab\" src=\"../../image/Labadd.jpg\"></a><br>";
    echo "<a href=\"../lab/labaddset.php\"><img style=\"border: 0px solid ; width: 120px; height: 120px;\" alt=\"Set of Lab\" src=\"../../image/labsadd.jpg\"></a><br>";
    echo "<a href=\"../lab/labstats.php\"><img style=\"border: 0px solid ; width: 120px; height: 120px;\" alt=\"Lab Stats\" src=\"../../image/statastic.jpg\"></a><br>";
    
}
?>

