<?php 
include '../../config/dbc.php';
page_protect();
include '../../libs/dateandtimezone.php';
include '../../libs/progdate.php';

$filter = mysqli_query($link, "select * from drug_id ");		
while ($row = mysqli_fetch_array($filter))
{
    if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
}
$filter = mysqli_query($link, "select * from drug_id  WHERE seti != 1 ORDER BY `dgname` ASC");

$title = "::รายงานสถิติการใช้ยา::";
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
            <form method="post" action="drugusedstat.php" name="regForm" id="regForm">
			<h3 class="titlehdr">รายงานสถิติการใข้ยาในช่วงปี พ.ศ. <?php echo $bsy; //date("Y")+543;
	if ($_SESSION['user_accode']%2==0  and $_SESSION['user_level']>1)
	  {
	    {
	      if($sy>$minyear) echo "&nbsp;<input type='submit' name='todoy' value = '<<'>";
	      echo "&nbsp;<input type='submit' name='todoy' value = '@'>&nbsp;";
	      if ($sy < date("Y"))
	      {
	      echo "<input type='submit' name='todoy' value = '>>'>";
	      }
	    }
	  }?> </h3>
				<table style="text-align: center;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="vertical-align: middle; ">
						<div style="text-align: center;">
						<?php	
								echo "<table class='TFtable' border='1' style='text-align: left; margin-left: auto; margin-right: auto;'>";
								echo "<tr><th>id</th>";
								echo "<th>ชื่อ</th><th>ชื่อสามัญ</th><th>ขนาด</th><th>มค.</th><th>กพ.</th><th>มีค.</th><th>เมย.</th><th>พค.</th><th>มิย.</th><th>กค.</th><th>สค.</th><th>กย.</th><th>ตค.</th><th>พย.</th><th>ธค.</th></tr>";
								while($row = mysqli_fetch_array($filter))
								 {
										// Print out the contents of each row into a table
										echo "<tr><th>";
										echo $drugid = $row['id'];
										echo "</th><td>"; 
										echo $row['dname'];
										echo "</td><td>"; 
										echo $row['dgname'];
										echo "</td><td >"; 
										echo $row['size'];
										echo "</td>";
										for ($i = 1;$i<=12;$i++)
										{
										echo "<th style='text-align: right;' >";
										$getdupmp = mysqli_query($link, "SELECT vol FROM dupm WHERE  drugid='$drugid' AND rmonth ='$i' AND ryear ='$sy' ORDER BY `id` DESC");
                                            while($gs = mysqli_fetch_array($getdupmp))
                                            {
                                                $allsum = $allsum + $gs['vol'];
                                            }
                                        echo $allsum;
                                        $allsum = 0;//reset value for new drugid
										echo "</th>";
										}
										echo "</tr>";
								} 
								echo "</table>";
						?>
							<br>
							</div>
						</td>
					</tr>
				</tbody>
				</table>
			</form>
<!--menu end-->
		</td>
<td width=130px></td></tr>
</table>
<!--end menu-->
</body></html>
