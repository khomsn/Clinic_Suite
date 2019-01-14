<?php 
include '../../config/dbc.php';
page_protect();

$title = "::ลบรายการ ผิดพลาด::";
include '../../main/header.php';
include '../../main/bodyheader.php';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="250" valign="top">
		<?php 
			/*********************** MYACCOUNT MENU ****************************
			This code shows my account menu only to logged in users. 
			Copy this code till END and place it in a new html or php where
			you want to show myaccount options. This is only visible to logged in users
			*******************************************************************/
			if (isset($_SESSION['user_id']))
			{
				include 'drugmenu.php';
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
			<h3 class="titlehdr">ผิดพลาด</h3> <br>
			<h3 class="titlehdr">ไม่สามารถเอาออกจาก บัญชีได้ เพราะ ยังมียอดคงคลัง</h3> <br>
			<h3 class="titlehdr">ไม่สามารถตัดยอดได้  เพราะมียอดคงคลังไม่พอ</h3> <br>
		</td>
</table>
<!--end menu-->
</body></html>
