<?php 
include '../../config/dbc.php';
page_protect();

$filter = mysqli_query($link, "select * from rawmat ORDER BY `rawname` ASC");

$title = "::รายงาน ราคาซื้อ Rawmat คงเหลือ::";
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
			<h3 class="titlehdr">ต้นทุน Rawmat คงคลังคงเหลือ</h3>
            <?php	
                    echo "<table class='TFtable' border='1' style='text-align: left; margin-left: auto; margin-right: auto;'>";
                    echo "<tr><th>id</th>";
                    echo "<th>ชื่อ</th><th>ชื่อสามัญ</th><th>ขนาด</th><th>ต้นทุนยาคงเหลือ</th></tr>";
                    while($row = mysqli_fetch_array($filter))
                        {
                            // Print out the contents of each row into a table
                            echo "<tr><td>";
                            echo $row['id'];
                            echo "</td><td>"; 
                            echo $row['rawcode'];
                            echo "</td><td>"; 
                            echo $row['rawname'];
                            echo "</td><td >"; 
                            echo $row['size'];
                            echo "</td><td style='text-align: right;' >"; 
                            $drugtable = "rawmat_".$row['id'];
                            $priceleft=0;
                            $getprice = mysqli_query($link, "select * from $drugtable ");
                            while($row2 = mysqli_fetch_array($getprice))
                            {
                            $priceleft = $priceleft+$row2['price']/$row2['volume']*($row2['volume']-$row2['customer']);
                            }
                            echo number_format(($priceleft),2);
                            echo "</td></tr>";
                    } 
                    echo "</table>";
            ?>
		</td>
<td width=130px></td></tr>
</table>
</body></html>
