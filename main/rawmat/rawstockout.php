<?php 
include '../../config/dbc.php';
page_protect();

$fulluri = $_SERVER['REQUEST_URI'];
$id = filter_var($fulluri, FILTER_SANITIZE_NUMBER_INT);

$stock_in = mysqli_query($link, "select * from rawmat where id='$id' ");
$rawmattable = "rawmat_".$id;

while ($row_settings = mysqli_fetch_array($stock_in))
{
	$volume = $row_settings['volume']; //get volume to update
	$dacno = $row_settings['ac_no']; //get account no into stock
}


if($_POST['doSave'] == 'Save')  
{
  if($volume >= $_POST['volume'])
    {
      $sql_insert = "INSERT into `rawmattouse`	(`date`, `rawmatid`, `volume`,`user`)
				      VALUES  (now(),'$id','$_POST[volume]','$_SESSION[user_id]')";
      // Now insert Drug order information to "rawmat_#id" table
      mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));


      // Update rawmat at volume
      $upvol = $volume - $_POST['volume'];
      mysqli_query($link, "UPDATE rawmat SET `volume` = '$upvol' WHERE `id` = '$id'");	

      $ctv = $_POST['volume'];

      $stock_out = mysqli_query($link, "select * from $rawmattable ");
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
		      // Update rawmat at volume..
		      $upvol = $dcustomer + $dleft;
		      mysqli_query($link, "UPDATE $rawmattable SET `customer` = '$upvol' WHERE `id` = '$rowid'");
		      $ctv = $ctv - $dleft;
		      //$alldp = $alldp + $dprice* $dleft / $dvolume;
	      }	
	      else
	      {
		      // Update rawmat at volume.
		      $upvol = $dcustomer + $ctv;
		      mysqli_query($link, "UPDATE $rawmattable SET `customer` = '$upvol' WHERE `id` = '$rowid'");
		      $ctv = 0;
		      //$alldp = $alldp + $dprice* $ctv / $dvolume;
	      }	
      }	
      // Price to cut
      $alldp = $price*$_POST['volume'];
      
      // accounting system
      //$acc = mysqli_query($link, "SELECT ac_no FROM rawmat WHERE id = $id");
      //while($rowac = mysqli_fetch_array($acc))
      //{ $dacno = $rowac['ac_no'];}
	if($_POST['RMat']==1)
	{
	      // assign insertion pattern 10700000 ตัดยอด
	      $detail ="เบิกประกอบสินค้า";
	      $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`recordby`)
			    VALUES  (now(),'10700000','$dacno','$detail','$alldp','c','$_SESSION[user_id]')";
	      // Now insert Drug order information to "rawmat_#id" table
	      mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
	 }
	 else
	 {
	      // assign insertion pattern 59999999 ตัดยอด
	      $detail ="เบิกใช้";
	      $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`recordby`)
			    VALUES  (now(),'59999999','$dacno','$detail','$alldp','c','$_SESSION[user_id]')";
	      // Now insert Drug order information to "rawmat_#id" table
	      mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
	 }     
      // go on to other step
      header("Location: rawtouse.php");  
    }
    else
    {
       // go on to other step
      header("Location: del_error.php");  
    }
 }


$title = "::เบิก วัตถุดิบ::";
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
			include 'rawmatmenu.php';
		} 
		/*******************************END**************************/
		?>
		</td>
		<td width="732">
			<h3 class="titlehdr">เบิก Raw Material: </h3>
				<?php
					$stock_in = mysqli_query($link, "select * from rawmat where id='$id' ");
					while ($row_settings = mysqli_fetch_array($stock_in))
					{
						echo "<div class='bgca5ffd5'><big><big><big><big>";
						echo "Code:".$row_settings['rawcode'];
						echo "<br>";
						echo $row_settings['rawname'];
						echo "<br>ขนาด:&nbsp;";
						echo $row_settings['size'];
						echo "<br>คงคลัง :&nbsp;";
						echo $row_settings['volume'];
						echo "</big></big></big></big></div>";
						$RMat = $row_settings['rmfpd'];
					}
				?>
			 <form action="rawstockout.php?msg=<?php echo $id?>" method="post" name="inForm" id="inForm">
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
							จำนวน <input type="text" name="volume" autofocus>	
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
