<?php 
include '../../config/dbc.php';
page_protect();

$sql_create = "CREATE TABLE IF NOT EXISTS `drug_group` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='group of product';";
mysqli_query($link, $sql_create);

if($_POST['add'] == 'เพิ่ม') 
{ 
    if(!preg_match('/^\s*$/',$_POST['tname']))
    {
        // assign insertion pattern
        $sql_insert = "INSERT into `drug_group`
                (`name`)
                VALUES
                ('$_POST[tname]')";

        // Now insert into "drug_group" table
        mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
    }
    // go on to other step
    header("Location: group.php");  
}


if($_POST['del'] != 0 ) 
{ 

	$id = $_POST['del'];
	$sql_del = "DELETE FROM drug_group WHERE id = $id";

	// Now insert into "drug_group" table
	mysqli_query($link, $sql_del) or die("Insertion Failed:" . mysqli_error($link));

    // go on to other step
    header("Location: group.php");  
}
$title = "::กลุ่ม ยาและผลิตภัณฑ์::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
include '../../libs/reloadopener.php';
include '../../main/bodyheader.php';

?>
<div style="text-align: right;"><input type=button value="Close" onClick="reloadParentAndClose();"/></div>
<form name="groupofd" method="post" action="group.php">
    <table border='1' style='text-align: center; margin-left: auto; margin-right: auto;'  class="TFtable">
    <tr><th>ชื่อ</th><th>ลบ</th></tr>
    <tr><th width=150><input type="text" name="tname" style="height: 35px;"></th><th><input type="submit" name="add" value="เพิ่ม"></th></tr>
<?php	
    $dgroup = mysqli_query($link, "SELECT * FROM drug_group");
            // keeps getting the next row until there are no more to get
    while($row = mysqli_fetch_array($dgroup))
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
</body></html>
