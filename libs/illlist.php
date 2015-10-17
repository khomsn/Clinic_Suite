<?php
include '../login/dbc.php';

	$q=$_GET['q'];
	$my_data=mysqli_real_escape_string($link, $q);

	$sql="SELECT chronname FROM drandillci WHERE chronname LIKE '%$my_data%' ORDER BY chronname";
	$result = mysqli_query($linkcm,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
		    if($oldj != $row['chronname'])
		    {
			echo $row['chronname']."\n";
			$oldj = $row['chronname'];
		     }	
		}
	}
?>