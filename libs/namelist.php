<?php
include '../config/dbc.php';

//	$q=$_GET['q'];
// get the search term
    $q = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
	$my_data=mysqli_real_escape_string($link, $q);

	$sql="SELECT DISTINCT fname FROM patient_id WHERE fname LIKE '$my_data%' ORDER BY fname LIMIT 0,10";
	$result = mysqli_query($linkopd,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			$data[] = $row['fname'];
		}
		echo json_encode($data);
	}
?>
