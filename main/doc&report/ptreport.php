<?php 
include '../../config/dbc.php';

page_protect();
include '../../libs/dateandtimezone.php';
include '../../libs/progdate.php';

$dtype = mysqli_query($link, "SELECT year FROM sell_account WHERE  id=1");
$row = mysqli_fetch_array($dtype);
$minyear = $row[0];

$title = "::รายงานจำนวนผู้ป่วย::";
include '../../main/header.php';
include '../../libs/currency.php';
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
	  }?></h3>
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
<td width=130px></td></tr>
</table>
</body></html>
