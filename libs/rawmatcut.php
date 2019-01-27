<?php //rawmat cut
$j = $_SESSION['drugmax'];
if($_SESSION['TM'])
{
  $j = $_SESSION['tmax'];
}

$sm = date("m");
$sy = date("Y");

$dtemp = mysqli_query($link, "select * from drugcombset");
while($row=mysqli_fetch_array($dtemp))
{ 
  $k = $row['id'];
  $idin[$k] = $row['drugidin'];
  $invol[$k] = $row['invol'];
  $idout[$k] = $row['drugidout'];
  $outvol[$k] = $row['outvol'];
  $outsetpoint[$k] = $row['outsetpoint'];
  $outcount[$k] = $row['outcount'];
}

for($i=1;$i<=$j;$i++)
{
  if($_SESSION['TM'])
  {
	$_SESSION['idrx'.$i] =	$_SESSION['idtr'.$i];
	$_SESSION['rxvol'.$i] =	$_SESSION['trvol'.$i];
  }
  include '../../libs/autorawmatcut.php';
}
?>
