<?php 
include '../../config/dbc.php';
page_protect();

if($_POST['add'] == "เพิ่ม") 
{
    $_SESSION['supplier'] = $_POST['supplier'];
    $_SESSION['invoice'] = $_POST['inv_num'];

    //update post value first
    for($j=1;$j<=$_SESSION['DG'];$j++)
    {
        $_SESSION['rawmat'.$j]=$_POST['rawmat'.$j];
        $_SESSION['vol'.$j]=$_POST['vol'.$j];
        $_SESSION['buyprice'.$j]=$_POST['buyprice'.$j];
        $_SESSION['expd'.$j]=$_POST['expd'.$j];
    }
    
    $j=$_SESSION['DG'];
    //then check if it is valid post
    $rawmat = $_SESSION['rawmat'.$j];
    if(ltrim($rawmat)!=='')
    {
        $rawmatid[$j] = strstr($rawmat, '-', true);
        
        $ptin = mysqli_query($link, "select * from rawmat where id='$rawmatid[$j]'");
        while ($row2 = mysqli_fetch_array($ptin))
        {
            $_SESSION['rawname'.$j]= $row2['rawname'];
            $_SESSION['size'.$j] =  $row2['size'];
            $_SESSION['rawcode'.$j]= $row2['rawcode'];
            $_SESSION['sunit'.$j] = $row2['sunit'];
            $validid = $row2['id'];
        }
        if(($_SESSION['DG']<30) AND $validid)
        {
            $_SESSION['DG'] = $_SESSION['DG']+1;
        }
        if(!$validid)
        {
            unset($_SESSION['rawmat'.$j]);
        }
    }
}

for($n=1;$n<=$_SESSION['DG'];$n++)
{
    if($_POST['del'.$n]=='ลบ')
    {
        for($m=$n;$m<$_SESSION['DG'];$m++)
        {
            $j=$m+1;
            
            $_SESSION['rawmat'.$m]=$_SESSION['rawmat'.$j];
            $_SESSION['rawname'.$m]=$_SESSION['rawname'.$j];
            $_SESSION['size'.$m]=$_SESSION['size'.$j];
            $_SESSION['rawcode'.$m]=$_SESSION['rawcode'.$j];
            $_SESSION['sunit'.$m]=$_SESSION['sunit'.$j];
            $_SESSION['expd'.$m]=$_SESSION['expd'.$j];
            $_SESSION['vol'.$m]=$_SESSION['vol'.$j];
            $_SESSION['buyprice'.$m]=$_SESSION['buyprice'.$j];
        }
        unset($_SESSION['rawmat'.$m]);
        unset($_SESSION['rawname'.$m]);
        unset($_SESSION['size'.$m]);
        unset($_SESSION['rawcode'.$m]);
        unset($_SESSION['sunit'.$m]);
        unset($_SESSION['expd'.$m]);
        unset($_SESSION['vol'.$m]);
        unset($_SESSION['buyprice'.$m]);
        
        if($_SESSION['DG']>1)
        {
            $_SESSION['DG'] = $_SESSION['DG']-1;
        }
    }
}

if($_POST['doSave'] == 'Save')  
{
    $day = $_POST['day'];
    $month = $_POST['month'];
    $byear = $_POST['year'];
    $year = $byear - 543;

    // format date for mysql
    $bday = $year.'-'.$month.'-'.$day;

    for($a=1;$a<=$_SESSION['DG'];$a++)
    {
        $_SESSION['rawmat'.$a]=$_POST['rawmat'.$a];
        $rawmat = $_SESSION['rawmat'.$a];
        $rawmatid[$a] = strstr($rawmat, '-', true); 
        $id = $rawmatid[$a];
        if(empty($id) or $id == 0) goto Noid; //check for blank input
        $buyvolume = $_POST['vol'.$a];
        if(empty($buyvolume) or $buyvolume == 0) goto Noid; //check for blank input
        $buyprice = $_POST['buyprice'.$a];
        $stock_in = mysqli_query($link, "select * from rawmat where id='$id' ");
        $rawmattable = "rawmat_".$id;
        //check for table if not exist create it
        include '../../libs/rawmat_table.php';

        while ($row_settings = mysqli_fetch_array($stock_in))
        {
            $volume = $row_settings['volume']; //get volume to update
            $dacno = $row_settings['ac_no']; //get account no into stock 
        }
        $expd = $_POST['expd'.$a];
        if(empty($expd))
        {// assign insertion pattern
            $sql_insert = "INSERT into `$rawmattable`	(`date`,`supplier`,`inv_num`, `volume`, `price`)
            VALUES  ('$bday','$_POST[supplier]','$_POST[inv_num]','$buyvolume','$buyprice')";
        }
        else
        {
            $sql_insert = "INSERT into `$rawmattable`	(`date`,`expdate`,`supplier`,`inv_num`, `volume`, `price`)
            VALUES  ('$bday','$expd','$_POST[supplier]','$_POST[inv_num]','$buyvolume','$buyprice')";
        }
        mysqli_query($link, $sql_insert) ;


        // Update rawmat at volume and buyprice.
        $upvol = $volume + $buyvolume;

        mysqli_query($link, "UPDATE rawmat SET `volume` = '$upvol' WHERE `id` = '$id'");	

        Next1:

        {
            //supplier tracking system
            $supac = mysqli_query($link, "SELECT * FROM supplier WHERE name='$_POST[supplier]'");
            while($rowac = mysqli_fetch_array($supac))
            { $spid = $rowac['id'];}

            // assign insertion pattern
            $rawid = "R".$id;
            // assign insertion pattern
            $sql_insert = "INSERT into `sp_$spid`	(`date`,`inid`,`inv_num`, `price`, `payment`)
            VALUES  ('$bday','$rawid','$_POST[inv_num]','$buyprice','$_POST[pay]')";
            mysqli_query($link, $sql_insert);

            // accounting system
            if($buyprice!=0)
            {
                if ($_POST['pay'] == '1')
                { 
                    $sup_ac = $_POST['payby'];
                    if($_POST['payby'] != 10000001)//ค่าธรรมเนียม 10000001 เงินสด 10000002-10000249 ธนาคาร
                    {
                        if($_POST['fee']!=0)
                        {
                            // assign insertion pattern
                            $pacnum = $_POST['payby'] + 40000000;
                            $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`bors`,`recordby`)
                            VALUES  (now(),'$pacnum','$_POST[payby]','ค่าธรรมเนียมการโอนเงิน/ขนส่ง','$_POST[fee]','c','p','$_SESSION[user_id]')";
                            mysqli_query($link, $sql_insert) ;
                            // now reset fee
                            $_POST['fee']=0;
                        }
                    }
                }
                else 
                {
                    $supac = mysqli_query($link, "SELECT ac_no FROM supplier WHERE name='$_POST[supplier]'");
                    while($rowac = mysqli_fetch_array($supac))
                    {$sup_ac = $rowac['ac_no'];}
                }
                // assign insertion pattern
                if($buyprice>0)
                {
                    $detail ="ซื้อ ".$_POST['supplier'].' '.$_POST['inv_num'];
                    $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`, `inv_num`, `price`,`type`,`bors`,`recordby`)
                    VALUES  (now(),'$dacno','$sup_ac','$detail','$_POST[inv_num]','$buyprice','c','b','$_SESSION[user_id]')";
                    mysqli_query($link, $sql_insert);
                }
            }      

        }
        Noid:
    }
    // go on to other step
    //header("Location: stock.php"); 
    if(isset($_POST['doSave']))
    {
        for($i=1;$i<=$_SESSION['DG'];$i++)
        {
            unset($_SESSION['rawmat'.$i]);
            unset($_SESSION['rawname'.$i]);
            unset($_SESSION['size'.$i]);
            unset($_SESSION['rawcode'.$i]);
            unset($_SESSION['sunit'.$i]);
            unset($_SESSION['vol'.$i]);
            unset($_SESSION['buyprice'.$i]);
            unset($_SESSION['expd'.$i]);
        }
        unset($_SESSION['DG']);
        unset($_SESSION['supplier']);
        unset($_SESSION['invoice']);

        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
    }
}

if($_SESSION['DG']<1)
{ 
  $_SESSION['DG']=1;
}

$title = "::ห้องคลังวัตถุดิบ::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../libs/autormin.php';
$formid = "inForm";
include '../../libs/validate.php';
include '../../main/bodyheader.php';

?>
<div id="content"><h3 class="titlehdr">นำเข้าวัตถุดิบ: </h3>
<form name="ddx" method="post" action="rawstockinall.php" id="inForm">
<table width="90%" border="1" align="center" cellpadding="3" cellspacing="3" class="forms">
<tr><td>วันที่&nbsp;
    <select name="day">
    <option value="<?php echo (idate("d"));?>" selected><?php echo (idate("d"));?></option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    <option value="6">6</option>
    <option value="7">7</option>
    <option value="8">8</option>
    <option value="9">9</option>
    <option value="10">10</option>
    <option value="11">11</option>
    <option value="12">12</option>
    <option value="13">13</option>
    <option value="14">14</option>
    <option value="15">15</option>
    <option value="16">16</option>
    <option value="17">17</option>
    <option value="18">18</option>
    <option value="19">19</option>
    <option value="20">20</option>
    <option value="21">21</option>
    <option value="22">22</option>
    <option value="23">23</option>
    <option value="24">24</option>
    <option value="25">25</option>
    <option value="26">26</option>
    <option value="27">27</option>
    <option value="28">28</option>
    <option value="29">29</option>
    <option value="30">30</option>
    <option value="31">31</option>
    </select>
    &nbsp;เดือน &nbsp;
    <select name="month">
    <option value="<?php echo (idate("m"));?>" selected><?php echo (idate("m"));?></option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    <option value="6">6</option>
    <option value="7">7</option>
    <option value="8">8</option>
    <option value="9">9</option>
    <option value="10">10</option>
    <option value="11">11</option>
    <option value="12">12</option>
    </select>
    พ.ศ. <input name="year" size="5" maxlength="4" type="text" value="<?php echo (idate("Y")+543);?>"><br>
    </td>
    <td></td>
</tr>
<?php //------
if($prod!='1')
{
?>	
<tr><td width=50% >บริษัท&nbsp;
    <select name="supplier" class="required" >
    <?php
    $supplier = mysqli_query($link, "SELECT name FROM supplier");
    while($sprow = mysqli_fetch_array($supplier))
    {
        echo "<option value=\"";
        echo $sprow['name'];
        echo " \">";
        echo $sprow['name']."</option>";
    }
    if(!empty($_SESSION['supplier']))
    {
        echo "<option value=\"";
        echo $_SESSION['supplier'];
        echo " \"";
        echo " selected";
        echo ">";
        echo $_SESSION['supplier']."</option>";
    }
    ?>
    </select>
</td><td width=50%>
    <?php 
    //for own product 
    if($prod != '1')
    {
        echo "ใบส่งของเลขที่&nbsp;<input name='inv_num' type='text' class='required' value=";
        echo $_SESSION['invoice'];
        echo ">";
    }
    ?>
</td></tr>
<?php 
}
?>
</table>
<table width="90%" border="1" align="center" cellspacing="3" cellpadding="3" class="forms">
    <tr><td>
     <table border='1'  class='TFtable' style='text-align: center; margin-left: auto; margin-right: auto;' >
      <tr><th>Order</th><th width="20%">Barcode/รหัสสินค้า</th><th width="30%">ชื่อสินค้า</th><th>ขนาด</th><th>EXPD</th><th>จำนวน</th><th>ราคา</th><th>เพิ่ม</th><th>ลบ</th></tr>
	    <?php
	    for($j=1;$j<=$_SESSION['DG'];$j++)
	    {
            if($j==$_SESSION['DG'])
            {
                echo "<tr><td>".$j."</td><td><input name='rawmat".$j."' type=text id=rawmat".$j;
                echo " value='".$_SESSION['rawmat'.$j]."' size=20% autofocus/></td>";
            }
            else
            {
                echo "<tr><td>".$j."</td><td><input name='rawmat".$j."' type=text id=rawmat".$j;
                echo " value='".$_SESSION['rawmat'.$j]."' size=20%/></td>";
            }
            echo "<td>";
            if(!empty($_SESSION['rawcode'.$j]))
            echo $_SESSION['rawcode'.$j]."-".$_SESSION['rawname'.$j]."-".$_SESSION['size'.$j]."-".$_SESSION['sunit'.$j];
            echo "</td>";
            echo "<td>";
            echo $_SESSION['size'.$j];
            echo "</td>";
            echo "<td>";
            echo "<input type='date' name='expd".$j."' min=\"".date("Y-m-d")."\" value=".$_SESSION['expd'.$j].">";
            echo "</td>";
            echo "<td>";
            echo "<input type='number' tabindex=".($j*2-1)." class='typenumber' name='vol".$j."' min=0 step=1 value=".$_SESSION['vol'.$j].">";
            echo "</td>";
            echo "<td>";
            echo "<input type='number' tabindex=".($j*2)." class='typenumber' name='buyprice".$j."' min=0 step=0.01 value=".$_SESSION['buyprice'.$j].">";
            echo "</td>";
            echo "<td><input type=submit name='add' value='เพิ่ม'></td>";
            echo "<td><input type=submit name='del".$j."' value='ลบ'></td>";
            echo "</tr>";
	    }
	    ?>
      </table>
    </td></tr>
</table>
<table width="90%" border="1" align="center" cellpadding="3" cellspacing="3" class="forms">
<tr><td><div style="text-align: center;">
การชำระเงิน&nbsp;<input type="radio" tabindex="61" name="pay" value="1">จ่ายแล้ว<input type="radio" tabindex="62" name="pay" CHECKED value="0">ค้างจ่าย
</div></td></tr>
<tr><td><div style="text-align: center;">
    ชำระโดย&nbsp;<select tabindex="63" name="payby">
    <?php //10000001 เงินสด 10000002-10000249 ธนาคาร
    $acname = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 10000001 AND ac_no <=10000249");
    while($row = mysqli_fetch_array($acname))
    {
        echo "<option value='";
        echo $row['ac_no'];
        echo "'>";
        echo $row['name'];
        echo "</option>";
    }	
    ?>
    </select>&nbsp;ค่าธรรมเนียมการโอน<input type="number" min=0 name="fee" size="6" >
</div></td></tr>
</table>
<p align="center"><input name="doSave" type="submit" id="doSave" value="Save"></p>
</form></div>
</body>
</html>
