<?php 
include '../../config/dbc.php';
page_protect();

$sql_create = "CREATE TABLE IF NOT EXISTS `commission` (
  `sellofmon` int(11) NOT NULL,
  `perofcom` decimal(3,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

mysqli_query($link, $sql_create);

$sql_select =  mysqli_query($link, "SELECT * FROM `initdb` WHERE `refname`='commission' ORDER BY `id`" );
$tableversion = mysqli_num_rows($sql_select);
if(!$tableversion)
{
    $sql  = "INSERT INTO `initdb` (`refname`, `version`) VALUES (\'commission\', \'1\')";
    mysqli_query($link, $sql);
}

if($_POST['add'] == 'เพิ่ม') 
{ 
	// assign insertion pattern
	$sql_insert = "INSERT into `commission`
  			(`sellofmon`,`perofcom`)
		    VALUES
			('$_POST[sellofmon]', '$_POST[perofcom]' )";

	// Now insert into "commission" table
	mysqli_query($link, $sql_insert);
// go on to other step
header("Location: comrate.php");  
}

if($_POST['del'] != 0 ) 
{ 
	$dels = $_POST['del'];
	$sql_del = "DELETE FROM `commission` WHERE perofcom = $dels";

	// Now insert into "commission" table
	mysqli_query($link, $sql_del);

// go on to other step
header("Location: comrate.php");  
}
$title = "::Commission Rate::";
include '../../main/header.php';
include '../../main/bodyheader.php';

?>
<div id="content"><form name="groupofd" method="post" action="comrate.php">
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
</form></div>
</body>
<script>

var content = document.getElementById("content");
window.resizeTo(content.offsetWidth + 30, content.offsetHeight + 50);

</script>
</html>
