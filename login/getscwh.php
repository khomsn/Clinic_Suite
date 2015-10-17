<?php
include 'dbc.php';
page_protect();
$_SESSION['scwidth'] = $_GET['width'];
$_SESSION['scheight'] = $_GET['height'];
header("Location: myaccount.php");
?>