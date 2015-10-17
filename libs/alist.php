<?php
include '../login/dbc.php';

	$q=$_GET['q'];
	$my_data=mysqli_real_escape_string($link, $q);

	$sql="SELECT aname FROM zip WHERE aname LIKE '%$my_data%' ORDER BY aname";
	$result = mysqli_query($linkcm,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
		    if($oldj != $row['aname'])
		    {
			echo $row['aname']."\n";
			$oldj = $row['aname'];
		     }	
		}
	}
?>