<?php 
include '../login/dbc.php';
page_protect();

$fulluri = $_SERVER['REQUEST_URI'];
$drugidin = filter_var($fulluri, FILTER_SANITIZE_NUMBER_INT);
if(!empty($drugidin))
{
if($_SESSION['drugidin'] != $drugidin) $_SESSION['drugidin'] = $drugidin;
}
$pin = mysqli_query($linkcm, "select MAX(id) from druggeneric");
$rid = mysqli_fetch_array($pin);
$id = $rid[0];
/*
if(empty($_SESSION['drugidin']))
{
$fulluri = $_SERVER['REQUEST_URI'];
$drugidin = filter_var($fulluri, FILTER_SANITIZE_NUMBER_INT);
$_SESSION['drugidin'] = $drugidin;
}
*/
if($_POST['register'] == 'แก้ไข') 
{
//$_SESSION[drugidin] part   
  for($j=1;$j<=$id;$j++)
  {
    $itl = "itl".$j;
    $itout = $_POST[$itl];
    if(!empty($itout))
    { //assign pattern
      if(empty($ita))
      {
      $ita = "[".$j.",".$itout."]";
      }
      else
      {
      $ita = $ita.";"."[".$j.",".$itout."]";
      }
    }
   } 
   //assign pattern
   //update Interaction at Drug ID In.
    mysqli_query($linkcm, "UPDATE druggeneric SET `dinteract` = '$ita' WHERE id='$_SESSION[drugidin]' LIMIT 1  ") or die(mysqli_error($linkcm));
//$_SESSION[drugidin] part end

//drug id = $j part start
 for($j=1;$j<=$id;$j++)    
 {    
    $itl = "itl".$j;
    $itout = $_POST[$itl];
    $itlold = "itlold".$j;
    $itoutold = $_POST[$itlold];

    if($itoutold!=$itout) //not equal ->change, equal -> not change
    {
      $filter = mysqli_query($linkcm, "select dinteract from druggeneric  where id=$j ");
      while($row = mysqli_fetch_array($filter))
	{
	  $itz = $row['dinteract'];
	}	
	if(!empty($itz))
	{
	    $n = substr_count($itz, ';');
	    //$str = 'hypertext;language;programming';
	    $charsl = preg_split('/;/', $itz);
	
	  for($b=0;$b<=$n;$b++)
	  {
	    $charslnew[$b] = str_replace('[', '', $charsl[$b]);
	    $charslnew[$b] = str_replace(']', '', $charslnew[$b]);
	    $chars = preg_split('/,/', $charslnew[$b]);
//		    if(empty($itw)) $itw = $chars[0];
//		    else $itw = $itw.','.$chars[0];
	  }
	  
	  //find $_SESSION['drugidin'] in this id.
	  for($b=0;$b<=$n;$b++)
	  {
	    $chars = preg_split('/,/', $charslnew[$b]);
	    if($chars[0]==$_SESSION['drugidin']) 
	    {
	     // $itz='';
	      goto gotithere;
	    }
	  }
	}
	
	gotithere:
	$itz='';
      //change value if not empty, remove if empty
      if(!empty($itout))
      {
	
	//remove+change this $_SESSION['drugidin'] from $itz
	for ($c=0;$c<$b;$c++)
	{
	  if(empty($itz)) $itz = $charsl[$c];
	  else $itz = $itz.";".$charsl[$c];
	}
	//change value if not empty, remove if empty
	  if(empty($itz)) $itz = "[".$_SESSION['drugidin'].",".$itout."]";
	  else $itz = $itz.";[".$_SESSION['drugidin'].",".$itout."]";
	//
	for ($c=$b+1;$c<=$n;$c++)
	{
	  if(empty($itz)) $itz = $charsl[$c];
	  else $itz = $itz.";".$charsl[$c];
	}
      }
      else
      {
	//remove+change this $_SESSION['drugidin'] from $itz
	for ($c=0;$c<$b;$c++)
	{
	  if(empty($itz)) $itz = $charsl[$c];
	  else $itz = $itz.";".$charsl[$c];
	}
	//
	for ($c=$b+1;$c<=$n;$c++)
	{
	  if(empty($itz)) $itz = $charsl[$c];
	  else $itz = $itz.";".$charsl[$c];
	}
	
      }
      //update Interaction at Drug ID out.
	mysqli_query($linkcm, "UPDATE druggeneric SET `dinteract` = '$itz' WHERE id='$j' LIMIT 1  ") or die(mysqli_error($linkcm));
    }
  }
// drug id =$j part end

} 

$pin = mysqli_query($linkcm, "select name from druggeneric WHERE id='$_SESSION[drugidin]'");
$dinname = mysqli_fetch_array($pin);

?>

<!DOCTYPE html>
<html>
<head>
<title>รายการยาและผลิตภัณฑ์</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<link rel="stylesheet" href="../public/css/styles.css">
<link rel="stylesheet" href="../public/css/table_alt_color.css">
</head>
<?php 
if(!empty($_SESSION['user_background']))
{
echo "<body style='background-image: url(".$_SESSION['user_background'].");' alink='#000088' link='#006600' vlink='#660000'>";
}
else
{
?>
<body style="background-image: url(../image/ptbg.jpg);" alink="#000088" link="#006600" vlink="#660000">
<?php
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td colspan="3" >&nbsp;</td></tr>
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
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
<!--menu--><form method="post" action="interactionset.php">
			<h3 class="titlehdr">Set Generic Drug Properties&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div style="text-align: center;"><?php if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0){?><input name="register" value="แก้ไข" type="submit"><?php }?></div><hr>Interaction with <?php echo $dinname[0];?></h3>
			
				<table style="text-align: center;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="width: 18px;"></td>
						<td style="vertical-align: middle; ">
						<div style="text-align: center;">
<?php	
echo "<table class='TFtable' border='1' style='text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);'>";
echo "<tr><th>ID</th><th>Name</th><th>Interation With ID</th><th>Interation Level</th></tr>";
$filter = mysqli_query($linkcm, "select * from druggeneric  ORDER BY `id` ASC");
while($row = mysqli_fetch_array($filter))
  {
		// Print out the contents of each row into a table
		echo "<tr><th>";
		echo $itid = $row['id'];
		echo "</th><th>"; 
		echo $row['name'];
		echo "</th><th>";
		$iti = $row['dinteract'];
		//concurdrug
		$itw='';
		if(!empty($iti))
		{
		    $n = substr_count($iti, ';');
		    //$str = 'hypertext;language;programming';
		    $charsl = preg_split('/;/', $iti);
		
		  for($b=0;$b<=$n;$b++)
		  {
		    $charslnew[$b] = str_replace('[', '', $charsl[$b]);
		    $charslnew[$b] = str_replace(']', '', $charslnew[$b]);
		    $chars = preg_split('/,/', $charslnew[$b]);
		    if(empty($itw)) $itw = $chars[0];
		    else $itw = $itw.','.$chars[0];
		  }
		  
		  //find $_SESSION['drugidin'] in this id.
		  for($b=0;$b<=$n;$b++)
		  {
		    $chars = preg_split('/,/', $charslnew[$b]);
		    if($chars[0]==$_SESSION['drugidin']) 
		    {
		      $iti='';
		      goto gotit;
		    }
		  }
		}
		//not match id
		$chars[1]='';
		gotit:
/*		
		//remove this $_SESSION['drugidin'] from $iti
		for ($c=0;$c<$b;$c++)
		{
		  if(empty($iti)) $iti = $charsl[$c];
		  else $iti = $iti.";".$charsl[$c];
		}
		for ($c=$b+1;$c<=$n;$c++)
		{
		  if(empty($iti)) $iti = $charsl[$c];
		  else $iti = $iti.";".$charsl[$c];
		}
*/
		echo $itw;
		//match id
		echo "</th><th>";
		if($row['id']!=$_SESSION['drugidin'])
		{
		echo "<input type=hidden name=itlold".$row['id']." value=".$chars[1].">";
		echo "<select name=itl".$row['id'].">";
		echo "<option value=''></option>";
		echo "<option value='-1'"; if($chars[1]=='-1') echo " Selected"; echo ">Minor(-)</option>";
		echo "<option value='1'"; if($chars[1]=='1') echo " Selected"; echo ">Minor(+)</option>";
		echo "<option value='-2'"; if($chars[1]=='-2') echo " Selected"; echo ">Significant(-)</option>";
		echo "<option value='2'"; if($chars[1]=='2') echo " Selected"; echo ">Significant(+)</option>";
		echo "<option value='-3'"; if($chars[1]=='-3') echo " Selected"; echo ">Serious(-)</option>";
		echo "<option value='3'"; if($chars[1]=='3') echo " Selected"; echo ">Serious(+)</option>";
		echo "<option value='5'"; if($chars[1]=='5') echo " Selected"; echo ">Contraindicated</option></select>";
		}
		echo "</th></tr>";
		
}
echo "</table>";
?>
							<br>
							</div>
						</td>
						<td>
					</td>
					</tr>
				</tbody>
				</table>
			</form>
<!--menu end-->
		</td>
	</tr>
</table>
<!--end menu-->
</body></html>