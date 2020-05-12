<?php 
include '../../config/dbc.php';
page_protect();

$fulluri = $_SERVER['REQUEST_URI'];
$id = filter_var($fulluri, FILTER_SANITIZE_NUMBER_INT);
?>

<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<link rel="stylesheet" href="../../public/css/styles.css">
</head>

<body >
<iframe src="../register/lmenubar.php" height="600" width="15%" frameborder="0" style="float:left;" name="lmenu"></iframe>
<iframe src="paydeb.php" height="650" width="68%" frameborder="0" style="float:none;" name="MAIN"></iframe>
<iframe src="../register/rmenuopd.php" height="600" width="15%" frameborder="0" style="float:right;" name="rmenu"></iframe>
</body>
</html>
