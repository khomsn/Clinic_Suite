<?php 
include '../../config/dbc.php';
page_protect();

if($_POST['reset']=="Reset")
{
  		mysqli_query($link, "UPDATE drug_id SET
			`volreserve` = '0'
			") or die(mysqli_error($link));
}
$title = "::ห้องยา::";
include '../../main/header.php';
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
			<h3 class="titlehdr">Reset Reserve Volume of Drugs to 0</h3>
			<form method="post" action="drrsrvreset.php" name="regForm" id="regForm">
			  <input type="submit" name=reset value="Reset">
			</form>
		</td><td width="160"></td>
	</tr>
</table>
</body></html>
