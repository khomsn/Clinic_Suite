<?php 
include '../../config/dbc.php';
page_protect();
date_default_timezone_set('Asia/Bangkok');

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
		</td><td>
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
<table style="text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
    <tr><td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">
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
		<?php  //10000001 เงินสด
		$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i = 10000001");
		$cashin = 0;
		while($row = mysqli_fetch_array($result))
		{
			$cashin = $cashin + $row['price'];
		}
		$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o = 10000001");
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
	<?php //10000002-10000249 ธนาคาร
		$result = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 10000002 AND ac_no <= 10000249");
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
	      // 10300000-10699999 สินค้า   
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
	      // 10700001-10999999 วัตถุดิบ 
	      
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
	      // 10700000 raw mat left in account system /ค่าใช้จ่ายถูกคิดไปแล้วในการตัดยอดจากวัตถุดิบ 59999999 ตัดยอด      
	      $result=mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i = 10700000");
	      while ($row = mysqli_fetch_array($result))
	      {
	      $rwi = $rwi+$row['price'];
	      }
	      
	      $result=mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o = 10700000");
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
		//10010000-10099999 วอ.แพทย์
		$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i >= 10010000 AND ac_no_i <= 10099999 ");
		while($row = mysqli_fetch_array($result))
		{
			$dsup = $dsup + $row['price'];
		}	
		//50010000-50099999  ค่าเสื่อมราคา วอ.แพทย์ หักเป็นค่าใช้จ่าย <->10010000-10099999 วอ.แพทย์ 
		$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o >= 10010000 AND ac_no_o <= 10099999 ");
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
		// 10100000-10299999 อ.สำนักงาน
		$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i >= 10100000 AND ac_no_i <= 10299999 ");
		while($row = mysqli_fetch_array($result))
		{
			$osup = $osup + $row['price'];
		}	
		
		// 50100000-50299999 ค่าเสื่อมราคา อ.สำนักงาน หักเป็นค่าใช้จ่าย <->10100000-10299999 อ.สำนักงาน
		$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o >= 10100000 AND ac_no_o <= 10299999 ");
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
		อาคารและที่ดิน และทรัพย์อื่นๆ<br>
		<?php //10000250-10003999 อาคาร และ ที่ดิน สินทรัพย์อื่นๆ 
			$result = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 10000250 AND ac_no <= 10003999");
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
		////10000250-10003999 อาคาร และ ที่ดิน สินทรัพย์อื่นๆ 
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
		//10004000-10009999 ลูกหนี้ทั่วไป
			$result = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 10004000 AND ac_no <= 10009999");
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

		//11000000-19999999 ลูกหนี้ คนไข้ค้างชำระ รวมทั้งหมด
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
<?php //21000001-29999999 เจ้าหนี้ ซื้อ ยา และ อุปกรณ์
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
	//20000001-20199999 เจ้าหนี้ เงินกู้ อื่นๆ
	$result = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 20000001 AND ac_no <= 20199999");
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
		<?php //30000001-34999999 ทุน ผู้ร่วมทุน

			$result = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 30000001 AND ac_no <= 34999999");
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
		<?php //35000001-39999999 ทุน คืน กำไร
			$result = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 35000001 AND ac_no <= 39999999");
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
			//40000002-40000249 ดอกเบี้ยรับธนาคาร 40000250-49999999 รายได้อื่นๆ
			$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o >= 40000002 and ac_no_o <= 49999999 ");
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
		/*	50000000 รายจ่าย
            50000002-50000249 ธนาคาร ค่าธรรมเนียม
            50000250-50002999 รายจ่ายต่างๆ
            50003000-50009999 ว.สำนักงาน ตัดเป็นค่าใช่จ่ายเลย
            50010000-50099999  ค่าเสื่อมราคา วอ.แพทย์ หักเป็นค่าใช้จ่าย 
            50100000-50299999 ค่าเสื่อมราคา อ.สำนักงาน หักเป็นค่าใช้จ่าย 
            51000000-59999998 เงินเดือน จ่าย
            59999999 ตัดยอด ไม่ต้องรวมเป็นรายจ่ายทั่วไปอีก
		*/
			$result = mysqli_query($link, "SELECT * FROM daily_account WHERE (ac_no_i >= 50000000 AND ac_no_i <= 59999998)");
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
</table><br>
		</td>
		<td width="160" valign="top">
			<div class="pos_r_fix_mypage1">
				<h6 class="titlehdr2" align="center">ประเภทบัญชี</h6>
				<?php 
				if (isset($_SESSION['user_id']))
				{
					include 'actmenu.php';
				} 
				?>
			</div>	
		</td>
	</tr>
</table>
</body></html>
