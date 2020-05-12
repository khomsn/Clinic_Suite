<?php 
include '../../config/dbc.php';
page_protect();
$err=array();

$filter = mysqli_query($link, "select * from deleted_rm ORDER BY `rawname` ASC");

if($_POST['Reactivate'] != 0) 
{ 
    echo $did = $_POST['Reactivate'];
    
    $didinfo = mysqli_query($link, "SELECT * FROM deleted_rm WHERE `id` = '$did'");
    while($infor = mysqli_fetch_array($didinfo))
    {
        $rawcode = $infor['rawcode'];
        $rawname = $infor['rawname'];
        $size = $infor['size'];
        $ac = $infor['ac_no'];
        $adn = $rawcode.'-'.$size;
    }

    //10700000-10999999 วัตถุดิบ 10700000 ตัดยอด //start with 10700000 + rawmat_id
/*
    $mmm=10700000;
    $maxm=10999999;

    $ac = mysqli_query($link, "SELECT * FROM acnumber WHERE (ac_no>=$mmm AND ac_no<$maxm) ORDER BY ac_no ASC");

    while($row = mysqli_fetch_array($ac))
    { 
        if(empty($row['ac_no'])) goto Nextacno;
        if( $mmm < $row['ac_no'] and $row['ac_no']<$maxm )
        { 
            $newmmm =$row['ac_no'];
            if($newmmm==($mmm+1))
            {
                $mmm=$row['ac_no'];
            }
            else goto Nextacno;
        }
    }
    Nextacno:
    // assign account number to new product.
    $ac = $mmm + 1;
*/
    $rs_duplicate = mysqli_query($link, "select count(*) as total from rawmat where rawcode='$rawcode' AND rawname='$rawname' AND size='$size' ") or $err[]=(mysqli_error($link));
    list($total) = mysqli_fetch_row($rs_duplicate);

    if ($total > 0)
    {
        $err[] = "ERROR - This Raw-Material already exists. Please Check.";
        goto ErrorJump;
    }
    /***************************************************************************/
    if(empty($err))
    {
        //assign ac_no to table acnumber
/*        $sql_insert = "INSERT into `acnumber`
                    (`ac_no`, `name`)
                    VALUES
                    ('$ac','$adn')";
        // Now insert Account number for product to "acnumber" table
        mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));
*/
        // assign insertion pattern
        $sql_insert = "INSERT into `rawmat`
                    (`id`,`rawcode`,`rawname`, `size`, `sunit`, `lowlimit`, `volume`, `ac_no`, `rmfpd`, `rmtype`,`location`)
                    VALUES
                    ('$did','$rawcode','$rawname','$size','','0','0', '$ac','0','','')";
        // Now insert into "rawmat" table
        mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));
        //remove this id from deleted_rm
        $sql_del = "DELETE FROM `deleted_rm` WHERE `id` = '$did'";
        // Now DELETE Patient to "pt_to_lab" table
        mysqli_query($link, $sql_del) or $err[]=("Insertion Failed:" . mysqli_error($link));
    }
    // go on to other step
    header("Location: delrmlistandreactivate.php");  

}
ErrorJump:

$title = "::ห้องคลังวัตถุดิบ::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
include '../../main/bodyheader.php';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
<tr><td width="170" valign="top"><div class="pos_l_fix">
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
</td>
<td><div align="center">
<?php
/******************** ERROR MESSAGES*************************************************
This code is to show error messages 
**************************************************************************/
if(!empty($err))
{
    echo "<div class=\"msg\">";
    foreach ($err as $e) {echo "$e <br>";}
    echo "</div>";
    echo "<br>";
}
/******************************* END ********************************/	  
?><h3 class="titlehdr">รายการวัตถุดิบที่ถูกลบจากระบบ</h3>
<form method="post" action="delrmlistandreactivate.php" name="regForm" id="regForm">

            <table style="text-align: center;" border="0" cellpadding="2" cellspacing="2">
            <tr><td style="vertical-align: middle; ">
                    <?php	
                            echo "<table class='TFtable' border='1' style='text-align: left; margin-left: auto; margin-right: auto;'>";
                            echo "<tr>";
                            echo "<th>ชื่อ</th><th>ชื่อสามัญ</th><th>ขนาด</th>";
                            echo "<th>Reactivate</th>";
                            echo "</tr>";
                            while($row = mysqli_fetch_array($filter))
                                {
                                    // Print out the contents of each row into a table
                                    echo "<tr><td>";
                                    echo $row['rawcode'];
                                    echo "</td><td>"; 
                                    echo $row['rawname'];
                                    echo "</td><td >"; 
                                    echo $row['size'];
                                    echo "</td>";
                                    echo "<td>";
                                    echo "<input type=submit name='Reactivate' value='$row[id]'/>";
                                    echo "</td></tr>";
                            } 
                            echo "</table>";
                    ?>
            </td></tr></table>
    </div>
</td></tr>
</table>
</body></html>
