<?php
  //check for step price for treatment
  $trstep = mysqli_query($link, "select * from trpstep");
  $t=1;
  while($row = mysqli_fetch_array($trstep))
  {
    $tr_drugid[$t] = $row['drugid'];
    $first1[$t] = $row['firstone'];
    $f1price[$t] = $row['init_pr'];
    $sec2[$t] = $row['secstep'];
    $sec2price[$t] = $row['sec_pr'];
    $tri3[$t] = $row['tristep'];
    $tri3price[$t] = $row['tri_pr'];
    $t=$t+1;
  }
?>