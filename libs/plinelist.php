<?php
include '../login/dbc.php';
	$q=$_GET['q'];
	$my_data = mysqli_real_escape_string($linkopd, $q);
	$sql="SELECT hometel FROM patient_id WHERE hometel LIKE '%$my_data%' ORDER BY hometel";
	$result = mysqli_query($linkopd,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
		    if($oldj != $row['hometel'])
		    {
			echo $row['hometel']."\n";
			$oldj = $row['hometel'];
		     }
		}
	}
?>