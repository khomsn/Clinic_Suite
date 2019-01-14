<?php
$fulluri = $_SERVER['REQUEST_URI'];
/*
$trimString = "/clinic/main/account/";
$actsite = trim($fulluri, $trimString);
*/
$actsite = substr($fulluri, 14);

$thisdate = date_create();
date_date_set($thisdate, $sy, $sm, $sd);
$ddate = date_format($thisdate, 'Y-m-d');

?>
<div >
<form method="post" action="<?php echo $actsite;?>" name="regForm" id="regForm">
<?php
if ($_SESSION['user_accode']%2==0  and $_SESSION['user_level']>1)
{
	echo "<a href='../../main/account/dailyaccount.php'>บัญชีรายวันทั่วไป</a><br><br>";

		if ($actsite == "dailyaccount.php")
		{
                        if($ddate>$_SESSION['acstrdate']) echo "<input type='submit' name='todo' value = '<<'>&nbsp;";
                        else echo "<input type='button' value='*||*'>&nbsp;";
                        echo "<input type='submit' name='todo' value = '@'>&nbsp;";
			if (date(date_format($thisdate, 'Y-m-d')) < date("Y-m-d"))
			{
				echo "<input type='submit' name='todo' value = '>>'>";
			}
		echo "<br>";
		}
}
//if ($_SESSION['user_accode']%2==0  and $_SESSION['user_level']>1)
{

	echo "<a href='../../main/account/dailycash.php'>บัญชีเงินสด ประจำวัน</a><br>";
	
	if ($actsite == "dailycash.php")
		{
                        if($ddate>$_SESSION['acstrdate']) echo "<input type='submit' name='todo' value = '<<'>&nbsp;";
                        else echo "<input type='button' value='*||*'>&nbsp;";
                        echo "<input type='submit' name='todo' value = '@'>&nbsp;";
			if (date(date_format($thisdate, 'Y-m-d')) < date("Y-m-d"))
			{
				echo "<input type='submit' name='todo' value = '>>'>";
			}
		echo "<br>";
		}
}
if ($_SESSION['user_accode']%2==0  and $_SESSION['user_level']>1)
{
	echo "<a href='../../main/account/tcom.php'>บัญชีเงินสด ประจำเดือน</a><br><br>";
	if ($actsite == "tcom.php")
		{
                        if($ddate>$_SESSION['acstrdate'])
                        {
                         if( ( (date("Y",strtotime($ddate)))<=(date("Y",strtotime($_SESSION['acstrdate']))) ) AND  ((date("m",strtotime($ddate)))<=(date("m",strtotime($_SESSION['acstrdate'])))))
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
		echo "<br>";
		}
}
//if ($_SESSION['user_accode']%2==0  and $_SESSION['user_level']>1)
{

	echo "<a href='../../main/account/sellac.php'>บัญชีขาย ประจำวัน</a><br>";
	if ($actsite == "sellac.php")
		{
                        if($ddate>$_SESSION['acstrdate']) echo "<input type='submit' name='todo' value = '<<'>&nbsp;";
                        else echo "<input type='button' value='*||*'>&nbsp;";
                        echo "<input type='submit' name='todo' value = '@'>&nbsp;";
			if (date(date_format($thisdate, 'Y-m-d')) < date("Y-m-d"))
			{
				echo "<input type='submit' name='todo' value = '>>'>";
			}
		echo "<br>";
		}
}
if ($_SESSION['user_accode']%2==0  and $_SESSION['user_level']>1)
{
	echo "<a href='../../main/account/sellacm.php'>บัญชีขาย ประจำเดือน</a><br>";
	if ($actsite == "sellacm.php")
		{
            if($ddate>$_SESSION['acstrdate'])
            {
                if( ( (date("Y",strtotime($ddate)))<=(date("Y",strtotime($_SESSION['acstrdate']))) ) AND  ((date("m",strtotime($ddate)))<=(date("m",strtotime($_SESSION['acstrdate'])))))
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
	echo "<br>";	
	echo "<a href='../../main/account/Ledgeraccount.php'>แยกประเภทรายจ่าย ประจำเดือน</a><br>";
	if ($actsite == "Ledgeraccount.php")
		{
            if($ddate>$_SESSION['acstrdate'])
            {
                if( ( (date("Y",strtotime($ddate)))<=(date("Y",strtotime($_SESSION['acstrdate']))) ) AND  ((date("m",strtotime($ddate)))<=(date("m",strtotime($_SESSION['acstrdate'])))))
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
<br>
<?php 
if ($_SESSION['user_accode']%13 == 0)
{
	echo "<a href='../../main/account/profit.php'>บัญชีกำไรขาดทุน/เดือน</a><br>";
	if ($actsite == "profit.php")
		{
                         if($ddate>$_SESSION['acstrdate'])
                        {
                         if( ( (date("Y",strtotime($ddate)))<=(date("Y",strtotime($_SESSION['acstrdate']))) ) AND  ((date("m",strtotime($ddate)))<=(date("m",strtotime($_SESSION['acstrdate'])))))
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
echo "<br>";
echo "<a href='../../main/account/netprofit.php'>กำไรขาดทุนคงเหลือ</a><br><br>";
echo "<a href='../../main/account/ac_balance.php'>งบดุล</a><br><br>";
}
if ($_SESSION['user_accode']%13 == 0)
{
echo "<a  onClick=\"return popup(this, 'name','450','600','yes')\" href=\"../../main/account/comrate.php\">Commission Rate</a><br><br>";
}
?>
</form>
</div>
