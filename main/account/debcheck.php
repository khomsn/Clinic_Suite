<?php 
include '../../config/dbc.php';

page_protect();
include '../../libs/dateandtimezone.php';
include '../../libs/progdate.php';

$title = "::บัญชีและการเงิน::";
include '../../main/header.php';
include '../../libs/currency.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../libs/popup.php';
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
<h3 class="titlehdr">บัญชีรายชื่อผู้ป่วยที่ค้างชำระค่ารักษา</h3>
<table style="text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
<tbody>
<tr><td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">
    <table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2" class="TFtable">
    <tr><th>เลขระเบียน</th><th>ชื่อ</th><th>จำนวน (บาท)</th><th>มารักษาครั้งสุดท้าย</th><th>อายุ</th></tr>
    <?php
        $deb=mysqli_query($link, "select * from debtors");
        while($gd = mysqli_fetch_array($deb))
        {
            if(!empty($gd['ctmid']))
            {
                echo "<tr><td>".$gd['ctmid'];
                echo "</td><td>";
                //name
                $gn=mysqli_fetch_array(mysqli_query($linkopd, "select * from patient_id where id='$gd[ctmid]'"));
                echo $gn['prefix'].'&nbsp;'.$gn['fname'].'&nbsp;&nbsp;'.$gn['lname'];
                echo "</td>";
                echo "<td>". number_format ($gd['price'] , 2 , '.' , ',' )."</td>";
                echo "</td><td>";
                //lastvisit
                $lv = mysqli_fetch_array(mysqli_query($link, "select * from sell_account where ctmid='$gd[ctmid]' order by vsdate DESC"));
                echo $lv['day'].'&nbsp;';
                $m = $lv['month'];
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
			}
			echo " พ.ศ. ";
			echo $lv['year']+543;                
                echo "</td><td>";
                echo "</td></tr>";
            }
        }
    ?>
    </table>
</td></tr>
</tbody>
</table>
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
