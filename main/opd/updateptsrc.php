<?php
include '../../config/dbc.php';
page_protect();
$pdir = "../".AVATAR_PATH;
echo "<div id=\"main\">";

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
echo "<a href='updateptimage.php?msg=".$msg."' onClick=\"return popup(this, 'name' , '500' , '300' , 'yes' );\">";
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
