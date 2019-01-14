<?php 
include '../../config/dbc.php';
page_protect();

if($_POST['add'] == 'เพิ่ม') 
{
    if(($_POST['type']!=0) AND (ltrim($_POST['tname']!== '')))
    {
        $dtype = mysqli_query($link, "SELECT * FROM acnumber");
        /* 
            10000000 สินทรัพย์
            10000001 เงินสด
            10000002-10000249 ธนาคาร
            10000250-10003999 อาคาร และ ที่ดิน สินทรัพย์อื่นๆ
            10004000-10009999 ลูกหนี้ทั่วไป
            10010000-10099999 วอ.แพทย์
            10100000-10299999 อ.สำนักงาน
            10300000-10699999 สินค้า 10300000 ตัดยอด//start with 10300000 + drug_id
            10700000-10999999 วัตถุดิบ 10700000 ตัดยอด //start with 10700000 + rawmat_id
            11000001-19999999 ลูกหนี้ คนไข้ค้างชำระ  //start with 11000000 + pt_id


            20000000 เจ้าหนี้
            20000001-20199999 เจ้าหนี้ เงินกู้
            21000001-29999999 เจ้าหนี้ ซื้อ ยา และ อุปกรณ์ //start with 21000000 + sp_id

            30000000 ทุน
            30000001-34999999 ทุน ผู้ร่วมทุน
            35000001-39999999 ทุน คืน กำไร

            40000000 รายได้รับ
            40000001 รายได้จากการขาย
            40000002-40000249 ดอกเบี้ยรับธนาคาร
            40000250-49999999 รายได้อื่นๆ

            50000000 รายจ่าย
            50000001 หนี้สูญ-จ่าย
            50000002-50000249 ธนาคาร ค่าธรรมเนียม
            50000250-50002999 รายจ่ายต่างๆ
            50003000-50009999 ว.สำนักงาน ตัดเป็นค่าใช่จ่ายเลย
            50010000-50099999  ค่าเสื่อมราคา วอ.แพทย์ หักเป็นค่าใช้จ่าย 
            50100000-50299999 ค่าเสื่อมราคา อ.สำนักงาน หักเป็นค่าใช้จ่าย 
            51000000-59999998 เงินเดือน จ่าย 51000000 for non staff payment //start with 51000000 + staff_id
            59999999 ตัดยอด
        */	
        
        if($_POST['type'] ==  1 )
        {  //1. 10010000-10099999 วอ.แพทย์ 
            //assign account no.
            $ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >=10010000 AND ac_no < 10099999 ORDER BY ac_no ASC");
            $mmm=10009999;
            while($row = mysqli_fetch_array($ac))
            { if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
            $ac = $mmm + 1;

            // assign insertion pattern สำหรับ 50010000-50099999  ค่าเสื่อมราคา วอ.แพทย์ หักเป็นค่าใช้จ่าย 
            $acfp = 40000000 + $ac;
            $acnfp = "ค่าเสื่อมราคา-".$_POST['tname'];
            $sql_insert = "INSERT into `acnumber`
                (`ac_no`,`name`)
                VALUES
                ('$acfp','$acnfp')";
            // Now insert Patient to "acnumber" table
            mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
        }
        elseif($_POST['type'] ==  2 )
        {  //2.  10100000-10299999 อ.สำนักงาน
            //assign account no.
            $ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >=10100000 AND ac_no < 10299999 ");
            $mmm=10099999;
            while($row = mysqli_fetch_array($ac))
            { if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
            $ac = $mmm + 1;
            
            // assign insertion pattern สำหรับ 50100000-50299999 ค่าเสื่อมราคา อ.สำนักงาน หักเป็นค่าใช้จ่าย 
            $acfp = 40000000 + $ac;
            $acnfp = "ค่าเสื่อมราคา-".$_POST['tname'];
            $sql_insert = "INSERT into `acnumber`
                (`ac_no`,`name`)
                VALUES
                ('$acfp','$acnfp')";
            // Now insert Patient to "acnumber" table
            mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
        }
        elseif($_POST['type'] ==  10 )
        {  //10. 50003000-50009999 ว.สำนักงาน ตัดเป็นค่าใช่จ่ายเลย 
            //assign account no.
            $ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >=50003000 AND ac_no < 50009999 ");
            $mmm=50002999;
            while($row = mysqli_fetch_array($ac))
            { if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
            $ac = $mmm + 1;
        }
        elseif($_POST['type'] ==  3 )
        {  //3. 10000002-10000249 ธนาคาร
            //assign account no.
            $ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >=10000002 AND ac_no < 10000249 ");
            $mmm=10000001;
            while($row = mysqli_fetch_array($ac))
            { if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
            $ac = $mmm + 1;
            // assign insertion pattern สำหรับ 40000002-40000249 ดอกเบี้ยรับธนาคาร 
            $acfp = 30000000 + $ac;
            $acnfp = "ดอกเบี้ยรับ-".$_POST['tname'];
            $sql_insert = "INSERT into `acnumber`
                (`ac_no`,`name`)
                VALUES
                ('$acfp','$acnfp')";
            // Now insert Patient to "acnumber" table
            mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
            // assign insertion pattern สำหรับ 50000002-50000249 ธนาคาร ค่าธรรมเนียม 
            $acfp = 40000000 + $ac;
            $acnfp = "จ่าย-Fee-".$_POST['tname'];
            $sql_insert = "INSERT into `acnumber`
                (`ac_no`,`name`)
                VALUES
                ('$acfp','$acnfp')";
            // Now insert Patient to "acnumber" table
            mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
        }
        elseif($_POST['type'] ==  4 )
        {  /*4. ทุน 30000000
            30000001-34999999 ทุน ผู้ร่วมทุน
            35000001-39999999 ทุน คืน กำไร*/
            //assign account no.
            $ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >=30000001 AND ac_no < 34999999 ");
            $mmm=30000000;
            while($row = mysqli_fetch_array($ac))
            { if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
            $ac = $mmm + 1;
            // assign insertion pattern สำหรับ การคืนทุน
            $acfp = 5000000 + $ac;
            $acnfp = "ปันผลจ่าย-".$_POST['tname'];
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
        {  //5. 50000250-50002999 รายจ่ายต่างๆ
            //assign account no.
            $ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 50000250 AND ac_no < 50002999 ");
            $mmm=50000249;
            while($row = mysqli_fetch_array($ac))
            { if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
            $ac = $mmm + 1;
            $_POST[tname]=$_POST[tname]."-จ่าย";
        }
        
        elseif($_POST['type'] ==  6 )
        {  //6. 40000250-49999999 รายได้อื่นๆ
            //assign account no.
            $ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 40000250 AND ac_no < 49999999 ");
            $mmm = 40000249;
            while($row = mysqli_fetch_array($ac))
            { if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
            $ac = $mmm + 1;
            $_POST[tname]=$_POST[tname]."-รับ";
        }
        elseif($_POST['type'] ==  7 )
        {  //7. 10004000-10009999 ลูกหนี้ทั่วไป
            //assign account no.
            $ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 10004000 AND ac_no < 10009999 ");
            $mmm=10003999;
            while($row = mysqli_fetch_array($ac))
            { if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
            $ac = $mmm + 1;
            $_POST[tname]="ลูกหนี้-".$_POST[tname];
            
        }
        elseif($_POST['type'] ==  8 )
        {  //8. 20000001-20199999 เจ้าหนี้ อื่นๆ
            //assign account no.
            $ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 20000001 AND ac_no < 20199999 ");
            $mmm=20000000;
            while($row = mysqli_fetch_array($ac))
            { if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
            $ac = $mmm + 1;
            $_POST[tname]="เจ้าหนี้-".$_POST[tname];
        }
        elseif($_POST['type'] ==  9 )
        {  // 9. 10000250-10003999 อาคาร และ ที่ดิน สินทรัพย์อื่นๆ 
            //assign account no.
            $ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 10000250 AND ac_no < 10003999 ");
            $mmm=10000249;
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
    header("Location: ../../main/account/acname.php");  
}
$title = "::ชื่อบัญชี::";
include '../../main/header.php';
include '../../libs/reloadopener.php';
?>
</head>
<body>
<div style="text-align: center;"> ก่อน กด เพิ่ม บัญชี ต้องแน่ใจว่า ได้เลื่อก ประเภทแล้ว</div>
<div style="text-align: right;"><input type=button value="Close" onClick="reloadParentAndClose();"/></div>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
	<tr><td style="width:260px;vertical-align: top;"><form name="nofac" method="post" action="acname.php" >
			ประเภท<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="1">1. วัสดุ+อุปกรณ์ การแพทย์<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="2">2. อุปกรณ์ สำนักงาน<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="10">3. วัสดุ สำนักงาน<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="3">4. เงินฝาก/ธนาคาร<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="4">5. ทุนส่วนของเจ้าของ<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="5">6. รายจ่าย<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="6">7. รับรายได้<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="7">8. ลูกหนี้/ทั่วไป<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="8">9. เจ้าหนี้/เงินกู้ธนาคาร<br>
			<INPUT TYPE=RADIO NAME="type" VALUE="9">10. อาคาร ที่ดิน ทรัพย์อื่นๆ<br>
			<br>ชนิด	Ac_No ตามเลขนำหน้า<br> 
			<br>1. สินทรัพย์ 2. หนี้สิน 
			<br>3. ทุนส่วนของเจ้าของ 4. รายได้
			<br>5. รายจ่าย
		</td><td><table border='1' style='text-align: center; margin-left: auto; margin-right: auto;' >
				<tr><th>ชื่อ</th><th>Ac_No</th></tr>
				<tr><th width=150><input type="text" name="tname"></th><th><input type="submit" name="add" value="เพิ่ม"></th></tr>
			<?php	
				$dsort = "SELECT * FROM `acnumber` WHERE  ((ac_no >= '10000002') AND (ac_no <= '10299999')) OR ((ac_no >= '20000001') AND (ac_no <= '20199999')) OR ((ac_no >= '30000001') AND (ac_no <= '34999999')) OR ((ac_no >= '40000250') AND (ac_no <= '49999999')) OR ((ac_no >= '50000250') AND (ac_no <= '50009999')) OR ((ac_no >= '51000000') AND (ac_no <= '59999998')) ORDER BY `ac_no` ASC ";
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
			?></td></form>
	</tr>
</table>
</body></html>
