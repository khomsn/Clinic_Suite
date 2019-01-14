<?php 
include '../../config/dbc.php';
page_protect();

$fulluri = $_SERVER['REQUEST_URI'];
$inv_num =  substr_replace($fulluri,'',0,34); 
$sply = mysqli_query($link, "select * from supplier where id='$_SESSION[spid]' ");

while ($rowsp = mysqli_fetch_array($sply))
{
	$spname = $rowsp['name'];
	$spacno = $rowsp['ac_no'];
}	

$id = $_SESSION['spid'];
$sptable = "sp_".$id;

$spp = mysqli_query($link, "select * from $sptable where inv_num='$inv_num' AND payment='0'");

$i = 1;
while($srow1 = mysqli_fetch_array($spp))
{
	$payid[$i] = $srow1['id'];
	$pprice[$i] = $srow1['price'];
	$i = $i+1;
	$duedate = $srow1['duedate'];
} 
$imax = $i;

if($_POST['set']=='SET')
{
  $date=date_create($_POST['duedate']);
  $date=date_format($date,"Y-m-d");
  
  mysqli_query($link, "UPDATE $sptable SET `duedate` = '$date' WHERE `inv_num` = '$inv_num'");
  header("Location: $fulluri");
}

if($_POST['doSave'] == 'จ่าย')  
{
$day = $_POST['day'];
$month = $_POST['month'];
$byear = $_POST['year'];
$year = $byear - 543;

// format date for mysql
$pday = $year.'-'.$month.'-'.$day;
//update sp_# pay
for($i=1;$i<$imax;$i++)
{
	
	if ($_POST['pay_'.$i] == 1)
	{
		mysqli_query($link, "UPDATE $sptable SET `payment` = 1 WHERE `inv_num` = '$inv_num' and id = $payid[$i]");
		$price = $price + $pprice[$i];
	}	
}
// accounting system

	$sup_ac = $_POST['payby'];
	if($_POST['payby'] != 10000001)//ค่าธรรมเนียมธนาคาร
	{
	  if($_POST['free']!= 0){
		// assign insertion pattern
		$pacnum = $_POST['payby'] + 40000000;
		$sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`,`price`,`type`,`bors`,`recordby`)
						VALUES  (now(),'$pacnum','$_POST[payby]','ค่าธรรมเนียมการโอนเงิน','$_POST[free]','c','p','$_SESSION[user_id]')";
			// Now insert Drug order information to "drug_#id" table
			mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));
		}
	}	

	// assign insertion pattern
	$detail = "จ่าย ".$spname."->InvNo.".$inv_num;
	$sql_insert = "INSERT into `daily_account`	(`date`,`ac_no_i`, `ac_no_o`, `detail`, `inv_num`, `price`,`type`,`recordby`)
		       VALUES  ('$pday','$spacno','$sup_ac','$detail','$inv_num','$price','c','$_SESSION[user_id]')";
	// Now insert Drug order information to "drug_#id" table
	mysqli_query($link, $sql_insert) or die("Insertion Failed:" . mysqli_error($link));

	
// go on to other step
header("Location: updatesp.php");  
 }
 
$title = "::จ่ายค่า ยาและผลิตภัณฑ์::";
include '../../main/header.php';
echo "<script language=\"JavaScript\" type=\"text/javascript\" src=\"../../jscss/js/checkthemall.js\"></script>";
$formid = "formMultipleCheckBox";
include '../../libs/validate.php';
include '../../libs/currency.php'; 

include '../../main/bodyheader.php';

?>
<form action="paysupply.php?msg=<?php echo $inv_num;?>" method="post" name="inForm" id="formMultipleCheckBox">
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="200" valign="top"><div class="pos_l_fix">
		<?php 
			/*********************** MYACCOUNT MENU ****************************
			This code shows my account menu only to logged in users. 
			Copy this code till END and place it in a new html or php where
			you want to show myaccount options. This is only visible to logged in users
			*******************************************************************/
			if (isset($_SESSION['user_id']))
			{
				include 'spmenu.php';
			} 
		/*******************************END**************************/
		?></div>
	</td>
	<td width="732" valign="top">
	<h3 class="titlehdr">จ่ายค่า ยาและผลิตภัณฑ์: <?php echo $spname;?></h3>
	<h3 class="titlehdr2"><div>ใบส่งของเลขที่ <?php echo $inv_num;?></div><div>Due Date (m/d/Y):<input name=duedate type="date" value="<?php echo $duedate;?>"><input type=submit name=set value="SET"></div></h3>
		<table width="90%" border="1" align="center" cellpadding="3" cellspacing="3" class="forms">
			<tr><td width=100%; align = center>วันที่&nbsp;
			      <select tabindex="1" name="day">
			      <option value="<?php echo (idate("d"));?>" selected><?php echo (idate("d"));?></option>
			      <option value="1">1</option>
			      <option value="2">2</option>
			      <option value="3">3</option>
			      <option value="4">4</option>
			      <option value="5">5</option>
			      <option value="6">6</option>
			      <option value="7">7</option>
			      <option value="8">8</option>
			      <option value="9">9</option>
			      <option value="10">10</option>
			      <option value="11">11</option>
			      <option value="12">12</option>
			      <option value="13">13</option>
			      <option value="14">14</option>
			      <option value="15">15</option>
			      <option value="16">16</option>
			      <option value="17">17</option>
			      <option value="18">18</option>
			      <option value="19">19</option>
			      <option value="20">20</option>
			      <option value="21">21</option>
			      <option value="22">22</option>
			      <option value="23">23</option>
			      <option value="24">24</option>
			      <option value="25">25</option>
			      <option value="26">26</option>
			      <option value="27">27</option>
			      <option value="28">28</option>
			      <option value="29">29</option>
			      <option value="30">30</option>
			      <option value="31">31</option>
			      </select>
			      &nbsp;เดือน &nbsp;
			      <select tabindex="2" name="month">
			      <option value="<?php echo (idate("m"));?>" selected><?php echo (idate("m"));?></option>
			      <option value="1">1</option>
			      <option value="2">2</option>
			      <option value="3">3</option>
			      <option value="4">4</option>
			      <option value="5">5</option>
			      <option value="6">6</option>
			      <option value="7">7</option>
			      <option value="8">8</option>
			      <option value="9">9</option>
			      <option value="10">10</option>
			      <option value="11">11</option>
			      <option value="12">12</option>
			      </select>
			      พ.ศ. <input tabindex="3" name="year" size="5" maxlength="4" type="text" value="<?php echo (idate("Y")+543);?>"><br>
			</td></tr>
		</table>
	<div style="text-align: center;">
	<table width="90%" border="1" align="center" cellpadding="3" cellspacing="3" class="forms">
	    <tr><th><input name="checkAll" type="checkbox" id="checkAll" value="1" onclick="javascript:checkThemAll(this);" />เลือก</th><th>วันที่</th><th>รายการ</th><th>ราคา</th></tr>
	    <?php
	    $mi = 1;
	    $spp = mysqli_query($link, "select * from $sptable where inv_num = '$inv_num' AND payment = '0' ");
    while($smp = mysqli_fetch_array($spp))
    {	
	    echo "<tr><th width =5%>";
	    ?><input type="checkbox" name="<?php echo "pay_".$mi; ?>" value="1" id="checkBoxes" >
	    <?php
	      echo "</th><th width=10%>"; 
	      echo $smp['date'];
	      echo "</th><th width=65% style='text-align: left;'>";
	      $dorw = $smp['inid'];
	    // check is this drug or rawmat
	      if(is_numeric(substr($dorw, 0, 1)))
	      {  
		//this is drug
		$chid = mysqli_query($link, "SELECT * FROM drug_id WHERE id = $dorw");
		while ($dpl = mysqli_fetch_array($chid))
		{
		  echo $dpl['dname'].'-('.$dpl['dgname'].')-'.$dpl['size'];
		}
	      }
	      else
	      { //this is rawmat
	      
		$dorw = substr($dorw, 1);
		$chid = mysqli_query($link, "SELECT * FROM rawmat WHERE id = $dorw");
		while ($dpl = mysqli_fetch_array($chid))
		{
		  echo $dpl['rawcode'].'-('.$dpl['rawname'].')-'.$dpl['size'];
		}
	      }
	      echo "</th><th width=20%><span class=currency>"; 
	      echo $smp['price']; $allprice = $allprice+$smp['price'];
	      echo "</span></th></tr>";
	      $mi = $mi+1;
    }
      echo "<tr><th>รวม</th><th>". ($mi-1) ." รายการ</th><th>ราคา (บาท)</th><th><span class=currency>".$allprice."</span></th></tr>";
      echo "</table>";?><br>ชำระโดย&nbsp;
      <select tabindex="10" name="payby" class="required" >
      <?php //10000001 เงินสด    10000002-10000249 ธนาคาร

      $acname = mysqli_query($link, "SELECT * FROM acnumber WHERE ac_no >= 10000001 AND ac_no <=10000249");
      while($row = mysqli_fetch_array($acname))
      {
	      echo "<option value='";
	      echo $row['ac_no'];
	      echo "'>";
	      echo $row['name'];
	      echo "</option>";
      }	
      ?>
      </select>&nbsp;ค่าธรรมเนียมการโอน<input type="number" min="0" step="1" name="free" size="6" >
	</div>
	<p align="center"><input name="doSave" type="submit" id="doSave" value="จ่าย"></p>
    </td><td width="196" valign="top">&nbsp;</td></tr>
</table>
</form>
</body>
</html>
