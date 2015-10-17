<?php 
include '../login/dbc.php';
//include '../Grap/Graph.php';
page_protect();

$id = $_SESSION['patdesk'];
$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;

$pin = mysqli_query($linkopd, "select * from $pttable ");

?>

<!DOCTYPE html>
<html>
<head>
<title>Weight</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<link rel="stylesheet" href="../public/css/styles.css">
</head>

<body>
<div id="content">
	<div style="text-align: center;">
	<?php 
	$pin = mysqli_query($linkopd, "select * from (select * from $pttable ORDER BY `date` DESC limit 0,10) t ORDER BY `date` ASC") ;
	echo "<table style=' width: 80%; text-align: center; margin-left: auto; margin-right: auto;' border='1' cellpadding='1' cellspacing='1'>";
	echo "<tr><th>วัน</th><th>น้ำหนัก</th><th>+/-</th>";
	$weightold = 0;
	while ($row_settings = mysqli_fetch_array($pin))
	{
		echo "<tr><td>";
		$date = new DateTime($row_settings['date']);
		$sd = $date->format("d");
		$sm = $date->format("m");
		$sy = $date->format("Y");
		$bsy = $sy+543;
		echo $sd;
		echo " ";
		$m = $sm;
		switch ($m)
		{
		 case 1:
		 echo "มค";
		 break;
		 case 2:
		 echo "กพ";
		 break;
		 case 3:
		 echo "มีค";
		 break;
		 case 4:
		 echo "เมย";
		 break;
		 case 5:
		 echo "พค";
		 break;
		 case 6:
		 echo "มิย";
		 break;
		 case 7:
		 echo "กค";
		 break;
		 case 8:
		 echo "สค";
		 break;
		 case 9:
		 echo "กย";
		 break;
		 case 10:
		 echo "ตค";
		 break;
		 case 11:
		 echo "พย";
		 break;
		 case 12:
		 echo "ธค";
		 break;
		}
		echo " ";
		echo $bsy; //date("Y")+543;
		echo "</td>";
		echo "<td>";
		echo $row_settings['weight']; 
		echo "</td>";
		echo "<td>";
		echo $row_settings['weight'] - $weightold ; 
		echo "</td>";
		echo "</tr>";
		$weightold = $row_settings['weight'];
	}
	
	echo "</table>";
	?>
	</div>
<!--end menu-->
</div>
</body>
<script>

var content = document.getElementById("content");
window.resizeTo(content.offsetWidth + 30, content.offsetHeight + 50);

</script>
</html>