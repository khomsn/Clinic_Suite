<?php
echo "<div class='page'>";
$pin = mysqli_query($linkopdx, "select * from $pttable WHERE id= '$_SESSION[rid]' ");
while ($row = mysqli_fetch_array($pin))
{
    $date = new DateTime($row['date']);
    $sd = $date->format("d");
    $sm = $date->format("m");
    $sy = $date->format("Y");
    $hms = $date->format("G:i:s");
    $bsy = $sy +543;
    $clinic = $row['clinic'];
}
echo "<div class='kheader'>";
if(!$kp_ad)
{
    if("$clinic" != "$klinic")
        echo "<p class='mainheader'>".$clinic."</p>";
    else
    {
        echo "<p class='mainheader'>".$klinic."</p>";
        echo "<p class='comment'>".$kladdress." Tel:".$kltel."</p>";        
    }
}
else
{
    echo "<p class='mainheader'>".$klinic."</p>";
    echo "<p class='comment'>".$kladdress." Tel:".$kltel."</p>";
}
echo "<p class='ptinfo'><h4 class='titlehdr2'>ข้อมูลการตรวจผู้ป่วย ณ วันที่ ";  
echo $sd." "; 
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
echo " พ.ศ.".$bsy."  เวลา ".$hms." น.";
echo "</h4></p>";
echo "<p class='mainheader'>ชื่อ:&nbsp;";
while ($row_settings = mysqli_fetch_array($ptin))
{
echo $row_settings['prefix'];
echo " ";
echo $row_settings['fname'];
echo "&nbsp;&nbsp;"; 
echo $row_settings['lname'];
echo "&nbsp; &nbsp; &nbsp;เพศ";
echo $row_settings['gender'];
//$date1=date_create(date("Y-m-d"));
$date2=date_create($row_settings['birthday']);
$diff=date_diff($date2,$date);
echo "&nbsp;อายุ&nbsp; ";
echo $diff->format("%Y ปี %m เดือน %d วัน");
}
echo "</p>";
echo "</div>";

$ptin = mysqli_query($linkopdx, "select * from $pttable WHERE id= '$_SESSION[rid]' ");
while ($row = mysqli_fetch_array($ptin))
{
    echo "<p class='ptinfo'>";
    echo "<b><u>ประวัติแพ้ยาและสาร</u></b>: ".$row['drugallergy']."<br>";
    echo "<b><u>ประวัติโรคประจำตัว</u></b>: ".$row['chronicill']."<br>";
    echo "<b><u>อาการนำ</u></b>: ".$row['ccp']."<br>";
    echo "<b><u>ประวัติอาการ</u></b>:";
    $hist_ill = $row['dofhis'];
    $hist_ill = mysqli_real_escape_string($linkopd, $hist_ill);
    $hist_ill = str_replace("\\r\\n","<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$hist_ill);
    echo "<p class='P1' align=justify>".$hist_ill."</p>";
    echo "<p class='klpresent'>";
    echo "<b><u>ตรวจร่างกาย</u></b>:";
    echo " BW = ";
    echo $row['weight'];
    echo " kg. ;";
    echo " Temp = ";
    echo $row['temp'];
    echo "°C ;";
    echo " BP = ";
    echo $row['bpsys'];
    echo "/";
    echo $row['bpdia'];
    echo " mmHg ; HR = ";
    echo $row['hr'];
    echo " BPM ;";
    echo " RR = ";
    echo $row['rr'];
    echo "/min";
    echo "</p>";
    $oldphex = $row['phex'];
    $oldphex = mysqli_real_escape_string($linkopdx, $oldphex);
    $phex = strstr($oldphex, '[PICTURE]', true);
    if (empty($phex)) {$phex = $oldphex;}
    $phex = str_replace("\\r\\n","<br>",$phex);
    echo "<p class='klpresent'>".$phex."</p>";
    $pic_file = strstr($oldphex, '[PICTURE]');
    if(!empty($pic_file)) {
        $num_of_pic = substr_count($pic_file ,";");
        $j = $num_of_pic + 1;
        $pic_file = ltrim($pic_file,"[PICTURE]");
        $pic_file = rtrim($pic_file,"[/PICTURE]");
        $picsrc = explode(";", $pic_file);
        echo "<div>";
        for ($i=0;$i<$j;$i++){
            echo "<button><img src='".$picsrc[$i]."' width='100' height='100'/></button>";
        }
        echo "</div>";
    }
    $progs = $row['obsandpgnote'];
    ///
    echo "<p class='P1'>";
    $labidr=$row['labid'];
    $labrsr=$row['labresult'];
    if(!empty($labidr))
    {
        $n = substr_count($labidr, ';');
        //$str = 'hypertext;language;programming';
        $charsl = preg_split('/;/', $labidr);
    }
    if(!empty($labrsr))
    {
        $n = substr_count($labrsr, ';');
        //$str = 'hypertext;language;programming';
        $charsr = preg_split('/;/', $labrsr);
        echo "<b><u>Lab</u></b>: ";
        //	print_r($charsr);
    }
    $filter = mysqli_query($link, "select * from lab WHERE `L_Set` !='SETNAME' ORDER BY `id` ASC  ");
    while ($labinfo = mysqli_fetch_array($filter))
    {
        //	  $labset = $labinfo['L_Set'];
        //	  $labset =  substr($labset,5);
        //	  $labspec = $labinfo['L_specimen'];
        //	  $labnomr = $labinfo['normal_r'];
        $labunit = $labinfo['Lrunit'];
        //	  $labmin = $labinfo['r_min'];
        //	  $labmax = $labinfo['r_max'];
        $lname = $labinfo['S_Name'];
        $lname1 = $labinfo['S_Name']." [".$labinfo['L_Name']."]";
        for ($i=0;$i<=$n;$i++)
        {
            $cond = $charsl[$i];
            if($lname1==$charsl[$i])
            {
                ////	      echo "<tr><td>";
                //	      echo $i+1;
                //	      echo "</td><td style='text-align:left;'>";
                //	      echo $charsl[$i];
                //	      echo "</td><td>";
                //	      echo $labset;
                //	      echo "</td><td>";
                //	      echo $labspec;
                //	      echo "</td><td>";
                echo $lname."=";
                echo $charsr[$i]." ";
                //	      echo "</td><td>";
                echo $labunit." ";
                //	      echo "</td><td>";
                //	      echo $labnomr;
                //	      echo "</td><td>";
                //	      echo $labmin;
                //	      echo "</td><td>";
                //	      echo $labmax;
                //	      echo "</td></tr>";
            }
        } 
    }
    ///
    echo "</p>";
    echo "<b><u>Diag</u></b>: ".$row['ddx']."<br>";
    echo "<b><u>คำแนะนำ</u></b>: ".$row['inform']."<br>";
    echo "<b><u>Treatment</u></b>:";
    echo "<p class='klpresent'><ol>";
    for ($i=1; $i<=4;$i++)
    {
        if($row['tr'.$i]!="")
        {
        echo "<li>";
        echo $row['tr'.$i].' '.$row['trv'.$i].' '.$row['tr'.$i.'o1'].' '.$row['tr'.$i.'o1v'].$row['tr'.$i.'o2'].' '.$row['tr'.$i.'o2v'].$row['tr'.$i.'o3'].' '.$row['tr'.$i.'o3v'].$row['tr'.$i.'o4'].' '.$row['tr'.$i.'o4v'] ;
        echo "</li>";
        }
    }
    echo "</ol></p>";
    echo "<b><u>ยาและผลิตภัณฑ์</u></b>:";
    echo "<p class='klpresent'><ol>";
    for ($i=1;$i<=14;$i++)
    {
        $check[$i] = 0;
        if($mask == 1)
        {
            for($j=1;$j<$di;$j++)
            {
                if(($row['idrx'.$i] == "$didin[$j]") AND !$check[$i])
                {
                    $check[$i] = 1;
                }
            }
        }
        if(($row['rx'.$i] !="") AND !$check[$i])
        {
            echo "<li>";
            if($row['rxby'.$i]!=0) echo $row['rx'.$i].'('.$row['rxg'.$i].'<sup>'.$row['rxby'.$i].'</sup>'.') จำนวน: '.$row['rx'.$i.'v'].' วิธีใช้: '.$row['rx'.$i.'uses'];
            else echo $row['rx'.$i].'('.$row['rxg'.$i].') จำนวน: '.$row['rx'.$i.'v'].' วิธีใช้: '.$row['rx'.$i.'uses'];
            echo "</li>";
        }
    }
    echo "</ol></p>";
    //progression note
    if (ltrim($progs) !== '')
    {
        echo "<b><u>บันทึกสังเกตอาการ</u></b>:";
        echo "<p class='comment'>".$progs."</p>";
    }
    $doctor = $row['doctor'];
    $drlc = $row['dtlc'];
    $disprx = $row['disprxby'];
    //$trx = ;
    $staff = mysqli_query($link, "select * from staff WHERE ID = '$disprx' ");
    while($row_vl = mysqli_fetch_array($staff))
    {   
        $stpf = $row_vl['prefix'];
        $stfname = $row_vl['F_Name'];
        $stlname = $row_vl['L_Name'];
    }
}
echo "<br>";
echo "<b><u>ตรวจรักษาโดย</u></b>: ".$doctor."__________________________<br>";
echo "<b><u>จ่ายยาโดย</u></b>: ".$stpf." ".$stfname." ".$stlname;
echo "</p>";
echo "</div>";
?>
