<?php
// code for set fddi temporary

$fddi=''; //empty $fddi to reset filter

for($i=1;$i<$ddindex;$i++)
{
 abs($dil[$i]);
 if($_SESSION['ddiltemp_'.$id]>=$_SESSION['ddil'])
 {
  abs($dil[$i]);
  if(abs($dil[$i]) > $_SESSION['ddiltemp_'.$id])
  {
   abs($dil[$i]);
  
    $cho = mysqli_query($linkcm, "select name from druggeneric where id = '$did[$i]' ");
    $didname = mysqli_fetch_array($cho);
    if(empty($fddi)) $fddi = 'dgname != "'.$didname[0].'"';
    else $fddi = $fddi.' AND dgname != "'.$didname[0].'"';
  }
 }
 else
 {
  if(abs($dil[$i]) > $_SESSION['ddil'])
  {
    $cho = mysqli_query($linkcm, "select name from druggeneric where id = '$did[$i]' ");
    $didname = mysqli_fetch_array($cho);
    if(empty($fddi)) $fddi = 'dgname != "'.$didname[0].'"';
    else $fddi = $fddi.' AND dgname != "'.$didname[0].'"';
  }
 }
}
if(empty($fddi)) $fddi = 1;

?>