<?php 
include '../login/dbc.php';
page_protect();

$fulluri = $_SERVER['REQUEST_URI'];
$id = filter_var($fulluri, FILTER_SANITIZE_NUMBER_INT);
$_SESSION['pattrm'] = $id;

?>

<!DOCTYPE html>
<html>
<head>
<title>Lab Room</title>
<meta name="generator" content="Bluefish 2.2.5" >
<meta name="author" content="khomsn" >
<meta name="date" content="2014-06-11T21:58:13+0700" >
<meta name="copyright" content="">
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8">
<meta http-equiv="content-style-type" content="text/css">
<meta http-equiv="expires" content="0">
	<script language="JavaScript" type="text/javascript" src="../public/jquery-2.1.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">

</head>
<body>
<iframe src="lmenubar.php" onload="this.height=window.innerHeight-20;" width="15%" frameborder="0" style="float:left;" name="lmenu"></iframe>
<iframe src="trmpage.php" onload="this.height=window.innerHeight-20;" width="68%" frameborder="0" style="float:none;" name="MAIN"></iframe>
<iframe src="rtmenubar.php" onload="this.height=window.innerHeight-20;" width="15%" frameborder="0" style="float:right;" name="rmenu"></iframe>
</body>
</html>