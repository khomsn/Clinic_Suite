<?php
$fulluri = $_SERVER['REQUEST_URI'];/* "/main/pharma/" =13 in lenght*/
$actsite = substr($fulluri, 13);
$actsite;

$thisdate = date_create();
date_date_set($thisdate, $sy, $sm, $sd);
$ddate = date_format($thisdate, 'Y-m-d');


?>
<div >
<form method="post" action="<?php echo $actsite;?>" name="regForm" id="regForm">
<?php
if ($_SESSION['user_level']>1)
{
	if ($actsite == "drugusestat.php")
		{
            if($ddate>$_SESSION['acstrdate'])
            {
                if( ( (date("Y",strtotime($ddate)))==(date("Y",strtotime($_SESSION['acstrdate']))) ) AND  ((date("m",strtotime($ddate)))==(date("m",strtotime($_SESSION['acstrdate'])))))
                echo "<input type='button' value='*||*'>&nbsp;";
                else 
                echo "<input type='submit' name='todom' value = '<<'>&nbsp;";
            }
            echo "<input type='submit' name='todom' value = '@'>&nbsp;";

			if ($sm < date("m"))
			{
				if ($sy <= date("Y"))
				{
				echo "<input type='submit' name='todom' value = '>>'>";
				}
			}
			if ($sm >= date("m"))
			{
				if ($sy < date("Y"))
				{
				echo "<input type='submit' name='todom' value = '>>'>";
				}
			}
		}
}
?>
</form>
</div>
