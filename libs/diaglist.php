<?php
/*************************This lib use in****************/
/***** ../libs/autodiag.php                         *****/
/********************************************************/
include '../config/dbc.php';
//	$q=$_GET['q'];
// get the search term
    $q = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
	$my_data=mysqli_real_escape_string($link, $q);

	$sql="SELECT name FROM diag WHERE name LIKE '%$my_data%' ORDER BY name";
	$result = mysqli_query($linkcm,$sql);
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			$data[] = $row['name'];
		}
		echo json_encode($data);
	}
?>
