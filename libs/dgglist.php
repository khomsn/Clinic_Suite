<?php
/*************************This lib use in****************/
/***** ../libs/autodruggen.php                      *****/
/********************************************************/
include '../config/dbc.php';
$q = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
$my_data=mysqli_real_escape_string($link, $q);

$sql="SELECT DISTINCT name FROM druggeneric WHERE name LIKE '%$my_data%' ORDER BY name";
$result = mysqli_query($linkcm,$sql) or die(mysqli_error());

if($result)
{
    while($row=mysqli_fetch_array($result))
    {
        $data[] = $row['name'];
    }
}
$sql="SELECT DISTINCT name FROM drug_group WHERE name LIKE '%$my_data%' ORDER BY name";
$result = mysqli_query($link,$sql) or die(mysqli_error());
if($result)
{
    while($row=mysqli_fetch_array($result))
    {
        $data[] = $row['name'];
    }
}
$sql="SELECT DISTINCT name FROM drug_subgroup WHERE name LIKE '%$my_data%' ORDER BY name";
$result = mysqli_query($link,$sql) or die(mysqli_error());
if($result)
{
    while($row=mysqli_fetch_array($result))
    {
        $data[] = $row['name'];
    }
}
echo json_encode($data);
?>
