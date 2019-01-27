<?php
include '../config/dbc.php';

// get the search term
    $q = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
	$my_data = mysqli_real_escape_string($linkopd, $q);
	$sql="SELECT ctz_id FROM patient_id WHERE ctz_id LIKE '$my_data%' ORDER BY ctz_id LIMIT 0,10";
	$result = mysqli_query($linkopd,$sql);
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			$data[] = $row['ctz_id'];
		}
		echo json_encode($data);
	}
?>
