<?php 
include '../../config/dbc.php';
page_protect();
include '../../libs/dateandtimezone.php';
include '../../libs/progdate.php';

if(empty($_SESSION['acno']))
$_SESSION['acno'] = $_POST['acout'];
if($_SESSION['acno'] != $_POST['acout'])
$_SESSION['acno'] = $_POST['acout'];

$title = "::บัญชีและการเงิน::";
include '../../main/header.php';
include '../../libs/currency.php';
include '../../libs/popup.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../main/bodyheader.php';

?>
<form method="post" action="bankaccountbalance.php" name="regForm" id="regForm">
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="160" valign="top"><div class="pos_l_fix">
    <?php 
    if (isset($_SESSION['user_id']))
    {
        include 'accountmenu.php';
    } 
    ?></div>
    </td><td><h3 class="titlehdr"><div style="background-color:rgba(124,200,0,0.65); display:inline-block;">รายการเดินบัญชี <select tabindex="1" name="acout" id="AcNo" class="required">
		    <?php
		    $condi = "(ac_no >=10000002 AND ac_no <=10000249)";
		    $dgroup = mysqli_query($link, "SELECT * FROM acnumber WHERE $condi
		    ORDER BY `name` ASC");
	    /* 
            10000001 เงินสด
            10000002-10000249 ธนาคาร
            10000250-10003999 อาคาร และ ที่ดิน สินทรัพย์อื่นๆ
            10004000-10009999 ลูกหนี้ทั่วไป
            10010000-10099999 วอ.แพทย์
            10100000-10299999 อ.สำนักงาน

            20000001-20199999 เจ้าหนี้ เงินกู้

            30000001-34999999 ทุน ผู้ร่วมทุน

            40000002-40000249 ดอกเบี้ยรับธนาคาร
            40000250-49999999 รายได้อื่นๆ
	    */
		    
		    while($grow = mysqli_fetch_array($dgroup))
		    {
			    echo "<option value='";
			    echo $grow['ac_no'];
			    echo "'";
			    if ($grow['ac_no'] == $_SESSION['acno'])
			    echo " selected ";
			    echo ">";
			    echo $grow['name']."</option>";
		    }
		    ?>
	    </select> ประจำเดือน <?php $m = $sm;
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
    }?> พ.ศ. <?php echo $bsy; //date("Y")+543;?></div></h3>
    <table style="width:80%; text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
    <tr><td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">
        <table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2"  class="TFtable">
        <tr><th width = 12%>วันที่</th><th>รายละเอียด</th><th width = 15%>รับ(+)/จ่าย(-) (บาท)</th><th width=15%>คงเหลือ (บาท)</th></tr>
        <?php
        $check_acno = $_SESSION['acno'];
        $pvmc = "ยอดยกมา";
        $cashpin = 0;
        $cashpout = 0;
        $callin = 0;
        $callout =0;
        $pvmci = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i = '$check_acno' ");
        $pvmco = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o = '$check_acno' ");
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
            echo "</th><th width=30% style='text-align: right;'>"; 
            echo "<span class=currency>".($remain = $cashpin - $cashpout)."</span>";
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
            $ctmin = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i = '$check_acno' AND date = '$din' ");
            $ctmout = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o = '$check_acno' AND date = '$din' ");
            while ($row = mysqli_fetch_array($ctmin))
            {
                // Print out the contents of each row into a table
                echo "<tr><th>";
                echo $i;
                echo "</th><th >"; 
                echo $row['detail'];
                echo "<sup><".$row['recordby']."></sup>";
                echo "</th><th width=30% style='text-align: right;'>"; 
                echo "<span class=currency>".$row['price']."</span>";
                $remain = $remain + $row['price'];
                echo "</th><th width=30% style='text-align: right;'>"; 
                echo "<span class=currency>".$remain."</span>";
                echo "</th></tr>";
            }	
            while ($row = mysqli_fetch_array($ctmout))
            {
                // Print out the contents of each row into a table
                echo "<tr><th>";
                echo $i;
                echo "</th><th >"; 
                echo $row['detail'];
                echo "<sup><".$row['recordby']."></sup>";
                echo "</th><th width=30% style='text-align: right;'>"; 
                echo "<span class=currency>-".$row['price']."</span>";
                $remain = $remain - $row['price'];
                echo "</th><th width=30% style='text-align: right;'>"; 
                echo "<span class=currency>".$remain."</span>";
                echo "</th></tr>";
            }
        }?>
        </table></td></tr>
    </table></td><td width="160" valign="top">
    <div class="pos_r_fix_mypage1"><h6 class="titlehdr2" align="center">ประเภทบัญชี</h6>
    <?php 
    if (isset($_SESSION['user_id']))
    {
    include 'actmenu.php';
    } 
    ?>
    </div></td></tr>
</table>
</form>
</body></html>
<script type="text/javascript">

  jQuery(function() {
    jQuery('#AcNo').change(function() {
        this.form.submit();
    });
});
</script>
