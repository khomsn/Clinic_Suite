<?php 
include '../login/dbc.php';
page_protect();

include '../libs/progdate.php';
include '../libs/trpricecheck.php';

$id = $_SESSION['patcash'];
$ctmid = $id;
$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
$tmp = "tmp_".$id;
$today = date("Y-m-d");


$pin = mysqli_query($linkopd, "select MAX(id) from $pttable");
$rid = mysqli_fetch_array($pin);
/*

$pin = mysqli_query($linkopd, "select * from $pttable where date ='$today' ");
while ($row_settings = mysqli_fetch_array($pin))
	{
		$rid = $row_settings['id'];
	}	
*/
// ไม่อนุญาตให้ ลบ การสั่งยาในขั้นตอนนี้อีก
/*
for($i=1;$i<=10;$i++)
{
	$delrx = "rx".$i;
	if($_POST[$delrx] == 'ลบ')
	{
		$us = "rx".$i."uses";
		$vl = "rx".$i."v";
		mysqli_query($link, "UPDATE $tmp SET
			`idrx$i` = '0',
			`rx$i` = '',
			`rxg$i` = '',
			`$us` = '',
			`$vl` = '0'
			") or die(mysqli_error($link));
		// go on to other step
		header("Location: ptpay.php"); 	
	}
}
for($i=1;$i<=4;$i++)
{
	$trdel ="tr".$i;
	if($_POST[$trdel] == 'ลบ')
	{
		$idtrp ="idtr".$i;
		$trp = "tr".$i;
		$trvp = "trv".$i;
		$tr1o1p = "tr".$i."o1";
		$tr1o1vp ="tr".$i."o1v";
 		$tr1o2p = "tr".$i."o2";
		$tr1o2vp ="tr".$i."o2v";
 		$tr1o3p = "tr".$i."o3";
		$tr1o3vp ="tr".$i."o3v";
 		$tr1o4p = "tr".$i."o4";
		$tr1o4vp ="tr".$i."o4v";
 
		mysqli_query($link, "UPDATE $tmp SET
			`$idtrp` = '0',
			`$trp` = '',
			`$trvp` = '0',
			`$tr1o1p` = '',
			`$tr1o1vp` = '0',
			`$tr1o2p` = '',
			`$tr1o2vp` = '0',
			`$tr1o3p` = '',
			`$tr1o3vp` = '0',
			`$tr1o4p` = '',
			`$tr1o4vp` = '0'
			") or die(mysqli_error($link));
		// go on to other step
		header("Location: ptpay.php"); 	
	}
}
*/
?>

<!DOCTYPE html>
<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="js/jquery.validate.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
	<link rel="stylesheet" href="../public/css/table_alt_color.css">
</head>

<body >
<form method="post" action="ptpay.php" name="regForm" id="regForm">
							<div style="text-align: center;">
							<h2 class="titlehdr"> ยาและผลิตภัณฑ์ ณ วันที่ <?php echo $sd; ?> <?php $m = $sm;
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
			}?> พ.ศ. <?php echo $bsy; //date("Y")+543;?></h2>
							<h3>ชื่อ: &nbsp; 
							<?php
					while ($row_settings = mysqli_fetch_array($ptin))
					{
									echo $row_settings['fname'];
									echo "&nbsp; &nbsp; &nbsp;"; 
									echo $row_settings['lname'];
					}
					$ptin = mysqli_query($linkopd, "select * from $pttable where id = '$rid[0]' ");
							while ($row_settings = mysqli_fetch_array($ptin))
							{
								$inform = $row_settings['inform']; 
								$_SESSION['diag'] = $row_settings['ddx'];
							}
					$disc = mysqli_query($link, "select * from discount WHERE ctmid = $ctmid ");
					while( $rowd = mysqli_fetch_array($disc))
					{
						echo "   &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;   มีสิทธิส่วนลด ";
						echo $rowd['percent'];
						echo " %";
						$perdc = $rowd['percent']/100;
					}	
					echo "</h3>";
					echo "วินิจฉัย: "; echo $_SESSION['diag'];
					echo "<div class=\"msg\"><br>คำแนะนำ: ".$inform."</div>"; 
					
							?>
							</div>
					<?php 
							echo "Treatment:<br> ";
					?>		
							<table class='TFtable' border="1"  style="text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);">									
								<tr>
									<th width = 10 >No</th><th >ชื่อ</th><th width = 75px>ราคา</th><th width = 35px>Vol</th>
									<th width = 75px>รวม</th><!-- <th width = 15px>ลบ</th> -->
								</tr>
								<?php 
								$j = 1;
								for($i =1;$i<=4;$i++)
								{
								$ptin = mysqli_query($link, "select * from $tmp ");
								while ($row = mysqli_fetch_array($ptin))
								{
									$idtr = "idtr".$i;
									$tr ="tr".$i;
									$trv = "trv".$i;
									//echo "<tr><td>".$i."</td><td>";
									if($row[$idtr] !=0)
									{
										echo "<tr><td>".$j."</td><td>";
										echo $row[$tr];
										echo "</td>";
										echo "<td>";
										$did = $row[$idtr];
										//check id if match jump
										for($s=1;$s<=$t;$s++)
										{
										  if($did ==  $tr_drugid[$s]) goto jpoint1;
										}
										$ptin2 = mysqli_query($link, "select * from drug_id WHERE id = $did ");
										if($ptin2 !=0)
										{
										while ($row2 = mysqli_fetch_array($ptin2))
										{
											echo $row2['sellprice'];
											$price1 = $row2['sellprice'] * $row[$trv] - floor($row2['sellprice'] * $row[$trv] * $row2['disct'] * $perdc);
											
											$typen = $row2['typen'];
										}
										}
										jpoint1:
										
										echo "</td>";
										echo "<td>";
										echo $row[$trv];
										echo "</td>";
										echo "<td>";
										//cal price
if($did ==  $tr_drugid[$s])
{
    if($row[$trv]>=$first1[$s]) 
    $price1 = ($row[$trv]-$first1[$s]+1)*$f1price[$s];
    if($row[$trv]>=$sec2[$s]) 
    $price1 = ($row[$trv]-$sec2[$s]+1)*$sec2price[$s]+($sec2[$s]-$first1[$s])*$f1price[$s];
    if($row[$trv]>=$tri3[$s]) 
    $price1 = ($row[$trv]-$tri3[$s]+1)*$tri3price[$s]+($tri3[$s]-$sec2[$s])*$sec2price[$s]+($sec2[$s]-$first1[$s])*$f1price[$s];
}
										
										echo $price1;
										/*
										echo "</td><td>";
										if($typen != "ยาฉีด")
										{
										echo "<input type ='submit' value='ลบ' name='tr";
										echo $i;
										echo "' >";
										}
										*/
										echo "</td></tr>";
										$j = $j+1;
									}
								}
								}
								
							?>	
							</table>
							ยาและผลิตภัณฑ์: <br>
							<table class="TFtable" border="1"  style="text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);" >									
								<tr>
									<th width = 10 >No</th><th>ชื่อ+ขนาด</th><th width=50%>วิธีการใช้</th><th width = 35px>จำนวน</th><!--<th width = 15px>ลบ</th>-->
								</tr>
								<?php 
								$j=1;
								for($i=1;$i<=10;$i++)
								{
								$ptin = mysqli_query($link, "select * from $tmp ");
								while ($row = mysqli_fetch_array($ptin))
								{
									$idrx = "idrx".$i;
									$rx ="rx".$i;
									$rgx = "rxg".$i;
									$rxuses = "rx".$i."uses";
									$rxv = "rx".$i."v";
									if($row[$idrx] !=0)
									{
										echo "<tr><td>".$j."</td><td>";
										echo $row[$rx]."(".$row[$rgx].")";
										echo "</td>";
										echo "<td>";
										echo $row[$rxuses];
										echo "</td>";
										echo "<td>";
										$did = $row[$idrx];
										$ptin2 = mysqli_query($link, "select * from drug_id WHERE id = $did ");
										if($ptin2 !=0)
										{
										while ($row2 = mysqli_fetch_array($ptin2))
										{
										//	echo $row2['sellprice'];
											$price1 = $row2['sellprice'] * $row[$rxv] - floor($row2['sellprice'] * $row[$rxv] * $row2['disct'] * $perdc);
											$typen = $row2['typen'];
										}
										}
									//	echo "</td>";
									//	echo "<td>";
										echo $row[$rxv];
										/*
										echo "</td><td>";
                                        echo $price1;
										echo "</td><td>";
										if($typen != "ยาฉีด")
										{
										echo "<input type ='submit' value='ลบ' name='rx";
										echo $i;
										echo "' >";
										}
										*/
										echo "</td></tr>";
										$j = $j+1;
									}
								}
								}
								?>
							</table>		
</form>
</body></html>
