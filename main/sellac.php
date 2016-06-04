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
  <tr><td colspan="3">&nbsp;</td></tr>
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
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
<!--menu-->
			<h3 class="titlehdr">บัญชีขาย ณ วันที่ <?php echo $sd; ?> <?php $m = $sm;
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
							<td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">
								<table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2">
									<tr>
										<th width = 8%>ลำดับ
										</th>
										<th  >รายละเอียด
										</th>
										<th width = 10%>เงินสด
										</th>
										<th width = 10%>ค้างจ่าย
										</th>
										<th width = 10%>รวม(บาท)
										</th>
									</tr>
									<?php 	
										$i = 1;
										$dtype = mysqli_query($link, "SELECT * FROM sell_account WHERE day = '$sd' AND month ='$sm' AND year ='$sy' ");
										while($row = mysqli_fetch_array($dtype))
										{
										// Print out the contents of each row into a table
											echo "<tr><th width = 8%>";
											echo $i;
											echo "</th><th >"; 
											$ptid = $row['ctmid'];
											$row2 = mysqli_fetch_array(mysqli_query($linkopd, "SELECT * FROM patient_id WHERE id = '$ptid' "));
											echo $row2['prefix'].' '.$row2['fname'].'  '.$row2['lname'];
											//echo $row['ctmid'];
											echo "</th><th width=10%  style='text-align: right;'>"; 
											echo "<span class=currency>".$row['cash']."</span>";
											$cash = $cash + $row['cash']; 
											echo "</th><th width=10%  style='text-align: right;'>"; 
											echo "<span class=currency>".$row['own']."</span>";
											$own = $own + $row['own'];
											echo "</th><th width=10%  style='text-align: right;'>"; 
											echo "<span class=currency>".$row['total']."</span>";
											echo "</th></tr>";
											$i = $i + 1;
										} 
										echo "<tr><th>&nbsp;</th><th>ยอดรวม</th><th  style='text-align: right;'>";
										echo "<span class=currency>".$cash."</span>";
										echo "</th><th  style='text-align: right;'>";
										echo "<span class=currency>".$own."</span>";
										echo "</th><th  style='text-align: right;'>";
										echo "<span class=currency>".($cash + $own)."</span>";
										echo "</th></tr>";
									?>
								</table>
								<?php 
									$sdate = $sy.'-'.$sm.'-'.$sd; 
									$dtype = mysqli_query($link, "SELECT * FROM daily_account WHERE date = '$sdate' AND ac_no_i = '1001' AND ac_no_o ='4000' ");
									$dtail = "ยอดขายเงินสด ประจำวัน";
									$i =0;
									while ($row = mysqli_fetch_array($dtype))
									{
										$i = $i+1;
									}
									if( $i == 0 )
									{
										// assign insertion pattern
										$sql_insert = "INSERT into `daily_account`
														(`date`,`ac_no_i`,`ac_no_o`, `detail`, `price`, `type`, `bors`, `recordby`)
														VALUES
														('$sdate','1001','4000','$dtail','$cash','d','s','0')";
										// Now insert Patient to "patient_id" table
										if($cash == 0) goto noupdate;
										mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
									}
									else
									{
										if($cash == 0) goto noupdate;
										mysqli_query($link, "UPDATE  `daily_account` SET `price` = '$cash' WHERE `date` = '$sdate' and ac_no_o = '4000' ");
									}
									noupdate:
								?>
								
							</td>
						</tr>
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
