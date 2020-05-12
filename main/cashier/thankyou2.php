<?php 
include '../../config/dbc.php';
page_protect();
unset($_SESSION['patcash']);
unset($_SESSION['patdesk']);
unset($_SESSION['pricecheck']);
unset($_SESSION['price']);

echo "<!DOCTYPE html>
<html>
<head>
<meta content=\"text/html; charset=utf-8\" http-equiv=\"content-type\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/styles.css\"/>";
?>
<script type="text/javascript">
    if(window.top.location != window.location) 
    {
        window.top.location.href = window.location.href; 
    }
</script>        
</head>
<body  style="background-image: url(../../image/thanks2.gif); background-size: cover;">
<?php
header('refresh: 2; ../opd/pt_to_drug.php'); // redirect the user after 2 seconds
#exit; // note that exit is not required, HTML can be displayed.
?>
</body>
</html>
