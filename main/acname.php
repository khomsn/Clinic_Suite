<?php 
include '../login/dbc.php';
page_protect();

if($_POST['add'] == 'เพิ่ม') 
{


if(($_POST['type']!=0) AND (ltrim($_POST['tname']!== '')))
{
	$dtype = mysqli_query($link, "SELECT * FROM acnumber");
	/* 
		1000 สินทรัพย์
		1001 เงินสด
		1002-1020 ธนาคาร
		1021-1030 อาคาร และ ที่ดิน 
		1500-1999 ลูกหนี้ทั่วไป
		10000-15000 วอ.แพทย์
		15001-19999 อ.สำนักงาน
		100000-179999 สินค้า
		180000-189999 วัตถุดิบ
		1000000-1999999 ลูกหนี้ คนไข้ค้างชำระ


		2000 เจ้าหนี้
		2000-2100 เจ้าหนี้ อื่นๆ
		2101-2999 เจ้าหนี้ ซื้อ ยา และ อุปกรณ์

		3000 ทุน
		3001-3499 ทุน ผู้ร่วมทุน
		3500-3999 ทุน คืน กำไร

		4000 รายได้รับ รายได้จากการขาย
		4001-4999 รายได้อื่นๆ

		5000 รายจ่าย
		5002-5020 ธนาคาร ค่าธรรมเนียม
		5021-5550 รายจ่ายต่างๆ
		5551-5998 ว.สำนักงาน ตัดเป็นค่าใช่จ่ายเลย
		50000-55000  ค่าเสื่อมราคา วอ.แพทย์ หักเป็นค่าใช้จ่าย 
		55001-59999 ค่าเสื่อมราคา อ.สำนักงาน หักเป็นค่าใช้จ่าย 
		5999 ตัดยอด

	*/	
	
	if($_POST['type'] ==  1 )
	{  //1. วอ. แพทย์ 	10000-15000 
		//assign account no.
		$ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >=10000 AND ac_no <=15000 ");
		$mmm=10000;
		while($row = mysqli_fetch_array($ac))
		{ if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
		$ac = $mmm + 1;

		// assign insertion pattern สำหรับ 50000-55000  ค่าเสื่อมราคา วอ.แพทย์ หักเป็นค่าใช้จ่าย 
		$acfp = 40000 + $ac;
		$acnfp = "ค่าเสื่อมราคา".$_POST['tname'];
		$sql_insert = "INSERT into `acnumber`
  			(`ac_no`,`name`)
		    VALUES
			('$acfp','$acnfp')";
		// Now insert Patient to "acnumber" table
		mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
	}
	elseif($_POST['type'] ==  2 )
	{  //2.  15001-19999 อ.สำนักงาน
		//assign account no.
		$ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >=15001 AND ac_no <=19999 ");
		$mmm=15000;
		while($row = mysqli_fetch_array($ac))
		{ if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
		$ac = $mmm + 1;
		
		// assign insertion pattern สำหรับ 500000-599999 ค่าเสื่อมราคา อ.สำนักงาน หักเป็นค่าใช้จ่าย 
		$acfp = 40000 + $ac;
		$acnfp = "ค่าเสื่อมราคา".$_POST['tname'];
		$sql_insert = "INSERT into `acnumber`
  			(`ac_no`,`name`)
		    VALUES
			('$acfp','$acnfp')";
		// Now insert Patient to "acnumber" table
		mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
	}
	elseif($_POST['type'] ==  10 )
	{  //10. 5551-5998 ว.สำนักงาน ตัดเป็นค่าใช่จ่ายเลย 
		//assign account no.
		$ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >=5551 AND ac_no <5999 ");
		$mmm=5550;
		while($row = mysqli_fetch_array($ac))
		{ if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
		$ac = $mmm + 1;
	}
	elseif($_POST['type'] ==  3 )
	{  //3. ธนาคาร 1002-1020
		//assign account no.
		$ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >=1002 AND ac_no <1021 ");
		$mmm=1001;
		while($row = mysqli_fetch_array($ac))
		{ if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
		$ac = $mmm + 1;
	}
	elseif($_POST['type'] ==  4 )
	{  /*4. ทุน 3000
		3001-3499 ทุน ผู้ร่วมทุน
		3500-3999 ทุน คืน กำไร*/
		//assign account no.
		$ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >=3000 AND ac_no < 3500 ");
		$mmm=3000;
		while($row = mysqli_fetch_array($ac))
		{ if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
		$ac = $mmm + 1;
		// assign insertion pattern สำหรับ การคืนทุน
		$acfp = 500 + $ac;
		$acnfp = "กำไรจ่าย ".$_POST['tname'];
		$sql_insert = "INSERT into `acnumber`
  			(`ac_no`,`name`)
		    VALUES
			('$acfp','$acnfp')";
		// Now insert Patient to "acnumber" table
		mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
		$name = "ทุน-".$_POST['tname'];
		$_POST['tname'] = $name;
	}
	elseif($_POST['type'] ==  5 )
	{  //5. จ่าย 5000 5021-5550 รายจ่ายต่างๆ
		//assign account no.
		$ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 5021 AND ac_no < 5551 ");
		$mmm=5020;
		while($row = mysqli_fetch_array($ac))
		{ if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
		$ac = $mmm + 1;
		$_POST[tname]=$_POST[tname]."-จ่าย";
	}
	
	elseif($_POST['type'] ==  6 )
	{  //6. รับ 4000 
		//assign account no.
		$ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 4000 AND ac_no < 5000 ");
		$mmm = 4000;
		while($row = mysqli_fetch_array($ac))
		{ if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
		$ac = $mmm + 1;
		$_POST[tname]=$_POST[tname]."-รับ";
	}
	elseif($_POST['type'] ==  7 )
	{  //7. 1500-1999 ลูกหนี้ทั่วไป
		//assign account no.
		$ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 1500 AND ac_no < 2000 ");
		$mmm=1500;
		while($row = mysqli_fetch_array($ac))
		{ if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
		$ac = $mmm + 1;
		$_POST[tname]="ลูกหนี้-".$_POST[tname];
		
	}
	elseif($_POST['type'] ==  8 )
	{  //8. เจ้าหนี้ 2000-2100 เจ้าหนี้ อื่นๆ
		//assign account no.
		$ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 2000 AND ac_no <= 2100 ");
		$mmm=2000;
		while($row = mysqli_fetch_array($ac))
		{ if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
		$ac = $mmm + 1;
		$_POST[tname]="เจ้าหนี้-".$_POST[tname];
	}
	elseif($_POST['type'] ==  9 )
	{  // 9. 1021-1030 อาคาร และ ที่ดิน 
		//assign account no.
		$ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 1021 AND ac_no < 1031 ");
		$mmm=1020;
		while($row = mysqli_fetch_array($ac))
		{ if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
		$ac = $mmm + 1;
	}
	

	// assign insertion pattern
	$sql_insert = "INSERT into `acnumber`
  			(`ac_no`,`name`)
		    VALUES
			('$ac','$_POST[tname]')";

	// Now insert Patient to "acnumber" table
	mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
// go on to other step
}
header("Location: acname.php");  
}

?>

<!DOCTYPE html>
<html>
<head>
<title>ชื่อบัญชี</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
	<script>
	function reloadParentAndClose()
	 {
		 // reload the opener or the parent window
		 window.opener.location.reload();
		 // then close this pop-up window
		 window.close();
	 }
	</script>
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<div style="text-align: center;"> ก่อน กด เพิ่ม บัญชี ต้องแน่ใจว่า ได้เลื่อก ประเภทแล้ว</div>
<div style="text-align: right;"><input type=button value="Close" onClick="reloadParentAndClose();"/></div>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
	<tr><td style="width:260px;vertical-align: top;"><form name="nofac" method="post" action="acname.php" >
			ประเภท<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="1">1. วอ. แพทย์<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="2">2. อ. สำนักงาน<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="10">3. ว. สำนักงาน<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="3">4. ลูกหนี้/เงินฝากธนาคาร<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="4">5. ทุน<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="5">6. จ่าย<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="6">7. รับ<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="7">8. ลูกหนี้<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="8">9. เจ้าหนี้/เงินกู้ธนาคาร<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="9">10. อาคาร ที่ดิน<br>
			<br>ชนิด	Ac_No ตามเลขนำหน้า<br> 
			<br>1. สินทรัพย์ 2. เจ้าหนี้ 
			<br>3. ทุน 4. รายได้
			<br>5. รายจ่าย
			
		</td>
		<td>
				<table border='1' style='text-align: center; margin-left: auto; margin-right: auto;' >
				<tr><th>ชื่อ</th><th>Ac_No</th></tr>
				<tr><th width=150><input type="text" name="tname"></th><th><input type="submit" name="add" value="เพิ่ม"></th></tr>
			<?php	
				$dsort = "SELECT * FROM `acnumber` WHERE  (ac_no <= '2100') OR (ac_no > '9999' AND ac_no < '20000') OR  (ac_no > '169999' AND ac_no < '180000') OR (ac_no > '3000' AND ac_no < '6000')  ORDER BY `ac_no` ASC ";
				$dtype = mysqli_query($link, $dsort) or die("SORT Failed:" . mysqli_error($link));
				//$dtype = mysqli_query($link, "SELECT * FROM acnumber");
						// keeps getting the next row until there are no more to get
				while($row = mysqli_fetch_array($dtype))
				 {
					// Print out the contents of each row into a table
					echo "<tr><th >"; 
					echo $row['name'];
					echo "</th><th>";
					echo $row['ac_no'];
					echo "</th></tr>";
				}	 
				echo "</table>";
			?>
			</td>
		</form>
	</tr>
</table>
<!--end menu-->
</body></html>
