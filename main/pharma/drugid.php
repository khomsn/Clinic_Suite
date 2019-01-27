<?php 
include '../../config/dbc.php';
page_protect();
$err = array();

$sql_create = "CREATE TABLE IF NOT EXISTS `drug_id` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `dname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `dgname` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `uses` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `indication` text COLLATE utf8_unicode_ci,
  `size` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `volume` smallint(6) NOT NULL DEFAULT '0',
  `volreserve` smallint(6) NOT NULL DEFAULT '0',
  `sellprice` float(8,2) NOT NULL DEFAULT '0.00',
  `min_limit` smallint(4) NOT NULL DEFAULT '0',
  `typen` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `groupn` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subgroup` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seti` tinyint(1) NOT NULL DEFAULT '0',
  `ac_no` int(11) NOT NULL,
  `track` tinyint(1) NOT NULL DEFAULT '0',
  `disct` tinyint(1) NOT NULL DEFAULT '0',
  `prod` tinyint(1) NOT NULL DEFAULT '0',
  `RawMat` tinyint(1) NOT NULL DEFAULT '0',
  `cat` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `dinteract` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `candp` tinyint(1) NOT NULL DEFAULT '0',
  `staffcanorder` tinyint(1) NOT NULL DEFAULT '0',
  `stcp` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

mysqli_query($link, $sql_create);

if($_POST['register'] == 'ตกลง') 
{ 

    $dname =mysqli_real_escape_string($link, $_POST['dname']);
    $dgname =mysqli_real_escape_string($link, $_POST['dgname']);
    $size = mysqli_real_escape_string($link,$_POST['size']);
    $adn = $dname.'-'.$size;
    
    if(empty($_POST['set'])) $_POST['set']=0;
    if(empty($_POST['track'])) $_POST['track']=0;
    if(empty($_POST['disct'])) $_POST['disct']=0;
    if(empty($_POST['prod'])) $_POST['prod']=0;
    if(empty($_POST['RawMat'])) $_POST['RawMat']=0;
    //set to 0 if not checked
    if($_POST['stcp']!=1) $_POST['stcp']=0;

    //10300000-10699999 สินค้า assign account no. 10300000-10699999 สินค้า //start with 10300000 + drug_id

    $mmm=10300000;
    $maxm=10699999;

    $ac = mysqli_query($link, "SELECT * FROM acnumber WHERE (ac_no>=$mmm AND ac_no<$maxm) ORDER BY ac_no ASC");

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

    $rs_duplicate = mysqli_query($link, "select count(*) as total from drug_id where dname='$_POST[dname]' AND dgname='$_POST[dgname]' AND size='$_POST[size]' ") or $err[]=(mysqli_error($link));
    list($total) = mysqli_fetch_row($rs_duplicate);

    if ($total > 0)
    {
        $err[] = "ERROR - This Drug already exists. Please Check.";
        goto ErrorJump;
    }
    /***************************************************************************/
    if(empty($err))
    {
        //assign ac_no to table acnumber
        $sql_insert = "INSERT into `acnumber`
                    (`ac_no`, `name`)
                    VALUES
                    ('$ac','$adn')";
        // Now insert Account number for product to "acnumber" table
        mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));
        //check to reactive deleted drug
        $rs_duplicate = mysqli_query($link, "select count(*) as total from deleted_drug where dname='$_POST[dname]' AND dgname='$_POST[dgname]' AND size='$_POST[size]' ") or $err[]=(mysqli_error($link));
        list($dldrug) = mysqli_fetch_row($rs_duplicate);

        if($dldrug)
        {
            //get accout no + id no.
            $deld = mysqli_query($link, "SELECT * FROM deleted_drug WHERE dname='$_POST[dname]' AND dgname='$_POST[dgname]' AND size='$_POST[size]' ");

            while($row = mysqli_fetch_array($deld))
            { 
            //   $oldacn = $row['ac_no']; no reuse of ac_no
                $id = $row['id'];
            }
            // assign insertion pattern
            $sql_insert = "INSERT into `drug_id`
                        (`id`,`dname`,`dgname`,`uses`, `indication`, `size`, `sellprice`, `min_limit`, `typen`, `groupn`, `seti`, `ac_no`, `track`, `disct`,`prod`,`RawMat`,`cat`,`unit`,`candp`,`stcp`)
                        VALUES
                        ('$id','$_POST[dname]','$_POST[dgname]','$_POST[uses]','$_POST[Indication]','$_POST[size]','$_POST[sellprice]','$_POST[min_limit]','$_POST[type]','$_POST[group]','$_POST[set]',
                        '$ac','$_POST[track]','$_POST[disct]','$_POST[prod]','$_POST[RawMat]','$_POST[cat]','$_POST[unit]','$_POST[candp]','$_POST[stcp]')";
            // Now insert into "drug_id" table
            mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));
            //remove this id from deleted_drug
            $sql_del = "DELETE FROM `deleted_drug` WHERE `id` = '$id'";
            // Now DELETE Patient to "pt_to_lab" table
            mysqli_query($link, $sql_del) or $err[]=("Insertion Failed:" . mysqli_error($link));

        }
        else
        {
            // assign insertion pattern
            $sql_insert = "INSERT into `drug_id`
                        (`dname`,`dgname`,`uses`, `indication`, `size`, `sellprice`, `min_limit`, `typen`, `groupn`, `seti`, `ac_no`, `track`, `disct`,`prod`,`RawMat`,`cat`,`unit`,`candp`,`stcp`)
                        VALUES
                        ('$_POST[dname]','$_POST[dgname]','$_POST[uses]','$_POST[Indication]','$_POST[size]','$_POST[sellprice]','$_POST[min_limit]','$_POST[type]','$_POST[group]','$_POST[set]',
                        '$ac','$_POST[track]','$_POST[disct]','$_POST[prod]','$_POST[RawMat]','$_POST[cat]','$_POST[unit]','$_POST[candp]','$_POST[stcp]')";

            // Now insert into "drug_id" table
            mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));

            // Then get Patient ID to process to other step.
            $result = mysqli_query($link, "SELECT * FROM drug_id
            WHERE dname='$dname' AND dgname='$dgname' AND size='$size'");

            $row = mysqli_fetch_array($result);

            $drugtableid = $row['id'];
            include '../../libs/drtable.php';

            if($_POST['track'] ==1)
            {
                $id1 = "drug_".$row['id'];
                $sql_add = "ALTER TABLE `$id1` ADD `mkname` VARCHAR( 60 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
                            ADD `mkplace` VARCHAR( 50 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
                            ADD `mklot` VARCHAR( 20 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
                            ADD `mkanl` VARCHAR( 20 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ,
                            ADD `mkunit` VARCHAR( 20 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL ";
                mysqli_query($link, $sql_add) or $err[]=("Insertion Failed:" . mysqli_error($link));
                    
                $id = "tr_drug_".$row['id'];
                $sql_insert ="
                            CREATE TABLE `$id` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `date` DATE NOT NULL ,
                            `ctz_id` VARCHAR( 13 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL ,
                            `pt_id` INT NOT NULL ,
                            `volume` INT NOT NULL ,
                            PRIMARY KEY (`id`)
                            ) ENGINE = InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; ";
                // Now create drug information table
                mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));
                        
            }

            if($_POST['set'] ==1)
            {
                $id = "set_drug_".$row['id'];
                $sql_insert ="
                            CREATE TABLE `$id` (
                            `drugid` SMALLINT NOT NULL ,
                            `volume` SMALLINT NOT NULL ,
                            `uses` VARCHAR( 50 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL 
                            ) ENGINE = InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; ";
                // Now create drug information table
                mysqli_query($link, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($link));
                goto Skipdgname; // not update druggeneric if it is drug set        
            }

            //update druggeneric table
            $imp = mysqli_query($linkcm, "select name from druggeneric WHERE name = '$_POST[dgname]'");

            list($imprs) = mysqli_fetch_row($imp);
            if(empty($imprs))
            {
                $sql_insert = "INSERT into `druggeneric`  (`name`, `indication`, `dcat`  )  VALUES ('$_POST[dgname]','$_POST[indication]','$_POST[cat]')";
                mysqli_query($linkcm, $sql_insert) or $err[]=("Insertion Failed:" . mysqli_error($linkcm));
            }
          Skipdgname:  
        }

    }
    // go on to other step
    header("Location: drugid.php");  

}
ErrorJump:

$title = "::ยาและผลิตภัณฑ์::";
include '../../main/header.php';
$formid = "regForm";
include '../../libs/validate.php';
include '../../libs/popup.php';
include '../../libs/autodruggen.php';
include '../../main/bodyheader.php';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
<tr><td width="170" valign="top"><div class="pos_l_fix">
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
?></div>
</td>
<td><div align="center">
<?php
/******************** ERROR MESSAGES*************************************************
This code is to show error messages 
**************************************************************************/
if(!empty($err))
{
    echo "<div class=\"msg\">";
    foreach ($err as $e) {echo "$e <br>";}
    echo "</div>";
    echo "<br>";
}
/******************************* END ********************************/	  
?><h3 class="titlehdr">เพิ่ม ทะเบียนยา และ ผลิตภัณฑ์</h3>
<form method="post" action="drugid.php" name="regForm" id="regForm">
<table style="text-align: left; width: 800px; height: 413px;" border="0" cellpadding="2" cellspacing="2">
<tbody>
<tr><td style="width: 700px; vertical-align: middle; background-color: rgb(152, 161, 76);">
<div style="text-align: center;">ชื่อ* <input tabindex="1" name="dname" id="alldrugname" class="required" > 
ชื่อสามัญ <input tabindex="2" name="dgname" id="dgname1" class="required" > ขนาด* <input tabindex="3" class="required" name="size" id="drugsize" size=10> <a HREF="packagetype.php" onClick="return popup(this,'name','300','500','yes');">รูปแบบการสั่งซื้อ:</a><select name=unit>
<?php	
$dpackagetype = mysqli_query($link, "SELECT * FROM packagetype");
// keeps getting the next row until there are no more to get
while($row = mysqli_fetch_array($dpackagetype))
{
    echo "<option value='".$row['name']."'>".$row['name']."</option>";
}
?>
</select><br>
<input type="radio" name="cat" class="required" value="A">Cat A
<input type="radio" name="cat" class="required" value="B">Cat B
<input type="radio" name="cat" class="required" value="C">Cat C
<input type="radio" name="cat" class="required" value="D">Cat D
<input type="radio" name="cat" class="required" value="X">Cat X
<input type="radio" name="cat" class="required" value="N" checked >Cat N
<hr>
Course / Programs Lab:<br>
<input type="radio" name="candp" class="required" value="0" checked >None
<input type="radio" name="candp" class="required" value="1">Treatment
<input type="radio" name="candp" class="required" value="2">Programs+Labs
</div>
<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;"><br>
<div style="text-align: center;">วิธีใช้* <textarea tabindex="4" cols="80" rows="3" class="required" name="uses"></textarea>
<br>
</div>
<hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;"><br>
<div style="text-align: center;">Indication<textarea cols="80" rows="1" name="Indication"></textarea>
<br>
</div>
<hr style="width: 80%; height: 2px;"><br>
<div style="text-align: center;">
ราคาขาย* <input type=number class="typenumber" step=0.01 tabindex="6" name="sellprice" value=0> บาท
&nbsp; &nbsp; &nbsp;จำนวนคงคลังขั้นต่ำ*<input  type=number class="typenumber" tabindex="7" name="min_limit" value=0>พนักงานร่วมจ่ายต้นทุน:<input type="checkbox" name="stcp" value="1"><br>
</div>
<hr style="width: 80%; height: 2px;"><br>
<div style="text-align: center;">
<?php	
$dtype = mysqli_query($link, "SELECT name FROM drug_type");
$dgroup = mysqli_query($link, "SELECT name FROM drug_group");
?>
<a HREF="type.php" onClick="return popup(this,'name','300','500','yes');">ประเภท*</a>&nbsp;
<select tabindex="8"  name="type">
<option value="" selected></option>
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
<a HREF="group.php" onClick="return popup(this,'name','300','500','yes');" >กลุ่ม*</a>
<select tabindex="9"  name="group">
<option value="" selected></option>
<?php while($grow = mysqli_fetch_array($dgroup))
{
echo "<option value=\"";
echo $grow['name'];
echo "\">";
echo $grow['name']."</option>";
}
?>
</select>
<br>
<input type="checkbox" name="track" value="1">ยาพิเศษ-รายงานการใช้ 
&nbsp; &nbsp; &nbsp; &nbsp; <input type="checkbox" name="disct" value="1">ลดราคาได้
&nbsp; &nbsp; &nbsp; &nbsp; <input type="checkbox" name="set" value="1">ชุดยา
&nbsp; &nbsp; &nbsp; &nbsp; <input type="checkbox" name="prod" value="1">ผลิตภัณฑ์
&nbsp; &nbsp; &nbsp; &nbsp; <input type="checkbox" name="RawMat" value="1">วัตถุดิบ
<br>
</div>
</td>
</tr>
<tr>
<td><br>
<br>
<div style="text-align: center;"><input name="register" value="ตกลง" type="submit"></div>
</td>
</tr>
</tbody>
</table>
<br>
</form>
</div>
</td><td width=25%><div style="text-align: center;">
    <h3 class="titlehdr">หมายเหต</h3>
    <h3 class="myaccount">ผลิตภัณฑ์ คือ สินค้าที่ประกอบเอง</h3>
    <h3 class="myaccount">ยาพิเศษ คือ ยากลุ่มที่ต้องทำรายงานส่งราชการ</h3>
    <h3 class="myaccount">ลดราคาได้ คือ สินค้าที่ลดราคาได้</h3>
    <h3 class="myaccount">ชุดยา คือ ชื่อชุดของยาที่จัดรวมกัน</h3>
    <h3 class="myaccount">วัตถุดิบ คือ ส่วนที่นำมาประกอบสินค้าและขายให้ผู้ป่วยด้วย</h3>
    </div>
</td></tr>
</table>
</body></html>
