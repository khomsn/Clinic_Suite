<?php 
include '../../config/dbc.php';
page_protect();

if($_POST['register'] == 'ดูข้อมูล') 
{ 

// pass drug-id to other page
$_SESSION['spid'] = $_POST['spid'];
// go on to other step
header("Location: updatesp.php");  

}

$title = "::Supplier Lists::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
include '../../main/bodyheader.php';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="180" valign="top"><div class="pos_l_fix">
		<?php 
			/*********************** MYACCOUNT MENU ****************************
			This code shows my account menu only to logged in users. 
			Copy this code till END and place it in a new html or php where
			you want to show myaccount options. This is only visible to logged in users
			*******************************************************************/
			if (isset($_SESSION['user_id']))
			{
				include 'spmenu.php';
			} 
		/*******************************END**************************/
		?></div>
	</td>
	<td><h3 class="titlehdr">ผู้จำหน่าย ยา ผลิตภัณฑ์ และวัตถุดิบ</h3>
	<form method="post" action="listsp.php" name="regForm" id="regForm">
	  <table style="text-align: center; width: 100%; height: 413px;" border="0" cellpadding="2" cellspacing="2">
	  <tbody><tr><td style="width: 646px; vertical-align: middle; ">
			<div style="text-align: center;">
			 <?php	
				  $dtype = mysqli_query($link, "SELECT * FROM supplier ORDER BY `name` ASC  ");
					  echo "<table border='1' style='text-align: center; margin-left: auto; margin-right: auto;' class='TFtable'>";
					  echo "<tr><th>เลือก</th><th>ชื่อ</th><th>โทรศัพท์</th><th>ตัวแทน</th><th>เบอร์ติดต่อ</th><th>การชำระเงิน</th></tr>";
					  while($row = mysqli_fetch_array($dtype))
					    {
							  // Print out the contents of each row into a table
							  echo "<tr><th>";
			  ?>							
							  <input type="radio" name="spid" value="<?php	echo $row['id']; ?>" />
			  <?php
							  echo "</th><th width=250>"; 
							  echo $row['name'];
							  echo "</th><th width=80>"; 
							  echo $row['tel'];
							  echo "</th><th width=150>"; 
							  echo $row['agent'];
							  echo "</th><th width=80>"; 
							  echo $row['mobile'];
							  echo "</th><th width=250>"; 
							  echo $row['paydetail'];
							  echo "</th></tr>";
					  } 
					  echo "</table>";
			  ?><br></div>
		      </td>
		  </tr>
		  <tr>
		  <td><br>
			  <br>
			  <div style="text-align: center;"><input name="register" value="ดูข้อมูล" type="submit"></div>
		  </td></tr>
	  </tbody></table>
	<br>
	</form>
	</td><td width="160"></td>
  </tr>
</table>
<!--end menu-->
</body></html>
