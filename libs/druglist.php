<?php
include '../config/dbc.php';

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
//	$q=$_GET['q'];
// get the search term
    $q = isset($_REQUEST['term']) ? $_REQUEST['term'] : "";
	$my_data=mysqli_real_escape_string($link, $q);
/************************************/

include 'ptdrugill.php';

/***********************************/
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

/***************************************/

include 'ccdrlist.php';

/****************************************/
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
/*******************************/
include 'dricilist.php';

include 'catcset.php';

include 'drugfilter.php';
/***********************************************************/
	$sql="SELECT * FROM drug_id WHERE ( $cat AND $fdout AND $foutlast  AND $fddi) AND ( dname LIKE '%$my_data%' OR dgname LIKE '%$my_data%' OR typen LIKE '%$my_data%' OR groupn LIKE '%$my_data%' OR indication LIKE '%$my_data%') AND volume > 0 ORDER BY dgname";
	$result = mysqli_query($link,$sql);
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			$data[] = $row['id']."-".$row['dgname']."-".$row['dname']."-".$row['size']."-".$row['typen'];
		}
		echo json_encode($data);
	}
?>
