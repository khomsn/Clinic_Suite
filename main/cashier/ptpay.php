<?php 
include '../../config/dbc.php';

page_protect();

unset($_SESSION['checkup']);

$dimdir = "../".DRUGIMAGE_PATH;

include '../../libs/dateandtimezone.php';
include '../../libs/progdate.php';
include '../../libs/trpricecheck.php';

if(!empty($_SESSION['patdesk']))
{
 $id = $_SESSION['patdesk'];
}
if(!empty($_SESSION['patcash']))
{
    $id = $_SESSION['patcash'];
}

$Patient_id = $id;
include '../../libs/opdxconnection.php';

$ctmid = $id;
$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
$tmp = "tmp_".$id;
$today = date("Y-m-d");

$pin = mysqli_query($linkopdx, "select MAX(id) from $pttable");
$rid = mysqli_fetch_array($pin);

$popupmaxid = 14;

$title = "::Cashier + จ่ายยา::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" href=\"../../jscss/css/table_alt_color.css\">";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/popuponpage.css\"/>";
include '../../libs/popuponpage.php';
include '../../libs/popupshowdrugpic.php';
echo "</head><body>";
?>
<form method="post" action="ptpay.php" name="regForm" id="regForm">
<div style="text-align: center;">
<h2 class="titlehdr"><div style="background-color:rgba(0,255,0,0.5); display:inline-block;">ยาและผลิตภัณฑ์ ณ วันที่ <?php echo $sd; ?> <?php $m = $sm;
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
}?> พ.ศ. <?php echo $bsy; //date("Y")+543;?></div></h2>
<h3><div style="background-color:rgba(0,255,0,0.5); display:inline-block;">ชื่อ: &nbsp; 
<?php
while ($row_settings = mysqli_fetch_array($ptin))
{
    echo $row_settings['fname'];
    echo "&nbsp; &nbsp; &nbsp;"; 
    echo $row_settings['lname'];
}
$ptin = mysqli_query($linkopdx, "select * from $pttable where id = '$rid[0]' ");
while ($row_settings = mysqli_fetch_array($ptin))
{
    $inform = $row_settings['inform']; 
    $_SESSION['diag'] = $row_settings['ddx'];
}

$perdc=0; //init value
$disc = mysqli_query($link, "select * from discount WHERE ctmid = $ctmid ");
while( $rowd = mysqli_fetch_array($disc))
{
    echo "   &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;   มีสิทธิส่วนลด ";
    echo $rowd['percent'];
    echo " %";
    $perdc = $rowd['percent']/100;
}
echo "</div>";
echo "</h3>";
echo "<div style=\"background-color:rgba(0,255,0,0.5); display:inline-block;\">วินิจฉัย: "; echo $_SESSION['diag']."</div>";
echo "<div class=\"msg\"><br>คำแนะนำ: ".$inform."</div>"; 
?>
</div>
<?php 
echo "<div style=\"background-color:rgba(0,255,0,0.5); display:inline-block;\">Treatment:</div><br> ";
?><table class='TFtable' border="1"  style="text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);">
<tr><th width = 10 >No</th><th >ชื่อ</th><th width = 75px>ราคา</th><th width = 35px>Vol</th><th width = 75px>รวม</th></tr>
<?php
$j = 1;
for($i =1;$i<=4;$i++)
{
    $ptin = mysqli_query($link, "select * from $tmp ");
    while ($row = mysqli_fetch_array($ptin))
    {
        $idtr = "idtr".$i;
        $tr ="tr".$i;
        $trv = "trv".$i;
        if($row[$idtr] !=0)
        {
            echo "<tr><td>".$j."</td><td>";
            echo $row[$tr];
            echo "</td>";
            echo "<td>";
            $did = $row[$idtr];
            //check id if match jump
            for($s=1;$s<=$t;$s++)
            {
                if($did ==  $tr_drugid[$s]) goto jpoint1;
            }
            $ptin2 = mysqli_query($link, "select * from drug_id WHERE id = $did ");
            if($ptin2 !=0)
            {
                while ($row2 = mysqli_fetch_array($ptin2))
                {
                    echo $row2['sellprice'];
                    $price1 = $row2['sellprice'] * $row[$trv] - floor($row2['sellprice'] * $row[$trv] * $row2['disct'] * $perdc);

                    $typen = $row2['typen'];
                }
            }
            jpoint1:

            echo "</td>";
            echo "<td>";
            echo $row[$trv];
            echo "</td>";
            echo "<td>";
            //cal price
            if($did ==  $tr_drugid[$s])
            {
                if($row[$trv]>=$first1[$s]) 
                $price1 = ($row[$trv]-$first1[$s]+1)*$f1price[$s];
                if($row[$trv]>=$sec2[$s]) 
                $price1 = ($row[$trv]-$sec2[$s]+1)*$sec2price[$s]+($sec2[$s]-$first1[$s])*$f1price[$s];
                if($row[$trv]>=$tri3[$s]) 
                $price1 = ($row[$trv]-$tri3[$s]+1)*$tri3price[$s]+($tri3[$s]-$sec2[$s])*$sec2price[$s]+($sec2[$s]-$first1[$s])*$f1price[$s];
            }

            echo $price1;
            echo "</td></tr>";
            $j = $j+1;
        }
    }
}

?>
</table>
<div style="background-color:rgba(0,255,0,0.5); display:inline-block;">ยาและผลิตภัณฑ์:</div><br>
<table class="TFtable" border="1"  style="text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);" >
<tr><th width = 10 >No</th><th>ชื่อการค้า</th><th>ขนาด</th><th>Generic Name</th><th width=50%>วิธีการใช้</th><th width = 35px>จำนวน</th></tr>
<?php 
$j=1;
for($i=1;$i<=14;$i++)
{
    $ptin = mysqli_query($link, "select * from $tmp ");
    while ($row = mysqli_fetch_array($ptin))
    {
        $idrx = "idrx".$i;
        $rx ="rx".$i;
        $rgx = "rxg".$i;
        $rxuses = "rx".$i."uses";
        $rxv = "rx".$i."v";
        if($row[$idrx] !=0)
        {
            $did = $row[$idrx];
            $ptin2 = mysqli_query($link, "select * from drug_id WHERE id = $did ");
            
            if(!empty($ptin2))
            {
                while ($row2 = mysqli_fetch_array($ptin2))
                {
                    $price1 = $row2['sellprice'] * $row[$rxv] - floor($row2['sellprice'] * $row[$rxv] * $row2['disct'] * $perdc);
                    $typen = $row2['typen'];
                    $dname = $row2['dname'];
                    $dsize = $row2['size'];
                    $loca = $row2['location'];
                }
            }
            echo "<tr><td>";
            echo $j;
            echo "</td><td>";
            echo "<div class='popup' onmouseover='myFunction".$i."()' onmouseout='myFunction".$i."()'>";
            echo $dname;
            echo "<span class=\"popuptext\" id=\"myPopup".$i."\">";
            //echo $loca;
            $loca1 = mysqli_query($link, "SELECT `fullplace` FROM `stockplace` WHERE `placeindex` = '$loca' ");
            while ($loc = mysqli_fetch_array($loca1))
            {
                echo $loc['fullplace'];
            }
           // echo $loca1[0];
            echo "</span>";
            echo "</div>";
            echo "</td><td>";
            echo "<div class='popup' onmouseover='myFunctionDrugpig".$i."()' onmouseout='myFunctionDrugpig".$i."()'>";
            echo $dsize;
            echo "<span class=\"popuptext\" id=\"myPopupdrugpig".$i."\">";
            $avatar = $dimdir. "drug_". $did . ".jpg";
            echo "<img src=\"".$avatar."\" width=150 height=150 />";
            //echo $loca;
            echo "</span>";
            echo "</div>";
            echo "</td><td>";
            echo $row[$rgx];
            echo "</td>";
            echo "<td>";
            echo $row[$rxuses];
            echo "</td>";
            echo "<td>";
            echo $row[$rxv];
            echo "</td></tr>";
            $j = $j+1;
        }
    }
}
?>
</table><br>
</form><br>
</body></html>
