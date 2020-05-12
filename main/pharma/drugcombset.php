<?php 
include '../../config/dbc.php';
page_protect();

if($_POST['set'] == 'ReSet')
{
      mysqli_query($link, "TRUNCATE TABLE drugcombset");
}
if($_POST['ลบ'])
{
      mysqli_query($link, "DELETE FROM `drugcombset` WHERE `id` = '$_POST[ลบ]'");
}
if($_POST['set'] == 'Set') 
{ 
  $j = $_SESSION['rowmax']-1;
  for($i=1;$i<=$j;$i++)
  {
    $idin = "idin".$i;
    $invol = "invol".$i;
    $idout = "idout".$i;
    $outvol = "outvol".$i;
    $outsetpoint = "outsetpoint".$i;
    
    mysqli_query($link, "UPDATE drugcombset SET
			    `drugidin` = '$_POST[$idin]',
			    `invol` = '$_POST[$invol]',
			    `drugidout` = '$_POST[$idout]',
			    `outvol` = '$_POST[$outvol]',
			    `outsetpoint` = '$_POST[$outsetpoint]'
			    WHERE id='$i'
			    ");
  }
  
    $i = $_SESSION['rowmax'];
    $idin = "idin".$i;
    $invol = "invol".$i;
    $idout = "idout".$i;
    $outvol = "outvol".$i;
    $outsetpoint = "outsetpoint".$i;
  if(!empty($_POST[$idin]))
  {
    // assign insertion pattern
    $sql_insert = "INSERT into `drugcombset`
			    (`drugidin`,`invol`,`drugidout`, `outvol`, `outsetpoint`)
			VALUES
			    ('$_POST[$idin]','$_POST[$invol]','$_POST[$idout]','$_POST[$outvol]','$_POST[$outsetpoint]')";

    // Now insert into "drugcombset" table
    mysqli_query($link, $sql_insert) ;
  }
  unset($_SESSION['rowmax']);
}
$title = "::ห้องยา::";
include '../../main/header.php';
include '../../main/bodyheader.php';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr><td width="160" valign="top"><div class="pos_l_fix">
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
		</td><td>
<!--menu-->
<form method="post" action="drugcombset.php" name="regForm" id="regForm">
<h3 class="titlehdr">รายการ ยา และ ผลิตภัณฑ์   รายการสั่งยา รวม ที่จะทำการตัด ยอด Stock อัตโนมัติ เมื่อการสั่งถึงกำหนด <button type=submit name=set value=Set>Set</button>  <button type=submit name=set value=ReSet>Reset</button></h3>
<table style="text-align: center;" border="1" cellpadding="2" cellspacing="2" bgcolor="#F0FFFF">
<tbody><tr><th>DrugIDin</th><th>DN</th><th>DrugInVol</th><th>DrugIDOut</th><th>RN</th><th>DrugOutVol</th><th>DrugOutSP</th><th>ลบ</th></tr>
<?php 
$drug = mysqli_query($link, "select * from drugcombset");
$i=1;
while($dcs = mysqli_fetch_array($drug))
{
 echo "<tr><th>";
 echo "<input type=text class=typenumber name='idin".$dcs['id']."' value='".$dcs['drugidin']."'>";
 echo "</th><th>";
 //echo "<div style='background-color:rgba(124,200,0,0.85); display:inline-block;'>";
 if (stripos($dcs['drugidin'], 'L') === FALSE)
 {
    $dn = mysqli_fetch_array(mysqli_query($link, "select * from drug_id where id='$dcs[drugidin]'"));
    echo $dn['dname'];
 }
 else
 {
    $lid = substr($dcs['drugidin'], 1);
    $ln = mysqli_fetch_array(mysqli_query($link, "select * from lab where id='$lid'"));
    if (substr($ln['L_Set'], 5) != "")
    {
        echo $lsetname = substr($ln['L_Set'], 5);
        echo "-";
    }
    echo $ln['S_Name'];
 }
// echo "</div>";
// echo $dsc['drugidin'];
 echo "</th><th>";
 echo "<input type=text class=typenumber name='invol".$dcs['id']."' value='".$dcs['invol']."'>";
// echo $dsc['invol'];
 echo "</th><th>";
 echo "<input type=text class=typenumber name='idout".$dcs['id']."' value='".$dcs['drugidout']."'>";
// echo $dsc['drugidout'];
 echo "</th><th>";
// echo "<div style='background-color:rgba(124,200,0,0.85); display:inline-block;'>";
 if (stripos($dcs['drugidout'], 'R') === FALSE)
 {
    $dn = mysqli_fetch_array(mysqli_query($link, "select * from drug_id where id='$dcs[drugidout]'"));
    echo $dn['dname']."-".$dn['size'];
 }
 else
 {
    $lid = substr($dcs['drugidout'], 1);
    $ln = mysqli_fetch_array(mysqli_query($link, "select * from rawmat where id='$lid'"));
    echo $ln['rawcode'];
 }
// echo "</div>";
 echo "</th><th>"; 
 echo "<input type=text class=typenumber name='outvol".$dcs['id']."' value='".$dcs['outvol']."'>";
// echo $dsc['outvol'];
 echo "</th><th>";
 echo "<input type=text class=typenumber name='outsetpoint".$dcs['id']."' value='".$dcs['outsetpoint']."'>";
// echo $dsc['outsetpoint'];
 echo "</th><th>";
 echo "<button name='ลบ' type='submit' value='".$dcs['id']."'>ลบ</button>";
// echo $dsc['outsetpoint'];
 echo "</th></tr>";
$i=$dcs['id']+1;
}
$_SESSION['rowmax'] = $i;
?>
<tr><th><input type='text' class='typenumber ' name='idin<?php echo $i;?>' autofocus></th><th></th><th><input type='text' class='typenumber ' name='invol<?php echo $i;?>'></th>
<th><input type='text' class='typenumber ' name='idout<?php echo $i;?>'></th><th></th><th><input type='text' class='typenumber ' name='outvol<?php echo $i;?>'></th>
<th><input type='text' class='typenumber ' name='outsetpoint<?php echo $i;?>'></th></tr>
</tbody>
</table>
</form>
</td></tr>
</table> 
<div class="pos_r_fbox" ><palign align="justify" ><h2>วิธีการใช้ การตัดยอด เมื่อการใช้งานถึงยอดที่กำหนด</h2>
ส่วนประกอบ / วิธีการ SET
<ol>
  <li>DrugIDin คือ ID ของ ยา/Lab ที่จะสั่ง</li>
  <li>DrugInVol คือ จำนวนการสั่งต่อครั้งของ ยา/Lab DrugIDin</li>
  <li>DrugIDOut คือ ID ของ ยา/วัตถุดิบ ที่จะตัดยอด ในการสั่งยา/Lab DrugIDin</li>
  <li>DrugOutVol คือ จำนวน ยา/วัตถุดิบ ของ DrugIDOut ที่จะตัด ต่อการสั่งต่อครั้งของ ยา/Lab DrugIDin (ใส่ -1 เมื่อต้องการตัดเป็นครั้งที่สั่งไม่สนใจจำนวน)</li>
  <li>DrugOutSP คือ จำนวน ของ ยา/วัตถุดิบ ที่ตัดยอด ไป 1 ครั้งเมื่อถึงกำหนดนี้</li>
  <li>ยา/หัตถการที่อยู่ใน List ของ "รายการยาและผลิตภัณฑ์" ให้ใส่เฉพาะ เลข id</li>
  <li>วัตถุดิบ List ใน "รายการ RawMat" ให้ใส่ "r" หรือ "R" ตามดัวย id</li>
  <li>LAB ที่อยู่ใน List ของ "รายการ Lab" ให้ใส่ "l" หรือ "L" เลข No. ถ้าเป็น SET ของ Lab ให้ใส่ No. Lab ที่ต้องตรวจ 1 ตัว เช่น CBC มีสมาชิก 10 ตัว เลือกมา 1</li>
</ol>ตัวอย่างเช่น
<ol><li>ทำการฉีดยา order A 1 หน่วย(ID=15 (ดูในรายการยา)) ประกอบด้วยการใช้ยา 2 ตัว B(ID=150) 1amp ตัดยอด 1 amp, C(ID=240) 2.5ml ตัดยอดที่ 10ml/vial และ วัตถุดิบ/RawMat 3 อย่าง คือ Syring 10ml x1 (id=4 (ดูในรายการRawmat)) ตัดยอดที่ 100/กล่อง, เข็ม 2 อัน คือ 18G1" x1 (id=14) ตัดยอดที่ 100/กล่อง, 24G 11/2" x1 (id=35)ตัดยอดที่ 100/กล่อง</li>
<li>DTX(No=5009) ด้วย DTX set จาก Rawmat(ID=60) 1อัน ใน 30set/กล่อง</li>ลงเป็น
<ul style="list-style-type:disc">
  <li>DrugIDin = 15 ,DrugInVol=1, DrugIDOut=150 ,DrugOutVol= 1 ,DrugOutSP=1 </li>
  <li>DrugIDin = 15 ,DrugInVol=1, DrugIDOut=240 ,DrugOutVol= 2.5 ,DrugOutSP=10 </li>
  <li>DrugIDin = 15 ,DrugInVol=1, DrugIDOut=r4 ,DrugOutVol= 1 ,DrugOutSP=100 </li>
  <li>DrugIDin = 15 ,DrugInVol=1, DrugIDOut=r14 ,DrugOutVol= 1 ,DrugOutSP=100 </li>
  <li>DrugIDin = 15 ,DrugInVol=1, DrugIDOut=R35 ,DrugOutVol= 1 ,DrugOutSP=100 </li>
  <li>DrugIDin = L5009 ,DrugInVol=1, DrugIDOut=R60 ,DrugOutVol= 1 ,DrugOutSP=30 </li>
</ul></p></div> 
<!--end menu-->
</body></html>
