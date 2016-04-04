<?php
include '../login/dbc.php';
	$id = $_GET['ptid'];
	$catc = $_GET['catc'];
	$ddil = $_GET['ddil'];
	$dg[1] = $_GET['dg1'];
	$dg[2] = $_GET['dg2'];
	$dg[3] = $_GET['dg3'];
	$dg[4] = $_GET['dg4'];
	$dg[5] = $_GET['dg5'];
	$dg[6] = $_GET['dg6'];
	$dg[7] = $_GET['dg7'];
	$dg[8] = $_GET['dg8'];
	$dg[9] = $_GET['dg9'];
	$dg[10] = $_GET['dg10'];
	$q=$_GET['q'];
	$my_data=mysqli_real_escape_string($link, $q);
//
$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
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
for($i=1;$i<=10;$i++)
{
  if(!empty($dg[$i]))
    {
      if(empty($orderlist)) $orderlist = $dg[$i];
      else $orderlist = $orderlist.','.$dg[$i];
    }
}
if(empty($concurdrug))$concurdrug=$orderlist;
else $concurdrug=$concurdrug.','.$orderlist;
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
//$ddil
for($i=1;$i<$ddindex;$i++)
{
  if(abs($dil[$i])>$ddil)
  {
    $cho = mysqli_query($linkcm, "select name from druggeneric where id = '$did[$i]' ");
    $didname = mysqli_fetch_array($cho);
    if(empty($fddi)) $fddi = 'dgname != "'.$didname[0].'"';
    else $fddi = $fddi.' AND dgname != "'.$didname[0].'"';
  }
}
if(empty($fddi)) $fddi = 1;

//add
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
//$ddil
for($i=1;$i<$ddindex;$i++)
{
  if(abs($dil[$i])>$ddil)
  {
    $cho = mysqli_query($linkcm, "select name from druggeneric where id = '$did[$i]' ");
    $didname = mysqli_fetch_array($cho);
    if(empty($fddi)) $fddi = 'dgname != "'.$didname[0].'"';
    else $fddi = $fddi.' AND dgname != "'.$didname[0].'"';
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
  if($catc ==1)
  {
    $cat = '(cat = "A" or cat = "B" or cat = "C" or cat = "N")';
  }
}
else $cat=1;
//

$j=1;$k=1;$l=1;

for($i=1;$i<=5;$i++)
{
  if(!empty($dl[$i]))
  {
      $drug = mysqli_query($linkcm, "select * from druggeneric where name='$dl[$i]'");

      while ($drugrs = mysqli_fetch_array($drug))
      {
      $dgn[$l] = $drugrs['name'];
      $l = $l+1;
	if(!empty($drugrs['dgroup'])) 
	{
	  $dgg[$j] = $drugrs['dgroup'];
	  $j = $j+1;
	}
	if(!empty($drugrs['dsgroup'])) 
	{
	  $dgsg[$k] = $drugrs['dsgroup'];
	  $k = $k+1;
	}
      }
  }
}

for($i=1;$i<=5;$i++)
{
  if(!empty($dl[$i]))
  {
      $drug = mysqli_query($linkcm, "select * from druggeneric where dgroup='$dl[$i]' ");

      while ($drugrs = mysqli_fetch_array($drug))
      {
      $dgg[$j] = $drugrs['dgroup'];
	if(!empty($drugrs['dsgroup'])) 
	{
	  $dgsg[$k] = $drugrs['dsgroup'];
	  $k = $k+1;
	}
	$j = $j+1;
      }
  }
}

for($i=1;$i<=5;$i++)
{
  if(!empty($dl[$i]))
  {
      $drug = mysqli_query($linkcm, "select * from druggeneric where dsgroup='$dl[$i]'");

      while ($drugrs = mysqli_fetch_array($drug))
      {
      $dgsg[$k] = $drugrs['dsgroup'];
      $k = $k+1;
      }
  }
}


if(!empty($dgn[1])) {$fdo1 = 'dgname != "'.$dgn[1].'"'; $fdo = $fdo1;}
if(!empty($dgn[2])) {$fdo2 = 'dgname != "'.$dgn[2].'"'; $fdo = $fdo." AND ".$fdo2;}
if(!empty($dgn[3])) {$fdo3 = 'dgname != "'.$dgn[3].'"'; $fdo = $fdo." AND ".$fdo3;}
if(!empty($dgn[4])) {$fdo4 = 'dgname != "'.$dgn[4].'"'; $fdo = $fdo." AND ".$fdo4;}
if(!empty($dgn[5])) {$fdo5 = 'dgname != "'.$dgn[5].'"'; $fdo = $fdo." AND ".$fdo5;}

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

//
	$sql="SELECT * FROM drug_id WHERE ( $cat AND $fdout AND $foutlast  AND $fddi) AND ( dname LIKE '%$my_data%' OR dgname LIKE '%$my_data%' OR typen LIKE '%$my_data%' OR groupn LIKE '%$my_data%' OR indication LIKE '%$my_data%') AND volume > 0 ORDER BY dgname";
	$result = mysqli_query($link,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			echo $row['id']."-".$row['dgname']."-".$row['dname']."-".$row['size']."-".$row['typen']."\n";
		}
	}
?>