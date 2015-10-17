<?php
$fulluri = $_SERVER['REQUEST_URI'];
$trimString = "/clinic/main/";
$actsite = trim($fulluri, $trimString);
//echo $actsite;
?>
<div >
<form method="post" action="<?php echo $actsite;?>" name="regForm" id="regForm">
<?php
if ($_SESSION['user_accode']%2==0  and $_SESSION['user_level']>1)
{
	echo "<a href='../main/dailyaccount.php'>บัญชีรายวันทั่วไป</a><br><br>";

		if ($actsite == "dailyaccount.php")
		{
		echo "<input type='submit' name='todo' value = '<<'>&nbsp;<input type='submit' name='todo' value = '@'>&nbsp;";
			$thisdate = date_create();
			date_date_set($thisdate, $sy, $sm, $sd);
//			echo date_format($thisdate, 'Y-m-d');
			if (date(date_format($thisdate, 'Y-m-d')) < date("Y-m-d"))
			{
				echo "<input type='submit' name='todo' value = '>>'>";
			}
		echo "<br>";
		}
}
//if ($_SESSION['user_accode']%2==0  and $_SESSION['user_level']>1)
{

	echo "<a href='../main/dailycash.php'>บัญชีเงินสด ประจำวัน</a><br>";
	
	if ($actsite == "dailycash.php")
		{
		echo "<input type='submit' name='todo' value = '<<'>&nbsp;<input type='submit' name='todo' value = '@'>&nbsp;";
			$thisdate = date_create();
			date_date_set($thisdate, $sy, $sm, $sd);
//			echo date_format($thisdate, 'Y-m-d');
			if (date(date_format($thisdate, 'Y-m-d')) < date("Y-m-d"))
			{
				echo "<input type='submit' name='todo' value = '>>'>";
			}
		echo "<br>";
		}
}
if ($_SESSION['user_accode']%2==0  and $_SESSION['user_level']>1)
{

	echo "<a href='../main/tcom.php'>บัญชีเงินสด ประจำเดือน</a><br><br>";
	if ($actsite == "tcom.php")
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
		echo "<br>";
		}
}
//if ($_SESSION['user_accode']%2==0  and $_SESSION['user_level']>1)
{

	echo "<a href='../main/sellac.php'>บัญชีขาย ประจำวัน</a><br>";
	if ($actsite == "sellac.php")
		{
		echo "<input type='submit' name='todo' value = '<<'>&nbsp;<input type='submit' name='todo' value = '@'>&nbsp;";
			$thisdate = date_create();
			date_date_set($thisdate, $sy, $sm, $sd);
//			echo date_format($thisdate, 'Y-m-d');
			if (date(date_format($thisdate, 'Y-m-d')) < date("Y-m-d"))
			{
				echo "<input type='submit' name='todo' value = '>>'>";
			}
		echo "<br>";
		}
}
if ($_SESSION['user_accode']%2==0  and $_SESSION['user_level']>1)
{

	echo "<a href='../main/sellacm.php'>บัญชีขาย ประจำเดือน</a><br>";
	if ($actsite == "sellacm.php")
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
	echo "<br>";	
	echo "<a href='../main/Ledgeraccount.php'>แยกประเภทรายจ่าย ประจำเดือน</a><br>";
	if ($actsite == "Ledgeraccount.php")
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

<!--<input type="submit" name="todo" value = "<<">&nbsp;<input type="submit" name="todo" value = "@">&nbsp;<input type="submit" name="todo" value = ">>"><br>-->
<br>
<?php 
if ($_SESSION['user_accode']%13 == 0)
{
	echo "<a href='../main/profit.php'>บัญชีกำไรขาดทุน/เดือน</a><br>";
	if ($actsite == "profit.php")
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
echo "<br>";

echo "<a href='../main/ac_balance.php'>งบดุล</a><br><br>";
}
if ($_SESSION['user_accode']%13 == 0)
{
echo "<a  onClick=\"return popup(this, 'name','450','600','yes')\" href=\"../main/comrate.php\">Commission Rate</a><br><br>";
}
?>
</form>
</div>
