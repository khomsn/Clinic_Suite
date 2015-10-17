<?php 
include '../login/dbc.php';
page_protect();

if($_POST['add'] == 'เพิ่ม') 
{ 
	// assign insertion pattern
	$sql_insert = "INSERT into `commission`
  			(`sellofmon`,`perofcom`)
		    VALUES
			('$_POST[sellofmon]', '$_POST[perofcom]' )";

	// Now insert into "commission" table
	mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
// go on to other step
header("Location: comrate.php");  
}

if($_POST['del'] != 0 ) 
{ 
	$dels = $_POST['del'];
	$sql_del = "DELETE FROM `commission` WHERE perofcom = $dels";

	// Now insert into "commission" table
	mysqli_query($link, $sql_del) or die("Insertion Failed:" . mysqli_error($link));

// go on to other step
header("Location: comrate.php");  
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Commission Rate</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<link rel="stylesheet" href="../public/css/styles.css">
</head>

<body>
<div id="content">
<table border="0" cellspacing="0" cellpadding="5" class="main">
	<tr>
		<td><form name="groupofd" method="post" action="comrate.php">
				<table border='1' style='text-align: center;' >
				<tr><th>ยอดขายประจำเดือน</th><th>%</th><th>ลบ</th></tr>
				<tr><th><input type='number' min=0 name='sellofmon' ></th>
				     <th><input type='number' step=0.1 min=0 name='perofcom' ></th>
					 <th><input type='submit' name='add' value='เพิ่ม'></th></tr>
				<?php 
				$dgroup = mysqli_query($link, "SELECT * FROM commission ORDER BY `sellofmon` DESC ");
				
						// keeps getting the next row until there are no more to get
				while($row = mysqli_fetch_array($dgroup))
				{
					echo "<tr><th>";
					echo $row['sellofmon'];
					echo "</th><th>";
					echo $row['perofcom'];
					echo "</th><th>";
					echo "<input type='submit' name='del' value='"; echo $row['perofcom']; echo "'></th></tr>";
				}
				?>
				</table>
			</form>
		</td>
	</tr>
</table>
<!--end menu-->
</div>
</body>
<script>

var content = document.getElementById("content");
window.resizeTo(content.offsetWidth + 30, content.offsetHeight + 50);

</script>
</html>