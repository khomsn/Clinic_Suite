<?php 
include '../login/dbc.php';
page_protect();
$pdir = AVATAR_PATH;

if($_POST['found'] == 'ส่งตรวจ')
{
	$_SESSION['Patient_id'] = $_POST['ptid'];
	//check for opdcard in case of not exist create it.
	include '../libs/pt_table.php';
	// go on to other step
	header("Location: pt_to_doctor.php");  

}

elseif($_POST['found'] == 'แก้ไขข้อมูล')
{
	$_SESSION['Patient_id'] = $_POST['ptid'];
	// go on to other step
	header("Location: patientupdate.php");  

}

elseif($_POST['found'] == 'OPD Card')
{
	$_SESSION['patdesk'] = $_POST['ptid'];
	// go on to other step
	header("Location: opdpageview.php");  

}
elseif($_POST['found'] == 'ใบเสร็จรับเงิน')
{
	$_SESSION['patdesk'] = $_POST['ptid'];
	// go on to other step
	header("Location: recview.php");  

}
elseif($_POST['found'] == 'ชำระหนี้')
{
	$_SESSION['patdesk'] = $_POST['ptid'];
	// go on to other step
	header("Location: payfordeb.php");  

}

?>

<!DOCTYPE html>
<html><head>
<title>ค้นหารายชื่อผู้ป่วย</title>
<meta content="text/html; charset=utf8" http-equiv="content-type">
<script language="JavaScript" type="text/javascript" src="../public/js/jquery-1.3.2.min.js"></script>
<link rel="stylesheet" href="../public/css/styles.css">
<?php 
include '../libs/autoname.php';
include '../libs/autojatz.php';
?>

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
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr><td width="160" valign="top"><div class="pos_l_fix">
		<?php 
			/*********************** MYACCOUNT MENU ****************************
			This code shows my account menu only to logged in users. 
			Copy this code till END and place it in a new html or php where
			you want to show myaccount options. This is only visible to logged in users
			*******************************************************************/
			if (isset($_SESSION['user_id']))
			{
				include 'clinicmenu.php';
			} 
		/*******************************END**************************/
		?></div>
		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
			<?php
			if($_POST['search'] =='ค้นหา')
			{
			?>
			<div style="text-align: center;">
			<a HREF="search_pt.php" ><input value="ค้นหาใหม่" type="submit"></a>
			</div>
			<?php
			}
			?>
			<h3 class="titlehdr">ค้นหารายชื่อผู้ป่วย</h3>
			<form action="search_pt.php" method="post" name="searchForm" id="searchForm">
			<?php
			if($_POST['search'] =='')
			{
			?>
			
				<div style="text-align: center;">
					<table width="100%" border="1" cellspacing="0" cellpadding="5" class="main">
					<tr><td width="100%" valign="top">
							เลขทะเบียน:<input maxlength="5" size="7" name="ptid">
							ชื่อ:<input maxlength="15" size="15" tabindex="1" name="fname" id="fname" autofocus>&nbsp;
							นามสกุล:<input maxlength="20" size="20" tabindex="2" name="lname" id="lname">
							<hr style="width: 50%;">
							เลขที่บัตรประชาชน<input maxlength="13" size="13" tabindex="2" name="id" id="cid">
							<hr style="width: 30%;">
							โทรศัพท์บ้าน:<input maxlength="12" size="12" tabindex="3" name="htel" value="02xxx" id="htel">
							โทรศัพท์มือถือ <input maxlength="12" size="12" tabindex="4" name="mtel" value="08xxx" id="mtel">
							<hr style="width: 65%;">
							บ้านเลขที่<input tabindex="6" name="address1" type="text" size="5">หมู่ที่<input tabindex="7" name="address2" type="text" size="3"> ตำบล <input tabindex="8" name="address3" type="text" id="tname" > อำเภอ <input tabindex="14" name="address4" type="text" id="aname"> จังหวัด <input tabindex="15" name="address5" type="text" id="jname">
							<hr style="width: 85%;">
							<input tabindex="5" value="ค้นหา" name="search" type="submit">
						</td>
					</tr>
					</table>
				</div>
			<?php } ?>	
			</form>
<form action="search_pt.php" method="post" name="submitsearchForm" id="submitsearchForm">
<?php

	if($_POST['search'] == 'ค้นหา')  
	{
		$result = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE fname='$_POST[fname]' or lname='$_POST[lname]' or hometel='$_POST[htel]' or mobile='$_POST[mtel]' OR id='$_POST[ptid]' or ctz_id='$_POST[id]' and ctz_id!=0 ");
	
		if (ltrim($_POST['fname']) !== '' and ltrim($_POST['lname']) !== '')
		{
		  $result = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE fname='$_POST[fname]' AND lname='$_POST[lname]'");
		}
		if (ltrim($_POST['fname']) !== '' and ltrim($_POST['lname']) !== '' and ltrim($_POST['id']) !== '')
		{
		  $result = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE fname='$_POST[fname]' AND lname='$_POST[lname]' AND ctz_id='$_POST[id]'");
		}
		if (ltrim($_POST['ptid']) !== '')
		{
		  $result = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE  id='$_POST[ptid]' ");
		}
		
		if (ltrim($_POST['address1']) == '') $ft1 = 1;
		else $ft1 = "address1 = '$_POST[address1]'";
		if (ltrim($_POST['address2']) == '') $ft2 = 1;
		else $ft2 = "address2 = '$_POST[address2]'";
		if (ltrim($_POST['address3']) == '') $ft3 = 1;
		else $ft3 = "address3 = '$_POST[address3]'";
		if (ltrim($_POST['address4']) == '') $ft4 = 1;
		else $ft4 = "address4 = '$_POST[address4]'";
		if (ltrim($_POST['address5']) == '') $ft5 = 1;
		else $ft5 = "address5 = '$_POST[address5]'";
		if(($ft1!=1) OR ($ft2!=1) OR ($ft3!=1) OR ($ft4!=1) OR ($ft5!=1))
		{
		  $result = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE  ($ft1) AND ($ft2) AND  ($ft3) AND  ($ft4) AND ($ft5)" );
		}

		echo "<table border='1'>";
		echo "<tr> <th>เลือก</th><th>เลขทะเบียน</th><th>ยศ</th><th>ชื่อ</th><th>นามสกุล</th><th>เลขบัตรประชาขน</th><th>มือถือ</th><th></th><th>ที่อยู่</th></tr>";
		// keeps getting the next row until there are no more to get
		while($row = mysqli_fetch_array($result))
		  {
				// Print out the contents of each row into a table
				echo "<tr><th>";
?>							
				<input type="radio" name="ptid" value="<?php	echo $row['id']; ?>" />
<?php
				echo "</th><th>"; 
				echo $y = $row['id'];
				echo "</th><th>"; 
				echo $row['prefix'];
				echo "</th><th width=150>"; 
				echo $row['fname'];
				echo "</th><th width=150>"; 
				echo $row['lname'];
				echo "</th><th width=150>"; 
				echo $row['ctz_id'];
				echo "</th><th width=150>"; 
				echo $row['mobile'];
				echo "</th><th>";
?><div class="avatar">
<img src="<?php $avatar = $pdir. "pt_".$y.".jpg";
echo $avatar; ?>" width="44" height="44" /></div>
<?php				echo "</th><th>";
				echo $row['address1']." ม.".$row['address2']." ต.".$row['address3']." อ.".$row['address4'];
				echo "</th></tr>";
} 
echo "</table>";
//}
?>

<br>
<br>
<?php 
if($y)
{
?>
<input name="found" value="ส่งตรวจ" type="submit">&nbsp;&nbsp;&nbsp;<input name="found" value="แก้ไขข้อมูล" type="submit">&nbsp;&nbsp;&nbsp;<input name="found" value="OPD Card" type="submit">&nbsp;&nbsp;&nbsp;<input name="found" value="ใบเสร็จรับเงิน" type="submit">&nbsp;&nbsp;&nbsp;<input name="found" value="ชำระหนี้" type="submit">
<?php 
}
else
{
echo "<a href='../main/PIDregister.php'>ลงทะเบียนผู้ป่วยใหม่</a>";
}
}
?>
</form>
<!--menu end-->
		</td>
		<td width="60"></td>
	</tr>
</table>
<!--end menu-->
</body>
</html>