<?php
include '../../config/dbc.php';
page_protect();

if($_POST['add'] == "เพิ่ม") 
{
    $_SESSION['stackno'] = $_POST['stackno'];
    //update post value first
    for($j=1;$j<=$_SESSION['DG'];$j++)
    {
        $_SESSION['drug'.$j]=$_POST['drug'.$j];
    }
    
    $j=$_SESSION['DG'];
    //then check if it is valid post
    $drug = $_SESSION['drug'.$j];
    
    if(ltrim($drug)!=='')
    {
        $drugid[$j] = strstr($drug, '-', true);
        $ptin = mysqli_query($link, "select * from drug_id where id='$drugid[$j]'");
        while ($row2 = mysqli_fetch_array($ptin))
        {
            $_SESSION['dgname'.$j]= $row2['dgname'];
            $_SESSION['size'.$j] =  $row2['size'];
            $_SESSION['dname'.$j]= $row2['dname'];
            $validid = $row2['id'];
        }

       if($validid)
        {
            $_SESSION['DG'] = $_SESSION['DG']+1;
        }
        if(!$validid)
        {
            unset($_SESSION['drug'.$j]);
        }
    }
}

for($n=1;$n<=$_SESSION['DG'];$n++)
{
    if($_POST['del'.$n]=='ลบ')
    {
        for($m=$n;$m<$_SESSION['DG'];$m++)
        {
            $j=$m+1;
            
            $_SESSION['drug'.$m]=$_SESSION['drug'.$j];
            $_SESSION['dname'.$m]=$_SESSION['dname'.$j];
            $_SESSION['size'.$m]=$_SESSION['size'.$j];
            $_SESSION['dgname'.$m]=$_SESSION['dgname'.$j];
        }
        unset($_SESSION['dname'.$m]);
        unset($_SESSION['dgname'.$m]);
        unset($_SESSION['size'.$m]);
        unset($_SESSION['drug'.$m]);
     
        if($_SESSION['DG']>1)
        {
            $_SESSION['DG'] = $_SESSION['DG']-1;
        }
    }
}

if($_POST['doSave'] == 'Save')  
{
    for($a=1;$a<=$_SESSION['DG'];$a++)
    {
        $_SESSION['drug'.$a]=$_POST['drug'.$a];
        $drug = $_SESSION['drug'.$a];
        $drugid[$a] = strstr($drug, '-', true); 
        $id = $drugid[$a];
        if(empty($id) or $id == 0) goto Noid; //check for blank input
      
        mysqli_query($link, "UPDATE drug_id SET `location` = '$_SESSION[stackno]' WHERE `id` = '$id'");
        
        Noid:
    }
    // go on to other step
    if(isset($_POST['doSave']))
    {
        for($i=1;$i<=$_SESSION['DG'];$i++)
        {
            unset($_SESSION['drug'.$i]);
            unset($_SESSION['dgname'.$i]);
            unset($_SESSION['size'.$i]);
            unset($_SESSION['dname'.$i]);
        }
        unset($_SESSION['DG']);
        unset($_SESSION['stackno']);
        echo  "<script type='text/javascript'>";
        echo "window.close();";
        echo "</script>";
    }
}

if($_SESSION['DG']<1)
{ 
  $_SESSION['DG']=1;
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
        <select name="stackno" class="required" >
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
<tr><th>Order</th><th width="20%">Barcode/รหัสสินค้า</th><th width="30%">ชื่อสินค้า</th><th>ขนาด</th><th>เพิ่ม</th><th>ลบ</th></tr>
<?php
for($j=1;$j<=$_SESSION['DG'];$j++)
{
if($j==$_SESSION['DG'])
{
echo "<tr><td>".$j."</td><td><input name='drug".$j."' type=text id=drug";
echo " value='".$_SESSION['drug'.$j]."' size=20% autofocus/></td>";
}
else
{
echo "<tr><td>".$j."</td><td><input name='drug".$j."' type=text ";
echo " value='".$_SESSION['drug'.$j]."' size=20%/></td>";
}
echo "<td>";
if(!empty($_SESSION['dname'.$j]))
echo $_SESSION['dname'.$j]."-".$_SESSION['dgname'.$j]."-".$_SESSION['size'.$j]."-".$_SESSION['unit'.$j];
echo "</td>";
echo "<td>";
echo $_SESSION['size'.$j];
echo "</td>";
echo "<td><input type=submit name='add' value='เพิ่ม'></td>";
echo "<td><input type=submit name='del".$j."' value='ลบ'></td>";
echo "</tr>";
}
?>
</table>
<p align="center"><input name="doSave" type="submit" id="doSave" value="Save"></p>
</form></div>
</body>
</html>
