<?php 
include '../../config/dbc.php';

page_protect();

$pin = mysqli_query($linkopd, "select MAX(id) from patient_id ");
$rid = mysqli_fetch_array($pin);

if($_POST['todo']=="Search")
{
  $dn1 = $_POST['drug'];
  $drugid = strstr($dn1, '-', true);
  $drugid = (int)$drugid;
  $ptin = mysqli_query($link, "select * from drug_id where id='$drugid'");
  while ($row2 = mysqli_fetch_array($ptin))
  {
    $dname = $row2['dname'];
    $dgname = $row2['dgname'];
    $dsize = $row2['size'];
  }
  if(empty($dname))
  {
      $ptin = mysqli_query($link, "select * from deleted_drug where id='$drugid'");
      while ($row2 = mysqli_fetch_array($ptin))
      {
	$dname = $row2['dname'];
	$dgname = $row2['dgname'];
	$dsize = $row2['size'];
      }
  }
  $clinic = $_POST['sop'];
}
$title = "::ห้องยา::";
include '../../main/header.php';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color1.css\"/>";
include '../../libs/autodrug.php';
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
	<form method="post" action="drandpt.php" name="regForm" id="regForm">
	<h3 class="titlehdr">รายชื่อผู้ป่วยที่ได้รับยา <?php echo '('.$dname.')-'.$dgname.'-'.$dsize;?>
	<div class="pos_r_fix" ><input type="radio" name="sop" value="1">รวมทั้งหมด<input type="radio" name="sop" value="2">เฉพาะคลินิก<input type='search' name='drug' id='alldrug' >
	<input type="submit" name='todo' value='Search' ></div></h3>
	<?php
	if(!empty($dname))
	{
	echo "<table width='100%' border='1' cellspacing='1' cellpadding='5' class='TFtable'>";
	echo "<th>PID</th><th>ชื่อ</th><th>จำนวน</th><th>เมื่อวันที่</th>";
	  for($i=1;$i<=$rid[0];$i++)
	  {
        $Patient_id = $i;
        include '../../libs/opdxconnection.php';

	    $pttable = "pt_".$i;
	    if($clinic==2)
	    $ptin = mysqli_query($linkopdx, "select * from $pttable where (`idrx1`='$drugid' or `idrx2`='$drugid' or `idrx3`='$drugid' or `idrx4`='$drugid' or `idrx5`='$drugid' or `idrx6`='$drugid' or `idrx7`='$drugid' or `idrx8`='$drugid' or `idrx9`='$drugid' or `idrx10`='$drugid') AND `clinic`='$_SESSION[clinic]'");
	    else 
	    $ptin = mysqli_query($linkopdx, "select * from $pttable where `idrx1`='$drugid' or `idrx2`='$drugid' or `idrx3`='$drugid' or `idrx4`='$drugid' or `idrx5`='$drugid' or `idrx6`='$drugid' or `idrx7`='$drugid' or `idrx8`='$drugid' or `idrx9`='$drugid' or `idrx10`='$drugid'");
	    
	    while ($row2 = mysqli_fetch_array($ptin))
	    {
	      $date = $row2['date'];
	      for($rx=1;$rx<=10;$rx++)
	      {
		$idrx = "idrx".$rx;
		$idvol = "rx".$rx."v";
		if($row2[$idrx]==$drugid)
		{
		$vol = $row2[$idvol];
		}
	      }
	      if($vol>0)
	      {
	      if(empty($ptname))
	      {
		$ptn = mysqli_query($linkopd, "SELECT * FROM patient_id where `id`='$i'");
		while ($rowpt = mysqli_fetch_array($ptn))
		{
		  $ptname = $rowpt['prefix'].' '.$rowpt['fname'].' '.$rowpt['lname'];
		}
		$kn = $kn+1;
	      }
	      echo "<tr><td>";
	      echo $i;
	      echo "</td><td>";
	      echo $ptname;
	      echo "</td><td>";
	      $sumvol = $sumvol+$vol;
	      echo $vol;
	      echo "</td><td>";
	      echo $date;
	      echo "</td></tr>";
	      }
	 //     if($vol>0) goto nextpt;
	    }
	    
//	    nextpt:
	  $ptname = '';
	  }
	      echo "<tr><td>";
	      echo 'รวม';
	      echo "</td><td>";
	      echo $kn.' ฅน';
	      echo "</td><td>";
	      echo $sumvol;
	      echo "</td><td>";
	      echo '';
	      echo "</td></tr>";
	  
	echo "</table>";
	}
	?>
	</form>
<!--menu end-->
	</td>
	<td width="160"></td>
   </tr>
</table>
<!--end menu-->
</body></html>
