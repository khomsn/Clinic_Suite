<?php 
include '../login/dbc.php';
page_protect();

$sql = "
CREATE TABLE IF NOT EXISTS `drandillci` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chronname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `drugname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

";

mysqli_query($linkcm, $sql);

if($_POST['set'] == 'ReSet')
{
      mysqli_query($linkcm, "TRUNCATE TABLE drandillci") or die(mysqli_error($linkcm));
}
if($_POST['set'] == 'Set') 
{ 
  $j = $_SESSION['rowmax']-1;
  for($i=1;$i<=$j;$i++)
  {
    $chronname = "chronname".$i;
    $drugname = "drugname".$i;
    
    mysqli_query($linkcm, "UPDATE drandillci SET
			    `chronname` = '$_POST[$chronname]',
			    `drugname` = '$_POST[$drugname]'
			    WHERE id='$i'
			    ") or die(mysqli_error($linkcm));
  }
  
    $i = $_SESSION['rowmax'];
    $chronname = "chronname".$i;
    $drugname = "drugname".$i;
  if(!empty($_POST[$chronname]))
  {
    // assign insertion pattern
    $sql_insert = "INSERT into `drandillci`
			    (`chronname`,`drugname`)
			VALUES
			    ('$_POST[$chronname]','$_POST[$drugname]')";

    // Now insert into "drandillci" table
    mysqli_query($linkcm, $sql_insert) or die("Insertion Failed:" . mysqli_error($linkcm));
  }
  unset($_SESSION['rowmax']);
} 
?>

<!DOCTYPE html>
<html>
<head>
<title>รายการยาและผลิตภัณฑ์</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
	<link rel="stylesheet" href="../public/css/styles.css">
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
		<td>
<!--menu-->
<form method="post" action="drandillci.php" name="regForm" id="regForm">
<h3 class="titlehdr">โรค/Condition และ ยาที่ห้ามใช้   <input type=submit name=set value=Set>  <input type=submit name=set value=ReSet></h3>

<table style="text-align: center;" border="1" cellpadding="2" cellspacing="2">
<tbody><tr><th width="50%">Condition</th><th>Drug Group/Name</th></tr>
<?php 
$drug = mysqli_query($linkcm, "select * from drandillci");
$i=1;
while($dcs = mysqli_fetch_array($drug))
{
 echo "<tr><th width=50%>";
 echo "<input type=text name='chronname".$dcs['id']."' value='".$dcs['chronname']."'>";
// echo $dsc['drugchronname'];
 echo "</th><th>";
 echo "<input type=text  name='drugname".$dcs['id']."' value='".$dcs['drugname']."'>";
// echo $dsc['drugname'];
 echo "</th></tr>";
$i=$dcs['id']+1;
}
$_SESSION['rowmax'] = $i;

?>
<tr><th width="50%"><input type='text' name='chronname<?php echo $i;?>' ></th><th><input type='text' name='drugname<?php echo $i;?>'></th></tr>
</tbody>
</table>
</form>
<!--menu end-->
  </td></tr>
</table>
<!--end menu-->
</body></html>
