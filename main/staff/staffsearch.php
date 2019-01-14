<?php 
include '../../config/dbc.php';
page_protect();

if($_SESSION['user_accode']%13 != 0)
// go on to other step
header("Location: staff.php");  

if($_POST['found'] == 'แก้ไขข้อมูล')
{
	$_SESSION['Staff_id'] = $_POST['ptid'];
	// go on to other step
	header("Location: staffupdate.php");  
}
$title = "::ค้นหารายชื่อ Staff::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../libs/autoname.php';
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
			include '../../login/menu.php';
			} 
		/*******************************END**************************/
		?></div>
		</td><td>
			<?php
			if($_POST['search'] =='ค้นหา')
			{
			?>
			<div style="text-align: center;"><a HREF="staffsearch.php" ><input value="ค้นหาใหม่" type="submit"></a></div>
			<?php
			}
			?>
			<h3 class="titlehdr">ค้นหารายชื่อ</h3>
			<form action="staffsearch.php" method="post" name="searchForm" id="searchForm">
			<?php
			if($_POST['search'] =='')
			{
			?><div style="text-align: center;">
                <table width="100%" border="1" cellspacing="10" cellpadding="15"  class="TFtable">
                <tr><td valign="top" style="text-align: center;">
                        เลขพนักงาน:<input maxlength="5" size="7" name="ptid">&nbsp;
                        ชื่อ:<input maxlength="15" size="15" tabindex="1" name="F_Name" id="fname">&nbsp;
                        นามสกุล:<input maxlength="20" size="20" tabindex="2" name="L_Name" id="lname">
                </td></tr>
                <tr><td style="text-align: center;">
                        เลขที่บัตรประชาชน<input maxlength="13" size="30" tabindex="2" name="id" id="cid">
                </td></tr>
                <tr><td style="text-align: center;">
                        โทรศัพท์บ้าน:<input maxlength="12" size="12" tabindex="3" name="htel" value="02xxx" id="htel">
                        โทรศัพท์มือถือ <input maxlength="12" size="12" tabindex="4" name="mtel" value="08xxx" id="mtel">
                        <br>
                        <br>
                        <br>
                        <input tabindex="5" value="ค้นหา" name="search" type="submit">
                </td></tr>
                </table>
            </div>
			<?php } ?>
			</form>
			<form action="staffsearch.php" method="post" name="submitsearchForm" id="submitsearchForm">
			<div style="text-align: center;">
			<?php
				if($_POST['search'] == 'ค้นหา')  
				{
					$result = mysqli_query($link, "SELECT * FROM staff WHERE F_Name='$_POST[F_Name]' or L_Name='$_POST[L_Name]' or h_tel='$_POST[htel]' or mobile='$_POST[mtel]' OR ID='$_POST[ptid]' or ctz_id='$_POST[id]' and ctz_id!=0 ");
                    echo "<table border='1'  class='TFtable'>";
                    echo "<tr><th>เลือก</th><th>เลขทะเบียน</th><th>ชื่อ</th><th>นามสกุล</th></tr>";
                    // keeps getting the next row until there are no more to get
                    while($row = mysqli_fetch_array($result))
                        {
                            // Print out the contents of each row into a table
                            echo "<tr><td>";
                            echo "<input type=\"radio\" name=\"ptid\" value=\"".$row['ID']."\" />";
                            echo "</td><td>"; 
                            echo $row['ID'];
                            echo "</td><td width=150>"; 
                            echo $row['F_Name'];
                            echo "</td><td width=150>"; 
                            echo $row['L_Name'];
                            echo "</th></tr>";
                    } 
                    echo "</table>";
                    echo "<br><br><input name='found' value='แก้ไขข้อมูล' type='submit'><br>";
                }
            ?>
            </div>
            </form>
		</td><td width="160"><div class="pos_r_fix"><?php include 'stmenurt.php';?></div></td></tr>
</table>
</body>
</html>
