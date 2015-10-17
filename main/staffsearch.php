<?php 
include '../login/dbc.php';
page_protect();


if($_SESSION['user_accode']%13 != 0)
// go on to other step
header("Location: staff.php");  

if($_POST['found'] == 'แก้ไขข้อมูล')
{
	$_SESSION['Staff_id'] = $_POST['ptid'];
	// go on to other step
	header("Location: staffupdate.php");  

}

?>

<!DOCTYPE html>
<html><head>
<meta content="text/html; charset=UTF-8" http-equiv="content-type"><title>ค้นหารายชื่อ Staff</title>
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
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="160" valign="top">
		<?php 
			/*********************** MYACCOUNT MENU ****************************
			This code shows my account menu only to logged in users. 
			Copy this code till END and place it in a new html or php where
			you want to show myaccount options. This is only visible to logged in users
			*******************************************************************/
			if (isset($_SESSION['user_id']))
			{
			include '../login/menu.php';
			} 
		/*******************************END**************************/
		?>
			  <p>&nbsp; </p>
			  <p>&nbsp;</p>
			  <p>&nbsp;</p>
			  <p>&nbsp;</p>
		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
<!--menu-->
		<td>
			<?php
			if($_POST['search'] =='ค้นหา')
			{
			?>
			<div style="text-align: center;">
			<a HREF="staffsearch.php" ><input value="ค้นหาใหม่" type="submit"></a>
			</div>
			<?php
			}
			?>
			<h3 class="titlehdr">ค้นหารายชื่อ</h3>
			<form action="staffsearch.php" method="post" name="searchForm" id="searchForm">
			<?php
			if($_POST['search'] =='')
			{
			?>
			
				<div style="text-align: center;">
					<table width="100%" border="0" cellspacing="10" cellpadding="15" class="main">
					<tr><td valign="top">
							เลขพนักงาน:<input maxlength="5" size="7" name="ptid">&nbsp;
							ชื่อ:<input maxlength="15" size="15" tabindex="1" name="F_Name">&nbsp;
							นามสกุล:<input maxlength="20" size="20" tabindex="2" name="L_Name">
					</td></tr>
					<tr><td>
							เลขที่บัตรประชาชน<input maxlength="13" size="30" tabindex="2" name="id">
					</td></tr>
					<tr><td>
							โทรศัพท์บ้าน:<input maxlength="12" size="12" tabindex="3" name="htel" value="02xxx">
							โทรศัพท์มือถือ <input maxlength="12" size="12" tabindex="4" name="mtel" value="08xxx">
							<br>
							<br>
							<br>
							<input tabindex="5" value="ค้นหา" name="search" type="submit">
						</td>
					</tr>
					</table>
				</div>
			<?php } ?>	
			</form>
			<form action="staffsearch.php" method="post" name="submitsearchForm" id="submitsearchForm">

			<?php
			
				if($_POST['search'] == 'ค้นหา')  
				{
					$result = mysqli_query($link, "SELECT * FROM staff WHERE F_Name='$_POST[F_Name]' or L_Name='$_POST[L_Name]' or h_tel='$_POST[htel]' or mobile='$_POST[mtel]' OR ID='$_POST[ptid]' or ctz_id='$_POST[id]' and ctz_id!=0 ");
								echo "<table border='1'>";
								echo "<tr> <th>เลือก</th><th>เลขทะเบียน</th> <th>ชื่อ</th> <th>นามสกุล</th></tr>";
								// keeps getting the next row until there are no more to get
								while($row = mysqli_fetch_array($result))
								 {
										// Print out the contents of each row into a table
										echo "<tr><th>";
			?>							
										<input type="radio" name="ptid" value="<?php	echo $row['ID']; ?>" />
			<?php
										echo "</th><th>"; 
										echo $row['ID'];
										echo "</th><th width=150>"; 
										echo $row['F_Name'];
										echo "</th><th width=150>"; 
										echo $row['L_Name'];
										echo "</th></tr>";
								} 
								echo "</table>";
				//}
			?>

			<br>
			<br>
			<input name="found" value="แก้ไขข้อมูล" type="submit">
			<br>  <?php } ?>
			</form>
<!--menu end-->
		</td>
		<td>
			  <table style="text-align: left;" border="1" cellpadding="5" cellspacing="5">
			      <tbody>
				    <tr>
				      <td style="text-align: right;">
				      <?php 
				      if($_SESSION['user_accode']%13 == 0)
				      {
				      echo "<a href='../main/staffreg.php'>";
				      ?>
				      <img style="border: 0px solid ; width: 120px; height: 120px;" alt="ลงทะเบียนพนักงาน"
					  src="../image/staffadd.jpeg">
				      <?php echo  "</a>";?>
				    </td></tr>
				    <tr><td>
				    </td></tr>
				    <tr><td style="text-align: right;">
				      <?php echo "<a href='../main/staffsearch.php'>"; ?>
				      <img style="border: 0px solid ; width: 120px; height: 120px;" alt="ค้นทะเบียนพนักงาน"
					  src="../image/user-management_3.jpg">
					  <?php 
					  echo "</a>";
				      }
				      ?></td>
				    </tr>
			      </tbody>
			    </table>
		</td>
	</tr>
</table>
<!--end menu-->
</body>
</html>