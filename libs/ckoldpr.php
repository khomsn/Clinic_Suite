<?php
	$id = $_SESSION['rid'];
	$pin = mysqli_query($link, "select * from $tmp ");
	while ($row = mysqli_fetch_array($pin))
	{	
		for($i=1;$i<=14;$i++)
		{
			$cn = "idrx".$i;
			if($row[$cn] == 0)
			{
				$imin = $i;
				break 1;
			}
		}
//		echo $cn; echo  "and"; echo $imin;
		for($j=1;$j<=14;$j++)
		{ 
			if($_POST[$j] ==1)
			{
			$ptin = mysqli_query($linkopdx, "select * from $pttable where id='$id' ");
			$idrxg = "idrx".$j;
			$rxg = "rx".$j;
			$rgng = "rxg".$j;
			$usg = "rx".$j."uses";
			$vlg = "rx".$j."v";
			while ($row2 = mysqli_fetch_array($ptin))
				{
					$idrx[$i] =  $row2[$idrxg];
					$rx[$i] =  $row2[$rxg];
					$rgx[$i] =  $row2[$rgng];
					$rxuses[$i] =  $row2[$usg];
					$rxv[$i] =  $row2[$vlg];
				}
				$imax = $i;
				$i = $i+1;
			}
		}
	}

// prepare and bind for drug_id update:
$stmt = $link->prepare("UPDATE drug_id SET `volreserve` = ? WHERE `id` = ? ");
$stmt->bind_param("ii", $volreserve, $idres);


	for($i=$imin;$i<=$imax;$i++)
	{
		$us = "rx".$i."uses";
		$vl = "rx".$i."v";
		$idp = $idrx[$i];
		$rxp = $rx[$i];
		$rgp = $rgx[$i];
		$usp = $rxuses[$i];
		$vlp = $rxv[$i];
		//check system volume and cat
		$did = mysqli_query($link, "select * from drug_id where id='$idp' ");
		while($row2 = mysqli_fetch_array($did))
		{
           if(empty($row2['id'])) 
           {
            goto Next_item;
           }
		      $svol = $row2['volume'];
		      $resvol = $row2['volreserve'];
		}
		if ($vlp > $svol-$resvol)
		{
		  $vlp = $svol-$resvol;
		}
		{
		mysqli_query($link, "UPDATE $tmp SET
			`idrx$i` = '$idp',
			`rx$i` = '$rxp',
			`rxg$i` = '$rgp',
			`$us` = '$usp',
			`$vl` = '$vlp'
			");
		}
		//now update reservolume
        //update drug_id at volreserve return volume.
        //update reserve volume at drug_id
        
        $volreserve = $resvol + $vlp;
        $idres=$idp;
        
        $stmt->execute();
        ///update reserve volume at drug_id end
		
		//
		Next_item:
	}
?>
