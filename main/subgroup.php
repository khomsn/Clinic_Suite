<?php 
include '../login/dbc.php';
page_protect();

if($_POST['add'] == 'เพิ่ม') 
{ 

	// assign insertion pattern
	$sql_insert = "INSERT into `drug_subgroup`
  			(`name`)
		    VALUES
			('$_POST[tname]')";

	// Now insert Patient to "patient_id" table
	mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
// go on to other step
header("Location: subgroup.php");  
}


if($_POST['del'] != 0 ) 
{ 

	$id = $_POST['del'];
	$sql_del = "DELETE FROM drug_subgroup WHERE id = $id";

	// Now insert Patient to "patient_id" table
	mysqli_query($link, $sql_del) or die("Insertion Failed:" . mysqli_error($link));

// go on to other step
header("Location: subgroup.php");  
}
?>

<!DOCTYPE html>
<html>
<head>
<title>กลุ่ม ยาและผลิตภัณฑ์</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
<?php include '../libs/reloadopener.php';?>
</head>

<body>
<div style="text-align: right;"><input type=button value="Close" onClick="reloadParentAndClose();"/></div>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
	<tr>
		<td><form name="subgroupofd" method="post" action="subgroup.php">
				<table border='1' style='text-align: center; margin-left: auto; margin-right: auto;' >
				<tr><th>ชื่อ</th><th>ลบ</th></tr>
				<tr><th width=150><input type="text" name="tname" style="height: 35px;"></th><th><input type="submit" name="add" value="เพิ่ม"></th></tr>
			<?php	
				$dsubgroup = mysqli_query($link, "SELECT * FROM drug_subgroup");
						// keeps getting the next row until there are no more to get
				while($row = mysqli_fetch_array($dsubgroup))
				 {
					// Print out the contents of each row into a table
					echo "<tr><th >"; 
					echo $row['name'];
					echo "</th><th>";
					echo "<input name=del type=submit value=".$row['id'].">";
					echo "</th></tr>";
				}	 
				echo "</table>";
			?>
			</form>
		</td>
	</tr>
</table>
<!--end menu-->
</body></html>