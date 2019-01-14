<?php 
include '../../config/dbc.php';
page_protect();

if($_POST['register'] == 'ดูข้อมูล') 
{ 

// pass drug-id to other page
$_SESSION['drugid'] = $_POST['drugid'];
// go on to other step
header("Location: ../pharma/updatedrugid.php");  

} 

$filter = mysqli_query($link, "select * from drug_id ");
while ($row = mysqli_fetch_array($filter))
{
    if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
}	
$filter = mysqli_query($link, "select * from drug_id  WHERE seti != 1 ORDER BY `dgname` ASC");

$title = "::รายงาน ราคาซื้อ ยาและผลิตภัณฑ์::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
include '../../main/bodyheader.php';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="160" valign="top"><div class="pos_l_fix">
		<?php 
			if (isset($_SESSION['user_id']))
			{
				include 'reportmenu.php';
			} 
		?></div>
		</td><td><h3 class="titlehdr">รายการ ราคาต้นทุนต่อหน่วย ของ ยา และ ผลิตภัณฑ์</h3>
			<form method="post" action="druglistbuyprice.php" name="regForm" id="regForm">
				<table style="text-align: center;" border="0" cellpadding="2" cellspacing="2">
					<tr><td style="vertical-align: middle; ">
						<div style="text-align: center;">
						<?php	
								echo "<table class='TFtable' border='1' style='text-align: left; margin-left: auto; margin-right: auto;'>";
								echo "<tr><th>เลือก</th>";
								if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0)
								{
								echo "<th>id</th>";
								}
								echo "<th>ชื่อ</th><th>ชื่อสามัญ</th><th>ขนาด</th><th>ราคาซื้อ</th></tr>";
								while($row = mysqli_fetch_array($filter))
								 {
										// Print out the contents of each row into a table
										echo "<tr><th>";
										if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0)
										{
										echo "<input type='radio' name='drugid' value='".$row['id']."' />";
										echo "</th><th>";
										echo $row['id'];
										}
										echo "</th><th>"; 
										echo $row['dname'];
										echo "</th><th>"; 
										echo $row['dgname'];
										echo "</th><th >"; 
										echo $row['size'];
										echo "</th><th style='text-align: right;' >"; 
										$drugtable = "drug_".$row['id'];
										$getsup = mysqli_query($link, "select distinct supplier from $drugtable where supplier!='$_SESSION[clinic]' AND price!='0'");
										$sp=0;
										while($gs = mysqli_fetch_array($getsup))
										{
                                            $sup[$sp]=$gs['supplier'];
                                            $sp=$sp+1;
										}
										
										for($n=0;$n<$sp;$n++)
										{
										$supplier=$sup[$n];
										
										$gr = mysqli_fetch_array(mysqli_query($link, "select MAX(id) from $drugtable WHERE supplier='$supplier' AND price!='0'"));
										$rowid = $gr[0];
										
										$gp = mysqli_query($link, "select * from $drugtable WHERE id = $rowid");
										while($row2 = mysqli_fetch_array($gp))
										{
										 echo "[".$row2['supplier'].":".number_format(($row2['price']/$row2['volume']),2)."]";
										}
										}
										echo "</th></tr>";
								} 
								echo "</table>";
						?><br></div>
				</td></tr>
                <tr><td><div style="text-align: center;"><?php if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0){?><input name="register" value="ดูข้อมูล" type="submit"><?php }?></div>
				</td></tr>
				</table>
			</form>
</td><td width=130px></td></tr>
</table>
</body></html>
