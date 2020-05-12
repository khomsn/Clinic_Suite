<?php 
include '../../config/dbc.php';

page_protect();

$id = $_SESSION['patdesk'];
$Patient_id = $id;
include '../../libs/opdxconnection.php';

if($_POST['language'] == 'English')
{
    $_SESSION['language'] = "English";
}
elseif($_POST['language'] == 'Thai')
{
    $_SESSION['language'] = "ไทย";
}
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
<script type='text/javascript'>
$(document).ready(function Cl() 
{ 
    $('input[name=language]').change(function Cl(){
        $('form').submit();
    });
});
</script>
</head><body>
<div class="myaccount">
    <form method="post" action="recprintpv.php" name="regForm" id="regForm"><input type="radio" name="language" value="English" <?php if ($_SESSION['language'] == "English") echo "checked";?>>English<input type="radio" name="language" value="Thai" <?php if ($_SESSION['language'] =="ไทย") echo "checked";?>>ไทย
    <div style="text-align: center;">
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
        <div style="text-align: center;"><?php if($_SESSION['language']=='English') echo "RECEIPT No."; else echo " ใบเสร็จรับเงิน เลขที่."; echo $id.$sy.$sm.$sd.$hms2;?><br>
        <?php
            $rs_settings = mysqli_query($link, "select * from parameter where id='1'");
            while ($row_settings = mysqli_fetch_array($rs_settings))
            {
                if($_SESSION['language']=='English')
                {
                echo $row_settings['Ename'];
                echo "<br>";
                echo "License number ";
                echo $row_settings['cliniclcid'];
                echo "<br>";
                echo $row_settings['Eaddress']."<br>Tel.";
                }
                else
                {
                echo $row_settings['name'];
                echo "<br>";
                echo "ใบอนุญาตเลขที่ ";
                echo $row_settings['cliniclcid'];
                echo "<br>";
                echo $row_settings['address']."<br>โทร.";
                }
                echo $row_settings['tel'].",";
                echo $row_settings['mobile']."<br> Email:";
                echo $row_settings['email'];
            }
            
	echo "<br>";
	
	if($_SESSION['language']=='English') echo "Date ";
	else echo "วันที่ ";
	echo $sd;
	$m = $sm;
        if($_SESSION['language']=='English')
        {
			switch ($m)
			{
				 case 1:
				 echo " January ";
				 break;
 				 case 2:
				 echo " February ";
				 break;
				 case 3:
				 echo " March ";
				 break;
				 case 4:
				 echo " April ";
				 break;
				 case 5:
				 echo " May ";
				 break;
				 case 6:
				 echo " June ";
				 break;
				 case 7:
				 echo " July ";
				 break;
				 case 8:
				 echo " August ";
				 break;
				 case 9:
				 echo " September ";
				 break;
				 case 10:
				 echo " October ";
				 break;
				 case 11:
				 echo " November ";
				 break;
				 case 12:
				 echo " December ";
				 break;
            }
			echo $sy; //date("Y")+543;
			echo "<br>Recieved payment from "; 
        }
        else
        {
			switch ($m)
			{
				 case 1:
				 echo " มกราคม";
				 break;
 				 case 2:
				 echo " กุมภาพันธ์";
				 break;
				 case 3:
				 echo " มีนาคม";
				 break;
				 case 4:
				 echo " เมษายน";
				 break;
				 case 5:
				 echo " พฤษภาคม";
				 break;
				 case 6:
				 echo " มิถุนายน";
				 break;
				 case 7:
				 echo " กรกฎาคม";
				 break;
				 case 8:
				 echo " สิงหาคม";
				 break;
				 case 9:
				 echo " กันยายน";
				 break;
				 case 10:
				 echo " ตุลาคม";
				 break;
				 case 11:
				 echo " พฤศจิกายน";
				 break;
				 case 12:
				 echo " ธันวาคม";
				 break;
            }
			echo " พ.ศ.".$bsy; //date("Y")+543;
			echo "<br>ได้รับเงินจาก "; 
		}

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
            if($_SESSION['language']=='English')
            echo "In the name of ".$crow['comdt'];
            else
            echo "ในนาม ".$crow['comdt'];
            }
        }
        echo "<br>";
    ?>
                                
<table class="d" style="background-color: rgb(255, 204, 153); text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="1" cellspacing="1">
<?php 
if($_SESSION['language']=='English')
    echo "<tr><th width='6%'>No</th><th>Item</th><th>Price</th><th width='12%'>Vol.</th><th width='10%'>Subtotal</th></tr>";
else
    echo "<tr><th width='6%'>No</th><th>รายการทั้งหมด</th><th>ราคา</th><th width='12%'>จำนวน</th><th width='10%'>รวม</th></tr>";
/*************************Treatment price******************************************************/
$discounttoday=0;
$j = 1;
for($i =1;$i<=4;$i++)
{
    $ptin = mysqli_query($linkopdx, "select * from $pttable WHERE id= '$_SESSION[rid]' AND clinic= '$_SESSION[clinic]' ");
    while ($row = mysqli_fetch_array($ptin))
    {
        $idtr = "idtr".$i;
        $tr ="tr".$i;
        $trv = "trv".$i;
        if($row[$idtr] !=0)
        {
            echo "<tr><td>".$j."</td><td  style='text-align:left;'>";
            echo $row[$tr];
            echo "</td>";
            echo "<td style='text-align:right;'>";
            $did = $row[$idtr];
            /* check for treatment step price */
            $stp = mysqli_query($link, "select * from trpstep WHERE drugid = $did ");
            if($stp !=0)
            {
                while ($stpc = mysqli_fetch_array($stp))
                {
                    if($row[$trv]>=$stpc['firstone']) 
                        $price1 = ($row[$trv]-$stpc['firstone']+1)*$stpc['init_pr'];
                    if($row[$trv]>=$stpc['secstep']) 
                        $price1 = ($row[$trv]-$stpc['secstep']+1)*$stpc['sec_pr']+($stpc['secstep']-$stpc['firstone'])*$stpc['init_pr'];
                    if($row[$trv]>=$stpc['tristep']) 
                        $price1 = ($row[$trv]-$stpc['tristep']+1)*$stpc['tri_pr']+($stpc['tristep']-$stpc['secstep'])*$stpc['sec_pr']+($stpc['secstep']-$stpc['firstone'])*$stpc['init_pr'];
                }
            }
            else
            {
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
                }
                }
            }
            echo "</td>";
            echo "<td>";
            echo $row[$trv];
            echo "</td>";
            echo "<td style='text-align:right;'>";
            echo $pricetr = $price1;
            echo "</td></tr>";
            $allprice = $allprice+$pricetr;
            $j = $j+1;
        }
    }
}
/***********DF part******************************************************************/
for($i=1;$i<=14;$i++)
{
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
            if($_SESSION['language']=='English')
            echo "Doctor Fee";
            else
            echo "ค่าตรวจรักษาโดยแพทย์";
            echo "</td>";
            echo "<td style='text-align:right;'>";
            $did = $row[$idrx];
            $ptin2 = mysqli_query($link, "select * from drug_id WHERE id = $did AND dgname='DF'");
            if($ptin2 !=0)
            {
                while ($row2 = mysqli_fetch_array($ptin2))
                {
                    $price1 = $row2['sellprice'] * $row[$rxv] - floor($row2['sellprice'] * $row[$rxv] * $row2['disct'] * $perdc);
                }
            }
            echo "</td>";
            echo "<td>";
            echo "</td>";
            echo "<td style='text-align:right;'>";
            echo $price1;
            echo "</td></tr>";
            $allprice = $allprice+$price1;
            $j = $j+1;
        }
    }
}
/**********************drug part************************************************/
for($i=1;$i<=14;$i++)
{
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

/**************** all drug + df + treatment price******************/
$all_ddft_price = $allprice;
/******************************* lab part****************************/
    
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
    $cashp = $row['pay'];
    $ownp =$row['own'];
    $totalp =$row['total'];

    if($allprice+$alllabprice < $totalp)
    {
        $alllabprice = $totalp - $allprice;
    }
}
if($alllabprice>0)
{
    echo "<tr><td>".$j."</td><td style='text-align:left;'>";
    if($_SESSION['language']=='English')
    echo "All Labs Price ";
    else
    echo "ค่าตรวจทาง Lab รวมทั้งหมด";
    echo "</td><td></td><td></td><td style='text-align:right;'>".$alllabprice;
    echo "</td></tr>";
    $allprice= $allprice+$alllabprice;
}
//lab price finish

/******************************* accout system buy for today ***********************/
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
        if($_SESSION['language']=='English')
        echo "Previous Debt";
        else
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
<?php 
if($_SESSION['language']=='English')
echo "<tr><th width='82%'> Grand Total</th><th width='10%' style='text-align:right;'>";
else
echo "<tr><th width='82%'> ยอดรวมสุทธิ</th><th width='10%' style='text-align:right;'>";
echo ($allprice + $olddeb);
echo "&#xE3F;</th></tr>";
if($all_ddft_price>$totalp)
{
if($_SESSION['language']=='English')
echo "<tr><th width='82%'> Discount on medicines</th><th width='10%' style='text-align:right;'>";
else
echo "<tr><th width='82%'> ส่วนลดค่ายา</th><th width='10%' style='text-align:right;'>";
echo $discounttoday = ($all_ddft_price - $totalp);
echo "&#xE3F;</th></tr>";
}
$depnow = mysqli_fetch_array(mysqli_query($link, "select price from `debtors` WHERE ctmid = $id"));
if($depnow[0]>0)
{
if($_SESSION['language']=='English')
echo "<tr><th width='82%'>Arrear</th><th width = 10% style='text-align:right;'>";
else
echo "<tr><th width='82%'>ค้างจ่าย</th><th width = 10% style='text-align:right;'>";
echo $depnow[0];
echo "&#xE3F;</th></tr>";
}
if($_SESSION['language']=='English')
echo "<tr><th width='82%'>Paid</th><th width = 10% style='text-align:right;'>";
else
echo "<tr><th width='82%'>จ่าย</th><th width = 10% style='text-align:right;'>"; 
echo ($allprice + $olddeb - $depnow[0] - $discounttoday) ;
echo "&#xE3F;</th></tr>";
?>
</table>
<br>
<?php
if($_SESSION['language']=='English')
echo "Cashier:<u>";
else
echo "รับเงินโดย:<u>";
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
