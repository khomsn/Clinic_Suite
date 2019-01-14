<?php 
include '../../config/dbc.php';
page_protect();
if($_POST['Edit'] == 'Edit') 
{ 

// pass drug-id to other page
$_SESSION['labid'] = $_POST['labid'];
// go on to other step
header("Location: labedit.php");  

} 

$title = "::Laboratory List::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../main/bodyheader.php';

?>
<table width="100%" border="0">
<tr><td width=160px>
    <div class="pos_l_fix">
    <?php 
        /*********************** MYACCOUNT MENU ****************************
        This code shows my account menu only to logged in users. 
        Copy this code till END and place it in a new html or php where
        you want to show myaccount options. This is only visible to logged in users
        *******************************************************************/
        if (isset($_SESSION['user_id']))
        {
            include 'labmenu.php';
        } 
    /*******************************END**************************/
    ?>
    </div>
    </td><td><h3 class="titlehdr">รายการ Lab</h3>
        <form method="post" action="lablist.php" name="regForm" id="regForm">
		 <table border="1" width =100% class='TFtable' style="color:blue">
		 <th>Edit</th><th>No</th><th>Name</th><th>Int</th><th>Set</th><th>Spec</th><th>Price</th><th>TC1</th><th>TC2</th><th>Information</th>
		 <?php
		 $ptin = mysqli_query($link, "select * from lab ORDER BY id ASC ");
		 while ($rows=mysqli_fetch_array($ptin))
		 {
		     echo "<tr><td>";
		     echo "<input type=radio name=labid value=".$rows['id']." />";
		     echo "</td><td>";
		     $labid = $rows['id'];
		     echo $rows['id'];
		     echo "</td><td>";
		     echo $rows['L_Name'];
		     echo "</td><td>";
		     echo $rows['S_Name'];
		     echo "<input type=hidden name='S_Name".$rows['id']."' value=".$rows['S_Name'].">";
		     echo "</td><td>";
		     echo $rows['L_Set'];
		     echo "</td><td>";
		     echo $rows['L_specimen'];
		     echo "</td><td>";
		     echo $rows['price'];
		     echo "</td><td style='background-color:";
		     echo $rows['colourcode'];
		     echo ";'>";
		     echo "</td><td style='background-color:";
		     echo $rows['colourcode2'];
		     echo ";'>";
		     echo "</td><td>";
		     echo $rows['Linfo'];
		     echo "</td></tr>";
		 }
		 ?>
		 </table>
		 <p style="text-align:center;"><input type="submit" name="Edit" value="Edit"></p>
        </form>
   </td><td width=130px><div class="pos_r_fix"><?php include 'labrmenu.php';?></div>
</td></tr>
</table>
</body></html>
