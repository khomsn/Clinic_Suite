<?php 
include '../../config/dbc.php';
page_protect();

if($_POST['register'] == 'รายงาน บจ 8') 
{ 

// pass drug-id to other page
$_SESSION['drugid'] = $_POST['drugid'];
// go on to other step
//header("Location: updatedrugid.php");   
?>
 <script>
 window.open(" ../../docform/bj8.php");
 </script>
 <?php 
}
$title = "::รายการยาและผลิตภัณฑ์::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
include '../../main/bodyheader.php';

?>
<div class="pos_l_fix">
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
?>
</div>
<div align="center">
<h3 class="titlehdr">รายการ ยาพิเศษต้องรายงานการใช้</h3>
<form method="post" action="drugtracklist8.php" name="regForm" id="regForm">
<table style="height: 413px;" border="0" cellpadding="2" cellspacing="2">
<tbody>
<tr><td>
            <?php	
                $dtype = mysqli_query($link, "SELECT * FROM drug_id WHERE track = '1' ");
                    echo "<table class='TFtable' border='1' style='text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);'>";
                    echo "<tr> <th>เลือก</th><th>ชื่อ</th><th>ชื่อสามัญ</th><th>ขนาด</th><th>จำนวน</th></tr>";
                    while($row = mysqli_fetch_array($dtype))
                        {
                            // Print out the contents of each row into a table
                            echo "<tr><th>";
                            echo "<input type='radio' name='drugid' value='".$row['id']."' />";
                            echo "</th><th width=150>"; 
                            echo $row['dname'];
                            echo "</th><th width=150>"; 
                            echo $row['dgname'];
                            echo "</th><th width=50>"; 
                            echo $row['size'];
                            echo "</th><th width=50>"; 
                            echo $row['volume'];
                            echo "</th></tr>";
                        } 
                    echo "</table>";
            ?>
</td></tr>
<tr><td><div style="text-align: center;"><input name="register" value="รายงาน บจ 8" type="submit"></div></td></tr>
</tbody>
</table>
</form>
</body></html>
