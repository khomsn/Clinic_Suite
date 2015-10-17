<?php
include '../login/dbc.php';

	$q=$_GET['q'];
	$my_data=mysqli_real_escape_string($link, $q);

	$sql="SELECT tname FROM zip WHERE tname LIKE '%$my_data%' ORDER BY tname";
	$result = mysqli_query($linkcm,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
		    if($oldj != $row['tname'])
		    {
			echo $row['tname']."\n";
			$oldj = $row['tname'];
		     }	
		}
	}
?>