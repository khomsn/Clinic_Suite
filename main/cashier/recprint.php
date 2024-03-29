<?php 
include '../../config/dbc.php';

page_protect();

include '../../libs/dateandtimezone.php';
include '../../libs/progdate.php';

$id = $_SESSION['patcash'];
$Patient_id = $id;
include '../../libs/opdxconnection.php';

$ctmid = $id;
$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
$tmp = "tmp_".$id;
$today = date("Y-m-d");
$visitdt = $_SESSION['vd'];
$all_ddft_price=0;

$pin = mysqli_query($linkopdx, "select * from $pttable  ORDER BY `id` ASC ");
while ($row_settings = mysqli_fetch_array($pin))
{
    $rid = $row_settings['id'];
    $date = new DateTime($row_settings['date']);
    $sd = $date->format("d");
    $sm = $date->format("m");
    $sy = $date->format("Y");
    $hms = $date->format("G:i:s");
    $hms2 = $date->format("Gis");
}

if($_POST['language'] == 'English')
{
    $_SESSION['language'] = "English";
}
elseif($_POST['language'] == 'Thai')
{
    $_SESSION['language'] = "ไทย";
}

if($_POST['OK'] == 'จ่าย')
{
    if(mysqli_num_rows(mysqli_query($link, "select * from $tmp"))==0)
    {
        //paid 0 mean paid
        goto Paid;
    }
    //Recheck if PT is in pay-list

    $ck1 = mysqli_query($link, "SELECT id FROM pt_to_drug WHERE id='$id'");
    $chrow = mysqli_fetch_array($ck1);
    if(empty($chrow[0]))
    {
        $err[]= "ยังไม่เสร็จสิ้นการ รักษา กรูณา ให้ผู้ป่วยรอ ก่อน และ ให้บริการท่านอื่นก่อน";
        goto CannotPay;
    }

    include '../../libs/payprocess.php';	

    /************ Delete the sessions****************/

    unset($_SESSION['buyprice']);
    unset($_SESSION['olddeb']);
    unset($_SESSION['patdesk']);
    unset($_SESSION['price']);
    unset($_SESSION['paytoday']);
    unset($_SESSION['newdeb']);
    unset($_SESSION['pbac']);
    if($propdcard)
    {
        header("Location: ../docform/opdcard.php ");
    }
    else
    {
        Paid:
        unset($_SESSION['language']);
        unset($_SESSION['patcash']);
        unset($_SESSION['vd']);
        header("Location: thankyou2.php ");
    }
}
CannotPay:

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
   docprint.document.write('<html><head><title><?php if($_SESSION['language']=='English') echo "RECEIPT"; else echo "ใบเสร็จรับเงิน"; ?></title>'); 
   docprint.document.write('<link href="../../jscss/css/recform_print.css" rel="stylesheet" type="text/css" media="print">'); 
   docprint.document.write('</head><body onLoad="self.print()">');          
   docprint.document.write(content_vlue);          
   docprint.document.write('</body></html>'); 
   docprint.document.close(); 
   docprint.focus(); 
}
</script>
<script type='text/javascript'>
$(document).ready(function() 
{ 
    $('input[name=language]').change(function(){
        $('form').submit();
    });
});
</script>
</head><body>
<form method="post" action="recprint.php" name="regForm" id="regForm">
<input type="radio" name="language" value="English" <?php if ($_SESSION['language'] == "English") echo "checked";?>>English<input type="radio" name="language" value="Thai" <?php if ($_SESSION['language'] =="ไทย") echo "checked";?>>ไทย
<br><div align="center"><input type="submit" name="OK" value="จ่าย" onClick="javascript:Clickheretoprint()" ></div>
<br><br>
<table style="width: 100%; text-align: center; margin-left: auto; margin-right: auto;" border="0" cellpadding="3" cellspacing="3">	
<tr><td><div class="myaccount">
    <div class="style3" id="print_content"><div class="page"><div class="subpage"><div class="a">
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
			$df = $row_settings['df'];//auto df
			$dfp = $row_settings['dfp'];//df price

		}
		if($df==0) $dfp =0;
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
/*******************************treatment part*********************************/
//Treatment price
$j = 1;
for($i =1;$i<=4;$i++)
{
$ptin = mysqli_query($link, "select * from $tmp ");
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
            //check for candp /cource and product
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
        echo $pricetr=$price1;
        echo "</td></tr>";
        $allprice = $allprice+$pricetr;
        $j = $j+1;
    }
}
}
/***********************Lab part*************************************************/
    //lab price and pricepolicy
    $ptin = mysqli_query($link, "select * from $tmp ");
    while ($row = mysqli_fetch_array($ptin))
    {
        $alllabprice = $row['licprice']+$row['lcprice'];
        $pricepolicy = $row['pricepolicy'];
        if($row['licprice']) $rmovelab = 0;
        else $rmovelab = 1;
    }
    if($alllabprice)
    {
        echo "<tr><td>".$j."</td><td style='text-align:left;'>";
        if($_SESSION['language']=='English')
        echo "All Labs Price ";
        else
        echo "ค่าตรวจทาง Lab รวมทั้งหมด"; 
        echo "</td><td></td><td></td><td style='text-align:right;'>".$alllabprice;
        echo "</td></tr>";
        $j=$j+1;
    }
    //lab price finish
/****************************DF part*************************************************/
		for($i=1;$i<=14;$i++)
		{
		$ptin = mysqli_query($link, "select * from $tmp ");
		while ($row = mysqli_fetch_array($ptin))
		{
			$idrx = "idrx".$i;
			$rx ="rx".$i;
			$rgx = "rxg".$i;
			$rxuses = "rx".$i."uses";
			$rxv = "rx".$i."v";
			if($row[$idrx] !=0 and $row[$rgx] =='DF' and $df==0)
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
				//$allprice = $allprice+$price1;
				$j = $j+1;
			}
		}
		}
		//auto df
		if($df==1)
		{
			echo "<tr><td>".$j."</td><td style='text-align:left;'>";
            if($_SESSION['language']=='English')
            echo "Doctor Fee";
            else
            echo "ค่าตรวจรักษาโดยแพทย์";
			echo "</td>";
			echo "<td style='text-align:right;'>";
			echo "</td>";
			echo "<td>";
			//echo $row[$rxv];
			echo "</td>";
			echo "<td style='text-align:right;'>";
			echo $price1=$price1+$dfp;
			echo "</td></tr>";
			//$allprice = $allprice+$price1;
			$j = $j+1;
		}
		
/**********************************drug part**********************************************/
		
		for($i=1;$i<=14;$i++)
		{
		$ptin = mysqli_query($link, "select * from $tmp ");
		while ($row = mysqli_fetch_array($ptin))
		{
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
/**********all drug + df + treatment price***********/
        $all_ddft_price = $allprice;

/**************accout system buy for today******************************/

$ptin3 = mysqli_query($link, "select * from `debtors` WHERE ctmid = $id ");
while ($row3 = mysqli_fetch_array($ptin3))
{
    if($row3['price']>0)
    {
        echo "<tr><td>".$j."</td><td style='text-align:left;'>";
        if($_SESSION['language']=='English')
        echo "Previous Debt";
        else
        echo "ยอดค้างชำระครั้งก่อน";
        echo "</td><td>";
        echo $row3['price'];
        echo "</td><td>1</td><td style='text-align:right;'>";
        echo $row3['price'];
        echo "</td></tr>";
    }
}
echo "</table>";
?>
<table class="d" style="background-color: rgb(255, 204, 153); text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="1" cellspacing="1">
    <?php 
    echo "<tr><th>";
    if($_SESSION['language']=='English') echo "Grand Total"; else echo "ยอดรวมสุทธิ";
    echo "</th><th width = 10% style='text-align:right;'>";
    echo $_SESSION['price']."&#3647;";
    echo "</th></tr>";
    if($all_ddft_price>$_SESSION['price'])
    {
    if($_SESSION['language']=='English')
    echo "<tr><th width='82%'> Discount on medicines</th><th width='10%' style='text-align:right;'>";
    else
    echo "<tr><th width='82%'> ส่วนลดค่ายา</th><th width='10%' style='text-align:right;'>";
    echo ($all_ddft_price - $_SESSION['price']);
    echo "&#xE3F;</th></tr>";
    }
    echo "<tr><th>";
    if($_SESSION['language']=='English') echo "Paid"; else echo "จ่าย";
    echo "</th><th width = 10% style='text-align:right;'>";
    echo  $_SESSION['paytoday']."&#xE3F;";
    echo "</th></tr>";
    ?>
</table>
<br>
<?php
if($_SESSION['language']=='English') 
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cashier: ";
else
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;รับเงินโดย: ";

    $staff = mysqli_query($link, "select * from staff WHERE ID = '$_SESSION[staff_id]' ");
    while($row_vl = mysqli_fetch_array($staff))
    {
        $prefix = $row_vl['prefix'];
        $stfname = $row_vl['F_Name'];
        $stlname = $row_vl['L_Name'];
    }
    echo $prefix.' '.$stfname.' '.$stlname;
?>
</div></div></div></div></div></div>
</td></tr>   
</table>
</form>
</body></html>
