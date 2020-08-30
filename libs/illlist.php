<?php
/*************************This lib use in****************/
/***** ../libs/autodruggen.php                      *****/
/********************************************************/
include '../config/dbc.php';
$q = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
$my_data=mysqli_real_escape_string($link, $q);
$sql="SELECT DISTINCT chronname FROM drandillci WHERE chronname LIKE '%$my_data%' ORDER BY chronname";
$result = mysqli_query($linkcm,$sql);
if($result)
{
    while($row=mysqli_fetch_array($result))
    {
        $data[] = $row['chronname'];
    }
    echo json_encode($data);
}
?>
