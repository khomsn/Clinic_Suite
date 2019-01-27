<?php 
include '../../config/dbc.php';
page_protect();
$err = array();
$msg = array();

if(!($_SESSION['accode']%13==0 or $_SESSION['accode']%5==0)) header("Location: lablist.php");

$labid = $_SESSION['labid'];

//$L_Set = $_SESSION['labid']."-".$_SESSION['SLSName'];

//search for id
$lin = mysqli_query($link, "SELECT * FROM lab WHERE id = '$labid'");
while ($lsetin = mysqli_fetch_array($lin))
{
    $L_Set = $lsetin['id'].'-'.$lsetin['S_Name'];
}

$lsetmemb = mysqli_query($link, "select count(*) as total from lab where L_Set='$L_Set'");
list($total) = mysqli_fetch_row($lsetmemb);

if ($total > 0)
{
    $setnumber = $total;
}

if($_POST['Save']=='Save') 
{
    //check for SETNAME
    $rs_duplicate = mysqli_query($link, "select count(*) as total from lab where L_Name='$_POST[L_Name]' AND L_specimen='$_POST[L_specimen]'") or $err[] = mysqli_error($link);
    list($total) = mysqli_fetch_row($rs_duplicate);
	if ($total > 1)
	{
	    $err[] = "ERROR - LabSet Name [".$L_Set."] already exists. Please check.";
	    goto ErrJP;
	}

  for($i=1;$i<=$setnumber;$i++)
    {
      
	$LLName =$_POST['L_Name'.$i];
	  
	/************ Lab Name CHECK ************************************
	This code does a second check on the server side if the email already exists. It 
	queries the database and if it has any existing email it throws user email already exists
	*******************************************************************/
	$rs_duplicate = mysqli_query($link, "select count(*) as total from lab where L_Name='$LLName' AND L_specimen ='$_POST[L_specimen]'") or  $err[] = mysqli_error($link);
	list($total) = mysqli_fetch_row($rs_duplicate);
	if ($total > 1)
	{
	    $err[] = "ERROR - Lab Name [".$LLName."] already exists. Please check.";
	    goto ErrJP;
	}
	/***************************************************************************/
    }

    if(empty($err))
    {
    
	if(empty($_POST['ltr'])) $_POST['ltr']=0;
	if(empty($_POST['tcc1e'])) $_POST['ccode1']="";
	if(empty($_POST['tcc2e'])) $_POST['ccode2']="";
	//update LabSet Header
        mysqli_query($link, "UPDATE lab SET
                    `L_Name` = '$_POST[L_Name]',
                    `S_Name` = '$_POST[S_Name]',
                    `L_specimen` = '$_POST[L_specimen]',
                    `Linfo` = '$_POST[Linfo]',
                    `L_Set` = 'SETNAME',
                    `price` = '$_POST[L_price]',
                    `ltr` = '$_POST[ltr]',
                    `colourcode` = '$_POST[ccode1]',
                    `colourcode2` = '$_POST[ccode2]'
                    WHERE id='$labid'
                    ") or die(mysqli_error($link));
	
    //update lab @ labid
    for($i=1;$i<=$setnumber;$i++)
        {
        $Lid = $_POST['L_Id'.$i];
        $LName = $_POST['L_Name'.$i];
        $SName = $_POST['S_Name'.$i];
        $Lspec = $_POST['L_specimen'];
        $Lru = $_POST['Lrunit'.$i];
        $Lnmr = $_POST['normal_r'.$i];
        $Lrmin = $_POST['r_min'.$i];
        $Lrmax = $_POST['r_max'.$i];
        $Linfo = $_POST['Linfo'];
        $Lprice = $_POST['L_price'.$i];
       
        mysqli_query($link, "UPDATE lab SET
                    `L_Name` = '$LName',
                    `S_Name` = '$SName',
                    `L_specimen` = '$Lspec',
                    `Lrunit` = '$Lru',
                    `normal_r` = '$Lnmr',
                    `r_min` = '$Lrmin',
                    `r_max` = '$Lrmax',
                    `Linfo` = '$Linfo',
                    `L_Set` = '$L_Set',
                    `price` = '$Lprice',
                    `ltr` = '$_POST[ltr]',
                    `colourcode` = '$_POST[ccode1]',
                    `colourcode2` = '$_POST[ccode2]'
                    WHERE id='$Lid'
                    ") or $err[] = mysqli_error($link);
        }
    }
    $msg[]="Update Completed";
}
ErrJP:

$title = "::Lab Set::";
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
    </td><td><div style="background-color:rgba(0,255,0,0.5); display:inline-block;">Update Lab Set ถ้าต้องการเพิ่มเป็นรายตัวให้เพิ่มใน Lab</div>
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
    <form action="labgroupedit.php" method="post" name="regForm" id="regForm" ><div align='center'>
    <?php 
        if(!empty($L_Set) AND ($setnumber>0))
        {
        echo "<table border=1 width=70% >";

        $lin = mysqli_query($link, "SELECT * FROM lab WHERE id = '$labid'");
        while ($lsetin = mysqli_fetch_array($lin))
        {?>
            <tr><td>Lab Set </td><td><?php echo $lsetin['L_Set'];?></td></tr>
            <tr><td>ชื่อเต็ม </td><td><input type="text" name="L_Name" class="required" value="<?php echo $lsetin['L_Name'];?>"></td></tr>
            <tr><td>ชื่อย่อย </td><td><input type="text" name="S_Name" value="<?php echo $lsetin['S_Name'];?>"></td></tr>
            <tr><td>ชนิดสิ่งส่งตรวจ </td><td><input type="text" name="L_specimen" class="required" value="<?php echo $lsetin['L_specimen'];?>"></td></tr>
            <tr><td>ข้อมูลของ Lab </td><td><textarea name="Linfo" rows="5" cols="100%"><?php echo $lsetin['Linfo'];?></textarea></td></tr>
            <tr><td>ราคาขาย </td><td><input type="number" name="L_price" size="7" min="0" step="1" value="<?php 
            echo $lsetin['price'];
            ?>"></td></tr>
            <tr><td>สีหลอด1 </td><td><input type="color" name="ccode1" onchange="clickColor(0, -1, -1, 5)" value="<?php echo $lsetin['colourcode'];?>" style="width:85%;"><input type="checkbox" name="tcc1e" value="1" <?php if(!empty($lsetin['colourcode'])) echo "checked";?>></td></tr>
            <tr><td>สีหลอด2 </td><td><input type="color" name="ccode2" onchange="clickColor(0, -1, -1, 5)" value="<?php echo $lsetin['colourcode2'];?>" style="width:85%;"><input type="checkbox" name="tcc2e" value="1" <?php if(!empty($lsetin['colourcode2'])) echo "checked";?>></td></tr>
            <tr><td>บันทึกเวลา </td><td><input type="checkbox" name="ltr" value="1" <?php if($lsetin['ltr']==1) echo "checked";?>></td></tr>
        <?php 
        }
        echo "</table>";

        echo "<table border=1 width=70%>";
        $num_i = 1;
        $lin = mysqli_query($link, "SELECT * FROM lab WHERE L_Set = '$L_Set'");
        while ($rows = mysqli_fetch_array($lin))
        {
            $setid[$num_i] = $rows['id'];
            $setLN[$num_i] = $rows['L_Name'];
            $setSN[$num_i] = $rows['S_Name'];
            $setlunit[$num_i] = $rows['Lrunit'];
            $setnr[$num_i] = $rows['normal_r'];
            $setrmin[$num_i] = $rows['r_min'];
            $setrmax[$num_i] = $rows['r_max'];
            $setidprice[$num_i] = $rows['price'];
            $num_i = $num_i+1;
        }

        for($nin=1;$nin<=$setnumber;$nin++)
        {
        $j=$nin+1;

        echo  "<tr><td>Lab Id</td><td><input type='number' name='L_Id".$nin."' min='";
        echo  $labid + 1;
        echo  "' max='";
        echo  $labid + 99;
        echo  "' tabindex=".$nin." value='".$setid[$nin]."' class='required'></td>";
        if($j<=$setnumber) 
        {
        echo  "<td>Lab Id</td><td><input type='number' name='L_Id".$j."' min='";
        echo  $labid + 1;
        echo  "' max='";
        echo  $labid + 99;
        echo  "' tabindex=".$j." value='".$setid[$j]."' class='required'></td>";
        }
        echo  "</tr>";
        echo  "<tr><td>ชื่อเต็ม</td><td><input type='text' tabindex=".$nin." name='L_Name".$nin."' value='".$setLN[$nin]."' class='required'></td>";
        if($j<=$setnumber) 
        echo  "<td>ชื่อเต็ม</td><td><input type='text' tabindex=".$j." name='L_Name".$j."' value='".$setLN[$j]."' class='required'></td>";
        echo  "</tr>";
        echo  "<tr><td>ชื่อย่อย </td><td><input type='text' tabindex=".$nin."  name='S_Name".$nin."' value='".$setSN[$nin]."'></td>";
        if($j<=$setnumber)
        echo  "<td>ชื่อย่อย </td><td><input type='text' tabindex=".$j."  name='S_Name".$j."' value='".$setSN[$j]."'></td>";
        echo  "</tr>";
        echo  "<tr><td>หน่วยของ Lab </td><td><input type='text' tabindex=".$nin."  name='Lrunit".$nin."' value='".$setlunit[$nin] ."'></td>";
        if($j<=$setnumber)
        echo  "<td>หน่วยของ Lab </td><td><input type='text' tabindex=".$j."  name='Lrunit".$j."' value='".$setlunit[$j] ."'></td>";
        echo  "</tr>";
        echo  "<tr><td>ผลปกติ </td><td><input type='text' tabindex=".$nin."  name='normal_r".$nin."' value='".$setnr[$nin] ."'></td>";
        if($j<=$setnumber)
        echo  "<td>ผลปกติ </td><td><input type='text' tabindex=".$j."  name='normal_r".$j."' value='".$setnr[$j] ."'></td>";
        echo  "</tr>";
        echo  "<tr><td>ค่าต่ำ </td><td><input type='text' tabindex=".$nin."  name='r_min".$nin."' value='".$setrmin[$nin] ."'></td>";
        if($j<=$setnumber)
        echo  "<td>ค่าต่ำ </td><td><input type='text'  tabindex=".$j." name='r_min".$j."' value='".$setrmin[$j] ."'></td>";
        echo  "</tr>";
        echo  "<tr><td>ค่าสูง </td><td><input type='text' tabindex=".$nin."  name='r_max".$nin."' value='".$setrmax[$nin] ."'></td>";
        if($j<=$setnumber)
        echo  "<td>ค่าสูง </td><td><input type='text'  tabindex=".$j." name='r_max".$j."' value='".$setrmax[$j] ."'></td>";
        echo  "</tr>";
        echo  "<tr><td>ราคาขายรายตัว </td><td><input type='number' tabindex=".$nin."  name='L_price".$nin."' value='".$setidprice[$nin] ."' size='7' min='0' step='1'></td>";
        if($j<=$setnumber)
        echo  "<td>ราคาขายรายตัว </td><td><input type='number' tabindex=".$j." name='L_price".$j."' value='".$setidprice[$j] ."' size='7' min='0' step='1'></td>";
        echo  "</tr>";

        $nin=$j;

        }
        echo "</table>";
        }
    ?><div style="text-align:center;"><input type='submit' name='Save' value='Save'></div>
    </div></form>
   </td>
   </td><td width=130px><div class="pos_r_fix"><?php include 'labrmenu.php';?></div>
</td></tr>
</table>
</body>
</html>
