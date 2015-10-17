<?php
include '../login/dbc.php';

	$q=$_GET['q'];
	$my_data=mysqli_real_escape_string($link, $q);
	$sql="SELECT zipcode FROM zip WHERE zipcode LIKE '%$my_data%' ORDER BY zipcode";
	$result = mysqli_query($linkcm,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
		    if($oldj != $row['zipcode'])
		    {
			echo $row['zipcode']."\n";
			$oldj = $row['zipcode'];
		     }	
		}
	}
?>