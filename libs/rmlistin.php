<?php
include '../login/dbc.php';
	$q=$_GET['q'];
	$my_data=mysqli_real_escape_string($link, $q);
	
	$sql="SELECT * FROM rawmat WHERE (rawcode LIKE '%$my_data%' OR rawname LIKE '%$my_data%') ORDER BY rawname";
	$result = mysqli_query($link,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			echo $row['id']."-".$row['rawcode']."-".$row['rawname']."-".$row['size']."\n";
		}
	}
?>
