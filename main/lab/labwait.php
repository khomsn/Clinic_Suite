<?php 
include '../../config/dbc.php';

page_protect();
if($_POST['Rec'])
{
    $str_arr = explode ("@", $_POST['Rec']); 
    $_SESSION['ptid'] = $str_arr[0];
    $_SESSION['rid'] = $str_arr[1];
    $_SESSION['ltb'] = $_POST['tb'.$_SESSION['ptid'].$_SESSION['rid']];
    header("Location: laballrspage.php");
}

$title = "::Laboratory List::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../main/bodyheader.php';

?>
<table width="100%" border="0">
<tr><td width=155px>
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
    </td><td><h3 class="titlehdr">รายการ Lab รอผล</h3>
    <form method="post" action="labwait.php" name="regForm" id="regForm">		 
		 <table border="1" width =100% class='TFtable'>
		 <th>ID</th><th>Name</th><th>Request Date</th>
		 <?php
		 $ptin = mysqli_query($link, "select * from labwait ORDER BY dtr ASC ");
		 while ($rows=mysqli_fetch_array($ptin))
		 {
		    $ptid = $rows['ptid'];
		    $ptrid = $rows['rid'];
		    $pttable = $rows['tablename'];
		    $date = $rows['dtr'];
		    $ptrq = mysqli_query($linkopd, "select * from patient_id where id='$ptid' ");
		    while($rows1 = mysqli_fetch_array($ptrq))
		    {
		      $ptname = $rows1['prefix']." ".$rows1['fname']." ".$rows1['lname'];
		    }
		 
		     echo "<tr><td style='text-align:center;'>";
		     echo "<input type='submit' name='Rec' value='".$rows['ptid']."@".$rows['rid']."'>";
		     echo "<input type='hidden' name='tb".$rows['ptid'].$rows['rid']."' value='".$rows['tablename']."'>";
		     echo "</td><td>";
		     echo $ptname;
		     echo "</td><td>";
		     echo $date;
		     echo "</td></tr>";
		 }
		 ?>
		 </table></form>
   </td><td width=130px><div class="pos_r_fix"><?php include 'labrmenu.php';?></div>
</td></tr>
</table>
</body></html>
