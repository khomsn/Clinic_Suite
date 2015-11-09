<?php
include '../login/dbc.php';
	$q=$_GET['q'];
	$my_data = mysqli_real_escape_string($linkopd, $q);
	$sql="SELECT mobile FROM patient_id WHERE mobile LIKE '%$my_data%' ORDER BY mobile";
	$result = mysqli_query($linkopd,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
		    if($oldj != $row['mobile'])
		    {
			echo $row['mobile']."\n";
			$oldj = $row['mobile'];
		     }
		}
	}
?>