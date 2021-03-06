<?php 
include '../../config/dbc.php';
page_protect();
$msg = array();

$sql="CREATE TABLE IF NOT EXISTS `appointment` (
  `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time DEFAULT NULL,
  `ptid` int(11) NOT NULL,
  `FuBy` int(11) DEFAULT NULL,
  `FuFor` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ddx` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recorddate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
mysqli_query($link, $sql);

$sql_select =  mysqli_query($link, "SELECT * FROM `initdb` WHERE `refname`='appointment' ORDER BY `id`" );
$tableversion = mysqli_num_rows($sql_select);
if(!$tableversion)
{
    $sql  = "INSERT INTO `initdb` (`refname`, `version`) VALUES (\'appointment\', \'1\')";
    mysqli_query($link, $sql);
}

$id = $_SESSION['patdesk'];

$Patient_id = $id;
include '../../libs/opdxconnection.php';

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;

$pin = mysqli_query($linkopdx, "select MAX(id) from $pttable");
$rid = mysqli_fetch_array($pin);
$rid[0];
$pin = mysqli_query($linkopdx, "select `inform` from $pttable  WHERE `id` = '$rid[0]' ");
$inf = mysqli_fetch_array($pin);
$finject = $inf[0];
$recorddate = date('Y-m-d');
$rctime = date("h:i:s");
if($_POST['set']=="SET")
{
    if($_POST['calfup']== 1)
    {
        if (strstr($finject, '#'))
        {
            $finject = substr($finject, 0, strpos($finject, "#"));
            $msg[] = "รายการนัดทั้งหมดถูกยกเลิกแล้ว";
        }
        $finject = mysqli_real_escape_string($linkopdx, $finject);

        mysqli_query($linkopdx, "UPDATE $pttable SET `inform` = '$finject' WHERE `id` = '$rid[0]' ");
        mysqli_query($link, "DELETE from appointment WHERE  `recorddate` = '$recorddate' and `ptid` = '$id'");
    }
    else
    {
        if($_POST['fupinj3']==5){//นัด ฉีดยา ต่อเนื่อง
        $finject = $finject ."# นัด ฉีดยา ต่อเนื่อง 5 วัน #";
        $flup = 5;
        }
        if($_POST['fupinj3']==4){//นัด ฉีดยา ต่อเนื่อง
        $finject = $finject ."# นัด ฉีดยา ต่อเนื่อง 4 วัน #";
        $flup = 4;
        }
        if($_POST['fupinj3']==3){//นัด ฉีดยา ต่อเนื่อง
        $finject = $finject ."# นัด ฉีดยา ต่อเนื่อง 3 วัน #";
        $flup = 3;
        }
        if($_POST['fupinj3']==2){//นัด ฉีดยา ต่อเนื่อง
        $finject = $finject ."# นัด ฉีดยา ต่อเนื่อง 2 วัน #";
        $flup = 2;
        }
        if($_POST['fupinj3']==1){//นัด ฉีดยา ต่อเนื่อง
        $finject = $finject ."# นัด ฉีดยา ต่อเนื่อง 1 วัน #";
        $flup = 1;
        }
        if($_POST['fupinj3time']==3){//นัด ฉีดยา ต่อเนื่อง
        $finject = $finject ."# นัดฉีดยา ทุก 24 ชม #";
        }
        if($_POST['fupinj3time']==2){//นัด ฉีดยา ต่อเนื่อง
        $finject = $finject ."# นัดฉีดยา ทุก 12 ชม #";
        }
        if($_POST['fupinj3time']==1){//นัด ฉีดยา ต่อเนื่อง
        $finject = $finject ."# นัดฉีดยา ทุก 8 ชม #";
        }
        if($flup)
        {
            $datetime = new DateTime();
            //echo $datetime->format('Y-m-d');
            for($i=1;$i<=$flup;$i++){
            $datetime->add(new DateInterval('P1D'));
            $hinjd = $datetime->format('d');
            $hinjm = $datetime->format('m');
            $hinjy = $datetime->format('Y');
            $sql_insert = "INSERT INTO `appointment` 
                                    (`id`, `date`, `time`, `ptid`, `FuBy`, `FuFor`, `ddx`, `recorddate`)
                            VALUES 
                                    (NULL, '$hinjy"."-".$hinjm."-"."$hinjd', '$rctime', '$Patient_id', '$_SESSION[staff_id]', '$finject', '$ddx', '$recorddate');";
            mysqli_query($link, $sql_insert);
            }
        }
        if($_POST['fup']==90)
        {
            $datetime = new DateTime();
            //echo $datetime->format('Y-m-d');
            $datetime->add(new DateInterval('P90D'));
            $hinjd = $datetime->format('d');
            $hinjm = $datetime->format('m');
            $hinjy = $datetime->format('Y');
            //echo $hinjd .$hinjm.$hinjy;
            $sye = $hinjy+543;
            switch ($hinjm)
            {
                case 1: $m =  "มกราคม"; break;
                case 2: $m =  "กุมภาพันธ์";break;
                case 3: $m =  "มีนาคม";break;
                case 4: $m =  "เมษายน";break;
                case 5:$m =  "พฤษภาคม";break;
                case 6:$m =  "มิถุนายน";break;
                case 7:$m =  "กรกฎาคม";break;
                case 8:$m =  "สิงหาคม";break;
                case 9:$m =  "กันยายน";break;
                case 10:$m =  "ตุลาคม";break;
                case 11:$m =  "พฤศจิกายน";break;
                case 12:$m =  "ธันวาคม";break;
            } 
            $finject = $finject ."# นัดติดตาม วันที่ ".$hinjd." ".$m." ".$sye." #";
            $flcase = 90;
        }
        if($_POST['fup']==60)//นัด 2 เดือน 60 วัน
        {
            $datetime = new DateTime();
            //echo $datetime->format('Y-m-d');
            $datetime->add(new DateInterval('P60D'));
            $hinjd = $datetime->format('d');
            $hinjm = $datetime->format('m');
            $hinjy = $datetime->format('Y');
            //echo $hinjd .$hinjm.$hinjy;
            $sye = $hinjy+543;
            switch ($hinjm)
            {
                case 1: $m =  "มกราคม"; break;
                case 2: $m =  "กุมภาพันธ์";break;
                case 3: $m =  "มีนาคม";break;
                case 4: $m =  "เมษายน";break;
                case 5:$m =  "พฤษภาคม";break;
                case 6:$m =  "มิถุนายน";break;
                case 7:$m =  "กรกฎาคม";break;
                case 8:$m =  "สิงหาคม";break;
                case 9:$m =  "กันยายน";break;
                case 10:$m =  "ตุลาคม";break;
                case 11:$m =  "พฤศจิกายน";break;
                case 12:$m =  "ธันวาคม";break;
            } 
            $finject = $finject ."# นัดติดตาม วันที่ ".$hinjd." ".$m." ".$sye." #";
            $flcase = 60;
        }
        if($_POST['fup']==30)//นัด ฉีดยา ต่อเนื่อง
        {
            $datetime = new DateTime();
            //echo $datetime->format('Y-m-d');
            $datetime->add(new DateInterval('P30D'));
            $hinjd = $datetime->format('d');
            $hinjm = $datetime->format('m');
            $hinjy = $datetime->format('Y');
            //echo $hinjd .$hinjm.$hinjy;
            $sye = $hinjy+543;
            switch ($hinjm)
            {
                case 1: $m =  "มกราคม"; break;
                case 2: $m =  "กุมภาพันธ์";break;
                case 3: $m =  "มีนาคม";break;
                case 4: $m =  "เมษายน";break;
                case 5:$m =  "พฤษภาคม";break;
                case 6:$m =  "มิถุนายน";break;
                case 7:$m =  "กรกฎาคม";break;
                case 8:$m =  "สิงหาคม";break;
                case 9:$m =  "กันยายน";break;
                case 10:$m =  "ตุลาคม";break;
                case 11:$m =  "พฤศจิกายน";break;
                case 12:$m =  "ธันวาคม";break;
            } 
            $finject = $finject ."# นัดติดตามอีก 30 วัน วันที่ ".$hinjd." ".$m." ".$sye." #";
            $flcase =30;
        }
        if($_POST['fup']==21)//นัด ฉีดยา ต่อเนื่อง
        {
            $datetime = new DateTime();
            //echo $datetime->format('Y-m-d');
            $datetime->add(new DateInterval('P21D'));
            $hinjd = $datetime->format('d');
            $hinjm = $datetime->format('m');
            $hinjy = $datetime->format('Y');
            //echo $hinjd .$hinjm.$hinjy;
            $sye = $hinjy+543;
            switch ($hinjm)
            {
                case 1: $m =  "มกราคม"; break;
                case 2: $m =  "กุมภาพันธ์";break;
                case 3: $m =  "มีนาคม";break;
                case 4: $m =  "เมษายน";break;
                case 5:$m =  "พฤษภาคม";break;
                case 6:$m =  "มิถุนายน";break;
                case 7:$m =  "กรกฎาคม";break;
                case 8:$m =  "สิงหาคม";break;
                case 9:$m =  "กันยายน";break;
                case 10:$m =  "ตุลาคม";break;
                case 11:$m =  "พฤศจิกายน";break;
                case 12:$m =  "ธันวาคม";break;
            } 
            $finject = $finject ."# นัดติดตามอีก 3 สัปดาห์ วันที่ ".$hinjd." ".$m." ".$sye." #";
            $flcase = 21;
        }
        if($_POST['fup']==14)//นัด ฉีดยา ต่อเนื่อง
        {
            $datetime = new DateTime();
            //echo $datetime->format('Y-m-d');
            $datetime->add(new DateInterval('P14D'));
            $hinjd = $datetime->format('d');
            $hinjm = $datetime->format('m');
            $hinjy = $datetime->format('Y');
            //echo $hinjd .$hinjm.$hinjy;
            $sye = $hinjy+543;
            switch ($hinjm)
            {
                case 1: $m =  "มกราคม"; break;
                case 2: $m =  "กุมภาพันธ์";break;
                case 3: $m =  "มีนาคม";break;
                case 4: $m =  "เมษายน";break;
                case 5:$m =  "พฤษภาคม";break;
                case 6:$m =  "มิถุนายน";break;
                case 7:$m =  "กรกฎาคม";break;
                case 8:$m =  "สิงหาคม";break;
                case 9:$m =  "กันยายน";break;
                case 10:$m =  "ตุลาคม";break;
                case 11:$m =  "พฤศจิกายน";break;
                case 12:$m =  "ธันวาคม";break;
            } 
            $finject = $finject ."# นัดติดตามอีก 2 สัปดาห์ วันที่ ".$hinjd." ".$m." ".$sye." #";
            $flcase = 14;
        }
        if($_POST['fup']==7)//นัด ฉีดยา ต่อเนื่อง
        {
            $datetime = new DateTime();
            //echo $datetime->format('Y-m-d');
            $datetime->add(new DateInterval('P7D'));
            $hinjd = $datetime->format('d');
            $hinjm = $datetime->format('m');
            $hinjy = $datetime->format('Y');
            //echo $hinjd .$hinjm.$hinjy;
            $sye = $hinjy+543;
            switch ($hinjm)
            {
                case 1: $m =  "มกราคม"; break;
                case 2: $m =  "กุมภาพันธ์";break;
                case 3: $m =  "มีนาคม";break;
                case 4: $m =  "เมษายน";break;
                case 5:$m =  "พฤษภาคม";break;
                case 6:$m =  "มิถุนายน";break;
                case 7:$m =  "กรกฎาคม";break;
                case 8:$m =  "สิงหาคม";break;
                case 9:$m =  "กันยายน";break;
                case 10:$m =  "ตุลาคม";break;
                case 11:$m =  "พฤศจิกายน";break;
                case 12:$m =  "ธันวาคม";break;
            } 
            $finject = $finject ."# นัดติดตามอีก 7 วัน วันที่ ".$hinjd." ".$m." ".$sye." #";
            $flcase = 7;
        }
        if($_POST['fup']==5)//นัด ฉีดยา ต่อเนื่อง
        {
            $datetime = new DateTime();
            //echo $datetime->format('Y-m-d');
            $datetime->add(new DateInterval('P5D'));
            $hinjd = $datetime->format('d');
            $hinjm = $datetime->format('m');
            $hinjy = $datetime->format('Y');
            //echo $hinjd .$hinjm.$hinjy;
            $sye = $hinjy+543;
            switch ($hinjm)
            {
                case 1: $m =  "มกราคม"; break;
                case 2: $m =  "กุมภาพันธ์";break;
                case 3: $m =  "มีนาคม";break;
                case 4: $m =  "เมษายน";break;
                case 5:$m =  "พฤษภาคม";break;
                case 6:$m =  "มิถุนายน";break;
                case 7:$m =  "กรกฎาคม";break;
                case 8:$m =  "สิงหาคม";break;
                case 9:$m =  "กันยายน";break;
                case 10:$m =  "ตุลาคม";break;
                case 11:$m =  "พฤศจิกายน";break;
                case 12:$m =  "ธันวาคม";break;
            } 
            $finject = $finject ."# นัดติดตามอีก 5 วัน วันที่ ".$hinjd." ".$m." ".$sye." #";
            $flcase =5;
        }
        if($_POST['fup']==3)//นัด ฉีดยา ต่อเนื่อง
        {
            $datetime = new DateTime();
            //echo $datetime->format('Y-m-d');
            $datetime->add(new DateInterval('P3D'));
            $hinjd = $datetime->format('d');
            $hinjm = $datetime->format('m');
            $hinjy = $datetime->format('Y');
            //echo $hinjd .$hinjm.$hinjy;
            $sye = $hinjy+543;
            switch ($hinjm)
            {
                case 1: $m =  "มกราคม"; break;
                case 2: $m =  "กุมภาพันธ์";break;
                case 3: $m =  "มีนาคม";break;
                case 4: $m =  "เมษายน";break;
                case 5:$m =  "พฤษภาคม";break;
                case 6:$m =  "มิถุนายน";break;
                case 7:$m =  "กรกฎาคม";break;
                case 8:$m =  "สิงหาคม";break;
                case 9:$m =  "กันยายน";break;
                case 10:$m =  "ตุลาคม";break;
                case 11:$m =  "พฤศจิกายน";break;
                case 12:$m =  "ธันวาคม";break;
            } 
            $finject = $finject ."# นัดติดตามอีก 3 วัน วันที่ ".$hinjd." ".$m." ".$sye." #";
            $flcase =3;
        }
        if($_POST['fup']==1){//นัด ฉีดยา ต่อเนื่อง
            $finject = $finject ."# นัดติดตาม วันพรุ่งนี้ #";
            $datetime = new DateTime();
            //echo $datetime->format('Y-m-d');
            $datetime->add(new DateInterval('P1D'));
            $hinjd = $datetime->format('d');
            $hinjm = $datetime->format('m');
            $hinjy = $datetime->format('Y');
            $flcase = 1;
            //echo $hinjd .$hinjm.$hinjy;
        }
        //
        if(!empty($_POST['nfolday']))
        {
            $nd = $_POST['nfolday'];
            $datetime = new DateTime();
            //echo $datetime->format('Y-m-d');
            $datetime->add(new DateInterval('P'.$nd.'D'));
            $hinjd = $datetime->format('d');
            $hinjm = $datetime->format('m');
            $hinjy = $datetime->format('Y');
            //echo $hinjd .$hinjm.$hinjy;
            $sye = $hinjy+543;
            switch ($hinjm)
            {
                case 1: $m =  "มกราคม"; break;
                case 2: $m =  "กุมภาพันธ์";break;
                case 3: $m =  "มีนาคม";break;
                case 4: $m =  "เมษายน";break;
                case 5:$m =  "พฤษภาคม";break;
                case 6:$m =  "มิถุนายน";break;
                case 7:$m =  "กรกฎาคม";break;
                case 8:$m =  "สิงหาคม";break;
                case 9:$m =  "กันยายน";break;
                case 10:$m =  "ตุลาคม";break;
                case 11:$m =  "พฤศจิกายน";break;
                case 12:$m =  "ธันวาคม";break;
            } 
            $finject = $finject ."# นัดติดตามอีก ".$nd." วัน วันที่ ".$hinjd." ".$m." ".$sye." #";
            $flcase = $nd;
        } 

        $fdate = $_POST['fupdate'];
        //echo $fdate->format('Y-m-d');
        $datetime = new DateTime();
        $datetime = $datetime->format('m/d/Y');
        $sd = substr($fdate, 3, -5);
        $sm = substr($fdate, 0, 2);
        $sy = substr($fdate, -4); 
        //echo $hinjd .$hinjm.$hinjy;
        $sye = $sy+543;
        $date = new DateTime($sy.'-'.$sm.'-'.$sd);
        $date = $date->format('m/d/Y');
        if($date !== $datetime)
        {
            switch ($sm)
            {
                case 1: $m =  "มกราคม"; break;
                case 2: $m =  "กุมภาพันธ์";break;
                case 3: $m =  "มีนาคม";break;
                case 4: $m =  "เมษายน";break;
                case 5:$m =  "พฤษภาคม";break;
                case 6:$m =  "มิถุนายน";break;
                case 7:$m =  "กรกฎาคม";break;
                case 8:$m =  "สิงหาคม";break;
                case 9:$m =  "กันยายน";break;
                case 10:$m =  "ตุลาคม";break;
                case 11:$m =  "พฤศจิกายน";break;
                case 12:$m =  "ธันวาคม";break;
            } 
            $date1=date_create($date);
            $date2=date_create($datetime);
            $diff=date_diff($date2,$date1);
            $diff1 = $diff->format("%a");
            $hinjd = $sd;
            $hinjm = $sm;
            $hinjy = $sy;

            $finject = $finject ."# นัดติดตามอีก ".$diff1." วัน ในวันที่ ".$sd." ".$m." ".$sye." #";
            $flcase = $diff1;
        }
        
        if($_POST['lab']==1)//นัด ฉีดยา ต่อเนื่อง
        $finject = $finject ."# นัด ตรวจ Lab งดอาหาร 12 ชม กินยาความดันและน้ำเปล่าได้ก่อนมา #";
        if($_POST['lab']==2)//นัด ฉีดยา ต่อเนื่อง
        $finject = $finject ."# นัด ตรวจ Lab งดอาหาร 6 ชม กินยาความดันและน้ำเปล่าได้ก่อนมา #";
        if($_POST['lab']==3)//นัด ฉีดยา ต่อเนื่อง
        $finject = $finject ."# นัด ตรวจ Lab ไม่ต้องงดอาหาร #";
        
        if($flcase){
            $sql_insert = "INSERT INTO `appointment` 
                                    (`id`, `date`, `time`, `ptid`, `FuBy`, `FuFor`, `ddx`, `recorddate`)
                            VALUES 
                                    (NULL, '$hinjy"."-".$hinjm."-"."$hinjd', '$rctime', '$Patient_id', '$_SESSION[staff_id]', '$finject', '$ddx', '$recorddate');";
            mysqli_query($link, $sql_insert);
        }
        //<input type=checkbox name=fupin1 value=1>นัด ฉีดยาคุมกำเนิด
        if($_POST['fupin1'] == 3)
        {
            $datetime = new DateTime();
            //echo $datetime->format('Y-m-d');
            $datetime->add(new DateInterval('P84D'));
            $hinjd = $datetime->format('d');
            $hinjm = $datetime->format('m');
            $hinjy = $datetime->format('Y');
            //echo $hinjd .$hinjm.$hinjy;
            $sql_insert = "INSERT INTO `appointment` 
                                    (`id`, `date`, `time`, `ptid`, `FuBy`, `FuFor`, `ddx`, `recorddate`)
                            VALUES 
                                    (NULL, '$hinjy"."-".$hinjm."-"."$hinjd', '$rctime', '$Patient_id', '$_SESSION[staff_id]', 'ฉีดยาคุมกำเนิด', 'คุมกำเนิด', '$recorddate');";
            mysqli_query($link, $sql_insert);
            $sye = $hinjy+543;
            switch ($hinjm)
            {
                case 1: $m =  "มกราคม"; break;
                case 2: $m =  "กุมภาพันธ์";break;
                case 3: $m =  "มีนาคม";break;
                case 4: $m =  "เมษายน";break;
                case 5:$m =  "พฤษภาคม";break;
                case 6:$m =  "มิถุนายน";break;
                case 7:$m =  "กรกฎาคม";break;
                case 8:$m =  "สิงหาคม";break;
                case 9:$m =  "กันยายน";break;
                case 10:$m =  "ตุลาคม";break;
                case 11:$m =  "พฤศจิกายน";break;
                case 12:$m =  "ธันวาคม";break;
            } 
            $finject = $finject ."# นัด ฉีดยาคุมกำเนิด วันที่ ".$hinjd." ".$m." ".$sye." #";
        }
        //<input type=checkbox name=fupin1 value=4>นัด ฉีดยาคุมกำเนิด
        if($_POST['fupin1'] == 1)
        {
            $datetime = new DateTime();
            //echo $datetime->format('Y-m-d');
            $datetime->add(new DateInterval('P28D'));
            $hinjd = $datetime->format('d');
            $hinjm = $datetime->format('m');
            $hinjy = $datetime->format('Y');
            //echo $hinjd .$hinjm.$hinjy;
            $sql_insert = "INSERT INTO `appointment` 
                                    (`id`, `date`, `time`, `ptid`, `FuBy`, `FuFor`, `ddx`, `recorddate`)
                            VALUES 
                                    (NULL, '$hinjy"."-".$hinjm."-"."$hinjd', '$rctime', '$Patient_id', '$_SESSION[staff_id]', 'ฉีดยาคุมกำเนิด', 'คุมกำเนิด', '$recorddate');";
            mysqli_query($link, $sql_insert);
            $sye = $hinjy+543;
            switch ($hinjm)
            {
                case 1: $m =  "มกราคม"; break;
                case 2: $m =  "กุมภาพันธ์";break;
                case 3: $m =  "มีนาคม";break;
                case 4: $m =  "เมษายน";break;
                case 5:$m =  "พฤษภาคม";break;
                case 6:$m =  "มิถุนายน";break;
                case 7:$m =  "กรกฎาคม";break;
                case 8:$m =  "สิงหาคม";break;
                case 9:$m =  "กันยายน";break;
                case 10:$m =  "ตุลาคม";break;
                case 11:$m =  "พฤศจิกายน";break;
                case 12:$m =  "ธันวาคม";break;
            } 
            $finject = $finject ."# นัด ฉีดยาคุมกำเนิด วันที่ ".$hinjd." ".$m." ".$sye." หรือกำลังมีประจำเดือน #";
        }
        //<input type=checkbox name=fupin2 value=2>นัด Vaccine TT
        if($_POST['fupin2']==2){//นัด ฉีดยา ต่อเนื่อง
            $finject = $finject ."# นัด Vaccine TT บาดทะยัก อีก 1 และ 6 เดือน #";
            $datetime = new DateTime();
            //echo $datetime->format('Y-m-d');
            $datetime->add(new DateInterval('P1M'));
            $hinjd = $datetime->format('d');
            $hinjm = $datetime->format('m');
            $hinjy = $datetime->format('Y');
            //echo $hinjd .$hinjm.$hinjy;
            $sql_insert = "INSERT INTO `appointment` 
                                    (`id`, `date`, `time`, `ptid`, `FuBy`, `FuFor`, `ddx`, `recorddate`)
                            VALUES 
                                    (NULL, '$hinjy"."-".$hinjm."-"."$hinjd', '$rctime', '$Patient_id', '$_SESSION[staff_id]', 'Vaccine TT บาดทะยัก', 'Vaccination', '$recorddate');";
            mysqli_query($link, $sql_insert);
            //echo $datetime->format('Y-m-d');
            $datetime->add(new DateInterval('P6M'));
            $hinjd = $datetime->format('d');
            $hinjm = $datetime->format('m');
            $hinjy = $datetime->format('Y');
            //echo $hinjd .$hinjm.$hinjy;
            $sql_insert = "INSERT INTO `appointment` 
                                    (`id`, `date`, `time`, `ptid`, `FuBy`, `FuFor`, `ddx`, `recorddate`)
                            VALUES 
                                    (NULL, '$hinjy"."-".$hinjm."-"."$hinjd', '$rctime', '$Patient_id', '$_SESSION[staff_id]', 'Vaccine TT บาดทะยัก', 'Vaccination', '$recorddate');";
            mysqli_query($link, $sql_insert);
        }
        if($_POST['fupin3']==3){//นัด ฉีดยา ต่อเนื่อง
            $finject = $finject ."# นัด Vaccine พิษสุนัขบ้า นับจากวันนี้#";
            $datetime = new DateTime();
            //echo $datetime->format('Y-m-d');
            $datetime->add(new DateInterval('P7D'));
            $hinjd = $datetime->format('d');
            $hinjm = $datetime->format('m');
            $hinjy = $datetime->format('Y');
            //echo $hinjd .$hinjm.$hinjy;
            $sql_insert = "INSERT INTO `appointment` 
                                    (`id`, `date`, `time`, `ptid`, `FuBy`, `FuFor`, `ddx`, `recorddate`)
                            VALUES 
                                    (NULL, '$hinjy"."-".$hinjm."-"."$hinjd', '$rctime', '$Patient_id', '$_SESSION[staff_id]', 'Vaccine พิษสุนัขบ้า', 'Vaccination', '$recorddate');";
            mysqli_query($link, $sql_insert);
            //echo $datetime->format('Y-m-d');
            $datetime->add(new DateInterval('P21D'));
            $hinjd = $datetime->format('d');
            $hinjm = $datetime->format('m');
            $hinjy = $datetime->format('Y');
            //echo $hinjd .$hinjm.$hinjy;
            $sql_insert = "INSERT INTO `appointment` 
                                    (`id`, `date`, `time`, `ptid`, `FuBy`, `FuFor`, `ddx`, `recorddate`)
                            VALUES 
                                    (NULL, '$hinjy"."-".$hinjm."-"."$hinjd', '$rctime', '$Patient_id', '$_SESSION[staff_id]', 'Vaccine พิษสุนัขบ้า', 'Vaccination', '$recorddate');";
            mysqli_query($link, $sql_insert);
         }

        //<input type=checkbox name=fupin3 value=3>นัด Vaccine พิษสุนัขบ้า
        
        $finject = mysqli_real_escape_string($linkopdx, $finject);
        mysqli_query($linkopdx, "UPDATE $pttable SET `inform` = '$finject' WHERE `id` = '$rid[0]' ");
        // go on to other step
        unset($_SESSION['medcert']);
        header("Location: prescriptconfirm.php");   
    }
}

$title = "::My Counter::";
include '../../main/header.php';
?>
<script>
$(function() {

$( "#datepicker" ).datepicker({ minDate: 0, maxDate: "+1Y " });
});
</script>
</head>
<body >
<div style="text-align: center;" class="myaccount">
<h2 class="titlehdr"> ข้อมูลการนัดผู้ป่วย </h2>
<h3>ชื่อ: &nbsp; 
<?php
while ($row_settings = mysqli_fetch_array($ptin))
{
    echo $row_settings['fname'];
    echo "&nbsp; &nbsp; &nbsp;"; 
    echo $row_settings['lname'];
    echo "&nbsp; &nbsp; &nbsp;เพศ";
    echo $row_settings['gender'];

    $date1=date_create(date("Y-m-d"));
    $date2=date_create($row_settings['birthday']);
    $diff=date_diff($date2,$date1);
    echo "&nbsp; &nbsp;อายุ&nbsp; ";
    echo $diff->format("%Y ปี %m เดือน %d วัน");
    echo "</h3>";
    echo $finject;
    if(!empty($msg))
    {
      echo "<div class=\"msg\">";
      foreach ($msg as $m) {echo "* $m <br>";}
      echo "</div>";
    }

}
/*
$date = new DateTime('2000-01-01');
$date->add(new DateInterval('P7Y5M4DT4H3M2S'));
echo $date->format('Y-m-d H:i:s') . "\n";
*/
/*
$datetime = new DateTime();
echo $datetime->format('Y-m-d');
$datetime->add(new DateInterval('P7Y5M4DT4H3M2S'));
echo $datetime->format('Y-m-d');
*/
?>
<form method="post" action="appointment.php" name="regForm" id="regForm">
<br>
<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
<input type=radio name=fupinj3 id=follow1 value=5><label for='follow1'>นัด ฉีดยา ต่อเนื่องอีก 5 วัน</label>
<input type=radio name=fupinj3 id=follow2 value=4><label for='follow2'>นัด ฉีดยา ต่อเนื่องอีก 4 วัน</label>
<input type=radio name=fupinj3 id=follow3 value=3><label for='follow3'>นัด ฉีดยา ต่อเนื่องอีก 3 วัน</label>
<input type=radio name=fupinj3 id=follow4 value=2><label for='follow4'>นัด ฉีดยา ต่อเนื่องอีก 2 วัน</label>
<input type=radio name=fupinj3 id=follow5 value=1><label for='follow5'>นัด ฉีดยา ต่อเนื่องอีก 1 วัน</label>
<br>
<input type=radio name=fupinj3time id=follow6 value=3><label for='follow6'>นัดฉีดยา ทุก 24 ชม.</label>
<input type=radio name=fupinj3time id=follow7 value=2><label for='follow7'>นัดฉีดยา ทุก 12 ชม.</label>
<input type=radio name=fupinj3time id=follow8 value=1><label for='follow8'>นัดฉีดยา ทุก 8 ชม.</label>
<input type=radio name=fupinj3time id=follow9 value=0><label for='follow9'>นัด ไม่จำกัดเวลา </label>


<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
<input type=radio name=fup id=follow10 value=90><label for='follow10'>นัด 3 เดือน</label>
<input type=radio name=fup id=follow11 value=60><label for='follow11'>นัด 2 เดือน</label>
<input type=radio name=fup id=follow12 value=30><label for='follow12'>นัด 1 เดือน</label>
<input type=radio name=fup id=follow13 value=21><label for='follow13'>นัด 3 สัปดาห์</label>
<input type=radio name=fup id=follow14 value=14><label for='follow14'>นัด 2 สัปดาห์</label>
<input type=radio name=fup id=follow15 value=7><label for='follow15'>นัด 1 สัปดาห์</label>
<input type=radio name=fup id=follow16 value=5><label for='follow16'>นัด 5 วัน</label>
<input type=radio name=fup id=follow17 value=3><label for='follow17'>นัด 3 วัน</label>
<input type=radio name=fup id=follow18 value=1><label for='follow18'>นัด 1 วัน</label>

<h4>นัด <input type="number" min=0 name="nfolday">วัน ***---***  นัดวันที่ : <input name=fupdate type="text" id="datepicker" value="<?php $duedate=date_create($duedate);$duedate=date_format($duedate,"m/d/Y");echo $duedate;?>">
<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
<input type=checkbox name=lab id=follow19 value=1><label for='follow19'>นัด ตรวจ Lab งดอาหาร 12 ชม</label>
<input type=checkbox name=lab id=follow20 value=2><label for='follow20'>นัด ตรวจ Lab งดอาหาร 6 ชม</label>
<input type=checkbox name=lab id=follow21 value=3><label for='follow21'>นัด ตรวจ Lab ไม่ต้องงดอาหาร</label>

<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
<input type=checkbox name=fupin1 id=follow22 value=3><label for='follow22'>นัด ฉีดยาคุมกำเนิด 3 เดือน</label>
<input type=checkbox name=fupin1 id=follow23 value=1><label for='follow23'>นัด ฉีดยาคุมกำเนิด 1 เดือน</label>
<input type=checkbox name=fupin2 id=follow24 value=2><label for='follow24'>นัด Vaccine TT</label>
<input type=checkbox name=fupin3 id=follow25 value=3><label for='follow25'>นัด Vaccine พิษสุนัขบ้า</label>

<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
<input type=checkbox name=calfup id=follow26 value=1><label for='follow26'>ยกเลิกนัด</label>

<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">

<input type=submit name=set value="SET" id="firstfocus">
</h4>
</form>
</div><br>
</body></html>
