<?php 
include '../../config/dbc.php';
page_protect();
$err = array();
$msg = array();

if(!($_SESSION['accode']%13==0 or $_SESSION['accode']%5==0)) header("Location: lablist.php");

$labid = $_SESSION['labid'];

if($labid %100 ==0 and $labid<5000) 
{
    header("Location: labgroupedit.php");
}
else
{
    /* Lab id start from 1x-- to 49-- for lab set.
    * for individual lab start from 5000 to 9999.
    */
    $oldlabid = $labid;
}

if($_POST['Save']=='Save') 
{
    /************ Lab Name CHECK ************************************
    This code does a second check on the server side if lab name already exists. It 
    queries the database and if it has any existing lab namel it throws lab name+Specimen already exists
    *******************************************************************/

    $rs_duplicate = mysqli_query($link, "select count(*) as total from lab where L_Name='$_POST[L_Name]' AND L_specimen='$_POST[L_specimen]'") or die(mysqli_error($link));
    list($total) = mysqli_fetch_row($rs_duplicate);

    if ($total > 1)
    {
        $err[] = "ERROR - Lab Name with Lab Specimen already exists. Please check.";
        goto ErrJP;
    }
    /***************************************************************************/
    if(empty($err))
    {
        //check for lab set change
        if($_POST['L_Set'] != $_SESSION['L_Set_old'])
        {
            $newset = $_POST['L_Set'];
            $msg[] = "\"".$newset."\"";

            if(trim($newset) === '') //not in lab set <labid for individual lab start from 5001 up>
            {
                $minid = 5000;
                $msl = mysqli_query($link, "SELECT MAX(id) FROM lab WHERE `id` >  5000 ");
                $maxid = mysqli_fetch_array($msl);
                $maxid = $maxid[0];
                $msg[]="maxid=".$maxid;
                
                $msl = mysqli_query($link, "SELECT id FROM lab WHERE `id` > $minid ORDER BY `id` ASC");
                while($fid = mysqli_fetch_array($msl))
                {
                    $labid = $fid['id'];
                    
                    $step = $labid - $minid;
                    
                    if($step > 1) 
                    {
                        $labid = $minid+1;
                        goto Jpoint;
                    }
                    else
                    {
                        $minid = $labid;
                    }
                    if($labid==$maxid)
                    {   $msg[]=" come 1";
                        $labid = $maxid+1;
                        goto Jpoint;
                    }
                }
            }
            else // Lab is in new lab L_Set
            {
                
                $msl = mysqli_query($link, "SELECT MIN(id) FROM lab WHERE `L_Set` = '$newset' ");
                $minid = mysqli_fetch_array($msl);
                $minid = $minid[0]-$minid[0]%100;
                $msl = mysqli_query($link, "SELECT MAX(id) FROM lab WHERE `L_Set` = '$newset' ");
                $maxid = mysqli_fetch_array($msl);
                $maxid = $maxid[0];
                $msg[]= "minid=".$minid;
                $msg[]="maxid=".$maxid;
                
                $msl = mysqli_query($link, "SELECT id FROM lab WHERE `L_Set` = '$newset' AND `id` > $minid ORDER BY `id` ASC");
                while($fid = mysqli_fetch_array($msl))
                {
                    $labid = $fid['id'];
                    $msg[] = "labid is=".$labid;
                    
                    $step = $labid - $minid;
                    $msg[] = " step =".$step;
                    if($step > 1) 
                    {
                        $labid = $minid+1;
                        goto Jpoint;
                    }
                    else
                    {
                        $minid = $labid;
                        $msg[] = "minid=".$minid;
                    }
                    if($labid==$maxid)
                    { $msg[]="come 2";
                        $labid = $maxid+1;
                        goto Jpoint;
                    }
                }
            }
        }
        
        Jpoint:
        /* Lab_set not change and we get labid to update*/
        /* 1st delete old record*/
        /* 2nd insert new id*/

        mysqli_query($link, "DELETE FROM `lab` WHERE `lab`.`id` = $oldlabid");
        
        if(empty($_POST['ltr'])) $_POST['ltr']=0;
        if(empty($_POST['tcc1e'])) $_POST['ccode1']="";
        if(empty($_POST['tcc2e'])) $_POST['ccode2']="";

        // Set up lab data in lab table
        $sql_insert = "INSERT into `lab`
                (`id`, `L_Name`, `S_Name`, `L_Set`, `L_specimen`, `Lrunit`, `normal_r`, `r_min`, `r_max`, `Linfo`, `price`, `ltr`, `colourcode`, `colourcode2`)
            VALUES
                ('$labid','$_POST[L_Name]','$_POST[S_Name]','$_POST[L_Set]','$_POST[L_specimen]','$_POST[Lrunit]','$_POST[normal_r]','$_POST[r_min]','$_POST[r_max]','$_POST[Linfo]','$_POST[L_price]','$_POST[ltr]','$_POST[ccode1]','$_POST[ccode2]')";
        // Now insert Lab table
        mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
        $msg[] = $_POST['L_Name']." with ".$_POST['L_specimen']." Specimen created successful.";

        $msg[]="final labid is =".$labid; 
    }
}
ErrJP:

$title = "::Labt::";
include '../../main/header.php';
include '../../libs/autolsl.php';
$formid = "regForm";
include '../../libs/validate.php';

?>
</head>
<body>
<table width="100%" border="0">
<tr><td width=160px>
    <div class="pos_l_fix">
    <?php 
        /*********************** MYACCOUNT MENU ****************************
        This code shows my account menu only to logged in users. 
        Copy this code till END and place it in a new html or php where
        you want to show myaccount options. This is only visible to logged in users
        *******************************************************************/
        if (isset($_SESSION['user_id']))
        {
            include 'labmenu.php';
        } 
    /*******************************END**************************/
    ?>
    </div>
    </td><td><div style="background-color:rgba(0,255,0,0.5); display:inline-block;">แก้ไข Lab เป็นรายตัว ถ้าต้องการเพิ่มเป็นสมาชิกของชุด Lab Set ก็ให้กำหนดค่า Lab Set ได้เลย</div>
    <?php
    if(!empty($err))  
	 {
	   echo "<div class=\"msg\">";
       foreach ($err as $e) { echo "* $e <br>"; }
	   echo "</div>";	
     }
	 if(!empty($msg))  
	 {
	   echo "<div class=\"msg\">";
	   foreach ($msg as $m) { echo "* $m <br>"; }
	   echo "</div>";	
     }
	 ?> 
    <form action="labedit.php" method="post" name="regForm" id="regForm" >
    <table><?php 

    $lab_in = mysqli_query($link, "select * from lab where id=$labid");
    while ($rows = mysqli_fetch_array($lab_in))
    {?>
        <tr><td>Lab Set </td><td><input type="text" name="L_Set" id="lsn" value="<?php echo $rows['L_Set']; $_SESSION['L_Set_old'] = $rows['L_Set'];?>"></td></tr>
        <tr><td>ชื่อเต็ม </td><td><input type="text" name="L_Name" class="required" value="<?php echo $rows['L_Name'];?>"></td></tr>
        <tr><td>ชื่อย่อย </td><td><input type="text" name="S_Name" value="<?php echo $rows['S_Name'];?>"></td></tr>
        <tr><td>ชนิดสิ่งส่งตรวจ </td><td><input type="text" name="L_specimen" id="lsp" class="required" value="<?php echo $rows['L_specimen'];?>"></td></tr>
        <tr><td>หน่วยของ Lab </td><td><input type="text" name="Lrunit" value="<?php echo $rows['Lrunit'];?>"></td></tr>
        <tr><td>ผลปกติ </td><td><input type="text" name="normal_r" value="<?php echo $rows['normal_r'];?>"></td></tr>
        <tr><td>ค่าต่ำ </td><td><input type="text" name="r_min" value="<?php echo $rows['r_min'];?>"></td></tr>
        <tr><td>ค่าสูง </td><td><input type="text" name="r_max" value="<?php echo $rows['r_max'];?>"></td></tr>
        <tr><td>ข้อมูลของ Lab </td><td><textarea name="Linfo" rows="5" cols="60"><?php echo $rows['Linfo'];?></textarea></td></tr>
        <tr><td>ราคาขาย </td><td><input type="number" name="L_price" size="7" min="0" step="1" value="<?php 
        echo $rows['price'];
        ?>"></td></tr>
        <tr><td>สีหลอด1 </td><td><input type="color" name="ccode1" onchange="clickColor(0, -1, -1, 5)" value="<?php echo $rows['colourcode'];?>" style="width:85%;"><input type="checkbox" name="tcc1e" value="1" <?php if(!empty($rows['colourcode'])) echo "checked";?>></td></tr>
        <tr><td>สีหลอด2 </td><td><input type="color" name="ccode2" onchange="clickColor(0, -1, -1, 5)" value="<?php echo $rows['colourcode2'];?>" style="width:85%;"><input type="checkbox" name="tcc2e" value="1" <?php if(!empty($rows['colourcode2'])) echo "checked";?>></td></tr>
        <tr><td>บันทึกเวลา </td><td><input type="checkbox" name="ltr" value="1" <?php if($rows['ltr']==1) echo "checked";?>></td></tr>
    <?php 
    }
    ?>
    </table><div style="text-align:center;"><?php if(empty($msg)){echo "<input type=submit name=Save value=Save>";}?></div>
    </form>
    </td><td width=130px><div class="pos_r_fix"><?php include 'labrmenu.php';?></div>
</td></tr>
</table>
</body>
</html>
