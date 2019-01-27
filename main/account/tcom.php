<?php 
include '../../config/dbc.php';
page_protect();
include '../../libs/progdate.php';

$title = "::บัญชีและการเงิน::";
include '../../main/header.php';
include '../../libs/currency.php';
include '../../libs/popup.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../main/bodyheader.php';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="160" valign="top"><div class="pos_l_fix">
    <?php 
    if (isset($_SESSION['user_id']))
    {
        include 'accountmenu.php';
    } 
    ?></div>
    </td><td><h3 class="titlehdr">บัญชีเงินสด ประจำเดือน <?php $m = $sm;
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
    <table style="width:80%; text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
    <tr><td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">
        <table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2"  class="TFtable">
        <tr><th width = 12%>วันที่</th><th>รายละเอียด</th><th width = 30%>รับ-ขาย</th></tr>
        <?php 	
        $pvmc = "ยอดเงินสดยกมา";
        $cashpin = 0;
        $cashpout = 0;
        $callin = 0;
        $callout =0;
        $pvmci = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i = 10000001 ");
        $pvmco = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o = 10000001 ");
        while($row = mysqli_fetch_array($pvmci))
        { 	
            $date = new DateTime($row['date']);
            $smp = $date->format("m");
            $syp = $date->format("Y");
        if ($syp < $sy) 
            {
                $cashpin = $cashpin +  $row['price'];
            }
        if($syp == $sy)
            {
                if ($smp < $sm)
                {
                    $cashpin = $cashpin + $row['price'];
                }
            }
        }	
        while($row = mysqli_fetch_array($pvmco))
        { 	
            $date = new DateTime($row['date']);
            $smp = $date->format("m");
            $syp = $date->format("Y");
            if($syp < $sy)
            {
                $cashpout = $cashpout + $row['price'];
            }
            if($syp == $sy)
            {
                if ($smp < $sm)
                {
                    $cashpout = $cashpout + $row['price'];
                }
            }
        }	
        // Print out the contents of each row into a table
            echo "<tr><th>";
            echo 1;
            echo "</th><th >"; 
            echo $pvmc;
            echo "</th><th width=30% style='text-align: right;'>"; 
            echo "<span class=currency>".($cashpin - $cashpout)."</span>";
            echo "</th></tr>";
        //ยอดยกมา จบ	
        if($sm == date("m") and $sy == date("Y")) $imax = date("d");
        elseif($sm == 1 or $sm == 3 or $sm == 5 or $sm == 7 or $sm == 8 or $sm == 10 or $sm == 12) $imax=31;
        elseif($sm == 2 and $sy%4 == 0) $imax = 29;
        elseif($sm == 2 and $sy%4 != 0) $imax = 28;
        else $imax = 30;
        for ($i = 1;$i<=$imax;$i++)
        {
            $din = $sy.'-'.$sm.'-'.$i;
            $ctm = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i = 10000001 AND date = '$din' ");
            while ($row = mysqli_fetch_array($ctm))
            {
                // Print out the contents of each row into a table
                echo "<tr><th>";
                echo $i;
                echo "</th><th >"; 
                echo $row['detail'];
                echo "</th><th width=30% style='text-align: right;'>"; 
                echo "<span class=currency>".$row['price']."</span>";
                $callin = $callin + $row['price'];
                echo "</th></tr>";
            }	
        }
        echo "</table>";
        echo "<table style='text-align: center; margin-left: auto; margin-right: auto; width: 100%;' border='1' cellpadding='2' cellspacing='2' >";
        echo "<tr><th>ยอดรวม</th><th width = 30% style='text-align: right;'>";
        echo "<span class=currency>".($cashpin - $cashpout + $callin)."</span>";
        echo "</th></tr>";
        ?>
        </table>
    </td><td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">
        <table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2" class="TFtable">
        <tr><th width = 12%>ลำดับ</th><th  >รายละเอียด</th><th width = 30%>จ่าย-ซื้อ</th></tr>
        <?php 	
        if($sm == date("m") and $sy == date("Y")) $imax = date("d");
        elseif($sm == 1 or $sm == 3 or $sm == 5 or $sm == 7 or $sm == 8 or $sm == 10 or $sm == 12) $imax=31;
        elseif($sm == 2 and $sy%4 == 0) $imax = 29;
        elseif($sm == 2 and $sy%4 != 0) $imax = 28;
        else $imax = 30;
        for ($i = 1;$i<=$imax;$i++)
        {
            $din = $sy.'-'.$sm.'-'.$i;
            $ctm = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o = 10000001 AND date = '$din' ");
            while ($row = mysqli_fetch_array($ctm))
            {
                // Print out the contents of each row into a table
                echo "<tr><th>";
                echo $i;
                echo "</th><th >"; 
                echo $row['detail'];
                echo "</th><th width=30% style='text-align: right;'>"; 
                echo "<span class=currency>".$row['price']."</span>";
                $callout = $callout + $row['price'];
                echo "</th></tr>";
            }	
        }
        echo "</table>";
        echo "<table style='text-align: center; margin-left: auto; margin-right: auto; width: 100%;' border='1' cellpadding='2' cellspacing='2'>";
        echo "<tr><th>ยอดรวม</th><th width = 30%  style='text-align: right;'>";
        echo "<span class=currency>".$callout."</span>";
        echo "</th></tr>";

        //commission cal 11000000-19999999 ลูกหนี้ คนไข้ค้างชำระ
        $asgp =0;
        for ($i = 1;$i<=$imax;$i++)
        {
            $din = $sy.'-'.$sm.'-'.$i;
        // combind all cash for sell 
        $cotm = mysqli_query($link, "SELECT * FROM daily_account WHERE (ac_no_o = 40000001 OR (ac_no_o >= 11000000 and ac_no_o <= 19999999)) AND date = '$din' ");
            while ($row = mysqli_fetch_array($cotm))
            {
                $asgp = $asgp + $row['price'];
            }	
        }
        ?>
        </table>
    </td></tr>
    </table>
    <br><h3 class="titlehdr" align="center">ยอดขายรับชำระแล้ว <?php echo "<span class=currency>".$asgp."</span>";?> บาท Com: <?php 
    $comrate = mysqli_query($link, "SELECT * FROM commission ORDER BY `sellofmon` ASC ");
    while($row =mysqli_fetch_array($comrate))
    {
    if($row['sellofmon'] <= $asgp) $rate = $row['perofcom'];
    }
    echo $rate;
    echo "% = ";
    echo "<span class=currency>".floor($asgp* $rate/100)."</span>";
    echo " บาท";
    ?>
    </h3>
</td><td width="160" valign="top">
<div class="pos_r_fix_mypage1"><h6 class="titlehdr2" align="center">ประเภทบัญชี</h6>
<?php 
if (isset($_SESSION['user_id']))
{
include 'actmenu.php';
} 
?>
</div></td></tr>
</table>
</body></html>
