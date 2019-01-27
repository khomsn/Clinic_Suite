<?php 
include '../../config/dbc.php';
page_protect();

$sql_create = "CREATE TABLE IF NOT EXISTS `drugtouse` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `drugid` smallint(6) NOT NULL,
  `volume` smallint(6) NOT NULL,
  `user` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
mysqli_query($link, $sql_create);

$fulluri = $_SERVER['REQUEST_URI'];
$id = filter_var($fulluri, FILTER_SANITIZE_NUMBER_INT);

$stock_in = mysqli_query($link, "select * from drug_id where id='$id' ");
$drugtable = "drug_".$id;

while ($row_settings = mysqli_fetch_array($stock_in))
{
	$volume = $row_settings['volume']; //get volume to update
	$dacno = $row_settings['ac_no']; //get account no into stock
}


if($_POST['doSave'] == 'Save')  
{
  if($volume >= $_POST['volume'])
    {
      $sql_insert = "INSERT into `drugtouse`	(`date`, `drugid`, `volume`,`user`)
				      VALUES  (now(),'$id','$_POST[volume]','$_SESSION[user_id]')";
      // Now insert Drug order information to "drug_#id" table
      mysqli_query($link, $sql_insert);


      // Update drug_id at volume
      $upvol = $volume - $_POST['volume'];
      mysqli_query($link, "UPDATE drug_id SET `volume` = '$upvol' WHERE `id` = '$id'");	

      $ctv = $_POST['volume'];

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
      $alldp = $price*$_POST['volume'];
      
      // accounting system
	if($_POST['RMat']==1)
	{
	      // assign insertion pattern
	      $detail ="เบิกประกอบสินค้า"; //10300000 ตัดยอด
	      $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`recordby`)
			    VALUES  (now(),'10300000','$dacno','$detail','$alldp','c','$_SESSION[user_id]')";
	      mysqli_query($link, $sql_insert);
	 }
	 else
	 {
	      // assign insertion pattern 59999999 ตัดยอด
	      $detail ="เบิกใช้";
	      $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`recordby`)
			    VALUES  (now(),'59999999','$dacno','$detail','$alldp','c','$_SESSION[user_id]')";
	      mysqli_query($link, $sql_insert);
	 }     
    //record drug use per month in dupm table for statistics
    $dupmin = mysqli_query($link, "SELECT * FROM dupm WHERE drugid = '$id' AND MONTH(mon) = MONTH(CURRENT_DATE()) AND YEAR(mon) = MONTH(CURRENT_DATE())");
    $dupmo = mysqli_fetch_array($dupmin);
    if(empty($dupmo))
    {
      $sql_insert = "INSERT into `dupm`
		(`drugid`,`mon`,`vol`)
	    VALUES
		('$id',now(),'$_POST[volume]')";
	mysqli_query($link, $sql_insert);
    
    }
    else
    {
      $newvol = $dupmo['vol'] + $_POST['volume'];
      
      $sql_insert = "UPDATE `dupm` SET `mon` = now(),`vol` = '$newvol'
				      WHERE drugid = '$id' AND MONTH(mon) = MONTH(CURRENT_DATE()) AND YEAR(mon) = MONTH(CURRENT_DATE()) LIMIT 1 ; 
				      ";
      mysqli_query($link, $sql_insert);
    
    }
      // go on to other step
      header("Location: drtouse.php");  
    }
    else
    {
       // go on to other step
      header("Location: del_error.php");  
    }
 }

$title = "::เบิก ยาและผลิตภัณฑ์::";
include '../../main/header.php';
include '../../main/bodyheader.php';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="160" valign="top"><div class="pos_l_fix">
		<?php 
		/*********************** MYACCOUNT MENU ****************************
		This code shows my account menu only to logged in users. 
		Copy this code till END and place it in a new html or php where
		you want to show myaccount options. This is only visible to logged in users
		*******************************************************************/
		if (isset($_SESSION['user_id'])) 
		{
			include 'drugmenu.php';
		} 
		/*******************************END**************************/
		?>
		</td>
		<td width="732" valign="top">
			<h3 class="titlehdr">เบิก ยาและผลิตภัณฑ์: </h3>
				<?php
					$stock_in = mysqli_query($link, "select * from drug_id where id='$id' ");
					while ($row_settings = mysqli_fetch_array($stock_in))
					{
						echo "<div class='bgca5ffd5'><big><big><big><big>";
						echo $row_settings['dname'];
						echo "&nbsp;ขนาด :&nbsp;";
						echo $row_settings['size'];
						echo "&nbsp;คงคลัง :&nbsp;";
						echo $row_settings['volume'];
						echo "</big></big></big></big></div>";
						$RMat = $row_settings['RawMat'];
					}
				?>
			 <form action="stockout.php?msg=<?php echo $id?>" method="post" name="inForm" id="inForm">
				<table width="90%" border="0" align="center" cellpadding="3" cellspacing="3" class="forms">
					<tr><td>
							<p align="center">
							<?php 
							  if ($RMat ==1)
							  {
							      echo "<input type='radio' name='RMat' value='1'>เบิกประกอบสินค้า";
							      echo "<input type='radio' name='RMat' value='2'>เบิกใช้-ตัดยอด  |";
							  }
							?> 
							จำนวน <input type="text" name="volume">	
							<input name="doSave" type="submit" id="doSave" value="Save">
							</p>
					</td></tr>
				</table>	
			  </form>
		<td width="196" valign="top">&nbsp;</td>
	</tr>
</table>
</body>
</html>
