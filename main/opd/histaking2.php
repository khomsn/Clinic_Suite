<?php 
include '../../config/dbc.php';
page_protect();
$msg = array();

$Patient_id = $_SESSION['patdesk'];
include '../../libs/opdxconnection.php';

$ptdata = mysqli_query($linkopd, "SELECT * FROM patient_id where id='$Patient_id' ");

$pttable = "pt_".$Patient_id;
$tmp = "tmp_".$Patient_id;
$today = date("Y-m-d");

$pttmpdata = mysqli_query($link, "select * from $tmp ");
while ($row_settings = mysqli_fetch_array($pttmpdata))
	{
        $_SESSION[$Patient_id] = $row_settings['rindex'];
	}

if($_POST['TimeKey']=="Reset")
{
    $_SESSION['called'] = 0;
    $sql_update = "UPDATE `queuesystem` SET `ptid` = '0' WHERE `FromIp` = '$_SESSION[user_ip]'";
    mysqli_query($link, $sql_update);
}
if($_POST['CallForInspection'] == "เรียกตัวเข้าตรวจ")
{
    $_SESSION['called'] = 1;
    //$msg[] = $_SESSION['user_ip'];
    $sql_update = "UPDATE `queuesystem` SET `ptid` = '$Patient_id' WHERE `FromIp` = '$_SESSION[user_ip]'";
    mysqli_query($link, $sql_update);
}

if($_POST['rec'] == "DDx" OR $_POST['rec'] == "ReDDx") 
{
    $_SESSION['history'.$Patient_id]= $_POST['dhist'];
    $_SESSION['DDx'] = $_POST['diag'];
    $_SESSION['phex'.$Patient_id]=$_POST['phex'];
    unset($_SESSION['cddx']);
    header("Location: histaking.php"); 
}
if($_POST['register'] == 'บันทึก') 
{
    //check staff
    $pstaff=mysqli_fetch_array(mysqli_query($linkopd, "select staff from patient_id where id='$Patient_id'"));
    $policy = $pstaff[0];//พนักงาน
    if(!$policy)
    {
        $policy = $_POST['policy']; //คนไข้ทั่วไป
    }

    $diags = $_POST['diag'];
    $dhist = $_POST['dhist'];
    $diags = mysqli_real_escape_string($linkopdx, $diags);
    $dhist = mysqli_real_escape_string($linkopdx, $dhist);

    $chronicill = $_SESSION['chron'];
    $drugallergy = $_SESSION['drugale'];
    $concurdrug = $_SESSION['concurdrug'];

    $chronicill = mysqli_real_escape_string($linkopdx, $chronicill);
    $drugallergy = mysqli_real_escape_string($linkopdx, $drugallergy);
    $concurdrug = mysqli_real_escape_string($linkopdx, $concurdrug);

    mysqli_query($link, "UPDATE $tmp SET `preg` = '$_POST[preg]',`pricepolicy` = '$policy'") or $msg[]=(mysqli_error($link));
    if($_POST['preg'] ==1 )
    {
        $dhist = $dhist.'# และ กำลังตั้งครรภ์ '.$_POST['pregmon']. ' เดือน #';
        $pregmonth = $_POST['pregmon'];
    }
    else
    {
        $pregmonth = 0;
    }
    
    $ptrecord = mysqli_query($linkopdx, "select * from $pttable where  id = '$_SESSION[$Patient_id]' ");
    while ($row_settings = mysqli_fetch_array($ptrecord))
    {
        $oldphex = $row_settings['phex'];
    }
    $oldphex = mysqli_real_escape_string($linkopd, $oldphex);
    $pic_file = strstr($oldphex, '[PICTURE]');
    if(empty($pic_file)) $phex_to_record = $_POST['phex'];
    else $phex_to_record = $_POST['phex'].$pic_file;
    
    mysqli_query($linkopdx, "UPDATE $pttable SET
                `dofhis` = '$dhist',
                `phex` = '$phex_to_record',
                `ddx` = '$diags',
                `bpsys` = '$_POST[bpsys]',
                `bpdia` = '$_POST[bpdia]',
                `temp` = '$_POST[temp]',
                `hr` = '$_POST[hr]',
                `rr` = '$_POST[rr]',
                `doctor` = '$_SESSION[sfname]',
                `dtlc` = '$_SESSION[sflc]',
                `weight` = '$_POST[weight]',
                `height` = '$_POST[height]',
                `chronicill` = '$chronicill',
                `drugallergy` = '$drugallergy',
                `concurdrug` = '$concurdrug'
                WHERE  id='$_SESSION[$Patient_id]'
                ") or $msg[]=(mysqli_error($linkopdx));
                
    // go on to other step
    //update height at patient_id use fup to record pregnancy period.
    mysqli_query($linkopd, "UPDATE patient_id SET `height` = '$_POST[height]', `fup` = '$pregmonth' where id='$Patient_id'") or $msg[]=(mysqli_error($linkopd));

    //update diagnosis table
    $d1=trim($_POST['diag']," ");
    if(!is_numeric($d1[0]) AND !empty($d1))
    {   
        $imp = mysqli_query($linkcm, "select name from diag WHERE name = '$d1'");

        list($imprs) = mysqli_fetch_row($imp);
        if(empty($imprs))
        {
            $sql_insert = "INSERT into `diag` (name) value ('$d1')";
            mysqli_query($linkcm, $sql_insert) or $msg[]=("Insertion Failed:" . mysqli_error($linkcm));
        }
    }
    unset($_SESSION['history'.$Patient_id]);
    unset($_SESSION['phex'.$Patient_id]);
    unset($_SESSION['DDx']);
    unset($_SESSION[$Patient_id]);
    //go on 
    header("Location: prescript.php");  
}

$title = "::ประวัติ ตรวจร่างกาย::";
include '../../main/header.php';
include '../../libs/popup.php';
include '../../libs/autodiag.php';
include '../../libs/iframeautofocus.php';
include '../../main/bodyheader.php';

?>
<table width="100%" border="1" cellspacing="0" cellpadding="5" class="main">
<tr><td>
    <form method="post" action="histaking.php" name="regForm" id="regForm">
    <h3 class="titlehdr">ประวัติ และ ตรวจร่างกาย</h3>
    <table style="text-align: left; width: 100%; height: 413px;" border="1" cellpadding="2" cellspacing="2"  class="forms">
    <tr><td style="width: 80%; vertical-align: middle;">
        <div style="text-align: center;">
        <h2>ชื่อ: &nbsp; 
        <?php
        if(!empty($msg))
        {
            echo "<div class=\"msg\">";
            foreach ($msg as $me) {echo "$me <br>";}
            echo "</div>";
        }
        while ($row_settings = mysqli_fetch_array($ptdata))
        {
            echo $row_settings['fname'];
            echo "&nbsp; &nbsp; &nbsp;"; 
            echo $row_settings['lname'];
            echo "&nbsp; &nbsp; &nbsp;เพศ";
            echo $row_settings['gender'];
            if($row_settings['gender']=="หญิง"){ $gd = 1;}
            $date1=date_create(date("Y-m-d"));
            $date2=date_create($row_settings['birthday']);
            $diff=date_diff($date2,$date1);
            $_SESSION['age'] = $diff->format("%Y");
            echo "&nbsp; &nbsp;อายุ&nbsp; ";
            echo $diff->format("%Y ปี %m เดือน %d วัน");
            //concurdrug
            $_SESSION['concurdrug']=$row_settings['concurdrug'];
            //check pregnancy
            $tmpin = mysqli_query($link, "select * from $tmp ");
            $preg =0;
            while($rowt = mysqli_fetch_array($tmpin))
            $preg = $rowt['preg'];

            if(($row_settings['gender']=="หญิง") AND ($_SESSION['age']>=10 AND $_SESSION['age'] <=55))
            {
                //get pregdate for fup
                $pregmonth = $row_settings['fup'];
                //check for pregnancy period
                if($pregmonth > 0)
                {
                    $oldrid = $_SESSION[$Patient_id]-1;
                    $ptin2 = mysqli_query($linkopdx, "select * from $pttable where  id = '$oldrid' ");
                    while($olddate = mysqli_fetch_array($ptin2))
                    {
                        $date2=date_create($olddate['date']);
                        $diff = date_diff($date2,$date1);
                        $dpass = $diff->format("%a");
                        if($dpass < 294){
                            $pregmonth = ceil($dpass / 28);
                            if ($pregmonth > 10)
                            $pregmonth = 10;
                        }
                        else
                        $pregmonth = 0;
                    }
                }
                echo "<input type=\"radio\" name=\"preg\" id='preg1' value=\"1\"";
                if($preg == 1 OR $pregmonth !=0 )
                {
                    $ptin2 = mysqli_query($linkopdx, "select * from $pttable where  id = '$_SESSION[$Patient_id]' ");
                    while ($row2 = mysqli_fetch_array($ptin2))
                    {
                        if(empty($_SESSION['history'.$Patient_id]))
                        $hist = $row2['dofhis'];
                        else
                        $hist = $_SESSION['history'.$Patient_id];
                    }
                    $newstring = substr($hist, -72);
                    //echo $str = substr($hist, -72);
                    $newstring = preg_replace('/\D/', '', $newstring);
                    echo "checked";
                }
                if($pregmonth > 0 AND $pregmonth <= 10) $newstring = $pregmonth;
                echo "><label for='preg1'>ตั้งครรภ์<input type=number name=pregmon min=1 max=10 step=1 class=typenumber value='".$newstring."'></label>";
                echo "<input type=\"radio\" name=\"preg\" id='preg0' value=\"0\"";
                if($preg == 0) echo "checked";
                echo "><label for='preg0'>ไม่ตั้งครรภ์</label>";
            }
            else
            {
                echo "<input type='hidden' name='preg' value='0'>";
            }

            for($i=1;$i<=5;$i++)
            {
                $dal = "drug_alg_".$i;
                if($row_settings[$dal]=="ปฎิเสธ")
                {
                $drugalg[$i] = "";
                }
                else $drugalg[$i] = $row_settings[$dal];
                $con = "chro_ill_".$i;
                $chro[$i] = $row_settings[$con];
            }
            $drugale = join(" , ", $drugalg ) ; 
            $chron = join(" , ", $chro) ;

            $_SESSION['drugale'] = $drugale;
            $_SESSION['chron'] = $chron;
            if(empty(str_replace(" , ","",$_SESSION['drugale']))) $_SESSION['drugale'] = "ปฎิเสธ";
            if(empty(str_replace(" , ","",$_SESSION['chron']))) $_SESSION['chron'] = "ปฎิเสธ";
            echo "</h2>";
        }
        ?>
        <span style="color: red;"><a href="drallergy.php" onclick="return popup(this,'name','800','450','yes')">แพ้ยา-สาร:</a>:
        <?php echo $drugale;?>
        <a href="chronicill.php" onclick="return popup(this,'name','800','600','yes')">โรคประจำตัว:</a>:<?php echo $chron;?></span><br>
        <a href="concurdrug.php" onclick="return popup(this,'name','800','600','yes')">ยาที่ใช้ประจำ:</a>:<?php echo $_SESSION['concurdrug'];?></span><br>
        มาปรึกษาเรื่อง : 
        <?php 
        $tmpin = mysqli_query($link, "select * from $tmp ");
        while($row_settings = mysqli_fetch_array($tmpin))
        {
            echo $row_settings['csf'];
            $policy = $row_settings['pricepolicy'];
        }
        ?>
        
        <hr style="width: 80%; height: 2px;"><br>
        <input type="radio" name="policy" id="policy2" value="2" <?php if($policy==2) echo "checked";?>><label for="policy2">ตรวจโรค</label>
        <input type="radio" name="policy" id="policy3" value="3" <?php if($policy==3) echo "checked";?>><label for="policy3">ทำหัตการ</label>
        <input type="radio" name="policy" id="policy4" value="4" <?php if($policy==4) echo "checked";?>><label for="policy4">มาตามนัด</label></div>

        </div>
    <hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
    <div style="text-align: center;">
        <table style="background-color: rgb(255, 204, 153); width: 80%; text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
        <tr><td width = 25%>
        <a HREF="heightdisp.php" onClick="return popup(this, 'name','400','600','yes')" >สูง:</a>
        <?php 
        //$ptdata = mysqli_query($linkopd, "SELECT * FROM patient_id where id='$Patient_id' ");
        while ($row_settings = mysqli_fetch_array($ptdata))
        {
        echo "<input type=text size=3 name=height value=".$row_settings['height'].">";
        $H = $row_settings['height']/100;
        }	
        echo " cm";
        ?>
        <br>
        <a HREF="weightdisp.php" onClick="return popup(this,'name','400','600','yes')" >หนัก :</a>
        <?php 
        $today = date("Y-m-d");
        
        $oldid = $_SESSION[$Patient_id] -1;
        if($oldid > 1){
            $ptrecordold = mysqli_query($linkopdx, "select * from $pttable where id ='$oldid' ");
            while ($row_settings = mysqli_fetch_array($ptrecordold))
            {
            $Wo = $row_settings['weight'];
            $dxold = $row_settings['ddx'];
            echo "<input type=hidden name=olddx id='oldDx' value=\"".$dxold."\">";
            }
        }
        $ptrecord = mysqli_query($linkopdx, "select * from $pttable where  id = '$_SESSION[$Patient_id]' ");
        while ($row_settings = mysqli_fetch_array($ptrecord))
        {
        echo "<input type='text' size=3 name='weight' value='";
        echo $row_settings['weight'];
        echo "'/>";
        $W = $row_settings['weight'];
        $bps = $row_settings['bpsys'];
        $bpd = $row_settings['bpdia'];
        $hr = $row_settings['hr'];
        if(empty($_SESSION['history'.$Patient_id]))
        $hist = $row_settings['dofhis'];
        else
        $hist = $_SESSION['history'.$Patient_id];
        if(empty($_SESSION['phex'.$Patient_id]))
        $phex = $row_settings['phex'];
        else
        $phex = $_SESSION['phex'.$Patient_id];
        $rr = $row_settings['rr']; 
        $temp = $row_settings['temp']; 
        $ddx = $row_settings['ddx']; 

        echo "&nbsp;<small><small><small>(";
        echo round($W - $Wo,1);
        echo ") kg</small></small></small>";

        }
        if($_SESSION['ddx']!='')
        {
        $ddx = $_SESSION['ddx'];
        }
        ?>

        </td>
        <td>

        <a HREF="bmi.php" onClick="return popup(this, 'name','700','200','no')" >BMI  = 
        <?php 
        echo round($W/($H*$H),2);
        echo "<br>";
        echo "[";
        if($gd==1){ echo  round((18.5 *($H*$H)),1). " - ".round(( 25* ($H*$H)),1); }
        else{echo round((18.5 *($H*$H)),1). " - ".round(( 27* ($H*$H)),1); }
        echo " kg";
        echo "]";        
        ?>
        </a>
        </td>
        <td style="width: 60%;">

        <span style="font-size: smaller;">
        <a href="bp.php" onclick="return popup(this,'name','600','600','yes')">BP</a>=
        <?php 
        echo "<input type='text' size=3 maxlength=3 name='bpsys' value='";
        echo $bps;
        echo "'>/";
        echo "<input type='text' size=3 maxlength=3 name='bpdia' value='";
        echo $bpd;
        echo "'> mmHg &nbsp;&nbsp;   HR=";
        echo "<input type='text' size=3 maxlength=3 name='hr' value='";
        echo $hr;
        echo "'> BPM<br>";
        echo "Temp =";
        echo "<input type='text' size=4 maxlength=4 name='temp' value='";
        echo  $temp;
        echo "'>°C &nbsp;&nbsp;&nbsp;  ";
        echo "RR = ";
        echo "<input type='text' size=4 maxlength=4 name='rr' value='";
        echo $rr;
        echo "'> / min";
        ?>
        </span>
        </td>
        </tr>
        </table>  
    </div>
    <hr style="width: 80%; height: 2px;">
    <div style="text-align: left;">
        ประวัติ:<br>
        <textarea autofocus  cols="100%" rows="3" type="text" name="dhist" style="width: 100%;" id="firstfocus"><?php
        if($preg == 1)
        {
            if (strstr($hist, '#'))
            {
            $hist = substr($hist, 0, strpos($hist, "#"));
            }
            echo $hist;
        }
        if($preg == 0) echo $hist;
        ?></textarea><br>
        <a HREF="PhysExamCheckList.php?msg=<?php echo $Patient_id;?>" onClick="return popup(this,'name','800','600','yes')" >ตรวจร่างกาย</a><span STYLE="Padding-left: 5px; border: 5px groove #ffffff">
        <a HREF="uploadpicture.php?page=0" onClick="return popup(this,'name','800','600','yes')" >Picture</a></span> <br>
        <textarea cols="70%" rows="5" name="phex" type="text"  style="width: 100%;"><?php
        if(empty($phex))
        {
        echo "HEENT:\nH:\nL:\nAbd:\nExt:\n";
        }
        else 
        {
        $phex_new = strstr($phex, '[PICTURE]', true);
        if(!empty($phex_new)) $phex = $phex_new;
        echo $phex;
        }
        ?></textarea>
        <div style="display:none;"><input name="register" value="บันทึก" type="submit"></div>
        <div style="text-align: center;">
        <hr style="width: 80%; height: 2px;">
        <input type=submit name=rec value="DDx" onmouseUp="return popup('ddx.php','DDx','600','400','yes')">
        <input name="diag" type="text" id="diag1" size="70%" value="<?php 
        echo $ddx;
        unset($_SESSION['ddx']);
        ?>"/>
        <script type="text/javascript">
        // Created by: Jay Rumsey | http://www.nova.edu/~rumsey/
        // This script downloaded from JavaScriptBank.com

        function ReDDx() {
        var gn, sum='';
        for (i=1; i<2; i++) {

        gn = document.getElementById('oldDx'); sum = gn.value; 
        }
        document.getElementById('diag1').value = sum;
        } 
        </script>
        <input type="button" id="game" Value="ReDxLV" onclick="ReDDx()" ><input type=submit name=rec value="ReDDx" onmouseUp="return popup('reddx.php','ReDDx','900','400','yes')">
        </div>
        </td>
        </tr>
        <tr>
        <td>
        <div style="text-align: center;"><input name="register" value="บันทึก" type="submit"></div>
        </td>
        </tr>
        </tbody>
        </table>
    </div>
        <div class='pos_r_fix_mypage1' id="demo"><input type='submit' name='CallForInspection' value='เรียกตัวเข้าตรวจ'></div>
    </form>
</td></tr>
</table>
<br>
<?php
if($_SESSION['called']==1)
{
?>
<script  type="text/javascript">
    // Set the date we're counting down to
    // 1. JavaScript
    // var countDownDate = new Date("Sep 5, 2018 15:37:25").getTime();
    // 2. PHP
/*    
echo " ". $startTime = date('h:i:s');
echo " ". $endTime = strtotime("+2 minutes", strtotime($startTime));
echo " ". $endTime = date('h:i:s', $endTime);
*/
    
    
    var now = <?php $startTime = date('h:i:s'); echo strtotime($startTime);?> * 1000;
    var countDownDate = <?php echo $endTime = strtotime("+1 minutes", strtotime($startTime));// echo strtotime($endTime) ?> * 1000;
    // Update the count down every 1 second
    var x = setInterval(function() {

        // Get todays date and time
        // 1. JavaScript
        // var now = new Date().getTime();
        // 2. PHP
        now = now + 1000;

        // Find the distance between now an the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        var resettime = "Reset";

        // Output the result in an element with id="demo"
       /* document.getElementById("demo").innerHTML = days + "d " + hours + "h " +
            minutes + "m " + seconds + "s ";*/
        document.getElementById("demo").innerHTML = minutes + "m " + seconds + "s ";

        // If the count down is over, write some text 
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("demo").innerHTML = "<input type='submit' name='CallForInspection' value='เรียกตัวเข้าตรวจ'>";
            
            $.ajax({
                    data: 'TimeKey=' + resettime,
                    url: 'histaking.php',
                    method: 'POST' // or GET
                    /*
                    success: function(msg) {
                        alert(msg);
                    }*/
                });
        }
    }, 1000);
</script>
<?php
}
?>
</body></html>
<script  type="text/javascript">
jQuery.fn.putCursorAtEnd = function() {

  return this.each(function() {
    
    // Cache references
    var $el = $(this),
        el = this;

    // Only focus if input isn't already
    if (!$el.is(":focus")) {
     $el.focus();
    }

    // If this function exists... (IE 9+)
    if (el.setSelectionRange) {

      // Double the length because Opera is inconsistent about whether a carriage return is one character or two.
      var len = $el.val().length * 2;
      
      // Timeout seems to be required for Blink
      setTimeout(function() {
        el.setSelectionRange(len, len);
      }, 1);
    
    } else {
      
      // As a fallback, replace the contents with itself
      // Doesn't work in Chrome, but Chrome supports setSelectionRange
      $el.val($el.val());
      
    }

    // Scroll to the bottom, in case we're in a tall textarea
    // (Necessary for Firefox and Chrome)
    this.scrollTop = 999999;

  });

};

(function() {
  
  var searchInput = $("#firstfocus");

  searchInput
    .putCursorAtEnd() // should be chainable
    .on("focus", function() { // could be on any event
      searchInput.putCursorAtEnd()
    });
  
})();
</script>
