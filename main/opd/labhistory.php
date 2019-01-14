<?php 
include '../../config/dbc.php';

//include '../Grap/Graph.php';
page_protect();
$fulluri = $_SERVER['REQUEST_URI'];
$inid = filter_var($fulluri, FILTER_SANITIZE_NUMBER_INT);
$labidin = $_GET['labidin'];
$id = $_SESSION['patdesk'];
$Patient_id = $id;
include '../../libs/opdxconnection.php';

$ptin = mysqli_query($linkopd, "select * from patient_id where id='$id' ");
while($infor = mysqli_fetch_array($ptin))
{
  $prefix=$infor['prefix'];
  $fname=$infor['fname'];
  $lname=$infor['lname'];
  
}
$pttable = "pt_".$id;

$pin = mysqli_query($linkopdx, "select * from $pttable ");

$title = "::Lab History::";
include '../../main/header.php';
?>
<script language="javascript">
function Clickheretoprint()
{ 
  var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
      disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25"; 
  var content_vlue = document.getElementById("print_content").innerHTML; 
  
  var docprint=window.open("","",disp_setting); 
   docprint.document.open(); 
   docprint.document.write("<html><head><title>Lab result</title>"); 
   docprint.document.write("<link rel='stylesheet' href='../../jscss/css/recform_print.css'/>"); 
   docprint.document.write("</head><body onLoad='self.print()'>");          
   docprint.document.write(content_vlue);          
   docprint.document.write("</body></html>"); 
   docprint.document.close(); 
   docprint.focus(); 
}
</script>
</head><body>
<div align="center"><input type="submit" name="OK" value="Print" onClick="javascript:Clickheretoprint()" ></div>
<div class="style3" id="print_content">
<div id="content">
	<div style="text-align: center;">
	<?php 
	echo "ผล Lab ของ ";
        echo "<a HREF=labhistory.php>".$prefix." ".$fname." ".$lname."</a>";
	echo "<br>";
	if(empty($inid) or ($inid ==0))
	{
	$pin = mysqli_query($linkopdx, "select * from (select * from $pttable WHERE labid!='' ORDER BY `date` DESC limit 0,10) t ORDER BY `date` ASC") ;
	}
	elseif(!empty($inid))
	{
	$pin = mysqli_query($linkopdx, "select * from (select * from $pttable WHERE labid!='' AND id=$inid ORDER BY `date` DESC limit 0,10) t ORDER BY `date` ASC") ;
	}
	if(!empty($labidin))
	{
	$pin = mysqli_query($linkopdx, "select * from (select * from $pttable WHERE `labid` LIKE '%$labidin%' ORDER BY `date` DESC limit 0,10) t ORDER BY `date` ASC") ;
	 $fls = 1;
	}
	if($fls!=1)
	{
	echo "<table style=' width: 80%; text-align: center; margin-left: auto; margin-right: auto;' border='1' cellpadding='1' cellspacing='1'>";
	echo "<tr><th>วัน</th><th>Lab Information</th>";
	while ($row_settings = mysqli_fetch_array($pin))
	{
		echo "<tr><td>";
		$date = new DateTime($row_settings['date']);
		$sd = $date->format("d");
		$sm = $date->format("m");
		$sy = $date->format("Y");
		$bsy = $sy+543;
		echo "<a HREF=labhistory.php?msg=".$row_settings['id']." >";
		echo $sd;
		echo " ";
		$m = $sm;
		switch ($m)
		{
		 case 1:
		 echo "มค";
		 break;
		 case 2:
		 echo "กพ";
		 break;
		 case 3:
		 echo "มีค";
		 break;
		 case 4:
		 echo "เมย";
		 break;
		 case 5:
		 echo "พค";
		 break;
		 case 6:
		 echo "มิย";
		 break;
		 case 7:
		 echo "กค";
		 break;
		 case 8:
		 echo "สค";
		 break;
		 case 9:
		 echo "กย";
		 break;
		 case 10:
		 echo "ตค";
		 break;
		 case 11:
		 echo "พย";
		 break;
		 case 12:
		 echo "ธค";
		 break;
		}
		echo " ";
		echo $bsy; //date("Y")+543;
		echo "</a>";
		echo "</td>";
		echo "<td>";
		
	$labidr=$row_settings['labid'];
	$labrsr=$row_settings['labresult'];
	if(!empty($labidr))
	{
	    $n = substr_count($labidr, ';');
	    //$str = 'hypertext;language;programming';
	    $charsl = preg_split('/;/', $labidr);
	}
	if(!empty($labrsr))
	{
	    $n = substr_count($labrsr, ';');
	    //$str = 'hypertext;language;programming';
	    $charsr = preg_split('/;/', $labrsr);
    //	print_r($charsr);
	}
	

	echo "<table style=' width: 100%; text-align: center; margin-left: auto; margin-right: auto;' border='1' cellpadding='1' cellspacing='1'>";
	echo "<tr><th>Lab Name</th><th>Result</th><th>Lab Unit</th><th>NormalRange</th><th>Min</th><th>Max</th>";

  $filter = mysqli_query($link, "select * from lab WHERE `L_Set` !='SETNAME' ORDER BY `id` ASC  ");
  while ($labinfo = mysqli_fetch_array($filter))
  {
//	  $labset = $labinfo['L_Set'];
//	  $labset =  substr($labset,5);
//	  $labspec = $labinfo['L_specimen'];
	  $labnomr = $labinfo['normal_r'];
	  $labunit = $labinfo['Lrunit'];
	  $labmin = $labinfo['r_min'];
	  $labmax = $labinfo['r_max'];
	  $lsname = $labinfo['S_Name'];
	  $lname1 = $labinfo['S_Name']." [".$labinfo['L_Name']."]";
	  for ($i=0;$i<=$n;$i++)
	  {
	  if($lname1==$charsl[$i])
	    {
	      echo "<tr><td>";
//	      echo $i+1;
//	      echo "</td><td style='text-align:left;'>";
//	      echo $charsl[$i];
//	      echo "</td><td>";
//	      echo $labset;
//	      echo "</td><td>";
//	      echo $labspec;
//	      echo "</td><td>";
              echo "<a HREF=labhistory.php?labidin=".$lsname." >";
	      echo $lsname;
	      echo "</a>";
	      echo "</td><td>";
	      echo $charsr[$i]." ";
	      echo "</td><td>";
	      echo $labunit." ";
	      echo "</td><td>";
	      echo $labnomr;
	      echo "</td><td>";
	      echo $labmin;
	      echo "</td><td>";
	      echo $labmax;
	      echo "</td></tr>";
	    }
	  } 
 }
	echo "</table>";
	echo "</td></tr>";	
	}
	
	echo "</table>";
	}
        else
        {
        echo "ผลการตรวจ ".$labidin;
	echo "<table style=' width: 100%; text-align: center; margin-left: auto; margin-right: auto;' border='1' cellpadding='1' cellspacing='1'>";
	echo "<tr><th>Date</th><th>Result</th><th>Lab Unit</th><th>NormalRange</th><th>Min</th><th>Max</th>";
	while ($row_settings = mysqli_fetch_array($pin))
	{
//		echo "<tr><td>";
		$date = new DateTime($row_settings['date']);
		$sd = $date->format("d");
		$sm = $date->format("m");
		$sy = $date->format("Y");
		$bsy = $sy+543;
	$labidr=$row_settings['labid'];
	$labrsr=$row_settings['labresult'];
	if(!empty($labidr))
	{
	    $n = substr_count($labidr, ';');
	    //$str = 'hypertext;language;programming';
	    $charsl = preg_split('/;/', $labidr);
	}
	if(!empty($labrsr))
	{
	    $n = substr_count($labrsr, ';');
	    //$str = 'hypertext;language;programming';
	    $charsr = preg_split('/;/', $labrsr);
    //	print_r($charsr);
	}
	
        
  $filter = mysqli_query($link, "select * from lab WHERE `L_Set` !='SETNAME' ORDER BY `id` ASC  ");
  while ($labinfo = mysqli_fetch_array($filter))
  {
//	  $labset = $labinfo['L_Set'];
//	  $labset =  substr($labset,5);
//	  $labspec = $labinfo['L_specimen'];
	  $labnomr = $labinfo['normal_r'];
	  $labunit = $labinfo['Lrunit'];
	  $labmin = $labinfo['r_min'];
	  $labmax = $labinfo['r_max'];
	  $lsname = $labinfo['S_Name'];
	  $lname1 = $labinfo['S_Name']." [".$labinfo['L_Name']."]";
	  for ($i=0;$i<=$n;$i++)
	  {
	  if(($lname1==$charsl[$i]) AND ($lsname == $labidin))
	    {
	      echo "<tr><td>";
	      
		echo "<a HREF=labhistory.php?msg=".$row_settings['id']." >";
		echo $sd;
		echo " ";
		$m = $sm;
		switch ($m)
		{
		 case 1:
		 echo "มค";
		 break;
		 case 2:
		 echo "กพ";
		 break;
		 case 3:
		 echo "มีค";
		 break;
		 case 4:
		 echo "เมย";
		 break;
		 case 5:
		 echo "พค";
		 break;
		 case 6:
		 echo "มิย";
		 break;
		 case 7:
		 echo "กค";
		 break;
		 case 8:
		 echo "สค";
		 break;
		 case 9:
		 echo "กย";
		 break;
		 case 10:
		 echo "ตค";
		 break;
		 case 11:
		 echo "พย";
		 break;
		 case 12:
		 echo "ธค";
		 break;
		}
		echo " ";
		echo $bsy; //date("Y")+543;
		echo "</a>";
	      echo "</td><td>";
	      echo $charsr[$i]." ";
	      echo "</td><td>";
	      echo $labunit." ";
	      echo "</td><td>";
	      echo $labnomr;
	      echo "</td><td>";
	      echo $labmin;
	      echo "</td><td>";
	      echo $labmax;
	      echo "</td></tr>";
	    }
	  } 
 }
	}
	
	echo "</table>";
        }
	?>
	</div>
<!--end menu-->
</div>
</div>
</body>
</html>
