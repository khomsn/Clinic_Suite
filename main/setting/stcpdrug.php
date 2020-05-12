<?php 
include '../../config/dbc.php';
page_protect();
$maxid = mysqli_fetch_array(mysqli_query($link, "select MAX(id) from drug_id "));
$sql = "
CREATE TABLE IF NOT EXISTS `stcpdrug` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";
mysqli_query($link, $sql);

$sql_select =  mysqli_query($link, "SELECT * FROM `initdb` WHERE `refname`='stcpdrug' ORDER BY `id`" );
$tableversion = mysqli_num_rows($sql_select);
if(!$tableversion)
{
    $sql  = "INSERT INTO `initdb` (`refname`, `version`) VALUES (\'stcpdrug\', \'1\')";
    mysqli_query($link, $sql);
}

if($_POST['save']=="บันทึก")
{
    //if()
    {
    $sql_insert = "INSERT into `stcpdrug` (name) value ('$_POST[tin]')";
    mysqli_query($link, $sql_insert);
    }
    for ($i=1;$i<=$maxid[0];$i++)
    {
        if($_POST[$i]) $stcp = 1;
        else $stcp = 0;
        mysqli_query($link, "UPDATE `drug_id` SET  `stcp` = '$stcp'  WHERE `id` ='$i' LIMIT 1 ;" );
    }
}

$title = "::Staff co-pay drug list::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
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
                            <td>
                            <?php
                            echo "<table class='TFtable' border='1' style='text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);'>";
                                $std=mysqli_query($link, "select * from drug_id ORDER BY `dgname` ASC");
                                 echo "<th>ชื่อ</th><th>ชื่อสามัญ</th><th>ขนาด</th><th>CP</th>";
                                while($row = mysqli_fetch_array($std))
                                {
                                 echo "<tr><td>";
                                 echo $row['dname'];
                                 echo "</td><td>";
                                 echo $row['dgname'];
                                 echo "</td><td>";
                                 echo $row['size'];
                                 echo "</td><td>";
                                 echo "<input type='checkbox' name='".$row['id']."' value='1'"; if($row['stcp']) echo " checked>"; else echo " >";
                                 echo "</td></tr>";
                                }
                            echo "</table>";
                            ?>
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
