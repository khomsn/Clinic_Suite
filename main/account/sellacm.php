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
		</td><td>
<!--menu-->
			<h3 class="titlehdr">บัญชีขาย ประจำเดือน <?php $m = $sm;// date("m");
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
                <table style="text-align: center; margin-left: auto; margin-right: auto; width: 50%;" border="0" cellpadding="2" cellspacing="2" class="TFtable">
                    <tr><th  >ยอดขาย ประจำวันที่</th><th width = 10%>จ่าย</th><th width = 10%>ค้างจ่าย</th><th width = 10%>รวม(บาท)</th></tr>
                    <?php 	
                    if($sm == date("m") and $sy == date("Y")) $imax = date("d");
                    elseif($sm == 1 or $sm == 3 or $sm == 5 or $sm == 7 or $sm == 8 or $sm == 10 or $sm == 12) $imax=31;
                    elseif($sm == 2 and $sy%4 == 0) $imax = 29;
                    elseif($sm == 2 and $sy%4 != 0) $imax = 28;
                    else $imax = 30;
                        for ($i = 1;$i<=$imax;$i++)
                        {
                            // Print out the contents of each row into a table
                                echo "<tr><td >"; 
                                echo $i.' - '. $sm .' - '. $bsy ;
                            $dtype = mysqli_query($link, "SELECT * FROM sell_account WHERE  day = '$i' AND month ='$sm' AND year ='$sy' ");
                            while($row = mysqli_fetch_array($dtype))
                            {
                                $cash[$i] = $cash[$i] + $row['pay']; 
                                $own[$i] = $own[$i] + $row['own'];
                            } 
                                echo "</td><td width=15%  style='text-align: right;'>";
                                if(!empty($cash[$i]))
                                echo "<span class=currency>".$cash[$i]."</span>";
                                $cashm = $cashm + $cash[$i]; 
                                echo "</td><td width=15%  style='text-align: right;'>";
                                if(!empty($own[$i]))
                                echo "<span class=currency>".$own[$i]."</span>";
                                $ownm = $ownm +$own[$i];
                                echo "</td><td width=15%  style='text-align: right;'>"; 
                                echo "<span class=currency>".($cash[$i] + $own[$i])."</span>";
                                echo "</td></tr>";
                            
                        }
                        echo "<tr><th>ยอดรวม</th><th  style='text-align: right;'>";
                        echo "<span class=currency>".$cashm."</span>";
                        echo "</th><th style='text-align: right;' >";
                        echo "<span class=currency>".$ownm."</span>";
                        echo "</th><th style='text-align: right;'>";
                        echo "<span class=currency>".($cashm + $ownm)."</span>";
                        echo "</th></tr>";
                        echo "<tr><th>ต้นทุนสินค้ารวม</th><th  style='text-align: right;'>";
                        //get this month price
                        $allrsu = mysqli_query($link, "select * from allrsupm WHERE MONTH(mandy) = '$sm' AND YEAR(mandy) = '$sy'");
                        while ($row1 =  mysqli_fetch_array($allrsu))
                        {
                        $omprice = $omprice+$row1['price'];
                        }
                        echo "ITOP</th><th>".round($omprice/($cashm + $ownm)*100,2)."%</th><th>";
                        echo "<span class=currency>".$omprice."</span>";
                        echo "</th></tr>";
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
