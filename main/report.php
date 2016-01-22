<?php 
include '../login/dbc.php';
page_protect();
?>
<!DOCTYPE html>
<html>
<head>
<title>Report</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
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
<table width="100%">
  <tr>
    <td width=130px>
      <div class="menur">
		      <?php 
			      /*********************** MYACCOUNT MENU ****************************
			      This code shows my account menu only to logged in users. 
			      Copy this code till END and place it in a new html or php where
			      you want to show myaccount options. This is only visible to logged in users
			      *******************************************************************/
			      if (isset($_SESSION['user_id']))
			      {
				      include 'reportmenu.php';
			      } 
		      /*******************************END**************************/
		      ?>
      </div>
    </td>
    <td>
		      <h3 class="titlehdr">Report Document</h3>
		      <p>รายงานทั่วไปของ คลินิก<p>
		      ประกอบด้วย
			  <ul>
			  <li>รายงานการใช้ยาที่ต้องติดตามการใช้และทำรายงานยาควบคุม</li>
			  <li>รายงานจำนวนผู้ป่วยในรอบปี แบ่งเป็น จำนวน Visit, จำนวนผู้ป่วยทั้งหมด, จำนวนผู้ป่วยใหม่</li>
			  <li>รายงาน ราคา ซื้อ ยาและเวชภัณฑ์</li>
			  </ul>
   </td>
    <td width=130px>
    <?php include 'reportrmenu.php';?>
    </td>
  </tr>
</table>
</body></html>