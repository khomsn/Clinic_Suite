<?php 
include '../../config/dbc.php';

page_protect();

$id = $_SESSION['patdesk'];
$Patient_id = $id;
include '../../libs/opdxconnection.php';

/*check global masking of drugid*/
$mmask = mysqli_fetch_array(mysqli_query($link, "select * from `parameter`"));
$main_mask = $mmask['maskingdrugid'];
$klinic = $mmask['name'];
$kladdress = $mmask['address'];
$kltel = $mmask['tel'];

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
//
$today = date("Y-m-d");
$pin = mysqli_query($linkopdx, "select MAX(id) from $pttable where clinic='$_SESSION[clinic]'");
$maxrow = mysqli_fetch_array($pin);

if($maxrow[0]!=$_SESSION['mrid'])
{
    $_SESSION['mrid'] = $maxrow[0];
    $_SESSION['rid'] = $maxrow[0];
}
if ($_POST['todo'] == '<<' )
{
	$_SESSION['rid'] = $_SESSION['rid'] - 1;
}
if ($_POST['todo'] == '>>' ) 
{
	$_SESSION['rid'] = $_SESSION['rid'] +1;
}
if ($_POST['todo'] == 'Last' )
{
	$_SESSION['rid'] = $_SESSION['mrid'];
}

//masking per drug id
$di=1;
$maskin = mysqli_query($link, "select * from maskid");
while ($row = mysqli_fetch_array($maskin))
{
    $didin[$di]=$row['drugid'];
    if(!$main_mask) {
        $mask[$di] = $row['mask'];
        if($mask[$di]){
            $mask = 1;
            $di=$di+1;
        }
    }
    else {
        $mask = 1;
        $di=$di+1;
    }
}

$title = "::My Counter::";
include '../../main/header.php';
//echo "<link rel='stylesheet' href='../../jscss/css/opdcard.css'/>";
?>
<script language="javascript">
function Clickheretoprint()
{ 
  var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
      disp_setting+="scrollbars=yes,width=800, height=600, left=100, top=25"; 
  var content_vlue = document.getElementById("print_content").innerHTML; 
  
  var docprint=window.open("","",disp_setting); 
   docprint.document.open(); 
   docprint.document.write("<html><head><title>OPD Card</title>"); 
   docprint.document.write("<link rel='stylesheet' href='../../jscss/css/opdcard_print.css'/>"); 
   docprint.document.write("</head><body onLoad='self.print()'>");          
   docprint.document.write(content_vlue);          
   docprint.document.write("</body></html>"); 
   docprint.document.close(); 
   docprint.focus(); 
}
</script>
</head>
<body><div class="bgc1">
<form method="post" action="opdpage.php" name="regForm" id="regForm">
<div style="text-align: center;">
<?php 
echo "<table width=100%><tr><td width=33%>";
if($_SESSION['rid'] > 1) echo "<input type='submit' name='todo' value='<<' >";
echo "</td><td width=33%>";
if($_SESSION['rid'] < $_SESSION['mrid']) echo "<input type='submit' name='todo' value='>>' >";
echo "</td><td width=33%>";
echo "<input type='submit' name='todo' value='Last' >";
echo "</td></tr></table>";
?>
</div>
</form>
<br>
<div align="center"><input type="submit" name="OK" value="Print" onClick="javascript:Clickheretoprint()" ></div>
<div class="style3" id="print_content">
<?php 
include '../../libs/opdpageformat.php';
?>
</div>
</div></body>
</html>
