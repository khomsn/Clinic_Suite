<?php 
include '../../config/dbc.php';

page_protect();

$ptid = $_SESSION['patdesk'];
if(empty($ptid))
{
    $ptid = $_SESSION['patlab'];
}
$Patient_id = $ptid;
include '../../libs/opdxconnection.php';

$ptin = mysqli_query($linkopd, "SELECT * FROM patient_id where id='$ptid' ");
$pttable = "pt_".$ptid;

$pin = mysqli_query($linkopdx, "select MAX(id) from $pttable");
$rid = mysqli_fetch_array($pin);
$id = $rid[0];
$temptable = "tmp_".$ptid;
$checkcp = mysqli_query($link, "select prolab from $temptable ");
$havepl = mysqli_fetch_array($checkcp);

$labtable = "labtmp_".$ptid."_".$id;
$result = mysqli_query($link, "SELECT * FROM $labtable");
$num_rows=1;
while($getrs = mysqli_fetch_array($result))
{
    $Labid[$num_rows]=$getrs['Labid'];
    $num_rows=$num_rows+1;
}
//$num_rows = mysqli_num_rows($result);
if($_POST['save']=='Save')
{
    //get lab price and prog
    $result = mysqli_query($link, "SELECT * FROM $labtable");
    $i=1;
    while($rows= mysqli_fetch_array($result))
    {
        $id=$rows['Labid'];
        $filter = mysqli_query($link, "select price from lab WHERE `id`= $id"); 
        $prin =mysqli_fetch_array($filter);
        $price[$id] = $prin[0];
        $Labid[$i] = $id;
        $i=$i+1;
    }
    for($i=1;$i<$num_rows;$i++)
    {
        $labid = $Labid[$i];
        if($_POST[$labid])
        {
            mysqli_query($link, "UPDATE $labtable SET
            `price` = '0',
            `prog` = '1'
            WHERE Labid='$labid'
            ");
        }
        if(!$_POST[$labid])
        {
            mysqli_query($link, "UPDATE $labtable SET
            `price` = '$price[$labid]',
            `prog` = '0'
            WHERE Labid='$labid'
            ");
        }
    }
    //get lab price
    $result = mysqli_query($link, "SELECT price FROM $labtable"); 
    while($alpr = mysqli_fetch_array($result))
    {
        $alllabprice = $alpr['price']+$alllabprice;
    }
    //update $tmp table at lab price
    mysqli_query($link, "UPDATE $temptable SET  `licprice` = '$alllabprice'");
}
$title = "::Lab Investigation::";
include '../../main/header.php';
include '../../libs/popup.php';
echo "<script language=\"JavaScript\" type=\"text/javascript\" src=\"../../jscss/js/checkthemall.js\"></script>";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../jscss/css/table_alt_color.css\"/>";
include '../../main/bodyheader.php';

?>
<form method="post" action="investigation.php" name="formMultipleCheckBox1" id="formMultipleCheckBox">
<table width="100%" border="1" cellspacing="0" cellpadding="5" class="main">
  <tr><td>
		<h3 class="titlehdr">Lab Investigation Order</h3>
			<table style="text-align: left; width: 100%; height: 413px;" border="1" cellpadding="2" cellspacing="2"  class="forms">
				<tbody>
					<tr>
						<td style="width: 80%; vertical-align: middle;">
							<div style="text-align: center;">
							ชื่อ: &nbsp; 
							<a HREF="labhistory.php" onClick="return popup(this,'name','800','600','yes')" ><?php
							
							while ($row_settings = mysqli_fetch_array($ptin))
							{
								echo $row_settings['fname'];
								echo "&nbsp; &nbsp; &nbsp;"; 
								echo $row_settings['lname'];
							}				
							?></a>
							
							
						</td>
					</tr>	
					<tr>
						<td>
</div>
<div style="text-align: center;"><a HREF="labselect.php" onClick="return popup(this,'name','1000','600','yes');" >Lab Test</a>:
<?php if($havepl[0]) echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; <input type=submit name=save value=Save>";?>
<br>*! ก่อนจะทำการสั่ง Lab ใหม่ กรุณาตรวจว่า Lab เดิม บันทึกเข้า OPD card หรือยัง ไปที่ หน้า Lab
	    <table class='TFtable' style="width: 100%; text-align: center; margin-left: auto; margin-right: auto;" border="1" cellpadding="2" cellspacing="2">
		    <tr>
			    <th>No</th><th >ชื่อ</th><th>Set</th><th>Specimen</th><th>Result</th><th>Unit</th><th>Normal#</th>
			    <th>Min</th><th>Max</th><th><input name="checkAll" type="checkbox" id="checkAll" value="1" onclick="javascript:checkThemAll(this);" /><label for='checkAll'>InPro</label></th>
		    </tr>
		    <?php 
		    $n=0;
		    
		    $ptin = mysqli_query($link, "select * from $labtable ");
		    while ($row = mysqli_fetch_array($ptin))
		    { 
			    $n=$n+1;
			    echo "<tr><td>";
			    echo $n;
			    echo "</td><td style='text-align:left;'>";
			    echo $row['Labname'];
			    $labid = $row['Labid'];
			    $lab = mysqli_query($link, "select * from lab where id='$labid'");
			    while ($labinfo = mysqli_fetch_array($lab))
			    {
			    $labset = $labinfo['L_Set'];
			    $labspec = $labinfo['L_specimen'];
			    $labnomr = $labinfo['normal_r'];
			    $labunit = $labinfo['Lrunit'];
			    $labmin = $labinfo['r_min'];
			    $labmax = $labinfo['r_max'];
			    }
			    $labset =  substr($labset,5);
			    
			    echo "</td><td>";
			    echo $labset;
			    echo "</td><td>";
			    echo $labspec;
			    echo "</td><td>";
			    if(empty($row['Labresult']))
			    echo "Wait for result";
			    elseif(!empty($row['Labresult']))
			    echo $row['Labresult'];
			    echo "</td><td>";
			    echo $labunit;
			    echo "</td><td>";
			    echo $labnomr;
			    echo "</td><td>";
			    echo $labmin;
			    echo "</td><td>";
			    echo $labmax;
			    echo "</td><td>";
			    if($havepl[0])
			    {
			    echo "<input type=checkbox  id='checkBoxes' ";
			    if($row['prog']) echo " checked ";
			    echo " name=".$labid." value=1>";
			    }
			    echo "</td></tr>";
		    }
		    $pin = mysqli_query($linkopdx, "select * from $pttable where id=$id");
		    while($labrs=mysqli_fetch_array($pin))
		    {
		      $labidr=$labrs['labid'];
		      $labrsr=$labrs['labresult'];
		    }
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
			    $labset = $labinfo['L_Set'];
			    $labset =  substr($labset,5);
			    $labspec = $labinfo['L_specimen'];
			    $labnomr = $labinfo['normal_r'];
			    $labunit = $labinfo['Lrunit'];
			    $labmin = $labinfo['r_min'];
			    $labmax = $labinfo['r_max'];
		            $lname1 = $labinfo['S_Name']." [".$labinfo['L_Name']."]";
		            for ($i=0;$i<=$n;$i++)
		            {
			      $cond = $charsl[$i];
		            if($lname1==$charsl[$i])
			      {
				echo "<tr><td>";
				echo $i+1;
				echo "</td><td style='text-align:left;'>";
				echo $charsl[$i];
				echo "</td><td>";
				echo $labset;
				echo "</td><td>";
				echo $labspec;
				echo "</td><td>";
				echo $charsr[$i];
				echo "</td><td>";
				echo $labunit;
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
		   ?>
	    </table>	
	</div>
	</td></tr></tbody></table>
	</td></tr></table></form><br>
</body></html>
