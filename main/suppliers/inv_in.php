<?php 
include '../../config/dbc.php';
page_protect();

$filter = mysqli_query($link, "select * from drug_id ");		
	while ($row = mysqli_fetch_array($filter))
	{
		if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
	}	
$filter = mysqli_query($link, "select * from drug_id  WHERE RawMat = 1 ORDER BY `dgname` ASC");

$title = "::นำเข้าวัตถุดิบ::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
include '../../libs/popup.php';
include '../../main/bodyheader.php';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="200" valign="top"><div class="pos_l_fix">
		<?php 
			/*********************** MYACCOUNT MENU ****************************
			This code shows my account menu only to logged in users. 
			Copy this code till END and place it in a new html or php where
			you want to show myaccount options. This is only visible to logged in users
			*******************************************************************/
			if (isset($_SESSION['user_id']))
			{
				include 'spmenu.php';
			} 
		/*******************************END**************************/
		?></div>
		</td><td>
<!--menu-->
			<h3 class="titlehdr">นำเข้าวัตถุดิบ</h3>
				<table style="text-align: left; width: 703px; height: 413px;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr><td style="width: 646px; vertical-align: middle;">
						<div style="text-align: center;">	
												<!--List Patient wait for doctor-->
						<?php
						echo "<table border='1' style='text-align: center; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76)' >";
						echo "<tr> <th>ชื่อ</th> <th>ชื่อสามัญ</th> <th>ขนาด</th></tr>";
						// keeps getting the next row until there are no more to get
						while($row = mysqli_fetch_array($filter))
						 {
								// Print out the contents of each row into a table
								echo "<tr><th width=150>"; 
						?>
							<?php
								$msg = urlencode($row['id']);
							?>
								<a onClick="return popup(this, 'notes','800','450','no')" HREF="stockin.php
								<?php echo "?msg=".$msg; ?>"><?php echo $row['dname'];?></a>
							
						<?php 
								echo "</th><th width=150>"; 
								echo $row['dgname'];
								echo "</th><th width=150>"; 
								echo $row['size'];
								echo "</th></tr>";
						} 
						echo "</table>";
						//////////////////////////
						?>						

							<br>
						</div>
						</td>
					</tr>
				</tbody>
				</table>
				<br>
		</td>
		<td style="width:260px;vertical-align: top;"></td>
	</tr>
</table>
<!--end menu-->
</body></html>
