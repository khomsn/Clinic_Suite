<?php 
include '../../config/dbc.php';
page_protect();

$filter = mysqli_query($link, "select * from rawmat ");
while ($row = mysqli_fetch_array($filter))
{
    if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
}
$filter = mysqli_query($link, "select * from rawmat  ORDER BY `rmtype` ASC ,`rawcode` ASC");

$title = "::ห้องคลังวัตถุดิบ::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
include '../../libs/popup.php';
include '../../main/bodyheader.php';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
<tr><td width="160" valign="top"><div class="pos_l_fix">
    <?php 
        /*********************** MYACCOUNT MENU ****************************
        This code shows my account menu only to logged in users. 
        Copy this code till END and place it in a new html or php where
        you want to show myaccount options. This is only visible to logged in users
        *******************************************************************/
        if (isset($_SESSION['user_id']))
        {
            include 'rawmatmenu.php';
        } 
    /*******************************END**************************/
    ?></div>
    </td><td><h3 class="titlehdr"><a onClick="return popup(this, 'notes','1000','850','yes')" HREF="rawstockinall.php">นำเข้า Raw Material</a></h3>
    <?php
        echo "<table class='TFtable' border='1' style='text-align: center; margin-left: auto; margin-right: auto;' >";
        echo "<tr><th>Code</th><th>ชื่อ</th><th>ขนาด</th><th>Unit</th><th>Type</th></tr>";
        // keeps getting the next row until there are no more to get
        while($row = mysqli_fetch_array($filter))
            {
                // Print out the contents of each row into a table
                echo "<tr><td style='text-align: left;'>"; 
                $msg = urlencode($row['id']);
                echo "<a onClick=\"return popup(this, 'notes','800','450','yes')\" HREF=\"rawstockin.php?msg=".$msg."\">";
                echo $row['rawcode'];
                echo "</a>";
                echo "</td><td style='text-align: left;'>"; 
                echo $row['rawname'];
                echo "</td><td>"; 
                echo $row['size'];
                echo "</td><td>"; 
                echo $row['sunit'];
                echo "</td><td>"; 
                echo $row['rmtype'];
                echo "</td></tr>";
            } 
        echo "</table>";
        //////////////////////////
    ?>
    </td><td style="width:260px;vertical-align: top;">
</td></tr>
</table>
</body></html>
