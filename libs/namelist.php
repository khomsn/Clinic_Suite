<?php
include '../login/dbc.php';
	$q=$_GET['q'];
	$my_data = mysqli_real_escape_string($linkopd, $q);
	$sql="SELECT fname FROM patient_id WHERE fname LIKE '%$my_data%' ORDER BY fname";
	$result = mysqli_query($linkopd,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
		    if($oldj != $row['fname'])
		    {
			echo $row['fname']."\n";
			$oldj = $row['fname'];
		     }
		}
	}
?>