<?php
include '../config/dbc.php';

//	$q=$_GET['q'];
// get the search term
    $q = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
	$my_data=mysqli_real_escape_string($link, $q);

// write your query to search for data
	$sql="SELECT DISTINCT mobile FROM patient_id WHERE mobile LIKE '$my_data%' ORDER BY mobile LIMIT 0,10";
	$result = mysqli_query($linkopd,$sql);
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			$data[] = $row['mobile'];
		}
		echo json_encode($data);
	}
?>
