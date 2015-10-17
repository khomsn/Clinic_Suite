<?php 
include '../login/dbc.php';
page_protect();
include '../libs/progdate.php';
?>

<!DOCTYPE html>
<html>
<head>
<title>รายการยาและผลิตภัณฑ์</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
<?php include '../libs/popup.php'; ?>
<?php include '../libs/currency.php'; ?>
	<link rel="stylesheet" href="../public/css/styles.css">
	
<!--<META HTTP-EQUIV="Refresh" CONTENT="10;URL=../main/rawmatusestat.php">-->

<SCRIPT LANGUAGE="JavaScript"><!--
function redirect () { setTimeout("go_now()",100); }
function go_now ()   { window.location.href = "./rawmatusestat.php"; }
//--></SCRIPT>
	
</head>
<?php 
if(!empty($_SESSION['user_background']))
{
echo "<body style='background-image: url(".$_SESSION['user_background'].");' alink='#000088' link='#006600' vlink='#660000'";
$sd = date("d"); if($sd==28) echo "onLoad='redirect()'";
echo ">";
}
else
{
?>
<body style="background-image: url(../image/ptbg.jpg);" alink="#000088" link="#006600" vlink="#660000">
<?php
}
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
		</td>
		<td>
<!--menu-->
			<h3 class="titlehdr">ยอดการใช้ยาและเวชภัณฑ์ ประจำเดือน <?php $m = $sm;// date("m");
			switch ($m)
			{
				 case 1:
				 echo "มกราคม";
				 break;
 				 case 2:
				 echo "กุมภาพันธ์";
				 break;
				 case 3:
				 echo "มีนาคม";
				 break;
				 case 4:
				 echo "เมษายน";
				 break;
				 case 5:
				 echo "พฤษภาคม";
				 break;
				 case 6:
				 echo "มิถุนายน";
				 break;
				 case 7:
				 echo "กรกฎาคม";
				 break;
				 case 8:
				 echo "สิงหาคม";
				 break;
				 case 9:
				 echo "กันยายน";
				 break;
				 case 10:
				 echo "ตุลาคม";
				 break;
				 case 11:
				 echo "พฤศจิกายน";
				 break;
				 case 12:
				 echo "ธันวาคม";
				 break;
			}?> พ.ศ. <?php echo $bsy; //date("Y")+543;?></h3>
				<table style="text-align: center; margin-left: auto; margin-right: auto;height: 413px;" border="1" cellpadding="2" cellspacing="2">
					<tbody>
						<tr>
							<td style="vertical-align: top; background-color: rgb(255, 255, 204);">
								<table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2">
									<tr>
										<th>ID
										</th>
										<th>ชื่อยา
										</th>
										<th>Generic
										</th>
										<th>Size
										</th>
										<th>Stock-Vol
										</th>
										<th>Buy-Vol
										</th>
										<th>Used-Vol
										</th>
										<th>Supp.
										</th>
									</tr>
<?php 	
if($sm == date("m") and $sy == date("Y")) $imax = date("d");
elseif($sm == 1 or $sm == 3 or $sm == 5 or $sm == 7 or $sm == 8 or $sm == 10 or $sm == 12) $imax=31;
elseif($sm == 2 and $sy%4 == 0) $imax = 29;
elseif($sm == 2 and $sy%4 != 0) $imax = 28;
else $imax = 30;

$pin = mysqli_query($link, "select MAX(id) from drug_id");
$rid = mysqli_fetch_array($pin);
$pin = mysqli_query($link, "select * from drug_id ORDER BY `dgname` ASC ");
$i=1;
while($row = mysqli_fetch_array($pin))
{
  $did[$i] = $row['id'];
  $i=$i+1;
}
for ($i = 1;$i<=$rid[0];$i++)
{
	// Print out the contents of each row into a table
		$ddname = mysqli_query($link, "select * from drug_id WHERE id=$did[$i]");
		$ridinf = mysqli_fetch_array($ddname);
	if(!empty($ridinf['dname']))
	{
		echo "<tr><th >";
		echo $i;
		echo "</th><th>";
		echo $ridinf['dname'];
		echo "</th><th>";
		echo $ridinf['dgname'];
		echo "</th><th>";
		echo $ridinf['size'];
		echo "</th><th>";
		echo $ridinf['volume'];
		echo "</th><th>";
	$dtype = mysqli_query($link, "SELECT * FROM drug_$did[$i] WHERE MONTH(date) = '$sm' AND YEAR(date) = '$sy'");
	while($row = mysqli_fetch_array($dtype))
	{
		//$druguse[$i] = $druguse[$i] + $row['customer'];
		$drugbuy[$i] = $drugbuy[$i] + $row['volume'];
		$supp[$i] = $row['supplier'];
	} 
		echo $drugbuy[$i];
		echo "</th><th>";
		$dupmin = mysqli_query($link, "SELECT * FROM dupm WHERE drugid = '$did[$i]' AND MONTH(mon) = '$sm' AND YEAR(mon) = '$sy'");
		$dupmo = mysqli_fetch_array($dupmin);
		echo $dupmo['vol'];
		//echo $druguse[$i];
		echo "</th><th>";
		echo $supp[$i];
		echo "</th></tr>";
	}
//update allrsupm table price calculation
  $dtype = mysqli_query($link, "SELECT * FROM drug_$did[$i]");
  while ($row1 =  mysqli_fetch_array($dtype))
  {
    $allprice[$i] = $allprice[$i]+$row1['price']*$row1['customer']/$row1['volume'];
  }
  
  //get previous month price
//  $allrsu = mysqli_query($link, "select * from allrsupm WHERE drugid=$did[$i] AND MONTH(mandy) < '$sm' AND YEAR(mandy) <= '$sy'");
  //get previous month price
  $allrsu = mysqli_query($link, "select * from allrsupm WHERE drugid=$did[$i] AND mandy < date('$sy-$sm-01')");
  
  while ($row1 =  mysqli_fetch_array($allrsu))
  {
    $omprice[$i] = $omprice[$i]+$row1['price'];
  }
  //get this month price
  $allrsu = mysqli_query($link, "select * from allrsupm WHERE drugid=$did[$i] AND MONTH(mandy) = '$sm' AND YEAR(mandy) = '$sy'");
  $rinfalrsu = mysqli_fetch_array($allrsu);
  $tmprice[$i] = $rinfalrsu['price'];
//check for month to update  if not this current month not update data

if( (date('m') == $sm) AND (date('Y') == $sy))
{
  if($did[$i]!=0)
  {
    if(empty($rinfalrsu['drugid']))
    {
      $tmprice[$i] = $allprice[$i]-$omprice[$i];
      
      if ($tmprice[$i]<0)$tmprice[$i] = 0;
      
      $sql_insert = "INSERT into `allrsupm`
	      (`drugid`,`mandy`,`price`)
	  VALUES
	      ('$did[$i]',now(),'$tmprice[$i]')";
      // Now insert into "allrsupm" table
      mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
    }
    else
    { 
	$tmpricenew = $allprice[$i]-$omprice[$i];
	
	 if ($tmpricenew<0)$tmpricenew = 0;
	 
	$sql_insert = "UPDATE `allrsupm` SET `mandy` = now(),`price` = '$tmpricenew'
					WHERE drugid=$did[$i] AND MONTH(mandy) = '$sm' AND YEAR(mandy) = '$sy' LIMIT 1 ; 
					";

	// Now insert into "allrsupm" table
	mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
    }
  }
//
}

}
?>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<br>
<!--menu end-->
		</td>
		<td width="160" valign="top">
			<div class="pos_r_fix_mypage1">
				<h6 class="titlehdr2" align="center">ประเภทบัญชี</h6>
				<?php 
				/*********************** MYACCOUNT MENU ****************************
				This code shows my account menu only to logged in users. 
				Copy this code till END and place it in a new html or php where
				you want to show myaccount options. This is only visible to logged in users
				*******************************************************************/
				if (isset($_SESSION['user_id']))
				{
					include 'dusmenu.php';
				} 
				/*******************************END**************************/
				?>
			</div>	
		</td>
	</tr>
</table>
<!--end menu-->
</body></html>
