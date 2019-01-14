<?php 
include '../../config/dbc.php';
page_protect();
include '../../libs/progdate.php';

$title = "::ห้องยา::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../libs/currency.php';
include '../../main/bodyheader.php';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
<tr><td width="160" valign="top"><div class="pos_l_fix">
    <?php 
    if (isset($_SESSION['user_id']))
    {
    include 'drugmenu.php';
    } 
    ?></div>
    </td><td>
        <h3 class="titlehdr">ยอดการใช้ยาและเวชภัณฑ์ ประจำเดือน <?php $m = $sm;// date("m");
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
        }?> พ.ศ. <?php echo $bsy; //date("Y")+543;?></h3>
    <table style="text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2"  class='TFtable'>
        <tr><th>ID</th><th>ชื่อยา</th><th>Generic</th><th>Size</th><th>Stock-Vol</th><th>Buy-Vol</th><th>Used-Vol</th><th>Supp.</th></tr>
        <?php
        if($sm == date("m") and $sy == date("Y")) $imax = date("d");
        elseif($sm == 1 or $sm == 3 or $sm == 5 or $sm == 7 or $sm == 8 or $sm == 10 or $sm == 12) $imax=31;
        elseif($sm == 2 and $sy%4 == 0) $imax = 29;
        elseif($sm == 2 and $sy%4 != 0) $imax = 28;
        else $imax = 30;

        $pin = mysqli_query($link, "select MAX(id) from drug_id");
        $rid = mysqli_fetch_array($pin);
        $pin = mysqli_query($link, "select * from drug_id ORDER BY `dgname` ASC ");
        $i=1;
        while($row = mysqli_fetch_array($pin))
        {
            $did[$i] = $row['id'];
            $didacno[$i] = $row['ac_no'];
            $i=$i+1;
        }
        for ($i = 1;$i<=$rid[0];$i++)
        {
            // Print out the contents of each row into a table
            $ddname = mysqli_query($link, "select * from drug_id WHERE id=$did[$i]");
            $ridinf = mysqli_fetch_array($ddname);
            if(!empty($ridinf['dname']))
            {
                echo "<tr><td >";
                echo $i;
                echo "</td><td>";
                echo $ridinf['dname'];
                echo "</td><td>";
                echo $ridinf['dgname'];
                echo "</td><td>";
                echo $ridinf['size'];
                echo "</td><td>";
                echo $ridinf['volume'];
                echo "</td><td>";
                $dtype = mysqli_query($link, "SELECT * FROM drug_$did[$i] WHERE MONTH(date) = '$sm' AND YEAR(date) = '$sy'");
                while($row = mysqli_fetch_array($dtype))
                {
                    $drugbuy[$i] = $drugbuy[$i] + $row['volume'];
                    $supp[$i] = $row['supplier'];
                } 
                echo $drugbuy[$i];
                echo "</td><td>";
                $dupmin = mysqli_query($link, "SELECT * FROM dupm WHERE drugid = '$did[$i]' AND MONTH(mon) = '$sm' AND YEAR(mon) = '$sy'");
                $dupmo = mysqli_fetch_array($dupmin);
                echo $dupmo['vol'];
                //echo $druguse[$i];
                echo "</td><td>";
                echo $supp[$i];
                echo "</td></tr>";
            }
            //update allrsupm table price calculation
            $dtype = mysqli_query($link, "SELECT * FROM drug_$did[$i]");
            while ($row1 =  mysqli_fetch_array($dtype))
            {
                $allprice[$i] = $allprice[$i]+$row1['price']*$row1['customer']/$row1['volume'];
            }

            //get previous month price
            $allrsu = mysqli_query($link, "select * from allrsupm WHERE drugid=$didacno[$i] AND mandy < date('$sy-$sm-01')");

            while ($row1 =  mysqli_fetch_array($allrsu))
            {
                $omprice[$i] = $omprice[$i]+$row1['price'];
            }
            //get this month price
            $allrsu = mysqli_query($link, "select * from allrsupm WHERE drugid=$didacno[$i] AND MONTH(mandy) = '$sm' AND YEAR(mandy) = '$sy'");
            $rinfalrsu = mysqli_fetch_array($allrsu);
            $rowid = $rinfalrsu['id'];
            
            $tmprice[$i] = $rinfalrsu['price'];
            //check for month to update  if not this current month not update data

            if( (date('m') == $sm) AND (date('Y') == $sy))
            {
                if($did[$i]!=0)
                {
                    if(empty($rowid))
                    {
                        $tmprice[$i] = $allprice[$i]-$omprice[$i];

                        if ($tmprice[$i]<0)$tmprice[$i] = 0;

                        $sql_insert = "INSERT into `allrsupm`
                        (`drugid`,`mandy`,`price`)
                        VALUES
                        ('$didacno[$i]',now(),'$tmprice[$i]')";
                        // Now insert into "allrsupm" table
                        mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
                    }
                    else
                    { 
                        $tmpricenew = $allprice[$i]-$omprice[$i];

                        if ($tmpricenew<0)$tmpricenew = 0;

                        $sql_insert = "UPDATE `allrsupm` SET `mandy` = now(),`price` = '$tmpricenew' WHERE id=$rowid LIMIT 1 ;";

                        // Now insert into "allrsupm" table
                        mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
                    }
                }
            }
        }
        ?>
    </table>
    </td><td width="160" valign="top"><div class="pos_r_fix_mypage1">
    <?php 
    if (isset($_SESSION['user_id']))
    {
        include 'dusmenu.php';
    } 
    ?></div>	
</td></tr>
</table>
</body></html>
