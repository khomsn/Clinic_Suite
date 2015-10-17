<?php 
include '../login/dbc.php';
page_protect();
unset($_SESSION['patcash']);
unset($_SESSION['patdesk']);
unset($_SESSION['pricecheck']);
unset($_SESSION['price']);

?>

<html>

<head>
<head>
<title>Thank you</title>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<link rel="stylesheet" href="../public/css/styles.css">
<script type="text/javascript">
    if(window.top.location != window.location) 
    {
        window.top.location.href = window.location.href; 
    }
</script>        
</head>

<body  style="background-image: url(../image/thanks2.gif);">
<?php
header('refresh: 2; ../main/ptodrug.php'); // redirect the user after 10 seconds
#exit; // note that exit is not required, HTML can be displayed.
?>
</body>
</html>
