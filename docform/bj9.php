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

$fulluri = $_SERVER['REQUEST_URI'];
$trimString = "/clinic/docform/";
$actsite = trim($fulluri, $trimString);
$dtype_1 = mysqli_query($link, "SELECT * FROM drug_id WHERE  dname = '$_SESSION[drugname]' ");
$i=1;
while($row = mysqli_fetch_array($dtype_1))
{
	$drid[$i] = $row['id'];
	$drname[$i] = $row['dname'];
	$drgname[$i] = $row['dgname'];
	$drsize[$i] = $row['size'];
	$drmax = $i;
	$i = $i+1;
}	
$presthismon[] = 0;
$oldvo[] = 0;
$nbvo[] = 0;
?>
<!DOCTYPE html>
<html>
<!--This file was converted to xhtml by OpenOffice.org - see http://xml.openoffice.org/odf2xhtml for more info.-->
<head profile="http://dublincore.org/documents/dcmi-terms/">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title xml:lang="th_TH.UTF-8">- no title specified</title>
<meta name="DCTERMS.title" content="" xml:lang="th_TH.UTF-8"/>
<meta name="DCTERMS.language" content="th_TH.UTF-8" scheme="DCTERMS.RFC4646"/>
<meta name="DCTERMS.source" content="http://xml.openoffice.org/odf2xhtml"/>
<meta name="DCTERMS.creator" content="narcotic"/>
<meta name="DCTERMS.issued" content="2006-08-24T06:40:36" scheme="DCTERMS.W3CDTF"/>
<meta name="DCTERMS.modified" content="2009-09-14T08:35:16" scheme="DCTERMS.W3CDTF"/>
<meta name="DCTERMS.provenance" content="" xml:lang="th_TH.UTF-8"/>
<meta name="DCTERMS.subject" content="," xml:lang="th_TH.UTF-8"/>
<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" hreflang="en"/>
<link rel="schema.DCTERMS" href="http://purl.org/dc/terms/" hreflang="en"/>
<link rel="schema.DCTYPE" href="http://purl.org/dc/dcmitype/" hreflang="en"/>
<link rel="schema.DCAM" href="http://purl.org/dc/dcam/" hreflang="en"/>
<base href="."/>
<link href="../public/css/bj9.css" rel="stylesheet" type="text/css">
<script language="javascript">
function Clickheretoprint()
{ 
  var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
      disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25"; 
  var content_vlue = document.getElementById("print_content").innerHTML; 
  
  var docprint=window.open("","",disp_setting); 
   docprint.document.open(); 
   docprint.document.write('<html><head><title>Print</title>'); 
   docprint.document.write('<link href="../public/css/bj9.css" rel="stylesheet" type="text/css">'); 
   docprint.document.write('</head><body onLoad="self.print()"><center>');          
   docprint.document.write(content_vlue);          
   docprint.document.write('</center></body></html>'); 
   docprint.document.close(); 
   docprint.focus(); 
}
</script>
</head>
<body dir="ltr">
<form method="post" action="bj9.php" name="regForm" id="regForm">
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
if ($actsite == "bj9.php")
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
?>
</form>

<div align="center"><a href="javascript:Clickheretoprint()">Print</a>    </div><br>
<div class="style3" id="print_content"><div class="page"><div class="subpage">
<table border='1' style='text-align: center; margin-left: auto; margin-right: auto;' >
	<tr><td>
			<table border="0" cellspacing="0" cellpadding="0" class="ta1">
				<colgroup><col width="100"/><col width="145"/><col width="147"/><col width="80"/><col width="81"/><col width="76"/>
						<col width="200"/><col width="60"/><col width="60"/><col width="60"/><col width="140"/>
				</colgroup>
				<tr class="ro1">
					<td colspan="7" style="text-align:center;width:0.7952in; " class="ce1">
					<p>รายงานประจำเดือน ..<?php $m = $sm;
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
			}?>..  พ.ศ..<?php echo $bsy;?>..</p>
					</td>
					<td colspan="4" style="text-align:left;width:0.5665in; " class="ce21"><p>ใบอนุญาตให้มีไว้ในครอบครองฯ เลขที่.<?php echo $cl_lcid;?> </p></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<tr class="ro1">
					<td colspan="7" style="text-align:left;width:0.7992in; " class="ce2">
						<p>ชื่อผู้รับอนุญาต …<?php echo $name_lc;?>…  สถานที่ชื่อ …<?php echo $clinic_name." ใบอนุญาตเลขที่ ".$cliniclcid;?>...</p>
					</td>
					<td colspan="4" style="text-align:left;width:0.5665in; " class="ce21"><p>ID เลขที่................................... </p></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<tr class="ro1">
					<td colspan="7" style="text-align:left;width:0.7992in; " class="ce3">
						<p>อยู่เลขที่ <?php echo $cl_addr; ?></p>
					</td>
					<td style="text-align:left;width:0.5665in; " class="ce11"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce11"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce11"> </td>
					<td style="text-align:left;width:0.9717in; " class="ce11"> </td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<tr class="ro2">
					<td colspan="2" style="text-align:left;width:0.7992in; " class="ce40"><p>แบบ บ.จ. 9</p></td>
					<td style="text-align:left;width:1.3256in; " class="ce11"> </td>
					<td style="text-align:left;width:1.3256in; " class="ce11"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce11"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce11"> </td>
					<td style="text-align:left;width:2.0236in; " class="ce11"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce11"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce11"> </td>
					<td colspan="2" style="text-align:left;width:0.5665in; " class="ce4"><p>          หน้า ….</p></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<tr class="ro3">
					<td rowspan="2" style="text-align:left;width:0.7992in; " class="ce5"><p>วัน เดือน ปี</p></td>
					<td style="text-align:left;width:1.3256in; " class="ce14"><p>ชื่อวัตถุออกฤทธิ์</p></td>
					<td style="text-align:left;width:1.3256in; " class="ce14"><p>ชื่อผู้ผลิต</p></td>
					<td style="text-align:left;width:1.3256in; " class="ce14"><p>เลขที่หรืออักษร</p></td>
					<td style="text-align:left;width:0.6882in; " class="ce14"><p>หมายเลข</p></td>
					<td rowspan="2" style="text-align:left;width:0.6882in; " class="ce5"><p>ได้มาจาก</p></td>
					<td rowspan="2" style="text-align:left;width:2.0236in; " class="ce5"><p>จ่ายไปให้</p></td>
					<td colspan="3" style="text-align:left;width:0.5665in; " class="ce20"><p>ปริมาณวัตถุออกฤทธิ์</p></td>
					<td rowspan="2" style="text-align:center;width:0.9717in; " class="ce24"><p>ผู้รับใบอนุญาต</p></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<tr class="ro1">
					<td style="text-align:left;width:1.3256in; " class="ce15"><p>(ชื่อการค้า)</p></td>
					<td style="text-align:left;width:1.3256in; " class="ce15"><p>และแหล่งผลิต</p></td>
					<td style="text-align:left;width:1.3256in; " class="ce15"><p>ของครั้งที่ผลิต</p></td>
					<td style="text-align:left;width:0.6882in; " class="ce15"><p>วิเคราะห์</p></td>
					<td style="text-align:left;width:0.5665in; " class="ce20"><p>รับ</p></td>
					<td style="text-align:left;width:0.5665in; " class="ce20"><p>จ่าย</p></td>
					<td style="text-align:left;width:0.5665in; " class="ce20"><p>คงเหลือ</p></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
		<?php 
		for($j=1;$j<=$drmax;$j++)
		{
			$dtype = mysqli_query($link, "SELECT * FROM drug_$drid[$j] ORDER BY `id` ASC ");
			while($row = mysqli_fetch_array($dtype))
			{
				$rdate = new DateTime($row['date']);
				$smp = $rdate->format("m");
				$syp = $rdate->format("Y");
				//ยอดยกมา	
				if($row['volume'] > $row['customer'])
					{
						if ($syp < $sy)
						{
						$spold = $row['supplier'];
						$mknold = $row['mkname'];
						$mkpold = $row['mkplace'];
						$mklold = $row['mklot'];
						$mkanold =$row['mkanl'];
						$mkunold = $row['mkunit'];
						$oldvo[$j] = $row['volume'] - $row['customer'];
						}
						if($syp == $sy)
						{
							if ($smp < $sm)
							{
								$spold = $row['supplier'];
								$mknold = $row['mkname'];
								$mkpold = $row['mkplace'];
								$mklold = $row['mklot'];
								$mkanold =$row['mkanl'];
								$mkunold = $row['mkunit'];	
								$oldvo[$j] = $row['volume'] - $row['customer'];
							}	
						}	
					}	
				//ซื้อมา
				if($syp == $sy AND $smp == $sm)
					{
							$nbvo[$j] = $nbvo[$j]+$row['volume'];	
							$nbdate = $row['date'];
							$nbsp = $row['supplier'];
							$nbmkn = $row['mkname'];
							$nbmkp = $row['mkplace'];
							$nbmkl = $row['mklot'];
							$nbmka =$row['mkanl'];
							$nbmku = $row['mkunit'];	
					}
			}	
			
			$dtype2 = mysqli_query($link, "SELECT * FROM tr_drug_$drid[$j] ORDER BY `date` ASC ");
			$iofp=0;
			while($row2 = mysqli_fetch_array($dtype2))
			{
				$trdate = new DateTime($row2['date']);
				$trmon = $trdate->format("m");
				$try = $trdate->format("Y");
				if($try == $sy AND $trmon == $sm)
				{ 
				//จ่าย ในเดือนนี้
					$presthismon[$j] = $presthismon[$j] + $row2['volume'];
					$iofp = $iofp +1; 
					$ctmid[$iofp] = $row2['ctz_id'];					
				}
			}
		if($oldvo[$j] != 0)
			{
			$oldvo[$j] = $presthismon[$j] + $oldvo[$j];
		?>	<tr class='ro1'>
					<td style='text-align:left;width:0.7992in; ' class='ce7'><p>วันที่ 1&nbsp;<?php $m = $sm;
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
									}?>.</p></td>
					<td style='text-align:left;width:1.3256in; ' class='ce161'><?php 
					echo $drgname[$j].'&nbsp'.$drsize[$j];?></td>
					<td style='text-align:left;width:1.3256in; ' class='ce161'><?php echo $mknold;?> </td>
					<td style='text-align:left;width:1.3256in; ' class='ce161'><?php echo $mklold;?> </td>
					<td style='text-align:left;width:0.6882in; ' class='ce161'><?php echo $mkanold;?> </td>
					<td style='text-align:left;width:0.6882in; ' class='ce7'><p> <?php echo $spold; ?>.</p></td>
					<td style='text-align:left;width:2.0236in; ' class='ce16'><p>ยอดคงเหลือจากเดือนที่แล้ว</p></td>
					<td style='text-align:left;width:0.5665in; ' class='ce161'> <?php echo $oldvo[$j];?></td>
					<td style='text-align:left;width:0.5665in; ' class='ce16'> </td>
					<td style='text-align:left;width:0.5665in; ' class='ce16'> </td>
					<td style='text-align:left;width:0.9717in; ' class='ce7'><p>___________</p></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<tr class="ro1">
					<td style="text-align:left;width:0.7992in; " class="ce8"> พ.ศ.<?php $s = $bsy; if($s>=2000) $s = $s - 2500; echo $s;?></td>
					<td style="text-align:left;width:1.3256in; " class="ce171"><?php echo '('.$drname[$j].')';?> </td>
					<td style="text-align:left;width:1.3256in; " class="ce171"><?php echo $mkpold;?> </td>
					<td style="text-align:left;width:1.3256in; " class="ce17"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce17"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce8"> </td>
					<td style="text-align:left;width:2.0236in; " class="ce17"><p>(หน่วย..<?php echo $mkunold;?>..)</p></td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.9717in; " class="ce8"></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
			<?php 
			}
			//ซื้อมา
			if($nbvo[$j] != 0)
			{
			?>
				<tr class="ro1">
					<td style="text-align:left;width:0.7992in; " class="ce8"><p>วันที่ <?php 
					$byda = new DateTime($nbdate);
					$bday = $byda->format("d");
					$bmo = $byda->format("m");
					echo $bday.' ';
					$m = $bmo;
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
									}
					?>.</p></td>
					<td style="text-align:left;width:1.3256in; " class="ce171"><?php echo $drgname[$j].'&nbsp'.$drsize[$j]; ?></td>
					<td style="text-align:left;width:1.3256in; " class="ce171"><?php echo $nbmkn; ?></td>
					<td style="text-align:left;width:1.3256in; " class="ce171"><?php echo $nbmkl; ?> </td>
					<td style="text-align:left;width:0.6882in; " class="ce171"><?php echo $nbmka; ?> </td>
					<td style="text-align:left;width:0.6882in; " class="ce8"><p>อย.</p></td>
					<td style="text-align:left;width:2.0236in; " class="ce17"><p>รับยาในเดือนนี้</p></td>
					<td style="text-align:left;width:0.5665in; " class="ce171"><?php echo $nbvo[$j]; ?></td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.9717in; " class="ce8"><p>___________</p></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<tr class="ro3">
					<td style="text-align:left;width:0.7992in; " class="ce8"> พ.ศ.<?php $s = $bsy; if($s>=2000) {$s = $s - 2500; echo $s;}?></td>
					<td style="text-align:left;width:1.3256in; " class="ce171"><?php echo '('.$drname[$j].')';?> </td>
					<td style="text-align:left;width:1.3256in; " class="ce171"><?php echo $nbmkp; ?> </td>
					<td style="text-align:left;width:1.3256in; " class="ce171"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce171"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce8"> </td>
					<td style="text-align:left;width:2.0236in; " class="ce17"><p>(หน่วย..<?php echo $nbmku;?> )</p></td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce171"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.9717in; " class="ce8"></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
			<?php 
			}
			if($presthismon[$j]!= 0)
			{
			?>
				<tr class="ro1">
					<td style="text-align:left;width:0.7992in; " class="ce8"><p>วันที่ 1 - <?php 
									if($sm == date("m") and $sy == date("Y")) $imax = date("d");
									elseif($sm == 1 or $sm == 3 or $sm == 5 or $sm == 7 or $sm == 8 or $sm == 10 or $sm == 12) $imax=31;
									elseif($sm == 2 and $sy%4 == 0) $imax = 29;
									elseif($sm == 2 and $sy%4 != 0) $imax = 28;
									else $imax = 30;
									echo $imax;?></p></td>
					<td style="text-align:left;width:1.3256in; " class="ce17"> </td>
					<td style="text-align:left;width:1.3256in; " class="ce17"> </td>
					<td style="text-align:left;width:1.3256in; " class="ce17"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce17"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce8"> </td>
					<td style="text-align:left;width:2.0236in; " class="ce17"><p>จ่ายผู้ป่วย..<?php echo count(array_unique($ctmid)); ?>..ราย</p></td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce171"><?php echo $presthismon[$j]; ?></td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.9717in; " class="ce8"><p>___________</p></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<tr class="ro1">
					<td style="text-align:left;width:0.7992in; " class="ce8"><p>พ.ศ.<?php $s = $bsy; if($s>=2000) $s = $s - 2500; echo $s;?></p></td>
					<td style="text-align:left;width:1.3256in; " class="ce17"> </td>
					<td style="text-align:left;width:1.3256in; " class="ce17"> </td>
					<td style="text-align:left;width:1.3256in; " class="ce17"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce17"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce8"> </td>
					<td style="text-align:left;width:2.0236in; " class="ce17"><p>ตามแบบ บ.จ. 8</p></td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.9717in; " class="ce17"></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<tr class="ro3">
					<td style="text-align:left;width:0.7992in; " class="ce9"> </td>
					<td style="text-align:left;width:1.3256in; " class="ce9"> </td>
					<td style="text-align:left;width:1.3256in; " class="ce9"> </td>
					<td style="text-align:left;width:1.3256in; " class="ce9"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce9"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce9"> </td>
					<td style="text-align:left;width:2.0236in; " class="ce9"><p>ที่เก็บไว้ที่สถานพยาบาล</p></td>
					<td style="text-align:left;width:0.5665in; " class="ce9"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce9"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce9"> </td>
					<td style="text-align:left;width:0.9717in; " class="ce9"> </td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>					
				</tr>
			<?php 
			}
			if($oldvo[$j] != 0 OR $presthismon[$j]!= 0 OR $nbvo[$j] != 0)
			{
			?>
				<tr class="ro3">
					<td style="text-align:left;width:0.7992in; " class="ce10"> </td>
					<td style="text-align:left;width:1.3256in; " class="ce10"> </td>
					<td style="text-align:left;width:1.3256in; " class="ce10"> </td>
					<td style="text-align:left;width:1.3256in; " class="ce10"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce10"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce10"> </td>
					<td style="text-align:left;width:2.0236in; " class="ce20"><p>รวม</p></td>
					<td style="text-align:left;width:0.5665in; " class="ce10"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce10"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce10"> </td>
					<td style="text-align:left;width:0.9717in; " class="ce10"> </td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
		<?php 
			}
		}
		?>
				</tr>
			</table>
	</td></tr>
</table></div></div></div>
</body>
</html>