<?php 
include '../login/dbc.php';
page_protect();
date_default_timezone_set('Asia/Bangkok');

if($_SESSION['sm'] =='')
{
$_SESSION['sm'] = date("m");
$_SESSION['sy'] = date("Y");
}
include '../libs/progdate.php';
if($_SESSION['page'] =='')
{
	$_SESSION['page']=1;
	$_SESSION['i']=1;
	$_SESSION['buy']=1;
	$_SESSION['alldrugleft']=0;
}	

$fulluri = $_SERVER['REQUEST_URI'];
$trimString = "/clinic/docform/";
$actsite = trim($fulluri, $trimString);
$dtype = mysqli_query($link, "SELECT * FROM drug_id WHERE  id = '$_SESSION[drugid]' ");
while($row = mysqli_fetch_array($dtype))
{
	$dname = $row['dname'];
	$dgname = $row['dgname'];
	$size = $row['size'];
}
$drugid = $_SESSION['drugid']; 
$presthismon[] = 0;
$oldvo[] = 0;
$nbvo[] = 0;
$oldvo = 0;

if($_POST['nopage'] == 'ถัดไป')
{	
	$_SESSION['page'] = $_SESSION['page']+1;
//	header("Location: bj8.php");
}	
?>
<!DOCTYPE html>
<html>
<!--This file was converted to xhtml by OpenOffice.org - see http://xml.openoffice.org/odf2xhtml for more info.  th_TH.UTF-8 -->
<head profile="http://dublincore.org/documents/dcmi-terms/">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title xml:lang="th_TH.UTF-8">บจ.8</title>
<meta name="DCTERMS.title" content="" xml:lang="th_TH.UTF-8"/>
<meta name="DCTERMS.language" content="th_TH.UTF-8" scheme="DCTERMS.RFC4646"/>
<meta name="DCTERMS.source" content="http://xml.openoffice.org/odf2xhtml"/>
<meta name="DCTERMS.issued" content="2009-09-16T04:56:30" scheme="DCTERMS.W3CDTF"/>
<meta name="DCTERMS.modified" content="2009-09-16T05:16:55" scheme="DCTERMS.W3CDTF"/>
<meta name="DCTERMS.provenance" content="" xml:lang="th_TH.UTF-8"/>
<meta name="DCTERMS.subject" content="," xml:lang="th_TH.UTF-8"/>
<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" hreflang="en"/>
<link rel="schema.DCTERMS" href="http://purl.org/dc/terms/" hreflang="en"/>
<link rel="schema.DCTYPE" href="http://purl.org/dc/dcmitype/" hreflang="en"/>
<link rel="schema.DCAM" href="http://purl.org/dc/dcam/" hreflang="en"/>
<base href="."/>
<link href="../public/css/bj8.css" rel="stylesheet" type="text/css">
<script language="javascript">
function Clickheretoprint()
{ 
  var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
      disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25"; 
  var content_vlue = document.getElementById("print_content").innerHTML; 
  
  var docprint=window.open("","",disp_setting); 
   docprint.document.open(); 
   docprint.document.write('<html><head><title>Print</title>'); 
   docprint.document.write('<link href="../public/css/bj8.css" rel="stylesheet" type="text/css">'); 
   docprint.document.write('</head><body onLoad="self.print()"><center>');          
   docprint.document.write(content_vlue);          
   docprint.document.write('</center></body></html>'); 
   docprint.document.close(); 
   docprint.focus(); 
}
</script>
</head>
<body dir="ltr" style="max-width:11.6902in;background-color:transparent; ">
<form method="post" action="bj8.php" name="regForm" id="regForm">
<?php 
$param = mysqli_query($link, "SELECT * FROM parameter WHERE ID ='1'");
		while($row = mysqli_fetch_array($param))
		{
		$clinic_name = $row['name'];
		$cliniclcid = $row['cliniclcid'];
		$name_lc = $row['name_lc'];
		$cl_addr = $row['address'];
		$cl_lcid = $row['lcid'];
		}


if ($actsite == "bj8.php")
{
echo "<input type='submit' name='todom' value = '<<'>&nbsp;<input type='submit' name='todom' value = '@'>&nbsp;";
	if ($sm < date("m"))
	{
		if ($sy <= date("Y"))
		{
		echo "<input type='submit' name='todom' value = '>>'>";
		}
	}
	if ($sy <= date("Y"))
	{
		if ($sm > date("m"))
		{
		echo "<input type='submit' name='todom' value = '>>'>";
		}
	}
}
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
//get data
		$dtype = mysqli_query($link, "SELECT * FROM drug_$drugid ORDER BY `id` ASC ");
		$nofrow = 1;
		while($row = mysqli_fetch_array($dtype))
		{
			$rdate = new DateTime($row['date']);
			$sdp[$nofrow] = $rdate->format("d");
			$smp[$nofrow] = $rdate->format("m");
			$syp[$nofrow] = $rdate->format("Y");
						//ยอดยกมา	
				if($row['volume'] > $row['customer'])
					{
						if ($syp[$nofrow] < $sy)
						{
						$mkloldm = $row['mklot'];
						$oldvo = $row['volume'] - $row['customer'];
						}
						if($syp[$nofrow] == $sy)
						{
							if ($smp[$nofrow] < $sm)
							{
								$mkloldm = $row['mklot'];
								$oldvo = $row['volume'] - $row['customer'];
							}	
						}	
					}	
			if( $smp[$nofrow]==$sm AND $syp[$nofrow]==$sy)
			{
			$spold[$nofrow] = $row['supplier'];
			$mknold[$nofrow] = $row['mkname'];
			$mkpold[$nofrow]= $row['mkplace'];
			$mklold[$nofrow] = $row['mklot'];
			$mkanold[$nofrow] =$row['mkanl'];
			$mkunold[$nofrow] = $row['mkunit'];
			$volp[$nofrow] = $row['volume'];
			$cust[$nofrow] = $row['customer'];
			$bmax = $nofrow;
			$nofrow =$nofrow +1;
			}
		}
		$dtrack = mysqli_query($link, "SELECT * FROM tr_drug_$drugid ORDER BY `date` ASC ");
		$norow = 1;
		while($row = mysqli_fetch_array($dtrack))
		{
			$sdate = new DateTime($row['date']);
			$stcd[$norow] = $sdate->format("d");
			$stcm[$norow] = $sdate->format("m");
			$stcy[$norow] = $sdate->format("Y");
			if( $stcm[$norow]==$sm AND $stcy[$norow]==$sy)
			{
				$pt_id[$norow] = $row['pt_id'];
				$ctmvol[$norow] =$row['volume'];
				
				$custo = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE id = $pt_id[$norow] ");
				while($rowc =mysqli_fetch_array($custo))
				{
					$ptname[$norow] = $rowc['fname'].' '.$rowc['lname'].' '.$rowc['ctz_id'];
					$ptaddre[$norow] = $rowc['address1']." หมู่ ".$rowc['address2']." ต. ".$rowc['address3']." อ.".$rowc['address4']." จ.".$rowc['address5'];
					$birthday = new DateTime($rowc['birthday']);
					$yob = $birthday->format("Y");
					$ptage[$norow] = date("Y") - $yob;
				}
				$ptmax = $norow;
				$norow =$norow +1;
			}
		}
	//$prescript this month 
			$dtype2 = mysqli_query($link, "SELECT * FROM tr_drug_$drugid ORDER BY `date` ASC ");
			while($row2 = mysqli_fetch_array($dtype2))
			{
				$trdate = new DateTime($row2['date']);
				$trmon = $trdate->format("m");
				$try = $trdate->format("Y");
				if($try == $sy AND $trmon == $sm)
				{ 
				//จ่าย ในเดือนนี้
					$presth = $presth + $row2['volume'];
				}
			}
		if($oldvo != 0)
			{
				$oldvo = $presth + $oldvo;
			}
	// จบ ยอดยกมา	
	$allrow = $bmax+$ptmax+1;
	$_SESSION['mpage'] = ceil($allrow/12);
	$rotab=1;
	$page = $_SESSION['page'];

echo "&nbsp;&nbsp;หน้า&nbsp;&nbsp;&nbsp;";
if($_SESSION['page'] < $_SESSION['mpage']) echo "<input type='submit' name='nopage' value='ถัดไป'>";

?>
</form>

<div align="center"><a href="javascript:Clickheretoprint()">Print</a></div><br>

<div class="style3" id="print_content">
<div class="page"><div class="subpage">
<table border="0" style="text-align: center; margin-left: 50px; margin-right: auto;" >
	<tr><td>
<?php 

$alldrugleft= $_SESSION['alldrugleft'];

if($rotab<= 12)
{
?>	
			<table border="0" cellspacing="0" cellpadding="0" class="ta1">
				<colgroup>
					<col width="72"/><col width="118"/><col width="100"/><col width="70"/><col width="133"/><col width="36"/>
					<col width="188"/><col width="84"/><col width="84"/><col width="84"/><col width="84"/><col width="76"/><col width="1"/>
				</colgroup>
				<tr class="ro3">
					<td style="text-align:left;width:0.6445in; " class="ce1"> </td>
					<td style="text-align:left;width:1.0591in; " class="ce1"> </td>
					<td style="text-align:left;width:0.852in; " class="ce1"> </td>
					<td style="text-align:left;width:0.6335in; " class="ce1"> </td>
					<td style="text-align:left;width:1.2016in; " class="ce1"> </td>
					<td style="text-align:left;width:0.3272in; " class="ce1"> </td>
					<td style="text-align:left;width:1.6929in; " class="ce1"> </td>
					<td colspan="5" style="text-align:left;width:0.7535in; " class="ce3"><p>ใบอนุญาตให้มีไว้ในครอบครองฯ เลขที่. <?php echo $cl_lcid;
					?></p></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<tr class="ro3">
					<td colspan="12" style="text-align:left;width:0.6445in; " class="ce2"><p>บัญชีรับ – จ่ายวัตถุออกฤทธิ์ที่มีไว้ในครอบครอง</p></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<tr class="ro3">
					<td colspan="12" style="text-align:left;width:0.6445in; " class="ce2"><p>ชื่อผู้รับอนุญาต 
					<?php 
					echo $name_lc;
					?>      สถานที่ชื่อ <?php echo $clinic_name." ใบอนุญาตเลขที่ ".$cliniclcid;?></p></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<tr class="ro3">
					<td colspan="12" style="text-align:left;width:0.6445in; " class="ce2"><p>สถานที่ตั้ง   
					<?php echo $cl_addr;?></p></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<tr class="ro3">
					<td style="text-align:left;width:0.6445in; " class="ce1"><p>แบบ บจ 8</p></td>
					<td style="text-align:left;width:1.0591in; " class="ce1"> </td>
					<td style="text-align:left;width:0.852in; " class="ce1"> </td>
					<td style="text-align:left;width:0.6335in; " class="ce1"> </td>
					<td style="text-align:left;width:1.2016in; " class="ce1"> </td>
					<td style="text-align:left;width:0.3272in; " class="ce1"> </td>
					<td style="text-align:left;width:1.6929in; " class="ce1"> </td>
					<td style="text-align:left;width:0.7535in; " class="ce1"> </td>
					<td style="text-align:left;width:0.7535in; " class="ce1"> </td>
					<td style="text-align:left;width:0.7535in; " class="ce1"> </td>
					<td style="text-align:left;width:0.7535in; " class="ce1"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce1"><p> หน้า ...<?php echo $page; ?>...</p></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<tr class="ro3">
					<td rowspan="2" style="text-align:left;width:0.6445in; " class="ce3"><p>วันเดือนปี</p></td>
					<td rowspan="2" style="text-align:left;width:1.0591in; " class="ce3"><p>ชื่อวัตถุออกฤทธิ์</p></td>
					<td rowspan="2" style="text-align:left;width:0.952in; " class="ce71"><p>เลขที่หรืออักษร<br>ของ<br>ครั้งที่ผลิต</p></td>
					<td rowspan="2" style="text-align:left;width:0.6335in; " class="ce3"><p>ได้มาจาก</p></td>
					<td colspan="3" style="text-align:left;width:1.2016in; " class="ce3"><p>จ่ายไปให้</p></td>
					<td colspan="4" style="text-align:left;width:0.7535in; " class="ce3"><p>ปริมาณวัตถุออกฤทธิ์ (<?php echo $mkunold[1]; ?>)</p></td>
					<td rowspan="2" style="text-align:left;width:0.6882in; " class="ce71"><p>ผู้รับอนุญาต<br>ให้มีไว้ใน<br>ครอบครอง</p></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<tr class="ro3">
					<td style="text-align:left;width:1.2016in; " class="ce4"><p>ชื่อผู้รับยา</p></td>
					<td style="text-align:left;width:0.3272in; " class="ce4"><p>อายุ</p></td>
					<td style="text-align:left;width:1.6929in; " class="ce4"><p>ที่อยู่</p></td>
					<td style="text-align:left;width:0.7535in; " class="ce4"><p>ยกมา</p></td>
					<td style="text-align:left;width:0.7535in; " class="ce4"><p>รับ</p></td>
					<td style="text-align:left;width:0.7535in; " class="ce4"><p>จ่าย</p></td>
					<td style="text-align:left;width:0.7535in; " class="ce4"><p>คงเหลือ</p></td>
					<td style="text-align:left;width:0.01in; " class="Default">  </td>
				</tr>
		<?php 
		if($_SESSION['page']==1)
		{
			if($oldvo != 0)
			{
			?>	
				<tr class="ro3">
					<td style="text-align:left;width:0.6445in; " class="ce5"><?php 
					echo "1";
					echo "&nbsp;";
									$m = $sm;
									switch ($m)
									{
										 case 1:
										 echo "มค";
										 break;
										 case 2:
										 echo "กพ";
										 break;
										 case 3:
										 echo "มีค";
										 break;
										 case 4:
										 echo "เมย";
										 break;
										 case 5:
										 echo "พค";
										 break;
										 case 6:
										 echo "มิย";
										 break;
										 case 7:
										 echo "กค";
										 break;
										 case 8:
										 echo "สค";
										 break;
										 case 9:
										 echo "กย";
										 break;
										 case 10:
										 echo "ตค";
										 break;
										 case 11:
										 echo "พย";
										 break;
										 case 12:
										 echo "ธค";
										 break;
									}?>. <?php $s = $bsy; if($s>=2000) $s = $s - 2500; echo $s;?></td>
					<td style="text-align:left;width:1.0591in; " class="ce5"><?php echo $dgname.' '.$size.' ('.$dname.')' ; ?> </td>
					<td style="text-align:left;width:0.852in; " class="ce5"><?php echo $mkloldm; ?></td>
					<td style="text-align:left;width:0.6335in; " class="ce5"> <?php echo $_SESSION['oldsp']; ?> </td>
					<td style="text-align:left;width:1.2016in; " class="ce5"> </td>
					<td style="text-align:left;width:0.3272in; " class="ce5"> </td>
					<td style="text-align:left;width:1.6929in; " class="ce5"> </td>
					<td style="text-align:left;width:0.7535in; " class="ce5"><?php echo $oldvo; ?> </td>
					<td style="text-align:left;width:0.7535in; " class="ce5"></td>
					<td style="text-align:left;width:0.7535in; " class="ce5"></td>
					<td style="text-align:left;width:0.7535in; " class="ce5"> <?php echo $oldvo; $alldrugleft = $oldvo; ?>  </td>
					<td style="text-align:left;width:0.6882in; " class="ce9"> </td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
		<?php 
				$rotab = $rotab +1;
			}
		}
		for($i = $_SESSION['i']; $i <= $ptmax; $i++)
		{
			if($rotab <= 12)
			{
				
			for($j = $_SESSION['buy']; $j <= $bmax; $j++)
					{
						if($stcd[$i]>=$sdp[$j] AND $smp[$j]==$stcm[$i] )
						{
							if($rotab <= 12)
							{
							
					?>	
							<tr class="ro3">
								<td style="text-align:left;width:0.6445in; " class="ce5"><?php 
								echo $sdp[$j];
								echo "&nbsp;";
											$m = $sm;
											switch ($m)
											{
												 case 1:
												 echo "มค";
												 break;
												 case 2:
												 echo "กพ";
												 break;
												 case 3:
												 echo "มีค";
												 break;
												 case 4:
												 echo "เมย";
												 break;
												 case 5:
												 echo "พค";
												 break;
												 case 6:
												 echo "มิย";
												 break;
												 case 7:
												 echo "กค";
												 break;
												 case 8:
												 echo "สค";
												 break;
												 case 9:
												 echo "กย";
												 break;
												 case 10:
												 echo "ตค";
												 break;
												 case 11:
												 echo "พย";
												 break;
												 case 12:
												 echo "ธค";
												 break;
											}?>. <?php $s = $bsy; if($s>=2000) $s = $s - 2500; echo $s;?></td>
							<td style="text-align:left;width:1.0591in; " class="ce5"><?php echo $dgname.' '.$size.' ('.$dname.')' ; ?> </td>
							<td style="text-align:left;width:0.852in; " class="ce5"><?php echo $mklold[$j]; ?></td>
							<td style="text-align:left;width:0.6335in; " class="ce5"><?php echo $_SESSION['oldsp'] = $spold[$j]; ?> </td>
							<td style="text-align:left;width:1.2016in; " class="ce5"> </td>
							<td style="text-align:left;width:0.3272in; " class="ce5"> </td>
							<td style="text-align:left;width:1.6929in; " class="ce5"> </td>
							<td style="text-align:left;width:0.7535in; " class="ce5"> </td>
							<td style="text-align:left;width:0.7535in; " class="ce5"><?php echo $volp[$j]; ?></td>
							<td style="text-align:left;width:0.7535in; " class="ce5"></td>
							<td style="text-align:left;width:0.7535in; " class="ce5"><?php $alldrugleft = $alldrugleft + $volp[$j]; echo $alldrugleft;?> </td>
							<td style="text-align:left;width:0.6882in; " class="ce9"></td>
							<td style="text-align:left;width:0.01in; " class="Default"><?php $_SESSION['buy'] = $_SESSION['buy']+1; ?></td>
						</tr>
				<?php 
							}
							$rotab = $rotab +1;
						}
					}
					
		?>
				<tr class="ro3">
					<td style="text-align:left;width:0.6445in; " class="ce5"><?php 
					echo $stcd[$i];
					echo "&nbsp;";
									$m = $sm;
									switch ($m)
									{
										 case 1:
										 echo "มค";
										 break;
										 case 2:
										 echo "กพ";
										 break;
										 case 3:
										 echo "มีค";
										 break;
										 case 4:
										 echo "เมย";
										 break;
										 case 5:
										 echo "พค";
										 break;
										 case 6:
										 echo "มิย";
										 break;
										 case 7:
										 echo "กค";
										 break;
										 case 8:
										 echo "สค";
										 break;
										 case 9:
										 echo "กย";
										 break;
										 case 10:
										 echo "ตค";
										 break;
										 case 11:
										 echo "พย";
										 break;
										 case 12:
										 echo "ธค";
										 break;
									}?>. <?php $s = $bsy; if($s>=2000) $s = $s - 2500; echo $s;?></td>
					<td style="text-align:left;width:1.0591in; " class="ce5"><?php echo $dgname.' '.$size.' ('.$dname.')' ; ?> </td>
					<td style="text-align:left;width:0.852in; " class="ce5"><?php echo $mklold[$i]; ?></td>
					<td style="text-align:left;width:0.6335in; " class="ce5"><?php echo $spold[$i]; ?> </td>
					<td style="text-align:left;width:1.2016in; " class="ce5"><?php echo $ptname[$i]; ?> </td>
					<td style="text-align:left;width:0.3272in; " class="ce5"><?php echo $ptage[$i]; ?> </td>
					<td style="text-align:left;width:1.6929in; " class="ce5"><?php echo $ptaddre[$i]; ?> </td>
					<td style="text-align:left;width:0.7535in; " class="ce5"> </td>
					<td style="text-align:left;width:0.7535in; " class="ce5"></td>
					<td style="text-align:left;width:0.7535in; " class="ce5"><?php echo $ctmvol[$i]; ?> </td>
					<td style="text-align:left;width:0.7535in; " class="ce5"><?php $alldrugleft = $alldrugleft - $ctmvol[$i]; echo $alldrugleft;?> </td>
					<td style="text-align:left;width:0.6882in; " class="ce9"> </td>
					<td style="text-align:left;width:0.01in; " class="Default"><?php $_SESSION['i'] = $_SESSION['i'] +1; ?></td>
				</tr>
		<?php 
				$rotab = $rotab +1;
				
			}
		}
		if($rotab == 12)
		{
//			if($i>12){ $i = $i-1; }
//			$_SESSION['i'] = $i;
			$_SESSION['alldrugleft'] = $alldrugleft;
		}	
		?>
				<tr class="ro3">
					<td colspan="7" style="text-align:left;width:0.6445in; " class="ce6"><p>สรุป</p></td>
					<td style="text-align:left;width:0.7535in; " class="ce5"></td>
					<td style="text-align:left;width:0.7535in; " class="ce5"></td>
					<td style="text-align:left;width:0.7535in; " class="ce5"> </td>
					<td style="text-align:left;width:0.7535in; " class="ce5"></td>
					<td style="text-align:left;width:0.6882in; " class="ce9"></td>
					<td style="text-align:left;width:0.01in; " class="Default"></td>
				</tr>
			</table>
		<?php 
}
?>
	</td></tr>
</table>
</div></div></div>
</body>
</html>