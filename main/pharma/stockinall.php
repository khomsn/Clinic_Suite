<?php
include '../../config/dbc.php';
page_protect();

if($_POST['additem'] == "เพิ่ม") 
{
    $_SESSION['supplier'] = $_POST['supplier'];
    $_SESSION['invoice'] = $_POST['inv_num'];

    //update post value first
    for($j=1;$j<=$_SESSION['DGinall'];$j++)
    {
        $_SESSION['stindrug'.$j]=$_POST['stindrug'.$j];
        $_SESSION['stinvol'.$j]=$_POST['stinvol'.$j];
        $_SESSION['stinexpd'.$j]=$_POST['stinexpd'.$j];
        $_SESSION['stinbuyprice'.$j]=$_POST['stinbuyprice'.$j];
        $_SESSION['stinexpd'.$j]=$_POST['stinexpd'.$j];
    }
    
    $j=$_SESSION['DGinall'];
    //then check if it is valid post
    $drug = $_SESSION['stindrug'.$j];
    
    if(ltrim($drug)!=='')
    {
        $drugid[$j] = strstr($drug, '-', true);
        $ptin = mysqli_query($link, "select * from drug_id where id='$drugid[$j]'");
        while ($row2 = mysqli_fetch_array($ptin))
        {
            $_SESSION['stindgname'.$j]= $row2['dgname'];
            $_SESSION['stinsize'.$j] =  $row2['size'];
            $_SESSION['stindname'.$j]= $row2['dname'];
            $_SESSION['stinunit'.$j] = $row2['unit'];
            $validid = $row2['id'];
        }

       if($validid)
        {
            $_SESSION['DGinall'] = $_SESSION['DGinall']+1;
        }
        if(!$validid)
        {
            unset($_SESSION['stindrug'.$j]);
        }
    }
}

for($n=1;$n<=$_SESSION['DGinall'];$n++)
{
    if($_POST['delitem'.$n]=='ลบ')
    {
        for($m=$n;$m<$_SESSION['DGinall'];$m++)
        {
            $j=$m+1;
            
            $_SESSION['stindrug'.$m]=$_SESSION['stindrug'.$j];
            $_SESSION['stindname'.$m]=$_SESSION['stindname'.$j];
            $_SESSION['stinsize'.$m]=$_SESSION['stinsize'.$j];
            $_SESSION['stindgname'.$m]=$_SESSION['stindgname'.$j];
            $_SESSION['stinunit'.$m]=$_SESSION['stinunit'.$j];
            $_SESSION['stinexpd'.$m]=$_SESSION['stinexpd'.$j];
            $_SESSION['stinvol'.$m]=$_SESSION['stinvol'.$j];
            $_SESSION['stinexpd'.$m]=$_SESSION['stinexpd'.$j];
            $_SESSION['stinbuyprice'.$m]=$_SESSION['stinbuyprice'.$j];
        }
        unset($_SESSION['stindname'.$m]);
        unset($_SESSION['stindgname'.$m]);
        unset($_SESSION['stinsize'.$m]);
        unset($_SESSION['stindrug'.$m]);
        unset($_SESSION['stinunit'.$m]);
        unset($_SESSION['stinexpd'.$m]);
        unset($_SESSION['stinvol'.$m]);
        unset($_SESSION['stinexpd'.$m]);
        unset($_SESSION['stinbuyprice'.$m]);
     
        if($_SESSION['DGinall']>1)
        {
            $_SESSION['DGinall'] = $_SESSION['DGinall']-1;
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
      
    for($a=1;$a<=$_SESSION['DGinall'];$a++)
    {
        $_SESSION['stindrug'.$a]=$_POST['stindrug'.$a];
        $drug = $_SESSION['stindrug'.$a];
        $drugid[$a] = strstr($drug, '-', true); 
        $id = $drugid[$a];
        if(empty($id) or $id == 0) goto Noid; //check for blank input
        $buyvolume = $_POST['stinvol'.$a];
        if(empty($buyvolume) or $buyvolume == 0) goto Noid; //check for blank input
        $buyprice = $_POST['stinbuyprice'.$a];
        $stock_in = mysqli_query($link, "select * from drug_id where id='$id' ");
        
        $drugtable = "drug_".$id;
               
        while ($row_settings = mysqli_fetch_array($stock_in))
        {
            $volume = $row_settings['volume']; //get volume to update
            $dacno = $row_settings['ac_no']; //get account no into stock 
            $prod = $row_settings['prod'];
        }
        $expd = $_POST['stinexpd'.$a];
        if(empty($expd))
        {// assign insertion pattern
            $sql_insert = "INSERT into `$drugtable`	(`date`,`supplier`,`inv_num`, `volume`, `price`)
                        VALUES  ('$bday','$_POST[supplier]','$_POST[inv_num]','$buyvolume','$buyprice')";
        }
        else
        {// assign insertion pattern
            $sql_insert = "INSERT into `$drugtable`	(`date`,`expdate`,`supplier`,`inv_num`, `volume`, `price`)
                        VALUES  ('$bday','$expd','$_POST[supplier]','$_POST[inv_num]','$buyvolume','$buyprice')";
        }
        /************ fixed insertion drop bug*****************************/
        /* check last row then compair with new last-row after insertion **/
        /******************************************************************/
        $din = mysqli_query($link, "select MAX(id) from $drugtable ");
        $maxrow = mysqli_fetch_row($din);
        $oldmaxrow = $maxrow[0];
        $newmaxrow = $oldmaxrow;
        while ($oldmaxrow == $newmaxrow){ 
            // Now insert Drug order information to "drug_#id" table
            mysqli_query($link, $sql_insert);
            // check for newmaxrow
            $maxrow = mysqli_fetch_row($din);
            $newmaxrow = $maxrow[0];
        }
        // Update drug_id at volume.
        $upvol = $volume + $buyvolume;

        mysqli_query($link, "UPDATE drug_id SET `volume` = '$upvol' WHERE `id` = '$id'");	

        //for own product 
        if($prod == '1')
        {
            if($buyprice==0) goto Next1;
            
            $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`bors`,`recordby`)
                                VALUES  (now(),'$dacno','10700000','สินค้าจากวัตถุดิบ','$buyprice','c','p','$_SESSION[user_id]')";
            // Now insert Drug order information to "drug_#id" table
            mysqli_query($link, $sql_insert) ;
        }
        
        //for product not register in supplier and account
        Next1:

        if($prod != '1')
        {
            //supplier tracking system
            $supac = mysqli_query($link, "SELECT * FROM supplier WHERE name='$_POST[supplier]'");
            while($rowac = mysqli_fetch_array($supac))
            { $spid = $rowac['id'];}

            // assign insertion pattern
            $sql_insert = "INSERT into `sp_$spid`	(`date`,`inid`,`inv_num`, `price`, `payment`)
                                        VALUES  ('$bday','$id','$_POST[inv_num]','$buyprice','$_POST[pay]')";
            // Now insert Drug order information to "drug_#id" table
            mysqli_query($link, $sql_insert);

            // accounting system
            if($buyprice!=0)
            {
                if ($_POST['pay'] == '1')
                { 
                    $sup_ac = $_POST['payby'];
                    if($_POST['payby'] != 10000001)//ค่าธรรมเนียม
                    {
                        if($_POST['fee']!=0)
                        {
                            // assign insertion pattern
                            $pacnum = $_POST['payby'] + 40000000;
                            $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`bors`,`recordby`)
                            VALUES  (now(),'$pacnum','$_POST[payby]','ค่าธรรมเนียมการโอนเงิน/ค่าขนส่ง','$_POST[fee]','c','p','$_SESSION[user_id]')";
                            // Now insert Drug order information to "drug_#id" table
                            mysqli_query($link, $sql_insert);
                            //reset fee
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
                    $detail ="ซื้อ ".$_SESSION['supplier'].' '.$_SESSION['invoice'];
                    $sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`, `inv_num`, `price`,`type`,`bors`,`recordby`)
                                        VALUES  (now(),'$dacno','$sup_ac','$detail','$_SESSION[invoice]','$buyprice','c','b','$_SESSION[user_id]')";
                    // Now insert Drug order information to "drug_#id" table
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
        for($i=1;$i<=$_SESSION['DGinall'];$i++)
        {
            unset($_SESSION['stindrug'.$i]);
            unset($_SESSION['stindgname'.$i]);
            unset($_SESSION['stinsize'.$i]);
            unset($_SESSION['stindname'.$i]);
            unset($_SESSION['stinunit'.$i]);
            unset($_SESSION['stinvol'.$i]);
            unset($_SESSION['stinexpd'.$i]);
            unset($_SESSION['stinbuyprice'.$i]);
            unset($_SESSION['stinexpd'.$i]);
        }
        unset($_SESSION['DGinall']);
        unset($_SESSION['supplier']);
        unset($_SESSION['invoice']);
        
        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
    }
}

if($_SESSION['DGinall']<1)
{ 
  $_SESSION['DGinall']=1;
}

$title = "::นำเข้ายาและผลิตภัณฑ์::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../libs/autodrugin.php';
$formid = "inForm";
include '../../libs/validate.php';
include '../../main/bodyheader.php';

?>
<div id="content"><h3 class="titlehdr">นำเข้ายาและผลิตภัณฑ์: </h3>
<form name="ddx" method="post" action="stockinall.php" id="inForm">
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
            ?><br>
            </td>
    </tr>
    <?php 
    }
    ?>
</table>

<table width="90%" border='1' style='text-align: center; margin-left: auto; margin-right: auto;' class="TFtable">
<tr><th>Order</th><th width="20%">Barcode/รหัสสินค้า</th><th width="30%">ชื่อสินค้า</th><th>ขนาด</th><th>EXPD</th><th>จำนวน</th><th>ราคา</th><th>เพิ่ม</th><th>ลบ</th></tr>
<?php
for($j=1;$j<=$_SESSION['DGinall'];$j++)
{
if($j==$_SESSION['DGinall'])
{
echo "<tr><td>".$j."</td><td><input name='stindrug".$j."' type=text id=drug";
echo " value='".$_SESSION['stindrug'.$j]."' size=20% autofocus/></td>";
}
else
{
echo "<tr><td>".$j."</td><td><input name='stindrug".$j."' type=text ";
echo " value='".$_SESSION['stindrug'.$j]."' size=20%/></td>";
}
echo "<td>";
if(!empty($_SESSION['stindname'.$j]))
echo $_SESSION['stindname'.$j]."-".$_SESSION['stindgname'.$j]."-".$_SESSION['stinsize'.$j]."-".$_SESSION['stinunit'.$j];
echo "</td>";
echo "<td>";
echo $_SESSION['stinsize'.$j];
echo "</td>";
echo "<td>";
echo "<input type='date' tabindex=".($j*2-1)." name='stinexpd".$j."' min='".date("Y-m-d")."' value=".$_SESSION['stinexpd'.$j].">";
echo "</td>";
echo "<td>";
echo "<input type='number' tabindex=".($j*2-1)." class='typenumber' name='stinvol".$j."' min=0 step=1 value=".$_SESSION['stinvol'.$j].">";
echo "</td>";
echo "<td>";
echo "<input type='number' tabindex=".($j*2)." class='typenumber' name='stinbuyprice".$j."' min=0 step=0.01 value=".$_SESSION['stinbuyprice'.$j].">";
echo "</td>";
echo "<td><input type=submit name='additem' value='เพิ่ม'></td>";
echo "<td><input type=submit name='delitem".$j."' value='ลบ'></td>";
echo "</tr>";
}
?>
</table>

<table width="90%" border="1" align="center" cellpadding="3" cellspacing="3" class="forms">
    <tr><td><div style="text-align: center;">
    การชำระเงิน&nbsp;<input type="radio" tabindex="61" name="pay" value="1">จ่ายแล้ว<input type="radio" tabindex="62" name="pay" CHECKED value="0">ค้างจ่าย
    </div></td></tr>
    <tr><td><div style="text-align: center;">
        ชำระโดย&nbsp;<select tabindex="63" name="payby">
        <?php //10000001 เงินสด 10000002-10000249 ธนาคาร
        $acname = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 10000001 AND ac_no <= 10000249");
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
