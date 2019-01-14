<?php 
include '../../config/dbc.php';
page_protect();

if($_POST['register'] == 'ดูข้อมูล') 
{ 

// pass rawmat-id to other page
$_SESSION['rawmatid'] = $_POST['rawmatid'];
// go on to other step
header("Location: rawmatupdate.php");  

} 

$filter = mysqli_query($link, "select * from rawmat ");		
	while ($row = mysqli_fetch_array($filter))
	{
		if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
	}	
$filter = mysqli_query($link, "select * from rawmat  ORDER BY `rmtype` ASC ,`rawcode` ASC ,`rawname` ASC");
$title = "::ห้องคลังวัตถุดิบ::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
include '../../main/bodyheader.php';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="160" valign="top"><div class="pos_l_fix">
		<?php 
			/*********************** MYACCOUNT MENU ****************************
			This code shows my account menu only to logged in users. 
			Copy this code till END and place it in a new html or php where
			you want to show myaccount options. This is only visible to logged in users
			*******************************************************************/
			if (isset($_SESSION['user_id']))
			{
				include 'reportmenu.php';
			} 
		/*******************************END**************************/
		?></div>
		</td><td>
<!--menu-->
			<h3 class="titlehdr">รายการ RawMat</h3>
			<form method="post" action="rawmatlist.php" name="regForm" id="regForm">
				<table style="text-align: center;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="width: 18px;"></td>
						<td style="vertical-align: middle; ">
						<div style="text-align: center;">
						<?php	
								echo "<table class='TFtable' border='1' style='text-align: left; margin-left: auto; margin-right: auto;'>";
								echo "<tr> <th>เลือก</th><th>Code</th> <th>ชื่อ</th><th>ขนาด</th><th>unit</th><th>Volume</th><th>Type</th><th>ราคาซื้อ</th></tr>";
								while($row = mysqli_fetch_array($filter))
								 {
										// Print out the contents of each row into a table
										echo "<tr><th>";
										if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0)
										{
										echo "<input type='radio' name='rawmatid' value='".$row['id']."' />";
										echo $row['id'];
										}
										echo "</th><th>"; 
										echo $row['rawcode'];
										echo "</th><th>"; 
										echo $row['rawname'];
										echo "</th><th >"; 
										echo $row['size'];
										echo "</th><th>"; 
										echo $row['sunit'];
										echo "</th><th>"; 
										echo $row['volume'];
								echo "</th><th>"; 
								echo $row['rmtype'];
										echo "</th><th style='text-align: right;' >"; 
										$rawmattable = "rawmat_".$row['id'];
										
										$getsup = mysqli_query($link, "select distinct supplier from $rawmattable where supplier!='$_SESSION[clinic]' AND price!='0'");
										$sp=0;
										while($gs = mysqli_fetch_array($getsup))
										{
                                            $sup[$sp]=$gs['supplier'];
                                            $sp=$sp+1;
										}
										
										for($n=0;$n<$sp;$n++)
										{
										$supplier=$sup[$n];
										
										$gr = mysqli_fetch_array(mysqli_query($link, "select MAX(id) from $rawmattable WHERE supplier='$supplier' AND price!='0'"));
										$rowid = $gr[0];
										
										$gp = mysqli_query($link, "select * from $rawmattable WHERE id = $rowid");
										while($row2 = mysqli_fetch_array($gp))
										{
										 echo "[".$row2['supplier'].":".number_format(($row2['price']/$row2['volume']),2)."]";
										}
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
					<tr>
					<td>&nbsp;</td>
					<td>
						<div style="text-align: center;"><?php if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0){?><input name="register" value="ดูข้อมูล" type="submit"><?php }?></div>
					</td>
					</tr>
				</tbody>
				</table>
				<br>
			</form>
<!--menu end-->
		</td>
		<td width=130px></td>
	</tr>
</table>
<!--end menu-->
</body></html>
