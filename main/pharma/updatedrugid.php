<?php 
include '../../config/dbc.php';
page_protect();

$id = $_SESSION['drugid'];

$stock_in = mysqli_query($link, "select * from drug_id where id='$id' ");

if($_POST['register'] == 'แก้ไข') 
{ 
    //set to 0 if not checked
    if($_POST['stcp']!=1) $_POST['stcp']=0;

    mysqli_query($link, "UPDATE drug_id SET
                `uses` = '$_POST[uses]',
                `indication` = '$_POST[Indication]',
                `sellprice` = '$_POST[sellprice]',
                `min_limit` = '$_POST[min_limit]',
                `typen` = '$_POST[type]',
                `groupn` = '$_POST[group]',
                `subgroup` = '$_POST[subgroup]',
                `unit` = '$_POST[unit]',
                `cat` = '$_POST[cat]',
                `candp` = '$_POST[candp]',
                `staffcanorder` = '$_POST[storder]',
                `stcp` = '$_POST[stcp]'
                WHERE id='$id'
                ");
    // go on to other step
    header("Location: druglist.php");  
}
$title = "ยาและผลิตภัณฑ์";
include '../../main/header.php';
$formid = "regForm";
include '../../libs/validate.php';
include '../../libs/popup.php';
include '../../main/bodyheader.php';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
<tr><td width="160" valign="top"><div class="pos_l_fix">
<?php 
        /*********************** MYACCOUNT MENU ****************************
        This code shows my account menu only to logged in users. 
        Copy this code till END and place it in a new html or php where
        you want to show myaccount options. This is only visible to logged in users
        *******************************************************************/
        if (isset($_SESSION['user_id']))
        {
            include 'drugmenu.php';
        } 
    /*******************************END**************************/
?></div></td>
    <td><div align="center"><h3 class="titlehdr">แก้ไข ทะเบียนยา และ ผลิตภัณฑ์</h3>
    <form method="post" action="updatedrugid.php" name="regForm" id="regForm">
    <table style="text-align: left; width: 703px; height: 413px;" border="1" cellpadding="2" cellspacing="2"  class="forms">
        <tr><td style="width: 646px; vertical-align: middle;">
        <div style="text-align: center;">ชื่อ: &nbsp; 
        <?php
        while ($row_settings = mysqli_fetch_array($stock_in))
        {
            echo $row_settings['dname'];
            echo "<br>ชื่อสามัญ: &nbsp;"; 
            echo $row_settings['dgname'];
            echo "<br>ขนาด: &nbsp;";
            echo $row_settings['size'];
            $cat = $row_settings['cat'];
            $candp = $row_settings['candp'];
            $storder = $row_settings['staffcanorder']; 
            echo "&nbsp;|{<a HREF=\"packagetype.php\" onClick=\"return popup(this,'name','300','500','yes');\">รูปแบบการสั่งซื้อ:</a>";
            echo "<select name=unit><option value=\"";
            echo $row_settings['unit'];
            echo "\" selected>";
            echo $row_settings['unit'];
            echo "</option>";
            $dpackagetype = mysqli_query($link, "SELECT * FROM packagetype");
            // keeps getting the next row until there are no more to get
            while($row = mysqli_fetch_array($dpackagetype))
            {
                echo "<option value='".$row['name']."'>".$row['name']."</option>";
            }
            ?>
            </select>}|<br>
            <input type="radio" name="cat" class="required" value="A"<?php if($cat =='A') echo "checked";?>>Cat A
            <input type="radio" name="cat" class="required" value="B"<?php if($cat =='B') echo "checked";?>>Cat B
            <input type="radio" name="cat" class="required" value="C"<?php if($cat =='C') echo "checked";?>>Cat C
            <input type="radio" name="cat" class="required" value="D"<?php if($cat =='D') echo "checked";?>>Cat D
            <input type="radio" name="cat" class="required" value="X"<?php if($cat =='X') echo "checked";?>>Cat X
            <input type="radio" name="cat" class="required" value="N"<?php if($cat =='N') echo "checked";?>>Cat N
            <hr>
            Course / Programs Lab:<br>
            <input type="radio" name="candp" class="required" value="0" <?php if($candp =='0') echo "checked";?>>None
            <input type="radio" name="candp" class="required" value="1" <?php if($candp =='1') echo "checked";?>>Treatment
            <input type="radio" name="candp" class="required" value="2" <?php if($candp =='2') echo "checked";?>>Programs+Labs
            <hr>
            พนักงาน สั่งได้:<br>
            <input type="radio" name="storder" class="required" value="0" <?php if($storder =='0') echo "checked";?>>No
            <input type="radio" name="storder" class="required" value="1" <?php if($storder =='1') echo "checked";?>>Yes</div>
            <hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
            <div style="text-align: center;">
            วิธีใช้:
            <textarea cols="80" rows="1" name="uses" value="<?php
            echo $row_settings['uses'];
            ?>
            "><?php
            echo $row_settings['uses'];
            ?></textarea>
            </div>
            <hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
            <div style="text-align: center;">
            Indication:
            <textarea cols="80" rows="1" name="Indication"	value="<?php
            echo $row_settings['indication'];
            ?>
            "><?php
            echo $row_settings['indication'];
            ?></textarea>
            </div>
            <hr style="width: 80%; height: 2px;"><br>
            <div style="text-align: center;">
            ราคาขาย: <input type=number class="typenumber" step=0.01  name="sellprice" value="<?php	echo $row_settings['sellprice']; ?>"> บาท
            &nbsp; &nbsp; &nbsp;
            จำนวนคงคลังขั้นต่ำ
            <input type=number class="typenumber" name="min_limit" value="<?php
            echo $row_settings['min_limit'];
            ?>">พนักงานร่วมจ่ายต้นทุน:<input type="checkbox" name="stcp" value=<?php if($row_settings['stcp']==1) echo "1 checked"; else echo "1"; ?>><br>
            </div>
            <hr style="width: 80%; height: 2px;"><br>
            <div style="text-align: center;">
            <?php	
            $dtype = mysqli_query($link, "SELECT name FROM drug_type");
            $dgroup = mysqli_query($link, "SELECT name FROM drug_group");
            $subgroup = mysqli_query($link, "SELECT name FROM drug_subgroup");
            ?>
            ประเภท&nbsp;
            <select  name="type">
            <option value="<?php
            echo $row_settings['typen'];
            ?>" selected>
            <?php
            echo $row_settings['typen'];
            ?>
            </option>
            <?php while($trow = mysqli_fetch_array($dtype))
            {
            echo "<option value=\"";
            echo $trow['name'];
            echo "\">";
            echo $trow['name']."</option>";
            }
            ?>
            </select>
            &nbsp; &nbsp; &nbsp; &nbsp; 
            กลุ่ม<sup>1</sup>
            <select name="group"><option value=""></option>
            <?php 
            $dg = $row_settings['groupn'];
            while($grow = mysqli_fetch_array($dgroup))
            {
            echo "<option value=".$grow['name'];
            if($dg==$grow['name']) echo " selected";
            echo ">";
            echo $grow['name']."</option>";
            }
            ?>
            </select>
            &nbsp; &nbsp; &nbsp; &nbsp;<a HREF="subgroup.php" onClick="return popup(this,'name','300','500','yes');" >กลุ่ม<sup>2</sup></a>
            <select name="subgroup"><option value=""></option>
            <?php 
            $sg = $row_settings['subgroup'];
            while($grow = mysqli_fetch_array($subgroup))
            {
            echo "<option value=".$grow['name'];
            if($sg==$grow['name']) echo " selected";
            echo ">";
            echo $grow['name']."</option>";
            }
        }
        ?>
        </select>
        </div>
        </td></tr>
        <tr><td><div style="text-align: center;"><input name="register" value="แก้ไข" type="submit"></div></td></tr>
    </table>
    </form>
</div></td><td width=160px></td></tr>
</table>
</body></html>
