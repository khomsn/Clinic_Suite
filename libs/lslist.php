<?php
include '../config/dbc.php';
    $q = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
	$my_data=mysqli_real_escape_string($link, $q);

	$sql="SELECT DISTINCT L_Set FROM lab WHERE L_Set LIKE '%$my_data%' AND L_Set != 'SETNAME' ORDER BY L_Set";
	$result = mysqli_query($link,$sql);
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			$data[] = $row['L_Set'];
		}
		echo json_encode($data);
	}
?>
