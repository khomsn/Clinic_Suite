<?php 
include '../../config/dbc.php';
page_protect();
include '../../libs/progdate.php';

$thisdate = date_create();
date_date_set($thisdate, $sy, $sm, $sd);
$ddate = date_format($thisdate, 'Y-m-d');

if($_POST['reset'])
{
  $id = $_POST['reset'];
  
	$sql_update = "UPDATE `lab` SET `volume` = '0' WHERE `id` ='$id' LIMIT 1 ; ";
	// Now update pttable
	mysqli_query($link, $sql_update);
      
}
if($_POST['Reset']=="Reset")
{
	$sql_update = "UPDATE `lab` SET `volume` = '0';"; 
	// Now update pttable
	mysqli_query($link, $sql_update);
}

$title = "::Laboratory::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../main/bodyheader.php';

?>
<table width="100%" border="0">
<tr><td width=160px><div class="pos_l_fix">
    <?php 
        /*********************** MYACCOUNT MENU ****************************
        This code shows my account menu only to logged in users. 
        Copy this code till END and place it in a new html or php where
        you want to show myaccount options. This is only visible to logged in users
        *******************************************************************/
        if (isset($_SESSION['user_id']))
        {
            include 'labmenu.php';
        } 
    /*******************************END**************************/
    ?></div>
    </td><td>
	<form method="post" action="labstats.php" name="regForm" id="regForm">
	<h3 class="titlehdr">
    <?php
    if ($_SESSION['user_level']>1)
    {
        if($ddate>$_SESSION['acstrdate'])
        {
            echo "<input type='submit' name='todom' value = '<<'>&nbsp;<input type='submit' name='todom' value = '@'>&nbsp;";
        }
        else
        {
            echo "<input type='submit' name='todom' value = '@'>&nbsp;";
        }
        if ($sm < date("m"))
        {
            if ($sy <= date("Y")){echo "<input type='submit' name='todom' value = '>>'>";}
        }
        if ($sy <= date("Y"))
        {
            if ($sm > date("m")){echo "<input type='submit' name='todom' value = '>>'> ";}
        }
    }
    echo "  รายงานการใช้ Lab ประจำเดือน ";
    $m = $sm;// date("m");
    switch ($m)
    {
        case 1:
        echo "มกราคม";
        break;
        case 2:
        echo "กุมภาพันธ์";
        break;
        case 3:
        echo "มีนาคม";
        break;
        case 4:
        echo "เมษายน";
        break;
        case 5:
        echo "พฤษภาคม";
        break;
        case 6:
        echo "มิถุนายน";
        break;
        case 7:
        echo "กรกฎาคม";
        break;
        case 8:
        echo "สิงหาคม";
        break;
        case 9:
        echo "กันยายน";
        break;
        case 10:
        echo "ตุลาคม";
        break;
        case 11:
        echo "พฤศจิกายน";
        break;
        case 12:
        echo "ธันวาคม";
        break;
    }?> พ.ศ. <?php echo $bsy; //date("Y")+543;
    ?></h3>
    <table border="1" width =100% class='TFtable' style="color:blue">
    <th>No</th><th>Name</th><th>Int</th><th>Spec</th><th>TVol</th><th>M-Vol</th><th>Cu-Vol</th><th><input type=submit name=Reset value=Reset></th>
    <?php
    $ptin = mysqli_query($link, "select * from lab ORDER BY id ASC ");
    while ($rows=mysqli_fetch_array($ptin))
    {
        echo "<tr><td>";
        echo $rows['id'];
        echo "</td><td>";
        if( $rows['id'] <5000 AND ($rows['id']%100!=0 ))
        {
        echo substr($rows['L_Set'], 5)."-";
        }
        echo $rows['L_Name'];
        echo "</td><td>";
        echo $rows['S_Name'];
        echo "</td><td>";
        echo $rows['L_specimen'];
        echo "</td><td style='text-align:right;'>";
        $ttvol=0;
        $labstin1 = mysqli_query($link, "SELECT vol FROM labstat WHERE labid = $rows[id]");
        while($ttv1 = mysqli_fetch_array($labstin1))
        {
        $ttvol = $ttvol + $ttv1['vol'];
        }
        echo $ttvol;
        echo "</td><td style='text-align:right;'>";
        $labstin = mysqli_query($link, "SELECT vol FROM labstat WHERE labid = $rows[id]  AND MONTH(MandY) = '$sm' AND YEAR(MandY) = '$sy'");
        $ttv = mysqli_fetch_array($labstin);
        echo $ttv[0];
        echo "</td><td style='text-align:right;'>";
        echo $rows['volume'];
        echo "</td><td>";
        if($_SESSION['user_accode']%13==0)
        {
        echo "<input type=submit name=reset value=".$rows['id'].">";
        }
        echo "</td></tr>";
    }
    ?>
    </table>
	</form>
   </td><td width=130px><div class="pos_r_fix"><?php include 'labrmenu.php';?></div>
</td></tr>
</table>
</body></html>
