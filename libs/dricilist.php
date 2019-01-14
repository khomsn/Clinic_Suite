<?php
/*************************This lib use in****************/
/***** ../libs/druglist.php                         *****/
/***** ../main/drugorder.php                        *****/
/********************************************************/
$chorow=1;
for($i=1;$i<=5;$i++)
{
  if(!empty($chron[$i]))
  {
    $cho = mysqli_query($linkcm, "select * from drandillci where chronname = '$chron[$i]' ");
    while ($row = mysqli_fetch_array($cho))
    {
      $dgnameset[$chorow]=$row['drugname'];
      $chorow = $chorow+1;
    }
  }
}

$foutlast = 1;
for($i=1;$i<$chorow;$i++)
{
  $check = "_dsg";
  if (strpos($dgnameset[$i], $check) == TRUE)
  {
    $dgnameset[$i] = str_replace('_dsg', '', $dgnameset[$i]);
    $fout = 'subgroup != "'.$dgnameset[$i].'"';
    goto next2;
   }
  $check = "_drg"; 
  if (strpos($dgnameset[$i],  $check) == TRUE)
  {
    $dgnameset[$i] = str_replace('_drg', '', $dgnameset[$i]);
    $fout = 'groupn != "'.$dgnameset[$i].'"';
     goto next2;
   }
   
   $fout = 'dgname != "'.$dgnameset[$i].'"';
   
   next2:
   $foutlast = $foutlast." AND ".$fout." ";
}
?>
