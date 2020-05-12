<?php 
include '../../config/dbc.php';
page_protect();
$splkup=$_POST['spname'];
$title = "::ยาและผลิตภัณฑ์::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
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
				include 'drugmenu.php';
			} 
		/*******************************END**************************/
		?></div>
		</td><td>
<!--menu--><form method="post" action="druglow.php" name="regForm" id="regForm">
			<h3 class="titlehdr">รายการ ยา และ ผลิตภัณฑ์ และ วัตถุดิบ ที่ถึงจุดสั่งซื้อ</h3>
				<table style="text-align: center;" border="0" cellpadding="2" cellspacing="2">
				<tbody>
					<tr>
						<td style="width: 18px;"></td>
						<td style="vertical-align: middle; ">
						<div style="text-align: center;">
						<?php	
							$dtype = mysqli_query($link, "SELECT * FROM drug_id WHERE volume <= min_limit ORDER BY `dgname` ASC");
								echo "<table class='TFtable' border='1' style='text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);'>";
								echo "<tr> <th>No</th><th>ชื่อ</th> <th>ชื่อสามัญ</th><th>ขนาด</th><th>จำนวน</th><th>ร้าน</th><th>จำนวนที่สั่ง</th><th>Unit</th><th>BP-S</th></tr>";
								$i=1;
								$k=1;
								while($row = mysqli_fetch_array($dtype))
								 {
                                    // Print out the contents of each row into a table
                                    echo "<tr><th>";
                                    echo $i;
                                    echo "</th><th>"; 
                                    echo $row['dname'];
                                    echo "</th><th>"; 
                                    echo $row['dgname'];
                                    echo "</th><th>"; 
                                    echo $row['size'];
                                    echo "</th><th>"; 
                                    echo $row['volume'];
                                    echo "</th><th>";
                                    $drugtable = "drug_".$row['id'];
                                    if(empty($splkup)){
                                        $spname = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM $drugtable where supplier!='$_SESSION[clinic]' ORDER BY id DESC LIMIT 1;"));
                                        echo $spname['supplier'];
                                        for($j=1;$j<=$k;$j++){
                                            if ($supname[$j]==$spname['supplier']) goto Next1;
                                        }
                                        $supname[$k] = $spname['supplier'];
                                        $k = $k+1;
                                        Next1:
                                    }
                                    else
                                    {
                                        $spname = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM $drugtable where supplier!='$_SESSION[clinic]' AND supplier='$splkup' ORDER BY id DESC LIMIT 1;"));
                                        echo $spname['supplier'];
                                    }
                                    echo "</th><th>";
                                    echo $spname['volume'];
                                    echo "</th><th>";
                                    echo $row['unit'];
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
                                    $i = $i+1;
								} 
								echo "</table>";
						?>
							<br>
							</div>
						</td>
					</tr>
				</tbody>
				</table>
<!--menu end-->
		</td>
		<td width="160"><div style="background-color:rgba(124,200,0,0.65); display:inline-block;">รายชื่อบริษัท<select tabindex="1" name="spname" id="AcNo" class="required">
		    <?php
		    for($n=0;$n<$k;$n++)
		    {
			    echo "<option value='";
			    echo $supname[$n];
			    echo "'";
			    echo ">";
			    echo $supname[$n]."</option>";
		    }
		    ?></td>
	</tr>
</table>
<!--end menu-->
</form>
</body></html>
<script type="text/javascript">

  jQuery(function() {
    jQuery('#AcNo').change(function() {
        this.form.submit();
    });
});
</script>
