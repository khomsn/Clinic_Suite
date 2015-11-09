<?php
include '../login/dbc.php';
	$q=$_GET['q'];
	$my_data = mysqli_real_escape_string($linkopd, $q);
	$sql="SELECT ctz_id FROM patient_id WHERE ctz_id LIKE '%$my_data%' ORDER BY ctz_id";
	$result = mysqli_query($linkopd,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
		    if($oldj != $row['ctz_id'])
		    {
			echo $row['ctz_id']."\n";
			$oldj = $row['ctz_id'];
		     }
		}
	}
?>