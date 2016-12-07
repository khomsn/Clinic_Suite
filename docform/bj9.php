<?php 
include '../login/dbc.php';
page_protect();
date_default_timezone_set('Asia/Bangkok');
$stday = mysqli_fetch_array(mysqli_query($link, "SELECT date from drug_$_SESSION[drugid] where id=1"));

$fulluri = $_SERVER['REQUEST_URI'];
$trimString = "/clinic/docform/";

$actsite = trim($fulluri, $trimString);

$param = mysqli_query($link, "SELECT * FROM parameter WHERE ID ='1'");
		while($row = mysqli_fetch_array($param))
		{
		$clinic_name = $row['name'];
		$cliniclcid = $row['cliniclcid'];
		$name_lc = $row['name_lc'];
		$cl_addr = $row['address'];
		$cl_lcid = $row['lcid'];
		}

$dtype = mysqli_query($link, "SELECT * FROM drug_id WHERE  id = '$_SESSION[drugid]' ");
while($row = mysqli_fetch_array($dtype))
{
	$dname = $row['dname'];//
	$dgname = $row['dgname'];//
	$size = $row['size'];//
}

$drugid = $_SESSION['drugid']; 

$line = 12; //number of table row per print out page
$rotab=1; //initial table row

if($_SESSION['sm'] =='')
{
$_SESSION['sm'] = date("m");
$_SESSION['sy'] = date("Y");
}

include '../libs/progdate.php';

$thisdate = date_create();
date_date_set($thisdate, $sy, $sm, $sd);
$ddate = date_format($thisdate, 'Y-m-d');


if($_SESSION['page'] =='')
{
	$_SESSION['page']=1;
	$_SESSION['i']=1;//initial pt no 1.
}
////////
if($_POST['nopage'] == 'Next')
{	
	$_SESSION['page'] = $_SESSION['page']+1;
	
	$ntimeprst=$_SESSION['ntimeprst'];
	
    if($_SESSION['page']>1)
    {
        $rotab=$_SESSION['i']+1;
    }	
	
}

//$prescript this month 
$dtype2 = mysqli_query($link, "SELECT * FROM tr_drug_$drugid ORDER BY `date` ASC ");
while($row2 = mysqli_fetch_array($dtype2))
{
    $trdate = new DateTime($row2['date']);
    $trmon = $trdate->format("m");
    $try = $trdate->format("Y");
    
    ////จ่าย ในเดือนนี้ 
    if($try == $sy AND $trmon == $sm)
    { 
    //จ่าย ในเดือนนี้
        $presth = $presth + $row2['volume'];
        $pvdate = new DateTime('1'.'-'.$_SESSION['sm'].'-'.$_SESSION['sy']);
        $pt_id[$norow] = $row2['pt_id'];
    }
}
//$prescript previous month 
$dtype2 = mysqli_query($link, "SELECT * FROM tr_drug_$drugid ORDER BY `date` ASC ");
while($row2 = mysqli_fetch_array($dtype2))
{
    //รวมจ่าย ในเดือนก่อนๆ
    $trdate = new DateTime($row2['date']);
    if($trdate<$pvdate)
    {
        $pvprst = $pvprst + $row2['volume'];
    }
}

 //get data
$dtype = mysqli_query($link, "SELECT * FROM drug_$drugid ORDER BY `id` ASC ");
$nofrow = 1;
while($row = mysqli_fetch_array($dtype))
{
    $rdate = new DateTime($row['date']);
    $sdp[$nofrow] = $rdate->format("d");//
    $smp[$nofrow] = $rdate->format("m");//
    $syp[$nofrow] = $rdate->format("Y");//
    $bydate[$nofrow] = $rdate->format("d-m-Y");
    //order previous month
    if($rdate<$pvdate)
    {
        $pvsvolin = $pvsvolin + $row['volume'];
    }
    $supplier[$nofrow] = $row['supplier'];//
    $mkname[$nofrow] = $row['mkname'];
    $mkplace[$nofrow]= $row['mkplace'];
    $mklot[$nofrow] = $row['mklot'];//
    $mkanl[$nofrow] =$row['mkanl'];
    $mkunit[$nofrow] = $row['mkunit'];//
    $volume[$nofrow] = $row['volume'];//
    //
    $bmax = $nofrow; //
    $nofrow =$nofrow +1;
}
// ยอดยกมา   
$oldvo = $pvsvolin - $pvprst;
// จบ ยอดยกมา
$_SESSION['ntimeprst']=$ntimeprst;
$_SESSION['mpage'] = ceil(($_SESSION['ntimeprst']+1)/$line); //max page per month (หน้าแรกมียอดยกมาด้วย)

$n=1; //initial count item for prescription

?>

<!DOCTYPE html>
<html>
<!--This file was converted to xhtml by OpenOffice.org - see http://xml.openoffice.org/odf2xhtml for more info.-->
<head profile="http://dublincore.org/documents/dcmi-terms/">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title xml:lang="th_TH.UTF-8">บจ.9</title>
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
if ($actsite == "bj9.php")
{
                if($ddate>$stday[0])
                {
                   echo "<input type='submit' name='todom' value = '<<'>&nbsp;";
                   $startrec=0;
                }
                else
                {
                echo "<input type='button' value='*||*'>&nbsp;";
                $startrec=1;
                }
                
                echo "<input type='submit' name='todom' value = '@'>&nbsp;";
    if ($sm < date("m"))
    {
        if ($sy <= date("Y"))
        {
        echo "<input type='submit' name='todom' value = '>>'>";
        }
    }
    if ($sm >= date("m"))
    {
        if ($sy < date("Y"))
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
					<td colspan="7" style="text-align:center;width:0.7952in; " class="ce2">
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
				//ยอกยกมา
				?>
		<?php
			if($oldvo != 0)//แสดงยอดยกมา
			{
               for($j=$bmax; $j>=1; $j=$j-1)
                {
                    $date1=date_create($bydate[$j]);
                    
                            $ckdate = new DateTime("1".'-'.$_SESSION['sm'].'-'.$_SESSION['sy']);
                            $ckdate = $ckdate->format("d-m-Y");
                            $date2 =date_create($ckdate);
                            $diff=date_diff($date1,$date2);
                            $ddate = $diff->format("%R%a");
                            if($ddate>=0)                          
                            {
                             $mknameold = $mkname[$j];
                             $mklotold = $mklot[$j];
                             $mkanlold = $mkanl[$j];
                             $supplierold = $supplier[$j];
                             $mkplaceold = $mkplace[$j];
                             $mkunitold = $mkunit[$j];
                                goto jpoint;
                            }
                }
                jpoint:
			?>	
                <tr class='ro1'>
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
					<td style='text-align:left;width:1.3256in; ' class='ce161'><?php echo $dgname.'&nbsp'.$size;?></td>
					<td style='text-align:left;width:1.3256in; ' class='ce161'><?php echo $mknameold;?> </td>
					<td style='text-align:left;width:1.3256in; ' class='ce161'><?php echo $mklotold;?> </td>
					<td style='text-align:left;width:0.6882in; ' class='ce161'><?php echo $mkanlold;?> </td>
					<td style='text-align:left;width:0.6882in; ' class='ce7'><p> <?php echo $supplierold; ?>.</p></td>
					<td style='text-align:left;width:2.0236in; ' class='ce16'><p>ยอดคงเหลือจากเดือนที่แล้ว</p></td>
					<td style='text-align:left;width:0.5665in; ' class='ce161'> <?php echo $oldvo;?></td>
					<td style='text-align:left;width:0.5665in; ' class='ce16'> </td>
					<td style='text-align:left;width:0.5665in; ' class='ce16'> <?php echo $dleft=$oldvo;?></td>
					<td style='text-align:left;width:0.9717in; ' class='ce7'><p>___________</p></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<tr class="ro1">
					<td style="text-align:left;width:0.7992in; " class="ce8"> พ.ศ.<?php echo $bsy;?></td>
					<td style="text-align:left;width:1.3256in; " class="ce171"><?php echo '('.$dname.')';?> </td>
					<td style="text-align:left;width:1.3256in; " class="ce171"><?php echo $mkplaceold;?> </td>
					<td style="text-align:left;width:1.3256in; " class="ce17"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce17"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce8"> </td>
					<td style="text-align:left;width:2.0236in; " class="ce17"><p>(หน่วย..<?php echo $mkunitold;?>..)</p></td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.9717in; " class="ce8"></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<?php
            }//ยอดยกมา
				//ซื้อมา
				?>
		<?php //แสดงรายการซื้อยาในแต่ละเดือน
        for($j=1;$j<=$bmax;$j++)
        {
                for($i=1;$i<=31;$i++)
                {
                    $ckdate = new DateTime($i.'-'.$_SESSION['sm'].'-'.$_SESSION['sy']);
                    $ckdate = $ckdate->format("d-m-Y");
                    if($bydate[$j]==$ckdate)
                    {
            ?>	
				<tr class="ro1">
					<td style="text-align:left;width:0.7992in; " class="ce8"><p>วันที่ <?php 
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
									}
					?>.</p></td>
					<td style="text-align:left;width:1.3256in; " class="ce171"><?php echo $dgname.'&nbsp'.$size; ?></td>
					<td style="text-align:left;width:1.3256in; " class="ce171"><?php echo $mkname[$j]; ?></td>
					<td style="text-align:left;width:1.3256in; " class="ce171"><?php echo $mklot[$j]; ?> </td>
					<td style="text-align:left;width:0.6882in; " class="ce171"><?php echo $mkanl[$j]; ?> </td>
					<td style="text-align:left;width:0.6882in; " class="ce8"><?php echo $supplier[$j];?></td>
					<td style="text-align:left;width:2.0236in; " class="ce17"><p>รับยาในเดือนนี้</p></td>
					<td style="text-align:left;width:0.5665in; " class="ce171"><?php echo $volume[$j]; ?></td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> <?php echo $dleft=$dleft+$volume[$j];?></td>
					<td style="text-align:left;width:0.9717in; " class="ce8"><p>___________</p></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<tr class="ro3">
					<td style="text-align:left;width:0.7992in; " class="ce8"> พ.ศ.<?php echo $bsy;?></td>
					<td style="text-align:left;width:1.3256in; " class="ce171"><?php echo '('.$dname.')';?> </td>
					<td style="text-align:left;width:1.3256in; " class="ce171"><?php echo $mkplace[$j]; ?> </td>
					<td style="text-align:left;width:1.3256in; " class="ce171"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce171"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce8"> </td>
					<td style="text-align:left;width:2.0236in; " class="ce17"><p>(หน่วย..<?php echo $mkunit[$j];?> )</p></td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce171"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.9717in; " class="ce8"></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
			<?php
                }
			}
        }
			//จ่ายไปในเดือนนี้
			?>
				<tr class="ro1">
					<td style="text-align:left;width:0.7992in; " class="ce8"><p>วันที่ 1 - <?php 
									if($sm == date("m") and $sy == date("Y")) $imax = date("d");
									elseif($sm == 1 or $sm == 3 or $sm == 5 or $sm == 7 or $sm == 8 or $sm == 10 or $sm == 12) $imax=31;
									elseif($sm == 2 and $sy%4 == 0) $imax = 29;
									elseif($sm == 2 and $sy%4 != 0) $imax = 28;
									else $imax = 30;
									echo $imax;
									?></p></td>
					<td style="text-align:left;width:1.3256in; " class="ce17"> </td>
					<td style="text-align:left;width:1.3256in; " class="ce17"> </td>
					<td style="text-align:left;width:1.3256in; " class="ce17"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce17"> </td>
					<td style="text-align:left;width:0.6882in; " class="ce8"> </td>
					<td style="text-align:left;width:2.0236in; " class="ce17"><p>จ่ายผู้ป่วย..<?php echo count(array_unique($pt_id)); ?>..ราย</p></td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> </td>
					<td style="text-align:left;width:0.5665in; " class="ce171"><?php echo $presth; ?></td>
					<td style="text-align:left;width:0.5665in; " class="ce17"> <?php echo $dleft=$dleft-$presth;?></td>
					<td style="text-align:left;width:0.9717in; " class="ce8"><p>___________</p></td>
					<td style="text-align:left;width:0.01in; " class="Default"> </td>
				</tr>
				<tr class="ro1">
					<td style="text-align:left;width:0.7992in; " class="ce8"><p>พ.ศ.<?php echo $bsy;?></p></td>
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
			//สรุป
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
				</tr>
			</table>
	</td></tr>
</table></div></div></div>
</body>
</html>
