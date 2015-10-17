<?php 
include '../login/dbc.php';
page_protect();
?>

<!DOCTYPE html>
<html>
<head>
<title>ประวัติ ตรวจร่างกาย</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="content">
							<div style="text-align: center;">
							<p>"Golden standard for defining obesity"
							<br>Men: BMI < 27 kg/m2 -> %BF < 25%  <->  Women: BMI < 25 kg/m2 ->%BF < 35%
							<br>Thai, cut-off values of BMI for diagnosing obesity should be lowered to 
							<br>27 kg/m2 in men and 25 kg/m2 in women.
							<br>Underweight = <18.5</p>
							</div>
<!--end menu-->
</div>
</body>
<script>

var content = document.getElementById("content");
window.resizeTo(content.offsetWidth + 30, content.offsetHeight + 50);

</script>
</html>