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
  <tr><td width="15%" valign="top"><div class="pos_l_fix">
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
			<h3 class="titlehdr">บัญชีรายวันทั่วไป ณ วันที่ <?php echo $sd; ?> <?php $m = $sm;
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
				<table style="text-align: center; margin-left: auto; margin-right: auto; " border="1" cellpadding="2" cellspacing="2">
					<tbody>
						<tr>
							<td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">
								<table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2">
									<tr>
										<th width = 12%>ลำดับ
										</th>
										<th  >รายละเอียด DB
										</th>
										<th  >รายละเอียด CR
										</th>
										<th width = 15%>จำนวน (บาท)
										</th>
									</tr>
									<?php 	
										$i = 1;
										$dadate = $sy.'-'.$sm.'-'.$sd;
										
										$dtype = mysqli_query($link, "SELECT * FROM daily_account WHERE date = '$dadate' ");
										while($row = mysqli_fetch_array($dtype))
										{
											//look up ac_no name
											$acnamein = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no = '$row[ac_no_i]' "));
											if ($row['ac_no_i'] >= 5000 and $row['ac_no_i'] < 6000) $acnamein['name'] = "จ่าย";
											//check from deleted drug
											if($row['ac_no_i'] == 5999)
											{
                                                //get recorded time to search for record in deleted list
                                                $rctime = $row['ctime'];
                                                $acn = $row['ac_no_o'];
                                                $gn = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM deleted_drug WHERE ac_no = '$acn' AND dtime > '$rctime' ORDER BY `dtime` ASC "));
                                                $acnameout['name']=$gn['dname'].'-'.$gn['size'];
                                                if ($gn['ac_no']!='') goto Next1;
                                                $gn = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM deleted_rm WHERE ac_no = '$acn' AND dtime > '$rctime' ORDER BY `dtime` ASC "));
                                                $acnameout['name']=$gn['rawname'].'-'.$gn['size'];
                                                if ($gn['ac_no']!='') goto Next1;
											}
											
											$acnameout = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no = '$row[ac_no_o]' "));
											Next1:
											if ($row['ac_no_o'] >= 4000 and $row['ac_no_o'] < 5000) $acnameout['name'] = "รับ";
											//this is for patient id 
											if ($row['ac_no_o'] >= 1000000 and $row['ac_no_o'] < 2000000)
												{
													$na1 = $row['ac_no_o']-1000000 ;
													$acnameout['name'] = 'ID-'.$na1 ;
												}	
										// Print out the contents of each row into a table
											echo "<tr><th width = 12%>";
											echo $i;
											echo "</th><th style='text-align: left;' >"; 
											echo $acnamein['name'].' : '.$row['detail'];
											echo " By:".$row['recordby'];
											if($row['recordby']!=0)
											{
											$rcb = $row['recordby'];
											$rb = mysqli_query($link, "SELECT F_Name FROM staff WHERE  user_id = '$rcb'");
											$rbn = mysqli_fetch_array($rb);
											echo ":".$rbn[0];
											}
											echo "</th><th style='text-align: left;' >"; 
											echo $acnameout['name'].' : '.$row['detail'];
											echo "</th><th style='text-align: right;' width=15%>"; 
											echo "<span class=currency>".$row['price']."</span>";
											$allflow = $allflow +$row['price'];
											echo "</th></tr>";
											$i = $i + 1;
										} 
										echo "</table>";
										echo "<table style='text-align: center; margin-left: auto; margin-right: auto; width: 100%;' border='1' cellpadding='2' cellspacing='2'>";
										echo "<tr><th>ยอดรวม</th><th style='text-align: right;' width = 15%>";
										echo "<span class=currency>".$allflow."</span>";
										echo "</th></tr>";
									?>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<br>
<!--menu end-->
		</td>
		<td width="15%" valign="top">
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
