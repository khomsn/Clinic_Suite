<?php
include '../config/dbc.php';
    $q = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
	$my_data=mysqli_real_escape_string($link, $q);

	$sql="SELECT DISTINCT L_specimen FROM lab WHERE L_specimen LIKE '%$my_data%' ORDER BY L_specimen";
	$result = mysqli_query($link,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			$data[] = $row['L_specimen'];
		}
		echo json_encode($data);
	}
?>
