<?php 

include '../../config/dbc.php';
page_protect();
$pdir = "../".AVATAR_PATH;
//
unset($_SESSION['price']);
unset($_SESSION['patcash']);

$title = "::ผู้ป่วยรอสังเกตอาการ::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" href=\"../../jscss/css/table_alt_color1.css\">";
include '../../main/bodyheader.php';
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() { /// Wait till page is loaded
setInterval(timingLoad, 3000);
function timingLoad() {
$('#main').load('updateptobs.php #main', function() {
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
    </td><td width="" valign="top">
        <form action="pt_to_doctor.php" method="post" name="ptd" id="ptd">
        <h3 class="titlehdr">ผู้ป่วยรอสังเกตอาการ</h3>
        <p align="right">&nbsp; </p>
        <div id="main"><!--List Patient wait for drug-->
        <?php
        $result = mysqli_query($link, "SELECT * FROM pt_to_obs ORDER BY time ASC");
        $n_of_row = mysqli_num_rows($result);
        echo "<table align='center' border='1' class='TFtable'>";
        echo "<tr><th>เลขทะเบียน</th><th>ยศ</th><th>ชื่อ</th><th>นามสกุล</th><th>";?><div class="avatar">
        <img src="<?php $avatar = $pdir."default.jpg";
        echo $avatar; ?>" width="44" height="44" /></div>
        <?php echo "</th></tr>";
        // keeps getting the next row until there are no more to get
        $j=1;
        while($row = mysqli_fetch_array($result))
        {
        // Print out the contents of each row into a table
        echo "<tr><th>"; 
        ?>
        <?php
        $msg = urlencode($row['id']);
        ?>
        <a href="patdesk.php
        <?php echo "?msg=".$msg; ?>"><?php echo $row['id'];?></a>

        <?php 
        echo "</th><th>"; 
        echo "<a href=patdesk.php?msg=".$msg.">".$row['prefix']."</a>";$row['prefix'];
        echo "</th><th width=150>"; 
        echo "<a href=patdesk.php?msg=".$msg.">".$row['fname']."</a>";$row['fname'];
        echo "</th><th width=150>"; 
        echo "<a href=patdesk.php?msg=".$msg.">".$row['lname']."</a>";$row['lname'];
        echo "</th><th>";
        ?><div class="avatar">
        <img src="<?php $avatar = $pdir. "pt_".$row['id'].".jpg";
        echo $avatar; ?>" width="44" height="44" /></div>
        <?php
        echo "</th></tr>";
        $j+=1;
        } 
        echo "</table>";
        //////////////////////////
        ?>
        </div>
        </form>
<td width=160px></td></tr>
</table>
</body>
</html>
