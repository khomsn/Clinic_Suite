<?php 
include '../../config/dbc.php';

page_protect();
$id = $_SESSION['patdesk'];

$Patient_id = $id;
include '../../libs/opdxconnection.php';

$tmp = "tmp_".$id;
//$pdir = PT_AVATAR_PATH.$id."/";
$pdir = "../".PT_AVATAR_PATH;

echo "<!DOCTYPE html>
<html>
<head>
<meta content=\"text/html; charset=utf-8\" http-equiv=\"content-type\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/styles.css\"/>";
include '../../libs/popup.php';
echo "</head><body>";

if (isset($_SESSION['user_id']))
{
    echo "<div class=\"myaccount\"><div class=\"ptavatar\">";
    $avatar = $pdir. "pt_". $id . ".jpg";
    echo "<img src='". $avatar."' width='140' height='140' />";
    echo "</div>";
    
    $pinfo = mysqli_query($linkopd, "select * from patient_id where id='$id'");
    while($rowinfo = mysqli_fetch_array($pinfo))
    {
        echo $rowinfo['prefix'].$rowinfo['fname']."  ".$rowinfo['lname']."<br>";
        echo "เพศ ".$rowinfo['gender'];
        $date1=date_create(date("Y-m-d"));
        $date2=date_create($rowinfo['birthday']);
        $diff=date_diff($date2,$date1);
        echo "&nbsp; &nbsp;อายุ&nbsp; ";
        echo $diff->format("%Y ปี %m เดือน %d วัน")."<br>";
        echo "สูง ".$rowinfo['height']."cm &nbsp; &nbsp;หนัก ";
    }
    $pttable = "pt_".$id;
    $pin = mysqli_query($linkopdx, "select MAX(id) from $pttable ");
    $maxid = mysqli_fetch_array($pin);
    $rid = $maxid[0];
    $ptin = mysqli_query($linkopdx, "select * from $pttable where  id = '$rid' ");
    while ($row_settings = mysqli_fetch_array($ptin))
    {
        echo $row_settings['weight']."kg<br>";
        echo "BT=".$row_settings['temp']."&deg;C BP=";
        echo $bps = $row_settings['bpsys']."/".$bpd = $row_settings['bpdia']."mmHg<br>";
        echo "HR=".$row_settings['hr']." bpm ";
        echo "RR=".$row_settings['rr']." tpm";
        if(empty($_SESSION['history'.$pid]))
        $hist = $row_settings['dofhis'];
        else
        $hist = $_SESSION['history'.$pid];
        if(empty($_SESSION['phex'.$pid]))
        $phex = $row_settings['phex'];
        else
        $phex = $_SESSION['phex'.$pid];
        $rr = $row_settings['rr']; 
        $temp = $row_settings['temp']; 
        $ddx = $row_settings['ddx'];
    }

    echo "<br><p><strong>Clinic Menu</strong></p>";

    $ptin = mysqli_query($link, "select * from $tmp ");
    $row_settings = mysqli_fetch_array($ptin);
    if (ltrim($row_settings['csf']) ==="" or $_SESSION['user_accode']%11!=0)
    {
        echo "<a href='../opd/prehist.php' TARGET='MAIN'>ตรวจร่างกายเบื่องต้น</a><br><br>";
    }
    if (ltrim($row_settings['csf']) !=="")
    {
        echo "<a href='../opd/histaking.php' TARGET='MAIN'>ประวัติ และ ตรวจร่างกาย</a><br><br>";
        echo "<a href='../opd/investigation.php' TARGET='MAIN'>Investigation</a><br><br>";
        echo "<a href='../opd/treatment.php' TARGET='MAIN'>หัตถการ</a><br><br>";
        echo "<a href='../opd/prescript.php' TARGET='MAIN'>สั่งยา</a><br>";
        if($_SESSION['user_accode']%11!=0)
        {
        echo "<hr>";
        echo "<a href='../cashier/ptpay.php' TARGET='MAIN'>สรุปรายการยา</a>";
        }
        echo "<hr>";
        echo "<a href='../opd/appointment.php' TARGET='MAIN'>Appointment</a><br>";
        echo "<hr>";
        echo "<a href='../opd/obsnote.php' TARGET='MAIN'>Observe Note</a><br>";
        echo "<hr>";
        if($row_settings['medcert']==1){echo "<a href='../../docform/Medical_Certificate_2551.php' TARGET='MAIN'>ใบรับรองแพทย์</a><br>";}
        else{echo "<a href='../../docform/Medical_Certificate.php' TARGET='MAIN'>ใบรับรองแพทย์</a><br>";}
        echo "<hr>";
    }
echo "<a HREF=\"../opd/opdpage.php\" onClick=\"return popup(this,'name','800','600','yes');\" >OPD Card</a><br>";
echo "</div>";
}
echo "<br></body></html>";
?>
