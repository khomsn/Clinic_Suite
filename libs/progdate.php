<?php
    date_default_timezone_set('Asia/Bangkok');
	$sd = date("d");
	$sm = date("m");
	$sy = date("Y");
	
if($sd!=$_SESSION['sdnow'])
{
    $_SESSION['sdnow'] = $sd;
    $_SESSION['sd'] = $_SESSION['sdnow'];
}
if($sm!=$_SESSION['smnow'])
{
    $_SESSION['smnow'] = $sm;
    $_SESSION['sm'] = $_SESSION['smnow'];
}
if($sy!=$_SESSION['synow'])
{
    $_SESSION['synow'] = $sy;
    $_SESSION['sy'] = $_SESSION['synow'];
}

if ($_POST['todo'] == '<<' )
{
	$date = new DateTime($_SESSION['sd'].'-'.$_SESSION['sm'].'-'.$_SESSION['sy']);
	$date = date_sub($date, new DateInterval("P1D"));
	$sd = $date->format("d");
	$sm = $date->format("m");
	$sy = $date->format("Y");
	$_SESSION['sd'] = $sd;
	$_SESSION['sm'] = $sm;
	$_SESSION['sy'] = $sy;
}
elseif ($_POST['todo'] == '>>' ) 
{
	$date = new DateTime($_SESSION['sd'].'-'.$_SESSION['sm'].'-'.$_SESSION['sy']);
	$date = date_add($date, new DateInterval("P1D"));
	$sd = $date->format("d");
	$sm = $date->format("m");
	$sy = $date->format("Y");
	$_SESSION['sd'] = $sd;
	$_SESSION['sm'] = $sm;
	$_SESSION['sy'] = $sy;
}
elseif ($_POST['todo'] == '@' )
{
	$_SESSION['sd'] = $_SESSION['sdnow'];
	$_SESSION['sm'] = $_SESSION['smnow'];
	$_SESSION['sy'] = $_SESSION['synow'];
}

if ($_POST['todom'] == '<<' )
{
	$date = new DateTime('28'.'-'.$_SESSION['sm'].'-'.$_SESSION['sy']);
	$date = date_sub($date, new DateInterval("P1M"));
	$sm = $date->format("m");
	$sy = $date->format("Y");
	$_SESSION['sm'] = $sm;
	$_SESSION['sy'] = $sy;
	$_SESSION['page']='';
}
elseif ($_POST['todom'] == '>>' ) 
{
	$date = new DateTime('1'.'-'.$_SESSION['sm'].'-'.$_SESSION['sy']);
	$date = date_add($date, new DateInterval("P1M"));
	$sm = $date->format("m");
	$sy = $date->format("Y");
	$_SESSION['sm'] = $sm;
	$_SESSION['sy'] = $sy;
	$_SESSION['page']='';
}
elseif ($_POST['todom'] == '@' )
{
	$_SESSION['sm'] = $_SESSION['smnow'];
	$_SESSION['sy'] = $_SESSION['synow'];
	$_SESSION['page']='';
}

if ($_POST['todoy'] == '<<' )
{
	$date = new DateTime('28'.'-'.$_SESSION['sm'].'-'.$_SESSION['sy']);
	$date = date_sub($date, new DateInterval("P1Y"));
	$sm = $date->format("m");
	$sy = $date->format("Y");
	$_SESSION['sm'] = $sm;
	$_SESSION['sy'] = $sy;
	$_SESSION['page']='';
}
elseif ($_POST['todoy'] == '>>' ) 
{
	$date = new DateTime('1'.'-'.$_SESSION['sm'].'-'.$_SESSION['sy']);
	$date = date_add($date, new DateInterval("P1Y"));
	$sm = $date->format("m");
	$sy = $date->format("Y");
	$_SESSION['sm'] = $sm;
	$_SESSION['sy'] = $sy;
	$_SESSION['page']='';
}
elseif ($_POST['todoy'] == '@' )
{
	$_SESSION['sm'] = $_SESSION['smnow'];
	$_SESSION['sy'] = $_SESSION['synow'];
	$_SESSION['page']='';
}

$bsy = $sy + 543;
?>
