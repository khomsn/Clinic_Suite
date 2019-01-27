<?php 

include '../../config/dbc.php';
page_protect();
$pdir = "../".AVATAR_PATH;

if($_POST['del'])
{
    $ptid=$_POST['del'];
    $sql="DROP TABLE tmp_$ptid";
    mysqli_query($link, $sql);
    $sql="DELETE FROM `pt_to_scr` WHERE `pt_to_scr`.`ID` = $ptid";
    mysqli_query($link, $sql);
}

$title = "::ผู้ป่วยรอตรวจร่างกายเบื้องต้น::";
include '../../main/header.php';
//echo "<meta http-equiv=\"refresh\" content=\"10\">";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/popuponpage.css\"/>";
include '../../libs/popup.php';
include '../../libs/popuponpage.php';
include '../../main/bodyheader.php';

?>
<script type="text/javascript" language="javascript">
$(document).ready(function() { /// Wait till page is loaded
setInterval(timingLoad, 3000);
function timingLoad() {
$('#main').load('updateptsrc.php #main', function() {
/// can add another function here
});
}
}); //// End of Wait till page is loaded
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
<tr><td width="160" valign="top">
    <?php 
    if (isset($_SESSION['user_id']))
    {
        include 'clinicmenu.php';
    } 
    ?>
    </td><td valign="top">
        <form action="pt_to_scr.php" method="post" name="ptd" id="ptd">
        <h3 class="titlehdr">ผู้ป่วยรอตรวจร่างกายเบื้องต้น</h3>
        <p align="right">&nbsp; </p>
        <div id="main"><!--List Patient wait for drug-->
        <?php
        $result = mysqli_query($link, "SELECT * FROM pt_to_scr ORDER BY time ASC ");
        $n_of_row = mysqli_num_rows($result);
        echo "<table border='1' class='TFtable' style='width: auto; margin-left: auto; margin-right: auto;'>";
        echo "<tr><th>เลขทะเบียน</th><th>ยศ</th><th>ชื่อ</th><th>นามสกุล</th><th>";?>
        <div class="popup" onmouseover="myFunction()" onmouseout="myFunction()"><div class="avatar">
        <img src="<?php $avatar = $pdir."default.jpg"; echo $avatar; ?>" width="44" height="44"/></div>
        <span class="popuptext" id="myPopup">Update รูปคนไข้ คลิกที่รูป คนไข้ ได้เลยครับ!</span></div>
        <?php echo "</th><th>ยกเลิก</th></tr>";
        // keeps getting the next row until there are no more to get
        $j=1;
        while($row = mysqli_fetch_array($result))
        {
        // Print out the contents of each row into a table
        echo "<tr><th>"; 
        $msg = urlencode($row['ID']);
        echo "<a href=patdesk.php?msg=".$msg.">".$row['ID']."</a>";
        echo "</th><th>"; 
        echo "<a href=patdesk.php?msg=".$msg.">".$row['prefix']."</a>";
        echo "</th><th width=150>"; 
        echo "<a href=patdesk.php?msg=".$msg.">".$row['F_Name']."</a>";
        echo "</th><th width=150>"; 
        echo "<a href=patdesk.php?msg=".$msg.">".$row['L_Name']."</a>";
        echo "</th><th>";
        echo "<div class='avatar'>";
        $avatar = $pdir. "pt_".$row['ID'].".jpg";
        echo "<a href='updateptimage.php?msg=".$msg."' onClick=\"return popup(this, 'name' , '800' , '500' , 'yes' );\">";
        echo "<img src='";
        echo $avatar;
        echo "' width=44 height=44 />";
        echo "</a>";
        echo "</div>";
        echo "</th><th><input type=submit name=del value=$row[ID]>";
        echo "</th></tr>";
        $j+=1;
        } 
        echo "</table>";
        //////////////////////////
        ?>
        </div>
        </form>
    </td><td width=160px></td></tr>
</table>
</body>
</html>
