<?php 
include '../../config/dbc.php';

page_protect();
$_SESSION['todoc']=0;

$id = $_SESSION['patdesk'];
$Patient_id = $id;
include '../../libs/opdxconnection.php';

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;

if($_POST['register'] == 'บันทึก') 
{ 

//$date = date("Y-m-d");
//check for "csf" if blank don't insert
if (ltrim($_POST['csf'])!=="")
{
$medcert = 0;

if($_POST['medc'])
{
  $medcert = $_POST['medcert'];
}

//check staff
$pstaff=mysqli_fetch_array(mysqli_query($linkopd, "select staff from patient_id where id='$id'"));
$staff = $pstaff[0];//พนักงาน
if($staff == 0)
{
$staff = $_POST['policy']; //คนไข้ทั่วไป
}

//check if already record
$tptin = mysqli_query($link, "select * from  `tmp_$id` ");
$row_settings = mysqli_fetch_array($tptin);

if (ltrim($row_settings['csf'])==="")
    {
    // assign insertion pattern
    $sql_insert = "INSERT into `tmp_$id` (`csf`,`preg`,`medcert`,`pricepolicy`) VALUES ('$_POST[csf]','$_POST[preg]','$medcert','$staff')";

    // Now insert Patient to "tmp_#id" table
    mysqli_query($link, $sql_insert);
    
    $rindex=mysqli_fetch_array(mysqli_query($link, "select rindex from `tmp_$id`"));
    $rindex = $rindex[0];

    // assign insertion pattern
    if(empty($_POST['weight'])) $_POST['weight']=0;
    if(empty($_POST['height'])) $_POST['height']=0;
    if(empty($_POST['temp'])) $_POST['temp']=0;
    if(empty($_POST['bpsys'])) $_POST['bpsys']=0;
    if(empty($_POST['bpdia'])) $_POST['bpdia']=0;
    if(empty($_POST['hr'])) $_POST['hr']=0;
    if(empty($_POST['rr'])) $_POST['rr']=0;
  
    $sql_insert = "INSERT into `pt_$id`
			    (`id`,`date`,`weight`,`height`,`temp`,`bpsys`, `bpdia`, `hr`, `rr`, `ccp`, `clinic`)
			VALUES
			('$rindex', NOW(),'$_POST[weight]','$_POST[height]','$_POST[temp]','$_POST[bpsys]','$_POST[bpdia]','$_POST[hr]','$_POST[rr]','$_POST[csf]','$_SESSION[clinic]')";

    // Now insert Patient to "pt_#id" table
    mysqli_query($linkopdx, $sql_insert);
    }
    
if (ltrim($row_settings['csf']) !=="")
    {
    // assign insertion pattern
    $sql = "UPDATE tmp_$id SET `csf` = '$_POST[csf]', `preg` = '$_POST[preg]',`medcert` = '$medcert',`pricepolicy`= '$staff'";
    // Now insert Patient to "tmp_#id" table
    mysqli_query($link, $sql);
    
    $pin = mysqli_query($linkopdx, "select MAX(id) from $pttable ");
    $rid = mysqli_fetch_array($pin);
   // assign insertion pattern
    $sql = "UPDATE $pttable SET
			`weight` = '$_POST[weight]',
			`height` = '$_POST[height]',
			`temp` = '$_POST[temp]',
			`bpsys` = '$_POST[bpsys]',
			`bpdia` = '$_POST[bpdia]',
			`hr` = '$_POST[hr]',
			`rr` = '$_POST[rr]',
			`ccp` = '$_POST[csf]'
			 WHERE  id='$rid[0]'
			";
    
    // Now insert Patient to "pt_#id" table
    mysqli_query($linkopdx, $sql);
    }
}
//update height at patient_id.

if($_SESSION['age']<=20 OR $_SESSION['age']>=50) mysqli_query($linkopd, "UPDATE patient_id SET `height` = '$_POST[height]' where id='$id'");
// go on to other step
$ptdoc = 1;
}
if($_POST['register']=='ส่งเข้าห้องตรวจ')
{
    //screening code 
    /*  vital signs screening  */
    // Temp 1=[36.5°C to 37.3°C] 2=[360-36.5°C or 37.3-38.0] 3=[38-38.5] 4=[38.5-39.0] 5=[35.5-36 or 39-39.5] 6=[<35.5 or >39.5]
    if($_POST['temp']>=36.5 and $_POST['temp']<=37.3) $ct = 1;
    elseif(($_POST['temp']>=36.0 and $_POST['temp']<36.5) or ($_POST['temp']>37.3 and $_POST['temp']<=38.0)) $ct = 5;
    elseif($_POST['temp']>38.0 and $_POST['temp']<=38.5) $ct = 15;
    elseif($_POST['temp']>38.5 and $_POST['temp']<=39.0) $ct = 30;
    elseif(($_POST['temp']>=35.5 and $_POST['temp']<36.0) or ($_POST['temp']>39.0 and $_POST['temp']<=39.5)) $ct = 50;
    elseif($_POST['temp']<35.5 or $_POST['temp']>39.5 ) $ct = 60;
    // RR 12 to 18 breaths per minute 1=[12-18] 2=[8-12] 3=[18-22] 4=[5-8 or 22-26] 5=[<5 or >26]
    if( $_SESSION['age'] >=13 )
    {
        if($_POST['hr']>=55 or $_POST['hr']<=105) $chr = 1;
        if(($_POST['hr']>45 and $_POST['hr']<55) or ($_POST['hr']>105 and $_POST['hr']<=125)) $chr = 2;
        if($_POST['hr']<=45 or $_POST['hr']>125) $chr = 5;
    }
    if( $_SESSION['age'] >=15 )
    {
        if($_POST['hr']>=55 or $_POST['hr']<=100) $chr = 1;
        if(($_POST['hr']>45 and $_POST['hr']<55) or ($_POST['hr']>100 and $_POST['hr']<=125)) $chr = 2;
        if(($_POST['hr']>125 and $_POST['hr']<=130)) $chr = 4;
        if($_POST['hr']<=45 or $_POST['hr']>130) $chr = 6;
    }
    // HR 15year up [60 – 100] bpm. 13+ [55 - 105]
    if($_SESSION['age']>=6)
    {
    // BP 80 - 120
        if($_POST['bpsys']>=90 and $_POST['bpsys']<=130) $cbp = 1;
        if(($_POST['bpsys']>130 and $_POST['bpsys']<=145)) $cbp = 2;
        if(($_POST['bpsys']>145 and $_POST['bpsys']<=165)) $cbp = 3;
        if(($_POST['bpsys']>165 and $_POST['bpsys']<=195)) $cbp = 4;
        if(($_POST['bpsys']-$_POST['bpdia'])>50) $cbpd = 0;
        if(($_POST['bpsys']-$_POST['bpdia'])<=50) $cbpd = 4;
        if(($_POST['bpsys']-$_POST['bpdia'])<=30) $cbpd = 8;
    }
    $codesum = $ct + $chr * ($cbp+$cbpd);
    if($_SESSION['age']>=10)
    {
        if(($_POST['bpsys']>=85 and $_POST['bpsys']<90)) $codesum = $codesum + 50;
        if($_POST['bpsys']>195)  $codesum = $codesum + 90;
        if($_POST['bpsys']<85)  $codesum = $codesum + 90;
    }
    if($_POST['temp']<35.0 or $_POST['temp']>=40 ) $codesum = 90;
    //
	  $sql_insert = "INSERT INTO `pt_to_doc` (`ID`, `prefix`, `F_Name`, `L_Name`, `code`) VALUES ('$id', '$_SESSION[prefix]', '$_SESSION[fname]', '$_SESSION[lname]', '$codesum')";
	  // Now insert Patient to "patient_id" table
	  mysqli_query($link, $sql_insert);
	  // Now Delete Patient from "pt_to_doc" table
	  mysqli_query($link, "DELETE FROM pt_to_scr WHERE id = '$id' ");
header("Location: pt1page.php");
}
$title = "::ประวัติ ตรวจร่างกาย::";
include '../../main/header.php';
include '../../main/bodyheader.php';
?>
<table width="100%" border="1" cellspacing="0" cellpadding="5" class="main">
<tr><td><h3 class="titlehdr">ประวัติ และ ตรวจร่างกาย เบื้องต้น</h3>
    <form method="post" action="prehist.php" name="regForm" id="regForm">
        <table align="center" style="text-align: center; width: 703px; height: 413px;" border="0" cellpadding="2" cellspacing="2"  class="forms">
            <tr><td style="width: 646px; vertical-align: middle;">
                <div style="text-align: center;">
                    <h3>ชื่อ: &nbsp; 
                    <?php
                        $pin1 = mysqli_query($link, "select * from `tmp_$id` ");
                        while($rid1 = mysqli_fetch_array($pin1))
                        {
                            $preg=$rid1['preg'];
                            $hrec=$rid1['csf'];
                            $medcert=$rid1['medcert'];
                            $pricepolicy=$rid1['pricepolicy'];
                        }
                        if(!empty($hrec))
                        {
                            $pin = mysqli_query($linkopdx, "select MAX(id) from $pttable ");
                            $rid = mysqli_fetch_array($pin);
                            $rid[0];
                            
                            $pin = mysqli_query($linkopdx, "select * from $pttable WHERE id=$rid[0]");
                            while($rid = mysqli_fetch_array($pin))
                            {
                                $weight=$rid['weight'];
                                $height=$rid['height'];
                                $temp=$rid['temp'];
                                $bpsys=$rid['bpsys'];
                                $bpdia=$rid['bpdia'];
                                $hr=$rid['hr'];
                                $rr=$rid['rr'];
                                $ccp=$rid['ccp'];
                            }
                        }
                        while ($row_settings = mysqli_fetch_array($ptin))
                        { 
                            $_SESSION['prefix']= $row_settings['prefix'];
                            echo $_SESSION['fname']= $row_settings['fname'];
                            echo "&nbsp; &nbsp; &nbsp;"; 
                            echo $_SESSION['lname']= $row_settings['lname'];
                            echo "&nbsp; &nbsp; &nbsp;เพศ";
                            echo $row_settings['gender'];
                            $staff = $row_settings['staff'];
                            $date1=date_create(date("Y-m-d"));
                            $date2=date_create($row_settings['birthday']);
                            $diff=date_diff($date2,$date1);
                            $_SESSION['age'] = $diff->format("%Y");
                            echo "&nbsp; &nbsp;อายุ&nbsp; ";
                            echo $diff->format("%Y ปี %m เดือน %d วัน");
                            echo "</h3>";
                            if($row_settings['gender']=='หญิง' AND ($_SESSION['age']>=10 AND $_SESSION['age'] <=55))
                            {
                                //get pregdate for fup
                                $pregmonth = $row_settings['fup'];
                            
                                //check for pregnancy period
                                if(!empty($pregmonth))
                                {
                                    $oldrid = $rid[0]-1;
                                    $ptin2 = mysqli_query($linkopdx, "select * from $pttable where  id = '$oldrid' ");
                                    while($olddate = mysqli_fetch_array($ptin2))
                                    {
                                        $date2=date_create($olddate['date']);
                                        $diff = date_diff($date2,$date1);
                                        $dmp = $diff->format("%m");
                                        if(($dmp+$pregmonth)>10) $pregmonth = 0;
                                        else $pregmonth = $dmp+$pregmonth;
                                    }
                                }
                                echo "<input type=\"radio\" name=\"preg\" class=\"required\" value=\"1\" ";
                                if(($preg == 1) OR ($pregmonth > 0)){echo "checked"; $preg=1;}
                                echo ">ตั้งครรภ์";
                                if($pregmonth!=0) echo "<sup>".$pregmonth."</sup>";
                                echo "<input type=\"radio\" name=\"preg\" class=\"required\" value=\"0\" ";
                                if((empty($preg)) OR ($preg == 0) ) echo "checked";
                                echo ">ไม่ตั้งครรภ์";
                            }
                            else
                            {
                                echo "<input type='hidden' name='preg' value='0'>";
                            }
                        }
                    ?>
                    <br>
                    </div>
                    <hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
                    <br>
                    <div style="text-align: center;">ปรึกษาเรื่อง : <input type="text" name="csf" size="60" value="<?php echo $ccp;?>" autofocus id="firstfocus"></div>
                    <hr style="width: 80%; height: 2px;">
                    <div style="text-align: center;">
                    <h4>ตรวจร่างกายเบื้องต้น</h4>
                    น้ำหนัก: <input maxlength="5" size="5" name="weight" value="<?php echo $weight;?>" > kg.  
                    &nbsp; &nbsp; &nbsp;
                    <?php
                    if($_SESSION['age']<=20 or $_SESSION['age']>=50)
                    {
                    ?>
                    ส่วนสูง: <input maxlength="5" size="5" name="height" value="<?php echo $height;?>" > cm.  
                    &nbsp; &nbsp; &nbsp;
                    <?php
                    }
                    else
                    {
                    $hin = mysqli_fetch_array(mysqli_query($linkopd, "SELECT height FROM patient_id where id='$id' "));
                        echo "<input type=hidden name=height value=".$hin[0].">";
                    }
                    ?>
                    Temp <input maxlength="4" size="5" name="temp" value="<?php echo $temp;?>" > C.
                    &nbsp; &nbsp; &nbsp;
                    หายใจ <input maxlength="4" size="5" name="rr" value ="16" value="<?php echo $rr;?>" > / นาฑี 
                    <br><br>
                    BP  Sys <input maxlength="3" size="4" name="bpsys" value="<?php echo $bpsys;?>" > / Dia <input  maxlength="3" size="4" name="bpdia" value="<?php echo $bpdia;?>"  > mmHg.
                    &nbsp; &nbsp; &nbsp; 
                    HR <input maxlength="3" size="4" name="hr" value="<?php echo $hr;?>"  > BPM.
                    <br>
                    </div>
                    <hr style="width: 80%; height: 2px;"><br>
                    <div style="text-align:center;"> <input type="checkbox" name="medc" value="1" <?php if($medcert != 0) echo "checked";?>> ใบรับรองแพทย์ + ใบเสร็จรับเงิน <input type=radio name=medcert value=1  <?php if($medcert == 1) echo "checked";?>>ตรวจโรคสมัครงาน <input type=radio name=medcert value=2 <?php if($medcert != 1 ) echo "checked";?>>ยืนยันตรวจจริง
                    <hr style="width: 80%; height: 2px;"><br>
                    <input type="radio" name="policy" value="2" <?php if($pricepolicy == 2 or $pricepolicy == "" or $pricepolicy == 0) echo "checked";?>>ตรวจโรค
                    <input type="radio" name="policy" value="3" <?php if($pricepolicy == 3 ) echo "checked";?>>ทำหัตการ
                    <input type="radio" name="policy" value="4" <?php if($pricepolicy == 4 ) echo "checked";?>>มาตามนัด</div>
                </td></tr>
                <tr><td><br><div style="text-align: center;"><input name="register" value="บันทึก" type="submit"></div>
                    <?php 
                    {
                    // check for reenter or update
                    $PID = $_SESSION['patdesk'];
                    //check id at any point of service..
                    $result1 = mysqli_query($link, "SELECT ID FROM pt_to_doc WHERE ID = $PID");
                    $num = mysqli_num_rows($result1);
                    if($num != 0)
                    {
                    goto checkfound;
                    }
                    $result1 = mysqli_query($link, "SELECT id FROM pt_to_obs WHERE id = $PID");
                    $num = mysqli_num_rows($result1);
                    if($num != 0)
                    {
                    goto checkfound;
                    }
                    $result1 = mysqli_query($link, "SELECT id FROM pt_to_drug WHERE id = $PID");
                    $num = mysqli_num_rows($result1);
                    if($num != 0) 
                    {
                    goto checkfound;
                    }
                    //
                    if($ptdoc == 1)
                    {
                    echo "<div style='text-align: center;'><input name='register' value='ส่งเข้าห้องตรวจ' type='submit'></div>";
                    }
                    checkfound:
                    }
                    ?>
                </td></tr>
            </table><br>
    </form>
</td></tr>
</table><br>
</body></html>