<?php 
include '../../config/dbc.php';
page_protect();
include '../../libs/dateandtimezone.php';
include '../../libs/progdate.php';

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
	    <table style="text-align: center; margin-left: auto; margin-right: auto; width: auto;" border="1" cellpadding="2" cellspacing="2" class="TFtable">
		    <tr><th>ชื่อบัญชี</th><th>รายการ</th><th>โดยวิธี</th><th>บันทึกโดย</th><th>รวม(บาท)</th></tr>
    <?php 
	/* 
	*/	
    
    $acn = mysqli_query($link, "SELECT * FROM acnumber WHERE  (ac_no >= '50000000' AND ac_no <= '50009999') OR (ac_no >= '51000000' AND ac_no < '59999999') ");
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
			    $acn = $row['ac_no_o'];
			    $acl = mysqli_query($link, "SELECT name FROM acnumber WHERE  ac_no = '$acn'");
			    $aclget = mysqli_fetch_array($acl);
			    echo $aclget[0];
			    echo "</td><td width=15%  style='text-align: right;'>";
			    echo $rcb = $row['recordby'];
			    $rb = mysqli_query($link, "SELECT F_Name FROM staff WHERE  user_id = '$rcb'");
			    $rbn = mysqli_fetch_array($rb);
			    echo "-".$rbn[0];
			    echo "</td><td width=15%  style='text-align: right;'>";
			    if(!empty($row['price']))
			    {
			    echo "<span class=currency>".$row['price']."</span>";
			    }
			    $price[$i] = $price[$i] +$row['price'];
			    echo "</td></tr>";
		    }
		    if($price[$i]!=0)
		    {
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
            }
            $allprice = $allprice+$price[$i];
	    }
	 echo "<tr><th style='text-align: left;'>รวมทั้งหมด</th><th></th><th></th><th></th><th><span class=currency>".$allprice."</span></th></tr>";   
    ?>
            </table>
		</td><td width="180" valign="top">
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
