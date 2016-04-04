<?php
include '../login/dbc.php';
	$q=$_GET['q'];
	$my_data=mysqli_real_escape_string($link, $q);
	
	$sql="SELECT * FROM drug_id WHERE (dname LIKE '%$my_data%' OR dgname LIKE '%$my_data%') AND track='0' ORDER BY dgname";
	$result = mysqli_query($link,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			echo $row['id']."-".$row['dname']."-".$row['dgname']."-".$row['size']."\n";
		}
	}
?>