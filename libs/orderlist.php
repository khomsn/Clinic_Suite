<?php
include '../config/dbc.php';
$lcid = $_GET['actor'];
$ordertable = 'doctemplate_'.$lcid;
       
//	$q=$_GET['q'];
// get the search term
    $q = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
	$my_data=mysqli_real_escape_string($link, $q);

	$sql="SELECT scode FROM $ordertable WHERE scode LIKE '$my_data%' ORDER BY scode LIMIT 0,10";
	$result = mysqli_query($link,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			$data[] = $row['scode'];
		}
		echo json_encode($data);
	}
?>
