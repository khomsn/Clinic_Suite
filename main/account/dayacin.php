<?php 
include '../../config/dbc.php';
page_protect();
include '../../libs/dateandtimezone.php';
include '../../libs/progdate.php';

$_SESSION['sd'] = $sd;
$_SESSION['sm'] = $sm;
$_SESSION['sy'] = $sy;

if($_POST['save'] == 'บันทึก') 
{ 

    // assign insertion pattern
    $sql_insert = "INSERT into `daily_account`
                (`date`,`ac_no_i`,`ac_no_o`, `detail`,`price`,`recordby`)
                VALUES (now(),'$_POST[acin]','$_POST[acout]','$_POST[detail]','$_POST[price]','$_SESSION[user_id]')";

    mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
    // go on to other step
    header("Location: ../../main/account/dayacin.php");  
}
$title = "::บัญชีและการเงิน::";
include '../../main/header.php';
$formid = "regForm";
include '../../libs/validate.php';
include '../../libs/popup.php';
include '../../main/bodyheader.php';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr><td width="160" valign="top">
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
      ?>
		<p>&nbsp; </p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
      </td>
      <td width="10" valign="top"><p>&nbsp;</p></td>
      <td>
<!--menu-->
	<h3 class="titlehdr">บันทึก บัญชีรายวันทั่วไป วันที่ <?php echo $sd; ?> <?php $m = $sm;
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
	<form method="post" action="dayacin.php" name="regForm" id="regForm">
	<table style="text-align: center; margin-left: auto; margin-right: auto; width: 80%; background-color: rgb(255, 255, 204);" border="1" cellpadding="2" cellspacing="2">
	<tr><td style="width: 100%; vertical-align: top; background-color: rgb(255, 255, 204);">
	  <h3><p>ในการลงบัญชี <span class="required">*</span> จำเป็นต้องมี.</p></h3>
	    <table style="text-align: center; margin-left: auto; margin-right: auto; width=80%" border=1>
		<tr><td style="text-align: right;" >
		ซื้อ รับ หรือ นำเข้า*</td>
		<td>
		<select tabindex="1" name="acin" class="required">
			<option value="" selected></option>
	<?php
		$condi = "(ac_no >=10000001 AND ac_no <= 10299999)
			  or (ac_no >=20000001 AND ac_no <= 20199999)
			  or (ac_no >=30000001 AND ac_no <=39999999)
			  or (ac_no >=50000002 AND ac_no <=59999998)";
		$dgroup = mysqli_query($link, "SELECT * FROM acnumber WHERE $condi
		ORDER BY `name` ASC");
	/* 
            10000001 เงินสด
            10000002-10000249 ธนาคาร
            10000250-10003999 อาคาร และ ที่ดิน สินทรัพย์อื่นๆ
            10004000-10009999 ลูกหนี้ทั่วไป
            10010000-10099999 วอ.แพทย์
            10100000-10299999 อ.สำนักงาน

            20000001-20199999 เจ้าหนี้ เงินกู้

            30000001-34999999 ทุน ผู้ร่วมทุน
            35000001-39999999 ทุน คืน กำไร

            50000002-50000249 ธนาคาร ค่าธรรมเนียม
            50000250-50002999 รายจ่ายต่างๆ
            50003000-50009999 ว.สำนักงาน ตัดเป็นค่าใช่จ่ายเลย
            50010000-50099999  ค่าเสื่อมราคา วอ.แพทย์ หักเป็นค่าใช้จ่าย 
            50100000-50299999 ค่าเสื่อมราคา อ.สำนักงาน หักเป็นค่าใช้จ่าย 
            51000000-59999998 เงินเดือน จ่าย
	*/	

		if (mysqli_multi_query($link, $query)) {
		    do {
			/* store first result set */
			if ($result = mysqli_store_result($link)) {
			    while ($row = mysqli_fetch_row($result)) {
				echo $row[0];
			    }
			    mysqli_free_result($result);
			}
			/* print divider */
			if (mysqli_more_results($link)) {
			    printf("-----------------\n");
			}
		    } while (mysqli_next_result($link));
		}
		
		while($grow = mysqli_fetch_array($dgroup))
		{
			echo "<option value='";
			echo $grow['ac_no'];
			echo "'>";
			echo $grow['name']."</option>";
		}
		?>
	    </select>
	    </td></tr>
	    <tr><td style="text-align: right;" >
	    ขาย จ่าย หรือ นำออก*
	    </td><td>
	    <select tabindex="2" name="acout" class="required">
		    <option value="" selected></option>
		    <?php
		    $condi = "(ac_no >=10000001 AND ac_no <=10299999)
			      or (ac_no >=20000001 AND ac_no <=20199999)
			      or (ac_no >=30000001 AND ac_no <=34999999)
			      or (ac_no >=40000002 AND ac_no <=49999999)";
		    $dgroup = mysqli_query($link, "SELECT * FROM acnumber WHERE $condi
		    ORDER BY `name` ASC");
	    /* 
            10000001 เงินสด
            10000002-10000249 ธนาคาร
            10000250-10003999 อาคาร และ ที่ดิน สินทรัพย์อื่นๆ
            10004000-10009999 ลูกหนี้ทั่วไป
            10010000-10099999 วอ.แพทย์
            10100000-10299999 อ.สำนักงาน

            20000001-20199999 เจ้าหนี้ เงินกู้

            30000001-34999999 ทุน ผู้ร่วมทุน

            40000002-40000249 ดอกเบี้ยรับธนาคาร
            40000250-49999999 รายได้อื่นๆ
	    */
		    
		    while($grow = mysqli_fetch_array($dgroup))
		    {
			    echo "<option value='";
			    echo $grow['ac_no'];
			    echo "'>";
			    echo $grow['name']."</option>";
		    }
		    ?>
	    </select>
	    </td></tr>
	    <tr><td style="text-align: right;" >ราคา*</td><td style="text-align: right;" > 
	    <input type="number" min=0 step="0.01" name="price" class="required" > บาท
	    </td>
	    </tr>
	    <tr><td style="text-align: right;" >รายละเอียด</td><td style="text-align: center;" ><input type="text" name="detail"  class="required" >
	    </td>
	    </tr>
	    </table>
	</tr>
	<tr>
	<td style="width: 100%; vertical-align: top; background-color: rgb(255, 255, 204);">
		<input type="submit" name="save" value="บันทึก">
	</td>
	</tr>
	</table>
	</form>	
	<br><div class="smalltextbox">
	ในการลงบัญชี ต้อง <a HREF="acname.php" onClick="return popup(this, 'notes','800','600','yes')" >กำหนดชื่อในการลงบัญชี</a> ก่อน นะครับ</div>
	<!--menu end-->
    </td>
    <td width="160" valign="top">
      <div class="mypage1">
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
