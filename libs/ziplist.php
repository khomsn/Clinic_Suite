<?php
/*************************This lib use in****************/
/***** ../libs/autojatz.php                         *****/
/********************************************************/
include '../config/dbc.php';
//	$q=$_GET['q'];
// get the search term
    $q = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
	$my_data=mysqli_real_escape_string($link, $q);

// write your query to search for data
	$sql="SELECT DISTINCT zipcode FROM zip WHERE zipcode LIKE '%$my_data%' ORDER BY zipcode LIMIT 0,10";
	$result = mysqli_query($linkcm,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			$data[] = $row['zipcode'];
		}
		echo json_encode($data);
	}
?>
