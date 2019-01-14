<?php 
include '../../config/dbc.php';
page_protect();

$filter = mysqli_query($link, "select * from drug_id ");		
	while ($row = mysqli_fetch_array($filter))
	{
		if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
	}
$filter = mysqli_query($link, "select * from drug_id  WHERE seti != 1 ORDER BY `dgname` ASC");

if ($_POST['todo'] == 'กรอง' ) 
{
	if($_POST['type'] != '' AND $_POST['group'] !='' )
	{
		$filter = mysqli_query($link, "select * from drug_id  WHERE seti != 1 AND typen='$_POST[type]' AND  `groupn` ='$_POST[group]' ORDER BY `dgname` ASC ");	
	}	
	if($_POST['type'] != '' AND $_POST['group'] =='' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE seti != 1 AND typen='$_POST[type]' ORDER BY `dgname` ASC ");	
	}	
	if($_POST['group'] !=''  AND  $_POST['type'] == '' )
	{
		$filter = mysqli_query($link, "select * from drug_id WHERE  seti != 1 AND `groupn` ='$_POST[group]' ORDER BY `dgname` ASC ");	
	}	
}

if($_POST['register'] == 'ดูข้อมูล') 
{ 
    // pass drug-id to other page
    $_SESSION['drugid'] = $_POST['drugid'];
    // go on to other step
    header("Location: updatedrugid.php");  
}
$popupmaxid = $maxdrid;

$title = "::รายการยาและผลิตภัณฑ์::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/popuponpage.css\"/>";
include '../../libs/popup.php';
include '../../libs/popuponpage.php';
include '../../main/bodyheader.php';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td style="text-align: left; width:170px;"><div class="pos_l_fix">
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
    <td><div align="center"><h3 class="titlehdr">รายการ ยา และ ผลิตภัณฑ์</h3><form method="post" action="druglist.php" name="regForm" id="regForm">
            <table style="text-align: center;" border="0" cellpadding="2" cellspacing="2">
            <tr><td style="vertical-align: middle; ">
                    <?php	
                            echo "<table class='TFtable' border='1' style='text-align: left; margin-left: auto; margin-right: auto; background-color: rgb(152, 161, 76);'>";
                            echo "<tr><th>เลือก</th>";
                            if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0)
                            {
                            echo "<th>id</th>";
                            }
                            echo "<th>ชื่อ</th><th>ชื่อสามัญ</th><th>ขนาด</th><th style='background-color:#A9E2F3'>จำนวน</th><th>RSVol</th><th>ราคา</th>";
                            if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0)
                            {
                            echo "<th>SP/BP</th>";
                            }
                            echo "<th><a onClick=\"return popup(this, 'notes','800','450','yes')\" HREF=\"drugstackingplace.php\">Location</a></th>";
                            echo "</tr>";
                            while($row = mysqli_fetch_array($filter))
                                {
                                    // Print out the contents of each row into a table
                                    echo "<tr><td>";
                                    $msg = urlencode($row['id']);
                                    if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0)
                                    {
                                    echo "<input type='radio' name='drugid' value='".$row['id']."' />";
                                    echo "</td><td>";
                                    echo $row['id'];
                                    }
                                    echo "</td><td>"; 
                                    echo "<div class='popup' onmouseover='myFunction".$row['id']."()' onmouseout='myFunction".$row['id']."()'>";
                                    echo "<a href='updatedrugimage.php?msg=".$msg."' onClick=\"return popup(this, 'name' , '800' , '500' , 'yes' );\">";
                                    echo $row['dname'];
                                    echo "</a>";
                                    echo "<span class=\"popuptext\" id=\"myPopup".$row['id']."\">";
                                    echo "<img src=\"../".DRUGIMAGE_PATH."drug_".$row['id'].".jpg\" width=150 height=150 />";
                                    echo "</span>";
                                    echo "</div>";
                                    echo "</td><td>"; 
                                    echo $row['dgname'];
                                    echo "</td><td >"; 
                                    echo $row['size'];
                                    echo "</td><td style='text-align: right; background-color:#A9E2F3;'>"; 
                                    echo $row['volume'];
                                    echo "</td><td style='text-align: center;'>"; 
                                    echo $row['volreserve'];
                                    echo "</td><td style='text-align: right;' >"; 
                                    echo $row['sellprice'];
                                    echo "</td>";
                                    if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0)
                                    {
                                    echo "<td style='text-align: right;' >";
                                    $drugtable = "drug_".$row['id'];
                                    $buyprice = 0;
                                    $getprice = mysqli_query($link, "select * from $drugtable WHERE supplier!='$_SESSION[clinic]' AND price!='0' ORDER BY `id` DESC ");
                                    while($row2 = mysqli_fetch_array($getprice))
                                    {
                                        $buyprice = $row2['price']/$row2['volume'];
                                        break;
                                    }
                                    echo round($row['sellprice']/$buyprice,2);
                                    echo "</td>";
                                    }
                                    echo "<td>";
                                    echo $row['location'];
                                    echo "</td></tr>";
                            } 
                            echo "</table>";
                    ?>
            </td></tr></table>
<div class="pos_r_fix" style="text-align: right;"><br><br><br><br><br><br>
<?php if($_SESSION['user_accode']%7==0 or $_SESSION['user_accode']%13==0){?><input name="register" value="ดูข้อมูล" type="submit" ><?php }?>
<?php	
$dtype = mysqli_query($link, "SELECT name FROM drug_type");
$dgroup = mysqli_query($link, "SELECT name FROM drug_group");
?>
ประเภท:<select name="type"><option value="" selected></option>
<?php while($trow = mysqli_fetch_array($dtype))
{
echo "<option value=\"";
    echo $trow['name'];
    echo "\">";
    echo $trow['name']."</option>";
}
?>
</select>
<br><br>
กลุ่ม:<select name="group"><option value="" selected></option>
<?php while($grow = mysqli_fetch_array($dgroup))
{
    echo "<option value=\"";
    echo $grow['name'];
    echo "\">";
    echo $grow['name']."</option>";
}
?>
</select>
<br><br><input type="submit" name='todo' value='กรอง' >
</div>	
</form>
</div></td><td width=250px></td></tr>
</table>
</body></html>
