<?php 
include '../../config/dbc.php';
page_protect();

$sql_create = "CREATE TABLE IF NOT EXISTS `discount` (
  `ctmid` mediumint(9) NOT NULL,
  `percent` tinyint(4) NOT NULL,
  PRIMARY KEY(`ctmid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

mysqli_query($link, $sql_create);

$id = $_SESSION['patcash'];

if($_POST['add'] == 'เพิ่ม') 
{ 
	// assign insertion pattern
	$dgroup = mysqli_query($link, "SELECT * FROM discount WHERE ctmid = $id ");
	$tyes = 0;
	while($row = mysqli_fetch_array($dgroup))
	{
		if($row['ctmid']==$id) $tyes = 1;
	}	
	// keeps getting the next row until there are no more to get
	if($tyes == 0)
	{
        $sql_insert = "INSERT into `discount` (`ctmid`,`percent`)  VALUES ('$id', '$_POST[percent]' )";
        mysqli_query($link, $sql_insert);
	}
	else
	{
		mysqli_query($link, " UPDATE `discount` SET `percent` = '$_POST[percent]' WHERE `ctmid` = '$id' ");
	}
}


if($_POST['del'] == 'ลบ' ) 
{ 
	$sql_del = "DELETE FROM discount WHERE ctmid = $id";
	mysqli_query($link, $sql_del);
}

$title = "::กำหนดส่วนลด::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" href=\"../../jscss/css/table_alt_color.css\">";
echo "</head><body>";

?>
<div id="content">
<table width="350px" border="0" cellspacing="0" cellpadding="5" class="main">
	<tr>
		<td><form name="groupofd" method="post" action="discount.php">
				<table border='1' style='text-align: center; margin-left: auto; margin-right: auto;' >
				<tr><th>ชื่อ</th><th>%</th><th>เพิ่ม</th><th>ลบ</th></tr>
				<tr><th width=150><?php 
				$dgroup = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE id = $id ");
                // keeps getting the next row until there are no more to get
				while($row = mysqli_fetch_array($dgroup))
				{
					echo $row['prefix'].'&nbsp;'.$row['fname'].'&nbsp;&nbsp;'.$row['lname'];
				}
				?>
				</th><th><input min=0 max=100 step=1 type="number" size='4' class="typenumber" name="percent" value="<?php 
				$dct = mysqli_query($link, "SELECT * FROM `discount` WHERE `ctmid` = '$id' ");
				while($row1 = mysqli_fetch_array($dct))
				{
					echo $row1['percent'];
				}
				?>"></th>
				<th><input type="submit" name="add" value="เพิ่ม"></th><th><input type="submit" name="del" value="ลบ"></th></tr>
				</table>
			</form>
		</td>
	</tr>
</table>
</div>
</body>
</html>
