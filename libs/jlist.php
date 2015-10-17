<?php
include '../login/dbc.php';
	$q=$_GET['q'];
	$my_data = mysqli_real_escape_string($link, $q);
	$sql="SELECT jname FROM zip WHERE jname LIKE '%$my_data%' ORDER BY jname";
	$result = mysqli_query($linkcm,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
		    if($oldj != $row['jname'])
		    {
			echo $row['jname']."\n";
			$oldj = $row['jname'];
		     }	
		}
	}
?>