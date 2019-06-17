<?php 
include '../../config/dbc.php';

page_protect();
include '../../libs/dateandtimezone.php';
include '../../libs/progdate.php';

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
					<tr><td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">
                        <table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2" class="TFtable">
                            <tr><th width = 8%>ลำดับ</th><th>รายละเอียด</th><th width = 10%>จ่ายโดย</th><th width = 10%>จ่าย</th><th width = 10%>ค้างจ่าย</th><th width = 10%>รวม(บาท)</th></tr>
                            <?php 	
                                $i = 1;$ac=1;
                                $dtype = mysqli_query($link, "SELECT DISTINCT payby_acno FROM sell_account WHERE day = '$sd' AND month ='$sm' AND year ='$sy' ");
                                while($row = mysqli_fetch_array($dtype))
                                {
                                    $pbacno[$ac]=$row['payby_acno'];
                                    $ac = $ac+1;
                                }
                                
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
                                    $byac = $row['payby_acno'];
                                    $pb = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no = '$byac' "));
                                    echo $pb['name'];
                                    echo "</th><th width=10%  style='text-align: right;'>"; 
                                    echo "<span class=currency>".$row['pay']."</span>";
                                    $pay = $pay + $row['pay'];
                                    for($j=1;$j<$ac;$j++)
                                    {
                                        if($byac==$pbacno[$j])
                                        {
                                            $pbacn[$byac] = $pbacn[$byac] + $row['pay'];
                                        }
                                    }
                                    echo "</th><th width=10%  style='text-align: right;'>"; 
                                    echo "<span class=currency>".$row['own']."</span>";
                                    $own = $own + $row['own'];
                                    echo "</th><th width=10%  style='text-align: right;'>"; 
                                    echo "<span class=currency>".$row['total']."</span>";
                                    echo "</th></tr>";
                                    $i = $i + 1;
                                } 
                                echo "<tr><th>&nbsp;</th><th>ยอดรวม</th><th></th><th  style='text-align: right;'>";
                                echo "<span class=currency>".$pay."</span>";
                                echo "</th><th  style='text-align: right;'>";
                                echo "<span class=currency>".$own."</span>";
                                echo "</th><th  style='text-align: right;'>";
                                echo "<span class=currency>".($pay + $own)."</span>";
                                echo "</th></tr>";
                            ?>
                        </table>
                        <?php 
                            $sdate = $sy.'-'.$sm.'-'.$sd;
                            for($j=1;$j<=$ac;$j++)
                            {
                                $acin = $pbacno[$j];
                                $dtype = mysqli_query($link, "SELECT * FROM daily_account WHERE date = '$sdate' AND ac_no_i = '$acin' AND ac_no_o ='40000001' ");
                                if($acin == 10000001) $dtail = "ยอดขายเงินสด ประจำวัน";
                                else $dtail = "ยอดขาย ประจำวัน";
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
                                                    ('$sdate','$acin','40000001','$dtail','$pbacn[$acin]','d','s','0')";
                                    // Now insert Patient to "patient_id" table
                                    if($pbacn[$acin] == 0) goto noupdate;
                                    mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
                                }
                                else
                                {
                                    if($pbacn[$acin] == 0) goto noupdate;
                                    mysqli_query($link, "UPDATE  `daily_account` SET `price` = '$pbacn[$acin]' WHERE `date` = '$sdate' AND ac_no_i = '$acin'  AND ac_no_o = '40000001' ");
                                }
                                noupdate:
                            }
                        ?>
					</td></tr>
				</table>
		</td><td width="160" valign="top">
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
</body></html>
