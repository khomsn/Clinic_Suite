<?php 
include '../login/dbc.php';
page_protect();
include '../libs/progdate.php';
?>

<!DOCTYPE html>
<html>
<head>
<title>บัญชีและการเงิน</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
<?php include '../libs/popup.php';?>
<?php include '../libs/currency.php'; ?>
	<link rel="stylesheet" href="../public/css/styles.css">
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
		</td>
		<td>
<!--menu-->
			<h3 class="titlehdr">บัญชีกำไรขาดทุน *(ต้นทุนยาได้ถูกหักหมดในวันที่ซื้อ) ประจำเดือน <?php $m = $sm;// date("m");
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
				<table style="text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
					<tbody>
						<tr>
							<td style="width: 40%; vertical-align: top; background-color: rgb(255, 255, 204);">
								<table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2">
									<tr>
										<th style="width: 30%;" >รายได้จากการขาย ประจำวันที่
										</th>
										<th>เงินสด
										</th>
										<th>ค้างจ่าย
										</th>
										<th>รวม(บาท)
										</th>
									</tr>
									<?php 	
									if($sm == date("m") and $sy == date("Y")) $imax = date("d");
									elseif($sm == 1 or $sm == 3 or $sm == 5 or $sm == 7 or $sm == 8 or $sm == 10 or $sm == 12) $imax=31;
									elseif($sm == 2 and $sy%4 == 0) $imax = 29;
									elseif($sm == 2 and $sy%4 != 0) $imax = 28;
									else $imax = 30;
										for ($i = 1;$i<=$imax;$i++)
										{
											// Print out the contents of each row into a table
												echo "<tr><th >"; 
												echo $i.' - '. $sm .' - '. $bsy ;
											$dtype = mysqli_query($link, "SELECT * FROM sell_account WHERE  day = '$i' AND month ='$sm' AND year ='$sy' ");
											while($row = mysqli_fetch_array($dtype))
											{
												$cash[$i] = $cash[$i] + $row['cash']; 
												$own[$i] = $own[$i] + $row['own'];
											} 
												echo "</th><th width=15% style='text-align: right;'>";
												if(!empty($cash[$i]))
												echo "<span class=currency>".$cash[$i]."</span>";
												$cashm = $cashm + $cash[$i]; 
												echo "</th><th width=15% style='text-align: right;'>";
												if(!empty($own[$i]))
												echo "<span class=currency>".$own[$i]."</span>";
												$ownm = $ownm +$own[$i];
												echo "</th><th width=15% style='text-align: right;'>"; 
												echo "<span class=currency>".($cash[$i] + $own[$i])."</span>";
												echo "</th></tr>";
											
										}
										echo "<tr><th>ยอดรวม</th><th style='text-align: right;'>";
										echo "<span class=currency>".$cashm."</span>";
										echo "</th><th style='text-align: right;'>";
										echo "<span class=currency>".$ownm."</span>";
										echo "</th><th style='text-align: right;'>";
										echo "<span class=currency>".$inp = $cashm + $ownm."</span>";
										echo "</th></tr>";
									?>
								</table>
							</td>
							<td style="width: 40%; vertical-align: top; background-color: rgb(255, 255, 204);">
								<table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2">
									<tr>
										<th style="width: 30%;" >รายจ่ายและต้นทุน ประจำวันที่
										</th>
										<th>รายจ่าย
										</th>
										<th>ต้นทุน
										</th>
										<th>รวม(บาท)
										</th>
									</tr>
									<?php 	
									if($sm == date("m") and $sy == date("Y")) $imax = date("d");
									elseif($sm == 1 or $sm == 3 or $sm == 5 or $sm == 7 or $sm == 8 or $sm == 10 or $sm == 12) $imax=31;
									elseif($sm == 2 and $sy%4 == 0) $imax = 29;
									elseif($sm == 2 and $sy%4 != 0) $imax = 28;
									else $imax = 30;
									
										for ($i = 1;$i<=$imax;$i++)
										{
											// Print out the contents of each row into a table
												echo "<tr><th >"; 
												echo $i.' - '. $sm .' - '. $bsy ;
												$dd = $sy."-".$sm."-".$i;
											//	echo $dd;
												$date = date($dd);
												//echo $date;
	/* 
		1000 สินทรัพย์
		1001 เงินสด
		1002-1020 ธนาคาร
		1021-1030 อาคาร และ ที่ดิน 
		1500-1999 ลูกหนี้ทั่วไป
		10000-15000 วอ.แพทย์
		15001-19999 อ.สำนักงาน
		100000-179999 สินค้า
		180000-189999 วัตถุดิบ
		1000000-1999999 ลูกหนี้ คนไข้ค้างชำระ


		2000 เจ้าหนี้
		2000-2100 เจ้าหนี้ อื่นๆ
		2101-2999 เจ้าหนี้ ซื้อ ยา และ อุปกรณ์

		3000 ทุน
		3001-3499 ทุน ผู้ร่วมทุน
		3500-3999 ทุน คืน กำไร

		4000 รายได้รับ รายได้จากการขาย
		4001-4999 รายได้อื่นๆ

		5000 รายจ่าย
		5001-5998 ว.สำนักงาน ตัดเป็นค่าใช่จ่ายเลย
		50000-55000  ค่าเสื่อมราคา วอ.แพทย์ หักเป็นค่าใช้จ่าย 
		55001-59999 ค่าเสื่อมราคา อ.สำนักงาน หักเป็นค่าใช้จ่าย 
		5999 ตัดยอด

	*/	
									//รายจ่าย
	$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i >= 5000 AND ac_no_i <= 5999 AND date='$date'");
	while($row = mysqli_fetch_array($result))
	{
		$pay[$i] = $pay[$i]  + $row['price'];
	}
	//ต้นทุนสินค่าที่ซื้อมา ตัดเป็นค่าใช้จ่ายในเดือนที่ซื้อเลย วัตถุดิบ 100000-179999 สินค้า 180000-189999 วัตถุดิบ
	$result = mysqli_query($link, "SELECT * FROM daily_account WHERE ac_no_i >= 100000 AND ac_no_i <= 189999 AND date='$date'");
	while($row = mysqli_fetch_array($result))
	{
		$buy[$i] = $buy[$i]  + $row['price'];
	}

												echo "</th><th width=15% style='text-align: right;'>";
												if(!empty($pay[$i]))
												echo "<span class=currency>".$pay[$i]."</span>";
												$paym = $paym + $pay[$i]; 
												echo "</th><th width=15% style='text-align: right;'>";
												if(!empty($buy[$i]))
												echo "<span class=currency>".$buy[$i]."</span>";
												$buym = $buym +$buy[$i];
												echo "</th><th width=15% style='text-align: right;'>"; 
												echo "<span class=currency>".($pay[$i] + $buy[$i])."</span>";
												echo "</th></tr>";
											
										}
										echo "<tr><th>ยอดรวม</th><th style='text-align: right;'>";
										echo "<span class=currency>".$paym."</span>";
										echo "</th><th style='text-align: right;'>";
										echo "<span class=currency>".$buym."</span>";
										echo "</th><th style='text-align: right;'>";
										echo "<span class=currency>".$outp = $paym + $buym."</span>";
										echo "</th></tr>";
									?>
								</table>
							</td>
						</tr>
<tr><td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">กำไรขาดทุนสุทธิ</td><td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);"><?php echo "<span class=currency>".($inp-$outp)."</span>";?> บาท</td></tr>
					</tbody>
				</table>
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