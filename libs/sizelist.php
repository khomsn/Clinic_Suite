<?php
/*************************This lib use in****************/
/***** ../libs/autodruggen.php                      *****/
/********************************************************/
include '../config/dbc.php';
$q = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
$my_data=mysqli_real_escape_string($link, $q);

$sql="SELECT DISTINCT size FROM drug_id WHERE size LIKE '%$my_data%' ORDER BY size";
$result = mysqli_query($link,$sql) or die(mysqli_error());
if($result)
{
    while($row=mysqli_fetch_array($result))
    {
        $data[] = $row['size'];
    }
}
$sql="SELECT DISTINCT size FROM deleted_drug WHERE size LIKE '%$my_data%' ORDER BY size";
$result = mysqli_query($link,$sql) or die(mysqli_error());
if($result)
{
    while($row=mysqli_fetch_array($result))
    {
        $data[] = $row['size'];
    }
}
echo json_encode($data);
?>
