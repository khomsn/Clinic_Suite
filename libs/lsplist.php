<?php
include '../login/dbc.php';
	$q=$_GET['q'];
	$my_data = mysqli_real_escape_string($link, $q);
	$sql="SELECT L_specimen FROM lab WHERE L_specimen LIKE '%$my_data%' ORDER BY L_specimen";
	$result = mysqli_query($link,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
		    if($oldj != $row['L_specimen'])
		    {
			echo $row['L_specimen']."\n";
			$oldj = $row['L_specimen'];
		     }
		}
	}
?>