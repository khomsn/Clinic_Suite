<?php 
include '../../config/dbc.php';
page_protect();

if($_POST['add'] == 'เพิ่ม') 
{ 
    if(!preg_match('/^\s*$/',$_POST['tname']))
    {
        // assign insertion pattern
        $sql_insert = "INSERT into `drug_type`
                (`name`)
                VALUES
                ('$_POST[tname]')";
        mysqli_query($link, $sql_insert);
	}
    // go on to other step
    header("Location: type.php");  
}


if($_POST['ddel'] != 0 ) 
{ 
	$id = $_POST['ddel'];
	
	$sql_del = "DELETE FROM drug_type WHERE id = $id";
	mysqli_query($link, $sql_del);

    // go on to other step
    header("Location: type.php");  
}
$title = "::ประเภท ยาและผลิตภัณฑ์::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
include '../../libs/reloadopener.php';
include '../../main/bodyheader.php';

?>
<div style="text-align: right;"><input type=button value="Close" onClick="reloadParentAndClose();"/></div>
<form name="typeofd" method="post" action="type.php">
    <table border='1' style='text-align: center; margin-left: auto; margin-right: auto;' class="TFtable">
    <tr><th>ชื่อ</th><th>ลบ</th></tr>
    <tr><th width=150><input type="text" name="tname" style="height: 35px;"></th><th><input type="submit" name="add" value="เพิ่ม"></th></tr>
<?php	
    $dtype = mysqli_query($link, "SELECT * FROM drug_type");
            // keeps getting the next row until there are no more to get
    while($row = mysqli_fetch_array($dtype))
        {
        // Print out the contents of each row into a table
        echo "<tr><th >"; 
        echo $row['name'];
        echo "</th><th>";
        echo "<input type=submit name=ddel value=".$row['id'].">";
        echo "</th></tr>";
    }	 
    echo "</table>";
?>
</form>
</body></html>
