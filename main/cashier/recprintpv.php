<?php 
include '../../config/dbc.php';

page_protect();

$id = $_SESSION['patdesk'];
$Patient_id = $id;
include '../../libs/opdxconnection.php';


$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
//
$today = date("Y-m-d");
$pin = mysqli_query($linkopdx, "select MAX(id) from $pttable where clinic='$_SESSION[clinic]'");
$maxrow = mysqli_fetch_array($pin);

if($maxrow[0]!=$_SESSION['mrid'])
{
    $_SESSION['mrid'] = $maxrow[0];
    $_SESSION['rid'] = $maxrow[0];
}

if ($_POST['todo'] == '<<' )
{
	$pin = mysqli_query($linkopdx, "select MAX(id) from $pttable where clinic='$_SESSION[clinic]' and id< '$_SESSION[rid]'");
	$maxrow = mysqli_fetch_array($pin);
	$_SESSION['rid'] = $maxrow[0];
}
elseif ($_POST['todo'] == 'Last' )
{
	$_SESSION['rid'] = $_SESSION['mrid'];
}
elseif ($_POST['todo'] == '>>' )
{
	$pin = mysqli_query($linkopdx, "select MIN(id) from $pttable where clinic='$_SESSION[clinic]' and id> '$_SESSION[rid]'");
	$maxrow = mysqli_fetch_array($pin);
	$_SESSION['rid'] = $maxrow[0];
}

$title = "::ใบเสร็จรับเงิน::";
include '../../main/header.php';
echo "<script language=\"JavaScript\" type=\"text/javascript\" src=\"../../jscss/js/autoclick.js\"></script>";

?>
<link href="../../jscss/css/recform.css" rel="stylesheet" type="text/css">
<script language="javascript">
function Clickheretoprint()
{ 
  var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
      disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25"; 
  var content_vlue = document.getElementById("print_content").innerHTML; 
  
  var docprint=window.open("","",disp_setting); 
   docprint.document.open(); 
   docprint.document.write("<html><head><title>ใบเสร็จรับเงิน</title>"); 
   docprint.document.write("<link rel='stylesheet' href='../../jscss/css/recform_print.css'/>"); 
   docprint.document.write("</head><body onLoad='self.print()'>");          
   docprint.document.write(content_vlue);          
   docprint.document.write("</body></html>"); 
   docprint.document.close(); 
   docprint.focus(); 
}
</script>
</head><body>
<div class="myaccount">
    <form method="post" action="recprintpv.php" name="regForm" id="regForm"><div style="text-align: center;">
    <table width=100%>
    <tr><td width=33%>
    <?php 
        if($_SESSION['rid'] > 1) echo "<input type='submit' name='todo' value='<<' >";
        echo "</td><td width=33%>";
        echo "<input type='submit' name='todo' value='Last' ></td><td width=33%>";
        if($_SESSION['rid'] < $_SESSION['mrid']) echo "<input type='submit' name='todo' value='>>' >";
        echo "</td></tr>";
    ?>
    </table>
    </div></form>
    <?php 
    $pin = mysqli_query($linkopdx, "select * from $pttable WHERE id= '$_SESSION[rid]' AND clinic='$_SESSION[clinic]'");
    while ($row = mysqli_fetch_array($pin))
    {
        $date = new DateTime($row['date']);
        $sd = $date->format("d");
        $sm = $date->format("m");
        $sy = $date->format("Y");
        $hms = $date->format("G:i:s");
        $hms2 = $date->format("Gis");
        $bsy = $sy +543;
        $vsdate = $row['date'];
    }
    echo "<h2 class='titlehdr'>";
    echo "<br> ค่าใช้จ่าย ณ วันที่ ";  
    echo $sd." "; 
    $m = $sm;
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
    }?> พ.ศ. <?php echo $bsy; echo "  เวลา ";echo $hms; echo " น." //date("Y")+543; ?></h2>
    <div align="center"><input type="submit" name="OK" value="Print" onClick="javascript:Clickheretoprint()" ></div><br><br>
    <table style="width: 100%; text-align: center; margin-left: auto; margin-right: auto;" border="0" cellpadding="3" cellspacing="3">	
    <tr><td>
        <div class="style3" id="print_content">
        <div class="page">
        <div class="subpage">
        <div class="a">
        <div style="text-align: center;">ใบเสร็จรับเงิน เลขที่.<?php echo $id.$sy.$sm.$sd.$hms2;?><br>
        <?php
            $rs_settings = mysqli_query($link, "select * from parameter where id='1'");
            while ($row_settings = mysqli_fetch_array($rs_settings))
            {
                echo $row_settings['name'];
                echo "<br>";
                echo "ใบอนุญาตเลขที่ ";
                echo $row_settings['cliniclcid'];
                echo "<br>";
                echo $row_settings['address']."<br>โทร.";
                echo $row_settings['tel'].",";
                echo $row_settings['mobile']."<br> Email:";
                echo $row_settings['email'];
            }
            
        ?>
        <br>วันที่ <?php echo $sd; ?> <?php $m = $sm;
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
                }?> พ.ศ. <?php echo $bsy; //date("Y")+543;?><br>
                                ได้รับเงินจาก &nbsp; 
                                <?php
                        while ($row_settings = mysqli_fetch_array($ptin))
                        {
                            echo $row_settings['prefix'];
                            echo "&nbsp;"; 
                            echo $row_settings['fname'];
                            echo "&nbsp; &nbsp; &nbsp;"; 
                            echo $row_settings['lname'];
                            if($row_settings['reccomp']!=0)
                            {			
                            echo "<br>";
                            $comp = mysqli_query($link, "SELECT * FROM reccompany WHERE id='$row_settings[reccomp]'");
                            $crow = mysqli_fetch_array($comp);
                            echo "ในนาม ".$crow['comdt'];
                            }
                        }
                        echo "<br>";
                                ?>
                                
        <table class="d" style="background-color: rgb(255, 204, 153); text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="1" cellspacing="1">
            <tr><th width="6%">No</th><th width="">รายการทั้งหมด</th><th width="15%">ราคา</th><th width="12%">จำนวน</th><th width="10%">รวม</th></tr>
            <?php 
    //Treatment price
    $j = 1;
    for($i =1;$i<=4;$i++)
    {
        $ptin = mysqli_query($linkopdx, "select * from $pttable WHERE id= '$_SESSION[rid]' AND clinic= '$_SESSION[clinic]' ");
        while ($row = mysqli_fetch_array($ptin))
        {
            $idtr = "idtr".$i;
            $tr ="tr".$i;
            $trv = "trv".$i;
            //echo "<tr><td>".$i."</td><td>";
            if($row[$idtr] !=0)
            {
                echo "<tr><td>".$j."</td><td  style='text-align:left;'>";
                echo $row[$tr];
                echo "</td>";
                echo "<td style='text-align:right;'>";
                $did = $row[$idtr];
                /*
                //check id if match jump
                for($s=1;$s<=$t;$s++)
                {
                if($did ==  $tr_drugid[$s]) goto jpoint1;
                }
                */
                $ptin2 = mysqli_query($link, "select * from drug_id WHERE id = $did ");
                if($ptin2 !=0)
                {
                while ($row2 = mysqli_fetch_array($ptin2))
                {
                //check for candp
                    $candp = $row2['candp'];
                    if($candp == 2)
                    {
                    $checkuprdp = $checkuprdp + floor($coursepd*$row2['sellprice']*$row[$trv]/100);
                    }
                //
                    echo $row2['sellprice'];
                    $buypr = $row2['buyprice']*$row[$trv];
                    $dcount = floor($row2['sellprice'] * $row[$trv] * $row2['disct'] * $perdc);
                    if ($dcount>$buypr)
                    {
                    $dcount=$buypr;
                    }
                    $price1 = $row2['sellprice'] * $row[$trv] - $dcount;
                    $discount =$discount + $dcount;
                }
                }
                jpoint1:
                echo "</td>";
                echo "<td>";
                echo $row[$trv];
                echo "</td>";
                echo "<td style='text-align:right;'>";
    /*			
    if($did ==  $tr_drugid[$s])
    {
        if($row[$trv]>=$first1[$s]) 
        $price1 = ($row[$trv]-$first1[$s]+1)*$f1price[$s];
        if($row[$trv]>=$sec2[$s]) 
        $price1 = ($row[$trv]-$sec2[$s]+1)*$sec2price[$s]+($sec2[$s]-$first1[$s])*$f1price[$s];
        if($row[$trv]>=$tri3[$s]) 
        $price1 = ($row[$trv]-$tri3[$s]+1)*$tri3price[$s]+($tri3[$s]-$sec2[$s])*$sec2price[$s]+($sec2[$s]-$first1[$s])*$f1price[$s];
    }
    */
                echo $price1;
                echo "</td></tr>";
                $allprice = $allprice+$price1;
                $j = $j+1;
            }
        }
    }
    /*		//treatment part
            
            $j = 1;
            for($i =1;$i<=4;$i++)
            {
        //	$ptin = mysqli_query($link, "select * from $pttable ");
        $ptin = mysqli_query($linkopdx, "select * from $pttable WHERE id= '$_SESSION['rid']' AND clinic= '$_SESSION[clinic]' ");
            while ($row = mysqli_fetch_array($ptin))
            {
                $idtr = "idtr".$i;
                $tr ="tr".$i;
                $trv = "trv".$i;
                //echo "<tr><td>".$i."</td><td>";
                if($row[$idtr] !=0)
                {
                    echo "<tr><td>".$j."</td><td  style='text-align:left;'>";
                    echo $row[$tr];
                    echo "</td>";
                    echo "<td style='text-align:right;'>";
                    $did = $row[$idtr];
                    $ptin2 = mysqli_query($link, "select * from drug_id WHERE id = $did ");
                    if($ptin2 !=0)
                    {
                    while ($row2 = mysqli_fetch_array($ptin2))
                    {
                        echo $row2['sellprice'];
                        $price1 = $row2['sellprice'] * $row[$trv] - floor($row2['sellprice'] * $row[$trv] * $row2['disct'] * $perdc);
                    }
                    }
                    echo "</td>";
                    echo "<td>";
                    echo $row[$trv];
                    echo "</td>";
                    echo "<td style='text-align:right;'>";
                    echo $price1;
                    echo "</td></tr>";
                    $allprice = $allprice+$price1;
                    $j = $j+1;
                }
            }
            }
    */
            //DF part
            for($i=1;$i<=10;$i++)
            {
        //	$ptin = mysqli_query($link, "select * from $pttable ");
        $ptin = mysqli_query($linkopdx, "select * from $pttable WHERE id= '$_SESSION[rid]' AND clinic= '$_SESSION[clinic]' ");
            while ($row = mysqli_fetch_array($ptin))
            {
                $idrx = "idrx".$i;
                $rx ="rx".$i;
                $rgx = "rxg".$i;
                $rxuses = "rx".$i."uses";
                $rxv = "rx".$i."v";
                if($row[$idrx] !=0 and $row[$rgx] =='DF')
                {
                    echo "<tr><td>".$j."</td><td style='text-align:left;'>";
                    echo "ค่าตรวจรักษาโดยแพทย์";
                    echo "</td>";
                    echo "<td style='text-align:right;'>";
                    $did = $row[$idrx];
                    $ptin2 = mysqli_query($link, "select * from drug_id WHERE id = $did AND dgname='DF'");
                    if($ptin2 !=0)
                    {
                    while ($row2 = mysqli_fetch_array($ptin2))
                    {
                        //echo $row2['sellprice'];
                        $price1 = $row2['sellprice'] * $row[$rxv] - floor($row2['sellprice'] * $row[$rxv] * $row2['disct'] * $perdc);
                    }
                    }
                    echo "</td>";
                    echo "<td>";
                    //echo $row[$rxv];
                    echo "</td>";
                    echo "<td style='text-align:right;'>";
                    echo $price1;
                    echo "</td></tr>";
                    $allprice = $allprice+$price1;
                    $j = $j+1;
                }
            }
            }
            //drug part
            
            for($i=1;$i<=10;$i++)
            {
        //	$ptin = mysqli_query($link, "select * from $pttable ");
        $ptin = mysqli_query($linkopdx, "select * from $pttable WHERE id= '$_SESSION[rid]' AND clinic= '$_SESSION[clinic]' ");
            while ($row = mysqli_fetch_array($ptin))
            {	
                $disprx = $row['disprxby'];
                $idrx = "idrx".$i;
                $rx ="rx".$i;
                $rgx = "rxg".$i;
                $rxuses = "rx".$i."uses";
                $rxv = "rx".$i."v";
                if($row[$idrx] !=0 and $row[$rgx] !='DF')
                {
                    echo "<tr><td>".$j."</td><td style='text-align:left;'>";
                    echo $row[$rx];
                    echo "</td>";
                    echo "<td style='text-align:right;'>";
                    $did = $row[$idrx];
                    $ptin2 = mysqli_query($link, "select * from drug_id WHERE id = $did ");
                    if($ptin2 !=0)
                    {
                    while ($row2 = mysqli_fetch_array($ptin2))
                    {
                        echo $row2['sellprice'];
                        $price1 = $row2['sellprice'] * $row[$rxv] - floor($row2['sellprice'] * $row[$rxv] * $row2['disct'] * $perdc);
                    }
                    }
                    echo "</td>";
                    echo "<td>";
                    echo $row[$rxv];
                    echo "</td>";
                    echo "<td style='text-align:right;'>";
                    echo $price1;
                    echo "</td></tr>";
                    $allprice = $allprice+$price1;
                    $j = $j+1;
                }
            }
            }
            //lab part
        
    $pin = mysqli_query($linkopdx, "select * from $pttable WHERE labid!='' AND id='$_SESSION[rid]'") ;
    while ($row_settings = mysqli_fetch_array($pin))
    {
        $labidr=$row_settings['labid'];
        if(!empty($labidr))
        {
        $n = substr_count($labidr, ';');
        //$str = 'hypertext;language;programming';
        $charsl = preg_split('/;/', $labidr);
        }
        $filter = mysqli_query($link, "select * from lab WHERE `L_Set` !='SETNAME' ORDER BY `id` ASC  ");
        while ($labinfo = mysqli_fetch_array($filter))
        {
            $lname = $labinfo['S_Name'];
            $lname1 = $labinfo['S_Name']." [".$labinfo['L_Name']."]";
            for ($i=0;$i<=$n;$i++)
            {
            if($lname1==$charsl[$i])
            {
            $alllabprice =  $alllabprice + $labinfo['price'];
            }
            } 
        }
    }

        $ptin = mysqli_query($link, "select * from sell_account WHERE ctmid= '$id' AND day=$sd AND month=$sm AND year=$sy AND vsdate='$vsdate'");
            while ($row = mysqli_fetch_array($ptin))
            {
            $cashp = $row['cash'];
            $ownp =$row['own'];
            $totalp =$row['total'];
            
            if($allprice+$alllabprice < $totalp)
            {
            $alllabprice = $totalp - $allprice;
            }
            }
            if($alllabprice>0)
            {
            echo "<tr><td>".$j."</td><td style='text-align:left;'>ค่าตรวจทาง Lab รวมทั้งหมด</td><td></td><td></td><td style='text-align:right;'>".$alllabprice;
            echo "</td></tr>";
            $allprice= $allprice+$alllabprice;
            }
            //lab price finish
            
            //accout system buy for today
            //$_SESSION['buyprice'] = $allprice;
            $acno = 11000000 + $id; 
            $newdate = $sy.'-'.$sm.'-'.$sd;
            //echo $newdate;
            $newdate = date($newdate);
            //echo $newdate;
            $olddeb = 0;
            $ptin3 = mysqli_query($link, "select * from `daily_account` WHERE ac_no_o = $acno and ctime > '$vsdate'");
            while ($row3 = mysqli_fetch_array($ptin3))
            {
                if($row3['price']>0 AND $row3['date']==$newdate AND $ownp < $row3['price'])
                {
                    echo "<tr><td>".$j."</td><td style='text-align:left;'>";
                    echo "จ่ายยอดค้างชำระ";
                    echo "</td><td>";
                    echo $row3['price'] - $ownp; 
                    echo "</td><td>1</td><td style='text-align:right;'>";
                    echo $olddeb = $row3['price']- $ownp; 
                    echo "</td></tr>";
                    break; //echo only one record 
                }
            }	
            
        echo "</table>";
        ?>
        <table class="d" style="background-color: rgb(255, 204, 153); text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="1" cellspacing="1">
            <tr><?php 
            echo "<th width='82%'> ยอดรวมสุทธิ</th><th width='10%' style='text-align:right;'>";
            echo ($allprice + $olddeb);
            echo "</th></tr>";
            $depnow = mysqli_fetch_array(mysqli_query($link, "select price from `debtors` WHERE ctmid = $id"));
            if($depnow[0]>0)
            {
            echo "<tr><th width='82%'>ค้างจ่าย</th><th width = 10% style='text-align:right;'>";
            echo $depnow[0];
            echo "</th></tr>";
            }
            echo "<tr><th width='82%'>จ่าย</th><th width = 10% style='text-align:right;'>"; 
            echo ($allprice + $olddeb - $depnow[0]);
            echo "</th></tr>";
            ?>
        </table>
        <br>
        รับเงินโดย:<u>
        <?php
            $staff = mysqli_query($link, "select * from staff WHERE ID = '$disprx' ");
            while($row_vl = mysqli_fetch_array($staff))
            {
            $prefix = $row_vl['prefix'];
            $stfname = $row_vl['F_Name'];
            $stlname = $row_vl['L_Name'];
            }
            echo $prefix.' '.$stfname.' '.$stlname;
        ?></u>
    </div></div></div></div></div>
    </td></tr>   
    </table><br>
</div>
</body></html>
