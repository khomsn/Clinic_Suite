<?php 
include '../../config/dbc.php';
page_protect();

$sql = "
CREATE TABLE IF NOT EXISTS `stcpdrug` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";
mysqli_query($link, $sql);

if($_POST['save']=="บันทึก")
{
    //if()
    {
    $sql_insert = "INSERT into `stcpdrug` (name) value ('$_POST[tin]')";
    mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
    }
}

$title = "::Staff co-pay drug list::";
include '../../main/header.php';
include '../../main/bodyheader.php';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td colspan="3">&nbsp;</td></tr>
  <tr><td width="160" valign="top"><div class="pos_l_fix">
		<?php 
			/*********************** MYACCOUNT MENU ****************************
			This code shows my account menu only to logged in users. 
			Copy this code till END and place it in a new html or php where
			you want to show myaccount options. This is only visible to logged in users
			*******************************************************************/
			if (isset($_SESSION['user_id']))
			{
				include '../../login/menu_admam.php';
			} 
		/*******************************END**************************/
		?></div>
		</td>
		<td width="10" valign="top"><p>&nbsp;</p></td>
		<td>
			<h3 class="titlehdr">รายการยาที่ Staff ต้องร่วมจ่าย เท่าราคาทุน</h3>
				<table style="text-align: center; margin-left: auto; margin-right: auto; width: 80%; background-color: rgb(255, 255, 204);" border="1" cellpadding="2" cellspacing="2">
				<form method="post" action="stcpdrug.php" name="regis" id="regForm">
					<tbody>
						<tr>
							<td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">
							<table style="text-align: center; margin-left: auto; margin-right: auto; background-color: rgb(255, 255, 204);" border="1" cellpadding="2" cellspacing="2">
                                <tr><td>ชนิดยาและผลิตภัณฑ์</td></tr>
                                <tr><td><input type=text name=tin></td></tr>
                                <?php
                                $std=mysqli_query($link, "select * from stcpdrug");
                                while($row = mysqli_fetch_array($std))
                                {
                                    echo "<tr><td>";
                                    echo $row['name'];
                                    echo "</td></tr>";
                                }
                                ?>
							</table>
							</td>
						</tr>
						<tr>
							<td style="width: 100%; vertical-align: top; background-color: rgb(255, 255, 204);">
								<input type="submit" name="save" value="บันทึก">
							</td>
						</tr>
					</tbody>
				</form>	
				</table>
		</td>
	</tr>
</table>
</body></html>
