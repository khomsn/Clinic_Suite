<?php 
include '../../config/dbc.php';
page_protect();
$err = array();

if(($_POST['add'] == 'เพิ่ม')  AND (ltrim($_POST['name']!== '')))
{ 
    $pktname = mysqli_real_escape_string($link, $_POST['name']);
	// assign insertion pattern
	$sql_insert = "INSERT into `packagetype` (`name`) VALUES ('$_POST[name]')";

	// Now insert Patient to "patient_id" table
	mysqli_query($link, $sql_insert) or $err[] = ("Insertion Failed:<br>"  . mysqli_error($link));
}

if($_POST['del'] != 0 ) 
{ 
	$id = $_POST['del'];
	$sql_del = "DELETE FROM packagetype WHERE id = $id";

	// Now delete
	mysqli_query($link, $sql_del) or $err[] = ("Deletion Failed:<br>" . mysqli_error($link));
}
$title = "::Package Type::";
include '../../main/header.php';
include '../../libs/reloadopener.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
include '../../main/bodyheader.php';

?>
<div style="text-align: right;"><input type=button value="Close" onClick="reloadParentAndClose();"/></div>
<?php
/******************** ERROR MESSAGES*************************************************
This code is to show error messages 
**************************************************************************/
if(!empty($err))
{
    echo "<div class=\"error\">";
    foreach ($err as $e) {echo "$e <br>";}
    echo "</div>";	
}
/******************************* END ********************************/	  

?>
<form name="pkt" method="post" action="packagetype.php">
<table border='1' style='text-align: center; margin-left: auto; margin-right: auto;'  class="TFtable">
<tr><th>ชื่อ</th><th>ลบ</th></tr>
<tr><th width=150><input type="text" name="name" autofocus></th><th><input type="submit" name="add" value="เพิ่ม"></th></tr>
<?php	
$dpackagetype = mysqli_query($link, "SELECT * FROM packagetype");
// keeps getting the next row until there are no more to get
while($row = mysqli_fetch_array($dpackagetype))
{
// Print out the contents of each row into a table
echo "<tr><th >"; 
echo $row['name'];
echo "</th><th>";
echo "<input name=del type=submit value=".$row['id'].">";
echo "</th></tr>";
}
?>
</table>
</form>
</body></html>
