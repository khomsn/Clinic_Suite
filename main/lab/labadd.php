<?php 
include '../../config/dbc.php';
page_protect();
$err = array();
$msg = array();

unset($_SESSION['LLSName']);
unset($_SESSION['SLSName']);
unset($_SESSION['SetNum']);

/* Lab id start from 1x-- to 49-- for lab set.
* for individual lab start from 50xx to 99xx.
*/
$id = 5000;
//search for id
$lin = mysqli_query($link, "SELECT id FROM lab WHERE id > 5000 ORDER BY id ASC");
while($rows=mysqli_fetch_array($lin)) 
{
	$idnew = $rows['id'];
	$step = $idnew - $id;
	if($step <= 1) 
	{
		$id = $idnew;
	}
	else { goto JPoint1;}
}

JPoint1:
if($idnew<5001){$id = 5001;}
else { $id = $id + 1;}

if($_POST['Save']=='Save') 
{

    /************ Lab Name CHECK ************************************
    This code does a second check on the server side if the email already exists. It 
    queries the database and if it has any existing email it throws user email already exists
    *******************************************************************/

    $rs_duplicate = mysqli_query($link, "select count(*) as total from lab where L_Name='$_POST[L_Name]' AND L_specimen='$_POST[L_specimen]'") or die(mysqli_error($link));
    list($total) = mysqli_fetch_row($rs_duplicate);

    if ($total > 0)
    {
        $err[] = "ERROR - Lab Name with Lab Specimen already exists. Please check.";
        goto ErrJP;
    }
    /***************************************************************************/
    if(empty($_POST['labtime'])) $_POST['labtime']=0;
    if(empty($_POST['lcc1e'])) $_POST['ccode1']="";
    if(empty($_POST['lcc2e'])) $_POST['ccode2']="";
    if(empty($_POST['L_price'])) $_POST['L_price']=0;
    if(empty($err))
    {
        // Set up lab data in lab table
        $sql_insert = "INSERT into `lab`
                (`id`, `L_Name`, `S_Name`, `L_Set`, `L_specimen`, `Lrunit`, `normal_r`, `r_min`, `r_max`, `Linfo`, `price`, `ltr`, `colourcode`, `colourcode2`)
            VALUES
                ('$id','$_POST[L_Name]','$_POST[S_Name]','','$_POST[L_specimen]','$_POST[Lrunit]','$_POST[normal_r]','$_POST[r_min]','$_POST[r_max]','$_POST[Linfo]','$_POST[L_price]','$_POST[labtime]','$_POST[ccode1]','$_POST[ccode2]')";
        // Now insert Lab table
        mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
        $msg[] = $_POST['L_Name']." with ".$_POST['L_specimen']." Specimen created successful.";
    }

}

ErrJP:

$title = "::Laboratory::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
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
    </td><td><div style="background-color:rgba(0,255,0,0.5); display:inline-block;">เพิ่ม Lab เป็นรายตัว ถ้าต้องการเพิ่มเป็นชุดให้เพิ่มใน Lab Set</div>
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

    <form action="labadd.php" method="post" name="regForm" id="regForm" >
    <table border="0" width="" class='TFtable' >
    <tr><td>ชื่อเต็ม </td><td><input type="text" name="L_Name" class="required"></td></tr>
    <tr><td>ชื่อย่อย </td><td><input type="text" name="S_Name"></td></tr>
    <tr><td>ชนิดสิ่งส่งตรวจ </td><td><input type="text" name="L_specimen" id="lsp" class="required"></td></tr>
    <tr><td>หน่วยของ Lab </td><td><input type="text" name="Lrunit"></td></tr>
    <tr><td>ผลปกติ </td><td><input type="text" name="normal_r"></td></tr>
    <tr><td>ค่าต่ำ </td><td><input type="text" name="r_min"></td></tr>
    <tr><td>ค่าสูง </td><td><input type="text" name="r_max"></td></tr>
    <tr><td>ข้อมูลของ Lab </td><td><textarea name="Linfo" rows="5" cols="60"></textarea></td></tr>
    <tr><td>ราคาขาย </td><td><input type="number" name="L_price" size="7" min="0" step="1"></td></tr>
    <tr><td>สีหลอด1 </td><td><input type="color" name="ccode1" onchange="clickColor(0, -1, -1, 5)" value="#ff0000" style="width:85%;"><input type="checkbox" name="lcc1e" size="8" value="1"></td></tr>
    <tr><td>สีหลอด2 </td><td><input type="color" name="ccode2" onchange="clickColor(0, -1, -1, 5)" value="#ff0000" style="width:85%;"><input type="checkbox" name="lcc2e" size="8" value="1"></td></tr>
    <tr><td>บันทึกเวลา </td><td><input type="checkbox" name="labtime" size="8" value="1"></td></tr>
    </table><br>
    <div style="text-align:center;"><input type="submit" name="Save" value="Save"></div>
    </form>
   </td>
    <td width=130px><div class="pos_r_fix"><?php include 'labrmenu.php';?></div>
    </td>
  </tr>
</table>
</body>
</html>
