<?php 
include '../config/dbc.php';

page_protect();

$id = $_SESSION['patcash'];
$Patient_id = $id;
include '../libs/opdxconnection.php';

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
//
$today = date("Y-m-d");
$pin = mysqli_query($linkopdx, "select MAX(id) from $pttable ");
$maxrow = mysqli_fetch_row($pin);
$_SESSION['rid'] = $maxrow[0];
?>
<!DOCTYPE html>
<html>
<head>
<title>::OPD CARD::</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<link rel="stylesheet" type="text/css" href="../jscss/js/jquery-ui-1.12.1.custom/jquery-ui.min.css"/>
<link rel="stylesheet" type="text/css" href="../jscss/css/styles.css"/>
<script language="JavaScript" type="text/javascript" src="../jscss/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../jscss/js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscss/js/jquery.validate.min.js"></script>
<link rel='stylesheet' href='../jscss/css/opdcard_print.css'/>
<script language="JavaScript" type="text/javascript" src="../jscss/js/autoclick.js"></script>
<script language="javascript">
function Clickheretoprint()
{ 
  var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
      disp_setting+="scrollbars=yes,width=800, height=600, left=100, top=25"; 
  var content_vlue = document.getElementById("print_content").innerHTML; 
  
  var docprint=window.open("","",disp_setting); 
   docprint.document.open(); 
   docprint.document.write("<html><head><title>OPD Card Print</title>"); 
   docprint.document.write("<link rel='stylesheet' href='../jscss/css/opdcard_print.css'/>"); 
   docprint.document.write("</head><body onLoad='self.print()'>");          
   docprint.document.write(content_vlue);          
   docprint.document.write("</body></html>"); 
   docprint.document.close(); 
   docprint.focus(); 
}
</script>
</head>
<body><div class="myaccount">
<div align="center"><a href="javascript:Clickheretoprint()" id="ATC">Print</a></div><br>
<div id="print_content">
<?php 
$pin = mysqli_query($link, "select * from parameter where id='1'");
while ($row = mysqli_fetch_array($pin))
{
    $klinic = $row['name'];
    $kladdress = $row['address'];
    $kltel = $row['tel'];
    $kp_ad = 1;
}
include '../libs/opdpageformat.php';
?>
</div>
</div></body>
</html>
<?php
	unset($_SESSION['patcash']);
	unset($_SESSION['buyprice']);
	unset($_SESSION['olddeb']);
	unset($_SESSION['patdesk']);
	unset($_SESSION['paytoday']);
	unset($_SESSION['newdeb']);
	unset($_SESSION['price']);
	unset($_SESSION['mrid']);
	unset($_SESSION['rid']);
?>
