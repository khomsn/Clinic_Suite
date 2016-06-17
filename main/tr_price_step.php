<?php 
include '../login/dbc.php';
page_protect();
$sql ="

CREATE TABLE IF NOT EXISTS `trpstep` (
  `id` int(11) NOT NULL,
  `drugid` int(11) NOT NULL,
  `firstone` tinyint(4) NOT NULL,
  `init_pr` int(11) NOT NULL,
  `secstep` tinyint(4) NOT NULL,
  `sec_pr` int(11) NOT NULL,
  `tristep` tinyint(4) NOT NULL,
  `tri_pr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='treatment price step cal';

ALTER TABLE `trpstep`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `drugid` (`drugid`),
  ADD KEY `id` (`id`);

ALTER TABLE `trpstep`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


";
mysqli_query($link, $sql);


if($_POST['set'] == 'ReSet')
{
      mysqli_query($link, "TRUNCATE TABLE trpstep") or die(mysqli_error($link));
}
if($_POST['set'] == 'Set') 
{ 
  $j = $_SESSION['rowmax']-1;
  for($i=1;$i<=$j;$i++)
  {
    $drugid = "drugid".$i;
    $firstone = "firstone".$i;
    $initpr = "initpr".$i;
    $secstep = "secstep".$i;
    $secpr = "secpr".$i;
    $tristep = "tristep".$i;
    $tripr = "tripr".$i;
    
    mysqli_query($link, "UPDATE trpstep SET
			    `drugid` = '$_POST[$drugid]',
			    `firstone` = '$_POST[$firstone]',
			    `init_pr` = '$_POST[$initpr]',
			    `secstep` = '$_POST[$secstep]',
			    `sec_pr` = '$_POST[$secpr]',
			    `tristep` = '$_POST[$tristep]',
			    `tri_pr` = '$_POST[$tripr]'
			    WHERE id='$i'
			    ") or die(mysqli_error($link));
  }
  
    $i = $_SESSION['rowmax'];
    $drugid = "drugid".$i;
    $firstone = "firstone".$i;
    $initpr = "initpr".$i;
    $secstep = "secstep".$i;
    $secpr = "secpr".$i;
    $tristep = "tristep".$i;
    $tripr = "tripr".$i;
  if(!empty($_POST[$drugid]))
  {
    // assign insertion pattern
    $sql_insert = "INSERT into `trpstep`
			    (`drugid`,`firstone`,`init_pr`,`secstep`,`sec_pr`,`tristep`,`tri_pr`)
			VALUES
			    ('$_POST[$drugid]','$_POST[$firstone]','$_POST[$initpr]','$_POST[$secstep]','$_POST[$secpr]','$_POST[$tristep]','$_POST[$tripr]')";

    // Now insert Patient to "patient_id" table
    mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
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
<form method="post" action="tr_price_step.php" name="regForm" id="regForm">
<h3 class="titlehdr">การคิดราคา หัตถการ   <input type=submit name=set value=Set>  <input type=submit name=set value=ReSet></h3>

<table style="text-align: center;" border="1" cellpadding="2" cellspacing="2">
<tbody><tr><th width="10%">DrugId</th><th>Name</th><th>InitVol</th><th>Init_Price</th><th>2 Step</th><th>2 Price Step</th><th>3 Step</th><th>3 Price Step</th></tr>
<?php 
$drug = mysqli_query($link, "select * from trpstep");
$i=1;
while($dcs = mysqli_fetch_array($drug))
{
 echo "<tr><th width=10%>";
 echo "<input size=6 type=text name='drugid".$dcs['id']."' value='".$dcs['drugid']."'>";
// echo $dsc['drugdrugid'];
 echo "</th><th>";
 $filter = mysqli_query($link, "select * from drug_id WHERE id=$dcs[drugid]");
 while($row = mysqli_fetch_array($filter))
 {
 echo $row['dname'];
 }
 echo "</th><th>";
 echo "<input type=number class=typenumber min=1 step=1 name='firstone".$dcs['id']."' value='".$dcs['firstone']."'>";
// echo $dsc['firstone'];
 echo "</th><th>";
 echo "<input type=number class=typenumber min=0 step=1 name='initpr".$dcs['id']."' value='".$dcs['init_pr']."'>";
// echo $dsc['druginitpr'];
 echo "</th><th>";
 echo "<input type=number class=typenumber min=0 step=1 name='secstep".$dcs['id']."' value='".$dcs['secstep']."'>";
// echo $dsc['secstep'];
 echo "</th><th>";
 echo "<input type=number class=typenumber min=0 step=1 name='secpr".$dcs['id']."' value='".$dcs['sec_pr']."'>";
// echo $dsc['secpr'];
 echo "</th><th>";
 echo "<input type=number class=typenumber min=0 step=1 name='tristep".$dcs['id']."' value='".$dcs['tristep']."'>";
// echo $dsc['secstep'];
 echo "</th><th>";
 echo "<input type=number class=typenumber min=0 step=1 name='tripr".$dcs['id']."' value='".$dcs['tri_pr']."'>";
// echo $dsc['secpr'];
 echo "</th></tr>";
$i=$dcs['id']+1;
}
$_SESSION['rowmax'] = $i;

?>
<tr><th width="10%"><input size=6 type='text' name='drugid<?php echo $i;?>' ></th><th>Name</th><th><input type='number' class="typenumber" min=1 step=1 name='firstone<?php echo $i;?>'></th>
<th><input type='number' class='typenumber ' min=0 step=1 name='initpr<?php echo $i;?>'></th><th><input type='number' class='typenumber ' min=0 step=1 name='secstep<?php echo $i;?>'></th>
<th><input type='number' class='typenumber ' min=0 step=1 name='secpr<?php echo $i;?>'></th><th><input type='number' class='typenumber ' min=0 step=1 name='tristep<?php echo $i;?>'></th>
<th><input type='number' class='typenumber ' min=0 step=1 name='tripr<?php echo $i;?>'></th></tr>
</tbody>
</table>
</form>
<!--menu end-->
  </td></tr>
</table>
<!--end menu-->
</body></html>
