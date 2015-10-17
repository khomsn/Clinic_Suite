<?php 
include '../login/dbc.php';
page_protect();
date_default_timezone_set('Asia/Bangkok');
?>

<!DOCTYPE html>
<html>
<head>
<title>บัญชีและการเงิน</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
	<style>
	p.big {line-height:165%;}
	</style>
<?php include '../libs/popup.php'; ?>
<?php include '../libs/currency.php'; ?>
</head>
<?php 
if(!empty($_SESSION['user_background']))
{
echo "<body style='background-image: url(".$_SESSION['user_background'].");' alink='#000088' link='#006600' vlink='#660000'>";
}
else
{
?>
<body style="background-image: url(../image/ptbg.jpg);" alink="#000088" link="#006600" vlink="#660000">
<?php
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr><td width="160" valign="top">
		<?php 
			/*********************** MYACCOUNT MENU ****************************
			This code shows my account menu only to logged in users. 
			Copy this code till END and place it in a new html or php where
			you want to show myaccount options. This is only visible to logged in users
			*******************************************************************/
			if (isset($_SESSION['user_id']))
			{
				include 'accountmenu.php';
			} 
		/*******************************END**************************/
		?>
			  <p>&nbsp; </p>
			  <p>&nbsp;</p>
			  <p>&nbsp;</p>
			  <p>&nbsp;</p>
		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
<!--menu-->
			<h3 class="titlehdr">งบดุล ณ วันที่ <?php echo date("d"); ?> <?php $m = date("m");
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
			}?> พ.ศ. <?php echo date("Y")+543;?></h3>
<table style="text-align: center; margin-left: auto; margin-right: auto; width: 700px; height: 413px;" border="1" cellpadding="2" cellspacing="2">
<tbody><tr><td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">
   <table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2">
	<tr>
		<th>สินทรัพย์
		</th>
		<th width=90px>บาท
		</th>
	</tr>
	<tr>
		<td style="text-align: left; vertical-align: top;">
		เงินสด
		</td>
		<td style="text-align: right;">
		<?php  //เงินสด 1001
		$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i = 1001");
		$cashin = 0;
		while($row = mysqli_fetch_array($result))
		{
			$cashin = $cashin + $row['price'];
		}
		$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o = 1001");
		$cashout = 0;
		while($row = mysqli_fetch_array($result))
		{
			$cashout = $cashout + $row['price'];
		}
		$cash = $cashin - $cashout;
		echo "<span class=currency>".$cash."</span>";
		?>
  </td></tr></table>
  <table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2">
	<tr><td style="text-align: left; vertical-align: top;">เงินฝากธนาคาร</td><td width = 90px></td></tr>
	<?php //1002-1020 ธนาคาร
		$result = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no > 1001 AND ac_no <= 1020");
		$i = 1;
		while($row =mysqli_fetch_array($result))
		{
			$bank[$i] = $row['ac_no'];
			$bankname[$i] = "&nbsp;&nbsp;&nbsp;--".$row['name'];
			$i = $i + 1;
		}
		$bmax = $i-1;
		
		for($i = 1; $i<=$bmax; $i++)
		{ 
			$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i = '$bank[$i]'");
			$bin = 0;
			while($row = mysqli_fetch_array($result))
			{
				$bin = $bin + $row['price'];
			}
			$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o = '$bank[$i]'");
			$bout = 0;
			while($row = mysqli_fetch_array($result))
			{
				$bout = $bout + $row['price'];
			}
			$bank[$i] = $bin - $bout;
			echo "<tr><td style='text-align: left; vertical-align: top;'>";
			echo $bankname[$i];
			echo "</td><td style='text-align: right; vertical-align: center;'>";
			echo "<span class=currency>".$bank[$i]."</span>";
			echo "</td></tr>";
			$allbank = $allbank + $bank[$i];
		}
	?>
  </table>
  <table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2">
	<tr><td style='text-align: left; vertical-align: top;'>สินค้า และ บริการ</td><td  width = 90px style="text-align: right;">
	<?php
	      // 100000-179999 สินค้า	   180000-189999 วัตถุดิบ   
	      $query = "SELECT * FROM drug_id"; 
	      $result = mysqli_query($link, $query) or die(mysqli_error($link));
	      while($row =mysqli_fetch_array($result))
	      {
		      if($maxid < $row['id']) $maxid = $row['id'];
	      }
	      for($i=1;$i<=$maxid;$i++)
	      {
		      $drugtable = "drug_".$i;
		      $result = mysqli_query($link, "SELECT * FROM $drugtable ");
		      if ($result != '0')
		      {
			      while($row =mysqli_fetch_array($result))
			      {
				      $dprice = $dprice + ($row['price']/$row['volume']) * ($row['volume'] - $row['customer']);//left
				      $baseprice = $baseprice  + $row['customer']*$row['price']/$row['volume'];//used
			      }
		      }	
	      }
	      if($dprice!=0)
	      echo "<span class=currency>".round($dprice,2)."</span>";
	      ?>
	      </td>
      </tr>
      <tr><td style="text-align: left; vertical-align: top;">
	      วัตถุดิบ
	      </td>
	      <td style="text-align: right;">
	      <?php
	      // วัตถุดิบ 180000-189999 วัตถุดิบ 
	      
	      $query = "SELECT * FROM rawmat"; 
	      $result = mysqli_query($link, $query) or die(mysqli_error($link));
	      while($row =mysqli_fetch_array($result))
	      {
		      if($rmmaxid < $row['id']) $rmmaxid = $row['id'];
	      }
	      for($i=1;$i<=$rmmaxid;$i++)
	      {
		      $rawtable = "rawmat_".$i;
		      $result = mysqli_query($link, "SELECT * FROM $rawtable ");
		      if ($result != '0')
		      {
			      while($row =mysqli_fetch_array($result))
			      {
				      $rawprice = $rawprice + ($row['price']/$row['volume']) * ($row['volume'] - $row['customer']);//left
				      $baseprice = $baseprice  + $row['customer']*$row['price']/$row['volume'];//used
			      }
		      }	
	      }
	      // 180000 raw mat left in account system /ค่าใช้จ่ายถูกคิดไปแล้วในการตัดยอดจากวัตถุดิบ	      
	      $result=mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i = 180000");
	      while ($row = mysqli_fetch_array($result))
	      {
	      $rwi = $rwi+$row['price'];
	      }
	      
	      $result=mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o = 180000");
	      while ($row = mysqli_fetch_array($result))
	      {
	      $rwi = $rwi-$row['price'];
	      }
	      
	      $rawprice = $rawprice+$rwi;
	      if($rawprice!=0)
	      echo "<span class=currency>".round($rawprice,2)."</span>";
	      ?>
	      </td>
      </tr>
  </table>
  <table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2">
        <tr><td style="text-align: left; vertical-align: top;">วัสดุ และ อุปกรณ์</td><td width=90px style="text-align: right;"></tr>
        <tr><td style="text-align: left; vertical-align: top;">&nbsp;&nbsp;&nbsp;-- การแพทย์</td><td width=90px style="text-align: right;">
		<?php
		//วัสดุ และ อุปกรณ์ -การแพทย 10000-15000 วอ.แพทย์
		$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i >= 10000 AND ac_no_i <= 15000 ");
		while($row = mysqli_fetch_array($result))
		{
			$dsup = $dsup + $row['price'];
		}	
		//วัสดุ และ อุปกรณ์ -การแพทย 1100-1199 วอ.แพทย์ ค่าเสื่อมราคา
		$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o >= 10000 AND ac_no_o <= 15000 ");
		while($row = mysqli_fetch_array($result))
		{
			$dsup = $dsup - $row['price'];
		}
		if($dsup!=0)
		echo "<span class=currency>".$dsup."</span>";
		echo "</td></tr>";
		echo "<tr><td style='text-align: left; vertical-align: top;'>";
		echo "&nbsp;&nbsp;&nbsp;-- สำนักงาน";
		echo "</td><td style='text-align: right;'>";
		// อุปกรณ์ -- 15001-19999 อ.สำนักงาน
		$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i >= 15001 AND ac_no_i <= 19999 ");
		while($row = mysqli_fetch_array($result))
		{
			$osup = $osup + $row['price'];
		}	
		
		// อุปกรณ์ -- 1700-1799 อ.สำนักงาน
		$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o >= 15001 AND ac_no_o <= 19999 ");
		while($row = mysqli_fetch_array($result))
		{
			$osup = $osup - $row['price'];
		}
		if($osup!=0)
		echo "<span class=currency>".$osup."</span>";
		?>
		</p>
		</td>
	</tr>
	<tr><td style="text-align: left; vertical-align: top;">
		อาคารและที่ดิน<br>
		<?php //1021-1030 อาคาร และ ที่ดิน 
			$result = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 1021 AND ac_no <= 1030");
			$i = 1;
			while($row =mysqli_fetch_array($result))
			{
				$land[$i] = $row['ac_no'];
				echo "&nbsp;&nbsp;&nbsp;--";
				echo $row['name'];
				echo "<br>";
				$i = $i + 1;
			}
			$lmax = $i-1;
		?>
		</td>
		<td  style="vertical-align: top; text-align: right;">
		&nbsp;
		<p class="big">
		<?php
		////อาคาร";1021-1030 อาคาร และ ที่ดิน 
		for($i = 1; $i<=$lmax; $i++)
		{ 
			$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i = '$land[$i]'");
			$lin = 0;
			while($row = mysqli_fetch_array($result))
			{
				$lin = $lin + $row['price'];
			}
			$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o = '$land[$i]'");
			$lout = 0;
			while($row = mysqli_fetch_array($result))
			{
				$lout = $lout + $row['price'];
			}
			$land[$i] = $lin - $lout;
			if($land[$i]!=0)
			echo "<span class=currency>".$land[$i]."</span>";
			echo "<br>";
			$building = $building + $land[$i];
		}
		?></p>
		</td>
	</tr>
	<tr><td style="text-align: left; vertical-align: top;">
		ลูกหนี้<br>
		<?php
		//1500-1999 ลูกหนี้ทั่วไป
			$result = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 1500 AND ac_no <= 1999");
			$i = 1; 
			while($row = mysqli_fetch_array($result))
			{
				$cname = $row['name'];
				$dbtacno[$i] = $row['ac_no'];
				$result2 = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o = '$dbtacno[$i]'");
				while($row2 = mysqli_fetch_array($result2))
				{
					$dbtout[$i] = $dbtout[$i] + $row2['price'];
				}
				$result3 = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i = '$dbtacno[$i]'");
				while($row3 = mysqli_fetch_array($result3))
				{
					$dbtin[$i] = $dbtin[$i] + $row3['price'];
				}
				$debti[$i] = $dbtin[$i] - $dbtout[$i];
				$debtor = $debtor + $debti[$i];
				if($debti[$i])
				{
				echo "&nbsp;&nbsp;&nbsp;--";
				echo $cname;
				echo "<br>";
				}
				$drtori = $i;
				$i = $i+1;
			}

		//ลูกหนี้ ลูกค้า รวมราคา
		$result = mysqli_query($link, "SELECT * FROM debtors ");
		while($row = mysqli_fetch_array($result))
		{
			$dec = $dec + $row['price'];
		}
		//รวมหนี้สิน
		$debtor = $debtor + $dec;
		//echo $debtor;
		if($dec) echo "&nbsp;&nbsp;&nbsp;--ลูกหนี้การค้า";
		?>
		</td>
		<td style=" text-align: right;"><br>
		<p class="big">
		<?php 
			for($i=1;$i<=$drtori;$i++)
			{
			if($debti[$i])
			{
			echo "<span class=currency>".$debti[$i]."</span>";
			echo "<br>";
			}
			}
		if($dec!=0)	
		echo "<span class=currency>".$dec."</span>";
		?>
		</p>
		</td>
	</tr>
	<tr><td style="text-align: left; vertical-align: top;">รวม สินทรัพย์สุทธิ</td>
		<td style="text-align: right;">
		<?php 
			$debit = $cash + $allbank + $dprice  + $rawprice + $dsup + $osup + $building + $debtor;
			if($debit!=0)
			echo "<span class=currency>".round($debit,2)."</span>";
		?>
		</td>
	</tr>	
</table>
</td>
<td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">
<table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2">
	<tr>
		<th>หนี้สิน และ ทุน
		</th>
		<th width=90px>บาท
		</th>
	</tr>
</table>
<table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2">
<tr><td style="text-align: left; vertical-align: top;">เจ้าหนี้</td><td width=90px></td><tr>
<?php //2101-2999 เจ้าหนี้ ซื้อ ยา และ อุปกรณ์
	$result = mysqli_query($link, "SELECT * FROM supplier");
	//$i = 1; 
	$smax = 0;
	while($row = mysqli_fetch_array($result))
	{
		$i = $row['id'];
		$spname[$i] = $row['name'];
		$result2 = mysqli_query($link, "SELECT * FROM sp_$i WHERE payment = 0");
		if($result2 != 0)
		{
		while($row2 = mysqli_fetch_array($result2))
		{
			$spprice[$i] = $spprice[$i] + $row2['price'];
		}
		}
		if ($spprice[$i] != 0)
		{
			echo "<tr><td style='text-align: left; vertical-align: top;'>";
			echo "&nbsp;&nbsp;&nbsp;--";
			echo $spname[$i];	
			echo "</td><td width=90px style='text-align: right;'>";
			echo "<span class=currency>".$spprice[$i]."</span>"; 
			$tcred = $tcred + $spprice[$i];
			echo "</td></tr>";
		}
	}
	//2000-2100 เจ้าหนี้ อื่นๆ
	$result = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 2000 AND ac_no <= 2100");
	$i = 1; 
	while($row = mysqli_fetch_array($result))
	{
		$cname = $row['name'];
		$crtacno[$i] = $row['ac_no'];
		$result2 = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o = '$crtacno[$i]'");
		while($row2 = mysqli_fetch_array($result2))
		{
			$crtin[$i] = $crtin[$i] + $row2['price'];
		}
		$result3 = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i = '$crtacno[$i]'");
		while($row3 = mysqli_fetch_array($result3))
		{
			$crtout[$i] = $crtout[$i] + $row3['price'];
		}
		$crti[$i] = $crtin[$i] - $crtout[$i];
		$crtor = $crtor + $crtin[$i] - $crtout[$i];
		if($crti[$i])
		{
			echo "<tr><td style='text-align: left; vertical-align: top;'>";
			echo "&nbsp;&nbsp;&nbsp;--";
			echo $cname;
			echo "</td><td width=90px style='text-align: right;'>";
			echo "<span class=currency>".$crti[$i]."</span>"; 
			echo "</td></tr>";
		}
		$i = $i+1;
	}
?>
</table>
<table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2">
<tr><td style="text-align: left; vertical-align: top;">ทุน<br>
		<?php //3000 ทุน	3001-3499 ทุน ผู้ร่วมทุน 3500-3999 ทุน คืน กำไร

			$result = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 3000 AND ac_no <= 3499");
			$i = 1; 
			$cmax = 0;
			while($row = mysqli_fetch_array($result))
			{
				$cname = $row['name'];
				$cacno[$i] = $row['ac_no'];
				$result2 = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o = '$cacno[$i]'");
				while($row2 = mysqli_fetch_array($result2))
				{
					$cpin[$i] = $cpin[$i] + $row2['price'];
				}
				$result3 = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i = '$cacno[$i]'");
				while($row3 = mysqli_fetch_array($result3))
				{
					$cpout[$i] = $cpout[$i] + $row3['price'];
				}
				echo "&nbsp;&nbsp;&nbsp;--";
				echo $cname;
				echo "<br>";
				$i = $i+1;
			}
			$cmax = $i - 1 ;
		?>
	</td>
	<td  style="text-align: right; vertical-align: top;"  width=90px>
	<br>
	<p class="big">
		<?php 
			//ทุน 
			for($i=1; $i<=$cmax; $i++)
			{
				$cvolume[$i] = $cpin[$i]-$cpout[$i];
				if($cvolume[$i])
				echo "<span class=currency>".$cvolume[$i]."</span>";
				$capital = $capital + $cvolume[$i]; 
				echo "<br>";
			}
		?></p>
	</td></tr>
	<tr><td style="text-align: left; vertical-align: top;">
		หัก<br>
		<table border="0" cellpadding="1" cellspacing="1" width = 100%>
		<?php //3500-3999 ทุน คืน กำไร
			$result = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 3500 AND ac_no <= 3999");
			$i = 1; 
			while($row = mysqli_fetch_array($result))
			{
				$cname = $row['name'];
				$cacno[$i] = $row['ac_no'];
				$result2 = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i = '$cacno[$i]'");
				while($row2 = mysqli_fetch_array($result2))
				{
					$rtp[$i] = $rtp[$i] + $row2['price'];
				}
				if($rtp[$i]){
				echo "<tr><td style='text-align: left; vertical-align: top;' >";
				echo "&nbsp;&nbsp;&nbsp;--";
				echo $cname;
				echo "</td><td style='text-align: right;' width = 30%>";
				echo "<span class=currency>".$rtp[$i]."</span>";
				echo "</td></tr>";
				}
				$rtpall = $rtpall + $rtp[$i];
				$i = $i+1;
			}
		?>	
		</table>
		</td>
		<td style="text-align: right;">
		<?php 
			if($rtpall)
			{
			echo "(-";
			echo "<span class=currency>".$rtpall."</span>";
			echo ")";
			}
		?></td>
	</tr>
	
	<tr><td style="text-align: left; vertical-align: top;">

	    <table border="0" cellpadding="1" cellspacing="1" width = 100%>
	    <tr><td>กำไรจากการขาย</td>
		<td style="text-align: right;" width = 30%>
		<?php 
			//รายได้จากการขาย
			$result = mysqli_query($link, "SELECT * FROM sell_account ");
			while($row = mysqli_fetch_array($result))
			{
				$allsell = $allsell + $row['total'];
			}
			$profit = $allsell - $baseprice;
			if($profit)
			echo "<span class=currency>".round($profit,2)."</span>";
			
		?>
		</td>
	      </tr>
	      <tr><td>รวม</td><td></td></tr>
	      <tr><td>รายได้อื่นๆ</td>
		<td style="text-align: right;">
		<?php 
			//4001-4999 รายได้อื่นๆ
			$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o > 4000 and ac_no_o < 5000 ");
			while($row = mysqli_fetch_array($result))
			{
				$allget = $allget  + $row['price'];
			}
			if($allget)
			echo "<span class=currency>".$allget."</span>";
		?>
		</td>
	      </tr>
	      <tr><td>หัก</td><td></td></tr>
	      <tr><td>ค่าใช่จ่ายในการดำเนินงาน</td>
		<td style="text-align: right;">
		<?php 
			//ค่าใช้จ่ายในการดำเนินงาน 
		/*	5000 รายจ่าย
			5001-5998 ว.สำนักงาน ตัดเป็นค่าใช่จ่ายเลย
			50000-55000  ค่าเสื่อมราคา วอ.แพทย์ หักเป็นค่าใช้จ่าย 
			55001-59999 ค่าเสื่อมราคา อ.สำนักงาน หักเป็นค่าใช้จ่าย 
			5999 ตัดยอด ได้ถูกคิดเป้นราคาซื้อสินค้าที่ใช้ไปแล้ว ไม่ต้องรวมเป็นรายจ่ายทั่วไปอีก
		*/
			$result = mysqli_query($link, "SELECT * FROM daily_account WHERE (ac_no_i >= 5000 AND ac_no_i < 5999) OR (ac_no_i >= 50000 AND ac_no_i <= 59999) ");
			while($row = mysqli_fetch_array($result))
			{
				$allpay = $allpay  + $row['price'];
			}
			if($allpay)
			{
			echo "(-";
			echo "<span class=currency>".round($allpay,2)."</span>";
			echo")";
			}
			//total profit
			$t_profit = $profit + $allget - $allpay;
		?>
		</td>
	      </tr>
	    </table>
		
		กำไร - ขาดทุนสุทธิ
	</td>
	<td style="text-align: right; vertical-align: bottom;">
	<?php 
			//กำไร สุทธิ
			if($t_profit)
			echo "<span class=currency>".round($t_profit,2)."</span>";
		?>	
	</td>
	</tr>
	<tr><td style="text-align: left; vertical-align: top;">รวม หนี้สิน และ ทุน สุทธิ</td>
		<td style="text-align: right;">
		<?php 
			$credit = $t_profit + $capital + $tcred + $crtor -$rtpall;
			echo "<span class=currency>".round($credit,2)."</span>";
		?>
		</td>
	</tr>	
</table>
		</td>
	</tr>
</tbody>
</table>
				<br>
<!--menu end-->
		</td>
		<td width="160" valign="top">
			<div class="mypage1">
				<h6 class="titlehdr2" align="center">ประเภทบัญชี</h6>
				<?php 
				/*********************** MYACCOUNT MENU ****************************
				This code shows my account menu only to logged in users. 
				Copy this code till END and place it in a new html or php where
				you want to show myaccount options. This is only visible to logged in users
				*******************************************************************/
				if (isset($_SESSION['user_id']))
				{
					include 'actmenu.php';
				} 
				/*******************************END**************************/
				?>
			</div>	
		</td>
	</tr>
</table>
<!--end menu-->
</body></html>