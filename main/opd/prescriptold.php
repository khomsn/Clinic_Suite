<?php 
include '../../config/dbc.php';
page_protect();

$ptid = $_SESSION['patdesk'];
$id = $_SESSION['patdesk'];
$Patient_id = $ptid;
include '../../libs/opdxconnection.php';

$pttable = "pt_".$ptid;

$pin = mysqli_query($linkopdx, "select MAX(id) from $pttable ");
$maxid = mysqli_fetch_array($pin);

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$ptid' ");
while($row = mysqli_fetch_array($ptin)) 
{
    $dl1 = $row['drug_alg_1'];
    $dl2 = $row['drug_alg_2'];
    $dl3 = $row['drug_alg_3'];
    $dl4 = $row['drug_alg_4'];
    $dl5 = $row['drug_alg_5'];
}

$catc = $_SESSION['catc'];
/* check for pregnancy and catc enable for this patient_id
*  and return $cat = $cat = '(cat = "A" or cat = "B" or cat = "N")';
*  '(cat = "A" or cat = "B" or cat = "C" or cat = "N")'; or 1;
*  from catcset.php
*/
include '../../libs/catcset.php';

if($maxid[0]!=$_SESSION['mxid'])
{
    $_SESSION['mxid'] = $maxid[0];
    $_SESSION['rid'] = $maxid[0]-1;
}

if ($_POST['todo'] == '<<' )
{
    include '../../libs/ckoldpr.php';
	$_SESSION['rid'] = $_SESSION['rid'] - 1;
}
if ($_POST['todo'] == '>>' ) 
{
    include '../../libs/ckoldpr.php';
	$_SESSION['rid'] = $_SESSION['rid'] +1;
//    include '../../libs/ckoldpr.php';
}
if ($_POST['todo'] == 'Last' )
{
    include '../../libs/ckoldpr.php';
    $_SESSION['rid'] = $_SESSION['mxid']-1;
//    include '../../libs/ckoldpr.php';
}
if ($_POST['todo'] == 'OK' OR $_POST['todo'] == 'Close') 
{
    $_SESSION['Prescription'] =1;
    include '../../libs/ckoldpr.php';
    unset($_SESSION['mxid']);
    unset($_SESSION['rid']);
}

$title = "::ประวัติยาเก่า::";
include '../../main/header.php';
include '../../libs/autodrug.php';
include '../../libs/autoorder.php';
include '../../libs/reloadopener.php';
?>
<script language="JavaScript" type="text/javascript" src="../../jscss/js/checkthemall.js"></script>
</head><body>
<form method="post" action="prescriptold.php" name="formMultipleCheckBox" id="formMultipleCheckBox">
<div style="text-align: right;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="main">
<tr><td width =18% style="text-align: center;"><?php if($_SESSION['rid'] > 1){ echo "<input type='submit' name='todo' value='<<' "; if($reload) echo "onClick='reloadParent();'"; echo "/>";} ?></td>
    <td width =16% style="text-align: center;"><?php if($_SESSION['rid'] < ($_SESSION['mxid'] - 1)){ echo "<input type='submit' name='todo' value='>>' "; if($reload) echo "onClick='reloadParent();'"; echo "/>";} ?></td>
    <td width =16% style="text-align: center;"><?php echo "<input type='submit' name='todo' value='Last' >"; ?></td>
    <td width =16% style="text-align: center;"><a HREF="drugorder.php">Order</a>:</td>
    <td widht =16% style="text-align: center;"><input type='submit' name='todo' value='OK' onClick='reloadParent();'/></td>
    <td width =18% style="text-align: center;"><input type="submit" name="todo" value="Close" onClick="reloadParentAndClose();"/>
</td></tr>
</table>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="main">
<tr><td>
    <h3 class="titlehdr">ประวัติยาของ <?php
    $ptin = mysqli_query($linkopd, "select * from patient_id where id='$ptid' ");
    while ($row_settings = mysqli_fetch_array($ptin))
    {
        echo $row_settings['fname']; 
        echo "&nbsp;"; 
        echo $row_settings['lname'];
    }
    $pin = mysqli_query($linkopdx, "select * from $pttable WHERE id = '$_SESSION[rid]' ");
    while ($row_settings = mysqli_fetch_array($pin))
    {
        echo " &nbsp; เมื่อวันที่ ";
        $date = new DateTime($row_settings['date']);
        $sd = $date->format("d");
        $sm = $date->format("m");
        $sy = $date->format("Y");
        $bsy = $sy+543;
        echo $sd;
        echo " ";
        $m = $sm;
        switch ($m)
        {
            case 1:
            echo "มกราคม";
            break;
            case 2:
            echo "กุมภาพันธ์";
            break;
            case 3:
            echo "มีนาคม";
            break;
            case 4:
            echo "เมษายน";
            break;
            case 5:
            echo "พฤษภาคม";
            break;
            case 6:
            echo "มิถุนายน";
            break;
            case 7:
            echo "กรกฎาคม";
            break;
            case 8:
            echo "สิงหาคม";
            break;
            case 9:
            echo "กันยายน";
            break;
            case 10:
            echo "ตุลาคม";
            break;
            case 11:
            echo "พฤศจิกายน";
            break;
            case 12:
            echo "ธันวาคม";
            break;
        }
        echo " พ.ศ.";
        echo $bsy; //date("Y")+543;
        echo " ณ ".$row_settings['clinic'];
    }
    ?></h3>
    <form id="formMultipleCheckBox" name="formMultipleCheckBox">
        <table style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2"  class="forms">
        <tr><th width = 10><input name="checkAll" type="checkbox" id="checkAll" value="1" onclick="javascript:checkThemAll(this);" /></th><th width=10>No</th><th width =250>ชื่อ+ขนาด</th><th>ชื่อสามัญ</th><th>วิธีใช้</th><th width = 35>จำนวน</th></tr>
        <?php 
            $ptin = mysqli_query($linkopdx, "select * from $pttable WHERE id = '$_SESSION[rid]' ");
            while ($row = mysqli_fetch_array($ptin))
            {
                for($i=1;$i<=14;$i++)
                {
                    $idrx = "idrx".$i;
                    $rx ="rx".$i;
                    $rxgn ="rxg".$i;
                    $us = "rx".$i."uses";
                    $rxv ="rx".$i."v";
                    $y=1;//initialize needed!
                    if($row[$rx]!="")
                    {   
                        $dgn = $row[$rxgn];
                        
                        //echo "catc=".$catc;
                        //echo "cat=".$cat;
                        $catcheck = mysqli_fetch_array(mysqli_query($link, "select * from drug_id WHERE dgname = '$dgn'  AND $cat"));
                        //echo " dgname=".$catcheck['dgname'];
                        //echo " cat=".$catcheck['cat'];
                        
                        if(empty($catcheck['dgname'])) {$y=0; goto BKP;}
                        
                        if(!empty($dl1)) { if($row[$rxgn] != $dl1) $y = 1;else {$y=0; goto BKP;}}
                        if(!empty($dl2)) { if($row[$rxgn] != $dl2) $y = 1;else {$y=0; goto BKP;}}
                        if(!empty($dl3)) { if($row[$rxgn] != $dl3) $y = 1;else {$y=0; goto BKP;}}
                        if(!empty($dl4)) { if($row[$rxgn] != $dl4) $y = 1;else {$y=0; goto BKP;}}
                        if(!empty($dl5)) { if($row[$rxgn] != $dl5) $y = 1;else {$y=0; goto BKP;}}
                        
                        $dgr = mysqli_query($link, "select groupn from drug_id WHERE dgname = '$dgn' ");
                        while($row1 = mysqli_fetch_array($dgr)) 
                        {
                            $cond = $row1['groupn'];
                            if(!empty($dl1) AND !empty($cond)) { if($row1['groupn'] != $dl1) $y = 1;else {$y=0; goto BKP1;}}
                            if(!empty($dl2) AND !empty($cond)) { if($row1['groupn'] != $dl2) $y = 1;else {$y=0; goto BKP1;}}
                            if(!empty($dl3) AND !empty($cond)) { if($row1['groupn'] != $dl3) $y = 1;else {$y=0; goto BKP1;}}
                            if(!empty($dl4) AND !empty($cond)) { if($row1['groupn'] != $dl4) $y = 1;else {$y=0; goto BKP1;}}
                            if(!empty($dl5) AND !empty($cond)) { if($row1['groupn'] != $dl5) $y = 1;else {$y=0; goto BKP1;}}
                            
                        }
                        BKP1:
                        $dgr = mysqli_query($link, "select subgroup from drug_id WHERE dgname = '$dgn' ");
                        while($row1 = mysqli_fetch_array($dgr)) 
                        {
                            $cond = $row1['subgroup'];
                            if(!empty($dl1) AND !empty($cond)) { if($row1['subgroup'] != $dl1) $y = 1;else {$y=0; goto BKP;}}
                            if(!empty($dl2) AND !empty($cond)) { if($row1['subgroup'] != $dl2) $y = 1;else {$y=0; goto BKP;}}
                            if(!empty($dl3) AND !empty($cond)) { if($row1['subgroup'] != $dl3) $y = 1;else {$y=0; goto BKP;}}
                            if(!empty($dl4) AND !empty($cond)) { if($row1['subgroup'] != $dl4) $y = 1;else {$y=0; goto BKP;}}
                            if(!empty($dl5) AND !empty($cond)) { if($row1['subgroup'] != $dl5) $y = 1;else {$y=0; goto BKP;}}
                            
                        }
                        // goto point
                        BKP://echo "Y=".$y;
                        //
                        echo "<tr><td>";
                        if($y)
                        { 
                            $idpst = false;
                            $tmpcheck = mysqli_query($link, "select * from $tmp ");
                            while ($tmpitem = mysqli_fetch_array($tmpcheck))
                            {	
                                for($m=1;$m<=14;$m++)
                                {
                                    $chitem = "idrx".$m;
                                    if($tmpitem[$chitem] == $row[$idrx])
                                    {
                                        $idpst = true;
                                        echo "No</td><td>$i</td><td>";
                                        break 1;
                                    }
                                }
                            }
                            if (!$idpst) echo "<input type='checkbox' name='$i' id='checkBoxes' value=1 ></td><td>$i</td><td>";
                        }
                        else echo "No</td><td>$i</td><td>";
                        echo $row[$rx];
                        echo "</td>";
                        echo "<td>";
                        echo $row[$rxgn];
                        echo "</td>";
                        echo "<td>";
                        echo $row[$us];
                        echo "</td>";
                        echo "<td>";
                        echo $row[$rxv];
                        echo "</td></tr>";
                    }
                }
            }
        ?>
        </table>
    </form>
</td></tr>
</table>
</form>
</body>
</html>
