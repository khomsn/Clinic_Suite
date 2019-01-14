<?php
include '../config/dbc.php';

//	$q=$_GET['q'];
// get the search term
    $q = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
	$my_data=mysqli_real_escape_string($link, $q);

	$sql="SELECT DISTINCT lname FROM patient_id WHERE lname LIKE '$my_data%' ORDER BY lname LIMIT 0,10";
	$result = mysqli_query($linkopd,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			$data[] = $row['lname'];
		}
		echo json_encode($data);
	}
?>
