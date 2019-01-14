<?php
include '../config/dbc.php';
//	$q=$_GET['q'];
// get the search term
$q = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
$my_data=mysqli_real_escape_string($link, $q);

$sql="SELECT * FROM rawmat WHERE (rawcode LIKE '%$my_data%' OR rawname LIKE '%$my_data%') ORDER BY rawname";
$result = mysqli_query($link,$sql) or die(mysqli_error());

if($result)
{
    while($row=mysqli_fetch_array($result))
    {
        $data[] = $row['id']."-".$row['rawcode']."-".$row['rawname']."-".$row['size'];
    }
    echo json_encode($data);
}
?>
