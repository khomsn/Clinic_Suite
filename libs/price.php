<?php
//lab price and pricepolicy
$allprice=0; //init value

$tpptin = mysqli_query($link, "select * from $tmp ");
while ($row = mysqli_fetch_array($tpptin))
{
    $alllabprice = $row['licprice']+$row['lcprice'];
    $pricepolicy = $row['pricepolicy'];
    if($row['licprice']) $rmovelab = 0;
    else $rmovelab = 1;
}
//lab price finish

include '../../libs/trpricecheck.php';

  //Treatment price
  $j = 1;
  for($i =1;$i<=4;$i++)
  {
	$tpptin = mysqli_query($link, "select * from $tmp ");
	while ($row = mysqli_fetch_array($tpptin))
	{
		$idtr = "idtr".$i;
		$tr ="tr".$i;
		$trv = "trv".$i;
		if($row[$idtr] !=0)
		{
			$did = $row[$idtr];
			//check id if match jump
			for($s=1;$s<=$t;$s++)
			{
			  if($did ==  $tr_drugid[$s]) goto jpoint1;
			}
			
			$tpptin2 = mysqli_query($link, "select * from drug_id WHERE id = $did ");
			if($tpptin2 !=0)
			{
			while ($row2 = mysqli_fetch_array($tpptin2))
			{
				$price1 = $row2['sellprice'] * $row[$trv] - floor($row2['sellprice'] * $row[$trv] * $row2['disct'] * $perdc);
			}
			}
			jpoint1:
            if($did ==  $tr_drugid[$s])
            {
                if($row[$trv]>=$first1[$s]) 
                $price1 = ($row[$trv]-$first1[$s]+1)*$f1price[$s];
                if($row[$trv]>=$sec2[$s]) 
                $price1 = ($row[$trv]-$sec2[$s]+1)*$sec2price[$s]+($sec2[$s]-$first1[$s])*$f1price[$s];
                if($row[$trv]>=$tri3[$s]) 
                $price1 = ($row[$trv]-$tri3[$s]+1)*$tri3price[$s]+($tri3[$s]-$sec2[$s])*$sec2price[$s]+($sec2[$s]-$first1[$s])*$f1price[$s];
            }			
			$allprice = $allprice+$price1;
			$j = $j+1;
		}
	}
  }
  //TMprice 
  $TMPrice = $allprice;
  
  //drug price
  for($i=1;$i<=14;$i++)
  {
	$tpptin = mysqli_query($link, "select * from $tmp ");
	while ($row = mysqli_fetch_array($tpptin))
	{
		$idrx = "idrx".$i;
		$rx ="rx".$i;
		$rgx = "rxg".$i;
		$rxuses = "rx".$i."uses";
		$rxv = "rx".$i."v";
		if($row[$idrx] !=0)
		{
			$did = $row[$idrx];
			$tpptin2 = mysqli_query($link, "select * from drug_id WHERE id = $did ");
			if($tpptin2)
			{
			while ($row2 = mysqli_fetch_array($tpptin2))
			{
				$price[$i] = $row2['sellprice'] * $row[$rxv] - floor($row2['sellprice'] * $row[$rxv] * $row2['disct'] * $perdc);
			}
			}
			$allprice = $allprice+$price[$i];
			$j = $j+1;
		}
	}
	}
?>



