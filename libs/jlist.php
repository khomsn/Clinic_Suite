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
	$sql="SELECT DISTINCT jname FROM zip WHERE jname LIKE '$my_data%' ORDER BY jname LIMIT 0,10";
	$result = mysqli_query($linkcm,$sql);
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			$data[] = $row['jname'];
		}
		echo json_encode($data);
	}
?>
