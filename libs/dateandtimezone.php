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
$bsy = $sy + 543;
?>
