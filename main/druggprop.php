<?php 
include '../login/dbc.php';
page_protect();

$pin = mysqli_query($linkcm, "select MAX(id) from druggeneric");
$rid = mysqli_fetch_array($pin);
$id = $rid[0];
//if(empty($_SESSION['minrow'])) $_SESSION['minrow'] = 0;

$pagelimit = 15;

$maxpage = ceil($rid[0]/$pagelimit);

if(empty($_SESSION['page']))
{
$_SESSION['page'] =1;
$minrow = 0;
}
else
{
$minrow = ($_SESSION['page']-1)*$pagelimit;
}
$druggroup = mysqli_query($link, "SELECT name FROM drug_group ORDER BY `name` ASC");
$drgnr = mysqli_num_rows($druggroup);
$i=1;
while($drgrow = mysqli_fetch_array($druggroup))
{
 $dgrname[$i]= $drgrow['name'];
 $i = $i+1;
}
$drugsubgroup = mysqli_query($link, "SELECT name FROM drug_subgroup ORDER BY `name` ASC");
$drsgnr = mysqli_num_rows($drugsubgroup);
$i=1;
while($drgsrow = mysqli_fetch_array($drugsubgroup))
{
 $dsgrname[$i]= $drgsrow['name'];
 $i = $i+1;
}

$ddx = mysqli_query($linkcm, "SELECT name FROM diag ORDER BY `name` ASC");
$ddxnr = mysqli_num_rows($ddx);
$i=1;
while($ddxrow = mysqli_fetch_array($ddx))
{
 $ddxname[$i]= $ddxrow['name'];
 $i = $i+1;
}

if($_POST['resetpage'] == "Reset Page")
{
  $_SESSION['page'] = $_POST['pageno'];
  if($_SESSION['page']==1) $minrow = 0;
  else  $minrow = ($_SESSION['page']-1)*$pagelimit;
}

if($_POST['SetValue'] == "Set Value")
{
   for($j=1;$j<=$id;$j++)
   {
     $dgn = mysqli_fetch_array( mysqli_query($linkcm, "select * from druggeneric WHERE id = $j"));
     $dgnamein = $dgn['name'];
     $dgroupin = $dgn['dgroup'];
     $dsgroupin = $dgn['dsgroup'];
     $dcatin = $dgn['dcat'];
     $dinteract = $dgn['dinteract'];
     $dciwith = $dgn['ciwith'];
     
      mysqli_query($link, "UPDATE drug_id SET
			      `groupn` = '$dgroupin',
			      `subgroup` = '$dsgroupin',
			      `cat` = '$dcatin',
			      `dinteract` = '$dinteract'
			      WHERE `dgname`='$dgnamein'
			      ") or die(mysqli_error($link));
      if(!empty($dciwith))
      {
	if(!empty($dsgroupin))
	{
	  $dsgroupin = $dsgroupin.'_dsg';
	  $dicon = mysqli_fetch_array( mysqli_query($linkcm, "select id from drandillci WHERE drugname = '$dsgroupin' and chronname = '$dciwith'"));
	  if(empty($dicon['id']))
	  {
	        $sql_insert = "INSERT into `drandillci` ( chronname, drugname) value ('$dciwith','$dsgroupin')";
	        mysqli_query($linkcm, $sql_insert) or die("Insertion Failed:" . mysqli_error($linkcm));
	  }
	}
	if(!empty($dgroupin))
	{
	  $dgroupin = $dgroupin.'_drg';
	  $dicon = mysqli_fetch_array( mysqli_query($linkcm, "select id from drandillci WHERE drugname = '$dgroupin' and chronname = '$dciwith'"));
	  if(empty($dicon['id']))
	  {
	        $sql_insert = "INSERT into `drandillci` ( chronname, drugname) value ('$dciwith','$dgroupin')";
	        mysqli_query($linkcm, $sql_insert) or die("Insertion Failed:" . mysqli_error($linkcm));
	  }
	}
	if(empty($dsgroupin) and empty($dgroupin))
	{
	  $dicon = mysqli_fetch_array( mysqli_query($linkcm, "select id from drandillci WHERE drugname = '$dgnamein' and chronname = '$dciwith'"));
	  if(empty($dicon['id']))
	  {
	        $sql_insert = "INSERT into `drandillci` ( chronname, drugname) value ('$dciwith','$dgnamein')";
	        mysqli_query($linkcm, $sql_insert) or die("Insertion Failed:" . mysqli_error($linkcm));
	  }
	}
	
      }
      
   }
   
}
if($_POST['register'] == 'แก้ไข') 
{
  for($j=($minrow+1);$j<=$_SESSION['page']*$pagelimit;$j++)
  {
    $dgr= $_POST['drg'.$j];
    $dsgr = $_POST['drsg'.$j];
    $dcat = $_POST['cat'.$j];
    $dciwith = $_POST['ciwith'.$j];
    
      mysqli_query($linkcm, "UPDATE druggeneric SET
			      `dgroup` = '$dgr',
			      `dsgroup` = '$dsgr',
			      `dcat` = '$dcat',
			      `ciwith` = '$dciwith'
			      WHERE id='$j' LIMIT 1
			      ") or die(mysqli_error($linkcm));
      
  }
$minrow = $_SESSION['page']*$pagelimit;
$_SESSION['page'] = $_SESSION['page']+1;
if ($minrow > $id)
{
  unset($_SESSION['page']);
  $minrow = 0;
 header("Location: druggprop.php");  
}
}
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
<!--menu--><form method="post" action="druggprop.php">
			<h3 class="titlehdr">Set Generic Drug Properties&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reset Page to <input type=number min=1 max="<?php echo $maxpage; ?>" size=2 name=pageno value=<?php echo $_SESSION['page'];?>>:<input type=submit name=resetpage value="Reset Page">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit name=SetValue value="Set Value"></h3>
			
				<table style="text-align: center;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="width: 18px;"></td>
						<td style="vertical-align: middle; ">
						<div style="text-align: center;">
<?php	
echo "<table class='TFtable' border='1' style='text-align: left; margin-left: auto; margin-right: auto;'>";
echo "<tr><th>ID</th><th>Name</th><th>Group</th><th>SubGroup</th><th>Cat</th><th>Interation</th><th>CI-With</th></tr>";
$filter = mysqli_query($linkcm, "select * from druggeneric  ORDER BY `name` ASC LIMIT $minrow, $pagelimit");
while($row = mysqli_fetch_array($filter))
  {
		// Print out the contents of each row into a table
		echo "<tr><th>";
		echo $row['id'];
		echo "</th><th>"; 
		echo $row['name'];
		echo "</th><th>";
		echo "<select name=drg".$row['id'].">";
		echo "<option value='";
		echo $row['dgroup'];
		echo "' selected>";
		echo $row['dgroup'];
		echo "</option>";
		echo "<option value=''></option>";
		for($i=1;$i<=$drgnr;$i++)
		{
		  echo "<option value='";
		  echo $dgrname[$i];
		  echo "'>";
		  echo $dgrname[$i];
		  echo "</option>";
		}
		echo "</select>";
		echo "</th><th >"; 
		echo "<select name=drsg".$row['id'].">";
		echo "<option value='".$row['dsgroup']."' 'selected'>".$row['dsgroup']."</option>";
		echo "<option value=''></option>";
		for($i=1;$i<=$drsgnr;$i++)
		{
		  echo "<option value=";
		  echo $dsgrname[$i];
		  echo ">";
		  echo $dsgrname[$i];
		  echo "</option>";
		}
		echo "</select>";
		echo "</th><th>"; 
		echo "<select name=cat".$row['id'].">";
		echo "<option value='".$row['dcat']."' 'selected'>".$row['dcat']."</option><option value=A>A</option><option value=B>B</option><option value=C>C</option><option value=D>D</option><option value=X>X</option><option value=N>N</option></select>";
		echo "</th><th>";
		//Interation
		if(empty($row['dinteract']))
		{
		    $msg = urlencode($row['id']);
		    echo "<a href=interactionset.php?msg=".$msg."  target='_blank'>"."Set</a>";
		}
		else
		{
		    $msg = urlencode($row['id']);
		    echo "<a href=interactionset.php?msg=".$msg."  target='_blank'>"."Update</a>";
		}
		//Interation end
		echo "</th><th>"; 
		echo "<select name=ciwith".$row['id'].">";
		echo "<option value='";
		echo $row['ciwith'];
		echo "' selected>";
		echo $row['ciwith'];
		echo "</option>";
		echo "<option value=''></option>";
		for($i=1;$i<=$ddxnr;$i++)
		{
		  echo "<option value=";
		  echo "\"".$ddxname[$i]."\"";
		  echo ">";
		  echo $ddxname[$i];
		  echo "</option>";
		}
		echo "</select>";
//		echo $row['ciwith'];
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
					<tr>
					<td>&nbsp;</td>
					<td>
						<div style="text-align: center;"><?php if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0){?><input name="register" value="แก้ไข" type="submit"><?php }?></div>
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