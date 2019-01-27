<?php 
include '../../config/dbc.php';

page_protect();

include '../../libs/progdate.php';

$cpd = mysqli_query($link, "select name from stcpdrug");
$t=1;
while($stc=mysqli_fetch_array($cpd))
{
    $stcp[$t]=$stc['name'];
    $t = $t+1;
}
$coursepd=0;
$checkuprdp=0;

$id = $_SESSION['patcash'];
$Patient_id = $id;
include '../../libs/opdxconnection.php';

$ctmid = $id;
$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
$tmp = "tmp_".$id;
$today = date("Y-m-d");
$pin = mysqli_query($linkopdx, "select * from $pttable where date ='$today' ");
while ($row_settings = mysqli_fetch_array($pin))
	{
		$rid = $row_settings['id'];
	}
if($_POST['codp'])
{
$coursepd = $_POST['codp'];
}

$title = "::Cashier + จ่ายยา::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" href=\"../../jscss/css/table_alt_color.css\">";
echo "</head><body>";

?>
<form method="post" action="pay.php" name="regForm" id="regForm">
<div style="text-align: center;">
<h2 class="titlehdr"><div style="background-color:rgba(0,255,0,0.5); display:inline-block;">ยาและผลิตภัณฑ์ ณ วันที่ <?php echo $sd; ?> <?php $m = $sm;
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
}?> พ.ศ. <?php echo $bsy; //date("Y")+543;?></div></h2>
<h3><div style="background-color:rgba(0,255,0,0.5); display:inline-block;">ชื่อ: &nbsp; 
<?php
    while ($row_settings = mysqli_fetch_array($ptin))
    {
	echo $row_settings['fname'];
	echo "&nbsp; &nbsp; &nbsp;"; 
	echo $row_settings['lname'];
    }
    //find discount value for this patient_id
    $disc = mysqli_query($link, "select * from discount WHERE ctmid = $ctmid ");
    while( $rowd = mysqli_fetch_array($disc))
    {
	    echo "   &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;   มีสิทธิส่วนลด ";
	    echo $rowd['percent'];
	    echo " %";
	    $perdc = $rowd['percent']/100;
    }	
    echo "</div></h3>";
?>
</div>
<?php 
   echo "<div style='text-align: center;'><div style='background-color:rgba(0,255,0,0.5); display:inline-block;'>Treatment: ยาและผลิตภัณฑ์: ทั้งหมด</div></div>";
?>
<table  class='TFtable' style="background-color: rgb(255, 204, 153); width: 80%; text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="1" cellspacing="1">
<tr><th width = 10 >No</th><th >ชื่อ</th><th width = 75px>ราคา</th><th width = 35px>Vol</th><th width = 75px>รวม</th></tr>
	<?php
	//lab price and pricepolicy
	$ptin = mysqli_query($link, "select * from $tmp ");
	while ($row = mysqli_fetch_array($ptin))
	{
	  $alllabprice = $row['licprice']+$row['lcprice'];
	  $pricepolicy = $row['pricepolicy'];
	  if($row['licprice']) $rmovelab = 0;
	  else $rmovelab = 1;
	}
	//lab price finish
  //Treatment price
  
  include '../../libs/trpricecheck.php';
  
  $j = 1;
  for($i =1;$i<=4;$i++)
  {
	$ptin = mysqli_query($link, "select * from $tmp ");
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
			//check id if match jump
			for($s=1;$s<=$t;$s++)
			{
			  if($did ==  $tr_drugid[$s]) goto jpoint1;
			}
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
            if($did ==  $tr_drugid[$s])
            {
                if($row[$trv]>=$first1[$s]) 
                $price1 = ($row[$trv]-$first1[$s]+1)*$f1price[$s];
                if($row[$trv]>=$sec2[$s]) 
                $price1 = ($row[$trv]-$sec2[$s]+1)*$sec2price[$s]+($sec2[$s]-$first1[$s])*$f1price[$s];
                if($row[$trv]>=$tri3[$s]) 
                $price1 = ($row[$trv]-$tri3[$s]+1)*$tri3price[$s]+($tri3[$s]-$sec2[$s])*$sec2price[$s]+($sec2[$s]-$first1[$s])*$f1price[$s];
            }
			echo $price1;
			echo "</td></tr>";
			$allprice = $allprice+$price1;
			$j = $j+1;
		}
	}
  }
  //drug price
  for($i=1;$i<=10;$i++)
  {
	$ptin = mysqli_query($link, "select * from $tmp ");
	while ($row = mysqli_fetch_array($ptin))
	{
	//check for course and program check up lab
		$checkup = $row['prolab'];
		$course = $row['course'];
	//
		$idrx = "idrx".$i;
		$rx ="rx".$i;
		$rgx = "rxg".$i;
		$rxuses = "rx".$i."uses";
		$rxv = "rx".$i."v";
		if($row[$idrx] !=0)
		{
			echo "<tr><td>".$j."</td><td style='text-align:left;'>";
			echo $row[$rx].'('.$row[$rgx].')';
			echo "</td>";
			echo "<td style='text-align:right;'>";
			$did = $row[$idrx];
			$ptin2 = mysqli_query($link, "select * from drug_id WHERE id = $did ");
			if($ptin2 !=0)
			{
			while ($row2 = mysqli_fetch_array($ptin2))
			{
			//check for candp
				$candp = $row2['candp'];
				if($candp == 2)
				{
				 $checkuprdp = $checkuprdp + floor($coursepd*$row2['sellprice']*$row[$rxv]/100);
				}
			//
				echo $row2['sellprice'];
				//ต้นทุนยา
				//get buy price per unit
				$dtb = "drug_".$did;
				$dbp = mysqli_query($link, "select * from $dtb");
				while($dtb=mysqli_fetch_array($dbp))
				{
                    if($dtb['volume']!=$dtb['customer'])
                    {
                        $buyprice = $dtb['price']/$dtb['volume'];
                        goto next1;
                    }
				}
				next1:
				$TT_Ya = $buyprice * $row[$rxv];
                if($row2['typen']=="$stcp[1]" OR $row2['stcp']==1)
                {
                   $stdcount = $stdcount+ceil($TT_Ya);
                }
				
				$dcount = floor($row2['sellprice'] * $row[$rxv] * $row2['disct'] * $perdc);
				if($dcount>$TT_Ya)
				{
				 $dcount=$TT_Ya;
				}
				$price1 = $row2['sellprice'] * $row[$rxv] - $dcount;
				$discount =$discount + $dcount;
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
			$TT_Yall = $TT_Yall+$TT_Ya;
			$j = $j+1;
		}
	}
	}
	//คิดราคายา
	//ราคาขั้นต่ำ							
	$para1=mysqli_query($link, "select * from parameter WHERE ID = 1");
	while ($para = mysqli_fetch_array($para1))
	{
	$normprice = $para['normprice']; //ราคาขั้นต่ำปกติ
	$fup = $para['fup'];//ราคาติดตามอาการขั้นต่ำ
	$treatmp = $para['tmp'];//ราคาทำหัตการขั้นต่ำ
	$maxcp = $para['maxcp']; //ต้นทุนยาที่ลดได้สูงสุด
	$staffp = $para['Staffp'];//ราคาพนักงานสูงสุด
	$df = $para['df'];//auto df
	$dfp = $para['dfp'];//df price
	
	//echo $maxcp;
	}
	if ($df==0) $dfp =0;
	
	
// Price policy มีกำหนดดังนี้ 1 = ราคาพนักงาน $staffp ,2 = ราคาปกติ $normprice, 3 = ราคาหัตการ $treatmp, 4 = ราคาติดตามอาการ $fup,
if($pricepolicy ==2) //Treatment+drug = $allprice, Lab = $alllabprice
{
  if($TT_Yall>$maxcp) $TT_Yall = $maxcp; //ต้นทุนยาถ้าเกิน
  
  $allprice = $allprice + $dfp - $TT_Yall; //ราคาหลังลดค่ายาแล้ว
  
  if($allprice<$normprice) $allprice = $normprice; //ราคาปกติต่ำสุด
  
  $discount = $discount + $TT_Yall;
}
elseif($pricepolicy ==3)
{
  if($TT_Yall>$maxcp) $TT_Yall = $maxcp; //ต้นทุนยาถ้าเกิน
  
  $allprice = $allprice + $dfp - $TT_Yall; //ราคาหลังลดค่ายาแล้ว
  
  if($allprice<$treatmp) $allprice = $treatmp; //ราคาหัตการ
  $discount = $discount + $TT_Yall;
}
elseif($pricepolicy ==4)
{
  if($TT_Yall>$maxcp) $TT_Yall = $maxcp; //ต้นทุนยาถ้าเกิน
  
  $allprice = $allprice + $dfp - $TT_Yall; //ราคาหลังลดค่ายาแล้ว
  
  if($allprice<$fup) $allprice = $fup; //ราคาติดตามอาการ
  $discount = $discount + $TT_Yall;
}

//รวมราคา LAB ด้วย
$allprice = $allprice + $alllabprice;

if($pricepolicy ==1)//staff
{
  $allprice1 = $allprice;

  if($TT_Yall>$maxcp) $TT_Yall = $maxcp; //ต้นทุนยาสูงสุดลดได้ = maxcp 
  
  $allprice = $allprice - $TT_Yall - $alllabprice; //ราคาหลังลดค่ายาแล้ว ไม่คิดค่าตรวจ Lab
  
  if($allprice<$staffp)
  { 
    $discount = $allprice1;
    $allprice = 0; //ราคาปกติต่ำสุด
    if($stdcount!=0)
    {
        $discount = $allprice1 - $stdcount;
        $allprice = $stdcount; //ราคาปกติต่ำสุด
    }
  }
  else
  {
    $allprice = $allprice - $staffp; //จ่ายส่วนต่าง
    $discount = $discount + $TT_Yall + $alllabprice +$staffp;
  }
}

if($pricepolicy ==9)//สงฆ์
{
  if ($TT_Yall>($allprice-$alllabprice)/2) 
  {
    if($allprice-$alllabprice <0)
    {
    $discount = $TT_Yall;
    $allprice = $allprice + $TT_Yall;
    }
    else
    {
    $discount = $allprice - $TT_Yall -$alllabprice ;
    $allprice = $TT_Yall + $alllabprice;
    }
  }
  if(($allprice-$alllabprice)/2 > $TT_Yall)
  {
    $discount = ($allprice-$alllabprice)/2;
    $allprice = $discount+$alllabprice;
  }
}
	//ส่งต่อราคายาและค่ำต่างๆทั้งหมดในวันนี้
	
	//accout system buy for today มี Treatment+drug = $allprice, Lab = $alllabprice
	$_SESSION['buyprice'] = ceil($allprice);
	
	//ยอดหนี้ค้างชำระที่ต้องจ่าย
	$ptin3 = mysqli_query($link, "select * from `debtors` WHERE ctmid = $id ");
	while ($row3 = mysqli_fetch_array($ptin3))
	{
		if($row3['price']>0)
		{
			echo "<tr><td>".$j."</td><td style='text-align:left;'>";
			echo "ยอดค้างชำระครั้งก่อน";
			echo "</td><td>";
			echo $row3['price'];
			echo "</td><td>1</td><td style='text-align:right;'>";
			echo $row3['price'];
			//for account system
			$_SESSION['olddeb'] = $row3['price'];
			$allprice = $allprice+$row3['price'];
			echo "</td></tr>";
		}
	}	
echo "</table>";
?>
<table style="background-color: rgb(255, 204, 153); width: 80%; text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="1" cellspacing="1">
<?php if($alllabprice>0)
      { ?>
        <tr>
		<th > ราคา Lab </th>
		<th></th>
		<th width = 75px style='text-align:right;'><?php echo $alllabprice;  
		?></th>
	</tr>
<?php 
}

if ($df == 1)
{
?>
	<tr>
		<th > ค่าแพทย์ตรวจและบริการทางคลินิก </th><th></th>
		<th width = 75px style='text-align:right;'><?php echo $dfp;  
		?></th>
	</tr>
<?php 
}
if($df==0) $dfp = 0;
?>
	<tr>
		<th > ยอดรวม </th><th></th>
		<th width = 75px style='text-align:right;'><?php echo $allprice2=floor($allprice+$discount);  
		?></th>
	</tr>
<?php 
$discount = floor($discount);
if($discount>0)
      { ?>
	<tr>
		<th > ส่วนลดค่าบริการ </th><th></th>
		<th width = 75px style='text-align:right;'><?php echo $discount;  
		?></th>
	</tr>
<form action="pay.php" method="POST">	
<?php }
if($course)
{
?>
	<tr>
		<th > ส่วนลด Program Check Up</th><th></th>

		<th width = 75px style='text-align:right;'><?php echo $discount;  
		?></th>
	</tr>
<?php
}
if($checkup)
{
?>
	<tr>
		<th > ส่วนลด Program Check Up</th><th><select name="codp" onchange="this.form.submit()">
  <option <?php if($coursepd==0) echo "selected";?> value="0">0%</option>
  <option <?php if($coursepd==5) echo "selected";?> value="5">5%</option>
  <option <?php if($coursepd==10) echo "selected";?> value="10">10%</option>
  <option <?php if($coursepd==15) echo "selected";?> value="15">15%</option>
  <option <?php if($coursepd==20) echo "selected";?> value="20">20%</option>
  <option <?php if($coursepd==35) echo "selected";?> value="35">35%</option>
</select>
</form>
</th>
		<th width = 75px style='text-align:right;'><?php echo $checkuprdp;  
		?></th>
	</tr>
<?php
}
?>	
	<tr>
		<th > ยอดรวมสุทธิ </th><th></th>
		<th width = 75px style='text-align:right;'><?php echo $_SESSION['price'] = ceil($allprice-$checkuprdp);  
		// ส่งต่อราคา
		if($_SESSION['price']>=0) $_SESSION['pricecheck'] = 1;
		elseif( $_SESSION['price']=="") $_SESSION['pricecheck'] = "";
		//$_SESSION['price'] = ceil($allprice-$checkuprdp);
		?></th>
	</tr>
</table>
</form><br>
</body></html>
