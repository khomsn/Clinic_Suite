<?php 
include '../login/dbc.php';
page_protect();

$fulluri = $_SERVER['REQUEST_URI'];
$id = filter_var($fulluri, FILTER_SANITIZE_NUMBER_INT);
?>

<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<link rel="stylesheet" href="../public/css/styles.css">
</head>

<body >
<iframe src="lmenubar.php" height="<?php echo $_SESSION['scheight']-20;?>" width="130px" frameborder="0" style="float:left;" name="lmenu"></iframe>
<iframe src="recprintpv.php" height="<?php echo $_SESSION['scheight']-20;?>" width="<?php echo $_SESSION['scwidth']-350;?>" frameborder="0" style="float:none;" name="MAIN"></iframe>
<iframe src="rmenuopd.php" height="<?php echo $_SESSION['scheight']-20;?>" width="150px" frameborder="0" style="float:right;" name="rmenu"></iframe>
</body>
</html>