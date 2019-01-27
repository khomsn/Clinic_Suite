<?php 
include '../../config/dbc.php';
page_protect();
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
    </td><td>
    <!--menu-->
    <h3 class="titlehdr">รายการ วัตถุดิบ ที่ถึงจุดสั่งซื้อ</h3>
    <form method="post" action="rawmatlist.php" name="regForm" id="regForm">
    <div style="text-align: center;">
    <?php	
    $dtype = mysqli_query($link, "SELECT * FROM rawmat WHERE volume <= lowlimit ORDER BY `rmtype` ASC ,`rawcode` ASC ,`rawname` ASC");
    echo "<table  class='TFtable' border='1' style='text-align: left; margin-left: auto; margin-right: auto;'>";
    echo "<tr><th>No</th><th>Code</th><th>ชื่อ</th><th>ขนาด</th><th>จำนวน</th><th>ร้าน</th><th>จำนวนที่สั่ง</th><th>Unit</th><th>Type</th><th>BP-S</th></tr>";
    $i=1;
    while($row = mysqli_fetch_array($dtype))
    {
    // Print out the contents of each row into a table
    echo "<tr><th>";
    echo $i;
    echo "</th><th>"; 
    echo $row['rawcode'];
    echo "</th><th>"; 
    echo $row['rawname'];
    echo "</th><th>"; 
    echo $row['size'];
    echo "</th><th>"; 
    echo $row['volume'];
    echo "</th><th>";
    $rawmattable = "rawmat_".$row['id'];
    $spname = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM $rawmattable ORDER BY id DESC LIMIT 1;"));
    echo $spname['supplier'];
    echo "</th><th>";
    echo $spname['volume'];
    echo "</th><th>";
    echo $row['sunit'];
    echo "</th><th>"; 
    echo $row['rmtype'];
    echo "</th><th>"; 
    $getsup = mysqli_query($link, "select distinct supplier from $rawmattable where supplier!='$_SESSION[clinic]' AND price!='0'");
    $sp=0;
    while($gs = mysqli_fetch_array($getsup))
    {
        $sup[$sp]=$gs['supplier'];
        $sp=$sp+1;
    }
    
    for($n=0;$n<$sp;$n++)
    {
        $supplier=$sup[$n];
        
        $gr = mysqli_fetch_array(mysqli_query($link, "select MAX(id) from $rawmattable WHERE supplier='$supplier' AND price!='0'"));
        $rowid = $gr[0];
        
        $gp = mysqli_query($link, "select * from $rawmattable WHERE id = $rowid");
        while($row2 = mysqli_fetch_array($gp))
        {
            echo "[".$row2['supplier'].":".number_format(($row2['price']/$row2['volume']),2)."]";
        }
    }
    echo "</th></tr>";
    $i = $i+1;
    } 
    echo "</table>";
    ?>
    <br>
    </div>
    </form>
    </td>
<td width="160"></td></tr>
</table>
</body></html>
