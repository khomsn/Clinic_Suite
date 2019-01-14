<?php
/*************************This lib use in****************/
/***** ../libs/autodrug.php                         *****/
/********************************************************/
include '../config/dbc.php';
	$q=$_GET['q'];
	$my_data=mysqli_real_escape_string($link, $q);
	
	$sql="SELECT * FROM drug_id WHERE dname LIKE '%$my_data%' OR dgname LIKE '%$my_data%' ORDER BY dgname";
	$result = mysqli_query($link,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			$data[] = $row['id']."-".$row['dname']."-".$row['dgname']."-".$row['size'];
		}
	}
	$sql="SELECT * FROM deleted_drug WHERE  dname LIKE '%$my_data%' OR dgname LIKE '%$my_data%' ORDER BY dgname";
	$result = mysqli_query($link,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			$data[] = $row['id']."-".$row['dname']."-".$row['dgname']."-".$row['size'];
		}
	}
	echo json_encode($data);
?>
