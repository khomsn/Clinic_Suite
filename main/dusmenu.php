<?php
$fulluri = $_SERVER['REQUEST_URI'];
$trimString = "/clinic/main/";
$actsite = trim($fulluri, $trimString);
//echo $actsite;
?>
<div >
<form method="post" action="<?php echo $actsite;?>" name="regForm" id="regForm">
<?php
if ($_SESSION['user_level']>1)
{

	echo "<a href='../main/drugusestat.php'>การใช้ยาประจำเดือน</a><br>";
	if ($actsite == "drugusestat.php")
		{
		echo "<input type='submit' name='todom' value = '<<'>&nbsp;<input type='submit' name='todom' value = '@'>&nbsp;";
			if ($sm < date("m"))
			{
				if ($sy <= date("Y"))
				{
				echo "<input type='submit' name='todom' value = '>>'>";
				}
			}
			if ($sy <= date("Y"))
			{
				if ($sm > date("m"))
				{
				echo "<input type='submit' name='todom' value = '>>'>";
				}
			}
		}
}
?>
</form>
</div>
