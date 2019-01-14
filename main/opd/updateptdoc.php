<?php
include '../../config/dbc.php';
page_protect();
$pdir = "../".AVATAR_PATH;
echo "<div id=\"main\">";
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
