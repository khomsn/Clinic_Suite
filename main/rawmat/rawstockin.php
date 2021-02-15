<?php 
include '../../config/dbc.php';
page_protect();

$fulluri = $_SERVER['REQUEST_URI'];
$id = filter_var($fulluri, FILTER_SANITIZE_NUMBER_INT);

$stock_in = mysqli_query($link, "select * from rawmat where id='$id' ");
$rawmattable = "rawmat_".$id;
//check if table exist
include '../../libs/rawmat_table.php';

while ($row_settings = mysqli_fetch_array($stock_in))
{
    $volume = $row_settings['volume']; //get volume to update
    $dacno = $row_settings['ac_no']; //get account no into stock 
    $sunit = $row_settings['sunit'];
}

if($_POST['doSave'] == 'Save')  
{
    $day = $_POST['day'];
    $month = $_POST['month'];
    $byear = $_POST['year'];
    $year = $byear - 543;
    
    // format date for mysql
    $bday = $year.'-'.$month.'-'.$day;
    // assign insertion pattern
    if($_POST['expd']=="")
    {
    $sql_insert = "INSERT into `$rawmattable`	(`date`,`supplier`,`inv_num`, `volume`, `price`)
    VALUES  ('$bday','$_POST[supplier]','$_POST[inv_num]','$_POST[volume]','$_POST[price]')";
    }
    else
    {
    $sql_insert = "INSERT into `$rawmattable`	(`date`, `expdate`,`supplier`,`inv_num`, `volume`, `price`)
    VALUES  ('$bday','$_POST[expd]','$_POST[supplier]','$_POST[inv_num]','$_POST[volume]','$_POST[price]')";
    }

    /************ fixed insertion drop bug*****************************/
    /* check last row then compair with new last-row after insertion **/
    /******************************************************************/
    $din = mysqli_query($link, "select MAX(id) from $rawmattable ");
    $maxrow = mysqli_fetch_row($din);
    $oldmaxrow = $maxrow[0];
    if(is_null($oldmaxrow)) $oldmaxrow = 0;
    $newmaxrow = $oldmaxrow;
    while ($oldmaxrow == $newmaxrow){ 
        // Now insert Drug order information to "drug_#id" table
        mysqli_query($link, $sql_insert);
        // check for newmaxrow
        $maxrow = mysqli_fetch_row($din);
        $newmaxrow = $maxrow[0];
    }

    // Update rawmat at volume and buyprice.
    $upvol = $volume + $_POST['volume'];
    mysqli_query($link, "UPDATE rawmat SET `volume` = '$upvol' WHERE `id` = '$id'");	

    //supplier tracking system
    $supac = mysqli_query($link, "SELECT * FROM supplier WHERE name='$_POST[supplier]'");
    while($rowac = mysqli_fetch_array($supac))
    { $spid = $rowac['id'];}

    // assign insertion pattern
    $rawid = "R".$id;
    $sql_insert = "INSERT into `sp_$spid`	(`date`,`inid`,`inv_num`, `price`, `payment`)
    VALUES  ('$bday','$rawid','$_POST[inv_num]','$_POST[price]','$_POST[pay]')";
    mysqli_query($link, $sql_insert);

    // accounting system
    if ($_POST['pay'] == '1')
    { 
        $sup_ac = $_POST['payby'];
        if($_POST['payby'] != 10000001)//ค่าธรรมเนียม 10000001 เงินสด 10000002-10000249 ธนาคาร
        {
            if($_POST['free']!=0)
            {
                // assign insertion pattern
                $pacnum = $_POST['payby'] + 40000000;
                $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`bors`,`recordby`)
                VALUES  (now(),'$pacnum','$_POST[payby]','ค่าธรรมเนียมการโอนเงิน','$_POST[free]','c','p','$_SESSION[user_id]')";
                mysqli_query($link, $sql_insert);
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
    $detail ="ซื้อ ".$_POST['supplier'].' '.$_POST['inv_num'];
    $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`, `inv_num`, `price`,`type`,`bors`,`recordby`)
    VALUES  (now(),'$dacno','$sup_ac','$detail','$_POST[inv_num]','$_POST[price]','c','b','$_SESSION[user_id]')";
    mysqli_query($link, $sql_insert);

    // go on to other step
    //header("Location: stock.php"); 
    if(isset($_POST['doSave']))
    {
        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
    }
}

$title = "::นำเข้า Raw Material::";
include '../../main/header.php';
$formid = "inForm";
include '../../libs/validate.php';
include '../../main/bodyheader.php';

?>
<div id="content">
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
<tr><td>
    <h3 class="titlehdr">นำเข้า Raw Material: </h3>
    <?php
        $stock_in = mysqli_query($link, "select * from rawmat where id='$id' ");
        while ($row_settings = mysqli_fetch_array($stock_in))
        {
            echo "<div style=\"text-align: center; background-color:rgba(0,255,0,0.7);\">";
            echo $row_settings['rawcode'];
            echo "&nbsp;(";
            echo $row_settings['rawname'];
            echo ")&nbsp;ขนาด :&nbsp;";
            echo $row_settings['size'];
            echo "</div>";
        }
    ?>
    <form action="rawstockin.php?msg=<?php echo $id?>" method="post" name="inForm" id="inForm">
    <table width="90%" border="1" align="center" cellpadding="3" cellspacing="3" class="forms">
        <tr><td>วันที่&nbsp;
                <select tabindex="1" name="day">
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
                <select tabindex="2" name="month">
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
                พ.ศ. <input tabindex="3" name="year" size="5" maxlength="4" type="text" value="<?php echo (idate("Y")+543);?>"><br>
            </td><td>EXP date:<input tabindex="6" name="expd" type="date" min="<?php echo date("Y-m-d");?>"></td>
        </tr>
        <tr><td width=50% >บริษัท&nbsp;
                <select  tabindex="4" name="supplier" class="required" >
                    <?php
                        $supplier = mysqli_query($link, "SELECT name FROM supplier");
                        while($sprow = mysqli_fetch_array($supplier))
                        {
                            echo "<option value=\"";
                            echo $sprow['name'];
                            echo " \">";
                            echo $sprow['name']."</option>";
                        }
                    ?>
                </select>
            </td><td width=50%>ใบส่งของเลขที่ <input tabindex='5' name='inv_num' type='text' class='required'><br>
        </td></tr>
        <tr><td width =350>จำนวน&nbsp;<input tabindex="6" name="volume" type="number" class="required" min=0 ><?php echo $sunit;?></td>
            <td width =350>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            ราคา&nbsp;<input tabindex="7" name="price" type="number" min=0 step=.01 class="required"> บาท </td>
        </tr>
    </table>
    <table width="90%" border="1" align="center" cellpadding="3" cellspacing="3" class="forms">
        <tr><td><div style="text-align: center;">การชำระเงิน<input type="radio" tabindex="8" name="pay" value="1">จ่ายแล้ว<input type="radio" tabindex="9" name="pay" CHECKED value="0">ค้างจ่าย
        </div></td></tr>
        <tr><td><div style="text-align: center;">
            ชำระโดย&nbsp;<select tabindex="10" name="payby">
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
            </select>&nbsp;ค่าธรรมเนียมการโอน<input type="number" min=0 name="free" size="6" >
        </div></td></tr>
    </table>
    <p align="center"><input name="doSave" type="submit" id="doSave" value="Save"></p>
    </form>
</td></tr>
</table>
</div>
</body>
</html>
