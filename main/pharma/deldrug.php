<?php 
include '../../config/dbc.php';
page_protect();
$err = array();

$sql_create = "CREATE TABLE  IF NOT EXISTS `deleted_drug` (
  `id` int(11) NOT NULL,
  `dname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `dgname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `ac_no` int(11) NOT NULL,
  `dtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bystid` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
mysqli_query($link, $sql_create);

$filter = mysqli_query($link, "select * from drug_id ");		
while ($row = mysqli_fetch_array($filter))
{
    if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
}

$filter = mysqli_query($link, "select * from drug_id WHERE track = '0' AND volume='0' AND min_limit='-1' ORDER BY `dgname` ASC ");

if ($_POST['todo'] == 'กรอง' ) 
{
	if($_POST['type'] != '' AND $_POST['group'] !='' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE typen='$_POST[type]' AND  `groupn` ='$_POST[group]' AND  track = '0' AND volume='0' AND min_limit='-1' ORDER BY `dgname` ASC");	
	}	
	if($_POST['type'] != '' AND $_POST['group'] =='' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE typen='$_POST[type]' AND  track = '0' AND volume='0' AND min_limit='-1'  ORDER BY `dgname` ASC ");	
	}	
	if($_POST['group'] !=''  AND  $_POST['type'] == '' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE  `groupn` ='$_POST[group]' AND  track = '0' AND volume='0' AND min_limit='-1'  ORDER BY `dgname` ASC");	
	}	

}

if($_POST['register'] == 'ลบข้อมูล') 
{ 
	$id = $_POST['drugid'];
	$acno = mysqli_query($link, "SELECT * FROM drug_id WHERE id = $id");
	while ($row = mysqli_fetch_array($acno))
	{
		$dacno = $row['ac_no'];
		$sety = $row['seti'];
		$vol = $row['volume'];
		// info for loging
		$dname = $row['dname'];
		$dgname = $row['dgname'];
		$size = $row['size'];
	}
	
	if($vol > '0')
	{
		$err[] = "ผิดพลาด";
		$err[] = "ไม่สามารถเอาออกจาก บัญชีได้ เพราะ ยังมียอดคงคลัง";
		goto Thisiserror;
	}

	if ($vol =='0')
	{

        $sql_del = "DELETE FROM drug_id WHERE id = $id";

        // Now delete drug information from drug_id table
        mysqli_query($link, $sql_del) or $err[]=("Deletion Failed:" . mysqli_error($link));

        if($sety == 1)
        {
            $setid = "set_drug_".$id;
            $sql_drop ="DROP TABLE `$setid`" ;
            mysqli_query($link, $sql_drop) or $err[]=("Insertion Failed:" . mysqli_error($link));
        }

        $tid = "drug_".$id;
        $ftid = mysqli_query($link, "select * from $tid");
        while($row = mysqli_fetch_array($ftid))
        {
            if($row['price']!=0)
            {
                goto NextStep;
            }
        }
        //if have price don't drop table *use in account system ในการคำนวน กำไร
        $sql_drop ="DROP TABLE `$tid`" ;
        mysqli_query($link, $sql_drop) or $err[]=("Insertion Failed:" . mysqli_error($link));
        // Delete Ac No
        NextStep:
        $sql_del = "DELETE FROM acnumber WHERE ac_no = $dacno";
        // Now remove drug information table
        mysqli_query($link, $sql_del) or $err[]=("Insertion Failed:" . mysqli_error($link));
        
        //loging del item
        $sql_insert = "INSERT into `deleted_drug` 
                (`id`,`dname`,`dgname`, `size`, `ac_no`,`bystid` ) 
                VALUES 
                ('$id','$dname','$dgname','$size','$dacno','$_SESSION[staff_id]')";
        // Now insert 
        mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));
        // go on to other step
        
        header("Location: deldrug.php");  

	} 
}
Thisiserror:

$title = "::ห้องยา::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
include '../../main/bodyheader.php';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="170" valign="top"><div class="pos_l_fix">
		<?php 
			if (isset($_SESSION['user_id']))
			{
				include 'drugmenu.php';
			} 
		?></div></td><td>
        <p>
        <?php
        /******************** ERROR MESSAGES*************************************************
        This code is to show error messages 
        **************************************************************************/
        if(!empty($err))
        {
            echo "<div class=\"msg\">";
            foreach ($err as $e) {echo "$e <br>";}
            echo "</div>";
        }
        /******************************* END ********************************/	  
        ?></p>
 			<h3 class="titlehdr">ลบ ทะเบียนยา และ ผลิตภัณฑ์</h3>
			<form method="post" action="deldrug.php" name="regForm" id="regForm">
						<div align="center">
						<?php	
								echo "<table class='TFtable' border='1' style='text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76); width: 70%;'>";
								echo "<tr><th>เลือก</th><th>ชื่อ</th><th>ชื่อสามัญ</th><th>ขนาด</th><th>คงคลัง</th></tr>";
								while($row = mysqli_fetch_array($filter))
								 {
										// Print out the contents of each row into a table
										echo "<tr><th>";
										echo "<input type='radio' name='drugid' value='".$row['id']."'/>";
										echo "</th><th>"; 
										echo $row['dname'];
										echo "</th><th>"; 
										echo $row['dgname'];
										echo "</th><th>"; 
										echo $row['size'];
										echo "</th><th>"; 
										echo $row['volume'];
										echo "</th></tr>";
								} 
								echo "</table>";
						?><br><input name="register" value="ลบข้อมูล" type="submit"></div>
		</td><td style="width:160px;vertical-align: top;"">
				<div class="pos_r_fix" style="text-align: right;">
				<br><br><br>
						<?php	
							$dtype = mysqli_query($link, "SELECT name FROM drug_type");
							$dgroup = mysqli_query($link, "SELECT name FROM drug_group");
						?>
							ประเภท&nbsp;
							<select name="type">
								<option value="" selected></option>
								<?php while($trow = mysqli_fetch_array($dtype))
								{
									echo "<option value=\"";
									echo $trow['name'];
									echo "\">";
									echo $trow['name']."</option>";
								}
								?>
							</select>
							&nbsp; &nbsp; &nbsp; 
							<br><br><br>
							กลุ่ม
							<select name="group">
								<option value="" selected></option>
								<?php while($grow = mysqli_fetch_array($dgroup))
								{
									echo "<option value=\"";
									echo $grow['name'];
									echo "\">";
									echo $grow['name']."</option>";
								}
								?>
							</select>&nbsp; &nbsp; &nbsp; &nbsp; 
							<br><br><br>
							<input type='submit' name='todo' value='กรอง' >&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
				</div>	
			</form>
		</td>
	</tr>
</table>
</body></html>
