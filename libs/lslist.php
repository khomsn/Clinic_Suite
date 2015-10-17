<?php
include '../login/dbc.php';
	$q=$_GET['q'];
	$my_data = mysqli_real_escape_string($link, $q);
	$sql="SELECT L_Set FROM lab WHERE L_Set LIKE '%$my_data%' AND L_Set != 'SETNAME' ORDER BY L_Set";
	$result = mysqli_query($link,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
		    if($oldj != $row['L_Set'])
		    {
			echo $row['L_Set']."\n";
			$oldj = $row['L_Set'];
		     }
		}
	}
?>