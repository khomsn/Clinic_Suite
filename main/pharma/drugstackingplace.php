<?php
include '../../config/dbc.php';
page_protect();

$_SESSION['stackno'] = $_POST['stackno'];

if($_POST['reset'] !== '')
{
    mysqli_query($link, "update drug_id set `location`='' where id='$_POST[reset]'");
}
    
if($_POST['Set'] == "Set") 
{
    //then check if it is valid post
    $drug = $_POST['drug'];
    if(ltrim($drug)!=='')
    {
        $drugid = strstr($drug, '-', true);
        mysqli_query($link, "UPDATE drug_id SET `location` = '$_SESSION[stackno]' WHERE `id` = '$drugid'");
    }
}

$title = "::จัดตำแหน่ง Stock ยา::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../libs/autodrugin.php';
include '../../main/bodyheader.php';

?>
<div id="content"><h3 class="titlehdr">จัดตำแหน่ง Stock ยา: </h3>
<form name="ddx" method="post" action="drugstackingplace.php" id="inForm">
<table width="90%" border="1" align="center" cellpadding="3" cellspacing="3" class="forms">
    <tr><td width=50% >ตำแหน่ง&nbsp;
        <select name="stackno" class="required"  onchange="myFunction()" >
                <?php
                $stack = mysqli_query($link, "SELECT * FROM stockplace");
                while($sprow = mysqli_fetch_array($stack))
                {
                        echo "<option value=\"";
                        echo $sprow['placeindex'];
                        echo "\" ";
                        if ($_SESSION['stackno'] == $sprow['placeindex']) echo " selected ";
                        echo ">";
                        echo $sprow['fullplace']."</option>";
                }
                ?>
         </select>
    </td></tr>
</table>
<table width="90%" border='1' style='text-align: center; margin-left: auto; margin-right: auto;' class="TFtable">
<tr><th>Order</th><th width="20%">Barcode/รหัสสินค้า</th><th width="30%">ชื่อสินค้า</th><th>ขนาด</th><th>Set/Reset</th></tr>
<?php
echo "<tr><td>";
echo "</td><td><input name='drug' type=text id=drug size=20% autofocus/></td>";
echo "<td>";
echo "</td>";
echo "<td>";
echo "</td>";
echo "<td><input type=submit name='Set' value='Set'></td>";
echo "</tr>";

$n=1;
$ptin = mysqli_query($link, "select * from drug_id where location='$_SESSION[stackno]' order by dgname ASC");
while($listdg = mysqli_fetch_array($ptin))
{
    echo "<tr><td>";
    echo $n++;
    echo "</td><td>";
    echo $listdg['dname'];
    echo "</td><td>";
    echo $listdg['dgname'];
    echo "</td><td>";
    echo $listdg['size'];
    echo "</td><td>";
    echo "Reset[<input type='submit' name='reset' value='$listdg[id]'>]";
    echo "</td></tr>";
}
?>
</table>
</form></div>
</body>
</html>
<script>
function myFunction(){
    document.getElementById("inForm").submit();
}
</script>
