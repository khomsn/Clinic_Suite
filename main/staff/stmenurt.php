<table style="text-align: left;" border="1" cellpadding="5" cellspacing="5">
<tbody>
<tr><td style="text-align: right;">
    <?php 
    if($_SESSION['user_accode']%13 == 0)
    {
        echo "<a href='../../main/staff/staffreg.php'>";
        echo "<img style=\"border: 0px solid ; width: 120px; height: 120px;\" alt=\"ลงทะเบียนพนักงาน\" src=\"../../image/staffadd.jpeg\"></a>";
        echo "</td></tr>";
        echo "<tr><td style=\"text-align: right;\">";
        echo "<a href='../../main/staff/staffsearch.php'><img style=\"border: 0px solid ; width: 120px; height: 120px;\" alt=\"ค้นทะเบียนพนักงาน\" src=\"../../image/user-management_3.jpg\"></a>";
    }
    ?>
</td></tr>
</tbody>
</table>
