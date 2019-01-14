<?php 
include '../../config/dbc.php';
page_protect();

if($_POST['register'] == 'ดูข้อมูล')
{ 

// pass drug-id to other page
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
				include 'rawmatmenu.php';
			} 
		/*******************************END**************************/
		?></div>
		</td><td>
			<h3 class="titlehdr">รายการ RawMat</h3>
			<form method="post" action="rawmatlist.php" name="regForm" id="regForm">
				<table style="text-align: center;" border="0" cellpadding="2" cellspacing="2">
					<tr><td>
						<?php
                            echo "<table border='1'  class='TFtable'>";
                            echo "<tr> <th>เลือก</th><th>Code</th> <th>ชื่อ</th><th>ขนาด</th><th>unit</th><th>Volume</th><th>Type</th><th>Location</th></tr>";
                            while($row = mysqli_fetch_array($filter))
                                {
                                    // Print out the contents of each row into a table
                                    echo "<tr><td>";
                                    if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0)
                                    {
                                    echo "<input type='radio' name='rawmatid' value='".$row['id']."' />";
                                    echo $row['id'];
                                    }
                                    echo "</td><td>"; 
                                    echo $row['rawcode'];
                                    echo "</td><td>"; 
                                    echo $row['rawname'];
                                    echo "</td><td >"; 
                                    echo $row['size'];
                                    echo "</td><td>"; 
                                    echo $row['sunit'];
                                    echo "</td><td>"; 
                                    echo $row['volume'];
                                    echo "</td><td>"; 
                                    echo $row['rmtype'];
                                    echo "</td><td>"; 
                                    echo $row['location'];
                                    echo "</td></tr>";
                                } 
                            echo "</table>";
						?>
					</td></tr>
					<tr><td>
						<div style="text-align: center;"><?php if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0){?><input name="register" value="ดูข้อมูล" type="submit"><?php }?></div>
					</td></tr>
				</table>
			</form>
		</td><td width="160"></td>
	</tr>
</table>
</body></html>
