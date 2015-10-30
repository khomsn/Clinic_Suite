<?php 
include '../login/dbc.php';
page_protect();

$id = $_SESSION['patdesk'];
$ptin = mysqli_query($linkopd, "SELECT * FROM patient_id where id='$id' ");
while($row = mysqli_fetch_array($ptin)) 
{
	$dl[1] = $row['drug_alg_1'];
	$dl[2] = $row['drug_alg_2'];
	$dl[3] = $row['drug_alg_3'];
	$dl[4] = $row['drug_alg_4'];
	$dl[5] = $row['drug_alg_5'];
	$chron[1] = $row['chro_ill_1'];
	$chron[2] = $row['chro_ill_2'];
	$chron[3] = $row['chro_ill_3'];
	$chron[4] = $row['chro_ill_4'];
	$chron[5] = $row['chro_ill_5'];
	$concurdrug = $row['concurdrug'];
}
//concurdrug
if(!empty($concurdrug))
{
    $ccd = substr_count($concurdrug, ',');
    //$str = 'hypertext;language;programming';
    $charsl = preg_split('/,/', $concurdrug);
    for($i=0;$i<=$ccd;$i++)
    {
	$cho = mysqli_query($linkcm, "select dinteract from druggeneric where name = '$charsl[$i]' ");
	$int[$i] = mysqli_fetch_array($cho);
    }

}

$ddindex=1;

for($i=0;$i<=$ccd;$i++)
{
  $iti = $int[$i][0];
  if(!empty($iti))
  {
      $nci = substr_count($iti, ';');
      //$str = 'hypertext;language;programming';
      $charsl = preg_split('/;/', $iti);
  
    for($b=0;$b<=$nci;$b++)
    {
      $charslnew[$b] = str_replace('[', '', $charsl[$b]);
      $charslnew[$b] = str_replace(']', '', $charslnew[$b]);
      $chars = preg_split('/,/', $charslnew[$b]);
      $did[$ddindex]=$chars[0];
      $dil[$ddindex]=$chars[1];
      $ddindex = $ddindex+1;
    }
  }
}
//$_SESSION['ddil']
for($i=1;$i<$ddindex;$i++)
{
 if($_SESSION['ddiltemp']>$_SESSION['ddil'])
 {
  if(abs($dil[$i])>$_SESSION['ddiltemp'])
  {
    $cho = mysqli_query($linkcm, "select name from druggeneric where id = '$did[$i]' ");
    $didname = mysqli_fetch_array($cho);
    if(empty($fddi)) $fddi = 'dgname != "'.$didname[0].'"';
    else $fddi = $fddi.' AND dgname != "'.$didname[0].'"';
  }
 }
 else
 {
  if(abs($dil[$i])>$_SESSION['ddil'])
  {
    $cho = mysqli_query($linkcm, "select name from druggeneric where id = '$did[$i]' ");
    $didname = mysqli_fetch_array($cho);
    if(empty($fddi)) $fddi = 'dgname != "'.$didname[0].'"';
    else $fddi = $fddi.' AND dgname != "'.$didname[0].'"';
  }
 }
}
if(empty($fddi)) $fddi = 1;

//add
$chorow=1;
for($i=1;$i<=5;$i++)
{
  if(!empty($chron[$i]))
  {
    $cho = mysqli_query($linkcm, "select * from drandillci where chronname = '$chron[$i]' ");
    while ($row = mysqli_fetch_array($cho))
    {
      $dgnameset[$chorow]=$row['drugname'];
      $chorow = $chorow+1;
    }
  }
}

$foutlast = 1;
for($i=1;$i<$chorow;$i++)
{
  $check = "_dsg";
  if (strpos($dgnameset[$i], $check) == TRUE)
  {
    $dgnameset[$i] = str_replace('_dsg', '', $dgnameset[$i]);
    $fout = 'subgroup != "'.$dgnameset[$i].'"';
    goto next2;
   }
  $check = "_drg"; 
  if (strpos($dgnameset[$i],  $check) == TRUE)
  {
    $dgnameset[$i] = str_replace('_drg', '', $dgnameset[$i]);
    $fout = 'groupn != "'.$dgnameset[$i].'"';
     goto next2;
   }
   
   $fout = 'dgname != "'.$dgnameset[$i].'"';
   
   next2:
   $foutlast = $foutlast." AND ".$fout." ";
}

$tmp = "tmp_".$id;

//check preg condition
$preg = mysqli_query($link, "select preg from $tmp");

while ($row = mysqli_fetch_array($preg)) $preg=$row['preg'];

if($preg==1)
{
$cat = '(cat = "A" or cat = "B" or cat = "N")';
  if($_SESSION['catc'] ==1)
  {
    $cat = '(cat = "A" or cat = "B" or cat = "C" or cat = "N")';
  }
}
else $cat=1;
//
if(!empty($dl[1])) {$fdo1 = 'dgname != "'.$dl[1].'"'; $fdo = $fdo1;}
if(!empty($dl[2])) 
    {
        $fdo2 = 'dgname != "'.$dl[2].'"';
        if (empty($fdo)) $fdo = $fdo2;
        else $fdo = $fdo." AND ".$fdo2;
    }
if(!empty($dl[3])) 
    {
        $fdo3 = 'dgname != "'.$dl[3].'"';
        if (empty($fdo)) $fdo = $fdo3;
        else $fdo = $fdo." AND ".$fdo3;
    }
if(!empty($dl[4]))
    {
        $fdo4 = 'dgname != "'.$dl[4].'"';
        if (empty($fdo)) $fdo = $fdo4;
        else $fdo = $fdo." AND ".$fdo4;
    }
if(!empty($dl[5]))
    {
        $fdo5 = 'dgname != "'.$dl[5].'"';
        if (empty($fdo)) $fdo= $fdo5;
        else $fdo = $fdo." AND ".$fdo5;
    }

$j=1;//for groupn
$k=1;//for subgroup

for($i=1;$i<=5;$i++)
{
if(!empty($dl[$i]))
{
    $drug = mysqli_query($link, "select * from drug_id where dgname='$dl[$i]'");

    while ($drugrs = mysqli_fetch_array($drug))
    {
    $dgg[$i] = $drugrs['groupn'];
    if (ltrim($dgg[$i]) === '') unset($dgg[$i]);
//    $j= $j+1;
    $dgsg[$i] = $drugrs['subgroup'];
    if (ltrim($dgsg[$i]) === '') unset($dgsg[$i]);
    }
}
}
/*
for($i=1;$i<=5;$i++)
{
if(!empty($dl[$i]))
{
    $drug = mysqli_query($link, "select * from drug_id where dgname='$dl[$i]'");

    while ($drugrs = mysqli_fetch_array($drug))
    {
    $dgsg[$k] = $drugrs['subgroup'];
    if (ltrim($dgsg[$k]) === '') unset($dgsg[$k]);
    $k= $k+1;
    }
}
}
*/
if(!empty($dgg[1])) {$fgo1 = 'groupn != "'.$dgg[1].'"'; $fgo = $fgo1;}

if(!empty($dgg[2])) 
    {
        $fgo2 = 'groupn != "'.$dgg[2].'"';
        if (empty($fgo)) $fgo = $fgo2;
        else $fgo = $fgo." AND ".$fgo2;
    }
if(!empty($dgg[3])) 
    {
        $fgo3 = 'groupn != "'.$dgg[3].'"';
        if (empty($fgo)) $fgo = $fgo3;
        else $fgo = $fgo." AND ".$fgo3;
    }
if(!empty($dgg[4])) 
    {
        $fgo4 = 'groupn != "'.$dgg[4].'"';
        if (empty($fgo)) $fgo = $fgo4;
        else $fgo = $fgo." AND ".$fgo4;
    }
if(!empty($dgg[5])) 
    {
        $fgo5 = 'groupn != "'.$dgg[5].'"';
        if (empty($fgo)) $fgo = $fgo5;
        else $fgo = $fgo." AND ".$fgo5;
    }

if(!empty($dgsg[1])) {$fsg1 = 'subgroup != "'.$dgsg[1].'"'; $fsg = $fsg1;}
if(!empty($dgsg[2])) 
    {
        $fsg2 = 'subgroup != "'.$dgsg[2].'"';
        if (empty($fsg)) $fsg = $fsg2;
        else $fsg = $fsg." AND ".$fsg2;
    }
if(!empty($dgsg[3])) 
    {
        $fsg3 = 'subgroup != "'.$dgsg[3].'"';
        if (empty($fsg)) $fsg = $fsg3;
        else $fsg = $fsg." AND ".$fsg3;
    }
if(!empty($dgsg[4])) 
    {
        $fsg4 = 'subgroup != "'.$dgsg[4].'"';
        if (empty($fsg)) $fsg = $fsg4;
        else $fsg = $fsg." AND ".$fsg4;
    }
if(!empty($dgsg[5])) 
    {
        $fsg5 = 'subgroup != "'.$dgsg[5].'"';
        if (empty($fsg)) $fsg = $fsg5;
        else $fsg = $fsg." AND ".$fsg5;
    }

if (!empty($fdo) and !empty($fgo) and !empty($fsg))
{
$fdout = "(".$fdo.") AND (".$fgo.") AND (".$fsg.")";
}
elseif(!empty($fdo) and !empty($fgo) and empty($fsg))
{
$fdout = "(".$fdo.") AND (".$fgo.")";
}
elseif(!empty($fdo) and empty($fgo) and !empty($fsg))
{
$fdout = "(".$fdo.") AND (".$fsg.")";
}
elseif(empty($fdo) and !empty($fgo) and !empty($fsg))
{
$fdout = "(".$fgo.") AND (".$fsg.")";
}
elseif(!empty($fdo) and empty($fgo) and empty($fsg))
{
$fdout = $fdo;
}
elseif(empty($fdo) and empty($fgo) and !empty($fsg))
{
$fdout = $fsg;
}
elseif(empty($fdo) and !empty($fgo) and empty($fsg))
{
$fdout = $fgo;
}
else
{
$fdout = 1;
}

//-------------------------------------------------------------------
//$mrid = $_SESSION['mrid']-1;
//get drug from temp file

$filter = mysqli_query($link, "select * from $tmp");
//control count//

if($_SESSION['ORDER']==0)
{
      while ($row = mysqli_fetch_array($filter))
      {
	$_SESSION['DG']=1;
	for($t=1;$t<=10;$t++)
	{
	  $idrx[$t]= $row['idrx'.$t];
	  $rx[$t]=$row['rx'.$t];
	  $rxg[$t]=$row['rxg'.$t];
	  $rxuses[$t]=$row['rx'.$t.'uses'];
	  $rxv[$t]=$row['rx'.$t.'v'];
	  //echo $idrx[$t];
      /*    if($idrx[$t] >"0")
	  {
	    $_SESSION['DG']=$_SESSION['DG']+1;
	  }*/
	      $ptin = mysqli_query($link, "select * from drug_id where id='$idrx[$t]' AND $cat AND $fdout AND $foutlast  AND $fddi");
	      while ($row2 = mysqli_fetch_array($ptin))
		      {
			      $rxtypen[$t] =  $row2['typen'];
			      $rxsize[$t] = $row2['size'];
			      $rxsv[$t] = $row2['volume'];
			      $rxrserve[$t] = $row2['volreserve'];
		      }
	  //$row['id']."-".$row['dgname']."-".$row['dname']."-".$row['size']."-".$row['typen']
	  if($idrx[$t]!=0 )
	  {
	  $_SESSION['drug'.$t]= $idrx[$t]."-".$rxg[$t]."-".$rx[$t]."-".$rxsize[$t]."-".$rxtypen[$t];
	  $_SESSION['dgname'.$t] = $rxg[$t];
	  $_SESSION['vol'.$t]=$rxv[$t];
	  $_SESSION['uses'.$t]=$rxuses[$t];
	  $_SESSION['svol'.$t]=$rxsv[$t];
	  $_SESSION['svolr'.$t]=$rxrserve[$t];
	  
	  }
	  elseif($idrx[$t]==0)
	  {
	    unset($_SESSION['drug'.$t]);
	    unset($_SESSION['dgname'.$t]);
	    unset($_SESSION['vol'.$t]);
	    unset($_SESSION['uses'.$t]);
	    unset($_SESSION['svol'.$t]);
	    unset($_SESSION['svolr'.$t]);
	  }
	}
      }

      for($t=1;$t<=10;$t++)
      {
	  if(!empty($_SESSION['drug'.$t]))
	  {
	    $_SESSION['DG']=$_SESSION['DG']+1;
	  }
      }
      //reorder drug need $_SESSION['drug'.$t], $_SESSION['vol'.$t],unset($_SESSION['uses'.$t]);
      // new stack for record value 
      $j=1; $k=1;
      for($i=1;$i<=$_SESSION['DG'];$i++)
      {
	for($j=$k;$j<=10;$j++)
	{
	  if(empty($_SESSION['drug'.$j]))
	  {
	    $k=$k+1;
	  }
	  else
	  {
	    //assign value to new stack
	    $_SESSION['drugnew'.$i]=$_SESSION['drug'.$j];
	    $_SESSION['dgnamenew'.$i]=$_SESSION['dgname'.$j];
	    $_SESSION['volnew'.$i]=$_SESSION['vol'.$j];
	    $_SESSION['usesnew'.$i]=$_SESSION['uses'.$j];
	    $_SESSION['svolnew'.$i]=$_SESSION['svol'.$j];
	    $_SESSION['svolrnew'.$i]=$_SESSION['svolr'.$j];
	    $k=$k+1;
//	    $j=$j+1;
	    //now unset old $_SESSION['drug'.$t], $_SESSION['vol'.$t],unset($_SESSION['uses'.$t]);
	    unset($_SESSION['drug'.$j]);
	    unset($_SESSION['dgname'.$j]);
	    unset($_SESSION['vol'.$j]);
	    unset($_SESSION['uses'.$j]);
	    unset($_SESSION['svol'.$j]);
	    unset($_SESSION['svolr'.$j]);
	    goto nexti;
	  }
	}
	nexti:
      }
      // now put data back to $_SESSION['drug'.$t], $_SESSION['vol'.$t],$_SESSION['uses'.$t];
      for($i=1;$i<=$_SESSION['DG'];$i++)
      {
	    $_SESSION['drug'.$i]=$_SESSION['drugnew'.$i];
	    $_SESSION['dgname'.$i]=$_SESSION['dgnamenew'.$i];
	    $_SESSION['vol'.$i]=$_SESSION['volnew'.$i];
	    $_SESSION['uses'.$i]=$_SESSION['usesnew'.$i];
	    $_SESSION['svol'.$i]=$_SESSION['svolnew'.$i];
	    $_SESSION['svolr'.$i]=$_SESSION['svolrnew'.$i];
	//clear data at new stack
	    unset($_SESSION['drugnew'.$i]);
	    unset($_SESSION['dgnamenew'.$i]);
	    unset($_SESSION['volnew'.$i]);
	    unset($_SESSION['usesnew'.$i]);
	    unset($_SESSION['svolnew'.$i]);
	    unset($_SESSION['svolrnew'.$i]);
      }
      
////control count//set to 1
$_SESSION['ORDER']=1;
//
}
/*
for($i=1;$i<=10;$i++)
{
  unset($_SESSION['drug'.$i]);
}
*/
$filter = mysqli_query($link, "select * from drug_id");		
	while ($row = mysqli_fetch_array($filter))
	{
		if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
	}	

	
if ($_SESSION['DG']<1)
{ 
  $i=1; 
  $_SESSION['DG']=1;
}

else $i = $_SESSION['DG'];

$i = $_SESSION['DG'];

if($_POST['add'] == 'เพิ่ม') 
{ 
  //check ddiltemp
  $_SESSION['ddiltemp'] = $_POST['ddiltemp'];
  if ($_SESSION['DG']<1) 
  {
  $_SESSION['DG']=1;
  $i = $_SESSION['DG'];
  }
  for($j=1;$j<=$i;$j++)
      {
      $_SESSION['drug'.$j]=$_POST['drug'.$j];
      $_SESSION['uses'.$j]=$_POST['uses'.$j];
      $_SESSION['oldvol'.$j]= $_SESSION['vol'.$j];
      $_SESSION['vol'.$j]=$_POST['vol'.$j];
      //
	  $drug = $_SESSION['drug'.$j];
	  $drugid[$j] = strstr($drug, '-', true); 
	  //echo $drugid[$j];
	  //
	  $ptin = mysqli_query($link, "select * from drug_id where id='$drugid[$j]' AND $cat AND $fdout AND $foutlast  AND $fddi");
	  while ($row2 = mysqli_fetch_array($ptin))
		  {
			  $rxuses[$j] =  $row2['uses'];
			  //check and compair uses order
			  if(empty($_SESSION['uses'.$j]))
			  {
			    $_SESSION['uses'.$j] = $rxuses[$j];
			  }
			  $_SESSION['svol'.$j] =  $row2['volume'];
			  $_SESSION['svolr'.$j] = $row2['volreserve'];
		  }
      //
      }
  if($_SESSION['DG']<10)
  {
  $_SESSION['DG'] = $_SESSION['DG']+1;
  $i = $_SESSION['DG'];
  }
//header("Location: drugorder.php");  
}
	
for($n=1;$n<=10;$n++)
{
  
  if($_POST['del'.$n]=='ลบ')
  {
    //check ddiltemp
    $_SESSION['ddiltemp'] = $_POST['ddiltemp'];
  
    //update drug_id at volreserve return volume.
    //update reserve volume at drug_id
    $rvolnew = $_SESSION['svolr'.$n] - $_SESSION['vol'.$n] ;
    unset($_SESSION['vol'.$n]);
    unset($_SESSION['oldvol'.$n]);
    unset($_SESSION['svol'.$n]);
    unset($_SESSION['svolr'.$n]);
    mysqli_query($link, "UPDATE drug_id SET
	    `volreserve` = '$rvolnew' WHERE `id` ='$drugid[$n]' LIMIT 1 ;
	    ") or die(mysqli_error($link));
   //
 
   for($m=$n;$m<$i;$m++)
     {
      $n=$n+1;
      $_SESSION['drug'.$m]=$_SESSION['drug'.$n];
      $_SESSION['vol'.$m]=$_SESSION['vol'.$n];
      $_SESSION['uses'.$m]=$_SESSION['uses'.$n];
     }
          //
	for($j=1;$j<=$i;$j++)
	    {
	    //$_SESSION['drug'.$j]=$_POST['drug'.$j];
	    //
		$drug = $_SESSION['drug'.$j];
		$drugid[$j] = strstr($drug, '-', true); 
		//echo $drugid[$j];
		$ptin = mysqli_query($link, "select * from drug_id where id='$drugid[$j]' AND $cat AND $fdout AND $foutlast  AND $fddi");
		while ($row2 = mysqli_fetch_array($ptin))
			{
				$rxuses[$j] =  $row2['uses'];
				$_SESSION['svol'.$j] =  $row2['volume'];
				$_SESSION['svolr'.$j] = $row2['volreserve'];
			}
	    }
      //

     unset($_SESSION['drug'.$i]);
     $_SESSION['DG'] = $_SESSION['DG']-1;
     $i = $_SESSION['DG'];
    // header("Location: drugorder.php"); 
  }
  if ($_SESSION['DG']<1)
  { 
    $i=1; 
    $_SESSION['DG']=1;
  }
  
}

if($_POST['todo']=='Close' or $_POST['todo']=='OK')
{
    
    for($a=1;$a<=10;$a++)
    {
      $_SESSION['drug'.$a]=$_POST['drug'.$a];
      $drug = $_SESSION['drug'.$a];
    /*  
      $domain = strstr($drug, '-');
      echo $domain; // prints @example.com

      $user = strstr($drug, '-', true); // As of PHP 5.3.0
      echo $user; // prints name
    */
      $drugid[$a] = strstr($drug, '-', true); 
      //echo $drugid[$a];
    }
    
	for($a=1;$a<=10;$a++)
	{
	  $idrx[$a] = $drugid[$a];
	  
	  $ptin = mysqli_query($link, "select * from drug_id where id='$idrx[$a]' AND $cat AND $fdout AND $foutlast  AND $fddi");
	  while ($row2 = mysqli_fetch_array($ptin))
		  {
			  $rx[$a] =  $row2['dname'].'-'.$row2['size'];
			  $rxgn[$a] = $row2['dgname'];
			  $rxuses[$a] =  $row2['uses'];
			  $rxv[$a] =  $_POST['vol'.$a];
			  $_SESSION['svol'.$a] =  $row2['volume'];
			  $_SESSION['uses'.$a]= $_POST['uses'.$a];
			  //check uses if change from default
			  if($_SESSION['uses'.$a]!=$rxuses[$a])
			  {
			    $rxuses[$a]=$_SESSION['uses'.$a];
			  }
			  $_SESSION['vol'.$a]= $_POST['vol'.$a];
			  $_SESSION['svolr'.$a] = $row2['volreserve'];
			  $candp = $row2['candp'];
			  if($candp ==1){$_SESSION['course']=1;}
			  if($candp ==2){$_SESSION['prolab']=1;}
		  }
	  //check for blocking drug and reset $idrx[$a] from $rxgn[$a]
	  if(empty($rxgn[$a]))
	  {
	    $idrx[$a]=0;
	  }
	  //
	}
	for($i=1;$i<=10;$i++)
	{
		$us = "rx".$i."uses";
		$vl = "rx".$i."v";
		$svl = "rx".$i."sv";
		$idp = $idrx[$i];
		$rxp = $rx[$i];
		$rgp = $rxgn[$i];
		$usp = $rxuses[$i];
		$vlp = $rxv[$i];
		$svlp = $_SESSION['svol'.$i];
		mysqli_query($link, "UPDATE $tmp SET
			`idrx$i` = '$idp',
			`rx$i` = '$rxp',
			`rxg$i` = '$rgp',
			`$us` = '$usp',
			`$vl` = '$vlp',
			`$svl` = '$svlp'
			") or die(mysqli_error($link));
/*	if($_SESSION['prolab'])
	{
                mysqli_query($link, "UPDATE $tmp SET `prolab` = '1'") or die(mysqli_error($link));
                unset($_SESSION['prolab']);
	}
	if($_SESSION['course'])
	{
                mysqli_query($link, "UPDATE $tmp SET `course` = '1'") or die(mysqli_error($link));
                unset($_SESSION['course']);
	}
			
*/		//update reserve volume at drug_id $_SESSION['svolr'
		$_SESSION['oldvol'.$i] = $_POST['oldvol'.$i];
		$rvolnew = $vlp - $_SESSION['oldvol'.$i] + $_SESSION['svolr'.$i];
		
		$_SESSION['oldvol'.$i] = $vlp;//new old order volume
		$_SESSION['svolr'.$i] = $rvolnew;//new old reserve volume
		//check not reserve less than 0
		if ($rvolnew <= 0) $rvolnew = 0;
//	  if($_POST['todo']=='Close')
	  {
		  mysqli_query($link, "UPDATE drug_id SET
			  `volreserve` = '$rvolnew' WHERE `id` ='$idp' LIMIT 1 ;
			  ") or die(mysqli_error($link));
		//  unset($_SESSION['oldvol'.$i]);
	  }
	}
unset($_SESSION['ddiltemp']);
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Prescription</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="../public/js/jquery.autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="../public/css/jquery.autocomplete.css" />
	<link rel="stylesheet" href="../public/css/styles.css">
<?php 
include '../libs/autodrug.php';
include '../libs/autoorder.php';
include '../libs/reloadopener.php';
?>
</head>

<body>
<div id="content">
<form name="ddx" method="post" action="drugorder.php">
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr>
    <td><?php echo "BW = ".$_SESSION['weight']." kg ";  echo "DDIL default = ".$_SESSION['ddil']; ?> [กำหนด DDIL เฉพาะรายนี้:
    <input name='ddiltemp' type='radio' value='0' <?php if($_SESSION['ddiltemp']==0) echo 'checked';?>>0 
    <input name='ddiltemp' type='radio' value='1' <?php if($_SESSION['ddiltemp']==1) echo 'checked';?>>|1| 
    <input name='ddiltemp' type='radio' value='2' <?php if($_SESSION['ddiltemp']==2) echo 'checked';?>>|2| 
    <input name='ddiltemp' type='radio' value='3' <?php if($_SESSION['ddiltemp']==3) echo 'checked';?>>|3|] 
     <table border='1' style='text-align: center; margin-left: auto; margin-right: auto;' >
      <tr><th>Order</th><th width="44%">DRUG LIST</th><th width="40%">วิธีการใช้ยา</th><th>Vol</th><th>เพิ่ม</th><th>ลบ</th></tr>
	    <?php 
	    for($j=1;$j<=$i;$j++)
	    {
	    if($j==$i)
	    {
	    echo "<tr><td>".$j."</td><td><input name='drug".$j."' type=text id=drug".$j;
	    echo " value='".$_SESSION['drug'.$j]."' size=44% autofocus/></td>";
	    }
	    else
	    {
	    echo "<tr><td>".$j."</td><td><input name='drug".$j."' type=text id=drug".$j;
	    echo " value='".$_SESSION['drug'.$j]."' size=44%/></td>";
	    }
	    echo "<td>";
	    echo "<input type='search' id=ordertxt$j size=40% name='uses".$j."' value='".$_SESSION['uses'.$j]."'>";
	    echo "</td>";
	    echo "<td>";
	    echo "<input type='hidden' name='oldvol".$j."' value='".$_SESSION['vol'.$j]."'>";
	    echo "<input type='number' tabindex=".$j." class=typenumber name='vol".$j."' min=0 step=1 max='".($_SESSION['svol'.$j]-$_SESSION['svolr'.$j]+$_SESSION['vol'.$j])."' value=".$_SESSION['vol'.$j].">";
	    echo "</td>";
	    echo "<td><input type=submit name='add' value='เพิ่ม'></td>";
	    echo "<td><input type=submit name='del".$j."' value='ลบ'></td>";
	    echo "</tr>";
	    }
	    //echo $_SESSION['ddx'];
	    ?>
      </table>
    </td>
  </tr>
  <tr><th><input type="submit" name="todo" value="OK" onClick="reloadParent;"/><input type="submit" name="todo" value="Close" onClick="reloadParentAndClose();"/></th></tr>
</table>
<!--end menu-->
</form></div>
</body>
</html>
