<?php 
include '../../config/dbc.php';
page_protect();
$err = array();
$msg = array();

if($_POST['Next']=='Next') 
{
    /************ Lab Name CHECK ************************************
    This code does a second check on the server side if the email already exists. It 
    queries the database and if it has any existing email it throws user email already exists
    *******************************************************************/

    $rs_duplicate = mysqli_query($link, "select count(*) as total from lab where L_Name='$_POST[LLSName]'") or die(mysqli_error($link));
    list($total) = mysqli_fetch_row($rs_duplicate);

    if ($total > 0)
    {
        $err[] = "ERROR - LabSet Name [".$_POST['LLSName']."] already exists. Please check.";
        goto ErrJP;
    }
    /***************************************************************************/
    if(empty($err))
    {
        //get Set Name
        $_SESSION['LLSName'] = $_POST['LLSName'];
        $_SESSION['SLSName'] = $_POST['SLSName'];
        //get Set Number
        $_SESSION['SetNum'] = $_POST['Ssize'];
    }
}

if($_POST['Back']=='Back') 
{
	//get Set Name
	unset($_SESSION['LLSName']);
	unset($_SESSION['SLSName']);
	//get Set Number
	unset($_SESSION['SetNum']);
	/* Lab id start from 1xx to 99xx for lab set.
	* for individual lab start from 10000 to 32767.
	*/
    //go on 
    header("Location: labaddset.php");  
}

/* Lab id start from 1xx to 99xx for lab set.
* for individual lab start from 10000 to 32767.
*/

$setnumber=$_SESSION['SetNum'];

$id = 10;
//search for id
$lin = mysqli_query($link, "SELECT id FROM lab WHERE id > 999 AND id < 5000 ORDER BY id ASC");

while($rows=mysqli_fetch_array($lin)) 
{
    $idnew = $rows['id']/100;
    $step = $idnew - $id;
    if($step <= 1){ $id = $idnew;}
    else { goto JPoint1;}
}

JPoint1:
if($idnew<10) {$id = 1000;}
else { $id = ceil($id)*100;}

if($_POST['Save']=='Save') 
{
    for($i=1;$i<=$setnumber;$i++)
    {
      
        $LLName =$_POST['L_Name'.$i];
        
        /************ Lab Name CHECK ************************************
        This code does a second check on the server side if the email already exists. It 
        queries the database and if it has any existing email it throws user email already exists
        *******************************************************************/

        $rs_duplicate = mysqli_query($link, "select count(*) as total from lab where L_Name='$LLName' AND L_specimen ='$_POST[L_specimen]'") or die(mysqli_error($link));
        list($total) = mysqli_fetch_row($rs_duplicate);
        if ($total > 0)
        {
            $err[] = "ERROR - LabSet Name [".$_POST['LLSName']."] already exists. Please check.";
            goto ErrJP;
        }
        /***************************************************************************/
    }
      
    if(empty($_POST['labtime'])) $_POST['labtime']=0;
    if(empty($_POST['lcc1e'])) $_POST['ccode1']="";
    if(empty($_POST['lcc2e'])) $_POST['ccode2']="";
    if(empty($_POST['L_price'])) $_POST['L_price']=0;
      
    if(empty($err))
    {
        $LSN = $id."-".$_SESSION['SLSName'];
        // Set up lab data in lab table
            $sql_insert = "INSERT into `lab`
                    (`id`, `L_Name`, `S_Name`,`L_Set`, `L_specimen`,`Linfo`,`price`,`ltr`, `colourcode`, `colourcode2`)
                VALUES
                    ('$id','$_SESSION[LLSName]','$_SESSION[SLSName]','SETNAME','$_POST[L_specimen]','$_POST[Linfo]','$_POST[L_price]','$_POST[labtime]','$_POST[ccode1]','$_POST[ccode2]')";
        // Now insert Lab table for set name
        mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));


        for($i=1;$i<=$setnumber;$i++)
        {
            $LLName =$_POST['L_Name'.$i];
            $SLName =$_POST['S_Name'.$i];
            $Lrunit =$_POST['Lrunit'.$i];
            $norr =$_POST['normal_r'.$i];
            $rmin =$_POST['r_min'.$i];
            $rmax =$_POST['r_max'.$i];
            $price = $_POST['L_price'.$i];
            if(empty($price)) $price=0;
            $id = $id +1;

            // Set up lab data in lab table
            $sql_insert = "INSERT into `lab`
                    (`id`, `L_Name`, `S_Name`, `L_Set`, `L_specimen`, `Lrunit`, `normal_r`, `r_min`, `r_max`, `Linfo`, `price`,`ltr`, `colourcode`, `colourcode2`)
                    VALUES
                    ('$id','$LLName','$SLName','$LSN','$_POST[L_specimen]','$Lrunit','$norr','$rmin','$rmax','$_POST[Linfo]','$price','$_POST[labtime]','$_POST[ccode1]','$_POST[ccode2]')";
            // Now insert Lab table
            mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
        }
	//go on 
	unset($_SESSION['LLSName']);
	unset($_SESSION['SLSName']);
	//get Set Number
	unset($_SESSION['SetNum']);

	header("Location: labaddset.php");  
    }
}
ErrJP:

$title = "::Lab Set::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
$formid = "regForm";
include '../../libs/autolsl.php';
include '../../libs/validate.php';
include '../../main/bodyheader.php';

?>
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
    </td><td><div style="background-color:rgba(0,255,0,0.5); display:inline-block;">เพิ่ม Lab Set ถ้าต้องการเพิ่มเป็นรายตัวให้เพิ่มใน Lab</div>
    <?php
    if(!empty($err))
    {
        echo "<div class=\"msg\">";
        foreach ($err as $e) {echo "* $e <br>";}
        echo "</div>";	
    }
    if(!empty($msg))
    {
        echo "<div class=\"msg\">";
        foreach ($msg as $m) {echo "* $m <br>";}
        echo "</div>";	
    }
    ?> 
    <form action="labaddset.php" method="post" name="regForm" id="regForm" >
    <?php 
    if(empty($_SESSION['LLSName']))
    {
        echo "<div style='background-color:rgba(0,255,0,0.5); display:inline-block;'>ชื่อชุด Lab: <input type=text name=LLSName class=required></div><br>";
        echo "<div style='background-color:rgba(0,255,0,0.5); display:inline-block;'>ชื่อย่อ Lab: <input type=text name=SLSName class=required></div><br>";
    }
    if(empty($_SESSION['SetNum']))
    {
        echo "<div style='background-color:rgba(0,255,0,0.5); display:inline-block;'>จำนวนสมาชิก Lab: <input type='number' name='Ssize' class=required></div>";
    }
    if(!empty($_SESSION['LLSName']) AND !empty($_SESSION['SetNum']))
    {
        echo "<div align='center'>";
        echo "<table border=1 width=70% class='TFtable' >";
        //$setnumber = ceil($setnumber/2);
        for($i=1;$i<=$setnumber;$i++)
        {
            $j=$i+1;
            echo  "<tr><td>ชื่อเต็ม </td><td><input type='text' tabindex=".$i." name='L_Name".$i."' class='required'></td>";
            echo  "<td>ชื่อเต็ม </td><td>";
            if($j<=$setnumber) echo "<input type='text' tabindex=".$j." name='L_Name".$j."' class='required'>";
            echo "</td></tr>";
            echo  "<tr><td>ชื่อย่อย </td><td><input type='text' tabindex=".$i."  name='S_Name".$i."'></td>";
            if($j<=$setnumber) echo  "<td>ชื่อย่อย </td><td><input type='text' tabindex=".$j."  name='S_Name".$j."'>";
            echo "</td></tr>";
            echo  "<tr><td>หน่วยของ Lab </td><td><input type='text' tabindex=".$i."  name='Lrunit".$i."'></td>";
            if($j<=$setnumber) echo  "<td>หน่วยของ Lab </td><td><input type='text' tabindex=".$j."  name='Lrunit".$j."'>";
            echo  "</td></tr>";
            echo  "<tr><td>ผลปกติ </td><td><input type='text' tabindex=".$i."  name='normal_r".$i."'></td>";
            if($j<=$setnumber) echo  "<td>ผลปกติ </td><td><input type='text' tabindex=".$j."  name='normal_r".$j."'>";
            echo "</td></tr>";
            echo  "<tr><td>ค่าต่ำ </td><td><input type='text' tabindex=".$i."  name='r_min".$i."'></td>";
            if($j<=$setnumber) echo  "<td>ค่าต่ำ </td><td><input type='text'  tabindex=".$j." name='r_min".$j."'>";
            echo "</td></tr>";
            echo  "<tr><td>ค่าสูง </td><td><input type='text' tabindex=".$i."  name='r_max".$i."'></td>";
            if($j<=$setnumber) echo  "<td>ค่าสูง </td><td><input type='text'  tabindex=".$j." name='r_max".$j."'>";
            echo "</td></tr>";
            echo  "<tr><td>ราคาขายรายตัว </td><td><input type='number' tabindex=".$i."  name='L_price".$i."' size='7' min='0' step='1'></td>";
            if($j<=$setnumber) echo  "<td>ราคาขายรายตัว </td><td><input type='number' tabindex=".$j." name='L_price".$j."' size='7' min='0' step='1'>";
            echo "</td></tr>";
            $i=$i+1;

        }
        echo "</table>";
        echo "<table border=1 width=70% class='TFtable' >";
        echo  "<tr><td>ชนิดสิ่งส่งตรวจ </td><td><input type='text' id='lsp' name='L_specimen'></td></tr>";
        echo  "<tr><td>สีหลอด1 </td><td><input type='color' name='ccode1' onchange='clickColor(0, -1, -1, 5)' value='#ff0000' style='width:85%;'><input type='checkbox' name='lcc1e' size='8' value='1'></td></tr>";
        echo  "<tr><td>สีหลอด2 </td><td><input type='color' name='ccode2' onchange='clickColor(0, -1, -1, 5)' value='#ff0000' style='width:85%;'><input type='checkbox' name='lcc2e' size='8' value='1'></td></tr>";
        echo  "<tr><td>ข้อมูลของ Lab </td><td><textarea name='Linfo' rows='5' cols='60'></textarea></td></tr>";
        echo  "<tr><td>ราคาขายทั้งชุด </td><td><input type='number' name='L_price' size='7' min='0' step='1'></td></tr>";
        echo  "<tr><td>บันทึกเวลา </td><td><input type='checkbox' name='labtime' size='8' value='1'></td></tr>";
        echo "</table><br>";
        echo "</div>";
    }
    ?>
    <div style="text-align:center;">
    <?php 
    if($setnumber==0) 
    {
        echo "<input type='submit' name='Next' value='Next'>";
    }
    else 
    {
        echo "<input type='button' name='Back' value='Back'>";
        echo "<input type='submit' name='Save' value='Save'>";
    }
    ?>
    </div>
    </form>
    </td><td width=130px><div class="pos_r_fix"><?php include 'labrmenu.php';?></div>
</td></tr>
</table>
</body>
</html>
