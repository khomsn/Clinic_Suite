<?php
include '../login/dbc.php';
	$q=$_GET['q'];
	$my_data = mysqli_real_escape_string($linkopd, $q);
	$sql="SELECT lname FROM patient_id WHERE lname LIKE '%$my_data%' ORDER BY lname";
	$result = mysqli_query($linkopd,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
		    if($oldj != $row['lname'])
		    {
			echo $row['lname']."\n";
			$oldj = $row['lname'];
		     }
		}
	}
?>