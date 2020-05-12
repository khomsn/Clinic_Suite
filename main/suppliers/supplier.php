<?php 
include '../../config/dbc.php';
page_protect();
$err = array();

$sql_create = "CREATE TABLE IF NOT EXISTS `supplier` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tel` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ac_no` int(11) NOT NULL,
  `paydetail` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

mysqli_query($link, $sql_create);

$sql_select =  mysqli_query($link, "SELECT * FROM `initdb` WHERE `refname`='supplier' ORDER BY `id`" );
$tableversion = mysqli_num_rows($sql_select);
if(!$tableversion)
{
    $sql  = "INSERT INTO `initdb` (`refname`, `version`) VALUES (\'supplier\', \'1\')";
    mysqli_query($link, $sql);
}

if(($_POST['register'] == 'ตกลง')  AND (ltrim($_POST['name']!== '')))
{ 

    $email = mysqli_real_escape_string($link,$_POST['email']);
    $name = mysqli_real_escape_string($link,$_POST['name']);
    $address = mysqli_real_escape_string($link,$_POST['address']);
    //assign account no. 21000001-29999999 เจ้าหนี้ ซื้อ ยา และ อุปกรณ์ //start with 21000000 + sp_id
    $ac = mysqli_query($link, "SELECT * FROM supplier");
    $mmm=21000000;
    $maxm =29999999;
    while($row = mysqli_fetch_array($ac))
    { if( $mmm < $row['ac_no']){ $mmm=$row['ac_no'];}}
    $ac = $mmm + 1;
    if($ac>=29999999){ $err[]="No more Account number to assign, please check!"; goto ErrJP;}

    //assign ac_no to table acnumber
    $sql_insert = "INSERT into `acnumber`
                (`ac_no`, `name`)
                VALUES
                ('$ac','$name')";
    mysqli_query($link, $sql_insert);

    // assign insertion pattern
    $sql_insert = "INSERT into `supplier`
                (`name`,`address`,`tel`, `email`, `agent`, `mobile` , `ac_no` )
                VALUES
                ('$name','$address','$_POST[tel]','$email','$_POST[agent]','$_POST[mobile]','$ac')";
    mysqli_query($link, $sql_insert);

    // Then get Supplier ID to process to other step.
    $result = mysqli_query($link, "SELECT * FROM supplier
    WHERE name='$_POST[name]' ");

    $row = mysqli_fetch_array($result);

    $id = "sp_".$row['id'];
    $sql_insert ="

    CREATE TABLE `$id` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `date` date NOT NULL,
    `inid` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
    `inv_num` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `price` decimal(9,2) NOT NULL,
    `payment` tinyint(1) NOT NULL,
    `duedate` DATE NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    ";

    mysqli_query($link, $sql_insert);
    // go on to other step
    header("Location: supplier.php");  

}
ErrJP:

$title = "::Supplier::";
include '../../main/header.php';
include '../../main/bodyheader.php';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="200" valign="top">
		<?php 
			/*********************** MYACCOUNT MENU ****************************
			This code shows my account menu only to logged in users. 
			Copy this code till END and place it in a new html or php where
			you want to show myaccount options. This is only visible to logged in users
			*******************************************************************/
			if (isset($_SESSION['user_id']))
			{
				include 'spmenu.php';
			} 
		/*******************************END**************************/
		?>
		</td><td>
			<h3 class="titlehdr">บันทึก Supplier</h3>
			<form method="post" action="supplier.php" name="regForm" id="regForm">
				<table style="text-align: left; width: 703px; height: 413px;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr><td style="width: 646px; vertical-align: middle; background-color: rgb(152, 161, 76);">
							<div style="text-align: center;">ชื่อ* <input size="50" tabindex="1" name="name" class="required" > 
							</div>
							<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;"><br>
							<div style="text-align: center;">ที่อยู่* <textarea tabindex="2" cols="80" rows="3" class="required" name="address"></textarea>
							<br>โทรศัพท์* <input maxlength="20" size="20" class="required" tabindex="3" name="tel">
							</div>
							<hr style="width: 80%; height: 2px;"><br>
							<div style="text-align: center;">
							ตัวแทน* <input tabindex="4" class="required"  name="agent" > 
							&nbsp; &nbsp; &nbsp;โทรศัพท์*<input maxlength="15" class="required" size="15" tabindex="5" name="mobile" ><br>
							E-mail@*<input size="30" tabindex="6" name="email" ><br>
							</div>
							<hr style="width: 80%; height: 2px;"><br>
                    </td></tr>
					<tr><td><br>
						<br>
						<div style="text-align: center;"><input name="register" value="ตกลง" type="submit"></div>
					</td></tr>
				</tbody>
				</table>
				<br>
			</form>
    </td></tr>
</table>
<?php
if(!empty($err))
{
    echo "<div class=\"msg\">";
    foreach ($err as $e) {echo "$e <br>";}
    echo "</div>";
    echo "<br>";
}
?>
</body></html>
