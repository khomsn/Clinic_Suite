<?php 
include '../../config/dbc.php';
page_protect();

if($_POST['register'] == 'ดูข้อมูล') 
{ 

    // pass drug-id to other page
    $_SESSION['drugid'] = $_POST['drugid'];
    // go on to other step
    header("Location: ../../main/pharma/updatedrugset.php");  

}

$title = "::ห้องยา::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../main/bodyheader.php';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="170" valign="top"><div class="pos_l_fix">
		<?php 
			if (isset($_SESSION['user_id']))
			{
				include 'drugmenu.php';
			} 
		?></div>
		</td><td>
			<h3 class="titlehdr">รายการ ชุดยา</h3>
			<form method="post" action="drugset.php" name="regForm" id="regForm">
						<div style="text-align: center;">
						<?php	
							$dtype = mysqli_query($link, "SELECT * FROM drug_id WHERE `seti` = '1' ");
							if($dtype != 0)
							{
								echo "<table border='1' style='text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);' class='TFtable'>";
								echo "<tr> <th>เลือก</th><th>ชื่อ</th> <th>ชื่อสามัญ</th><th>ขนาด</th><th>จำนวน</th><th>ราคา</th></tr>";
								while($row = mysqli_fetch_array($dtype))
								 {
										// Print out the contents of each row into a table
										echo "<tr><th>";
										echo "<input type='radio' name='drugid' value='".$row['id']."'/>";
										echo "</th><th width=150>"; 
										echo $row['dname'];
										echo "</th><th width=150>"; 
										echo $row['dgname'];
										echo "</th><th width=50>"; 
										echo $row['size'];
										echo "</th><th width=50>"; 
										echo $row['volume'];
										echo "</th><th width=50>"; 
										echo $row['sellprice'];
										echo "</th></tr>";
								} 
								echo "</table>";
							}	
						?></div>
<div style="text-align: center;"><input name="register" value="ดูข้อมูล" type="submit"></div>
			</form>
		</td>
		<td width="160"></td>
	</tr>
</table>
</body></html>
