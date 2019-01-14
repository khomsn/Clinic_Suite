<?php
include '../../config/dbc.php';
page_protect();
$pdir = "../".AVATAR_PATH;
echo "<div id=\"main\">";
$result = mysqli_query($link, "SELECT * FROM pt_to_treatment ORDER BY rtime ASC ");
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
$msg = urlencode($row['ptid']);
?>
<a href="pattrm.php
<?php echo "?msg=".$msg; ?>"><?php echo $row['ptid'];?></a>

<?php 
echo "</th><th>"; 
echo "<a href=pattrm.php?msg=".$msg.">".$row['prefix']."</a>";
echo "</th><th width=150>"; 
echo "<a href=pattrm.php?msg=".$msg.">".$row['fname']."</a>";
echo "</th><th width=150>"; 
echo "<a href=pattrm.php?msg=".$msg.">".$row['lname']."</a>";
echo "</th><th>";
?><div class="avatar">
<img src="<?php $avatar = $pdir. "pt_".$row['ptid'].".jpg";
echo $avatar; ?>" width="44" height="44" /></div>
<?php
echo "</th></tr>";
$j+=1;
} 
echo "</table>";
//////////////////////////
echo "</div>";
?>
