<?php 
include '../../config/dbc.php';

page_protect();
$pdir = "../".AVATAR_PATH;

if($_POST['found'] == 'ส่งตรวจ')
{
	$_SESSION['Patient_id'] = $_POST['ptid'];
	if(!empty($_SESSION['Patient_id'])){
        //check for opdcard in case of not exist create it.
        include '../../libs/pt_table.php';
        // go on to other step
        header("Location: pt_to_service.php");
	}
}
elseif($_POST['found'] == 'แก้ไขข้อมูล')
{
	$_SESSION['Patient_id'] = $_POST['ptid'];
	// go on to other step
	header("Location: patientupdate.php");  
}
elseif($_POST['found'] == 'OPD Card')
{
	$_SESSION['patdesk'] = $_POST['ptid'];
	// go on to other step
	header("Location: opdpageview.php");  
}
elseif($_POST['found'] == 'ใบเสร็จรับเงิน')
{
	$_SESSION['patdesk'] = $_POST['ptid'];
	// go on to other step
	header("Location: recview.php");  
}
elseif($_POST['found'] == 'ชำระหนี้')
{
	$_SESSION['patdesk'] = $_POST['ptid'];
	// go on to other step
	header("Location: ../cashier/payfordeb.php");
}
elseif($_POST['found'] == 'ขอผล-Lab')
{
	$_SESSION['patdesk'] = $_POST['ptid'];
	// go on to other step
	header("Location: ../opd/labhistory.php");
}
$title = "::My Counter::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../libs/autoname.php';
include '../../libs/autojatz.php';
include '../../main/bodyheader.php';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
<tr><td colspan="3">&nbsp;</td></tr>
<tr><td width="160" valign="top"><div class="pos_l_fix">
    <?php 
    if (isset($_SESSION['user_id']))
    {
        include 'registermenu.php';
    } 
    ?></div>
</td><td>
<?php
if($_POST['search'] =='ค้นหา')
{
    echo "<div style=\"text-align: center;\"><a HREF=\"search_pt.php\" ><input value=\"ค้นหาใหม่\" type=\"submit\"></a></div>";
}
?>
<h3 class="titlehdr">ค้นหารายชื่อผู้ป่วย</h3>
<form action="search_pt.php" method="post" name="searchForm" id="searchForm">
<?php
if($_POST['search'] =='')
{
?>
<div style="text-align: center;">
<table width="100%" border="1" cellspacing="0" cellpadding="5" class="forms">
<tr><td width="100%" valign="top">
    เลขทะเบียน:<input maxlength="5" size="7" name="ptid">
    ชื่อ:<input maxlength="15" size="15" tabindex="1" name="fname" id="fname" autofocus>&nbsp;
    นามสกุล:<input maxlength="20" size="20" tabindex="2" name="lname" id="lname">
    <hr style="width: 50%;">
    เลขที่บัตรประชาชน<input maxlength="13" size="13" tabindex="2" name="ctz_id" id="cid">
    <hr style="width: 30%;">
    โทรศัพท์บ้าน:<input maxlength="12" size="12" tabindex="3" name="htel" value="02xxx" id="htel">
    โทรศัพท์มือถือ <input maxlength="12" size="12" tabindex="4" name="mtel" value="08xxx" id="mtel">
    <hr style="width: 65%;">
    บ้านเลขที่<input tabindex="6" name="address1" type="text" size="5">หมู่ที่<input tabindex="7" name="address2" type="text" size="3"> ตำบล <input tabindex="8" name="address3" type="text" id="tname" > อำเภอ <input tabindex="14" name="address4" type="text" id="aname"> จังหวัด <input tabindex="15" name="address5" type="text" id="jname">
    <hr style="width: 85%;">
    <input tabindex="5" value="ค้นหา" name="search" type="submit">
</td></tr>
</table>
</div>
<?php
}
?>
</form>
<form action="search_pt.php" method="post" name="submitsearchForm" id="submitsearchForm">
<?php

if($_POST['search'] == 'ค้นหา')  
{
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $ptid = $_POST['ptid'];
    $ctzid = $_POST['ctz_id'];
    $hometel = $_POST['htel'];
    $mtel = $_POST['mtel'];
    
    if (ltrim($_POST['address1']) == '') $ft1 = 1;
    else $ft1 = "address1 = '$_POST[address1]'";
    if (ltrim($_POST['address2']) == '') $ft2 = 1;
    else $ft2 = "address2 = '$_POST[address2]'";
    if (ltrim($_POST['address3']) == '') $ft3 = 1;
    else $ft3 = "address3 = '$_POST[address3]'";
    if (ltrim($_POST['address4']) == '') $ft4 = 1;
    else $ft4 = "address4 = '$_POST[address4]'";
    if (ltrim($_POST['address5']) == '') $ft5 = 1;
    else $ft5 = "address5 = '$_POST[address5]'";

    

    if ($ptid)
    {
        $result = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE  id='$ptid' ");
    } else 
    if ($ctzid)
    {
        $result = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE  ctz_id='$ctzid' ");
    } else
    if ($fname and $lname and $ctz_id)
    {
        $result = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE fname='$fname' AND lname='$lname' AND ctz_id='$ctzid'");
    } else
    if ($fname AND $lname)
    {
        $result = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE fname='$fname' AND lname='$lname'");
    } else
    if ($fname)
    {
        $result = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE fname='$fname'");
    } else
    if ($lname)
    {
        $result = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE lname='$lname'");
    } else
    if(($ft1!=1) OR ($ft2!=1) OR ($ft3!=1) OR ($ft4!=1) OR ($ft5!=1))
    {
        $result = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE  ($ft1) AND ($ft2) AND  ($ft3) AND  ($ft4) AND ($ft5)" );
    } else
    if ($hometel != '02xxx')
    {
        $result = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE hometel='$hometel'");
    } else
    if ($mtel != '08xxx')
    {
        $result = mysqli_query($linkopd, "SELECT * FROM `patient_id` WHERE `mobile`='$mtel'");
    }
    

    echo "<table border='1' class='TFtable' >";
    echo "<tr><th>เลือก</th><th>เลขทะเบียน</th><th>ยศ</th><th>ชื่อ</th><th>นามสกุล</th><th>เลขบัตรประชาขน</th><th>มือถือ</th><th></th><th>ที่อยู่</th></tr>";
    // keeps getting the next row until there are no more to get
    while($row = mysqli_fetch_array($result))
    {
    // Print out the contents of each row into a table
    echo "<tr><th>";
    echo "<input type=\"radio\" name=\"ptid\" value=\"".$row['id']."\" />";
    echo "</th><th>"; 
    echo $y = $row['id'];
    echo "</th><th>"; 
    echo $row['prefix'];
    echo "</th><th width=150>"; 
    echo $row['fname'];
    echo "</th><th width=150>"; 
    echo $row['lname'];
    echo "</th><th width=150>"; 
    echo $row['ctz_id'];
    echo "</th><th width=150>"; 
    echo $row['mobile'];
    echo "</th><th>";
    $avatar = $pdir. "pt_".$y.".jpg";
    echo "<div class=\"avatar\"><img src=\"".$avatar."\" width=\"44\" height=\"44\" /></div>";
    echo "</th><th>";
    echo $row['address1']." ม.".$row['address2']." ต.".$row['address3']." อ.".$row['address4'];
    echo "</th></tr>";
    } 
    echo "</table>";
    ?>
    <br><br>
    <?php 
    if($y)
    {
    ?>
    <input name="found" value="ส่งตรวจ" type="submit">&nbsp;&nbsp;&nbsp;<input name="found" value="แก้ไขข้อมูล" type="submit">&nbsp;&nbsp;&nbsp;<input name="found" value="OPD Card" type="submit">&nbsp;&nbsp;&nbsp;<input name="found" value="ใบเสร็จรับเงิน" type="submit">&nbsp;&nbsp;&nbsp;<input name="found" value="ชำระหนี้" type="submit">&nbsp;&nbsp;&nbsp;<input name="found" value="ขอผล-Lab" type="submit">
    <?php 
    }
    else
    {
    echo "<a href='../opd/PIDregister.php'>ลงทะเบียนผู้ป่วยใหม่</a>";
    }
}
?>
</form>
</td><td width="160"></td>
</tr>
</table>
</body>
</html>
