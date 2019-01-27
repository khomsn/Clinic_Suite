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
      mysqli_query($link, $sql_insert);


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
	      }	
	      else
	      {
		      // Update drug_id at volume.
		      $upvol = $dcustomer + $ctv;
		      mysqli_query($link, "UPDATE $drugtable SET `customer` = '$upvol' WHERE `id` = '$rowid'");
		      $ctv = 0;
	      }	
      }	
      // Price to cut
      $alldp = $price*$voltoupdate;
      
    // accounting system
    // assign insertion pattern 59999999 ตัดยอด
    $detail ="เบิกใช้";
    $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`recordby`)
        VALUES  (now(),'59999999','$dacno','$detail','$alldp','c','0')";
    mysqli_query($link, $sql_insert);
    
    //record drug use per month in dupm table for statistics
    $dupmin = mysqli_query($link, "SELECT * FROM dupm WHERE drugid = '$id' AND MONTH(mon) = MONTH(CURRENT_DATE()) AND YEAR(mon) = MONTH(CURRENT_DATE())");
    while($dupmo = mysqli_fetch_array($dupmin))
    {
        $idstat = $dupmo['id'];
        $newvol = $dupmo['vol'] + $voltoupdate;
    }
    if(!empty($idstat))
    {
     
      $sql_insert = "UPDATE `dupm` SET `mon` = now(),`vol` = '$newvol' WHERE id = '$dupmo[id]'; ";
      mysqli_query($link, $sql_insert);
    
    }
    else
    {
        $sql_insert = "INSERT into `dupm` (`drugid`,`mon`,`vol`) VALUES ('$id',now(),'$voltoupdate')";
        mysqli_query($link, $sql_insert);
    }
}
?>
