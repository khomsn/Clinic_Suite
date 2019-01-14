<?php 
include '../../config/dbc.php';
page_protect();
date_default_timezone_set('Asia/Bangkok');
$title = "::บัญชีและการเงิน::";
include '../../main/header.php';
include '../../libs/currency.php';
include '../../libs/popup.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
include '../../main/bodyheader.php';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="160" valign="top"><div class="pos_l_fix">
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
		?></div>
		</td><td>
			<h3 class="titlehdr">กำไร ขาดทุน คงเหลือ สุทธิ หสังหักส่วนแบ่งกำไร ณ วันที่ <?php echo date("d"); ?> <?php $m = date("m");
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
	      // 10700000-10999999 วัตถุดิบ 
	      
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
?>
<table style="text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
<tr><td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">
<table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2">
	<tr><td style="text-align: left; vertical-align: top;">
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
		</td><td style="text-align: right;">
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
		</td></tr>
	    <tr><td>รวม</td><td></td></tr>
	    <tr><td>รายได้อื่นๆ</td>
		<td style="text-align: right;">
		<?php 
			//40000002-40000249 ดอกเบี้ยรับธนาคาร  40000250-49999999 รายได้อื่นๆ
			$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_o >= 40000002 and ac_no_o <= 49999999 ");
			while($row = mysqli_fetch_array($result))
			{
				$allget = $allget  + $row['price'];
			}
			if($allget)
			echo "<span class=currency>".$allget."</span>";
		?>
		</td></tr>
        <tr><td>หัก</td><td></td></tr>
	    <tr><td>ค่าใช่จ่ายในการดำเนินงาน</td>
		<td style="text-align: right;">
		<?php 
			//ค่าใช้จ่ายในการดำเนินงาน 
		/*50000000 รายจ่าย
            50000002-50000249 ธนาคาร ค่าธรรมเนียม
            50000250-50002999 รายจ่ายต่างๆ
            50003000-50009999 ว.สำนักงาน ตัดเป็นค่าใช่จ่ายเลย
            50010000-50099999  ค่าเสื่อมราคา วอ.แพทย์ หักเป็นค่าใช้จ่าย 
            50100000-50299999 ค่าเสื่อมราคา อ.สำนักงาน หักเป็นค่าใช้จ่าย 
            51000000-59999998 เงินเดือน จ่าย
            59999999 ตัดยอด ได้ถูกคิดเป้นราคาซื้อสินค้าที่ใช้ไปแล้ว ไม่ต้องรวมเป็นรายจ่ายทั่วไปอีก
		*/
			$result = mysqli_query($link, "SELECT * FROM daily_account WHERE (ac_no_i >= 50000000 AND ac_no_i < 59999999)");
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
		</td></tr></table>
		กำไร - ขาดทุนสุทธิ
	</td><td style="text-align: right; vertical-align: bottom;">
	<?php 
			//กำไร สุทธิ
			if($t_profit)
			echo "<span class=currency>".round($t_profit,2)."</span>";
		?>	
	</td></tr>
	<tr><td style="text-align: left; vertical-align: top;">กำไร - ขาดทุนสุทธิ หลังหัก ส่วนแบ่งกำไร</td>
		<td style="text-align: right;">
		<?php 
			$credit = $t_profit + $capital + $tcred + $crtor -$rtpall;
			echo "<span class=currency>".round($credit,2)."</span>";
		?>
	</td></tr></table>
</td></tr></table>
<br>
<!--menu end-->
		</td>
		<td width="160" valign="top">
			<div class="pos_r_fix_mypage1">
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
