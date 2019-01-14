<?php 
include '../../config/dbc.php';
page_protect();

$filter = mysqli_query($link, "select * from rawmat");		
while ($row = mysqli_fetch_array($filter))
{
    if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
}

$filter = mysqli_query($link, "select * from rawmat    WHERE `volume` >0 ORDER BY `rmtype` ASC ,`rawcode` ASC ,`rawname` ASC");

$title = "::ห้องคลังวัตถุดิบ::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
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
    </td><td><h3 class="titlehdr">เบิกใช้ RawMat</h3>
    <?php
    
    $n_of_row = mysqli_num_rows($filter);
    echo "<table class='TFtable' border='1' style='text-align: center; margin-left: auto; margin-right: auto;' >";
    echo "<tr><th>Code</th><th>ชื่อ</th><th>ขนาด</th><th>Unit</th><th>Type</th></tr>";
    // keeps getting the next row until there are no more to get
    while($row = mysqli_fetch_array($filter))
        {
            // Print out the contents of each row into a table
            echo "<tr><th>"; 
            $msg = urlencode($row['id']);
            echo "<a href=\"rawstockout.php";
            echo "?msg=".$msg;
            echo "\">";
            echo $row['rawcode'];
            echo "</a>";
            echo "</th><th>"; 
            echo $row['rawname'];
            echo "</th><th>"; 
            echo $row['size'];
            echo "</th><th>";
            echo $row['sunit'];
            echo "</th><th>"; 
            echo $row['rmtype'];
            echo "</th></tr>";
    } 
    echo "</table>";
    //////////////////////////
    ?>						
</td></tr>
</table>
</body></html>
