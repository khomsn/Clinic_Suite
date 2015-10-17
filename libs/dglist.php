<?php
include '../login/dbc.php';

	$q=$_GET['q'];
	$my_data=mysqli_real_escape_string($linkcm, $q);

	$sql="SELECT name FROM druggeneric WHERE name LIKE '%$my_data%' ORDER BY name";
	$result = mysqli_query($linkcm,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
		    if($oldj != $row['name'])
		    {
			echo $row['name']."\n";
			$oldj = $row['name'];
		     }	
		}
	}
?>