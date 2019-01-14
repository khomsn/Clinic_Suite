<?php
//concurdrug
if(!empty($concurdrug))
{
    $ccd = substr_count($concurdrug, ',');
    //$str = 'hypertext;language;programming';
    $charsl = preg_split('/,/', $concurdrug);
    for($i=0;$i<=$ccd;$i++)
    {
        $cho = mysqli_query($linkcm, "select dinteract from druggeneric where name = '$charsl[$i]' ");
        $int[$i] = mysqli_fetch_array($cho);
    }

}

$ddindex=1;

for($i=0;$i<=$ccd;$i++)
{
  $iti = $int[$i][0];
  if(!empty($iti))
  {
      $nci = substr_count($iti, ';');
      //$str = 'hypertext;language;programming';
      $charsl = preg_split('/;/', $iti);
  
    for($b=0;$b<=$nci;$b++)
    {
      $charslnew[$b] = str_replace('[', '', $charsl[$b]);
      $charslnew[$b] = str_replace(']', '', $charslnew[$b]);
      $chars = preg_split('/,/', $charslnew[$b]);
      $did[$ddindex]=$chars[0];
      $dil[$ddindex]=$chars[1];
      $ddindex = $ddindex+1;
    }
  }
}
//set filter for ddi
?>
