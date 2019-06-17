<?php //rawmat cut

$sm = date("m");
$sy = date("Y");

$dtemp = mysqli_query($link, "select * from drugcombset");
while($row=mysqli_fetch_array($dtemp))
{ //(`id`, `drugidin`, `invol`, `drugidout`, `outvol`, `outsetpoint`, `outcount`) 
  $k = $row['id'];
  $idin[$k] = $row['drugidin'];
  $invol[$k] = $row['invol'];
  $idout[$k] = $row['drugidout'];
  $outvol[$k] = $row['outvol'];
  $outsetpoint[$k] = $row['outsetpoint'];
  $outcount[$k] = $row['outcount'];
}

  include '../../libs/autorawmatcutforlab.php';
?>
