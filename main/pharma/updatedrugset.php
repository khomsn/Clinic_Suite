<?php 
include '../../config/dbc.php';
page_protect();

$id = $_SESSION['drugid'];
$table = "set_drug_".$id;
$stock_in = mysqli_query($link, "select * from drug_id where id='$id' ");

$filter = mysqli_query($link, "select * from drug_id ");		
	while ($row = mysqli_fetch_array($filter))
	{
		if($maxdrid<$row['id']) $maxdrid = $row['id'] ;
	}	
if($_POST['del'] == 'ลบ')
{
	for($j=1;$j<=$maxdrid;$j++)
	{ 
		if($_POST[$j] ==1)
		{
			mysqli_query($link, "DELETE FROM `$table` WHERE `drugid` = $j");
		}
	}
}

if($_POST['register'] == 'แก้ไข') 
{ 
mysqli_query($link, "UPDATE drug_id SET
			`uses` = '$_POST[uses]',
			`sellprice` = '$_POST[sellprice]',
			`min_limit` = '$_POST[min_limit]',
			`typen` = '$_POST[type]',
			`groupn` = '$_POST[group]'
			 WHERE id='$id'
			");
// go on to other step
header("Location: drugset.php");  

}

$title = "::ห้องยา::";
include '../../main/header.php';
include '../../libs/popup.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../main/bodyheader.php';

?>
<div class="pos_l_fix">
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
?>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main"><!--Table1-->
<tr><td style="text-align: center; width: 170px; "></td>
    <td><h3 class="titlehdr">แก้ไข ทะเบียนยาชุด</h3>
<form method="post" action="updatedrugset.php" name="regForm" id="regForm">
      <table style="text-align: left; width: 703px; height: 413px;" border="0" cellpadding="2" cellspacing="2"  class="forms"><!--Table2-->
      <tbody><tr><td style="width: 646px; vertical-align: middle;">
		  <div style="text-align: center;">ชื่อ: &nbsp; 
		  <?php
		  while ($row_settings = mysqli_fetch_array($stock_in))
		  {
		    echo $row_settings['dname'];
		  ?>
		  <br>
		  ชื่อสามัญ: &nbsp; 
		  <?php
		    echo $row_settings['dgname'];
		  ?>
		  <br>
		  ขนาด: &nbsp;
		  <?php
		    echo $row_settings['size'];
		  ?>
		  <br>
		  <a HREF="drugsetadd.php" onClick="return popup(this, 'notes','800','500','yes')" ><big><big>เพิ่มยา</big></big></a> 
		  <br>
		  </div>
		  <hr style="width: 80%; height: 2px; margin-left: auto; margin-right: auto;">
		  <br>
		  <div style="text-align: center;"><!--Table3-->
		    <?php 
		    $filter = mysqli_query($link, "select * from $table ");	
		    if($filter != 0)
		    {
		    echo "<table style='text-align: left; width: 100%;' border='1' cellpadding='2' cellspacing='2'  class='forms'>
		    <tbody>
		    <tr><th width = 10><input type='submit' name='del' value='ลบ'></th><th width =250>ชื่อ+ขนาด</th><th>วิธีใช้</th><th width = 35>จำนวน</th></tr>";
		    $i=1;
		    while ($row = mysqli_fetch_array($filter))
		    {
		    echo "<tr><td><input type=checkbox name="; echo $row['drugid']; echo " value=1 ></td><td>";
		    $idd = $row['drugid'];
		    $filter2 = mysqli_query($link, "select * from drug_id WHERE id = $idd ");
		    while ($row2 = mysqli_fetch_array($filter2))
		    {
		    echo $row2['dname'].'-'.$row2['size'].'('.$row2['dgname'].')';
		    $use1 = $row2['dname'].'-'.$row2['size'].'('.$row2['dgname'].')';
		    }		
		    echo "</td>";
		    echo "<td>";
		    echo $row['uses'];
		    $use2 = $row['uses'];
		    echo "</td>";
		    echo "<td>";
		    echo $row['volume'];
		    $use3 = $row['volume'];
		    echo "</td></tr>";
		    $use[$i] = $use1.' ('.$use2.' / '.$use3.' )';
		    $iall = $i;
		    $i = $i+1;
		    }	
if($use!=''){	echo "<input type='hidden' name='uses' value=' "; echo join(" , ", $use) ; echo " ' >"; }

echo 	"</tbody>
</table>";
}
?><!--end Table3-->

  </div>
  <hr style="width: 80%; height: 2px;"><br>
  <div style="text-align: center;">
  ราคาขาย: <input type=number class="typenumber" step=0.01 name="sellprice" value="<?php	echo $row_settings['sellprice']; ?>"> บาท
  &nbsp; &nbsp; &nbsp;
  จำนวนคงคลังขั้นต่ำ
  <input type=number class="typenumber" name="min_limit" value="<?php
  echo $row_settings['min_limit'];
  ?>"><br>
  </div>
  <hr style="width: 80%; height: 2px;"><br>
  <div style="text-align: center;">
  <?php	
  $dtype = mysqli_query($link, "SELECT name FROM drug_type");
  $dgroup = mysqli_query($link, "SELECT name FROM drug_group");
  ?>
  ประเภท&nbsp;
  <select  name="type">
  <option value="<?php
  echo $row_settings['typen'];
  ?>" selected><?php
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
  กลุ่ม&nbsp;
  <select name="group">
  <option value="<?php
  echo $row_settings['groupn'];
  ?>" selected><?php
  echo $row_settings['groupn'];
  }
  ?>
  </option>
  <?php while($grow = mysqli_fetch_array($dgroup))
  {
  echo "<option value='";
  echo $grow['name'];
  echo "'>";
  echo $grow['name']."</option>";
  }
  ?>
  </select>
  <br>
  </div>
  </td></tr>
  <tr><td><br><br><div style="text-align: center;"><input name="register" value="แก้ไข" type="submit"></div></td></tr>
  </tbody>
  </table><!--end Table2-->
  <br>
  </form>
  <!--menu end-->
</td><td width="60"></td></tr>
</table><!--end Table1-->
</body>
</html>
