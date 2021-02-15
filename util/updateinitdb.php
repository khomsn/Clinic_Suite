<?php 
include '../config/dbc.php';

page_protect();
?>
<!DOCTYPE html>
<html>
<head>
<title>Update pt_table</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="content">
<div style="text-align: center;">
<?php
    for ($i=1; $i<=37732 ;$i++){
        $sql_insert  = "INSERT INTO `initdb` (`refname`, `version`) VALUES ('pt_$i', '1')";
        mysqli_query($link, $sql_insert);
        }
?>
</div>
</div>
</body>
</html>
