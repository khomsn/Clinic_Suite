<?php 

$id = $idout;

$stock_in = mysqli_query($link, "select * from drug_id where id='$id' ");
$drugtable = "drug_".$id;

while ($row_settings = mysqli_fetch_array($stock_in))
{
	$volume = $row_settings['volume']; //get volume to update
	$dacno = $row_settings['ac_no']; //get account no into stock
}
//$voltoupdate
if($volume >= $voltoupdate)
{
      $sql_insert = "INSERT into `drugtouse`	(`date`, `drugid`, `volume`,`user`)
				      VALUES  (now(),'$id','$voltoupdate','0')";
      // Now insert Drug order information to "drug_#id" table
      mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));


      // Update drug_id at volume
      $upvol = $volume - $voltoupdate;
      mysqli_query($link, "UPDATE drug_id SET `volume` = '$upvol' WHERE `id` = '$id'");	

      $ctv = $voltoupdate;

      $stock_out = mysqli_query($link, "select * from $drugtable ");
      while ($ctv != 0)
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
		      // Update drug_id at volume..
		      $upvol = $dcustomer + $dleft;
		      mysqli_query($link, "UPDATE $drugtable SET `customer` = '$upvol' WHERE `id` = '$rowid'");
		      $ctv = $ctv - $dleft;
		      //$alldp = $alldp + $dprice* $dleft / $dvolume;
	      }	
	      else
	      {
		      // Update drug_id at volume.
		      $upvol = $dcustomer + $ctv;
		      mysqli_query($link, "UPDATE $drugtable SET `customer` = '$upvol' WHERE `id` = '$rowid'");
		      $ctv = 0;
		      //$alldp = $alldp + $dprice* $ctv / $dvolume;
	      }	
      }	
      // Price to cut
      $alldp = $price*$voltoupdate;
      
      // accounting system
      //$acc = mysqli_query($link, "SELECT ac_no FROM drug_id WHERE id = $id");
      //while($rowac = mysqli_fetch_array($acc))
      //{ $dacno = $rowac['ac_no'];}

	      // assign insertion pattern
	      $detail ="เบิกใช้";
	      $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`recordby`)
			    VALUES  (now(),'5999','$dacno','$detail','$alldp','c','0')";
	      // Now insert Drug order information to "drug_#id" table
	      mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
    
    //new 
    //record drug use per month in dupm table for statistics
    $dupmin = mysqli_query($link, "SELECT * FROM dupm WHERE drugid = '$id' AND MONTH(mon) = MONTH(CURRENT_DATE()) AND YEAR(mon) = MONTH(CURRENT_DATE())");
    $dupmo = mysqli_fetch_array($dupmin);
    if(empty($dupmo))
    {
      $sql_insert = "INSERT into `dupm`
		(`drugid`,`mon`,`vol`)
	    VALUES
		('$id',now(),'$voltoupdate')";
	// Now insert Patient to "patient_id" table
	mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
    
    }
    else
    {
    //get old vol to update
      //$oldvol = $dupmo['vol'];
      $newvol = $dupmo['vol'] + $voltoupdate;
      
      $sql_insert = "UPDATE `dupm` SET `mon` = now(),`vol` = '$newvol'
				      WHERE drugid = '$id' AND MONTH(mon) = MONTH(CURRENT_DATE()) AND YEAR(mon) = MONTH(CURRENT_DATE()) LIMIT 1 ; 
				      ";

      // Now insert Patient to "patient_id" table
      mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
    
    }
}


?>