<?php 
include '../../config/dbc.php';
page_protect();

$dft = $_POST['mt'];
$epd = date("Y-m-d");
$date = date_create("$epd");
if($dft == 1){
date_add($date, date_interval_create_from_date_string('1 month'));
$epd = date_format($date, 'Y-m-d');
}elseif($dft == 2){
date_add($date, date_interval_create_from_date_string('2 month'));
$epd = date_format($date, 'Y-m-d');
}elseif($dft == 3){
date_add($date, date_interval_create_from_date_string('3 month'));
$epd = date_format($date, 'Y-m-d');
}elseif($dft == 6){
date_add($date, date_interval_create_from_date_string('6 month'));
$epd = date_format($date, 'Y-m-d');
}


$drtocut = explode("@", $_POST['cutdrug']);
if($drtocut[0])
{
    $stock_in = mysqli_query($link, "select * from drug_id where id='$drtocut[0]' ");
    $drugtable = "drug_".$drtocut[0];

    while ($row_settings = mysqli_fetch_array($stock_in))
    {
        $volume = $row_settings['volume']; //get volume to update
        $dacno = $row_settings['ac_no']; //get account no into stock
    }
    $stock_out = mysqli_query($link, "select * from $drugtable where id ='$drtocut[1]' ");
    while ($row_settings = mysqli_fetch_array($stock_out))
    {
        $dvolume = $row_settings['volume']; //get volume
        $dprice = $row_settings['price']; //get price
        $price = $dprice/$dvolume;
        $dcustomer = $row_settings['customer']; //get volume on customer
    }
    
    mysqli_query($link, "UPDATE $drugtable SET `customer` = '$dvolume' WHERE `id` = '$drtocut[1]'");
    
    $upvol = $volume - $dvolume + $dcustomer;
    
    mysqli_query($link, "UPDATE drug_id SET `volume` = '$upvol' WHERE `id` = '$drtocut[0]'");
    
    {
        // assign insertion pattern 59999999 ตัดยอด
        $detail ="ตัดยอดยา -ยาหมดอายุ-";
        $alldp = ($dvolume - $dcustomer) * $price;
        $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`recordby`)
            VALUES  (now(),'59999999','$dacno','$detail','$alldp','c','$_SESSION[user_id]')";
        mysqli_query($link, $sql_insert);
    }
}

$title = "::ยาและผลิตภัณฑ์::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
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
		?></div>
		</td><td>
<!--menu--> <form method="post" action="druglowlife.php" name="regForm" id="regForm">
			<h3 class="titlehdr">รายการ ยา จะหมดอายุ ภายใน <?php if($dft ==1 ) echo "1 เดือน";if($dft ==2 ) echo "2 เดือน";if($dft ==3 ) echo "3 เดือน";if($dft ==6 ) echo "6 เดือน";if($dft ==0 or $dft =="") echo "วันนี้";?></h3>
				<table style="text-align: center;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="width: 18px;"></td>
						<td style="vertical-align: middle; ">
						<div style="text-align: center;">
						<?php
							$dtype = mysqli_query($link, "SELECT * FROM drug_id WHERE volume > 0 ORDER BY `dgname` ASC");
								echo "<table class='TFtable' border='1' style='text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);'>";
								echo "<tr><th>No</th><th>ชื่อ</th><th>ชื่อสามัญ</th><th>ขนาด</th><th>จำนวน</th><th>Unit</th><th>ตำแหน่งยา</th><th>วันหมดอายุ</th><th>ตัดยอด</th></tr>";
								while($row = mysqli_fetch_array($dtype))
								 {
                                    $drugtable = "drug_".$row['id'];
                                    $drt = mysqli_query($link, "SELECT * FROM $drugtable where volume != customer AND expdate <='$epd' ORDER BY id DESC;");
                                    while( $spname = mysqli_fetch_array($drt))
                                    {

                                    // Print out the contents of each row into a table
                                    echo "<tr><th>";
                                    echo $row['id'];
                                    echo "</th><th>"; 
                                    echo $row['dname'];
                                    echo "</th><th>"; 
                                    echo $row['dgname'];
                                    echo "</th><th>"; 
                                    echo $row['size'];
                                    echo "</th><th>"; 
                                    echo $spname['volume'] - $spname['customer'];
                                    echo "</th><th>";
                                    echo $row['unit'];
                                    echo "</th><th>";
                                    $loca = $row['location'];
                                    $loca1 = mysqli_query($link, "SELECT `fullplace` FROM `stockplace` WHERE `placeindex` = '$loca' ");
                                    while ($loc = mysqli_fetch_array($loca1)){
                                           echo $loc['fullplace'];
                                           }
                                    echo "</th><th>";
                                    echo $spname['expdate'];
                                    echo "</th><th>";
                                    if($spname['expdate'] < date("Y-m-d"))
                                    echo "<input type=submit name='cutdrug' value ='".$row['id']."@".$spname['id']."'>"; 
                                    echo "</th></tr>";
                                    }
								} 
								echo "</table>";
						?>
							<br>
							</div>
						</td>
					</tr>
				</tbody>
				</table>
		</td>
		<td width="160"><div class="pos_r_fix" style="background-color:rgba(124,200,0,0.65); display:inline-block; text-align: right;">หมดอายุภายใน<select name="mt" id="mt" onchange="this.form.submit()">
		<option value="0" <?php if($dft ==0 ) echo "selected";?> >Today</option>
        <option value="1" <?php if($dft ==1 ) echo "selected";?> >1</option>
        <option value="2" <?php if($dft ==2 ) echo "selected";?> >2</option>
        <option value="3" <?php if($dft ==3 ) echo "selected";?> >3</option>
        <option value="6" <?php if($dft ==6 ) echo "selected";?> >6</option>
    </select>เดือน</div>
</td></tr>
</form>
</table>
<!--end menu-->
</body></html>
