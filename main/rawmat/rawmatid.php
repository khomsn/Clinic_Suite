<?php 
include '../../config/dbc.php';
page_protect();
$sql_create = "
CREATE TABLE IF NOT EXISTS `rawmat` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `rawcode` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rawname` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sunit` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lowlimit` smallint(6) NOT NULL DEFAULT '0',
  `volume` smallint(6) NOT NULL DEFAULT '0',
  `ac_no` int(11) NOT NULL,
  `rmfpd` tinyint(1) NOT NULL DEFAULT '0',
  `rmtype` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `location` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

mysqli_query($link, $sql_create);

if($_POST['register'] == 'ตกลง') 
{ 

    $rawcode =$_POST['rawcode'];
    $rawname = $_POST['rawname'];
    $size = $_POST['size'];
    $adn = $rawcode.'-'.$rawname.'-'.$size;

    //assign account no. 10700000-10999999 วัตถุดิบ 10700000 ตัดยอด //start with 10700000 + rawmat_id
    $ac = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no>=10700000 AND ac_no<10999999 ORDER BY ac_no ASC");

    $mmm=10700000;
    $maxm =10999999;
    // ตรวจสอบ ac_no จาก acname ก่อน 
    while($row = mysqli_fetch_array($ac))
    { 
        if(empty($row['ac_no'])) goto Nextacno;
        if( $mmm < $row['ac_no'] and $row['ac_no']<$maxm )
        { 
            $newmmm =$row['ac_no'];
            if($newmmm==($mmm+1))
            {
                $mmm=$row['ac_no'];
            }
            else goto Nextacno;
        }
    }
    Nextacno:
    // assign account number to new product.
    $ac = $mmm + 1;

    //
    $rs_duplicate = mysqli_query($link, "select count(*) as total from rawmat where rawcode='$_POST[rawcode]' AND rawname='$_POST[rawname]' AND size='$_POST[size]' ");
    list($total) = mysqli_fetch_row($rs_duplicate);

    if ($total > 0)
    {
        $err[] = "ERROR - This RawMat Code already exists. Please Check.";
        //header("Location: register.php?msg=$err");
        //exit();
    }
    /***************************************************************************/

    if(empty($err))
    {
        //assign ac_no to table acnumber
        $sql_insert = "INSERT into `acnumber` (`ac_no`, `name`) VALUES  ('$ac','$adn')";
        mysqli_query($link, $sql_insert);

        // assign insertion pattern
        $sql_insert = "INSERT into `rawmat`
        (`rawcode`,`rawname`,`size`,`sunit`, `lowlimit`,`ac_no`,`rmfpd`,`rmtype`)
        VALUES
        ('$_POST[rawcode]','$_POST[rawname]','$_POST[size]','$_POST[unit]','$_POST[min_limit]','$ac','$_POST[rmpd]','$_POST[rwtype]')";

        // Now insert  to "rawmat" table
        mysqli_query($link, $sql_insert);

        // Then get ID to process to other step.
        $result = mysqli_query($link, "SELECT * FROM rawmat
        WHERE rawcode='$rawcode' AND rawname='$rawname' AND size='$size'");

        $row = mysqli_fetch_array($result);
        // Pass  ID as a session parameter.
        //$_SESSION['rawmat']= $row['id'];
        $rawmattable = "rawmat_".$row['id'];

        include '../../libs/rawmat_table.php';
    }
    // go on to other step
    header("Location: rawmatid.php");  

}

$title = "::ห้องคลังวัตถุดิบ::";
include '../../main/header.php';
include '../../libs/popup.php';
$formid = "regForm";
include '../../libs/validate.php';
include '../../main/bodyheader.php';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
<tr><td width="160" valign="top"><div class="pos_l_fix">
    <?php 
    if (isset($_SESSION['user_id']))
    {
    include 'rawmatmenu.php';
    } 
    ?></div>
    </td><td>
    <h3 class="titlehdr">เพิ่ม ทะเบียน RawMat</h3>
    <form method="post" action="rawmatid.php" name="regForm" id="regForm">
    <table style="text-align: left; width: 850px; height: 413px;" border="0" cellpadding="2" cellspacing="2">
    <tr><td style="vertical-align: middle; background-color: rgb(152, 161, 76);">
        <div style="text-align: center;">Code* <input tabindex="1" name="rawcode" class="required" > 
        ชื่อ <input tabindex="2" name="rawname" id="rawname" class="required" > ขนาด* <input tabindex="3" class="required" name="size" size=10> <a HREF="../../main/pharma/packagetype.php" onClick="return popup(this,'name','300','500','yes');">รูปแบบการสั่งซื้อ:</a><select name=unit>
        <?php	
        $dpackagetype = mysqli_query($link, "SELECT * FROM packagetype");
        // keeps getting the next row until there are no more to get
        while($row = mysqli_fetch_array($dpackagetype))
        {
        echo "<option value='".$row['name']."'>".$row['name']."</option>";
        }
        ?>
        </select><br>ส่วนประกอบของผลิตภัณฑ์:<input type="radio" tabindex="5" name="rmpd" class="required" value="1">Yes<input type="radio" tabindex="5" name="rmpd" class="required" value="0">No<br>ประเภท:<input type="radio" tabindex="6" name="rwtype" class="required" value="lab">Lab<input type="radio" tabindex="6" name="rwtype" class="required" value="ความงาม">ความงาม<input type="radio" tabindex="6" name="rwtype" class="required" value="other">อื่นๆ
        </div>
        <hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;"><br>
        <div style="text-align: center;">
        จำนวนคงคลังขั้นต่ำ*<input class="typenumber" type="number" tabindex="6" name="min_limit" value=0><br>
        </div>
        <hr style="width: 80%; height: 2px;"><br>
    </td></tr>
    <tr><td><br><div style="text-align: center;"><input name="register" value="ตกลง" type="submit" tabindex="7"></div></td></tr>
    </table>
    </form>
</td></tr>
</table>
</body></html>
