<?php
/*************************This lib use in****************/
/***** ../libs/autodruggen.php                      *****/
/********************************************************/
include '../config/dbc.php';
$q = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
$my_data=mysqli_real_escape_string($link, $q);

$sql="SELECT DISTINCT dname FROM drug_id WHERE dname LIKE '%$my_data%' ORDER BY dname";
$result = mysqli_query($link,$sql);
if($result)
{
    while($row=mysqli_fetch_array($result))
    {
        $data[] = $row['dname'];
    }
}
$sql="SELECT DISTINCT dname FROM deleted_drug WHERE dname LIKE '%$my_data%' ORDER BY dname";
$result = mysqli_query($link,$sql);
if($result)
{
    while($row=mysqli_fetch_array($result))
    {
        $data[] = $row['dname'];
    }
}
echo json_encode($data);
?>
