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
<style>

th {
    background-color: green;
    color: white;
}
</style>
	
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
			<h3 class="titlehdr">บัญชีแยกประเภทรายจ่าย ประจำเดือน <?php $m = $sm;// date("m");
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
				<table style="text-align: center; margin-left: auto; margin-right: auto; width: 703px; height: 413px;" border="1" cellpadding="2" cellspacing="2">
					<tbody>
						<tr>
    <td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">
	    <table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2">
		    <tr>
			    <th  >ชื่อบัญชี
			    </th>
			    <th  >รายการ
			    </th>
			    <th>โดยวิธี
			    </th>
			    <th>บันทึกโดย
			    </th>
			    <th>รวม(บาท)
			    </th>
		    </tr>
    <?php 
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
    
    $acn = mysqli_query($link, "SELECT * FROM acnumber WHERE  (ac_no >= '5000' AND ac_no < '5999') ");
    $j=1;
    while($row = mysqli_fetch_array($acn))
    {
      $acno[$j] = $row['ac_no'];
      $acname[$j] = $row['name'];
      $j = $j +1;
    }
	    for ($i = 1;$i<$j;$i++)
	    {
		    // Print out the contents of each row into a table
		    $dtype = mysqli_query($link, "SELECT * FROM daily_account WHERE  ac_no_i = '$acno[$i]' AND MONTH(date) ='$sm' AND YEAR(date) ='$sy' ");
		    while($row = mysqli_fetch_array($dtype))
		    {
			    echo "<tr><td style='text-align: left;'>"; 
			    echo $acname[$i];
			    echo "</td><td style='text-align: left;'>";
			    echo $row['detail'];
			    echo "</td><td width=15%  style='text-align: right;'>";
			    echo $row['ac_no_o'];
			    echo "</td><td width=15%  style='text-align: right;'>";
			    echo $row['recordby'];
			    echo "</td><td width=15%  style='text-align: right;'>";
			    if(!empty($row['price']))
			    {
			    echo "<span class=currency>".$row['price']."</span>";
			    }
			    $price[$i] = $price[$i] +$row['price'];
			    echo "</td></tr>";
		    }
			    echo "<tr><th style='text-align: left;'>"; 
			    echo $acname[$i];
			    echo "</th><th style='text-align: left;'>";
			    echo "รวม";
			    echo "</th><th width=15%  style='text-align: right;'>";
			    echo '';
			    echo "</th><th width=15%  style='text-align: right;'>";
			    echo '';
			    echo "</th><th width=15%  style='text-align: right;'>"; 
			    if(!empty($price[$i]))
			    {
			    echo "<span class=currency>".$price[$i]."</span>";
			    }
			    echo "</th></tr>";
			    $allprice = $allprice+$price[$i];
		    
	    }
	 echo "<tr><th style='text-align: left;'>รวมทั้งหมด</th><th></th><th></th><th></th><th><span class=currency>".$allprice."</span></th></tr>";   
    ?>
								</table>
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