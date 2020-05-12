<?php 
include '../../config/dbc.php';
page_protect();

if($_POST['set'] == 'ReSet')
{
      mysqli_query($link, "TRUNCATE TABLE trpstep");
}
if($_POST['ลบ'])
{
      mysqli_query($link, "DELETE FROM `trpstep` WHERE `id` = '$_POST[ลบ]'");
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
			    ");
  }
  
    $i = $_SESSION['rowmax'];
    $drugid = $_POST['drugid'.$i];
    $firstone = $_POST['firstone'.$i];
    $initpr = $_POST['initpr'.$i];
    $secstep = $_POST['secstep'.$i];
    $secpr = $_POST['secpr'.$i];
    $tristep = $_POST['tristep'.$i];
    $tripr = $_POST['tripr'.$i];
    
  if(!empty($drugid))
  {
    // assign insertion pattern
    $sql_insert = "INSERT into `trpstep`
			    (`drugid`,`firstone`,`init_pr`,`secstep`,`sec_pr`,`tristep`,`tri_pr`)
			VALUES
			    ('$drugid','$firstone','$initpr','$secstep','$secpr','$tristep','$tripr')";
    mysqli_query($link, $sql_insert);
  }
  unset($_SESSION['rowmax']);
}
$title = "::ห้องยา::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../main/bodyheader.php';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="160" valign="top"><div class="pos_l_fix">
		<?php 
			if (isset($_SESSION['user_id']))
			{
				include 'drugmenu.php';
			} 
		?></div>
		</td><td>
<form method="post" action="tr_price_step.php" name="regForm" id="regForm">
<h3 class="titlehdr">การคิดราคา หัตถการ   <input type=submit name=set value=Set>  <input type=submit name=set value=ReSet></h3>
<table style="text-align: center;" border="1" cellpadding="2" cellspacing="2" class='TFtable'>
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
 echo "</th><th>";
 echo "<button name='ลบ' type='submit' value='".$dcs['id']."'>ลบ</button>";
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
