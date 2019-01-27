<?php
include '../config/dbc.php';
    $q = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
	$my_data=mysqli_real_escape_string($link, $q);

	$sql="SELECT DISTINCT S_Name FROM lab WHERE L_Name LIKE '%$my_data%' OR S_Name LIKE '%$my_data%'AND L_Set != 'SETNAME' ORDER BY S_Name";
	$result = mysqli_query($link,$sql);
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			$data[] = $row['S_Name'];
		}
		echo json_encode($data);
	}
?>
