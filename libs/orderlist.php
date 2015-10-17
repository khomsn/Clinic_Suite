<?php
include '../login/dbc.php';
        $lcid = $_GET['actor'];
        $ordertable = 'doctemplate_'.$lcid;
       
	$q=$_GET['q'];
	$my_data=mysqli_real_escape_string($link, $q);

	$sql="SELECT scode FROM $ordertable WHERE scode LIKE '%$my_data%' ORDER BY scode";
	$result = mysqli_query($link,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			echo $row['scode']."\n";
		}
	}
?>