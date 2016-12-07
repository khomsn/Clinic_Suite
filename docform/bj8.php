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

////////
if($_POST['nopage'] == 'Next')
{	
    $sy = $_SESSION['sy'];
    $sm = $_SESSION['sm'];
	$_SESSION['page'] = $_SESSION['page']+1;
	$alldrugleft = $_SESSION['alldrugleft'];
	$ntimeprst=$_SESSION['ntimeprst'];
    if($_SESSION['page']>1)
    {
        $rotab=$_SESSION['i']+1;
        $_SESSION['i'.$_SESSION[page]] = $_SESSION['i'];
        $_SESSION['alldrugleft'.$_SESSION[page]]=$_SESSION['alldrugleft'];
    }	
}
if($_POST['nopage'] == 'Previous')
{	
    $sy = $_SESSION['sy'];
    $sm = $_SESSION['sm'];
	$_SESSION['page'] = $_SESSION['page']-1;
	//$alldrugleft = $_SESSION['alldrugleft'];
	$ntimeprst=$_SESSION['ntimeprst'];
    if($_SESSION['page']>1)
    {
        $_SESSION['i']= $_SESSION['i'.$_SESSION[page]];
        $_SESSION['alldrugleft'] = $_SESSION['alldrugleft'.$_SESSION[page]];
        $rotab=$_SESSION['i']+1;
        $alldrugleft = $_SESSION['alldrugleft'];
    }	
}

$thisdate = date_create();
date_date_set($thisdate, $sy, $sm, $sd);
$ddate = date_format($thisdate, 'Y-m-d');


if($_SESSION['page'] =='')
{
	$_SESSION['page']=1;
	$_SESSION['i']=1;//initial pt no 1.
}

//$prescript this month 
$norow = 1;
$ntimeprst = 0; // number of prescript order in this month
$dtype2 = mysqli_query($link, "SELECT * FROM tr_drug_$drugid ORDER BY `date` ASC ");
while($row2 = mysqli_fetch_array($dtype2))
{
    $trdate = new DateTime($row2['date']);
    $stcd[$norow] = $trdate->format("d");//
    $stcm[$norow] = $trdate->format("m");
    $stcy[$norow] = $trdate->format("Y");
    $trmon = $trdate->format("m");
    $try = $trdate->format("Y");
    
    ////จ่าย ในเดือนนี้ 
    if($try == $sy AND $trmon == $sm)
    { 
    //จ่าย ในเดือนนี้
        $presth = $presth + $row2['volume'];
        $pvdate = new DateTime('1'.'-'.$_SESSION['sm'].'-'.$_SESSION['sy']);
        $ntimeprst = $ntimeprst+1;
        
    //get patient_id information
        $pt_id[$norow] = $row2['pt_id'];
        $ctmvol[$norow] =$row2['volume'];//
        
        $custo = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE id = $pt_id[$norow] ");
        while($rowc =mysqli_fetch_array($custo))
        {
            $ptname[$norow] =$rowc['prefix'].' '. $rowc['fname'].' '.$rowc['lname'].' '.$rowc['ctz_id'];//
            $ptaddre[$norow] = $rowc['address1']." หมู่ ".$rowc['address2']." ต. ".$rowc['address3']." อ.".$rowc['address4']." จ.".$rowc['address5'];//
            $birthday = new DateTime($rowc['birthday']);
            $yob = $birthday->format("Y");
            $ptage[$norow] = date("Y") - $yob;//
        }
        $norow =$norow +1;
   
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
        $_SESSION['mklot']=$row['mklot'];//_SESSION  lot ยกไป
    }
    $spold[$nofrow] = $row['supplier'];//
//    $mknold[$nofrow] = $row['mkname'];
//    $mkpold[$nofrow]= $row['mkplace'];
    $mklold[$nofrow] = $row['mklot'];//
//    $mkanold[$nofrow] =$row['mkanl'];
    $mkunold[$nofrow] = $row['mkunit'];//
    $volp[$nofrow] = $row['volume'];//
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

if ($actsite == "bj8.php")
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

echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
if($_SESSION['page'] >1)
{
echo "<input type='submit' name='nopage' value='Previous'>";
}

echo "&nbsp;&nbsp;หน้า&nbsp;&nbsp;&nbsp;";

if($_SESSION['page'] < $_SESSION['mpage'])
{
echo "<input type='submit' name='nopage' value='Next'>";
}
?>
</form>

<div align="center"><a href="javascript:Clickheretoprint()">Print</a></div><br>

<div class="style3" id="print_content">
<div class="page"><div class="subpage">
<table border="0" style="text-align: center; margin-left: 50px; margin-right: auto;" >
	<tr><td>
<?php 
//echo "pvsvolin".$pvsvolin;
//echo "pvprst".$pvprst;
/*
echo $_SESSION[$sdp[$j].$smp[$j].$syp[$j]]."/";
echo $startrec;
echo "page".$_SESSION['page'];
echo "rotab".$_SESSION['i'];
echo "ntimeprst".$ntimeprst;
echo  $sy."-".$sm."-".$sd;
echo $ddate;
echo $stday[0];
*/
//if($rotab<= $ntimeprst)
//{
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
					<td style="text-align:left;width:0.6882in; " class="ce1"><p> หน้า ...<?php echo $_SESSION['page']; ?>...</p></td>
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
if($rotab<= $ntimeprst)
{
		//แสดงยอดยกมา ในหน้าแรกของเดือน
		if($_SESSION['page']==1)
		{
			if($oldvo != 0)//แสดงยอดยกมา
			{
			?>	
				<tr class="ro3">
					<td style="text-align:left;width:0.6445in; " class="ce5"><?php 
					echo "01";
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
					<td style="text-align:left;width:0.852in; " class="ce5"><?php echo $_SESSION['mklot']; ?></td>
					<td style="text-align:left;width:0.6335in; " class="ce5"> <?php echo $_SESSION['oldsp']; ?> </td>
					<td style="text-align:left;width:1.2016in; " class="ce5"> </td>
					<td style="text-align:left;width:0.3272in; " class="ce5"> </td>
					<td style="text-align:left;width:1.6929in; " class="ce5"> </td>
					<td style="text-align:left;width:0.7535in; " class="ce5"><?php echo $oldvo; ?> </td>
					<td style="text-align:left;width:0.7535in; " class="ce5"></td>
					<td style="text-align:left;width:0.7535in; " class="ce5"></td>
					<td style="text-align:left;width:0.7535in; " class="ce5"> <?php echo $alldrugleft = $oldvo; ?>  </td>
					<td style="text-align:left;width:0.6882in; " class="ce9"> </td>
					<td style="text-align:left;width:0.01in; " class="Default"></td>
				</tr>
		<?php 
			}//แสดงยอดยกมา
		}//แสดงยอดยกมา ในหน้าแรกของเดือน
        
            for($i = $rotab; $i <= $ntimeprst; $i++)//แสดงรายการจ่ายยา
            {
            
            unset($_SESSION['BN'.$i]);
			for($j = 1; $j <= $bmax; $j++)//แสดงรายการซื้อยาในแต่ละเดือน
            {
                if($startrec==1 and empty($_SESSION['BN'.$j])) $_SESSION['BN'.$j]=0;
                
                if($sdp[$j]<=$stcd[$i] AND $smp[$j]==$stcm[$i] AND $syp[$j]==$stcy[$i] AND ($_SESSION['BN'.$j]==0))
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
                    <td style="text-align:left;width:0.852in; " class="ce5"><?php echo $mklold[$j];?></td>
                    <td style="text-align:left;width:0.6335in; " class="ce5"><?php echo $_SESSION['oldsp'] = $spold[$j]; ?> </td>
                    <td style="text-align:left;width:1.2016in; " class="ce5"> </td>
                    <td style="text-align:left;width:0.3272in; " class="ce5"> </td>
                    <td style="text-align:left;width:1.6929in; " class="ce5"> </td>
                    <td style="text-align:left;width:0.7535in; " class="ce5"> </td>
                    <td style="text-align:left;width:0.7535in; " class="ce5"><?php echo $volp[$j];?></td>
                    <td style="text-align:left;width:0.7535in; " class="ce5"></td>
                    <td style="text-align:left;width:0.7535in; " class="ce5"><?php echo $alldrugleft = $alldrugleft + $volp[$j];?> </td>
                    <td style="text-align:left;width:0.6882in; " class="ce9"></td>
                    <td style="text-align:left;width:0.01in; " class="Default"><?php $_SESSION['BN'.$j] = 1; $n=$n+1;?></td>
                </tr>
        <?php 
                 if($n>$line) goto jpoint;   
                }
            }//แสดงรายการซื้อยาในแต่ละเดือน
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
					<td style="text-align:left;width:0.852in; " class="ce5"><?php //echo $mklold[$i]; ?></td>
					<td style="text-align:left;width:0.6335in; " class="ce5"><?php //echo $spold[$i]; ?> </td>
					<td style="text-align:left;width:1.2016in; " class="ce5"><?php echo $ptname[$i]; ?> </td>
					<td style="text-align:left;width:0.3272in; " class="ce5"><?php echo $ptage[$i]; ?> </td>
					<td style="text-align:left;width:1.6929in; " class="ce5"><?php echo $ptaddre[$i]; ?> </td>
					<td style="text-align:left;width:0.7535in; " class="ce5"> </td>
					<td style="text-align:left;width:0.7535in; " class="ce5"></td>
					<td style="text-align:left;width:0.7535in; " class="ce5"><?php echo $ctmvol[$i];?> </td>
					<td style="text-align:left;width:0.7535in; " class="ce5"><?php echo $alldrugleft = $alldrugleft - $ctmvol[$i];?> </td>
					<td style="text-align:left;width:0.6882in; " class="ce9"> </td>
					<td style="text-align:left;width:0.01in; " class="Default"><?php $_SESSION['i']= $i; $n=$n+1;?></td>
				</tr>
		<?php 
            if($n>$line) goto jpoint;
            }//แสดงรายการจ่ายยา

        jpoint:
		?>
				<tr class="ro3">
					<td colspan="7" style="text-align:left;width:0.6445in; " class="ce6"><p>สรุป</p></td>
					<td style="text-align:left;width:0.7535in; " class="ce5"></td>
					<td style="text-align:left;width:0.7535in; " class="ce5"></td>
					<td style="text-align:left;width:0.7535in; " class="ce5"> </td>
					<td style="text-align:left;width:0.7535in; " class="ce5"><?php echo $_SESSION['alldrugleft']=$alldrugleft;?></td>
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
