<?php 
include '../login/dbc.php';
page_protect();

$id = $_SESSION['patdesk'];

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
//
$today = date("Y-m-d");
$pin = mysqli_query($linkopd, "select MAX(id) from $pttable ");
$maxrow = mysqli_fetch_array($pin);
$maxid = $maxrow[0];
$rid = $maxid;

if ($_POST['todo'] == '<<' )
{
	$pin = mysqli_query($linkopd, "select MAX(id) from $pttable where clinic='$_SESSION[clinic]' and id< '$_SESSION[rid]'");
	$maxrow = mysqli_fetch_array($pin);
	$_SESSION['rid'] = $maxrow[0];
//	$_SESSION['rid'] = $_SESSION['rid'] - 1;
	$rid = $_SESSION['rid'];
}
elseif ($_POST['todo'] == 'Last' )
{
	$_SESSION['rid'] = $maxid;
	$rid = $maxid;
}
elseif ($_POST['todo'] == '>>' )
{
	$pin = mysqli_query($linkopd, "select MAX(id) from $pttable where clinic='$_SESSION[clinic]' and id> '$_SESSION[rid]'");
	$maxrow = mysqli_fetch_array($pin);
	$_SESSION['rid'] = $maxrow[0];
//	$_SESSION['rid'] = $_SESSION['rid'] + 1;
	$rid = $_SESSION['rid'];
}
?>

<!DOCTYPE html>
<html>
<head profile="http://dublincore.org/documents/dcmi-terms/">
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8"/>
<title xml:lang="th_TH.UTF-8">ใบเสร็จรับเงิน</title>
<meta name="DCTERMS.title" content="" xml:lang="th_TH.UTF-8"/>
<meta name="DCTERMS.language" content="th_TH.UTF-8" scheme="DCTERMS.RFC4646"/>
<meta name="DCTERMS.source" content="http://xml.openoffice.org/odf2xhtml"/>
<meta name="DCTERMS.issued" content="2014-06-02T11:20:18.323329400" scheme="DCTERMS.W3CDTF"/>
<meta name="DCTERMS.modified" content="2014-06-02T17:41:05.641561120" scheme="DCTERMS.W3CDTF"/>
<meta name="DCTERMS.provenance" content="" xml:lang="th_TH.UTF-8"/>
<meta name="DCTERMS.subject" content="," xml:lang="th_TH.UTF-8"/>
<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" hreflang="en"/>
<link rel="schema.DCTERMS" href="http://purl.org/dc/terms/" hreflang="en"/>
<link rel="schema.DCTYPE" href="http://purl.org/dc/dcmitype/" hreflang="en"/>
<link rel="schema.DCAM" href="http://purl.org/dc/dcam/" hreflang="en"/>
<link href="../public/css/recform.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../public/js/autoclick.js"></script>

<script language="javascript">
function Clickheretoprint()
{ 
  var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
      disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25"; 
  var content_vlue = document.getElementById("print_content").innerHTML; 
  
  var docprint=window.open("","",disp_setting); 
   docprint.document.open(); 
   docprint.document.write('<html><head><title>ใบเสร็จรับเงิน</title>'); 
   docprint.document.write('<link href="../public/css/recform_print.css" rel="stylesheet" type="text/css">'); 
   docprint.document.write('</head><body onLoad="self.print()">');          
   docprint.document.write(content_vlue);          
   docprint.document.write('</body></html>'); 
   docprint.document.close(); 
   docprint.focus(); 
}
</script>
</head>

<body >
<form method="post" action="recprintpv.php" name="regForm" id="regForm">
<div style="text-align: center;">
<table width=100%>
<tr><td width=33%>
<?php 
      if($rid > 1) echo "<input type='submit' name='todo' value='<<' >";
      echo "</td><td width=33%>";
      echo "<input type='submit' name='todo' value='Last' ></td><td width=33%>";
      if($rid < $maxid) echo "<input type='submit' name='todo' value='>>' >";
      echo "</td></tr>";
?>
</table>
</div>
</form>
<?php 
  $pin = mysqli_query($linkopd, "select * from $pttable WHERE id= '$rid' AND clinic='$_SESSION[clinic]'");
  while ($row = mysqli_fetch_array($pin))
  {
      $date = new DateTime($row['date']);
      $sd = $date->format("d");
      $sm = $date->format("m");
      $sy = $date->format("Y");
      $hms = $date->format("G:i:s");
      $bsy = $sy +543;
      $vsdate = $row['date'];
  }
echo "<h2 class='titlehdr'>";
echo "<br> ค่าใช้จ่าย ณ วันที่ ";  
echo $sd." "; 
$m = $sm;
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
  }?> พ.ศ. <?php echo $bsy; echo "  เวลา ";echo $hms; echo " น." //date("Y")+543; ?></h2>

<div align="center"><input type="submit" name="OK" value="Print" onClick="javascript:Clickheretoprint()" ></div>
<br><br>
<table style="width: 100%; text-align: center; margin-left: auto; margin-right: auto;" border="0" cellpadding="3" cellspacing="3">	
<tr><td>
    <div class="style3" id="print_content">
    <div class="page"><div class="subpage">
    <br><br>
	<div style="text-align: center;">ใบเสร็จรับเงิน<br>
	<?php
		$rs_settings = mysqli_query($link, "select * from parameter where id='1'");
		while ($row_settings = mysqli_fetch_array($rs_settings))
		{
			echo $row_settings['name'];
			echo "<br>";
			echo "ใบอนุญาตเลขที่ ";
			echo $row_settings['cliniclcid'];
			echo "<br>";
			echo $row_settings['address']."<br>โทร.";
			echo $row_settings['tel'].",";
			echo $row_settings['mobile']."<br> Email:";
			echo $row_settings['email'];
		}
		
	?>
	<br>วันที่ <?php echo $sd; ?> <?php $m = $sm;
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
			}?> พ.ศ. <?php echo $bsy; //date("Y")+543;?><br>
							ได้รับเงินจาก &nbsp; 
							<?php
					while ($row_settings = mysqli_fetch_array($ptin))
					{
						echo $row_settings['prefix'];
						echo "&nbsp;"; 
						echo $row_settings['fname'];
						echo "&nbsp; &nbsp; &nbsp;"; 
						echo $row_settings['lname'];
						if($row_settings['reccomp']!=0)
						{			
						echo "<br>";
						$comp = mysqli_query($link, "SELECT * FROM reccompany WHERE id='$row_settings[reccomp]'");
						$crow = mysqli_fetch_array($comp);
						echo "ในนาม ".$crow['comdt'];
						}
					}
					echo "<br>";
							?>
							</div>
	<table style="background-color: rgb(255, 204, 153); width: 80%; text-align: center;
			margin-left: auto; margin-right: auto;" border="1" cellpadding="1" cellspacing="1">									
		<tr><th>No</th><th>รายการทั้งหมด</th><th>ราคา</th><th>จำนวน</th><th>รวม</th></tr>
		<?php 
		//treatment part
		
		$j = 1;
		for($i =1;$i<=4;$i++)
		{
	//	$ptin = mysqli_query($link, "select * from $tmp ");
	$ptin = mysqli_query($linkopd, "select * from $pttable WHERE id= '$rid' AND clinic= '$_SESSION[clinic]' ");
		while ($row = mysqli_fetch_array($ptin))
		{
			$idtr = "idtr".$i;
			$tr ="tr".$i;
			$trv = "trv".$i;
			//echo "<tr><td>".$i."</td><td>";
			if($row[$idtr] !=0)
			{
				echo "<tr><td>".$j."</td><td  style='text-align:left;'>";
				echo $row[$tr];
				echo "</td>";
				echo "<td style='text-align:right;'>";
				$did = $row[$idtr];
				$ptin2 = mysqli_query($link, "select * from drug_id WHERE id = $did ");
				if($ptin2 !=0)
				{
				while ($row2 = mysqli_fetch_array($ptin2))
				{
					echo $row2['sellprice'];
					$price1 = $row2['sellprice'] * $row[$trv] - floor($row2['sellprice'] * $row[$trv] * $row2['disct'] * $perdc);
				}
				}
				echo "</td>";
				echo "<td>";
				echo $row[$trv];
				echo "</td>";
				echo "<td style='text-align:right;'>";
				echo $price1;
				echo "</td></tr>";
				$allprice = $allprice+$price1;
				$j = $j+1;
			}
		}
		}
		//DF part
		for($i=1;$i<=10;$i++)
		{
	//	$ptin = mysqli_query($link, "select * from $tmp ");
	$ptin = mysqli_query($linkopd, "select * from $pttable WHERE id= '$rid' AND clinic= '$_SESSION[clinic]' ");
		while ($row = mysqli_fetch_array($ptin))
		{
			$idrx = "idrx".$i;
			$rx ="rx".$i;
			$rgx = "rxg".$i;
			$rxuses = "rx".$i."uses";
			$rxv = "rx".$i."v";
			if($row[$idrx] !=0 and $row[$rgx] =='DF')
			{
				echo "<tr><td>".$j."</td><td style='text-align:left;'>";
				echo "ค่าตรวจรักษาโดยแพทย์";
				echo "</td>";
				echo "<td style='text-align:right;'>";
				$did = $row[$idrx];
				$ptin2 = mysqli_query($link, "select * from drug_id WHERE id = $did AND dgname='DF'");
				if($ptin2 !=0)
				{
				while ($row2 = mysqli_fetch_array($ptin2))
				{
					//echo $row2['sellprice'];
					$price1 = $row2['sellprice'] * $row[$rxv] - floor($row2['sellprice'] * $row[$rxv] * $row2['disct'] * $perdc);
				}
				}
				echo "</td>";
				echo "<td>";
				//echo $row[$rxv];
				echo "</td>";
				echo "<td style='text-align:right;'>";
				echo $price1;
				echo "</td></tr>";
				$allprice = $allprice+$price1;
				$j = $j+1;
			}
		}
		}
		//drug part
		
		for($i=1;$i<=10;$i++)
		{
	//	$ptin = mysqli_query($link, "select * from $tmp ");
	$ptin = mysqli_query($linkopd, "select * from $pttable WHERE id= '$rid' AND clinic= '$_SESSION[clinic]' ");
		while ($row = mysqli_fetch_array($ptin))
		{	
			$disprx = $row['disprxby'];
			$idrx = "idrx".$i;
			$rx ="rx".$i;
			$rgx = "rxg".$i;
			$rxuses = "rx".$i."uses";
			$rxv = "rx".$i."v";
			if($row[$idrx] !=0 and $row[$rgx] !='DF')
			{
				echo "<tr><td>".$j."</td><td style='text-align:left;'>";
				echo $row[$rx];
				echo "</td>";
				echo "<td style='text-align:right;'>";
				$did = $row[$idrx];
				$ptin2 = mysqli_query($link, "select * from drug_id WHERE id = $did ");
				if($ptin2 !=0)
				{
				while ($row2 = mysqli_fetch_array($ptin2))
				{
					echo $row2['sellprice'];
					$price1 = $row2['sellprice'] * $row[$rxv] - floor($row2['sellprice'] * $row[$rxv] * $row2['disct'] * $perdc);
				}
				}
				echo "</td>";
				echo "<td>";
				echo $row[$rxv];
				echo "</td>";
				echo "<td style='text-align:right;'>";
				echo $price1;
				echo "</td></tr>";
				$allprice = $allprice+$price1;
				$j = $j+1;
			}
		}
		}
		//lab part
	
  $pin = mysqli_query($linkopd, "select * from $pttable WHERE labid!='' AND id='$rid'") ;
  while ($row_settings = mysqli_fetch_array($pin))
  {
      $labidr=$row_settings['labid'];
      if(!empty($labidr))
      {
	  $n = substr_count($labidr, ';');
	  //$str = 'hypertext;language;programming';
	  $charsl = preg_split('/;/', $labidr);
      }
      $filter = mysqli_query($link, "select * from lab WHERE `L_Set` !='SETNAME' ORDER BY `id` ASC  ");
      while ($labinfo = mysqli_fetch_array($filter))
      {
	      $lname = $labinfo['S_Name'];
	      $lname1 = $labinfo['S_Name']." [".$labinfo['L_Name']."]";
	      for ($i=0;$i<=$n;$i++)
	      {
	      if($lname1==$charsl[$i])
		{
		$alllabprice =  $alllabprice + $labinfo['price'];
		}
	      } 
      }
   }

	$ptin = mysqli_query($link, "select * from sell_account WHERE ctmid= '$id' AND day=$sd AND month=$sm AND year=$sy AND vsdate='$vsdate'");
		while ($row = mysqli_fetch_array($ptin))
		{
		  $cashp = $row['cash'];
		  $ownp =$row['own'];
		  $totalp =$row['total'];
		  
		  if($allprice+$alllabprice < $totalp)
		  {
		  $alllabprice = $totalp - $allprice;
		  }
		}
		if($alllabprice>0)
		{
		  echo "<tr><td>".$j."</td><td style='text-align:left;'>ค่าตรวจทาง Lab รวมทั้งหมด</td><td></td><td></td><td style='text-align:right;'>".$alllabprice;
		  echo "</td></tr>";
		  $allprice= $allprice+$alllabprice;
		}
		//lab price finish
		
		//accout system buy for today
		//$_SESSION['buyprice'] = $allprice;
		$acno = 1000000+$id; 
		$newdate = $sy.'-'.$sm.'-'.$sd;
		//echo $newdate;
		$newdate = date($newdate);
		//echo $newdate;
		$olddeb = 0;
		$ptin3 = mysqli_query($link, "select * from `daily_account` WHERE ac_no_o = $acno and ctime > '$vsdate'");
		while ($row3 = mysqli_fetch_array($ptin3))
		{
			if($row3['price']>0 AND $row3['date']==$newdate)
			{
				echo "<tr><td>".$j."</td><td style='text-align:left;'>";
				echo "จ่ายยอดค้างชำระ";
				echo "</td><td>";
				echo $row3['price']; 
				echo "</td><td>1</td><td style='text-align:right;'>";
				echo $olddeb = $row3['price']; 
				echo "</td></tr>";
				break; //echo only one record 
			}
		}	
		
	echo "</table>";
	?>
	<table style="background-color: rgb(255, 204, 153); width:80%; text-align: center;
			margin-left: auto; margin-right: auto;" border="1" cellpadding="1" cellspacing="1">									
		<tr><th> ยอดรวมสุทธิ</th><th style='text-align:right;'>
		<?php 
			echo $allprice + $olddeb;
			?></th>
		</tr>
		<tr><th>จ่าย</th><th width = 18% style='text-align:right;'><?php echo  $cashp + $olddeb;?>
		</th></tr>
	</table>
	<br>
	รับเงินโดย:<u>
	<?php
	    $staff = mysqli_query($link, "select * from staff WHERE ID = '$disprx' ");
	    while($row_vl = mysqli_fetch_array($staff))
	    {
	      $prefix = $row_vl['prefix'];
	      $stfname = $row_vl['F_Name'];
	      $stlname = $row_vl['L_Name'];
	    }
	    echo $prefix.' '.$stfname.' '.$stlname;
	?></u>
   </div></div></div>
	</td></tr>   
</table>
</body></html>