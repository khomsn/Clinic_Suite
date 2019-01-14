<?php 

include '../../config/dbc.php';
page_protect();
$pdir = "../".AVATAR_PATH;
unset($_SESSION['patdesk']);

$_SESSION['called'] = 0;
$sql_update = "UPDATE `queuesystem` SET `ptid` = '0' WHERE `FromIp` = '$_SESSION[user_ip]'";
mysqli_query($link, $sql_update);

$title = "::ผู้ป่วยรอพบแพทย์::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" href=\"../../jscss/css/table_alt_color1.css\">";
echo "<script type=\"text/javascript\">
    if(window.top.location != window.location) 
    {
        window.top.location.href = window.location.href; 
    }
</script>";
include '../../main/bodyheader.php';
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() { /// Wait till page is loaded
setInterval(timingLoad, 3000);
function timingLoad() {
$('#main').load('updateptdoc.php #main', function() {
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
        <h3 class="titlehdr">ผู้ป่วยรอตรวจ</h3>
        <p align="right">&nbsp; </p>
        <div id="main"><!--List Patient wait for doctor-->
        <?php
        $result = mysqli_query($link, "SELECT * FROM pt_to_doc ORDER BY time ASC ");
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
        if($row['code']>=90) $bgc[$j] = "#ff0000";
        if($row['code']<90) $bgc[$j] = "#f52f0b";
        if($row['code']<60) $bgc[$j] = "#f5520b";
        if($row['code']<50) $bgc[$j] = "#f5ab0b";
        if($row['code']<40) $bgc[$j] = "#e0f50b";
        if($row['code']<30) $bgc[$j] = "#67f50b";
        if($row['code']<20) $bgc[$j] = "#008000";
        $msg = urlencode($row['ID']);
        echo "<tr><th style='background-color:";
        echo $bgc[$j];
        echo ";'>";
        echo "<a href=\"patdesk.php?msg=".$msg."\">";
        echo $row['ID'];
        echo "</a>";
        echo "</th><th>"; 
        echo "<a href=patdesk.php?msg=".$msg.">".$row['prefix']."</a>";$row['prefix'];
        echo "</th><th width=150>"; 
        echo "<a href=patdesk.php?msg=".$msg.">".$row['F_Name']."</a>";$row['F_Name'];
        echo "</th><th width=150>"; 
        echo "<a href=patdesk.php?msg=".$msg.">".$row['L_Name']."</a>";$row['L_Name'];
        echo "</th><th>";
        ?><div class="avatar">
        <img src="<?php $avatar = $pdir. "pt_".$row['ID'].".jpg";
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
