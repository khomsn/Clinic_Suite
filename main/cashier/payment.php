<?php 
include '../../config/dbc.php';
page_protect();

include '../../libs/progdate.php';

$id = $_SESSION['patcash'];
$Patient_id = $id;
include '../../libs/opdxconnection.php';

$opdidcard = $id;
unset($_SESSION['frompage']);
//
$result1 = mysqli_query($link, "SELECT ptid FROM  pt_to_treatment WHERE ptid = '$id'");
if(mysqli_num_rows($result1) != 0) 
{
    $_SESSION['pattrm'] = $id;
    $_SESSION['frompage'] = "payment";
    header("Location: ../opd/trmpage.php ");
}
//
$ctmid = $id;
$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
$pttable = "pt_".$id;
$tmp = "tmp_".$id;
$today = date("Y-m-d");
$pin = mysqli_query($linkopdx, "select * from $pttable ORDER BY `id` ASC ");
while ($row_settings = mysqli_fetch_array($pin))
{
    $rid = $row_settings['id'];
    $visitdt = $row_settings['date'];
}
if($_POST['OK'] == 'ใบเสร็จรับเงิน')
{
    $_SESSION['paytoday'] = $_POST['totalpay'];
    $_SESSION['newdeb'] = $_SESSION['price'] - $_SESSION['paytoday'];
    $_SESSION['vd'] = $visitdt;
    //check if not pay yet
    if(mysqli_num_rows(mysqli_query($link, "select * from $tmp"))==0)
    {
    //paid 0 mean paid
        goto Paid;
    }
    //Recheck if PT is in pay-list

    $ck1 = mysqli_query($link, "SELECT id FROM pt_to_drug WHERE id='$id'");
    $chrow = mysqli_fetch_array($ck1);
    if(empty($chrow[0]))
    {
        $err[]= "ยังไม่เสร็จสิ้นการ รักษา กรูณา ให้ผู้ป่วยรอ ก่อน และ ให้บริการท่านอื่นก่อน";
        goto CannotPay;
    }

    header("Location: recprint.php ");  
}
elseif($_POST['OK'] == 'จ่าย')
{
    //check if not pay yet
    if(mysqli_num_rows(mysqli_query($link, "select * from $tmp"))==0)
    {
        //paid 0 mean paid
        goto Paid;
    }
    //Recheck if PT is in pay-list

    $ck1 = mysqli_query($link, "SELECT id FROM pt_to_drug WHERE id='$id'");
    $chrow = mysqli_fetch_array($ck1);
    if(empty($chrow[0]))
    {
        $err[]= "ยังไม่เสร็จสิ้นการ รักษา กรูณา ให้ผู้ป่วยรอ ก่อน และ ให้บริการท่านอื่นก่อน";
        goto CannotPay;
    }
    //
    $_SESSION['paytoday'] = $_POST['totalpay'];
    $_SESSION['newdeb'] = $_SESSION['price'] - $_SESSION['paytoday'];
    include '../../libs/payprocess.php';

    Paid:
    //session_start(); 
    /************ Delete the sessions****************/
    //unset($_SESSION['patcash']);

    if($propdcard)
    {
        $_SESSION['patcash']=$opdidcard;
        header("Location: ../../docform/opdcard.php ");
    }
    else
    {
        unset($_SESSION['buyprice']);
        unset($_SESSION['olddeb']);
        unset($_SESSION['patdesk']);
        unset($_SESSION['paytoday']);
        unset($_SESSION['newdeb']);
        unset($_SESSION['price']);
        unset($_SESSION['mrid']);
        unset($_SESSION['patcash']);
        header("Location: thankyou2.php ");
    }
    //unset($_SESSION['patcash']);
}
CannotPay:


$title = "::ใบเสร็จรับเงิน::";
include '../../main/header.php';
?>
<SCRIPT TYPE="text/javascript">

$(document).ready(function(){
    $("#TotalPay").keyup(function(){
         var TotalDeb = function (){ return $('#TotalPrice').val() - $('#TotalPay').val();};
         $('#TotalDeb').html(TotalDeb);
    });
    $("#CashInput").keyup(function(){
         var CashBack = function (){ return $('#CashInput').val() - $('#TotalPay').val();};
         $('#CashBack').html(CashBack);
    });
    
 var TotalPrice= $('#TotalPrice').val();
 var TotalPay = $('#TotalPay').val();
 var CashInput =$('#CashInput').val();
 var TotalDeb = function (){ return TotalPrice - TotalPay;};
 var CashBack = function (){return CashInput - TotalPay;};

 $('#TotalPrice').attr('value', TotalPrice);
 $('#TotalPay').attr('value', TotalPay);
 $('#CashInput').attr('value', CashInput);
 $('#CashBack').html(CashBack);
 $('#TotalDeb').html(TotalDeb);

 });


</SCRIPT>
</head><body>
<form method="post" action="payment.php" name="regForm" id="regForm">
<div style="text-align: center;">
<h2 class="titlehdr"> ยอดรวมค่า ยาและผลิตภัณฑ์ ณ วันที่ <?php echo $sd; ?> <?php $m = $sm;
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
}?> พ.ศ. <?php echo $bsy; //date("Y")+543;?></h2>
<h3><div style="background-color:rgba(0,255,0,0.5); display:inline-block;">ชื่อ: &nbsp; 
<?php
while ($row_settings = mysqli_fetch_array($ptin))
{
    echo $row_settings['fname'];
    $FName = $row_settings['fname'];
    echo "&nbsp; &nbsp; &nbsp;"; 
    echo $row_settings['lname'];
    $LName = $row_settings['lname'];
    $ctzid = $row_settings['ctz_id'];
    if(($ctzid<1000000000000))
    {
        if(!preg_match('/[a-zA-Z\.]/i', $ctzid))
        {
            $err[]= "ในครั้งหน้า ให้นำบัตร ประชาชนมาด้วย";
        }
    }
    echo "<p>";
    /******************** ERROR MESSAGES*************************************************
    This code is to show error messages 
    **************************************************************************/
    if(!empty($err))
    {
        echo "<div class=\"msg\">";
        foreach ($err as $e) {echo "$e <br>";}
        echo "</div>";
    }
    /******************************* END ********************************/	  
    echo "</p>";
}
$disc = mysqli_query($link, "select * from discount WHERE ctmid = $ctmid ");
while( $rowd = mysqli_fetch_array($disc))
{
    echo "   &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;   มีสิทธิส่วนลด ";
    echo $rowd['percent'];
    echo " %";
    $perdc = $rowd['percent']/100;
}
echo "</div></h3>";
?>
</div>
<table style="background-color: rgb(255, 204, 153); width: 80%; text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="3" cellspacing="3">
<TR BGCOLOR="#99CCFF"><TH>รายการ</TD><TH>บาท</TD></TR>
<TR BGCOLOR="#FFFFCC">
<th>ยอดรวมค่า ยาและผลิตภัณฑ์</th>
<th><input type="hidden" name="ja_price" id="TotalPrice" value="<?php echo $_SESSION['price'];?>"><div STYLE="color: #FF0000; font-family: Verdana; font-weight: bold; font-size: 30px; ">
<?php echo $_SESSION['price'];?></div></th>
</tr>
<tr></tr><tr></tr><tr></tr>
<TR BGCOLOR="#FFFFCC">
<th><big><big>ยอดที่จ่าย</big></big></th>
<th><input id="TotalPay" STYLE="color: #A901DB; font-family: Verdana; font-weight: bold; font-size: 36px; background-color: #A9F5F2; text-align: center;" type="number" name="totalpay" size="10"  value="<?php 
echo $_SESSION['price'];?>" min=0 max=<?php echo $_SESSION['price'];?>></th>
</tr>
<form method="get" action="recprint.php">
<tr></tr><tr></tr><tr></tr>
<TR BGCOLOR="#FFFFCC">
<th>รับมา</th>
<th><input  id="CashInput" STYLE="color: #0040FF; font-family: Verdana; font-weight: bold; font-size: 26px; background-color: #F5F6CE; text-align: center;" type="number" min=0 name="cashin" size="10"></th>
</tr>
<tr></tr><tr></tr><tr></tr>
<TR BGCOLOR="#FFFFCC">
<th>เงินทอน</th>
<th><div id="CashBack" STYLE="color: #0B610B; font-family: Verdana; font-weight: bold; font-size: 30px; "></div></th>
</tr>
<tr></tr><tr></tr><tr></tr>
<TR BGCOLOR="#FFFFCC">
<th>ยอดค้างชำระ</th>
<th><div  id="TotalDeb" ></div>
</th>
</tr>
</form>
</table>
<br>
<div style="text-align: center;">
<div style="display:none;"><input type="submit" name="OK" value="จ่าย"></div>
<input type="submit" name="OK" value="ใบเสร็จรับเงิน"><div style="background-color:rgba(0,255,0,0.5); display:inline-block;">* ต้องใช้คู่กับ <a href="remedcert.php" TARGET="MAIN">ใบรับรองแพทย์</a></div>
<br>
<br>
<input type="submit" name="OK" value="จ่าย">
<br>
<br>
</div>
</form>
<br>
</body></html>
