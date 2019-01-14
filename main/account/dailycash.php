<?php 
include '../../config/dbc.php';

page_protect();
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
			<h3 class="titlehdr">บัญชีเงินสด ณ วันที่ <?php echo $sd; ?> <?php $m = $sm;
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
				<table style="width:80%; text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
                    <tr><td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">
                            <table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2" class="TFtable">
                                <tr><th width = 12%>ลำดับ</th><th>รายละเอียด</th><th width = 20%>ขายเงินสด</th>	<th width = 20%>รับ-ค้างจ่าย</th></tr><?php
                                $dadate = $sy.'-'.$sm.'-'.$sd;
                                $dpid = 0;//start index//row id สำหรับ รับชำระหนี้ 11000000-19999999 ลูกหนี้ คนไข้ค้างชำระ
                                $dtype = mysqli_query($link, "SELECT * FROM daily_account WHERE date = '$dadate' AND ac_no_i = '10000001' AND bors='s' AND (ac_no_o>='11000000' AND ac_no_o <='19999999')");
                                while($row = mysqli_fetch_array($dtype))
                                {
                                    $acno = $row['ac_no_o'];
                                    $pricecheck[$acno][$dpid] = $row['price'];
                                    //row id สำหรับ รับชำระหนี้ 11000000-19999999 ลูกหนี้ คนไข้ค้างชำระ
                                    $paytime[$dpid] = $row['ctime'];
                                    $dpid = $dpid+1;//next index
                                }
                                
                                $i = 1;	
                                $dtype = mysqli_query($link, "SELECT * FROM sell_account WHERE day = '$sd' AND month ='$sm' AND year ='$sy' ");
                                while($row = mysqli_fetch_array($dtype))
                                {
                                // Print out the contents of each row into a table
                                    $ptid = $row['ctmid'];
                                    $ctmacno = $row['ctmacno'];
                                    $vsdt = $row['vsdate'];
                                    $row2 = mysqli_fetch_array(mysqli_query($linkopd, "SELECT * FROM patient_id WHERE id = '$ptid' "));
                                //	if($check[$ctmacno]==0)
                                    {
                                    echo "<tr><th width = 12%>";
                                    echo $i;
                                    echo "</th><th >"; 
                                    echo $row2['prefix'].' '.$row2['fname'].'  '.$row2['lname'];
                                    echo "</th><th width=20%  style='text-align: right;'>"; 
                                    echo "<span class=currency>".$row['cash']."</span>";
                                    $cash = $cash + $row['cash']; 
                                    echo "</th><th width=20%  style='text-align: right;'>";
                                    for($m=0;$m<$dpid;$m++)
                                    {
                                    if(!empty($pricecheck[$ctmacno][$m]) AND ($paytime[$m] > $vsdt))
                                    {
                                    echo "<span class=currency>".$pricecheck[$ctmacno][$m]."</span>";
                                    $check[$ctmacno][$m] = 1;
                                    $own = $own + $pricecheck[$ctmacno][$m];
                                    $pricecheck[$ctmacno][$m] = "";
                                    break;
                                    }
                                    }
                                    echo "</th></tr>";
                                    $i = $i + 1;
                                    }
                                } 
                            //รับชำระหนั้เท่านั้น	
                                $i = 1;$j=0;
                                
                                $dtype = mysqli_query($link, "SELECT * FROM daily_account WHERE date = '$dadate' AND ac_no_i = '10000001' AND bors='s' AND (ac_no_o>='11000000' AND ac_no_o <='19999999') ");
                                while($row = mysqli_fetch_array($dtype))
                                {
                                // Print out the contents of each row into a table
                                    $acno = $row['ac_no_o'];
                                    $ptid = $acno - 11000000;
                                    if($check[$acno][$j] == 0)
                                    {
                                    echo "<tr><th width = 12%>";
                                    echo $i;
                                    echo "</th><th >"; 
                                    $row2 = mysqli_fetch_array(mysqli_query($linkopd, "SELECT * FROM patient_id WHERE id = '$ptid' "));
                                    echo $row2['prefix'].' '.$row2['fname'].'  '.$row2['lname'];
                                    echo "</th><th width=20%>"; 
                                    echo "</th><th width=20%  style='text-align: right;'>";
                                    if(!empty($row['price']))
                                    echo "<span class=currency>".$row['price']."</span>";
                                    else echo "<span class=currency>".(0)."</span>";
                                    $own = $own + $row['price'];
                                    echo "</th></tr>";
                                    $check[$acno][$j] = 1;
                                    $i = $i + 1;
                                    }
                                    $j = $j + 1;
                                } 
                            //เงินสดอื่นๆ
                                $i = 1;
                                
                                $dtype = mysqli_query($link, "SELECT * FROM daily_account WHERE date = '$dadate' AND ac_no_i = '10000001' AND  (ac_no_o <'11000000' OR ac_no_o >'19999999') AND ac_no_o!='40000001'");
                                while($row = mysqli_fetch_array($dtype))
                                {
                                // Print out the contents of each row into a table
                                    echo "<tr><th width = 12%>";
                                    echo $i;
                                    echo "</th><th >"; 
                                    $acno = $row['ac_no_o'];
                                    $row2 = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no = '$acno' "));
                                    echo $row2['name'].'  '.$row['detail'];
                                    echo "</th><th width=20%>"; 
                                    echo "</th><th width=20%  style='text-align: right;'>";
                                    if(!empty($row['price']))
                                    echo "<span class=currency>".$row['price']."</span>";
                                    else echo "<span class=currency>".(0)."</span>";
                                    $own = $own + $row['price'];
                                    echo "</th></tr>";
                                    $i = $i + 1;
                                } 

                                echo "</table>";
                                echo "<table style='text-align: center; margin-left: auto; margin-right: auto; width: 100%;' border='1' cellpadding='2' cellspacing='2'>";
                                echo "<tr><th>ยอดรวม</th><th width = 40%  style='text-align: right;'>";
                                echo "<span class=currency>".$allget = $cash + $own."</span>";
                                echo "</th></tr>";
                            ?></table></td>
            <td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">
                    <table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2" class="TFtable">
                        <tr><th width = 12%>ลำดับ</th><th>รายละเอียด</th><th width = 30%>จ่าย-ซื้อ</th></tr>
                        <?php 	
                            $i = 1;
                            $dtype = mysqli_query($link, "SELECT * FROM daily_account WHERE date = '$dadate'  AND ac_no_o = '10000001' ");
                            while($row = mysqli_fetch_array($dtype))
                            {
                            // Print out the contents of each row into a table
                                echo "<tr><th width = 12%>";
                                echo $i;
                                echo "</th><th >"; 
                                echo $row['detail'];
                                echo "</th><th width=30%  style='text-align: right;'>"; 
                                if(!empty($row['price']))
                                echo "<span class=currency>".$row['price']."</span>";
                                else echo "<span class=currency>".(0)."</span>";
                                $pall = $pall + $row['price'];
                                echo "</th></tr>";
                                $i = $i + 1;
                            } 
                            echo "</table>";
                            echo "<table style='text-align: center; margin-left: auto; margin-right: auto; width: 100%;' border='1' cellpadding='2' cellspacing='2'>";
                            echo "<tr><th>ยอดรวม</th><th width = 30%  style='text-align: right;'>";
                            echo "<span class=currency>".$pall."</span>";
                            echo "</th></tr>";
                        ?>
                    </table>
                </td>
            </tr>
            <tr><td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">รวมเงินคงเหลือ</td><td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);"><?php echo "<span class=currency>".($allget - $pall)."</span>"; ?></td></tr>
			</table>
			<br>
<!--menu end-->
		</td>
		<td width="200" valign="top">
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
