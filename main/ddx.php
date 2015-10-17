<?php 
include '../login/dbc.php';
page_protect();

if ($_SESSION['DX']<1){ $i=1; $_SESSION['DX']=1;$_SESSION['dx']='';}
else $i = $_SESSION['DX'];

for($j=1;$j<=$i;$j++)
{
	if($_POST['add'] == 'เพิ่ม') 
	{ 
	for($j=1;$j<=$i;$j++)
	    {
	    $_SESSION['dx'.$j]=$_POST['diag'.$j];
	    }
	$_SESSION['DX'] = $_SESSION['DX']+1;
	
	header("Location: ddx.php");  
	}

	if($_POST['del'.$j] == 'ลบ' ) 
	{ 
	  for($d=1;$d<$j;$d++)
	  {
	      $_SESSION['dx'.$d]=$_POST['diag'.$d];
	  }
	  for($d=$j;$d<$i;$d++)
	  {
	      $dn = $d+1;
	      $_SESSION['dx'.$d]=$_POST['diag'.$dn];
	  }
	  unset($_SESSION['dx'.$i]);
	  $_SESSION['DX'] = $_SESSION['DX']-1;
	  if($_SESSION['DX']<=0) unset($_SESSION['DX']);
	  // go on to other step
	header("Location: ddx.php");  
	}
	
}
if($_POST['todo']=='Close' or $_POST['todo']=='Diag')
{
  for($j=1;$j<=$i;$j++)
  {
    $dxx = $j."-".$_POST['diag'.$j];
    $_SESSION['ddx'] = $_SESSION['ddx']." ".$dxx;
    unset($_SESSION['dx'.$j]);
  }
    unset($_SESSION['DX']);
}

?>

<!DOCTYPE html>
<html>
<head>
<title>DDx</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<!--add menu -->
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="../public/js/jquery.autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="../public/css/jquery.autocomplete.css" />
	<link rel="stylesheet" href="../public/css/styles.css">
<?php 
include '../libs/autodiag.php';
include '../libs/reloadopener.php';
?>
</head>

<body>
<div id="content">
<form name="ddx" method="post" action="ddx.php">
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
	<tr>
		<td>
				<table border='1' style='text-align: center; margin-left: auto; margin-right: auto;' >
				<tr><th>Order</th><th>Diagnosis</th><th>เพิ่ม</th><th>ลบ</th></tr>
			<?php 
			for($j=1;$j<=$i;$j++)
			{
			if($j==$i)
			{
			echo	"<tr><th>".$j."</th><th><input name='diag".$j."' type=text id=diag".$j;
			echo        " value='".$_SESSION['dx'.$j]."' size=50 autofocus/></th>";
			}
			else
			{
			echo	"<tr><th>".$j."</th><th><input name='diag".$j."' type=text id=diag".$j;
			echo        " value='".$_SESSION['dx'.$j]."' size=50 /></th>";
			}
			echo	    "<th><input type=submit name='add' value='เพิ่ม'></th>";
			echo	    "<th><input type=submit name='del".$j."' value='ลบ'></th>";
			echo	"</tr>";
			}
			//echo $_SESSION['ddx'];
			?>
				</table>
		</td>
	</tr>
	<tr><th><input type="submit" name="todo" value="Diag" onClick="reloadParent;"/><input type="submit" name="todo" value="Close" onClick="reloadParentAndClose();"/></th></tr>
</table>
<!--end menu-->
</form></div>
</body>
</html>
