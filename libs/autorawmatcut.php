<?php

  for($l=1;$l<=$k;$l++)
  {
    //// check is this drug/treatment  or Lab , Drug part
    if(is_numeric(substr($idin[$l], 0, 1)))
    {  
	if($_SESSION['idrx'.$i]==$idin[$l])
	{
	  $outcount[$l] = $outcount[$l]+$_SESSION['rxvol'.$i]*$outvol[$l]/$invol[$l];
	  $tvol[$l] = $outvol[$l]*$outcount[$l];
	  // cut per order don't care for volume , just cut one unit outsetpoint
	  if($outvol[$l]=='-1') $tvol[$l] = $outsetpoint[$l];
	  
	  if($tvol[$l]>=$outsetpoint[$l])
	  {
	    $ctv = floor($tvol[$l]/$outsetpoint[$l]);
	    $acvol = $ctv;
	    //new outcount
	    $outcount[$l] =  $tvol[$l]%$outsetpoint[$l];
	    
		      // check is this drug or rawmat
		      if(is_numeric(substr($idout[$l], 0, 1)))
		      {   
			  $acsys = "drug";
    //		      $drugidout[$l] = substr($idout[$l], 1);
			  $drugidout[$l] = $idout[$l];
			  $drugtable = "drug_".$idout[$l];
			  $tabletoupdate = "drug_id";
			  $ddrug3 = mysqli_query($link, "select * from drug_id WHERE id = $drugidout[$l] ");
			  while ($rowd3 = mysqli_fetch_array($ddrug3))
			  {
				  $volold = $rowd3['volume'];
			    //      $RawMat = $row3['RawMat'];
				  $dacno = $rowd3['ac_no']; //get account no into stock
			  }
		      }
		      elseif(mb_substr($idout[$l], 0, 1 ) =="r" or mb_substr( $idout[$l], 0, 1 ) =="R")
		      {
			  $acsys = "rawmat";
			  $drugidout[$l] = substr($idout[$l], 1);
			  $drugtable = "rawmat_".$drugidout[$l];
			  $tabletoupdate = "rawmat";
			  $ddrug3 = mysqli_query($link, "select * from rawmat WHERE id = $drugidout[$l] ");
			  while ($rowd3 = mysqli_fetch_array($ddrug3))
			  {
				  $volold = $rowd3['volume'];
				  $dacno = $rowd3['ac_no']; //get account no into stock
			  }
		      }
		      
		      $stock_out = mysqli_query($link, "select * from $drugtable ORDER BY `id` ASC ");
		      while ($ctv > 0)
		      {
			      while ($row_settings = mysqli_fetch_array($stock_out))
			      {
				      $dvolume = $row_settings['volume']; //get volume
				      $dprice = $row_settings['price']; //get price
				      $price = $dprice/$dvolume;
				      $dcustomer = $row_settings['customer']; //get volume on customer
				      $rowid = $row_settings['id'];
				      if ($dcustomer < $dvolume) break 1;
			      }
			      $dleft = $dvolume - $dcustomer;
			      if ($dleft <= $ctv)
			      {
				      // Update drug_id at volume and buyprice.
				      $upvol = $dcustomer + $dleft;
				      mysqli_query($link, "UPDATE $drugtable SET `customer` = '$upvol' WHERE `id` = '$rowid'");
				      $ctv = $ctv - $dleft;
			      }	
			      else
			      {
				      // Update drug_id at volume
				      $upvol = $dcustomer + $ctv;
				      mysqli_query($link, "UPDATE $drugtable SET `customer` = '$upvol' WHERE `id` = '$rowid'");
				      $ctv = 0;
			      }	
		      }	
		      // update drug_# here---end----//
			//CHECK TO update reserve volume
			
		      if($acvol>0)
		      {
				$volnew = $volold - $acvol;
				
				if($volnew<0)
				{
				$volnew=0;
				$err[] = "Not enough inventory to Cut";
				goto JPincaseof0;
				}
				
				mysqli_query($link, "UPDATE $tabletoupdate SET `volume` = '$volnew' WHERE `id` = '$drugidout[$l]' ");
				
				//account system and stat
				if(($acsys == "drug") AND ($acvol>0))
				{
				      // Price to cut
				      $alldp = $price*$acvol;
				      
				      // accounting system
				    // assign insertion pattern 59999999 ตัดยอด
				    $detail ="เบิกใช้ จำนวน ".$acvol;
				    $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`recordby`)
						  VALUES  (now(),'59999999','$dacno','$detail','$alldp','c','0')";
				    mysqli_query($link, $sql_insert);
				    
				    //record drug use //record use statistics
				    $sql_insert = "INSERT into `drugtouse`	(`date`, `drugid`, `volume`,`user`)
								    VALUES  (now(),'$drugidout[$l]','$acvol','0')";
				    mysqli_query($link, $sql_insert);
				}
				if(($acsys == "rawmat") AND ($acvol>0))
				{
				      // Price to cut
				      $alldp = $price*$acvol;
				      //if price is 0 don't record in account system
				      if($alldp == 0) goto Next_rawmat1;
				      // assign insertion pattern
				      $detail ="เบิกใช้ จำนวน ".$acvol;
				      $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`recordby`)
						    VALUES  (now(),'59999999','$dacno','$detail','$alldp','c','0')";
				      mysqli_query($link, $sql_insert);
				      //
				      Next_rawmat1:
				      //record use stat
				      $sql_insert = "INSERT into `rawmattouse`	(`date`, `rawmatid`, `volume`,`user`)
								      VALUES  (now(),'$drugidout[$l]','$acvol','0')";
				      mysqli_query($link, $sql_insert);
				      
				}
		      }

	  }

	  JPincaseof0:

	    mysqli_query($link, "UPDATE drugcombset SET  `outcount` = '$outcount[$l]' WHERE  drugidin = '$idin[$l]' AND drugidout = '$idout[$l]'");   

	}
    }
    
    //// check is this drug/treatment  or Lab , Lab part
    elseif(mb_substr($idin[$l], 0, 1 ) =="l" or mb_substr( $idin[$l], 0, 1 ) =="L")
    {  
	//take out l or L from $idin[]
	$str = substr($idin[$l], 1);
	if($labid == $str)
	{
	  $outcount[$l] = $outcount[$l]+$invol[$l]; //volume is always 1.
	  $tvol[$l] = $outvol[$l]*$outcount[$l];
	  // cut per order don't care for volume , just cut one unit outsetpoint
	  if($outvol[$l]=='-1') $tvol[$l] = $outsetpoint[$l];
	  
	  if($tvol[$l]>=$outsetpoint[$l])
	  {
	    $ctv = floor($tvol[$l]/$outsetpoint[$l]);
	    $acvol = $ctv;
	    //new outcount
	    $outcount[$l] =  $tvol[$l]%$outsetpoint[$l];
	    
		      // check is this drug or rawmat
		      if(is_numeric(substr($idout[$l], 0, 1)))
		      {   
			  $acsys = "drug";
    //		      $drugidout[$l] = substr($idout[$l], 1);
			  $drugidout[$l] = $idout[$l];
			  $drugtable = "drug_".$idout[$l];
			  $tabletoupdate = "drug_id";
			  $ddrug3 = mysqli_query($link, "select * from drug_id WHERE id = $drugidout[$l] ");
			  while ($rowd3 = mysqli_fetch_array($ddrug3))
			  {
				  $volold = $rowd3['volume'];
			    //      $RawMat = $row3['RawMat'];
				  $dacno = $rowd3['ac_no']; //get account no into stock
			  }
		      }
		      elseif(mb_substr($idout[$l], 0, 1 ) =="r" or mb_substr( $idout[$l], 0, 1 ) =="R")
		      {
			  $acsys = "rawmat";
			  $drugidout[$l] = substr($idout[$l], 1);
			  $drugtable = "rawmat_".$drugidout[$l];
			  $tabletoupdate = "rawmat";
			  $ddrug3 = mysqli_query($link, "select * from rawmat WHERE id = $drugidout[$l] ");
			  while ($rowd3 = mysqli_fetch_array($ddrug3))
			  {
				  $volold = $rowd3['volume'];
				  $dacno = $rowd3['ac_no']; //get account no into stock
			  }
		      }
		      
		      $stock_out = mysqli_query($link, "select * from $drugtable ORDER BY `id` ASC ");
		      while ($ctv > 0)
		      {
			      while ($row_settings = mysqli_fetch_array($stock_out))
			      {
				      $dvolume = $row_settings['volume']; //get volume
				      $dprice = $row_settings['price']; //get price
				      $price = $dprice/$dvolume;
				      $dcustomer = $row_settings['customer']; //get volume on customer
				      $rowid = $row_settings['id'];
				      if ($dcustomer < $dvolume) break 1;
			      }
			      $dleft = $dvolume - $dcustomer;
			      if ($dleft <= $ctv)
			      {
				      // Update drug_id at volume and buyprice.
				      $upvol = $dcustomer + $dleft;
				      mysqli_query($link, "UPDATE $drugtable SET `customer` = '$upvol' WHERE `id` = '$rowid'");
				      $ctv = $ctv - $dleft;
			      }	
			      else
			      {
				      // Update drug_id at volume
				      $upvol = $dcustomer + $ctv;
				      mysqli_query($link, "UPDATE $drugtable SET `customer` = '$upvol' WHERE `id` = '$rowid'");
				      $ctv = 0;
			      }	
		      }	
		      // update drug_# here---end----//
			//CHECK TO update reserve volume
			
		      if($acvol>0)
		      {
				$volnew = $volold - $acvol;
				
				if($volnew<0)
				{
				$volnew=0;
				$err[] = "Not enough inventory to Cut";
				goto JPincaseof1;
				}
				
				mysqli_query($link, "UPDATE $tabletoupdate SET `volume` = '$volnew' WHERE `id` = '$drugidout[$l]' ");
				
				//account system and stat
				if(($acsys == "drug") AND ($acvol>0))
				{
				      // Price to cut
				      $alldp = $price*$acvol;
				      
				      // accounting system
				    // assign insertion pattern
				    $detail ="เบิกใช้ จำนวน ".$acvol;
				    $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`recordby`)
						  VALUES  (now(),'59999999','$dacno','$detail','$alldp','c','0')";
				    mysqli_query($link, $sql_insert);
				    
				    //record drug use //record use statistics
				    $sql_insert = "INSERT into `drugtouse`	(`date`, `drugid`, `volume`,`user`)
								    VALUES  (now(),'$drugidout[$l]','$acvol','0')";
				    mysqli_query($link, $sql_insert);
				}
				if(($acsys == "rawmat") AND ($acvol>0))
				{
				      // Price to cut
				      $alldp = $price*$acvol;
				      //if price is 0 don't record in account system
				      if($alldp == 0) goto Next_rawmat2;
				      // assign insertion pattern
				      $detail ="เบิกใช้ จำนวน ".$acvol;
				      $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`recordby`)
						    VALUES  (now(),'59999999','$dacno','$detail','$alldp','c','0')";
				      mysqli_query($link, $sql_insert);
				      //
				      Next_rawmat2:
				      //record use stat
				      $sql_insert = "INSERT into `rawmattouse`	(`date`, `rawmatid`, `volume`,`user`)
								      VALUES  (now(),'$drugidout[$l]','$acvol','0')";
				      mysqli_query($link, $sql_insert);

				      
				}
		      }

	  }

	  JPincaseof1:

	    mysqli_query($link, "UPDATE drugcombset SET  `outcount` = '$outcount[$l]' WHERE  drugidin = '$idin[$l]' AND drugidout = '$idout[$l]'");   

	}
    }
  }

?>
