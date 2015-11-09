<?php 
include '../login/dbc.php';
page_protect();
include '../libs/progdate.php';

$dtype = mysqli_query($link, "SELECT year FROM sell_account WHERE  id=1");
$row = mysqli_fetch_array($dtype);
$minyear = $row[0];

?>

<!DOCTYPE html>
<html>
<head>
<title>รายงานจำนวนผู้ป่วย</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<script language="JavaScript" type="text/javascript" src="../public/js/jquery-2.1.3.min.js"></script>
<script language="JavaScript" type="text/javascript" src="../public/js/jquery.validate.js"></script>
<link rel="stylesheet" href="../public/css/styles.css">
<link rel="stylesheet" href="../public/js/jquery-ui-1.11.2.custom/jquery-ui.css">
<script src="../public/js/jquery-ui-1.11.2.custom/external/jquery/jquery.js"></script>
<script src="../public/js/jquery-ui-1.11.2.custom/jquery-ui.js"></script>
<script>
$(function() {
$( "#datepicker" ).datepicker();
$( "#datepicker1" ).datepicker();
});
</script>
<?php include '../libs/currency.php'; ?>
</head>
<?php 
if(!empty($_SESSION['user_background']))
{
echo "<body style='background-image: url(".$_SESSION['user_background'].");' alink='#000088' link='#006600' vlink='#660000'>";
}
else
{
?>
<body style="background-image: url(../image/ptbg.jpg);" alink="#000088" link="#006600" vlink="#660000">
<?php
}
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
		    include 'reportmenu.php';
	    } 
    /*******************************END**************************/
    ?>
    </div></td>
    <td><form method="post" action="ptreport.php" name="regForm" id="regForm">
	<h3 class="titlehdr">รายงานผู้ป่วยประจำปี พ.ศ. <?php echo $bsy; //date("Y")+543;
	if ($_SESSION['user_accode']%2==0  and $_SESSION['user_level']>1)
	  {
	    {
	      if($sy>$minyear) echo "&nbsp;<input type='submit' name='todoy' value = '<<'>";
	      echo "&nbsp;<input type='submit' name='todoy' value = '@'>&nbsp;";
	      if ($sy < date("Y"))
	      {
	      echo "<input type='submit' name='todoy' value = '>>'>";
	      }
	    }
	  }?> หรือ ตั่งแต่ 
<input name=fdate type="text" id="datepicker" value="<?php $fdate=date_create($fdate);$fdate=date_format($fdate,"m/d/Y");echo $fdate;?>">
 ถึง <input name=tdate type="text" id="datepicker1" value="<?php $tdate=date_create($tdate);$tdate=date_format($tdate,"m/d/Y");echo $tdate;?>">	  
	</h3>
	<table style="text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
	  <tbody>
	    <tr><td style="width: 50%; vertical-align: top; background-color: rgb(255, 255, 204);">
		    <table style="text-align: center; margin-left: auto; margin-right: auto; width: 100%;" border="1" cellpadding="2" cellspacing="2">
		      <tr>
			<th>เดือน
			</th>
			<th width = 10%>จำนวน visit (ครั้ง)
			</th>
			<th width = 10%>ผู้ป่วยทั้งหมด (ฅน)
			</th>
			<th width = 10%>ผู้ป่วยรายใหม่ (ฅน)
			</th>
		      </tr>
			  <?php 	
			  if($sm == date("m") and $sy == date("Y")) $imax = date("d");
			  elseif($sm == 1 or $sm == 3 or $sm == 5 or $sm == 7 or $sm == 8 or $sm == 10 or $sm == 12) $imax=31;
			  elseif($sm == 2 and $sy%4 == 0) $imax = 29;
			  elseif($sm == 2 and $sy%4 != 0) $imax = 28;
			  else $imax = 30;
			  for ($i = 1;$i<=12;$i++)
			  {
			    // Print out the contents of each row into a table
				    echo "<tr><th >"; 
				    echo $i.' / '. $bsy ;
			    $dtype = mysqli_query($link, "SELECT * FROM sell_account WHERE  month ='$i' AND year ='$sy' ");
			    while($row = mysqli_fetch_array($dtype))
			    {
				    $count[$i]=mysqli_num_rows($dtype);
			    } 
				    echo "</th><th width=15%  style='text-align: right;'>";
				    echo $count[$i];
				    $AllVS = $AllVS+$count[$i];
				    echo "</th><th width=15%  style='text-align: right;'>";
			    $dtype = mysqli_query($link, "SELECT COUNT(DISTINCT ctmid) AS NumberOfCustomers FROM sell_account WHERE  month ='$i' AND year ='$sy' ");
			    while($row = mysqli_fetch_array($dtype))
			    {
				    $NoP[$i]=$row['NumberOfCustomers'];
			    } 
				    echo $NoP[$i];
			    $dtype = mysqli_query($link, "SELECT COUNT(DISTINCT ctmid) AS NumberOfCustomers FROM sell_account WHERE  year ='$sy' ");
			    while($row = mysqli_fetch_array($dtype))
			    {
				    $AllPt=$row['NumberOfCustomers'];
			    } 
				    echo "</th><th width=15%  style='text-align: right;'>"; 
			    $dtype = mysqli_query($linkopd, "SELECT * FROM patient_id WHERE  MONTH(date) ='$i' AND YEAR(date) ='$sy' AND clinic ='$_SESSION[clinic]' ");
			    while($row = mysqli_fetch_array($dtype))
			    {
				    $NPT[$i]=mysqli_num_rows($dtype);
			    } 
				    echo $NPT[$i];
				    $AllNP = $AllNP+$NPT[$i];
				    echo "</th></tr>";
			  }
		      echo "<tr><th>ยอดรวม</th><th  style='text-align: right;'>";
		      echo "<span class=currency>".$AllVS."</span>";
		      echo "</th><th style='text-align: right;' >";
		      echo "<span class=currency>".$AllPt."</span>";
		      echo "</th><th style='text-align: right;'>";
		      echo "<span class=currency>".$AllNP."</span>";
		      echo "</th></tr>";
$lyear = $sy-1;
$thisdate = date_create();
date_date_set($thisdate, $lyear, 10, 1);
$strdate = date_format($thisdate, 'Y-m-d');

$thisdate = date_create();
date_date_set($thisdate, $sy, 9, 30);
$stpdate = date_format($thisdate, 'Y-m-d');
      
		      echo "<tr><th> ยอดรวม ตั้งแต่ 1 ตุลาคม ".($bsy-1)." - 30 กันยายน ".$bsy."</th><th  style='text-align: right;'>";
		      $dtype = mysqli_query($link, "SELECT * FROM sell_account WHERE  vsdate>='$strdate' AND vsdate<='$stpdate' ");
		      while($row = mysqli_fetch_array($dtype))
		      {
			      $allvs=mysqli_num_rows($dtype); //all visit
		      } 
		      $dtype = mysqli_query($link, "SELECT COUNT(DISTINCT ctmid) AS NumberOfCustomers FROM sell_account WHERE  vsdate>='$strdate' AND vsdate<='$stpdate' ");
		      while($row = mysqli_fetch_array($dtype))
		      {
			      $AllPt=$row['NumberOfCustomers'];
		      } 
		      
		      echo "<span class=currency>".$allvs."</span>";
		      echo "</th><th style='text-align: right;' >";
		      echo "<span class=currency>".$AllPt."</span>";
		      echo "</th><th style='text-align: right;'>";
		      echo "</th></tr>";
			?>
		  </table>
	    </td></tr>
	  </tbody>
	</table>
    <br>
    </td></form>
<td width=130px><?php include 'reportrmenu.php';?></td></tr>
</table>
</body></html>