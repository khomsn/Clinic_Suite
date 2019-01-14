<?php
/*************************This lib use in****************/
/***** ../libs/autojatz.php                         *****/
/********************************************************/
include '../config/dbc.php';
// get the search term
    $q = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
	$my_data=mysqli_real_escape_string($link, $q);

// write your query to search for data
	$sql="SELECT DISTINCT tname FROM zip WHERE tname LIKE '$my_data%' ORDER BY tname";
	$result = mysqli_query($linkcm,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			$data[] = $row['tname'];
		}
		echo json_encode($data);
	}
?>
