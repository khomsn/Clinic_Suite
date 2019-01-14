<?php 

include '../../config/dbc.php';

page_protect();

$id = $_SESSION['patdesk'];

$result1 = mysqli_query($link, "SELECT * FROM  debtors WHERE ctmid = '$id'");

while ($row = mysqli_fetch_array($result1))
{
  $ctmacno = $row['ctmacno'];
  $olddeb = $row['price'];
}

if (($_POST['pay'] == "ชำระหนี้") OR ($_POST['pay'] == "ตัดยอดหนี้สูญ"))
{
    $pay = $_POST['payprice'];
    if (ltrim($pay) !== '')
    {
      $newdeb = $olddeb - $pay;

	//debtors account update
	if($newdeb <= 0)
	{
		mysqli_query($link, "DELETE FROM `debtors` WHERE `ctmid` = $id ") or die(mysqli_error($link));
	}	
	elseif($newdeb > 0)
	{
		mysqli_query($link, "DELETE FROM `debtors` WHERE `ctmid` = $id ") or die(mysqli_error($link));
		$sql_insert = "INSERT INTO `debtors` (`ctmid`,`ctmacno`,`price`)
						VALUES ('$id','$ctmacno','$newdeb');";
		mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
	}	
	
	//daily_account on debtor;
	if($olddeb !== 0)
	{
		if($pay <= $olddeb)
		{
		if ($_POST['pay'] == "ตัดยอดหนี้สูญ")
		{
                    $acin = 59999999; //59999999 ตัดยอด
                    $detail = "ตัดยอดหนี้สูญ";
		}
		else
		{
                    $acin = 10000001; //10000001 เงินสด
                    $detail = "รับชำระหนี้เงินสด";
		}
			$sql_insert = " INSERT INTO `daily_account` ( `date` , `ac_no_i` , `ac_no_o` , `detail` , `price` , `type`, `bors`, `recordby`	)
											VALUES (now(), '$acin', '$ctmacno', '$detail', '$pay', 'd', 's','$_SESSION[user_id]' );";
			// Now insert Patient to "patient_id" table
			mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
		}
	}
    }
header("Location: paydeb.php");  
}

$title = "::ใบเสร็จรับเงิน::";
include '../../main/header.php';
?>
</head>
<body>
<form action="paydeb.php" method="post" name="searchForm" id="searchForm">
 <div style="text-align: center;">
  <h2 class="titlehdr">ยอดหนี้คงค้าง</h2>
  <br>
  <br>
  มีหนี้คงค้าง = <?php echo $olddeb;?> บาท
  <hr style="width: 80%; height: 2px;"><br>
  ชำระ <input type=number name=payprice min=0 step=1> บาท
  <hr style="width: 80%; height: 2px;"><br>
  <input type=submit name=pay value="ชำระหนี้">
  <?php
        if ($_SESSION['user_accode']%13 == 0)
        {
            echo "<input type='submit' name='pay' value='ตัดยอดหนี้สูญ'>";
        }
  ?>
 </div>
 </form>
</body>
</html>
